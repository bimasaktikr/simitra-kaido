<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>{{ $survey->masterSurvey->name ?? $survey->name }} ({{ $survey->year }})</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
@page { margin: 8mm; }
* { box-sizing: border-box; }
body {
  font-family: 'Inter', sans-serif;
  background: #f8f9fa;
  color: #212529;
  font-size: 3.2mm;
  margin: 0;
}

.grid {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 6mm;
}

/* === KARTU === */
.card {
  width: 85.6mm;
  height: 53.98mm;
  background: #fff;
  border: 0.3mm solid #dee2e6;
  border-radius: 3mm;
  box-shadow: 0 0.7mm 2mm rgba(0,0,0,.08);
  padding: 4mm 5mm;
  display: flex;
  align-items: center;
}

/* === TATA LETAK DALAM === */
table.layout {
  width: 100%;
  height: 100%;
  border-collapse: collapse;
  table-layout: fixed;
}

td {
  vertical-align: middle;
  padding: 0 2mm;
}

.photo {
  width: 18mm;
  height: 24mm;
  border: 0.3mm solid #dee2e6;
  border-radius: 1mm;
  background: #f1f3f5;
  text-align: center;
  font-size: 2.8mm;
  color: #868e96;
  display: flex;
  align-items: center;
  justify-content: center;
  text-transform: uppercase;
}

.info {
  width: auto;
  font-size: 3mm;
  line-height: 1.35;
  padding: 0 1mm;
}

.name {
  font-weight: 600;
  font-size: 3.4mm;
  margin-bottom: 1mm;
}

.muted { color: #6c757d; }
.small { font-size: 2.9mm; }

.qr {
  width: 22mm;
  height: 22mm;
  border: 0.3mm solid #dee2e6;
  border-radius: 1.2mm;
  background: #fff;
  text-align: center;
  display: flex;
  align-items: center;
  justify-content: center;
}
.qr img {
  width: 90%;
  height: 90%;
  object-fit: contain;
}
</style>
</head>
<body>

<div class="grid">
@foreach($transactions as $tx)
  <div class="card">
    <table class="layout">
      <tr>
        {{-- FOTO --}}
        <td style="width:20mm;">
          <div class="photo">
            FOTO
            {{-- Uncomment jika foto tersedia --}}
            {{-- 
            @php
              $photoFile = storage_path('app/public/'.$tx->mitra?->photo_path);
            @endphp
            @if($tx->mitra?->photo_path && file_exists($photoFile))
              <img src="{{ $photoFile }}" alt="Foto {{ $tx->mitra?->name }}"
                   style="width:100%;height:100%;object-fit:cover;border-radius:1mm;">
            @endif
            --}}
          </div>
        </td>

        {{-- INFO --}}
        <td class="info">
          <div class="name">{{ $tx->mitra?->name ?? '—' }}</div>
          <div class="small">Survey: {{ $survey->masterSurvey->name ?? $survey->name }} ({{ $survey->year }})</div>

          @php
            $sobat = $tx->mitra?->sobat_id;
            if ($sobat && strlen($sobat) > 6) {
              $sobat = substr($sobat, 0, 3) . str_repeat('*', strlen($sobat) - 6) . substr($sobat, -3);
            }

            $email = $tx->mitra?->email;
            if ($email && strpos($email, '@') !== false) {
              $pos = strpos($email, '@');
              $namePart = substr($email, 0, $pos);
              $masked = substr($namePart, 0, 2) . str_repeat('*', max(0, strlen($namePart) - 4)) . substr($namePart, -1);
              $email = $masked . substr($email, $pos);
            }
          @endphp

          <div class="small">Sobat ID: {{ $sobat ?? '—' }}</div>
          <div class="small">Email: {{ $email ?? '—' }}</div>
          <div class="small">Target: {{ $tx->target }} | Rate: Rp {{ number_format((int)$tx->rate, 0, ',', '.') }}</div>
        </td>

        {{-- QR --}}
        <td style="width:24mm;">
          <div class="qr">
            @php
              $qrFile = storage_path('app/public/'.$tx->qr_path);
            @endphp
            @if($tx->qr_path && file_exists($qrFile))
              <img src="{{ $qrFile }}" alt="QR">
            @else
              <span class="small muted">QR missing</span>
            @endif
          </div>
        </td>
      </tr>
    </table>
  </div>
@endforeach
</div>

</body>
</html>
