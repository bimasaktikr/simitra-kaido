<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionQr;

class PublicTransactionController extends Controller
{
    public function show(string $uuid)
    {
        $qr = TransactionQr::with(['transaction.mitra', 'transaction.survey.masterSurvey'])
            ->where('uuid', $uuid)
            ->first();

        if (! $qr || ! $qr->transaction) {
            abort(404);
        }

        $tx     = $qr->transaction;
        $mitra  = $tx->mitra;
        $survey = $tx->survey;

        // no masking: pass raw mitra data to view

        $qrPath = $tx->qr?->qr_path ? asset('storage/' . $tx->qr->qr_path) : null;

        return view('mitra.check-transaction.transaction', [
            'transaction'   => $tx,
            'mitra'         => $mitra,
            'survey'        => $survey,
            'qrUrl'         => $qrPath,
        ]);
    }
}
