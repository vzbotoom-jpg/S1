<?php

namespace App\DTOs;

class ChatMessageDTO
{
    public function __construct(
        public readonly string $role,
        public readonly string $content,
        public readonly ?string $timestamp = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            role: $data['role'],
            content: $data['content'],
            timestamp: $data['timestamp'] ?? now()->toISOString(),
        );
    }

    public function toArray(): array
    {
        return [
            'role'      => $this->role,
            'content'   => $this->content,
            'timestamp' => $this->timestamp,
        ];
    }

    public function toGeminiFormat(): array
    {
        // Gemini uses "user" and "model" roles
        return [
            'role'  => $this->role === 'assistant' ? 'model' : 'user',
            'parts' => [['text' => $this->content]],
        ];
    }
}