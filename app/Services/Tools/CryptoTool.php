<?php

namespace App\Services\Tools;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CryptoTool implements ToolInterface
{
    /**
     * Nama tool (harus konsisten dengan decision AI)
     */
    public function name(): string
    {
        return 'crypto';
    }

    /**
     * Deskripsi tool (dipakai AI untuk memilih tool)
     */
    public function description(): string
    {
        return 'Digunakan untuk mendapatkan harga cryptocurrency secara real-time seperti Bitcoin, Ethereum, dll.';
    }

    /**
     * Eksekusi tool
     */
    public function execute(string $input): ?string
    {
        try {
            // 1. Deteksi coin dari input user
            $coin = $this->detectCoin($input);

            // 2. Mapping ke ID CoinGecko
            $coinId = $this->mapCoinToId($coin);

            if (!$coinId) {
                return "Saya tidak mengenali cryptocurrency tersebut.";
            }

            // 3. Call API CoinGecko
            $response = Http::timeout(10)->get(
                'https://api.coingecko.com/api/v3/simple/price',
                [
                    'ids' => $coinId,
                    'vs_currencies' => 'usd'
                ]
            );

            if ($response->failed()) {
                return null;
            }

            $price = $response->json()[$coinId]['usd'] ?? null;

            if (!$price) {
                return null;
            }

            return "Harga " . ucfirst($coin) . " saat ini adalah $price USD.";

        } catch (\Exception $e) {
            \Log::error('CryptoTool error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Deteksi nama coin dari input user
     */
    protected function detectCoin(string $input): string
    {
        $input = Str::lower($input);

        if (str_contains($input, 'bitcoin') || str_contains($input, 'btc')) {
            return 'bitcoin';
        }

        if (str_contains($input, 'ethereum') || str_contains($input, 'eth')) {
            return 'ethereum';
        }

        if (str_contains($input, 'solana') || str_contains($input, 'sol')) {
            return 'solana';
        }

        // default fallback
        return 'bitcoin';
    }

    /**
     * Mapping ke ID CoinGecko
     */
    protected function mapCoinToId(string $coin): ?string
    {
        $map = [
            'bitcoin'  => 'bitcoin',
            'ethereum' => 'ethereum',
            'solana'   => 'solana',
        ];

        return $map[$coin] ?? null;
    }
}