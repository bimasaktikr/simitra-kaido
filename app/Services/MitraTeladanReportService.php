<?php

namespace App\Services;

use App\Models\MitraTeladan;
use App\Models\Nilai2;
use Illuminate\Support\Collection;

class MitraTeladanReportService
{
    public const FASE_1_ASPEK = [
        1 => 'Kualitas Data',
        2 => 'Ketepatan Waktu',
        3 => 'Pemahaman Kerja'
    ];

    public const FASE_2_ASPEK = [
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
     * Get top 5 mitra teladan for specific quarter and year
     */
    public function getTopMitra(int $year, int $quarter, int $limit = 5): Collection
    {
        return MitraTeladan::with(['mitra', 'team'])
            ->where('year', $year)
            ->where('quarter', $quarter)
            ->where('status_phase_2', true)
            ->whereNotNull('avg_rating_2')
            ->orderByDesc('avg_rating_2')
            ->orderByDesc('surveys_count') // Tiebreaker: lebih banyak penilai
            ->limit($limit)
            ->get();
    }

    /**
     * Get detailed breakdown for specific mitra teladan
     */
    public function getMitraDetail(int $mitraTeladanId): array
    {
        $mitraTeladan = MitraTeladan::with(['mitra', 'team', 'nilai2'])
            ->findOrFail($mitraTeladanId);

        // Calculate average per aspek from all nilai2 records
        $nilai2Records = Nilai2::where('mitra_teladan_id', $mitraTeladanId)
            ->where('is_final', true) // Only count finalized assessments
            ->get();

        $aspekAverages = [];
        
        if ($nilai2Records->count() > 0) {
            for ($i = 1; $i <= 10; $i++) {
                $aspekAverages[$i] = round(
                    $nilai2Records->avg("aspek{$i}"), 
                    2
                );
            }
        }

        return [
            'mitra_teladan' => $mitraTeladan,
            'aspek_averages' => $aspekAverages,
            'total_penilai' => $nilai2Records->count(),
            'fase_2_aspek' => self::FASE_2_ASPEK,
        ];
    }

    /**
     * Get report metadata
     */
    public function getReportMetadata(int $year, int $quarter): array
    {
        $quarterNames = [
            1 => 'I (Januari - Maret)',
            2 => 'II (April - Juni)',
            3 => 'III (Juli - September)',
            4 => 'IV (Oktober - Desember)',
        ];

        return [
            'year' => $year,
            'quarter' => $quarter,
            'quarter_name' => $quarterNames[$quarter] ?? $quarter,
            'generated_at' => now()->format('d F Y'),
            'generated_time' => now()->format('H:i:s'),
        ];
    }

    /**
     * Get signatory data
     */
    public function getSignatory(): array
    {
        return [
            'city' => 'Malang',
            'date' => now()->format('d F Y'),
            'position' => 'Kepala BPS Kota Malang',
            'name' => 'Umar Sjaifudin, M.Si',
            'nip' => 'NIP. 197012161997031005',
        ];
    }
}