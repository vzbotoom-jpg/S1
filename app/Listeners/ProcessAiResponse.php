<?php

namespace App\Listeners;

use App\Events\AiResponseReceived;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class ProcessAiResponse implements ShouldQueue
{
    public function __construct() {}

    /**
     * Fired after AiResponseReceived event.
     * Use this for post-processing: logging, analytics, notifications, etc.
     */
    public function handle(AiResponseReceived $event): void
    {
        Log::info('AI Response delivered', [
            'user_id'    => $event->userId,
            'session_id' => $event->sessionId,
            'length'     => strlen($event->message),
        ]);

        // Add more post-processing here:
        // - Save usage analytics
        // - Send email notification
        // - Update user stats
        // - Content moderation / filtering
    }

    public function failed(AiResponseReceived $event, \Throwable $exception): void
    {
        Log::error('ProcessAiResponse listener failed', [
            'user_id' => $event->userId,
            'error'   => $exception->getMessage(),
        ]);
    }
}