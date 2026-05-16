<?php

namespace App\Services\Context;

class ContextBuilder
{
    protected ?string $toolData = null;
    protected ?string $userMessage = null;
    protected array $rules = [];

    /**
     * Set pesan user (opsional, untuk future reasoning)
     */
    public function setUserMessage(string $message): self
    {
        $this->userMessage = $message;
        return $this;
    }

    /**
     * Set data dari tool (crypto, weather, dll)
     */
    public function setToolData(?string $data): self
    {
        $this->toolData = $data;
        return $this;
    }

    /**
     * Tambahkan aturan custom
     */
    public function addRule(string $rule): self
    {
        $this->rules[] = $rule;
        return $this;
    }

    /**
     * Build final system prompt
     */
    public function build(): string
    {
        $time = now()->translatedFormat('l, d F Y H:i');

        $baseRules = [
            "Gunakan data yang diberikan sebagai sumber utama.",
            "Jangan mengarang informasi.",
            "Jika data tidak tersedia, katakan 'Saya tidak tahu'.",
            "Jawab secara jelas, ringkas, dan profesional."
        ];

        $allRules = array_merge($baseRules, $this->rules);

        return "
Kamu adalah AI assistant yang akurat dan tidak mengarang.

Waktu sekarang:
$time

" . $this->buildToolSection() . "

Aturan:
" . $this->formatRules($allRules) . "
";
    }

    /**
     * Format bagian tool data
     */
    protected function buildToolSection(): string
    {
        if (!$this->toolData) {
            return "Data real-time: Tidak ada data tambahan.";
        }

        return "Data real-time:\n" . $this->toolData;
    }

    /**
     * Format rules jadi bullet list
     */
    protected function formatRules(array $rules): string
    {
        return collect($rules)
            ->map(fn($rule) => "- " . $rule)
            ->implode("\n");
    }
}