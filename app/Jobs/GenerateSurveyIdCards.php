<?php

namespace App\Jobs;

use App\Models\Survey;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateSurveyIdCards implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public function __construct(public int $surveyId) {}

    public function handle(): void
    {
        $survey = Survey::with(['transactions.qr','transactions.mitra'])->findOrFail($this->surveyId);

        foreach ($survey->transactions as $t) {
            // QR dulu
            if (! $t->qr || ! $t->qr->qr_path) {
                dispatch(new GenerateTransactionQr($t->id));
            }
            // ID Card
            dispatch(new GenerateTransactionIdCard($t->id));
        }
    }
}
