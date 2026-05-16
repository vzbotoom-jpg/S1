<?php

return [

    /*
    |--------------------------------------------------------------------------
    | System Prompt
    |--------------------------------------------------------------------------
    | Instruksi yang selalu dikirim ke AI sebelum percakapan dimulai.
    | Gunakan ini untuk menentukan persona, batasan, dan gaya bicara AI.
    */
    'system_prompt' => env(
        'CHATBOT_SYSTEM_PROMPT',
        'Kamu adalah asisten AI yang ramah, cerdas, dan membantu. ' .
        'Jawab dengan bahasa yang jelas dan mudah dipahami. ' .
        'Jika tidak tahu jawabannya, katakan dengan jujur.'
    ),

    /*
    |--------------------------------------------------------------------------
    | Batas Riwayat Percakapan
    |--------------------------------------------------------------------------
    | Berapa pesan terakhir yang dikirim ke AI sebagai konteks.
    | Semakin banyak = lebih kontekstual, tapi lebih banyak token terpakai.
    */
    'history_limit' => (int) env('CHATBOT_HISTORY_LIMIT', 20),

    /*
    |--------------------------------------------------------------------------
    | Panjang Pesan Maksimum
    |--------------------------------------------------------------------------
    | Batas karakter pesan yang dikirim user (validasi di ChatRequest).
    */
    'max_message_length' => (int) env('CHATBOT_MAX_MESSAGE_LENGTH', 4000),

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    | Jumlah pesan maksimum yang bisa dikirim user per menit.
    */
    'rate_limit_per_minute' => (int) env('CHATBOT_RATE_LIMIT', 15),

    /*
    |--------------------------------------------------------------------------
    | Queue Name
    |--------------------------------------------------------------------------
    | Nama queue yang digunakan untuk memproses job AI.
    */
    'queue' => env('CHATBOT_QUEUE', 'default'),

    /*
    |--------------------------------------------------------------------------
    | Fitur Streaming
    |--------------------------------------------------------------------------
    | Aktifkan streaming SSE untuk respons AI yang lebih cepat terasa.
    | Membutuhkan setup route /chat/stream tersendiri.
    */
    'streaming_enabled' => (bool) env('CHATBOT_STREAMING', false),

    /*
    |--------------------------------------------------------------------------
    | Nama & Persona Bot
    |--------------------------------------------------------------------------
    */
    'bot_name'   => env('CHATBOT_NAME', 'AI Assistant'),
    'bot_avatar' => env('CHATBOT_AVATAR', null), // URL avatar opsional


    
];