<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak QR Code Meja — RasaKopi</title>
    <style>
        body { font-family: sans-serif; background: #f3f4f6; margin: 0; padding: 40px; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; }
        .card { background: #fff; padding: 20px; border-radius: 12px; text-align: center; border: 1px solid #e5e7eb; page-break-inside: avoid; }
        .logo { font-weight: bold; font-size: 14px; color: #78350f; margin-bottom: 10px; display: block; }
        .qr-img { width: 150px; height: 150px; margin: 10px 0; }
        .table-num { font-size: 20px; font-weight: 800; color: #111827; margin: 5px 0; }
        .instruction { font-size: 10px; color: #6b7280; margin-top: 10px; }
        @media print {
            body { background: #fff; padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 30px; background: #111827; color: #fff; padding: 15px; border-radius: 8px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h3 style="margin: 0;">Siap Cetak QR Code</h3>
            <p style="margin: 5px 0 0; font-size: 12px; opacity: 0.8;">Gunakan kertas stiker atau kertas tebal untuk hasil terbaik.</p>
        </div>
        <button onclick="window.print()" style="background: #fbbf24; color: #000; border: none; padding: 10px 20px; border-radius: 6px; font-weight: bold; cursor: pointer;">🖨️ Cetak Sekarang</button>
    </div>

    <div class="grid">
        @foreach($tables as $table)
        <div class="card">
            <span class="logo">☕ RASAKOPI</span>
            <div style="background: #fff; padding: 5px; display: inline-block; border: 1px solid #eee;">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(url('/qr-menu?meja=' . $table->number)) }}" class="qr-img" alt="QR Meja {{ $table->number }}">
            </div>
            <div class="table-num">MEJA {{ $table->number }}</div>
            <div class="instruction">SCAN UNTUK PESAN DARI MEJA</div>
        </div>
        @endforeach
    </div>

</body>
</html>
