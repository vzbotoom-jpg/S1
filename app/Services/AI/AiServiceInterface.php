<?php

namespace App\Services\AI;

interface AiServiceInterface
{
    public function sendMessage(string $message, array $context = []): string;
    public function sendConversation(array $messages, array $context = []): string;
    public function setSystemInstruction(string $instruction): self;
    public function setGenerationConfig(array $config): self;
    public function getLastError(): ?string;
}