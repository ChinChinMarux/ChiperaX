<?php
namespace App\Services;

class VigenereCipher {
    #Encryption
    public function encrypt($text, $key) {
    #Memanggil fungsi Clean Text    
    $text = CipherHelper::cleanText($text);
        $key = CipherHelper::cleanText($key);
        if (empty($key)) return $text;

        $result = '';
        $keyLen = strlen($key);
        $keyIndex = 0;
        for ($i = 0; $i < strlen($text); $i++) {
            $char = $text[$i];
            $shift = ord($key[$keyIndex % $keyLen]) - ord('A');
            $result .= chr((ord($char) - ord('A') + $shift) % 26 + ord('A'));
            $keyIndex++;
        }
        return $result;
    }
    #Decryption
    public function decrypt($text, $key) {
        #Memanggil fungsi Clean Text
        $text = CipherHelper::cleanText($text);
        $key = CipherHelper::cleanText($key);
        if (empty($key)) return $text;

        $result = '';
        $keyLen = strlen($key);
        $keyIndex = 0;
        for ($i = 0; $i < strlen($text); $i++) {
            $char = $text[$i];
            $shift = ord($key[$keyIndex % $keyLen]) - ord('A');
            $result .= chr((ord($char) - ord('A') - $shift + 26) % 26 + ord('A'));
            $keyIndex++;
        }
        return $result;
    }
}