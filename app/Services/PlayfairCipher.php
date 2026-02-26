<?php
namespace App\Services;

class PlayfairCipher {
    private function prepareKey($key) {
        $key = CipherHelper::cleanText($key);
        $key = str_replace('J', 'I', $key);
        $matrix = [];
        $used = [];
        // Tambahkan huruf dari key
        for ($i = 0; $i < strlen($key); $i++) {
            $char = $key[$i];
            if (!in_array($char, $used)) {
                $used[] = $char;
            }
        }
        // Tambahkan sisa alfabet (I dan J digabung)
        for ($c = ord('A'); $c <= ord('Z'); $c++) {
            $char = chr($c);
            if ($char == 'J') continue; // skip J, sudah diwakili I
            if (!in_array($char, $used)) {
                $used[] = $char;
            }
        }
        // Bentuk matriks 5x5
        $matrix = array_chunk($used, 5);
        return $matrix;
    }

    private function findPosition($matrix, $char) {
        if ($char == 'J') $char = 'I';
        for ($row = 0; $row < 5; $row++) {
            for ($col = 0; $col < 5; $col++) {
                if ($matrix[$row][$col] == $char) return [$row, $col];
            }
        }
        return null;
    }

    private function processText($text) {
    $text = CipherHelper::cleanText($text);
    $text = str_replace('J', 'I', $text);
    $digraphs = [];
    $i = 0;
    while ($i < strlen($text)) {
        $a = $text[$i];
        if ($i+1 >= strlen($text)) {
            // ganjil, tambah Q
            $b = 'Q';
            $i++;
        } else {
            $b = $text[$i+1];
            if ($a == $b) {
                $b = 'X';
                $i++;
            } else {
                $i += 2;
            }
        }
        $digraphs[] = [$a, $b];
    }
    return $digraphs;
}

    public function encrypt($text, $key) {
        $matrix = $this->prepareKey($key);
        $digraphs = $this->processText($text);
        $result = '';
        foreach ($digraphs as $pair) {
            list($a, $b) = $pair;
            list($r1, $c1) = $this->findPosition($matrix, $a);
            list($r2, $c2) = $this->findPosition($matrix, $b);
            if ($r1 == $r2) {
                $result .= $matrix[$r1][($c1+1)%5];
                $result .= $matrix[$r2][($c2+1)%5];
            } elseif ($c1 == $c2) {
                $result .= $matrix[($r1+1)%5][$c1];
                $result .= $matrix[($r2+1)%5][$c2];
            } else {
                $result .= $matrix[$r1][$c2];
                $result .= $matrix[$r2][$c1];
            }
        }
        return $result;
    }

    public function decrypt($text, $key) {
        $matrix = $this->prepareKey($key);
        
        $text = CipherHelper::cleanText($text);
        $digraphs = [];
        $chunks = str_split($text, 2);
        foreach ($chunks as $chunk) {
            if (strlen($chunk) == 2) {
                $digraphs[] = [$chunk[0], $chunk[1]];
            }
        }

        $result = '';
        foreach ($digraphs as $pair) {
            list($a, $b) = $pair;
            list($r1, $c1) = $this->findPosition($matrix, $a);
            list($r2, $c2) = $this->findPosition($matrix, $b);
            
            if ($r1 == $r2) {
                $result .= $matrix[$r1][($c1-1+5)%5];
                $result .= $matrix[$r2][($c2-1+5)%5];
            } elseif ($c1 == $c2) {
                $result .= $matrix[($r1-1+5)%5][$c1];
                $result .= $matrix[($r2-1+5)%5][$c2];
            } else {
                $result .= $matrix[$r1][$c2];
                $result .= $matrix[$r2][$c1];
            }
        }

        // OPSIONAL: Bersihkan padding huruf 'Q' di karakter terakhir
        // (Ini akan membuat AKUIAWAQ kembali menjadi AKUIAWA)
        if (substr($result, -1) === 'Q') {
            $result = substr($result, 0, -1);
        }

        return $result;
    }
}