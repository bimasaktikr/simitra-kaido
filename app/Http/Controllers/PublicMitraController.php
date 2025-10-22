<?php

namespace App\Http\Controllers;

use App\Services\PublicMitraService;

class PublicMitraController extends Controller
{
    protected PublicMitraService $service;

    public function __construct(PublicMitraService $service)
    {
        $this->service = $service;
    }

    public function show(string $uuid)
    {
        $result = $this->service->getMitraByUuid($uuid);

        if (!$result) {
            return view('mitra.cek-mitra', ['notFound' => true]);
        }

        return view('mitra.cek-mitra', [
            'mitra' => $result['data'],
            'maskedNik' => $result['masked_nik'],
            'maskedEmail' => $result['masked_email'],
            'maskedSobatId' => $result['masked_sobat_id'],
            'qrUrl' => $result['qr_url'],
            'latestSurvey' => $result['latest_survey'],
        ]);
    }
}
