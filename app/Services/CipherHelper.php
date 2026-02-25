<?php
namespace App\Services;

class CipherHelper {
    #Menghilangkan stopwords, specialized char, dan menjadikan UPPERCASE
    public static function cleanText($text) {
        $text = strtoupper($text);
        $text = preg_replace('/[^A-Z]/', '', $text);
        return $text;
    }
}