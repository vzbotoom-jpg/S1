<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\GeminiController;
use App\Http\Controllers\ConversationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {

    // Current user
    Route::get('/user', fn(Request $request) => $request->user());

    // Conversations
    Route::apiResource('conversations', ConversationController::class)->except(['store']);
    Route::delete('/conversations', [ConversationController::class, 'bulkDestroy']);

    // Chat messages
    Route::post('/chat/{conversation}/message', [ChatController::class, 'sendMessage'])
        ->middleware('chat.ratelimit');
});

// Gemini API endpoint
Route::post('/gemini/chat', [GeminiController::class, 'chat']);
