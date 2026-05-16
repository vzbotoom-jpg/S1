<?php

namespace App\Jobs;

use App\DTOs\ChatMessageDTO;
use App\Enums\MessageRole;
use App\Events\AiResponseReceived;
use App\Models\Conversation;
use App\Models\User;
use App\Services\GeminiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessChatMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;
    public int $timeout = 60;

    public function __construct(
        private Conversation $conversation,
        private User $user,
    ) {}

    public function handle(GeminiService $gemini): void
    {
        try {
            // Build full history for context
            $history = $this->conversation
                ->messages()
                ->orderBy('created_at')
                ->get()
                ->map(fn($m) => new ChatMessageDTO(
                    role: $m->role instanceof \BackedEnum ? $m->role->value : $m->role,
                    content: $m->content,
                ))
                ->toArray();

            $systemPrompt = $this->user->settings['system_prompt']
                ?? config('services.gemini.system_prompt',
                    'Kamu adalah asisten AI yang helpful, ramah, dan berbicara dalam Bahasa Indonesia.');

            // Get AI response
            $aiResponse = $gemini->chat($history, $systemPrompt);

            // Save to database
            $message = $this->conversation->messages()->create([
                'user_id' => $this->user->id,
                'role'    => MessageRole::Assistant,
                'content' => $aiResponse,
            ]);

            // Broadcast to frontend via WebSocket
            event(new AiResponseReceived(
                userId: $this->user->id,
                message: $aiResponse,
                sessionId: $this->conversation->session_id,
            ));

        } catch (\Throwable $e) {
            Log::error('ProcessChatMessage failed', [
                'conversation_id' => $this->conversation->id,
                'user_id'         => $this->user->id,
                'error'           => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Job ProcessChatMessage permanently failed', [
            'conversation_id' => $this->conversation->id,
            'error'           => $exception->getMessage(),
        ]);
    }
}