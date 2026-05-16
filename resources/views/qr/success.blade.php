<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Pesanan Diterima – RasaKopi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --brown-900: #2C1A0E;
            --brown-800: #3D2314;
            --brown-700: #5C3317;
            --amber-400: #FBBF24;
            --cream-100: #FFF8F0;
            --cream-200: #FFF0DC;
            --surface: #FFFFFF;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, var(--brown-900) 0%, var(--brown-700) 60%, #8B4513 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .success-card {
            background: var(--surface);
            border-radius: 28px;
            padding: 40px 28px;
            text-align: center;
            width: 100%;
            max-width: 380px;
            box-shadow: 0 24px 64px rgba(0,0,0,0.3);
        }

        /* ── ANIMATED CHECKMARK ──────────────── */
        .check-wrapper {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, #D1FAE5, #A7F3D0);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            animation: popIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }

        @keyframes popIn {
            0% { transform: scale(0); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        .check-icon {
            font-size: 44px;
            animation: fadeUp 0.4s ease 0.3s both;
        }

        @keyframes fadeUp {
            0% { transform: translateY(10px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        /* ── TITLE ───────────────────────────── */
        .success-title {
            font-size: 24px;
            font-weight: 800;
            color: var(--brown-900);
            letter-spacing: -0.5px;
            margin-bottom: 8px;
            animation: fadeUp 0.4s ease 0.4s both;
        }

        .success-subtitle {
            font-size: 14px;
            color: #6B7280;
            font-weight: 500;
            line-height: 1.6;
            animation: fadeUp 0.4s ease 0.5s both;
        }

        .success-name {
            color: var(--brown-700);
            font-weight: 800;
        }

        /* ── INFO CARDS ──────────────────────── */
        .info-row {
            display: flex;
            gap: 10px;
            margin: 24px 0;
            animation: fadeUp 0.4s ease 0.6s both;
        }

        .info-chip {
            flex: 1;
            background: var(--cream-200);
            border-radius: 14px;
            padding: 14px 10px;
        }

        .info-chip-icon { font-size: 22px; margin-bottom: 4px; }

        .info-chip-label {
            font-size: 11px;
            color: #9C7B6A;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .info-chip-value {
            font-size: 14px;
            font-weight: 800;
            color: var(--brown-900);
        }

        /* ── WAITING INFO ────────────────────── */
        .waiting-box {
            background: linear-gradient(135deg, var(--brown-900), var(--brown-700));
            border-radius: 16px;
            padding: 16px 18px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            text-align: left;
            animation: fadeUp 0.4s ease 0.7s both;
        }

        .waiting-icon {
            font-size: 28px;
            flex-shrink: 0;
        }

        .waiting-text {
            color: rgba(255,255,255,0.9);
            font-size: 13px;
            font-weight: 500;
            line-height: 1.5;
        }

        .waiting-text strong {
            color: var(--amber-400);
            display: block;
            margin-bottom: 2px;
            font-weight: 700;
        }

        /* ── BUTTONS ─────────────────────────── */
        .btn-order-more {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--brown-800), var(--brown-700));
            color: #fff;
            border: none;
            border-radius: 14px;
            font-size: 15px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            text-decoration: none;
            display: block;
            margin-bottom: 10px;
            animation: fadeUp 0.4s ease 0.8s both;
        }

        .btn-order-more:active { opacity: 0.9; }
    </style>
</head>
<body>

    <div class="success-card">

        {{-- CHECKMARK --}}
        <div class="check-wrapper">
            <span class="check-icon">✓</span>
        </div>

        {{-- TITLE --}}
        <h1 class="success-title">Pesanan Diterima!</h1>
        <p class="success-subtitle">
            Yeay, <span class="success-name">{{ $name }}</span>! Pesanan kamu sudah berhasil masuk ke dapur kami 🎉
        </p>

        {{-- INFO CHIPS --}}
        <div class="info-row">
            @if($tableNumber && $tableNumber !== 'Tanpa Meja')
                <div class="info-chip">
                    <div class="info-chip-icon">🪑</div>
                    <div class="info-chip-label">Meja</div>
                    <div class="info-chip-value">{{ $tableNumber }}</div>
                </div>
            @endif
            <div class="info-chip">
                <div class="info-chip-icon">💳</div>
                <div class="info-chip-label">Pembayaran</div>
                <div class="info-chip-value">QRIS</div>
            </div>
            <div class="info-chip">
                <div class="info-chip-icon">☕</div>
                <div class="info-chip-label">Status</div>
                <div class="info-chip-value">Diproses</div>
            </div>
        </div>

        {{-- WAITING INFO --}}
        <div class="waiting-box">
            <span class="waiting-icon">⏳</span>
            <div class="waiting-text">
                <strong>Pesanan sedang kami siapkan!</strong>
                Silakan tunggu di meja ya. Kami akan segera mengantarkan pesanan kamu. Terima kasih sudah mampir! 🙏
            </div>
        </div>

        {{-- BACK TO MENU --}}
        <a href="/qr-menu" class="btn-order-more">☕ Pesan Lagi</a>

    </div>

</body>
</html>
