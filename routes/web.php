<?php

use App\Exports\SurveyNilaiTemplateExport;
use App\Livewire\ListMitraTeladan;
use App\Models\Survey;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\SelectMitraTeladanExportController;
use App\Http\Controllers\PublicTransactionController;
use App\Http\Controllers\SurveyExportController;

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
})->name('survey.penilaian.template.download')->middleware(['auth']);

Route::get('/export-nilai2-report', [SelectMitraTeladanExportController::class, 'export'])->name('export.nilai2.report');

Route::get('/check/mitra/{uuid}', [PublicTransactionController::class, 'show'])->name('cek.mitra');

Route::post('/reviews', [PublicTransactionController::class, 'storeReview'])->name('review.store');

Route::middleware(['auth'])->group(function () {
    Route::post('/surveys/{survey}/export/id-cards/batch', [SurveyExportController::class, 'startBatch'])->name('survey.export.idcards.batch');
    Route::get('/jobs/batch/{id}/status', [SurveyExportController::class, 'batchStatus'])->name('jobs.batch.status');
    Route::get('/surveys/{survey}/export/id-cards/zip', [SurveyExportController::class, 'zip'])->name('survey.export.idcards.zip');
    Route::get('/transactions/{transaction}/id-card/download', [SurveyExportController::class, 'downloadSingle'])->name('transaction.idcard.download');
    Route::get('/surveys/{survey}/export/id-cards', [SurveyExportController::class, 'idCards'])->name('survey.export.idcards');
});
