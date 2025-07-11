<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Nilai2Service;
use Barryvdh\DomPDF\Facade\Pdf;

class SelectMitraTeladanExportController extends Controller
{
    public function export(Request $request)
    {
        $year = $request->input('year');
        $quarter = $request->input('quarter');
        $nilai2Service = app(Nilai2Service::class);
        // Check if all Mitra Teladan are finalized
        $allFinalized = \App\Models\MitraTeladan::where('year', $year)
            ->where('quarter', $quarter)
            ->where(function($q) {
                $q->whereNull('status_phase_2')->orWhere('status_phase_2', '!=', 1);
            })
            ->count() === 0;
        if (!$allFinalized) {
            return view('exports.nilai2-not-finalized', [
                'year' => $year,
                'quarter' => $quarter,
            ]);
        }
        $report = $nilai2Service->getReportData($year, $quarter);
        $reportData = $report['data'];
        $aspekDescriptions = $report['aspekDescriptions'];

        return Pdf::loadView('exports.nilai2-report', [
            'reportData' => $reportData,
            'year' => $year,
            'quarter' => $quarter,
            'aspekDescriptions' => $aspekDescriptions,
        ])->setPaper('a4', 'landscape')->download('nilai2-report.pdf');
    }
}
