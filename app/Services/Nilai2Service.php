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
            $aspekSums = array_fill(1, 10, 0);
            $rerataSum = 0;
            $count = 0;
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
                    'rerata' => $nilai2->rerata,
                ];
                for ($i = 1; $i <= 10; $i++) {
                    $aspekSums[$i] += $nilai2->{'aspek'.$i};
                }
                $rerataSum += $nilai2->rerata;
                $count++;
            }
            // Calculate averages
            if ($count > 0) {
                $averageRow = [
                    'employeeName' => 'average',
                ];
                for ($i = 1; $i <= 10; $i++) {
                    $averageRow['aspek'.$i] = round($aspekSums[$i] / $count, 2);
                }
                $averageRow['rerata'] = round($rerataSum / $count, 2);
                $nilai2List[] = $averageRow;
            }
            $reportData[] = [
                'mitraName' => $mitraName,
                'teamName' => $teamName,
                'nilai2List' => $nilai2List,
            ];
        }

        // Build mitra ranking
        $mitraRanking = [];
        foreach ($mitraTeladans as $mitraTeladan) {
            $mitraName = $mitraTeladan->mitra?->name ?? '-';
            $rerataSum = 0;
            $count = 0;
            foreach ($mitraTeladan->nilai2 as $nilai2) {
                $rerataSum += $nilai2->rerata;
                $count++;
            }
            $avgRerata = $count > 0 ? round($rerataSum / $count, 2) : 0;
            $mitraRanking[] = [
                'mitraName' => $mitraName,
                'avgRerata' => $avgRerata,
            ];
        }
        // Sort by avgRerata descending
        usort($mitraRanking, function($a, $b) {
            return $b['avgRerata'] <=> $a['avgRerata'];
        });
        // Add ranking number
        foreach ($mitraRanking as $i => &$row) {
            $row['rank'] = $i + 1;
        }
        unset($row);

        return [
            'data' => $reportData,
            'aspekDescriptions' => self::$aspekDescriptions,
            'mitraRanking' => $mitraRanking,
        ];
    }

        /**
     * Get top 5 Mitra Teladan with detailed info for official document
     */
    public function getTopMitraForDocument(int $year, int $quarter, ?int $teamId = null, int $limit = 5)
    {
        $query = MitraTeladan::with(['mitra', 'team', 'nilai2'])
            ->where('year', $year)
            ->where('quarter', $quarter)
            ->where('status_phase_2', true); // Only finalized

        if ($teamId) {
            $query->where('team_id', $teamId);
        }

        $mitras = $query->get();

        // Calculate total average (avg_rating_1 + avg_rating_2) / 2
        $ranked = $mitras->map(function ($mt) {
            $totalAvg = (($mt->avg_rating_1 ?? 0) + ($mt->avg_rating_2 ?? 0)) / 2;
            return [
                'mitra_teladan_id' => $mt->id,
                'mitra_name' => $mt->mitra->name ?? '-',
                'team_name' => $mt->team->name ?? '-',
                'avg_rating_1' => $mt->avg_rating_1,
                'avg_rating_2' => $mt->avg_rating_2,
                'total_average' => round($totalAvg, 2),
                'surveys_count' => $mt->surveys_count,
                'nilai2_count' => $mt->nilai2->count(),
            ];
        });

        // Sort by total_average descending
        $sorted = $ranked->sortByDesc('total_average')->values();

        // Add ranking number
        return $sorted->take($limit)->map(function ($item, $index) {
            $item['rank'] = $index + 1;
            return $item;
        });
    }

    /**
     * Get detailed breakdown per aspek for lampiran document
     */
    public function getDetailedAspekBreakdown(int $mitraTeladanId)
    {
        $mitraTeladan = MitraTeladan::with(['mitra', 'nilai2.user'])->findOrFail($mitraTeladanId);

        $nilai2List = $mitraTeladan->nilai2->where('is_final', true);

        if ($nilai2List->isEmpty()) {
            return null;
        }

        // Calculate average per aspek
        $aspekAverages = [];
        for ($i = 1; $i <= 10; $i++) {
            $aspekAverages["aspek{$i}"] = round($nilai2List->avg("aspek{$i}"), 2);
        }

        // Get individual scores
        $individualScores = $nilai2List->map(function ($nilai2) {
            return [
                'penilai_name' => $nilai2->user->name ?? '-',
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
                'rerata' => $nilai2->rerata,
            ];
        });

        return [
            'mitra_name' => $mitraTeladan->mitra->name ?? '-',
            'team_name' => $mitraTeladan->team->name ?? '-',
            'aspek_averages' => $aspekAverages,
            'individual_scores' => $individualScores,
            'total_penilai' => $nilai2List->count(),
            'overall_average' => round($nilai2List->avg('rerata'), 2),
        ];
    }

    /**
     * Get data for official document export
     */
    public function getOfficialDocumentData(int $year, int $quarter, ?int $teamId = null)
    {
        // Get top 5 mitra
        $topMitras = $this->getTopMitraForDocument($year, $quarter, $teamId, 5);

        // Get detailed breakdown for each top mitra
        $detailedBreakdowns = [];
        foreach ($topMitras as $mitra) {
            $breakdown = $this->getDetailedAspekBreakdown($mitra['mitra_teladan_id']);
            if ($breakdown) {
                $detailedBreakdowns[] = $breakdown;
            }
        }

        return [
            'top_mitras' => $topMitras,
            'detailed_breakdowns' => $detailedBreakdowns,
            'aspek_descriptions' => self::$aspekDescriptions,
            'year' => $year,
            'quarter' => $quarter,
            'quarter_name' => $this->getQuarterName($quarter),
        ];
    }

    /**
     * Get quarter name in Indonesian
     */
    public function getQuarterName(int $quarter): string
    {
        return match($quarter) {
            1 => 'I (Januari - Maret)',
            2 => 'II (April - Juni)',
            3 => 'III (Juli - September)',
            4 => 'IV (Oktober - Desember)',
            default => '-',
        };
    }

    /**
     * Check if all Mitra Teladan in period are finalized
     */
    public function checkAllFinalized(int $year, int $quarter, ?int $teamId = null): bool
    {
        $query = MitraTeladan::where('year', $year)
            ->where('quarter', $quarter);

        if ($teamId) {
            $query->where('team_id', $teamId);
        }

        $total = $query->count();
        if ($total === 0) {
            return false; // No data
        }

        $finalized = $query->where('status_phase_2', true)->count();

        return $total === $finalized;
    }
}
