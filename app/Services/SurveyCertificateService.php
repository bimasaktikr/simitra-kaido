<?php

namespace App\Services;

use App\Models\Survey;
use App\Models\Transaction;
use Illuminate\Support\Collection;

class SurveyCertificateService
{
    /**
     * Get certificate data for specific transaction/mitra in survey
     */
    public function getCertificateData(Transaction $transaction): array
    {
        $survey = $transaction->survey;
        $mitra = $transaction->mitra;

        $quarterNames = [
            1 => "I (Januari - Maret)",
            2 => "II (April - Juni)",
            3 => "III (Juli - September)",
            4 => "IV (Oktober - Desember)",
        ];

        return [
            "mitra_name" => $mitra->name,
            "team_name" => $survey->team->name,
            "survey_name" => $survey->masterSurvey->name ?? "-",
            "survey_code" => $survey->masterSurvey->code ?? "-",
            "target" => $transaction->target,
            "achievement" => $transaction->target, // atau field lain jika ada
            "score" => $transaction->nilai->rerata ?? "-",
            "aspek1" => $transaction->nilai->aspek1 ?? "-",
            "aspek2" => $transaction->nilai->aspek2 ?? "-",
            "aspek3" => $transaction->nilai->aspek3 ?? "-",
            "year" => $survey->year,
            "quarter" => $survey->triwulan,
            "quarter_name" =>
                $quarterNames[$survey->triwulan] ?? $survey->triwulan,
            "period_text" => "Kuartal {$quarterNames[$survey->triwulan]} Tahun {$survey->year}",
            "certificate_number" => $this->generateCertificateNumber(
                $transaction->id,
                $survey->year,
                $survey->triwulan,
            ),
            "issue_date" => now()->format("d F Y"),
            "signatory" => $this->getSignatory(),
        ];
    }

    /**
     * Generate certificate number
     */
    private function generateCertificateNumber(
        int $transactionId,
        int $year,
        int $quarter,
    ): string {
        return sprintf("SV/%04d/Q%d/%d", $transactionId, $quarter, $year);
    }

    /**
     * Get signatory data
     */
    public function getSignatory(): array
    {
        return [
            "city" => "Malang",
            "date" => now()->format("d F Y"),
            "position" => "Kepala BPS Kota Malang",
            "name" => "Umar Sjaifudin, M.Si",
            "nip" => "NIP. 197012161997031005",
        ];
    }

    /**
     * Get all transactions with scores for a survey
     */
    public function getScoredTransactions(Survey $survey): Collection
    {
        return Transaction::query()
            ->with(["mitra", "nilai"])
            ->where("survey_id", $survey->id)
            ->whereHas("nilai")
            ->get();
    }
}
