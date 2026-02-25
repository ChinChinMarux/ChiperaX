<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CipherHelper;
use App\Services\VigenereCipher;
use App\Services\AffineCipher;
use App\Services\PlayfairCipher;
use App\Services\HillCipher;
use App\Services\EnigmaCipher;

class CipherController extends Controller
{
    public function index()
    {
        return view('cipher');
    }

    public function process(Request $request)
    {
        $request->validate([
            'cipher' => 'required|in:vigenere,affine,playfair,hill,enigma',
            'mode' => 'required|in:encrypt,decrypt',
            'text' => 'required|string',
            'key' => 'required|string'
        ]);

        $cipherType = $request->cipher;
        $mode = $request->mode;
        $text = $request->text;
        $key = $request->key;

        // Bersihkan text (sudah dilakukan di masing-masing cipher, tapi kita bisa bersihkan dulu)
        $text = CipherHelper::cleanText($text);
        if (empty($text)) {
            return redirect()->back()->with('result', 'Teks kosong setelah pembersihan karakter.');
        }

        $result = '';
        try {
            switch ($cipherType) {
                case 'vigenere':
                    $cipher = new VigenereCipher();
                    $result = $mode == 'encrypt' ? $cipher->encrypt($text, $key) : $cipher->decrypt($text, $key);
                    break;
                case 'affine':
                    $cipher = new AffineCipher();
                    $result = $mode == 'encrypt' ? $cipher->encrypt($text, $key) : $cipher->decrypt($text, $key);
                    break;
                case 'playfair':
                    $cipher = new PlayfairCipher();
                    $result = $mode == 'encrypt' ? $cipher->encrypt($text, $key) : $cipher->decrypt($text, $key);
                    break;
                case 'hill':
                    $cipher = new HillCipher();
                    $result = $mode == 'encrypt' ? $cipher->encrypt($text, $key) : $cipher->decrypt($text, $key);
                    break;
                case 'enigma':
                    $cipher = new EnigmaCipher($key);
                    $result = $cipher->encrypt($text); // encrypt dan decrypt sama
                    break;
            }
        } catch (\Exception $e) {
            $result = 'Error: ' . $e->getMessage();
        }

        return redirect()->back()->with('result', $result)->withInput();
    }
}