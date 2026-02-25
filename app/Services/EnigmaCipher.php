<?php
namespace App\Services;

class EnigmaCipher {
    private $rotors = [
        1 => 'EKMFLGDQVZNTOWYHXUSPAIBRCJ', // Rotor I
        2 => 'AJDKSIRUXBLHWTMCQGZNPYFVOE', // Rotor II
        3 => 'BDFHJLCPRTXVZNYEIWGAKMUSQO', // Rotor III
    ];
    private $reflector = 'YRUHQSLDPXNGOKMIEBFZCWVJAT'; // Reflector B
    private $rotorPositions = [0, 0, 0];
    private $rotorOrder = [1, 2, 3]; // urutan rotor dari kiri ke kanan

    public function __construct($key = 'AAA', $rotorOrder = null) {
        $key = CipherHelper::cleanText($key);
        if ($rotorOrder) {
            $this->rotorOrder = $rotorOrder;
        }
        $numRotors = count($this->rotorOrder);
        // key harus sepanjang jumlah rotor, jika kurang ditambah 'A'
        if (strlen($key) < $numRotors) {
            $key = str_pad($key, $numRotors, 'A');
        }
        for ($i = 0; $i < $numRotors; $i++) {
            $this->rotorPositions[$i] = ord($key[$i]) - ord('A');
        }
    }

    private function rotate() {
        $last = count($this->rotorPositions) - 1;
        $this->rotorPositions[$last] = ($this->rotorPositions[$last] + 1) % 26;
        for ($i = $last; $i > 0; $i--) {
            if ($this->rotorPositions[$i] == 0) {
                $this->rotorPositions[$i-1] = ($this->rotorPositions[$i-1] + 1) % 26;
            } else {
                break;
            }
        }
    }

    private function passThroughRotor($char, $rotorIdx, $pos, $reverse = false) {
        $rotor = $this->rotors[$this->rotorOrder[$rotorIdx]];
        if (!$reverse) {
            // Forward: dari kanan ke kiri
            $idx = (ord($char) - ord('A') + $pos) % 26;
            $mapped = $rotor[$idx];
            $outIdx = (ord($mapped) - ord('A') - $pos + 26) % 26;
            return chr($outIdx + ord('A'));
        } else {
            // Reverse: dari kiri ke kanan, perlu menambahkan posisi ke input
            $targetVal = (ord($char) - ord('A') + $pos) % 26;
            $targetChar = chr($targetVal + ord('A'));
            $found = strpos($rotor, $targetChar);
            if ($found === false) return $char; // tidak mungkin
            $outIdx = ($found - $pos + 26) % 26;
            return chr($outIdx + ord('A'));
        }
    }

    public function encrypt($text) {
        $text = CipherHelper::cleanText($text);
        $result = '';
        $initialPos = $this->rotorPositions;

        for ($i = 0; $i < strlen($text); $i++) {
            $char = $text[$i];
            $this->rotate(); // rotor berputar sebelum memproses huruf

            // Forward melalui rotor dari kanan ke kiri
            for ($r = count($this->rotorOrder) - 1; $r >= 0; $r--) {
                $char = $this->passThroughRotor($char, $r, $this->rotorPositions[$r], false);
            }

            // Reflektor
            $idx = ord($char) - ord('A');
            $char = $this->reflector[$idx];

            // Reverse melalui rotor dari kiri ke kanan
            for ($r = 0; $r < count($this->rotorOrder); $r++) {
                $char = $this->passThroughRotor($char, $r, $this->rotorPositions[$r], true);
            }

            $result .= $char;
        }

        $this->rotorPositions = $initialPos; // reset posisi
        return $result;
    }

    public function decrypt($text) {
        // Enigma simetris
        return $this->encrypt($text);
    }
}