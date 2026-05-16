<?php

namespace App\Services\Tools;

interface ToolInterface
{
    /**
     * Nama unik tool (dipakai agent)
     */
    public function name(): string;

    /**
     * Deskripsi fungsi tool (untuk AI decision)
     */
    public function description(): string;

    /**
     * Eksekusi tool
     *
     * @param string $input (user message / parsed input)
     * @return string|null
     */
    public function execute(string $input): ?string;
}