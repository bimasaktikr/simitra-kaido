<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Mitra Teladan Q{{ $metadata['quarter'] }} {{ $metadata['year'] }}</title>
    <style>
        @page {
            margin: 2cm 2cm 2cm 2cm;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12px;
            line-height: 1.6;
            color: #000;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        h2 {
            text-align: center;
            font-size: 14px;
            margin: 20px 0;
            text-transform: uppercase;
        }
        
        .intro {
            text-align: justify;
            margin: 20px 0;
        }
        
        table.main-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        table.main-table th,
        table.main-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        
        table.main-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        
        table.main-table td:first-child,
        table.main-table td:nth-child(4),
        table.main-table td:nth-child(5) {
            text-align: center;
        }
        
        .lampiran-title {
            font-size: 13px;
            font-weight: bold;
            margin: 30px 0 15px 0;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
        }
        
        .mitra-info {
            margin: 15px 0;
            padding: 10px;
            background-color: #f9f9f9;
            border-left: 4px solid #333;
        }
        
        table.detail-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        
        table.detail-table th,
        table.detail-table td {
            border: 1px solid #000;
            padding: 6px 10px;
        }
        
        table.detail-table th {
            background-color: #e0e0e0;
            font-weight: bold;
            width: 70%;
        }
        
        table.detail-table td {
            text-align: center;
            width: 30%;
        }
        
        .summary-row {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        
        .footer-note {
            margin-top: 30px;
            font-size: 10px;
            color: #666;
            text-align: center;
        }

        /* Styles untuk nilai2-report */
        table.nilai2-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 10px;
        }
        
        table.nilai2-table th,
        table.nilai2-table td {
            border: 1px solid #333;
            padding: 4px 6px;
            text-align: left;
            word-break: break-word;
        }
        
        table.nilai2-table th {
            background-color: #e0e0e0;
            font-weight: bold;
            text-align: center;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            margin: 20px 0 10px 0;
            padding: 5px 10px;
            background-color: #f0f0f0;
            border-left: 4px solid #333;
        }
    </style>
