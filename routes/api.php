<?php

use App\Http\Controllers\Chatbot\ChatbotController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/ask-ai', [ChatbotController::class, 'askAI']);
Route::get('/recommendation', [ChatbotController::class, 'getRecommendations']);