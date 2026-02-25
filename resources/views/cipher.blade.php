<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalkulator Enkripsi-Dekripsi Cipher Klasik</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        select, textarea, input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            height: 100px;
            resize: vertical;
        }
        .radio-group {
            display: flex;
            gap: 20px;
        }
        .radio-group label {
            font-weight: normal;
            display: inline;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
        .result-box {
            margin-top: 20px;
            padding: 15px;
            background-color: #e9f7fe;
            border: 1px solid #bce8f1;
            border-radius: 4px;
            color: #31708f;
        }
        .note {
            font-size: 0.9em;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Kalkulator Enkripsi/Dekripsi Cipher Klasik</h1>
        <form method="POST" action="{{ route('cipher.process') }}">
            @csrf
            <div class="form-group">
                <label for="cipher">Pilih Cipher:</label>
                <select name="cipher" id="cipher" required>
                    <option value="vigenere" {{ old('cipher')=='vigenere' ? 'selected' : '' }}>Vigenere Cipher</option>
                    <option value="affine" {{ old('cipher')=='affine' ? 'selected' : '' }}>Affine Cipher</option>
                    <option value="playfair" {{ old('cipher')=='playfair' ? 'selected' : '' }}>Playfair Cipher</option>
                    <option value="hill" {{ old('cipher')=='hill' ? 'selected' : '' }}>Hill Cipher (2x2)</option>
                    <option value="enigma" {{ old('cipher')=='enigma' ? 'selected' : '' }}>Enigma Cipher (sederhana)</option>
                </select>
            </div>

            <div class="form-group">
                <label>Mode:</label>
                <div class="radio-group">
                    <label><input type="radio" name="mode" value="encrypt" {{ old('mode','encrypt')=='encrypt' ? 'checked' : '' }} required> Encrypt</label>
                    <label><input type="radio" name="mode" value="decrypt" {{ old('mode')=='decrypt' ? 'checked' : '' }} required> Decrypt</label>
                </div>
            </div>

            <div class="form-group">
                <label for="text">Teks:</label>
                <textarea name="text" id="text" required>{{ old('text') }}</textarea>
                <div class="note">Hanya huruf A-Z yang diproses, selain itu akan dihapus. Otomatis diubah ke UPPERCASE.</div>
            </div>

            <div class="form-group">
                <label for="key">Kunci:</label>
                <input type="text" name="key" id="key" value="{{ old('key') }}" required>
                <div class="note">
                    - Vigenere: kata kunci (huruf)<br>
                    - Affine: dua angka a,b dipisah koma (contoh: 5,8) dengan a koprima 26<br>
                    - Playfair: kata kunci (huruf)<br>
                    - Hill: Hill: 4 angka untuk 2x2 (a,b,c,d) atau 9 angka untuk 3x3 (a,b,c,d,e,f,g,h,i) determinan harus invertible<br>
                    - Enigma: 3 huruf posisi awal rotor (contoh: ABC)
                </div>
            </div>

            <button type="submit">Proses</button>
        </form>

        @if(session('result'))
            <div class="result-box">
                <strong>Hasil:</strong> {{ session('result') }}
            </div>
        @endif
    </div>
</body>
</html>