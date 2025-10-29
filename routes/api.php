<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TrainingWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Route::post('/login', [AuthController::class, 'login']);

// Training Webhook Routes (dipanggil oleh FastAPI setelah Airflow training selesai)
Route::prefix('webhooks')->group(function () {
    Route::post('/training-complete', [TrainingWebhookController::class, 'handleTrainingComplete']);
    Route::post('/ml-training-complete', [TrainingWebhookController::class, 'handleMLTrainingComplete']);
    Route::get('/health', [TrainingWebhookController::class, 'healthCheck']);
});
