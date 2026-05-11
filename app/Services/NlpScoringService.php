<?php

namespace App\Services;

use Sastrawi\Stemmer\StemmerFactory;
use Sastrawi\StopWordRemover\StopWordRemoverFactory;


class NlpScoringService
{
    private function preprocessText($text)
    {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z\s]/', '', $text);

        $stopwordFactory = new StopWordRemoverFactory();
        $remover = $stopwordFactory->createStopWordRemover();
        $text = $remover->remove($text);

        $stemmerFactory = new StemmerFactory();
        $stemmer = $stemmerFactory->createStemmer();
        $text = $stemmer->stem($text);

        return $text;
    }

    public function calculateCosineSimilarity($keyText, $studentText)
    {
        $cleanKey = $this->preprocessText($keyText);
        $cleanStudent = $this->preprocessText($studentText);

        $u1 = array_filter(explode(' ', $cleanKey));
        $u2 = array_filter(explode(' ', $cleanStudent));

        $b1 = [];
        $keys1 = array_values($u1);
        for($i=0; $i < count($keys1)-1; $i++) { $b1[] = $keys1[$i].' '.$keys1[$i+1]; }

        $b2 = [];
        $keys2 = array_values($u2);
        for($i=0; $i < count($keys2)-1; $i++) { $b2[] = $keys2[$i].' '.$keys2[$i+1]; }

        $t1 = array_merge($u1, $b1);
        $t2 = array_merge($u2, $b2);

        $words = array_unique(array_merge($t1, $t2));
        $counts1 = array_count_values($t1);
        $counts2 = array_count_values($t2);

        $v1 = []; $v2 = []; $logTerms = [];

        foreach ($words as $word) {
            $bobot1 = $counts1[$word] ?? 0;
            $bobot2 = $counts2[$word] ?? 0;
            
            $v1[] = $bobot1; $v2[] = $bobot2;

            $logTerms[$word] = [
                'bobot_kunci' => $bobot1,
                'bobot_jawaban' => $bobot2
            ];
        }

        $dotProduct = 0;
        foreach ($v1 as $i => $val) { $dotProduct += $v1[$i] * $v2[$i]; }

        $mag1 = sqrt(array_sum(array_map(fn($x) => $x * $x, $v1)));
        $mag2 = sqrt(array_sum(array_map(fn($x) => $x * $x, $v2)));

        $score = ($mag1 * $mag2 == 0) ? 0 : ($dotProduct / ($mag1 * $mag2));
        
        return [
            'score' => $score,
            'log' => [
                'clean_key' => $cleanKey,
                'clean_student' => $cleanStudent,
                'terms' => $logTerms,
                'dot_product' => $dotProduct,
                'magnitude_kunci' => $mag1,
                'magnitude_jawaban' => $mag2
            ]
        ];
    }
}