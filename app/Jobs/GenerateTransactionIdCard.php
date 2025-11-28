<?php

namespace App\Jobs;

use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateTransactionIdCard implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $transactionId) {}

    public function handle(): void
    {
        $tx = Transaction::with(['qr', 'mitra', 'survey.masterSurvey'])->findOrFail($this->transactionId);

        if (! $tx->qr || ! $tx->qr->qr_path) {
            throw new \RuntimeException("QR belum tersedia untuk transaksi ID {$tx->id}");
        }

        $tx->qr->id_card_status = 'processing';
        $tx->qr->save();

        // prepare absolute paths for resources used by DomPDF (it needs absolute filesystem paths)
        $qrPath = storage_path('app/public/' . $tx->qr->qr_path);
        $fotoPath = $tx->mitra && $tx->mitra->photo
            ? storage_path('app/public/' . $tx->mitra->photo)
            : null;

        // create the PDF view data and load it first, then set an explicit paper size in points
        $pdf = Pdf::loadView('exports.id-card-single', [
            'tx'      => $tx,
            'mitra'   => $tx->mitra,
            'survey'  => $tx->survey,
            'qrUrl'   => $qrPath,
            'fotoPath'=> $fotoPath,
        ]);

        // set paper to 54 x 85.6 mm (converted to points: 1 inch = 25.4 mm, 1 in = 72 points)
        $w = (54 / 25.4) * 72;     // 54mm in points
        $h = (85.6 / 25.4) * 72;   // 85.6mm in points
        $pdf->setPaper([0, 0, $w, $h], 'portrait'); // 1 halaman kartu

        $safeName = preg_replace('/[^A-Za-z0-9_\-]+/','_', $tx->mitra?->name ?? 'MITRA');
        $dir = "idcards/{$tx->survey?->code}_{$tx->survey?->year}";
        $file = "{$dir}/ID_{$safeName}_{$tx->qr->uuid}.pdf";
        $path = storage_path("app/public/{$file}");
        @mkdir(dirname($path), 0775, true);

        $pdf->save($path);

        $tx->qr->id_card_path = $file;
        $tx->qr->id_card_status = 'done';
        $tx->qr->save();
    }
}
