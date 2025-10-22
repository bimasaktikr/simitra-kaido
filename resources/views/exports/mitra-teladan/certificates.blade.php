<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Sertifikat Mitra Teladan Q{{ $metadata['quarter'] }} {{ $metadata['year'] }}</title>
    <style>
        /*@page {
            size: A4 landscape;
            margin: 15mm;
        }*/

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .page {
            page-break-after: always;
            height: 100%;
        }

        .page:last-child {
            page-break-after: auto;
        }

        .container {
            height: 100%;
            display: table;
            width: 100%;
        }

        /*.border {
            border: 3px solid #000;
            padding: 8mm;
            height: 100%;
            display: table;
            width: 100%;
        }*/

        /*.inner-border {
            border: 1px solid #000;
            padding: 6mm;
            height: 100%;
            display: table-cell;
            vertical-align: top;
        }*/

        .content-wrapper {
            position: relative;
            height: 100%;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 6px;
            margin-bottom: 3%;
        }

        .header h2 {
            font-size: 14pt;
            margin-bottom: 4px;
        }

        .header p {
            font-size: 8pt;
            margin: 2px 0;
        }

        .title {
            font-size: 22pt;
            font-weight: bold;
            text-align: center;
            margin: 3% 0 1%;
        }

        .subtitle {
            font-size: 16pt;
            text-align: center;
            margin-bottom: 3%;
            font-style: italic;
        }

        .intro {
            text-align: center;
            margin: 2% 0;
            font-size: 11pt;
        }

        .recipient {
            border: 2px solid #000;
            padding: 12px;
            margin: 3% auto;
            text-align: center;
            max-width: 50%;
        }

        .recipient-name {
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 6px;
        }

        .recipient-team {
            font-size: 11pt;
        }

        .description {
            text-align: center;
            margin: 3% 10%;
            font-size: 10pt;
            line-height: 1.4;
        }

        .badge {
            border: 2px solid #000;
            padding: 8px 20px;
            margin: 3% auto;
            text-align: center;
            width: 30%;
            font-weight: bold;
            font-size: 12pt;
        }

        .details {
            text-align: center;
            margin: 3% 0;
            font-size: 10pt;
        }

        .details p {
            margin: 4px 0;
        }

        .footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
        }

        .footer-table {
            width: 100%;
            font-size: 9pt;
        }

        .footer-left {
            width: 50%;
            vertical-align: bottom;
        }

        .footer-left p {
            margin: 2px 0;
        }

        .footer-right {
            width: 50%;
            vertical-align: bottom;
            text-align: center;
        }

        .signature p {
            margin: 2px 0;
        }

        .signature-space {
            height: 35px;
        }

        .signature-name {
            border-bottom: 2px solid #000;
            padding-bottom: 2px;
            font-weight: bold;
            display: inline-block;
            min-width: 140px;
        }
    </style>
</head>
<body>
    @foreach($certificates as $cert)
    <div class="page">
        <div class="border">
            <div class="inner-border">
                <div class="content-wrapper">
                    <!-- Header -->
                    <div class="header">
                        <h2>BADAN PUSAT STATISTIK KOTA MALANG</h2>
                        <p>Jl. Janti Bar. No.47, Bandungrejosari, Kec. Sukun, Kota Malang, Jawa Timur 65148</p>
                        <p>Telepon: (0341) 801164 | Email: bps3573@bps.go.id</p>
                    </div>

                    <!-- Content -->
                    <div class="title">SERTIFIKAT PENGHARGAAN</div>
                    <div class="subtitle">Mitra Teladan</div>

                    <p class="intro">Diberikan kepada:</p>

                    <div class="recipient">
                        <div class="recipient-name">{{ $cert['mitra_name'] }}</div>
                        <div class="recipient-team">Tim: <b>{{ $cert['team_name'] }}</b></div>
                    </div>

                    <p class="description">
                        Atas prestasi sebagai Mitra Teladan dalam mendukung kegiatan statistik
                        dengan komitmen tinggi dan kinerja yang inspiratif.
                    </p>

                    <div class="badge">
                        PERINGKAT {{ strtoupper($cert['ranking_text']) }}
                    </div>

                    <div class="details">
                        <p>Nilai Akhir: <b>{{ $cert['score'] }}</b></p>
                        <p>Periode: <b>{{ $cert['period_text'] }}</b></p>
                    </div>

                    <!-- Footer -->
                    <div class="footer">
                        <table class="footer-table" cellpadding="0" cellspacing="0">
                            <tr>
                                <td class="footer-left">
                                    <p><b>Nomor:</b> {{ $cert['certificate_number'] }}</p>
                                    <p><b>Tanggal:</b> {{ $cert['issue_date'] }}</p>
                                </td>
                                <td class="footer-right">
                                    <div class="signature">
                                        <p>{{ $cert['signatory']['city'] }}, {{ $cert['signatory']['date'] }}</p>
                                        <p><b>{{ $cert['signatory']['position'] }}</b></p>
                                        <div class="signature-space"></div>
                                        <p class="signature-name">{{ $cert['signatory']['name'] }}</p>
                                        <p>{{ $cert['signatory']['nip'] }}</p>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</body>
</html>
