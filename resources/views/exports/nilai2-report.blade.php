<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 24px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        th, td { border: 1px solid #333; padding: 2px 4px; text-align: left; word-break: break-word; }
        th { background: #eee; }
        h2 { margin-top: 24px; margin-bottom: 8px; font-size: 14px; }
        .page-break { page-break-before: always; }
    </style>
</head>
<body>
    <h1>Laporan Pemilihan Mitra Teladan Tahun {{ $year ?? '-' }} Triwulan {{ $quarter ?? '-' }}</h1>

    @foreach ($reportData as $i => $group)
        @if($i > 0)
            <div class="page-break"></div>
        @endif
        <h2>{{ $group['mitraName'] }} - {{ $group['teamName'] }}</h2>
        <table>
            <thead>
                <tr>
                    <th>Nama Pegawai</th>
                    @for ($i = 1; $i <= 10; $i++)
                        <th>aspek {{ $i }}: {{ $aspekDescriptions[$i] ?? '' }}</th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @forelse ($group['nilai2List'] as $row)
                    <tr>
                        <td>{{ $row['employeeName'] }}</td>
                        <td>{{ $row['aspek1'] }}</td>
                        <td>{{ $row['aspek2'] }}</td>
                        <td>{{ $row['aspek3'] }}</td>
                        <td>{{ $row['aspek4'] }}</td>
                        <td>{{ $row['aspek5'] }}</td>
                        <td>{{ $row['aspek6'] }}</td>
                        <td>{{ $row['aspek7'] }}</td>
                        <td>{{ $row['aspek8'] }}</td>
                        <td>{{ $row['aspek9'] }}</td>
                        <td>{{ $row['aspek10'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" style="text-align:center">noData</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endforeach

    @if(isset($mitraRanking) && count($mitraRanking))
        <div class="page-break"></div>
        <h2>Ranking Mitra Teladan</h2>
        <table>
            <thead>
                <tr>
                    <th>rank</th>
                    <th>mitraName</th>
                    <th>avgRerata</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mitraRanking as $row)
                    <tr @if($row['rank'] === 1) style="background:#d1fae5;font-weight:bold" @endif>
                        <td>{{ $row['rank'] }}</td>
                        <td>{{ $row['mitraName'] }}</td>
                        <td>{{ $row['avgRerata'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
