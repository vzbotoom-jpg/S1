<?php

return [
    'default_provider' => env('AI_PROVIDER', 'anthropic'),
    
    'anthropic_api_key' => env('ANTHROPIC_API_KEY'),
    'anthropic_model' => env('ANTHROPIC_MODEL', 'claude-3-sonnet-20240229'),
    
    'openai_api_key' => env('OPENAI_API_KEY'),
    'openai_model' => env('OPENAI_MODEL', 'gpt-4-turbo-preview'),
    
    'api_url' => env('AI_API_URL', 'https://api.anthropic.com/v1/messages'),
    'model' => env('AI_MODEL', 'claude-3-sonnet-20240229'),
    'max_tokens' => env('AI_MAX_TOKENS', 4096),
    'temperature' => env('AI_TEMPERATURE', 0.7),
];