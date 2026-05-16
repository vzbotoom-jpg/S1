<?php

namespace App\Services\Tools;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WebSearchTool implements ToolInterface
{
    protected string $apiKey;
    protected string $searchEngineId;

    public function __construct()
    {
        $this->apiKey       = config('services.google_search.api_key');
        $this->searchEngineId = config('services.google_search.cx');
    }

    public function name(): string
    {
        return 'websearch';
    }

    public function description(): string
    {
        return 'Digunakan untuk mencari informasi terbaru dari internet menggunakan Google Search. Gunakan ini jika user bertanya tentang berita terkini, fakta terbaru, harga, event, atau informasi apapun yang mungkin berubah.';
    }

    public function execute(string $input): ?string
    {
        try {
            $query = $this->cleanQuery($input);

            // Cache 10 menit agar tidak boros quota
            $cacheKey = 'websearch_' . md5($query);
            return Cache::remember($cacheKey, 600, function () use ($query) {
                return $this->search($query);
            });

        } catch (\Exception $e) {
            \Log::error('WebSearchTool error: ' . $e->getMessage());
            return null;
        }
    }

    protected function search(string $query): ?string
    {
        $response = Http::timeout(10)->get('https://www.googleapis.com/customsearch/v1', [
            'key'  => $this->apiKey,
            'cx'   => $this->searchEngineId,
            'q'    => $query,
            'num'  => 5,       // Ambil 5 hasil
            'lr'   => 'lang_id', // Prioritas bahasa Indonesia
            'gl'   => 'id',    // Lokasi: Indonesia
        ]);

        if ($response->failed()) {
            \Log::error('WebSearchTool: Google API error - ' . $response->body());
            return null;
        }

        $items = $response->json('items') ?? [];

        if (empty($items)) {
            return "Tidak ditemukan hasil pencarian untuk: \"$query\"";
        }

        return $this->formatResults($items, $query);
    }

    /**
     * Bersihkan query dari kata-kata tidak perlu
     */
    protected function cleanQuery(string $input): string
    {
        // Tambahkan konteks waktu agar hasil lebih relevan
        $year = now()->format('Y');

        $stopWords = ['tolong', 'coba', 'bisakah', 'apakah', 'jelaskan', 'ceritakan'];
        $query = str_ireplace($stopWords, '', $input);
        $query = trim(preg_replace('/\s+/', ' ', $query));

        // Tambah tahun jika query tentang hal terkini
        $terkiniKeywords = ['terbaru', 'terkini', 'sekarang', 'hari ini', 'terbaru'];
        foreach ($terkiniKeywords as $kw) {
            if (str_contains(strtolower($query), $kw)) {
                $query .= " $year";
                break;
            }
        }

        return $query;
    }

    /**
     * Format hasil pencarian jadi string untuk AI
     */
    protected function formatResults(array $items, string $query): string
    {
        $lines = ["Hasil pencarian Google untuk: \"$query\"\n"];

        foreach ($items as $i => $item) {
            $no      = $i + 1;
            $title   = $item['title'] ?? 'Tanpa judul';
            $link    = $item['link'] ?? '';
            $snippet = $item['snippet'] ?? 'Tidak ada deskripsi.';
            $source  = parse_url($link, PHP_URL_HOST) ?? 'Unknown';

            $lines[] = "$no. $title";
            $lines[] = "   Sumber: $source";
            $lines[] = "   $snippet";
            $lines[] = "   URL: $link";
            $lines[] = '';
        }

        $lines[] = "Gunakan informasi di atas untuk menjawab pertanyaan user secara akurat.";
        $lines[] = "Selalu sebutkan sumber informasi yang kamu gunakan.";

        return implode("\n", $lines);
    }
}