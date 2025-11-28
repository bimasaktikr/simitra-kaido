<?php
// app/Services/ProfilMitraService.php

namespace App\Services;

use App\Models\Mitra;
use App\Models\Transaction;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProfilMitraService
{
    /**
     * Get mitra with all transaction details
     */
    public function getMitraProfile(int $mitraId): ?Mitra
    {
        return Mitra::with([
            'transactions.survey.masterSurvey',
            'transactions.nilai'
        ])->find($mitraId);
    }

    /**
     * Get mitra transactions for table display
     */
    public function getMitraTransactions(int $mitraId): Collection
    {
        return Transaction::where('mitra_id', $mitraId)
            ->with(['survey.masterSurvey', 'mitra', 'nilai'])
            ->orderByDesc('survey.year')
            ->orderByDesc('survey.triwulan')
            ->get();
    }

    /**
     * Get mitra summary statistics
     */
    public function getMitraSummary(int $mitraId): array
    {
        $mitra = $this->getMitraProfile($mitraId);

        if (!$mitra) {
            return [
                'total_kegiatan' => 0,
                'total_target' => 0,
                'total_pendapatan' => 0,
                'avg_rerata' => 0,
            ];
        }

        $totalKegiatan = $mitra->transactions()->count();
        $totalTarget = $mitra->transactions()->sum('target');
        
        // Calculate total pendapatan (target * rate)
        $totalPendapatan = $mitra->transactions->sum(function($transaction) {
            return $transaction->target * $transaction->rate;
        });

        // Get average rerata from nilai1s
        $avgRerata = DB::table('transactions')
            ->join('nilai1s', 'transactions.id', '=', 'nilai1s.transaction_id')
            ->where('transactions.mitra_id', $mitraId)
            ->avg('nilai1s.rerata');

        return [
            'total_kegiatan' => $totalKegiatan,
            'total_target' => $totalTarget,
            'total_pendapatan' => $totalPendapatan,
            'avg_rerata' => $avgRerata ? round($avgRerata, 2) : 0,
        ];
    }

    /**
     * Generate CSV data for single mitra
     */
    public function generateSingleMitraCSV(int $mitraId): string
    {
        $mitra = $this->getMitraProfile($mitraId);

        if (!$mitra) {
            throw new \Exception("Mitra tidak ditemukan");
        }

        $output = fopen('php://temp', 'r+');
        
        // Header
        fputcsv($output, [
            'Nama Mitra',
            'Sobat ID',
            'Email',
            'Nama Kegiatan',
            'Kode Survey',
            'Triwulan',
            'Tahun',
            'Target',
            'Rate',
            'Total Bayar',
            'Aspek 1',
            'Aspek 2',
            'Aspek 3',
            'Rerata',
            'Status Penilaian',
        ]);

        // Data
        if ($mitra->transactions->isEmpty()) {
            fputcsv($output, [
                $mitra->name,
                $mitra->sobat_id,
                $mitra->email,
                '-',
                '-',
                '-',
                '-',
                0,
                0,
                0,
                '-',
                '-',
                '-',
                '-',
                'Belum ada kegiatan',
            ]);
        } else {
            foreach ($mitra->transactions as $transaction) {
                $survey = $transaction->survey;
                $masterSurvey = $survey->masterSurvey ?? null;
                $nilai = $transaction->nilai;
                $totalBayar = $transaction->target * $transaction->rate;

                fputcsv($output, [
                    $mitra->name,
                    $mitra->sobat_id,
                    $mitra->email,
                    $masterSurvey?->name ?? '-',
                    $masterSurvey?->code ?? '-',
                    $survey->triwulan ? 'Q' . $survey->triwulan : '-',
                    $survey->year ?? '-',
                    $transaction->target ?? 0,
                    $transaction->rate ?? 0,
                    $totalBayar,
                    $nilai?->aspek1 ?? '-',
                    $nilai?->aspek2 ?? '-',
                    $nilai?->aspek3 ?? '-',
                    $nilai?->rerata ?? '-',
                    $nilai ? 'Sudah Dinilai' : 'Belum Dinilai',
                ]);
            }
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return $csv;
    }

    /**
     * Generate CSV data for all mitras
     */
    public function generateAllMitrasCSV(): string
    {
        Log::info("Starting generateAllMitrasCSV");

        $mitras = Mitra::with([
            'transactions.survey.masterSurvey',
            'transactions.nilai'
        ])->get();

        Log::info("Total mitras to export: " . $mitras->count());

        $output = fopen('php://temp', 'r+');
        
        // Header
        fputcsv($output, [
            'Nama Mitra',
            'Sobat ID',
            'Email',
            'Nama Kegiatan',
            'Kode Survey',
            'Triwulan',
            'Tahun',
            'Target',
            'Rate',
            'Total Bayar',
            'Aspek 1',
            'Aspek 2',
            'Aspek 3',
            'Rerata',
            'Status Penilaian',
        ]);

        // Data rows
        foreach ($mitras as $mitra) {
            $transactions = $mitra->transactions;
            
            if ($transactions->isEmpty()) {
                fputcsv($output, [
                    $mitra->name,
                    $mitra->sobat_id,
                    $mitra->email,
                    '-',
                    '-',
                    '-',
                    '-',
                    0,
                    0,
                    0,
                    '-',
                    '-',
                    '-',
                    '-',
                    'Belum ada kegiatan',
                ]);
            } else {
                foreach ($transactions as $transaction) {
                    $survey = $transaction->survey;
                    $masterSurvey = $survey->masterSurvey ?? null;
                    $nilai = $transaction->nilai;
                    $totalBayar = $transaction->target * $transaction->rate;

                    fputcsv($output, [
                        $mitra->name,
                        $mitra->sobat_id,
                        $mitra->email,
                        $masterSurvey?->name ?? '-',
                        $masterSurvey?->code ?? '-',
                        $survey->triwulan ? 'Q' . $survey->triwulan : '-',
                        $survey->year ?? '-',
                        $transaction->target ?? 0,
                        $transaction->rate ?? 0,
                        $totalBayar,
                        $nilai?->aspek1 ?? '-',
                        $nilai?->aspek2 ?? '-',
                        $nilai?->aspek3 ?? '-',
                        $nilai?->rerata ?? '-',
                        $nilai ? 'Sudah Dinilai' : 'Belum Dinilai',
                    ]);
                }
            }
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        Log::info("CSV generation completed");

        return $csv;
    }

    /**
     * Get all mitras for dropdown
     */
    public function getAllMitrasForDropdown(): Collection
    {
        return Mitra::orderBy('name')
            ->select('id', 'name', 'sobat_id')
            ->get()
            ->mapWithKeys(function ($mitra) {
                return [$mitra->id => "{$mitra->name} ({$mitra->sobat_id})"];
            });
    }

    /**
     * Search mitras by keyword
     */
    public function searchMitras(string $search, int $limit = 50): Collection
    {
        return Mitra::where('name', 'like', "%{$search}%")
            ->orWhere('sobat_id', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->orderBy('name')
            ->limit($limit)
            ->get()
            ->mapWithKeys(function ($mitra) {
                return [$mitra->id => "{$mitra->name} ({$mitra->sobat_id})"];
            });
    }
}