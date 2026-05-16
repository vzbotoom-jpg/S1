<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;

class GeminiService implements AiServiceInterface
{
    protected string $apiKey;
    protected string $model;
    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta';
    protected string $systemInstruction = '';
    protected array $generationConfig = [
        'temperature' => 0.7,
        'maxOutputTokens' => 2048,
    ];
    protected ?string $lastError = null;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
        if (!$this->apiKey) {
            throw new \Exception('GEMINI_API_KEY is not configured. Please check your .env file.');
        }
        $this->model = config('services.gemini.model', 'gemini-1.5-flash');
    }

    /**
     * Set custom API key (from user settings)
     */
    public function setApiKey(string $apiKey): void
    {
        if (empty($apiKey)) {
            throw new \Exception('API key cannot be empty');
        }
        $this->apiKey = $apiKey;
    }

    /**
     * Set system instruction for the model
     */
    public function setSystemInstruction(string $instruction): self
    {
        $this->systemInstruction = $instruction;
        return $this;
    }

    /**
     * Set generation config
     */
    public function setGenerationConfig(array $config): self
    {
        $this->generationConfig = array_merge($this->generationConfig, $config);
        return $this;
    }

    /**
     * Get last error message
     */
    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    /**
     * Send single message to Gemini
     */
    public function sendMessage(string $message, array $context = []): string
    {
        $contents = [
            [
                'role'  => 'user',
                'parts' => [['text' => $message]],
            ]
        ];

        return $this->executeRequest($contents);
    }

    /**
     * Send conversation history to Gemini
     * Interface signature: sendConversation(array $messages, array $context = []): string
     */
    public function sendConversation(array $messages, array $context = []): string
    {
        // Extract system prompt from context if provided
        $systemPrompt = $context['system_prompt'] ?? '';
        
        $contents = collect($messages)->map(fn($msg) => [
            'role'  => $msg['role'] === 'assistant' ? 'model' : 'user',
            'parts' => [['text' => $msg['content']]],
        ])->toArray();

        if ($systemPrompt) {
            return $this->executeRequestWithSystemPrompt($contents, $systemPrompt);
        }

        return $this->executeRequest($contents);
    }

    /**
     * Alias for backward compatibility with ProcessChatMessage
     */
    public function chat(array $history, string $systemPrompt = ''): string
    {
        return $this->sendConversation($history, $systemPrompt);
    }

    /**
     * Execute request to Gemini API with retry on rate limit
     */
    protected function executeRequest(array $contents): string
    {
        $maxRetries = 3;
        $retryDelay = 2; // seconds
        $attempt = 0;

        while ($attempt < $maxRetries) {
            try {
                $payload = [
                    'contents' => $contents,
                    'generationConfig' => $this->generationConfig,
                ];

                if ($this->systemInstruction) {
                    $payload['systemInstruction'] = [
                        'parts' => [['text' => $this->systemInstruction]]
                    ];
                }

                $response = Http::withoutVerifying()
                    ->timeout(30)
                    ->post(
                        "{$this->baseUrl}/models/{$this->model}:generateContent?key={$this->apiKey}",
                        $payload
                    );

                // Handle 429 (Rate Limited) responses
                if ($response->status() === 429) {
                    $attempt++;
                    if ($attempt < $maxRetries) {
                        \Log::warning("GeminiService: Rate limited (attempt $attempt/$maxRetries), retrying in {$retryDelay}s...");
                        sleep($retryDelay);
                        $retryDelay *= 2; // Exponential backoff
                        continue;
                    }
                }

                if ($response->failed()) {
                    $this->lastError = 'Gemini API error: ' . $response->body();
                    \Log::error($this->lastError);
                    throw new \Exception($this->lastError);
                }

                $result = $response->json('candidates.0.content.parts.0.text');
                return $result ?? 'Maaf, saya tidak dapat merespons saat ini.';

            } catch (\Exception $e) {
                $this->lastError = $e->getMessage();
                \Log::error('GeminiService error: ' . $this->lastError);
                throw $e;
            }
        }

        throw new \Exception('Max retries exceeded for Gemini API');
    }

    /**
     * Execute request with system prompt and retry logic
     */
    protected function executeRequestWithSystemPrompt(array $contents, string $systemPrompt): string
    {
        $maxRetries = 3;
        $retryDelay = 2;
        $attempt = 0;

        while ($attempt < $maxRetries) {
            try {
                $payload = [
                    'contents' => $contents,
                    'generationConfig' => $this->generationConfig,
                    'systemInstruction' => [
                        'parts' => [['text' => $systemPrompt]]
                    ],
                ];

                $response = Http::withoutVerifying()
                    ->timeout(30)
                    ->post(
                        "{$this->baseUrl}/models/{$this->model}:generateContent?key={$this->apiKey}",
                        $payload
                    );

                // Handle 429 (Rate Limited) responses
                if ($response->status() === 429) {
                    $attempt++;
                    if ($attempt < $maxRetries) {
                        \Log::warning("GeminiService: Rate limited with system prompt (attempt $attempt/$maxRetries), retrying in {$retryDelay}s...");
                        sleep($retryDelay);
                        $retryDelay *= 2; // Exponential backoff
                        continue;
                    }
                }

                if ($response->failed()) {
                    $this->lastError = 'Gemini API error: ' . $response->body();
                    \Log::error($this->lastError);
                    throw new \Exception($this->lastError);
                }

                $result = $response->json('candidates.0.content.parts.0.text');
                return $result ?? 'Maaf, saya tidak dapat merespons saat ini.';

            } catch (\Exception $e) {
                $this->lastError = $e->getMessage();
                \Log::error('GeminiService error: ' . $this->lastError);
                throw $e;
            }
        }

        throw new \Exception('Max retries exceeded for Gemini API with system prompt');
    }

    public function setModel(string $model): void
{
    $this->model = $model;
}
}
