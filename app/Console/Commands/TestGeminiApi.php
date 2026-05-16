<?php

namespace App\Console\Commands;

use App\Services\AI\GeminiService;
use Illuminate\Console\Command;

class TestGeminiApi extends Command
{
    protected $signature = 'test:gemini-api {message? : Pesan yang akan dikirim ke AI}';
    protected $description = 'Test Gemini API connection and response';

    public function handle(): int
    {
        $this->info('Testing Gemini API connection...');
        
        $message = $this->argument('message') ?? 'Hai, bagaimana kabar?';
        
        try {
            $gemini = app(GeminiService::class);
            
            $this->info('API Key configured: ' . (config('services.gemini.api_key') ? '✓' : '✗'));
            $this->info('Model: ' . config('services.gemini.model'));
            
            $this->info("\nSending message: \"$message\"");
            $this->info('Waiting for response...');
            
            $response = $gemini->sendMessage($message);
            
            $this->info("\n✓ Success!");
            $this->line("\nResponse:");
            $this->line($response);
            
            return self::SUCCESS;
            
        } catch (\Exception $e) {
            $error = $gemini->getLastError() ?? $e->getMessage();
            $this->error('✗ Error: ' . $error);
            
            // Check if it's a quota error
            if (str_contains($error, '429') || str_contains($error, 'quota') || str_contains($error, 'RESOURCE_EXHAUSTED')) {
                $this->warn("\n⚠️  Free tier quota telah habis!");
                $this->line("\n📚 Solusi:");
                $this->line("1. Upgrade ke Paid Plan: https://console.cloud.google.com/billing");
                $this->line("2. Atau buat API key baru dengan fresh quota");
                $this->line("3. Implementasi caching untuk mengurangi API calls");
            }
            
            return self::FAILURE;
        }
    }
}
