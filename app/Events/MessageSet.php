<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSet implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly int    $userId,
        public readonly string $role,
        public readonly string $content,
        public readonly string $sessionId,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("chat.{$this->userId}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.set';
    }

    public function broadcastWith(): array
    {
        return [
            'role'       => $this->role,
            'content'    => $this->content,
            'session_id' => $this->sessionId,
            'timestamp'  => now()->toISOString(),
        ];
    }
}