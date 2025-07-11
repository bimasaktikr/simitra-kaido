<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 16px; background: #f8fafc; color: #222; }
        .modal {
            max-width: 480px;
            margin: 80px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            padding: 32px 24px;
            text-align: center;
        }
        .modal h2 { font-size: 1.5rem; margin-bottom: 1rem; color: #e53e3e; }
        .modal p { margin-bottom: 1.5rem; }
        .modal a { display: inline-block; margin-top: 1rem; color: #2563eb; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="modal">
        <h2>Export Gagal</h2>
        <p>Tidak semua Mitra Teladan sudah difinalisasi untuk tahun {{ $year }} triwulan {{ $quarter }}.<br>
        Silakan finalisasi semua Mitra Teladan terlebih dahulu sebelum melakukan export.</p>
        <a href="javascript:history.back()">Kembali</a>
    </div>
</body>
</html>
