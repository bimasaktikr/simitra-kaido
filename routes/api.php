<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SurveyEventWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Route::post('/login', [AuthController::class, 'login']);

// Survey Event Webhook Routes
Route::prefix('webhooks')->group(function () {
    Route::post('/survey-event-recommendations', [SurveyEventWebhookController::class, 'receiveRecommendations']);
    Route::post('/survey-event-complete', [SurveyEventWebhookController::class, 'markComplete']);
    Route::get('/health', [SurveyEventWebhookController::class, 'healthCheck']);
});
