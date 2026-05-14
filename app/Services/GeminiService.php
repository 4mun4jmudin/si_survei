<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected $apiKey;
    protected $endpoint;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key');
        // Menggunakan model flash yang lebih cepat
        $this->endpoint = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $this->apiKey;
    }

    /**
     * Minta AI memberikan rekomendasi spesifik per indikator.
     * Mengembalikan array [ 'Nama Indikator' => 'Rekomendasi Spesifik ...' ]
     */
    public function generateRecommendations(array $indikatorsDanSkor)
    {
        if (empty($this->apiKey) || empty($indikatorsDanSkor)) {
            return [];
        }

        // Susun daftar indikator untuk prompt AI
        $listText = "";
        foreach ($indikatorsDanSkor as $item) {
            $listText .= "- Indikator: " . $item['indikator'] . " (Skor Rata-rata: " . $item['rata_rata'] . " dari 5.0)\n";
        }

        $prompt = "Sebagai asisten ahli manajemen pendidikan, berikan rekomendasi tindak lanjut yang spesifik, cerdas, dan dapat langsung diterapkan untuk masing-masing indikator evaluasi sekolah berikut berdasarkan skor yang diperolehnya (Skala 1-5).\n\n"
                . "Aturan:\n"
                . "1. Rekomendasi maksimal 2 kalimat per indikator.\n"
                . "2. Fokus pada upaya pencegahan jika skor rendah (< 3.0), pemeliharaan jika sedang (3.0 - 4.0), atau peningkatan percontohan jika tinggi (> 4.0).\n"
                . "3. Kembalikan output HANYA dalam format JSON valid tanpa format markdown (```json ... ```) dengan key adalah nama indikator persis seperti yang diberikan, dan value adalah string rekomendasi.\n\n"
                . "Data Indikator:\n"
                . $listText;

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->endpoint, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            if ($response->successful()) {
                $result = $response->json();
                if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                    $aiText = $result['candidates'][0]['content']['parts'][0]['text'];
                    
                    // Bersihkan formatting markdown JSON jika terbawa oleh AI
                    $aiText = str_replace(['```json', '```'], '', $aiText);
                    $aiText = trim($aiText);

                    $decoded = json_decode($aiText, true);
                    return is_array($decoded) ? $decoded : [];
                }
            } else {
                Log::error('Gemini API Error: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Gemini API Exception: ' . $e->getMessage());
        }

        return [];
    }
}
