<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class AiReviewService
{
    public function reviewAnswer($keyAnswer, $studentAnswer)
    {
        $apiKey = env('GEMINI_API_KEY');
        
        if (empty($apiKey)) {
            throw new Exception("GEMINI_API_KEY belum diatur di file .env.");
        }

        if (empty(trim($studentAnswer))) {
            return [
                'score' => 0,
                'feedback' => 'Mahasiswa tidak memberikan jawaban.'
            ];
        }

        $prompt = "Anda adalah seorang asisten dosen yang ahli dalam menilai ujian esai berbahasa Indonesia.
Tugas Anda adalah menilai jawaban mahasiswa berdasarkan kunci jawaban yang diberikan. 
Berikan skor dari 0 hingga 100 berdasarkan tingkat pemahaman semantik (makna), bukan hanya pencocokan kata. Jika mahasiswa menggunakan sinonim atau kalimat yang berbeda namun maknanya sama dengan kunci jawaban, berikan skor tinggi. Jika jawaban melenceng, berikan skor rendah.

Kunci Jawaban:
\"{$keyAnswer}\"

Jawaban Mahasiswa:
\"{$studentAnswer}\"

Berikan output HANYA dalam format JSON yang valid tanpa markdown formatting (jangan gunakan ```json), dengan struktur:
{
    \"score\": [angka 0-100],
    \"feedback\": \"[alasan singkat mengapa skor tersebut diberikan, maksimal 2 kalimat]\"
}";

        $response = Http::withOptions(['verify' => false])->withHeaders([
            'Content-Type' => 'application/json',
        ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$apiKey}", [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ]);

        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                $resultText = $data['candidates'][0]['content']['parts'][0]['text'];
                // Bersihkan text dari kemungkinan markdown block
                $resultText = str_replace(['```json', '```JSON', '```'], '', $resultText);
                $result = json_decode(trim($resultText), true);

                if (json_last_error() === JSON_ERROR_NONE && isset($result['score']) && isset($result['feedback'])) {
                    return [
                        'score' => (float) $result['score'],
                        'feedback' => $result['feedback']
                    ];
                }
            }
        }

        throw new Exception("Gagal mendapatkan respons yang valid dari AI. ". $response->body());
    }
}
