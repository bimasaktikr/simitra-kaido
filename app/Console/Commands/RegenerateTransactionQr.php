<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;

class RegenerateTransactionQr extends Command
{
    protected $signature = 'transaction:regenerate-qr {--force : regenerate semua termasuk yang sudah punya file}';
    protected $description = 'Regenerate QR files for transactions';

    public function handle(): int
    {
        $query = Transaction::query();
        if (! $this->option('force')) {
            $query->whereNull('qr_path');
        }

        $count = $query->count();
        if ($count === 0) {
            $this->info('Tidak ada transaksi yang perlu diregenerasi.');
            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        $query->chunkById(500, function ($chunk) use ($bar) {
            foreach ($chunk as $tx) {
                if (empty($tx->uuid)) {
                    $tx->uuid = (string) Str::uuid();
                    $tx->save();
                }

                $file = "qr/qr_{$tx->uuid}.png";
                $path = storage_path("app/public/{$file}");
                @mkdir(dirname($path), 0775, true);

                $url = url("/check/mitra/{$tx->uuid}");

                $qrCode = new QrCode(
                    data: $url,
                    encoding: new Encoding('UTF-8'),
                    errorCorrectionLevel: ErrorCorrectionLevel::Medium,
                    size: 540,
                    margin: 2,
                    roundBlockSizeMode: RoundBlockSizeMode::Margin,
                );

                $writer = new PngWriter();
                $result = $writer->write($qrCode);
                $result->saveToFile($path);

                $tx->qr_path = $file;
                $tx->save();

                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine();
        $this->info('QR berhasil di generate dan UUID tersimpan.');
        return self::SUCCESS;
    }
}
