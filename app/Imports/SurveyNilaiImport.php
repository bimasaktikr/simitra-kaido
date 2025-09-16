<?php

namespace App\Imports;

use App\Models\Nilai1;
use App\Models\Transaction;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SurveyNilaiImport implements ToCollection, WithHeadingRow
{
    public function __construct(public int $surveyId) {}

    /** Our headings are on row 3 (A3:E3) */
    public function headingRow(): int
    {
        return 3;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Expected headers exactly: transaction_id, mitra_name, aspek1, aspek2, aspek3
            $transactionId = (int) ($row['transaction_id'] ?? 0);
            if (! $transactionId) {
                continue;
            }

            $tx = Transaction::query()
                ->where('id', $transactionId)
                ->where('survey_id', $this->surveyId)
                ->first();

            if (! $tx) {
                // Skip rows that don't belong to this survey
                continue;
            }

            // Normalize numeric inputs (accept blank)
            $a1 = $this->numOrNull($row['aspek1'] ?? null);
            $a2 = $this->numOrNull($row['aspek2'] ?? null);
            $a3 = $this->numOrNull($row['aspek3'] ?? null);

            // Build payload
            $payload = [
                'aspek1' => $a1,
                'aspek2' => $a2,
                'aspek3' => $a3,
            ];

            if ($a1 !== null && $a2 !== null && $a3 !== null) {
                $payload['rerata'] = round(($a1 + $a2 + $a3) / 3, 2);
            }

            // Upsert nilai for this transaction
            Nilai1::updateOrCreate(
                ['transaction_id' => $tx->id],
                $payload
            );
        }
    }

    private function numOrNull($v): ?float
    {
        if ($v === '' || $v === null) return null;
        // Excel often sends numeric cells as strings; cast safely
        return is_numeric($v) ? (float) $v : null;
    }
}
