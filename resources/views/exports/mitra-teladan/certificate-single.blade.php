<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Sertifikat {{ $certificate['mitra_name'] }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            width: 297mm;
            height: 210mm;
            margin: 0;
            padding: 0;
            overflow: hidden;
            font-family: 'Arial', 'Helvetica', sans-serif;
        }

        .page {
            position: relative;
            width: 297mm;
            height: 210mm;
            background: #ffffff;
            overflow: hidden;
        }

        /* Dekorasi kiri */
        .decoration-left {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 50mm;
            background: #1e3a8a;
        }

        /* Pattern overlay untuk dekorasi kiri */
        .decoration-pattern {
            position: absolute;
            left: 0;
            top: 0;
            width: 50mm;
            height: 100%;
            opacity: 0.1;
        }

        .pattern-circle {
            position: absolute;
            border-radius: 50%;
            background: white;
        }

        .circle-1 { width: 60px; height: 60px; top: 30px; left: 10px; }
        .circle-2 { width: 40px; height: 40px; top: 100px; right: 15px; }
        .circle-3 { width: 50px; height: 50px; top: 180px; left: 20px; }
        .circle-4 { width: 45px; height: 45px; bottom: 150px; right: 10px; }
        .circle-5 { width: 55px; height: 55px; bottom: 50px; left: 15px; }

        /* Dekorasi kanan bawah */
        .decoration-right {
            position: absolute;
            right: 0;
            bottom: 0;
            width: 0;
            height: 0;
            border-style: solid;
            border-width: 0 0 120px 80px;
            border-color: transparent transparent #1e3a8a transparent;
        }

        .decoration-right-pattern {
            position: absolute;
            right: 5px;
            bottom: 5px;
            width: 0;
            height: 0;
            border-style: solid;
            border-width: 0 0 110px 70px;
            border-color: transparent transparent #2563eb transparent;
            opacity: 0.5;
        }

        /* Ribbon badge di kanan atas */
        .ribbon-badge {
            position: absolute;
            top: 30mm;
            right: 30mm;
            width: 100px;
            height: 100px;
            text-align: center;
        }

        .ribbon-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* Content area */
        .content-wrapper {
            position: absolute;
            left: 60mm;
            right: 20mm;
            top: 25mm;
            bottom: 25mm;
        }

        /* Logo header */
        .header-logo {
            margin-bottom: 20px;
            display: table;
            width: 100%;
        }

        .logo-container {
            display: table-cell;
            vertical-align: middle;
            width: 50px;
        }

        .logo-image {
            width: 45px;
            height: 45px;
            object-fit: contain;
            display: block;
        }

        .header-text-container {
            display: table-cell;
            vertical-align: middle;
            padding-left: 12px;
        }

        .header-text {
            font-size: 11pt;
            color: #1e3a8a;
            font-weight: 600;
            line-height: 1.4;
        }

        /* Title */
        .title {
            font-size: 42pt;
            font-weight: 900;
            color: #1e3a8a;
            margin: 15px 0 5px 0;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .subtitle {
            font-size: 24pt;
            color: #1e3a8a;
            font-weight: 600;
            margin-bottom: 25px;
        }

        /* Statement */
        .statement {
            font-size: 11pt;
            color: #4b5563;
            margin-bottom: 12px;
        }

        /* Recipient name */
        .recipient-name {
            font-size: 32pt;
            font-weight: 900;
            color: #f59e0b;
            margin-bottom: 5px;
            padding-bottom: 8px;
            border-bottom: 4px solid #f59e0b;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Description */
        .description {
            font-size: 10.5pt;
            color: #1e3a8a;
            margin: 18px 0;
            line-height: 1.6;
            font-weight: 500;
        }

        .highlight {
            font-weight: 700;
            color: #1e40af;
        }

        /* Details box */
        .details-box {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 12px 20px;
            margin: 18px 0;
            border-radius: 4px;
        }

        .details-box p {
            font-size: 10pt;
            color: #1e3a8a;
            margin: 5px 0;
            font-weight: 500;
        }

        /* Signatures */
        .signatures {
            margin-top: 30px;
        }

        .signature-block {
            text-align: center;
        }

        .signature-block p {
            font-size: 9pt;
            color: #4b5563;
            margin: 3px 0;
            line-height: 1.4;
        }

        .signature-line {
            height: 50px;
            margin: 8px 0;
        }

        .signature-name {
            font-weight: 700;
            color: #1e3a8a;
            font-size: 10pt;
            border-bottom: 2px solid #1e3a8a;
            display: inline-block;
            padding: 0 20px 3px;
            margin-top: 5px;
        }

        .signature-title {
            font-size: 8pt;
            color: #6b7280;
            margin-top: 3px;
        }

        /* Footer info */
        .footer-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 10px 0;
            border-top: 1px solid #e5e7eb;
            font-size: 7.5pt;
            color: #6b7280;
        }

        .footer-left {
            display: inline-block;
            width: 48%;
            vertical-align: top;
        }

        .footer-right {
            display: inline-block;
            width: 48%;
            text-align: right;
            vertical-align: top;
        }

        .footer-info p {
            margin: 2px 0;
            line-height: 1.4;
        }

        /* Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 55%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 70px;
            font-weight: 900;
            color: rgba(30, 58, 138, 0.03);
            z-index: 0;
            letter-spacing: 8px;
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Dekorasi kiri -->
        <div class="decoration-left"></div>
        <div class="decoration-pattern">
            <div class="pattern-circle circle-1"></div>
            <div class="pattern-circle circle-2"></div>
            <div class="pattern-circle circle-3"></div>
            <div class="pattern-circle circle-4"></div>
            <div class="pattern-circle circle-5"></div>
        </div>

        <!-- Dekorasi kanan bawah -->
        <div class="decoration-right"></div>
        <div class="decoration-right-pattern"></div>

        <!-- Ribbon badge -->
        <div class="ribbon-badge">
            <img src="{{ public_path('images/ribbon.png') }}" alt="Ribbon" class="ribbon-image">
        </div>

        <!-- Watermark -->
        <div class="watermark">BPS MALANG</div>

        <!-- Content -->
        <div class="content-wrapper">
            <!-- Header -->
            <div class="header-logo">
                <div class="logo-container">
                    <img src="{{ public_path('images/logo.png') }}" alt="Logo BPS" class="logo-image">
                </div>
                <div class="header-text-container">
                    <div class="header-text">
                        Badan Pusat Statistik<br>Kota Malang
                    </div>
                </div>
            </div>

            <!-- Title -->
            <div class="title">SERTIFIKAT</div>
            <div class="subtitle">Mitra Teladan</div>

            <!-- Statement -->
            <p class="statement">Menyatakan bahwa mitra bernama :</p>

            <!-- Recipient -->
            <div class="recipient-name">{{ $certificate['mitra_name'] }}</div>

            <!-- Description -->
            <div class="description">
                Telah menunjukkan <span class="highlight">dedikasi dan kinerja luar biasa</span> sebagai Mitra Teladan
                dalam mendukung kegiatan statistik dengan <span class="highlight">komitmen tinggi</span> dan
                <span class="highlight">kinerja yang inspiratif</span> pada Tim <span class="highlight">{{ $certificate['team_name'] }}</span>.
            </div>

            <!-- Details -->
            <div class="details-box">
                <p><strong>Peringkat:</strong> {{ strtoupper($certificate['ranking_text']) }}</p>
                <p><strong>Nilai Akhir:</strong> {{ $certificate['score'] }}</p>
                <p><strong>Periode:</strong> {{ $certificate['period_text'] }}</p>
            </div>

            <!-- Signatures -->
            <div class="signatures">
                <div class="signature-block">
                    <p>{{ $certificate['signatory']['city'] }}, {{ $certificate['signatory']['date'] }}</p>
                    <p><strong>{{ $certificate['signatory']['position'] }}</strong></p>
                    <div class="signature-line"></div>
                    <p class="signature-name">{{ $certificate['signatory']['name'] }}</p>
                    <p class="signature-title">{{ $certificate['signatory']['nip'] }}</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer-info">
                <div class="footer-left">
                    <p><strong>Nomor:</strong> {{ $certificate['certificate_number'] }}</p>
                    <p><strong>Tanggal:</strong> {{ $certificate['issue_date'] }}</p>
                </div>
                <div class="footer-right">
                    <p>Jl. Janti Bar. No.47, Sukun, Kota Malang</p>
                    <p>Telepon: (0341) 801164 | Email: bps3573@bps.go.id</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
