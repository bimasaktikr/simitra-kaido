<?php

namespace App\Exports;

use App\Models\Survey;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionIdCardExport
{
    public function __construct(private Survey $survey) {}

    public function download()
    {
        $survey = $this->survey->load([
            'transactions.mitra',
        ]);

        $transactions = $survey->transactions;

        $pdf = Pdf::loadView('exports.id-cards', [
            'survey'       => $survey,
            'transactions' => $transactions,
        ])->setPaper('a4', 'portrait');

    $timestamp = date('Y-m-d_His');
    $filename = 'ID_'.$survey->code.'_'.$timestamp.'.pdf';

        return $pdf->download($filename);
    }
}
