<?php

namespace App\Providers;

use App\Services\AI\AgentService;
use App\Services\AI\AiServiceInterface;
use App\Services\AI\GeminiService;
use App\Services\AI\MultiAgentService;
use Illuminate\Support\ServiceProvider;

class AiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind interface ke GeminiService
        $this->app->bind(AiServiceInterface::class, function ($app) {
            return $app->make(GeminiService::class); // pakai singleton dari AppServiceProvider
        });

        // Bind GeminiService (fallback jika di-inject langsung)
        $this->app->bind(GeminiService::class, function ($app) {
            return $app->make(GeminiService::class);
        });

        // Bind MultiAgentService
        $this->app->bind(MultiAgentService::class, function ($app) {
            return new MultiAgentService(
                $app->make(GeminiService::class),  // singleton
                $app->make(AgentService::class),   // singleton dengan tools terdaftar
            );
        });
    }

    public function boot(): void
    {
        //
    }
}