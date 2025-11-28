<?php

namespace App\Jobs;

use App\Models\Transaction;
use App\Models\TransactionQr;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;

class GenerateTransactionQr implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $transactionId) {}

    public function handle(): void
    {
        $tx = Transaction::with('qr')->findOrFail($this->transactionId);

        $qr = $tx->qr ?: new TransactionQr([
            'transaction_id' => $tx->id,
            'uuid'           => (string) Str::uuid(),
        ]);

        $qr->qr_status = 'processing';
        $qr->save();

        $url  = url("/check/mitra/{$qr->uuid}");
        $file = "qr/qr_{$qr->uuid}.png";
        $path = storage_path("app/public/{$file}");
        if (! is_dir(dirname($path))) {
            @mkdir(dirname($path), 0775, true);
        }

        try {
            $result = Builder::create()
                ->writer(new PngWriter())
                ->data($url)
                ->encoding(new Encoding('UTF-8'))
                ->size(540)   // px
                ->margin(2)   // px
                ->build();

            $result->saveToFile($path);

            $qr->qr_path   = $file;
            $qr->qr_status = 'done';
            $qr->save();
        } catch (\Throwable $e) {
            $qr->qr_status = 'failed';
            $qr->save();
            throw $e;
        }
    }
}
