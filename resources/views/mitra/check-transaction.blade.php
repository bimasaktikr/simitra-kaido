@php($title = 'Detail Mitra & Survey')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Inter, sans-serif;
            margin: 0;
            background: #f7f7fb;
            color: #111;
        }

        main { max-width: 1000px; margin: 0 auto; padding: 24px; }
        .card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 1px 2px rgba(0,0,0,.03);
            padding: 20px;
            margin-bottom: 20px;
        }

        h2 { margin: 0 0 12px; font-size: 20px; }
        h3 { margin: 0 0 8px; font-size: 16px; }

        table { width: 100%; border-collapse: collapse; }
        td { padding: 6px 4px; vertical-align: top; }
        .muted { color:#6b7280; width: 140px; }
        .value { font-weight: 500; }

        .avatar {
            width: 140px;
            height: 140px;
            border-radius: 12px;
            object-fit: cover;
            border:1px solid #e5e7eb;
        }

        .section-title {
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 16px;
        }

        .flex { display: flex; flex-wrap: wrap; gap: 20px; }
        .flex-item { flex: 1 1 320px; }

        code { background: #f3f4f6; padding: 2px 4px; border-radius: 4px; font-size: 13px; }
    </style>
</head>
<body>
<main>

    {{-- === INFORMASI SURVEY === --}}
    <div class="card">
        <h2>Informasi Survey</h2>
        <table>
            <tr>
                <td class="muted">Nama Survey</td>
                <td>:</td>
                <td class="value">{{ $survey->masterSurvey->name ?? $survey->name ?? '—' }}</td>
            </tr>
            <tr>
                <td class="muted">Kode</td>
                <td>:</td>
                <td class="value">{{ $survey->masterSurvey->code ?? $survey->code ?? '—' }}</td>
            </tr>
            <tr><td class="muted">Tahun</td><td>:</td><td class="value">{{ $survey->year ?? '—' }}</td></tr>
            <tr><td class="muted">Finalisasi Nilai</td><td>:</td>
                <td class="value">
                    @if($survey->is_scored)
                        <span style="color:green;font-weight:600;">Sudah Difinalisasi</span>
                    @else
                        <span style="color:#b91c1c;font-weight:600;">Belum Difinalisasi</span>
                    @endif
                </td>
            </tr>
            <tr><td class="muted">Total Mitra</td><td>:</td><td class="value">{{ $survey->transactions()->count() }}</td></tr>
        </table>
    </div>

    {{-- === INFORMASI MITRA === --}}
    <div class="card">
        <h2>Informasi Mitra</h2>
        <div class="flex">
            <div class="flex-item">
                @if($mitra?->photo)
                    <img class="avatar" src="{{ asset('storage/'.$mitra->photo) }}" alt="Foto {{ $mitra->name }}">
                @else
                    <div style="width:140px;height:140px;border:1px dashed #d1d5db;border-radius:12px;display:flex;align-items:center;justify-content:center;color:#9ca3af;">FOTO</div>
                @endif
            </div>
            <div class="flex-item">
                <table>
                    <tr><td class="muted">Nama</td><td>:</td><td class="value">{{ $mitra->name ?? '—' }}</td></tr>
                </table>
            </div>
        </div>
    </div>

</main>
</body>
</html>
