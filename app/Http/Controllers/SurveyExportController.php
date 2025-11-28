<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateSurveyIdCards;
use App\Jobs\GenerateTransactionIdCard;
use App\Jobs\GenerateTransactionQr;
use App\Jobs\GenerateMultiIdCardPdf;
use App\Models\Survey;
use App\Models\Transaction;
use Illuminate\Bus\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Response;

class SurveyExportController extends Controller
{
    public function startBatch(Request $request, Survey $survey)
    {
        $survey->load(['transactions.qr']);

        $jobs = [];
        foreach ($survey->transactions as $t) {
            if (! $t->qr || ! $t->qr->qr_path) {
                $jobs[] = new GenerateTransactionQr($t->id);
            }
            $jobs[] = new GenerateTransactionIdCard($t->id);
        }

        $batch = Bus::batch($jobs)
            ->name("Generate ID Cards: {$survey->code} {$survey->year}")
            ->allowFailures()
            ->dispatch();

        return response()->json(['batch_id' => $batch->id]);
    }

    public function batchStatus(string $id)
    {
        /** @var Batch $batch */
        $batch = Bus::findBatch($id);
        if (! $batch) {
            return response()->json(['found' => false], 404);
        }

        return response()->json([
            'found'          => true,
            'name'           => $batch->name,
            'total_jobs'     => $batch->totalJobs,
            'pending_jobs'   => $batch->pendingJobs,
            'processed_jobs' => $batch->processedJobs(),
            'progress'       => $batch->progress(), // 0..100
            'failed_jobs'    => $batch->failedJobs,
            'cancelled'      => $batch->cancelled(),
            'finished'       => $batch->finished(),
        ]);
    }

    public function zip(Survey $survey)
    {
        $survey->load(['transactions.qr', 'transactions.mitra', 'masterSurvey']);
        $force = request()->boolean('force');
        $tmp = storage_path('app/tmp');
        @mkdir($tmp, 0775, true);
        $tmpZip = $tmp . '/IDCards_' . $survey->code . '_' . $survey->year . '_' . time() . '.zip';

        $zip = new \ZipArchive();
        if ($zip->open($tmpZip, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            abort(500, 'Cannot create ZIP');
        }

        // Generate individual ID cards (1 PDF = 1 card)
        foreach ($survey->transactions as $t) {
            $qrAbs = $t->qr?->qr_path ? storage_path('app/public/' . $t->qr->qr_path) : null;
            if ($force || ! $t->qr || ! $t->qr->qr_path || !is_file($qrAbs)) {
                Bus::dispatchSync(new GenerateTransactionQr($t->id));
                $t->refresh();
                $qrAbs = $t->qr?->qr_path ? storage_path('app/public/' . $t->qr->qr_path) : null;
            }

            $idAbs = $t->qr?->id_card_path ? storage_path('app/public/' . $t->qr->id_card_path) : null;
            if ($force || ! $t->qr?->id_card_path || !is_file($idAbs)) {
                Bus::dispatchSync(new GenerateTransactionIdCard($t->id));
                $t->refresh();
                $idAbs = $t->qr?->id_card_path ? storage_path('app/public/' . $t->qr->id_card_path) : null;
            }

            if (is_file($idAbs)) {
                $safe = preg_replace('/[^A-Za-z0-9_\-]+/', '_', $t->mitra?->name ?? 'MITRA');
                $zip->addFile($idAbs, "ID_{$safe}_{$t->qr->uuid}.pdf");
            }
        }

        $surveyName = $survey->masterSurvey?->name ?? $survey->name ?? $survey->code;
        $safeSurveyName = preg_replace('/[^A-Za-z0-9_\-]+/', '_', $surveyName);
        $multiPdfPath = $tmp . '/IDCards_All_' . $survey->code . '_' . $survey->year . '_' . time() . '.pdf';
        try {
            Bus::dispatchSync(new GenerateMultiIdCardPdf($survey->id, $multiPdfPath));
            if (is_file($multiPdfPath)) {
                $zip->addFile($multiPdfPath, "ID_{$safeSurveyName}.pdf");
            }
        } catch (\Exception $e) {
            \Log::error('Failed to generate multi-page ID card PDF: ' . $e->getMessage());
        }

        $zip->close();

        $response = response()->download($tmpZip, 'IDCards_' . $safeSurveyName . '_' . $survey->year . '.zip')
            ->deleteFileAfterSend(true);

        if (isset($multiPdfPath) && is_file($multiPdfPath)) {
            @unlink($multiPdfPath);
        }

        return $response;
    }

    public function idCards(Survey $survey)
    {
        return $this->zip($survey);
    }

    public function downloadSingle(Request $request, Transaction $transaction)
    {
        $transaction->load(['qr', 'mitra', 'survey']);

        $force = $request->boolean('force');

        // Pastikan QR ada
        $qrAbs = $transaction->qr?->qr_path ? storage_path('app/public/' . $transaction->qr->qr_path) : null;
        if ($force || ! $transaction->qr || ! $transaction->qr->qr_path || !is_file($qrAbs)) {
            dispatch_sync(new GenerateTransactionQr($transaction->id));
            $transaction->refresh();
            $qrAbs = $transaction->qr?->qr_path ? storage_path('app/public/' . $transaction->qr->qr_path) : null;
        }

        // Pastikan ID Card ada
        $idAbs = $transaction->qr?->id_card_path ? storage_path('app/public/' . $transaction->qr->id_card_path) : null;
        if ($force || ! $transaction->qr?->id_card_path || !is_file($idAbs)) {
            dispatch_sync(new GenerateTransactionIdCard($transaction->id));
            $transaction->refresh();
            $idAbs = $transaction->qr?->id_card_path ? storage_path('app/public/' . $transaction->qr->id_card_path) : null;
        }

        abort_unless(is_file($idAbs), 404);

        $safeName = preg_replace('/[^A-Za-z0-9_\-]+/', '_', $transaction->mitra?->name ?? 'MITRA');
        $filename = "ID_{$safeName}_{$transaction->qr->uuid}.pdf";

        return response()->download($idAbs, $filename);
    }
}
