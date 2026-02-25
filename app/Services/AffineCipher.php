<?php
namespace App\Services;

class AffineCipher {
    #Deklarasi logika invers untuk Decryption
    private function modInverse($a, $m) {
        $a = $a % $m;
        for ($x = 1; $x < $m; $x++) {
            if (($a * $x) % $m == 1) return $x;
        }
        return null;
    }
    #Encryption
    public function encrypt($text, $key) {
        #Memanggil fungsi Clean Text
        $text = CipherHelper::cleanText($text);
        list($a, $b) = $this->parseKey($key);
        if ($a === null || $b === null) return "Invalid key";
        if ($this->modInverse($a, 26) === null) return "Key 'a' must be coprime with 26";

        $result = '';
        for ($i = 0; $i < strlen($text); $i++) {
            $char = $text[$i];
            $x = ord($char) - ord('A');
            $enc = ($a * $x + $b) % 26;
            $result .= chr($enc + ord('A'));
        }
        return $result;
    }
    #Decryption
    public function decrypt($text, $key) {
        #Memanggil fungsi Clean Text
        $text = CipherHelper::cleanText($text);
        list($a, $b) = $this->parseKey($key);
        if ($a === null || $b === null) return "Invalid key";
        $inv = $this->modInverse($a, 26);
        if ($inv === null) return "Key 'a' must be coprime with 26";

        $result = '';
        for ($i = 0; $i < strlen($text); $i++) {
            $char = $text[$i];
            $y = ord($char) - ord('A');
            $dec = ($inv * ($y - $b + 26)) % 26;
            $result .= chr($dec + ord('A'));
        }
        return $result;
    }

    private function parseKey($key) {
        $parts = explode(',', $key);
        if (count($parts) != 2) return [null, null];
        $a = intval(trim($parts[0]));
        $b = intval(trim($parts[1]));
        if ($a < 1 || $a > 25 || $b < 0 || $b > 25) return [null, null];
        return [$a, $b];
    }
}