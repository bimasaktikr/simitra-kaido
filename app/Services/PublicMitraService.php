<?php

namespace App\Services;

use App\Models\Mitra;

class PublicMitraService
{
    /**
     * Ambil data Mitra + masking + URL QR tersimpan.
     */
    public function getMitraByUuid(string $uuid): ?array
    {
        // Eager load surveys dengan relasi masterSurvey dan team
        $mitra = Mitra::with([
            'surveys' => function($query) {
                $query->latest(); // urutkan dari terbaru
            }, 
            'surveys.masterSurvey', 
            'surveys.team'
        ])
        ->where('uuid', $uuid)
        ->first();

        if (!$mitra) {
            return null;
        }

        // Ambil survey terbaru dari collection
        $latestSurvey = $mitra->surveys->first();
        
        // Ambil QR URL dari accessor model
        $qrUrl = $mitra->qr_url;

        return [
            'data' => $mitra,
            'masked_nik' => $mitra->nik ? $this->maskNik($mitra->nik) : null,
            'masked_email' => $mitra->email ? $this->maskEmail($mitra->email) : null,
            'masked_sobat_id' => $mitra->sobat_id ? $this->maskSobatId($mitra->sobat_id) : null,
            'qr_url' => $qrUrl,
            'latest_survey' => $latestSurvey,
        ];
    }

    /**
     * Masking NIK: tampilkan 4 awal & 2 akhir, sisanya bintang.
     */
    private function maskNik(string $nik): string
    {
        if (strlen($nik) < 8) {
            return str_repeat('*', max(strlen($nik) - 2, 0)) . substr($nik, -2);
        }
        return substr($nik, 0, 4) . str_repeat('*', max(strlen($nik) - 6, 0)) . substr($nik, -2);
    }

    /**
     * Masking Email: ganti karakter sebelum @ (kecuali pertama) dengan *.
     */
    private function maskEmail(string $email): string
    {
        return preg_replace('/(?<=.).(?=[^@]*?@)/', '*', $email);
    }

    /**
     * Masking SOBAT ID: tampilkan 3 awal & 3 akhir, sisanya bintang.
     */
    private function maskSobatId(string $sobatId): string
    {
        $sobatId = (string) $sobatId; // pastikan string
        $length = strlen($sobatId);
        
        if ($length <= 6) {
            // Jika terlalu pendek, tampilkan setengahnya
            return substr($sobatId, 0, ceil($length / 2)) . str_repeat('*', floor($length / 2));
        }
        
        // Tampilkan 3 awal dan 3 akhir
        return substr($sobatId, 0, 3) . str_repeat('*', $length - 6) . substr($sobatId, -3);
    }
}
