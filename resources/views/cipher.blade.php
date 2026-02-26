<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChiperaX</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700&family=Cinzel:wght@400;500;600&family=IM+Fell+English:ital@0;1&display=swap" rel="stylesheet">
    <style>
        :root {
            --parchment: #f5ead6;
            --parchment-dark: #e8d5b0;
            --parchment-deeper: #d4b896;
            --ink: #2c1a0e;
            --ink-light: #5a3820;
            --ink-faded: #8b6347;
            --gold: #b8860b;
            --gold-light: #d4a017;
            --gold-bright: #f0c040;
            --red: #8b1a1a;
            --shadow: rgba(44, 26, 14, 0.35);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'IM Fell English', serif;
            background-color: #1a0f08;
            background-image:
                radial-gradient(ellipse at 20% 30%, rgba(90,40,10,0.5) 0%, transparent 55%),
                radial-gradient(ellipse at 80% 70%, rgba(60,20,5,0.5) 0%, transparent 55%),
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='300' height='300' filter='url(%23n)' opacity='0.08'/%3E%3C/svg%3E");
            min-height: 100vh;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 50px 20px 60px;
        }

        /* Floating particles */
        .particles {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            width: 3px;
            height: 3px;
            background: var(--gold-bright);
            border-radius: 50%;
            opacity: 0;
            animation: float-up linear infinite;
            box-shadow: 0 0 4px var(--gold-bright);
        }

        @keyframes float-up {
            0%   { opacity: 0; transform: translateY(0) scale(1); }
            10%  { opacity: 0.8; }
            90%  { opacity: 0.3; }
            100% { opacity: 0; transform: translateY(-80vh) scale(0.3); }
        }

        /* Book container */
        .book {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 640px;
            animation: appear 1.2s ease;
        }

        @keyframes appear {
            from { opacity: 0; transform: translateY(30px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Ornate outer frame */
        .outer-frame {
            background: linear-gradient(160deg, #3a1f0a, #1e0f04, #3a1f0a);
            border-radius: 4px;
            padding: 16px;
            box-shadow:
                0 20px 60px rgba(0,0,0,0.8),
                0 0 0 1px rgba(184,134,11,0.3),
                inset 0 0 30px rgba(0,0,0,0.5);
        }

        /* Inner parchment */
        .parchment {
            background:
                radial-gradient(ellipse at 50% 0%, #f9f0dc 0%, var(--parchment) 40%, var(--parchment-dark) 100%);
            border-radius: 2px;
            border: 1px solid var(--parchment-deeper);
            overflow: hidden;
            position: relative;
        }

        /* Parchment texture overlay */
        .parchment::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='200'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='200' height='200' filter='url(%23n)' opacity='0.06'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
        }

        /* Aged edges */
        .parchment::after {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse at 0% 0%, rgba(139,99,71,0.25) 0%, transparent 40%),
                radial-gradient(ellipse at 100% 0%, rgba(139,99,71,0.2) 0%, transparent 40%),
                radial-gradient(ellipse at 0% 100%, rgba(139,99,71,0.3) 0%, transparent 40%),
                radial-gradient(ellipse at 100% 100%, rgba(139,99,71,0.25) 0%, transparent 40%);
            pointer-events: none;
            z-index: 0;
        }

        /* Header */
        .header {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 36px 40px 28px;
            border-bottom: 2px solid var(--parchment-deeper);
        }

        /* Top ornament */
        .top-ornament {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
            justify-content: center;
        }

        .ornament-line {
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
        }

        .ornament-diamond {
            width: 8px; height: 8px;
            background: var(--gold);
            transform: rotate(45deg);
            box-shadow: 0 0 6px rgba(184,134,11,0.5);
        }

        .ornament-dot {
            width: 4px; height: 4px;
            background: var(--gold-light);
            border-radius: 50%;
        }

        /* Stars / crests */
        .header-crest {
            font-size: 2.2rem;
            margin-bottom: 6px;
            filter: drop-shadow(0 2px 6px rgba(184,134,11,0.5));
            animation: glow-crest 4s ease-in-out infinite;
        }

        @keyframes glow-crest {
            0%, 100% { filter: drop-shadow(0 2px 6px rgba(184,134,11,0.4)); }
            50% { filter: drop-shadow(0 2px 16px rgba(240,192,64,0.8)); }
        }

        h1 {
            font-family: 'Cinzel Decorative', serif;
            font-size: 2.4rem;
            color: var(--gold);
            letter-spacing: 0.08em;
            line-height: 1.1;
            text-shadow:
                0 1px 0 rgba(255,255,255,0.3),
                0 -1px 0 rgba(0,0,0,0.3),
                0 2px 8px rgba(184,134,11,0.4);
            margin-bottom: 8px;
        }

        .subtitle {
            font-family: 'Cinzel', serif;
            font-size: 0.75rem;
            color: var(--ink-faded);
            letter-spacing: 0.25em;
            text-transform: uppercase;
        }

        /* Section divider */
        .section-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 40px;
            margin: 4px 0;
        }

        .section-line {
            flex: 1;
            height: 1px;
            background: var(--parchment-deeper);
        }

        .section-fleur { color: var(--gold); font-size: 1rem; line-height: 1; }

        /* Form */
        .form-body {
            position: relative;
            z-index: 1;
            padding: 28px 40px 36px;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .field-label {
            display: block;
            font-family: 'Cinzel', serif;
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--ink-light);
            margin-bottom: 8px;
        }

        select, textarea, input[type="text"] {
            width: 100%;
            background: rgba(255,255,255,0.35);
            border: 1px solid var(--parchment-deeper);
            border-bottom: 2px solid var(--parchment-deeper);
            border-radius: 1px;
            color: var(--ink);
            font-family: 'IM Fell English', serif;
            font-size: 1rem;
            padding: 10px 14px;
            outline: none;
            transition: all 0.25s;
            box-shadow: inset 0 2px 6px rgba(139,99,71,0.1);
            -webkit-appearance: none;
        }

        select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%238b6347' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 36px;
            cursor: pointer;
        }

        select:focus, textarea:focus, input[type="text"]:focus {
            border-color: var(--gold);
            background: rgba(255,255,255,0.5);
            box-shadow: 0 0 0 3px rgba(184,134,11,0.12), inset 0 2px 6px rgba(139,99,71,0.1);
        }

        select option { background: #f5ead6; color: var(--ink); }

        textarea {
            height: 110px;
            resize: vertical;
            line-height: 1.65;
        }

        /* Mode selector */
        .mode-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .mode-option input[type="radio"] {
            position: absolute;
            opacity: 0; width: 0; height: 0;
        }

        .mode-option { position: relative; }

        .mode-label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 12px 10px;
            border: 1px solid var(--parchment-deeper);
            border-bottom: 2px solid var(--parchment-deeper);
            border-radius: 1px;
            cursor: pointer;
            font-family: 'Cinzel', serif;
            font-size: 0.82rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            color: var(--ink-faded);
            background: rgba(255,255,255,0.2);
            transition: all 0.25s;
            user-select: none;
        }

        .mode-label:hover {
            background: rgba(255,255,255,0.4);
            color: var(--ink-light);
        }

        .mode-option input:checked + .mode-label {
            background: rgba(184,134,11,0.12);
            border-color: var(--gold);
            color: var(--gold);
            box-shadow: 0 0 0 3px rgba(184,134,11,0.1);
        }

        .mode-icon { font-size: 1.1em; }

        /* Note */
        .note {
            font-family: 'IM Fell English', serif;
            font-style: italic;
            font-size: 0.8rem;
            color: var(--ink-faded);
            margin-top: 7px;
            line-height: 1.6;
            padding-left: 4px;
        }

        /* Decorative rule */
        .fancy-rule {
            text-align: center;
            margin: 24px 0;
            color: var(--gold);
            font-size: 1.2rem;
            letter-spacing: 0.4em;
            opacity: 0.7;
        }

        /* Submit button */
        .btn-submit {
            width: 100%;
            padding: 15px 20px;
            background: linear-gradient(180deg, #c9960c 0%, #a07008 50%, #8b5e06 100%);
            border: 1px solid #6b4404;
            border-radius: 2px;
            color: #fff8e8;
            font-family: 'Cinzel Decorative', serif;
            font-size: 0.9rem;
            letter-spacing: 0.12em;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: all 0.3s;
            text-shadow: 0 1px 3px rgba(0,0,0,0.4);
            box-shadow:
                0 4px 0 #5a3804,
                0 6px 12px rgba(0,0,0,0.3),
                inset 0 1px 0 rgba(255,255,255,0.25);
        }

        .btn-submit::before {
            content: '✦';
            margin-right: 12px;
            opacity: 0.8;
        }

        .btn-submit::after {
            content: '✦';
            margin-left: 12px;
            opacity: 0.8;
        }

        .btn-submit:hover {
            background: linear-gradient(180deg, #daa80e 0%, #b8820a 50%, #9a6a08 100%);
            box-shadow:
                0 4px 0 #5a3804,
                0 8px 20px rgba(0,0,0,0.35),
                inset 0 1px 0 rgba(255,255,255,0.3);
            transform: translateY(-1px);
        }

        .btn-submit:active {
            transform: translateY(2px);
            box-shadow:
                0 2px 0 #5a3804,
                0 3px 8px rgba(0,0,0,0.3),
                inset 0 1px 0 rgba(255,255,255,0.2);
        }

        /* Result */
        .result-section {
            position: relative;
            z-index: 1;
        }

        .result-divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
            opacity: 0.5;
        }

        .result-box {
            padding: 24px 40px 32px;
            text-align: center;
            animation: reveal 0.6s ease;
            background: rgba(184,134,11,0.04);
        }

        @keyframes reveal {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .result-scroll-top {
            text-align: center;
            font-size: 1.2rem;
            color: var(--gold);
            margin-bottom: 10px;
            letter-spacing: 0.3em;
        }

        .result-label {
            font-family: 'Cinzel', serif;
            font-size: 0.7rem;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: var(--ink-faded);
            margin-bottom: 12px;
        }

        .result-value {
            font-family: 'IM Fell English', serif;
            font-size: 1.15rem;
            color: var(--red);
            word-break: break-all;
            line-height: 1.8;
            border-top: 1px solid var(--parchment-deeper);
            border-bottom: 1px solid var(--parchment-deeper);
            padding: 14px 10px;
            letter-spacing: 0.05em;
        }

        /* Footer stamp */
        .footer {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 16px 40px 24px;
            border-top: 1px solid var(--parchment-deeper);
        }

        .footer-text {
            font-family: 'Cinzel', serif;
            font-size: 0.65rem;
            letter-spacing: 0.2em;
            color: var(--ink-faded);
            opacity: 0.7;
            text-transform: uppercase;
        }

        .wax-seal {
            display: inline-block;
            width: 44px; height: 44px;
            background: radial-gradient(circle at 40% 35%, #c0392b, #8b1a1a);
            border-radius: 50%;
            margin: 0 auto 8px;
            box-shadow: 0 2px 8px rgba(139,26,26,0.5), inset 0 1px 0 rgba(255,255,255,0.15);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            color: rgba(255,255,255,0.8);
        }

        @media (max-width: 500px) {
            .form-body, .header, .result-box, .footer { padding-left: 24px; padding-right: 24px; }
            .section-divider { padding: 0 24px; }
            h1 { font-size: 1.8rem; }
            .outer-frame { padding: 10px; }
        }
    </style>
</head>
<body>

    <!-- Floating magical particles -->
    <div class="particles" id="particles"></div>

    <div class="book">
        <div class="outer-frame">
            <div class="parchment">

                <!-- Header -->
                <div class="header">
                    <div class = "note">Evan Adkara Christian Putra - 21120123130086 / Kripto C</div>
                    <div class="top-ornament">
                        <div class="ornament-line"></div>
                        <div class="ornament-dot"></div>
                        <div class="ornament-diamond"></div>
                        <div class="ornament-dot"></div>
                        <div class="ornament-line"></div>
                    </div>
                    <div class="header-crest">🔮</div>
                    <h1>ChiperaX</h1>
                    <p class="subtitle">Kitab Sandi Para Penyihir</p>
                    <div class="top-ornament" style="margin-top:16px;margin-bottom:0">
                        <div class="ornament-line"></div>
                        <div class="ornament-dot"></div>
                        <div class="ornament-diamond"></div>
                        <div class="ornament-dot"></div>
                        <div class="ornament-line"></div>
                    </div>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('cipher.process') }}" class="form-body">
                    @csrf

                    <div class="form-group">
                        <label class="field-label" for="cipher">✧ Pilih Mantra Sandi</label>
                        <select name="cipher" id="cipher" required>
                            <option value="vigenere" {{ old('cipher')=='vigenere' ? 'selected' : '' }}>Mantra Vigenere</option>
                            <option value="affine"   {{ old('cipher')=='affine'   ? 'selected' : '' }}>Mantra Affine</option>
                            <option value="playfair" {{ old('cipher')=='playfair' ? 'selected' : '' }}>Mantra Playfair</option>
                            <option value="hill"     {{ old('cipher')=='hill'     ? 'selected' : '' }}>Mantra Hill</option>
                            <option value="enigma"   {{ old('cipher')=='enigma'   ? 'selected' : '' }}>Mantra Enigma (Kuno)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="field-label">✧ Pilih Ritual</label>
                        <div class="mode-group">
                            <div class="mode-option">
                                <input type="radio" name="mode" id="encrypt" value="encrypt" {{ old('mode','encrypt')=='encrypt' ? 'checked' : '' }} required>
                                <label class="mode-label" for="encrypt">
                                    <span class="mode-icon">🌑</span> Sembunyikan
                                </label>
                            </div>
                            <div class="mode-option">
                                <input type="radio" name="mode" id="decrypt" value="decrypt" {{ old('mode')=='decrypt' ? 'checked' : '' }}>
                                <label class="mode-label" for="decrypt">
                                    <span class="mode-icon">🌕</span> Ungkapkan
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="field-label" for="text">✧ Pesan Rahasia</label>
                        <textarea name="text" id="text" placeholder="Tuliskan pesan di sini..." required>{{ old('text') }}</textarea>
                        <p class="note">Hanya rune A–Z yang dikenali oleh mantra ini; simbol lain akan lenyap, dan huruf kecil akan dinaikkan derajatnya.</p>
                    </div>

                    <div class="form-group">
                        <label class="field-label" for="key">✧ Kunci Rahasia</label>
                        <input type="text" name="key" id="key" value="{{ old('key') }}" placeholder="Bisikkan kunci di sini..." required>
                        <p class="note">
                            Vigenere &amp; Playfair: serangkaian rune (huruf) · Affine: dua angka a,b (misal: 5,8) · Hill: 4 atau 9 angka · Enigma: 3 huruf posisi rotor (misal: ABC)
                        </p>
                    </div>

                    <div class="fancy-rule">· ✦ · ✦ · ✦ ·</div>

                    <button type="submit" class="btn-submit">Ucapkan Mantra</button>
                </form>

                @if(session('result'))
                    <div class="result-section">
                        <div class="result-divider"></div>
                        <div class="result-box">
                            <div class="result-scroll-top">⁕ &nbsp; ⁕ &nbsp; ⁕</div>
                            <div class="result-label">Wahyu dari Mantra</div>
                            <div class="result-value">{{ session('result') }}</div>
                        </div>
                    </div>
                @endif

                <div class="footer">
                    <div class="wax-seal">⚜</div>
                    <p class="footer-text">ChiperaX · Warisan Sandi Kuno · Dilindungi Mantra Abadi</p>
                </div>

            </div><!-- .parchment -->
        </div><!-- .outer-frame -->
    </div><!-- .book -->

    <script>
        // Magical floating particles
        const container = document.getElementById('particles');
        for (let i = 0; i < 28; i++) {
            const p = document.createElement('div');
            p.className = 'particle';
            p.style.left = Math.random() * 100 + 'vw';
            p.style.top = (40 + Math.random() * 60) + 'vh';
            p.style.animationDuration = (6 + Math.random() * 12) + 's';
            p.style.animationDelay = (Math.random() * 10) + 's';
            p.style.width = p.style.height = (2 + Math.random() * 3) + 'px';
            p.style.opacity = '0';
            container.appendChild(p);
        }
    </script>
</body>
</html>