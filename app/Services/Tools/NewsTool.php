<?php

namespace App\Services\Tools;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class NewsTool implements ToolInterface
{
    public function name(): string
    {
        return 'news';
    }

    public function description(): string
    {
        return 'Digunakan untuk mendapatkan berita terbaru dari internet berdasarkan topik yang ditanyakan user.';
    }

    public function execute(string $input): ?string
    {
        try {
            $query = $this->extractQuery($input);
            $apiKey = config('services.newsapi.key');

            // Gunakan NewsAPI.org (gratis untuk dev)
            $response = Http::timeout(10)->get('https://newsapi.org/v2/everything', [
                'q'        => $query,
                'language' => 'id',        // Prioritas berita bahasa Indonesia
                'sortBy'   => 'publishedAt',
                'pageSize' => 5,
                'apiKey'   => $apiKey,
            ]);

            // Fallback ke bahasa Inggris jika hasil kosong
            if ($response->failed() || empty($response->json('articles'))) {
                $response = Http::timeout(10)->get('https://newsapi.org/v2/everything', [
                    'q'        => $query,
                    'sortBy'   => 'publishedAt',
                    'pageSize' => 5,
                    'apiKey'   => $apiKey,
                ]);
            }

            if ($response->failed()) {
                \Log::error('NewsTool: API request failed - ' . $response->body());
                return null;
            }

            $articles = $response->json('articles') ?? [];

            if (empty($articles)) {
                return "Tidak ditemukan berita terbaru tentang '$query'.";
            }

            return $this->formatArticles($articles, $query);

        } catch (\Exception $e) {
            \Log::error('NewsTool error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Ekstrak kata kunci pencarian dari input user
     */
    protected function extractQuery(string $input): string
    {
        $input = Str::lower($input);

        // Hapus kata tanya umum agar query lebih bersih
        $stopWords = [
            'berita', 'terbaru', 'tentang', 'mengenai', 'apa', 'bagaimana',
            'gimana', 'kabar', 'update', 'info', 'informasi', 'seputar',
            'terkini', 'hari ini', 'kemarin', 'minggu ini',
        ];

        $query = str_replace($stopWords, '', $input);
        $query = trim(preg_replace('/\s+/', ' ', $query));

        // Fallback jika query kosong
        return $query ?: 'berita terbaru Indonesia';
    }

    /**
     * Format artikel jadi string yang mudah dibaca AI
     */
    protected function formatArticles(array $articles, string $query): string
    {
        $lines = ["Berita terbaru tentang \"$query\":\n"];

        foreach (array_slice($articles, 0, 5) as $i => $article) {
            $no      = $i + 1;
            $title   = $article['title'] ?? 'Tanpa judul';
            $source  = $article['source']['name'] ?? 'Sumber tidak diketahui';
            $date    = isset($article['publishedAt'])
                ? \Carbon\Carbon::parse($article['publishedAt'])->setTimezone('Asia/Jakarta')->translatedFormat('d F Y H:i')
                : 'Tanggal tidak tersedia';
            $desc    = $article['description'] ?? 'Tidak ada deskripsi.';
            $url     = $article['url'] ?? '';

            $lines[] = "$no. **$title**";
            $lines[] = "   Sumber: $source | $date";
            $lines[] = "   $desc";
            if ($url) $lines[] = "   Baca selengkapnya: $url";
            $lines[] = '';
        }

        return implode("\n", $lines);
    }
}