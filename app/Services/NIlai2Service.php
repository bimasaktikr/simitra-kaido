<?php
// app/Services/EmployeeSelectionService.php
namespace App\Services;

use App\Models\Mitra;
use App\Models\MitraTeladan;
use App\Models\Nilai2;
use App\Models\Team;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Nilai2Service
{
    /**
     * Save a new Nilai2 record.
     *
     * @param array $data
     * @return bool
     */
    public function saveNilai2(array $data)
    {
        try {
            DB::beginTransaction();

            Nilai2::create($data);

            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Error saving Nilai2: ' . $th->getMessage(), ['exception' => $th]);
            return false;
        }
    }

    public function getAverageRating($id)
    {
        return Nilai2::where('mitra_teladan_id', $id)
                        ->avg('rerata');
    }

    public function getEmployeeDone($id)
    {
        return Nilai2::where('mitra_teladan_id', $id)
                        ->pluck('employee_id');
    }

    public function getStatus($id)
    {
        // Retrieve is_final status mapped by team_penilai_id
        return Nilai2::where('employee_id', $id)
            ->pluck('is_final', 'employee_id')
            ->map(function($isFinal) {
                return (int) $isFinal; // Ensure the value is returned as an integer (1 or 0)
            })
            ->toArray();
    }

    public function checkFinal($id)
    {
        // Find the MitraTeladan by its ID
        $mitraTeladan = MitraTeladan::findOrFail($id);

        // Check if all related Nilai2 entries are final
        $allFinal = $mitraTeladan->nilai2->every(function ($nilai2) {
            return $nilai2->is_final;
        });

        // Return true if all are final, otherwise false
        return $allFinal;
    }

    public function userHasNilai2($mitraTeladanId, $userId)
    {
        return Nilai2::where('mitra_teladan_id', $mitraTeladanId)
            ->where('user_id', $userId)
            ->exists();
    }

    protected static $aspekDescriptions = [
        1 => 'Adaptif',
        2 => 'Akuntabel',
        3 => 'Amanah',
        4 => 'Disiplin',
        5 => 'Harmonis',
        6 => 'Inovatif',
        7 => 'Kolaboratif',
        8 => 'Kompeten',
        9 => 'Loyal',
        10 => 'Pelayanan',
    ];

    /**
     * Get Nilai2 report data for export, grouped by MitraTeladan and Team.
     */
    public function getReportData($year = null, $quarter = null)
    {
        $query = MitraTeladan::with(['team', 'mitra', 'nilai2.user.employee'])
            ->when($year, fn($q) => $q->where('year', $year))
            ->when($quarter, fn($q) => $q->where('quarter', $quarter));

        $mitraTeladans = $query->get();

        $reportData = [];
        foreach ($mitraTeladans as $mitraTeladan) {
            $teamName = $mitraTeladan->team?->name ?? '-';
            $mitraName = $mitraTeladan->mitra?->name ?? '-';
            $nilai2List = [];
            foreach ($mitraTeladan->nilai2 as $nilai2) {
                $employeeName = $nilai2->user?->employee?->name ?? $nilai2->user?->name ?? '-';
                $nilai2List[] = [
                    'employeeName' => $employeeName,
                    'aspek1' => $nilai2->aspek1,
                    'aspek2' => $nilai2->aspek2,
                    'aspek3' => $nilai2->aspek3,
                    'aspek4' => $nilai2->aspek4,
                    'aspek5' => $nilai2->aspek5,
                    'aspek6' => $nilai2->aspek6,
                    'aspek7' => $nilai2->aspek7,
                    'aspek8' => $nilai2->aspek8,
                    'aspek9' => $nilai2->aspek9,
                    'aspek10' => $nilai2->aspek10,
                ];
            }
            $reportData[] = [
                'mitraName' => $mitraName,
                'teamName' => $teamName,
                'nilai2List' => $nilai2List,
            ];
        }
        return [
            'data' => $reportData,
            'aspekDescriptions' => self::$aspekDescriptions,
        ];
    }
}
