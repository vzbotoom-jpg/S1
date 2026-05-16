<?php

namespace App\Providers;

use App\Services\AI\AgentService;
use App\Services\AI\GeminiService;
use App\Services\Tools\CryptoTool;
use App\Services\Tools\NewsTool;
use App\Services\Tools\WeatherTool;
use App\Services\Tools\WebSearchTool;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Singleton GeminiService
        $this->app->singleton(GeminiService::class, function ($app) {
            return new GeminiService();
        });

        // Singleton AgentService dengan semua tools terdaftar
        $this->app->singleton(AgentService::class, function ($app) {
            $agent = new AgentService($app->make(GeminiService::class));

            $agent->registerTool(new CryptoTool());
            $agent->registerTool(new NewsTool());
            $agent->registerTool(new WebSearchTool());
            // $agent->registerTool(new WeatherTool()); // Uncomment jika sudah diisi

            return $agent;
        });
    }

    public function boot(): void
    {
        // Set locale Carbon ke Bahasa Indonesia
        Carbon::setLocale('id');
    }
}