<?php

namespace App\Jobs;

use App\Models\Survey;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateMultiIdCardPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $surveyId, public string $outputPath) {}

    public function handle(): void
    {
        $survey = Survey::with(['transactions.qr', 'transactions.mitra', 'masterSurvey'])
            ->findOrFail($this->surveyId);

        // Filter only transactions with QR codes
        $transactions = $survey->transactions->filter(function ($tx) {
            return $tx->qr && $tx->qr->qr_path;
        });

        if ($transactions->isEmpty()) {
            throw new \RuntimeException("Tidak ada transaksi dengan QR code untuk survey ID {$this->surveyId}");
        }

        // Build HTML for all cards
        $htmlPages = [];
        foreach ($transactions as $tx) {
            // Prepare data for each card (same as single card)
            $qrUrl = $tx->qr->qr_path ? storage_path('app/public/' . $tx->qr->qr_path) : null;
            $fotoPath = $tx->mitra && $tx->mitra->photo 
                ? storage_path('app/public/' . $tx->mitra->photo) 
                : null;

            $htmlPages[] = view('exports.id-card-single', [
                'tx' => $tx,
                'mitra' => $tx->mitra,
                'survey' => $survey,
                'qrUrl' => $qrUrl,
                'fotoPath' => $fotoPath,
            ])->render();
        }

        // Combine all HTML pages
        $combinedHtml = implode('', $htmlPages);

        // Create the PDF
        $pdf = Pdf::loadHTML($combinedHtml);

        // Set paper to 54 x 85.6 mm (converted to points: 1 inch = 25.4 mm, 1 in = 72 points)
        $w = (54 / 25.4) * 72;     // 54mm in points
        $h = (85.6 / 25.4) * 72;   // 85.6mm in points
        $pdf->setPaper([0, 0, $w, $h], 'portrait');

        // Ensure directory exists
        @mkdir(dirname($this->outputPath), 0775, true);

        // Save the PDF
        $pdf->save($this->outputPath);
    }
}
