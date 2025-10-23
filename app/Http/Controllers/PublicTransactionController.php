<?php

namespace App\Http\Controllers;

use App\Models\Transaction;

class PublicTransactionController extends Controller
{
    public function show(string $uuid)
    {
    $tx = Transaction::with(['mitra', 'survey.masterSurvey'])->where('uuid', $uuid)->first();

        if (! $tx) {
            abort(404);
        }

        $mitra  = $tx->mitra;
        $survey = $tx->survey;

        // masking sederhana
        $maskedEmail = $mitra?->email
            ? preg_replace('/(^.).*(@.*$)/', '$1****$2', $mitra->email)
            : '—';

        $maskedSobatId = $mitra?->sobat_id
            ? preg_replace('/(\d{3})\d+(\d{2})/', '$1******$2', (string) $mitra->sobat_id)
            : '—';

        return view('mitra.check-transaction', [
            'transaction'   => $tx,
            'mitra'         => $mitra,
            'survey'        => $survey,
            'maskedEmail'   => $maskedEmail,
            'maskedSobatId' => $maskedSobatId,
            'qrUrl'         => $tx->qr_path ? asset('storage/' . $tx->qr_path) : null,
        ]);
    }
}
