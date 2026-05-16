<?php

namespace App\Services\AI;

use App\Services\Tools\ToolInterface;
use Illuminate\Support\Str;

class AgentService
{
    /**
     * @var ToolInterface[]
     */
    protected array $tools = [];

    public function __construct(
        protected GeminiService $gemini
    ) {}

    /**
     * Daftarkan tool ke agent
     */
    public function registerTool(ToolInterface $tool): self
    {
        $this->tools[$tool->name()] = $tool;
        return $this;
    }

    /**
     * Pilih tool yang tepat berdasarkan pesan user (pakai AI)
     * Return nama tool atau 'none'
     */
    public function decideTool(string $message): string
    {
        if (empty($this->tools)) {
            return 'none';
        }

        // Buat deskripsi tools untuk AI
        $toolList = collect($this->tools)
            ->map(fn($tool, $name) => "- $name: {$tool->description()}")
            ->implode("\n");

        $prompt = "
Kamu adalah decision engine. Tugasmu HANYA memilih tool yang tepat.

Pesan user: \"$message\"

Tools yang tersedia:
$toolList
- none: Tidak butuh tool eksternal, jawab dari pengetahuan sendiri.

ATURAN:
- Jawab HANYA dengan nama tool (satu kata): crypto, weather, news, atau none.
- Jangan tambahkan penjelasan apapun.
- Jika ragu, pilih none.
";

        $decision = strtolower(trim($this->gemini->sendMessage($prompt)));

        // Validasi — pastikan hanya nama tool valid yang diterima
        $validTools = array_merge(array_keys($this->tools), ['none']);

        // Ambil kata pertama saja (jaga-jaga AI tambah kalimat)
        $firstWord = Str::before($decision, ' ');

        return in_array($firstWord, $validTools) ? $firstWord : 'none';
    }

    /**
     * Jalankan tool yang dipilih dan kembalikan datanya
     */
    public function runTool(string $toolName, string $input): ?string
    {
        if ($toolName === 'none' || !isset($this->tools[$toolName])) {
            return null;
        }

        return $this->tools[$toolName]->execute($input);
    }

    /**
     * Shortcut: decide + run sekaligus (dipakai MultiAgentService)
     */
    public function getToolData(string $message): ?string
    {
        $toolName = $this->decideTool($message);
        return $this->runTool($toolName, $message);
    }

    /**
     * Daftar tools yang terdaftar (untuk debugging)
     */
    public function getRegisteredTools(): array
    {
        return array_keys($this->tools);
    }
}