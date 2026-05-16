<?php

namespace App\Services\AI;

abstract class AiService implements AiServiceInterface
{
    protected string $apiKey;
    protected ?string $systemInstruction = null;
    protected array $generationConfig = [];
    protected ?string $lastError = null;
    
    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->setDefaultConfig();
    }
    
    protected function setDefaultConfig(): void
    {
        $this->generationConfig = [
            'temperature' => 0.7,
            'maxOutputTokens' => 2048,
            'topP' => 0.95,
        ];
    }
    
    public function setSystemInstruction(string $instruction): self
    {
        $this->systemInstruction = $instruction;
        return $this;
    }
    
    public function setGenerationConfig(array $config): self
    {
        $this->generationConfig = array_merge($this->generationConfig, $config);
        return $this;
    }
    
    public function getLastError(): ?string
    {
        return $this->lastError;
    }
    
    protected function setLastError(?string $error): void
    {
        $this->lastError = $error;
    }
}