</head>
<body>
    {{-- HALAMAN 1: SURAT RESMI --}}
    @include('exports.mitra-teladan.header')
    
    <h2>
        Pengumuman Mitra Teladan<br>
        Kuartal {{ $metadata['quarter_name'] }} Tahun {{ $metadata['year'] }}
    </h2>
    
    <div class="intro">
        <p style="text-indent: 40px;">
            Berdasarkan hasil penilaian kinerja yang telah dilakukan melalui dua fase penilaian,
            dengan ini diumumkan <strong>Mitra Teladan</strong> periode 
            <strong>Kuartal {{ $metadata['quarter_name'] }} Tahun {{ $metadata['year'] }}</strong> 
            sebagai berikut:
        </p>
    </div>
    
    <table class="main-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 35%;">Nama Mitra</th>
                <th style="width: 25%;">Tim</th>
                <th style="width: 20%;">Nilai Akhir</th>
                <th style="width: 15%;">Jumlah Penilai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topMitra as $index => $mt)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $mt->mitra->name }}</td>
                <td>{{ $mt->team->name }}</td>
                <td>{{ number_format($mt->avg_rating_2, 2) }}</td>
                <td>{{ $mt->surveys_count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="intro">
        <p style="text-indent: 40px;">
            Demikian pengumuman ini dibuat untuk dapat digunakan sebagaimana mestinya.
            Detail penilaian per aspek terlampir pada halaman berikutnya.
        </p>
    </div>
    
    @include('exports.mitra-teladan.footer', ['signatory' => $signatory])
    
    <div class="footer-note">
        Dokumen ini dibuat secara elektronik pada {{ $metadata['generated_at'] }} pukul {{ $metadata['generated_time'] }} WIB
    </div>
    
    {{-- PAGE BREAK --}}
    <div class="page-break"></div>
    
    {{-- LAMPIRAN 1: DETAIL TOP 5 MITRA --}}
    <div class="lampiran-title">
        LAMPIRAN I<br>
        DETAIL PENILAIAN TOP 5 MITRA TELADAN Q{{ $metadata['quarter'] }} {{ $metadata['year'] }}
    </div>
    
    @foreach($topMitra as $index => $mt)
        @php
            $detail = $mitraDetails[$mt->id];
        @endphp
        
        <div class="mitra-info">
            <strong>{{ $index + 1 }}. {{ $mt->mitra->name }}</strong><br>
            Tim: {{ $mt->team->name }} | 
            Periode: Q{{ $mt->quarter }} {{ $mt->year }} | 
            Total Penilai: {{ $detail['total_penilai'] }} pegawai
        </div>
        
        <table class="detail-table">
            <thead>
                <tr>
                    <th>Aspek Penilaian (Fase 2)</th>
                    <th>Rata-rata Nilai</th>
                </tr>
            </thead>
            <tbody>
                @foreach($detail['fase_2_aspek'] as $num => $aspekName)
                <tr>
                    <td>{{ $num }}. {{ $aspekName }}</td>
                    <td>{{ number_format($detail['aspek_averages'][$num] ?? 0, 2) }}</td>
                </tr>
                @endforeach
                <tr class="summary-row">
                    <td>RATA-RATA KESELURUHAN</td>
                    <td>{{ number_format($mt->avg_rating_2, 2) }}</td>
                </tr>
            </tbody>
        </table>
        
        @if(!$loop->last)
            <div style="margin: 30px 0; border-bottom: 1px dashed #ccc;"></div>
        @endif
    @endforeach

    {{-- PAGE BREAK --}}
    <div class="page-break"></div>

    {{-- LAMPIRAN 2: DETAIL PENILAIAN SEMUA MITRA (nilai2-report) --}}
    <div class="lampiran-title">
        LAMPIRAN II<br>
        DETAIL PENILAIAN SELURUH MITRA TELADAN Q{{ $metadata['quarter'] }} {{ $metadata['year'] }}
    </div>

    @if(isset($reportData) && count($reportData))
        @foreach ($reportData as $i => $group)
            @if($i > 0)
                <div class="page-break"></div>
            @endif
            
            <div class="section-title">
                {{ $group['mitraName'] }} - {{ $group['teamName'] }}
            </div>
            
            <table class="nilai2-table">
                <thead>
                    <tr>
                        <th style="width: 15%;">Nama Pegawai</th>
                        @for ($j = 1; $j <= 10; $j++)
                            <th style="width: 8.5%;">{{ $j }}. {{ $aspekDescriptions[$j] ?? 'Aspek '.$j }}</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @forelse ($group['nilai2List'] as $row)
                        <tr>
                            <td>{{ $row['employeeName'] }}</td>
                            <td style="text-align: center;">{{ $row['aspek1'] }}</td>
                            <td style="text-align: center;">{{ $row['aspek2'] }}</td>
                            <td style="text-align: center;">{{ $row['aspek3'] }}</td>
                            <td style="text-align: center;">{{ $row['aspek4'] }}</td>
                            <td style="text-align: center;">{{ $row['aspek5'] }}</td>
                            <td style="text-align: center;">{{ $row['aspek6'] }}</td>
                            <td style="text-align: center;">{{ $row['aspek7'] }}</td>
                            <td style="text-align: center;">{{ $row['aspek8'] }}</td>
                            <td style="text-align: center;">{{ $row['aspek9'] }}</td>
                            <td style="text-align: center;">{{ $row['aspek10'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" style="text-align:center; font-style: italic; color: #666;">
                                Tidak ada data penilaian
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @endforeach

        {{-- Ranking Semua Mitra --}}
        @if(isset($mitraRanking) && count($mitraRanking))
            <div class="page-break"></div>
            
            <div class="lampiran-title">
                RANKING KESELURUHAN MITRA TELADAN<br>
                Q{{ $metadata['quarter'] }} {{ $metadata['year'] }}
            </div>
            
            <table class="main-table">
                <thead>
                    <tr>
                        <th style="width: 10%;">Peringkat</th>
                        <th style="width: 60%;">Nama Mitra</th>
                        <th style="width: 30%;">Rata-rata Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mitraRanking as $row)
                        <tr @if($row['rank'] <= 5) style="background:#d1fae5;font-weight:bold" @endif>
                            <td style="text-align: center;">{{ $row['rank'] }}</td>
                            <td>{{ $row['mitraName'] }}</td>
                            <td style="text-align: center;">{{ $row['avgRerata'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @else
        <p style="text-align: center; margin: 30px 0; color: #666; font-style: italic;">
            Data penilaian detail tidak tersedia
        </p>
    @endif
    
    <div class="footer-note" style="margin-top: 50px;">
        --- Akhir Dokumen ---
    </div>
</body>
</html>