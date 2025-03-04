<?php

namespace App\Service;

class ProfanityCheckerService
{
    private $badWords = ['badword1', 'badword2', 'badword3']; // Ajoutez vos mots interdits ici

    public function hasProfanity(string $text): bool
    {
        $text = strtolower($text);
        foreach ($this->badWords as $word) {
            if (strpos($text, strtolower($word)) !== false) {
                return true;
            }
        }
        return false;
    }

    public function filterProfanity(string $text): string
    {
        $text = strtolower($text);
        foreach ($this->badWords as $word) {
            $replacement = str_repeat('*', strlen($word));
            $text = str_ireplace($word, $replacement, $text);
        }
        return $text;
    }
}