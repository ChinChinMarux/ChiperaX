<?php
namespace App\Services;

class AffineCipher {
    private function modInverse($a, $m) {
        $a = $a % $m;
        for ($x = 1; $x < $m; $x++) {
            if (($a * $x) % $m == 1) return $x;
        }
        return null;
    }

    public function encrypt($text, $key) {
        $text = CipherHelper::cleanText($text);
        list($a, $b) = $this->parseKey($key);
        if ($a === null || $b === null) {
            return "Kunci tidak valid";
        }
        if ($this->modInverse($a, 26) === null) {
            return "Kunci 'a' harus merupakan bilangan yang relatif prima dengan 26";
        }

        $result = '';
        for ($i = 0; $i < strlen($text); $i++) {
            $char = $text[$i];
            $x = ord($char) - ord('A');
            $enc = ($a * $x + $b) % 26;
            $result .= chr($enc + ord('A'));
        }
        return $result;
    }

    public function decrypt($text, $key) {
        $text = CipherHelper::cleanText($text);
        list($a, $b) = $this->parseKey($key);
        if ($a === null || $b === null) {
            return "Kunci tidak valid";
        }
        $inv = $this->modInverse($a, 26);
        if ($inv === null) {
            return "Kunci 'a' harus merupakan bilangan yang relatif prima dengan 26";
        }

        $result = '';
        for ($i = 0; $i < strlen($text); $i++) {
            $char = $text[$i];
            $y = ord($char) - ord('A');
            // Normalisasi selisih agar selalu positif dalam rentang 0-25
            $diff = (($y - $b) % 26 + 26) % 26;
            $dec = ($inv * $diff) % 26;
            $result .= chr($dec + ord('A'));
        }
        return $result;
    }

    private function parseKey($key) {
        $parts = explode(',', $key);
        if (count($parts) != 2) {
            return [null, null];
        }
        $a = intval(trim($parts[0]));
        $b = intval(trim($parts[1]));
        // a: 1-25, b: non-negatif (tanpa batas atas)
        if ($a < 1 || $a > 25 || $b < 0) {
            return [null, null];
        }
        return [$a, $b];
    }
}