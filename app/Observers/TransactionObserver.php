<?php

namespace App\Observers;

use App\Models\Transaction;
use Illuminate\Support\Str;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;

class TransactionObserver
{
    public function creating(Transaction $transaction): void
    {
        if (empty($transaction->uuid)) {
            $transaction->uuid = (string) Str::uuid();
        }

        $fileName = "qr_{$transaction->uuid}.png";
        $relative = "qr/{$fileName}";
        $absolute = storage_path("app/public/{$relative}");

        if (! is_dir(dirname($absolute))) {
            @mkdir(dirname($absolute), 0775, true);
        }

        $url = url("/check/mitra/{$transaction->uuid}");

        $qrCode = new QrCode(
            data: $url,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::Medium,
            size: 300,
            margin: 2,
            roundBlockSizeMode: RoundBlockSizeMode::Margin
        );

        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        $result->saveToFile($absolute);

        $transaction->qr_path = $relative;
    }
}
