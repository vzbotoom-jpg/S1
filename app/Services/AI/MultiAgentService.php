<?php

namespace App\Services\AI;

use App\Services\Context\ContextBuilder;

class MultiAgentService
{
    public function __construct(
        protected GeminiService $gemini,
        protected AgentService $toolAgent
    ) {}

    public function handle(string $message, array $history): string
    {
        // 1. Planner — tentukan tool yang dibutuhkan
        $plan = $this->planner($message);

        // 2. Jalankan tool (tanpa AI call tambahan)
        $toolData = null;
        if ($plan !== 'none') {
            $toolData = $this->toolAgent->runTool($plan, $message);
        }

        // Fallback jika tool tidak menghasilkan data
        if (!$toolData) {
            $toolData = "Tidak ada data real-time tersedia.";
        }

        // 3. Build context dengan data tool
        $context = (new ContextBuilder())
            ->setUserMessage($message)
            ->setToolData($toolData)
            ->build();

        // 4. Responder — 1 kali AI call untuk jawaban final
        return $this->responder($history, $context);
    }

    protected function planner(string $message): string
    {
        // Daftar tools yang tersedia (sinkron dengan AgentService)
        $prompt = "
Tentukan tool yang dibutuhkan untuk menjawab pesan berikut.

Pesan: \"$message\"

Pilihan tool:
- crypto    : harga cryptocurrency (Bitcoin, Ethereum, dll)
- weather   : cuaca suatu kota
- news      : berita terbaru dari NewsAPI
- websearch : pencarian internet umum via Google (gunakan ini jika tidak ada tool spesifik yang cocok, atau user butuh informasi terkini/fakta/event)
- none      : tidak butuh data eksternal, jawab dari pengetahuan sendiri

ATURAN:
- Jawab HANYA satu kata: crypto, weather, news, websearch, atau none.
- Jangan tambahkan penjelasan apapun.
- Jika pertanyaan menyebut 'terbaru', 'terkini', 'hari ini', atau butuh fakta spesifik → gunakan websearch.
- Jika ragu antara news dan websearch → pilih websearch.
";

        $decision = strtolower(trim($this->gemini->sendMessage($prompt)));

        // Ambil kata pertama saja
        $valid = ['crypto', 'weather', 'news', 'websearch', 'none'];
        $first = strtok($decision, " \n");

        return in_array($first, $valid) ? $first : 'none';
    }

    protected function responder(array $history, string $context): string
    {
        return $this->gemini->sendConversation($history, [
            'system_prompt' => $context
        ]);
    }
}