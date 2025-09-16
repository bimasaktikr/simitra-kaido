<?php

use App\Exports\SurveyNilaiTemplateExport;
use App\Livewire\ListMitraTeladan;
use App\Models\Survey;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

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

Route::get('/surveys/{survey}/penilaian-template', function (Survey $survey) {
    $filename = 'Penilaian_Template_'.$survey->code.'_'.$survey->year.'.xlsx';
    return Excel::download(new SurveyNilaiTemplateExport($survey->id), $filename);
})->name('survey.penilaian.template.download')->middleware(['auth']); // add guards as needed

Route::get('/export-nilai2-report', [\App\Http\Controllers\SelectMitraTeladanExportController::class, 'export'])->name('export.nilai2.report');

// Route::middleware(['auth', 'verified']) // Or adjust as needed
//     ->get('/mitra-teladans', ListMitraTeladan::class)
//     ->name('mitra-teladan');
