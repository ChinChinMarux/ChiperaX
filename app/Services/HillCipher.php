<?php
namespace App\Services;

class HillCipher {
    private function modInverse($a, $m) {
        $a = $a % $m;
        for ($x = 1; $x < $m; $x++) {
            if (($a * $x) % $m == 1) return $x;
        }
        return null;
    }

    private function parseKey($key) {
    $parts = explode(',', $key);
    $count = count($parts);
    if ($count != 4 && $count != 9) return null;
    $matrix = array_map('intval', $parts);
    foreach ($matrix as $val) {
        if ($val < 0 || $val > 25) return null;
    }
    $n = $count == 4 ? 2 : 3;
    // bentuk matriks n x n
    $mat = [];
    for ($i=0; $i<$n; $i++) {
        $row = [];
        for ($j=0; $j<$n; $j++) {
            $row[] = $matrix[$i*$n + $j];
        }
        $mat[] = $row;
    }
    return ['matrix' => $mat, 'n' => $n];
}

    private function determinant($matrix, $n) {
    if ($n == 2) {
        return ($matrix[0][0]*$matrix[1][1] - $matrix[0][1]*$matrix[1][0]) % 26;
    } else { // n=3
        $det = 0;
        for ($i=0; $i<3; $i++) {
            $minor = $this->minor($matrix, 0, $i);
            $det += $matrix[0][$i] * $this->determinant($minor, 2) * (($i % 2 == 0) ? 1 : -1);
        }
        return (($det % 26) + 26) % 26;
    }
}

private function minor($matrix, $row, $col) {
    $minor = [];
    for ($i=0; $i<3; $i++) {
        if ($i == $row) continue;
        $rowMinor = [];
        for ($j=0; $j<3; $j++) {
            if ($j == $col) continue;
            $rowMinor[] = $matrix[$i][$j];
        }
        $minor[] = $rowMinor;
    }
    return $minor;
}

    private function inverseMatrix($matrix, $n) {
    $det = $this->determinant($matrix, $n);
    $det = ($det + 26) % 26;
    $invDet = $this->modInverse($det, 26);
    if ($invDet === null) return null;

    if ($n == 2) {
        // Adjoint: [[d, -b], [-c, a]]
        $adj = [
            [$matrix[1][1], -$matrix[0][1]],
            [-$matrix[1][0], $matrix[0][0]]
        ];
        $inv = [];
        for ($i=0; $i<2; $i++) {
            for ($j=0; $j<2; $j++) {
                $val = ($adj[$i][$j] * $invDet) % 26;
                $inv[$i][$j] = ($val + 26) % 26;
            }
        }
        return $inv;
    } else { // n=3
        // Hitung matriks kofaktor
        $cofactor = [];
        for ($i=0; $i<3; $i++) {
            for ($j=0; $j<3; $j++) {
                $minor = $this->minor($matrix, $i, $j);
                $detMinor = $this->determinant($minor, 2);
                $cofactor[$i][$j] = $detMinor * (($i+$j) % 2 == 0 ? 1 : -1);
            }
        }
        // Transpose untuk mendapatkan adjoint
        $adj = [];
        for ($i=0; $i<3; $i++) {
            for ($j=0; $j<3; $j++) {
                $adj[$i][$j] = $cofactor[$j][$i];
            }
        }
        // Kalikan dengan invDet
        $inv = [];
        for ($i=0; $i<3; $i++) {
            for ($j=0; $j<3; $j++) {
                $val = ($adj[$i][$j] * $invDet) % 26;
                $inv[$i][$j] = ($val + 26) % 26;
            }
        }
        return $inv;
    }
}

    public function encrypt($text, $key) {
    $text = CipherHelper::cleanText($text);
    $parsed = $this->parseKey($key);
    if (!$parsed) return "Kunci tidak valid";
    $matrix = $parsed['matrix'];
    $n = $parsed['n'];
    // Cek determinan invertible
    $det = $this->determinant($matrix, $n);
    if ($this->modInverse($det, 26) === null) {
        return "Matriks kunci tidak inversible";
    }

    $len = strlen($text);
    $remainder = $len % $n;
    if ($remainder != 0) {
        $text .= str_repeat('X', $n - $remainder);
    }

    $result = '';
    for ($i=0; $i<strlen($text); $i+=$n) {
        $block = [];
        for ($j=0; $j<$n; $j++) {
            $block[] = ord($text[$i+$j]) - ord('A');
        }
        // Enkripsi: C = M * K mod 26
        $enc = array_fill(0, $n, 0);
        for ($r=0; $r<$n; $r++) {
            $sum = 0;
            for ($c=0; $c<$n; $c++) {
                $sum += $matrix[$r][$c] * $block[$c];
            }
            $enc[$r] = $sum % 26;
        }
        for ($j=0; $j<$n; $j++) {
            $result .= chr($enc[$j] + ord('A'));
        }
    }
    return $result;
}

    public function decrypt($text, $key) {
    $text = CipherHelper::cleanText($text);
    $parsed = $this->parseKey($key);
    if (!$parsed) return "Kunci tidak valid";
    $matrix = $parsed['matrix'];
    $n = $parsed['n'];
    $inv = $this->inverseMatrix($matrix, $n);
    if (!$inv) return "Matriks kunci tidak inversible";

    // Pastikan panjang teks kelipatan n
    $len = strlen($text);
    if ($len % $n != 0) {
        return "Panjang ciphertext harus sepanjang $n";
    }

    $result = '';
    for ($i=0; $i<$len; $i+=$n) {
        $block = [];
        for ($j=0; $j<$n; $j++) {
            $block[] = ord($text[$i+$j]) - ord('A');
        }
        $dec = array_fill(0, $n, 0);
        for ($r=0; $r<$n; $r++) {
            $sum = 0;
            for ($c=0; $c<$n; $c++) {
                $sum += $inv[$r][$c] * $block[$c];
            }
            $dec[$r] = $sum % 26;
        }
        for ($j=0; $j<$n; $j++) {
            $result .= chr($dec[$j] + ord('A'));
        }
    }
    return $result;
}
}