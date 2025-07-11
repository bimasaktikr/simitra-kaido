<?php

use App\Livewire\ListMitraTeladan;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

Route::get('/mitra/template/download', function () {
    $headers = [
        'id_sobat', 'nama', 'jenis_kelamin', 'email',
        'pendidikan', 'tgl_lahir', 'target', 'rate',
        'Kualitas Data', 'Ketepatan Waktu', 'Pemahaman Pengetahuan Kerja',
    ];

    return Response::streamDownload(function () use ($headers) {
        $handle = fopen('php://output', 'w');
        fputcsv($handle, $headers);
        fclose($handle);
    }, 'mitra-template.csv');
})->name('mitra.template.download');

Route::get('/export-nilai2-report', [\App\Http\Controllers\SelectMitraTeladanExportController::class, 'export'])->name('export.nilai2.report');

// Route::middleware(['auth', 'verified']) // Or adjust as needed
//     ->get('/mitra-teladans', ListMitraTeladan::class)
//     ->name('mitra-teladan');
