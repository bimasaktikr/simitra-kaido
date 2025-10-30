<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>ID Card</title>
<style>
  @page { size: 54mm 85.6mm; margin: 0; }
  html, body {
    width: 54mm;
    height: 85.6mm;
    margin: 0;
    padding: 0;
    overflow: hidden;
    font-family: Arial, Helvetica, sans-serif;
    color: #111;
  }

  * {
    box-sizing: border-box;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
  }

  .card {
    width: 54mm;
    height: 85.6mm;
    border: 0.35mm solid #E1E5EA;
    border-radius: 3mm;
    position: absolute;
  }

  /* Logo */
  .logo {
    position: absolute;
    top: 3mm;
    left: 50%;
    transform: translateX(-50%);
    width: 17mm;
    height: auto;
  }

  /* Badan Pusat Statistik */
  .instansi {
    position: absolute;
    top: 13mm;
    left: 50%;
    transform: translateX(-50%);
    font-size: 3mm;
    letter-spacing: .3mm;
    font-weight: 700;
    text-transform: uppercase;
    text-align: center;
  }

  /* Bingkai foto */
  .photo-frame {
    position: absolute;
    top: 19mm;
    left: 50%;
    transform: translateX(-50%);
    width: 24mm;
    height: 28mm;
    border: 0.35mm solid #CED4DA;
    border-radius: 1.2mm;
    background: #F5F7FA;
    overflow: hidden;
  }

  .photo-frame img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  /* Garis luar nama */
  .panel-outline {
    position: absolute;
    top: 48mm;
    left: 50%;
    transform: translateX(-50%);
    width: 42mm;
    height: 17mm;
    border: 0.35mm solid #CBD3DC;
    border-radius: 2mm;
  }

  /* Nama */
  .name-line {
    position: absolute;
    top: 50mm;
    left: 50%;
    transform: translateX(-50%);
    width: 38mm;
    text-align: center;
    font-weight: 700;
    font-size: 3.2mm;
    letter-spacing: .2mm;
    text-transform: uppercase;
    padding-bottom: 0.4mm;
    border-bottom: 0.35mm solid #1f2937;
  }

  .name-sub {
    position: absolute;
    top: 54mm;
    left: 50%;
    transform: translateX(-50%);
    width: 38mm;
    text-align: center;
    font-weight: 600;
    font-size: 3mm;
    letter-spacing: .3mm;
    text-transform: uppercase;
    padding-bottom: 0.4mm;
    border-bottom: 0.3mm solid #94a3b8;
  }

  /* PETUGAS */
  .role {
    position: absolute;
    bottom: 18mm;
    left: 5mm;
    font-size: 3.2mm;
    font-weight: 800;
    text-transform: uppercase;
  }

  /* Nama survey */
  .survey {
    position: absolute;
    bottom: 6mm;
    left: 5mm;
    width: 32mm;
    font-size: 2.7mm;
    line-height: 1.25;
    text-transform: uppercase;
    white-space: pre-line;
  }

  /* QR Code */
  .qr {
    position: absolute;
    bottom: 4mm;
    right: 4mm;
    width: 20mm;
    height: 20mm;
    border: 0.35mm solid #DEE2E6;
    border-radius: 1.2mm;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
  }

  .qr img {
    width: 94%;
    height: 94%;
    object-fit: contain;
  }

  /* Mencegah page break */
  body, .card {
    page-break-before: avoid;
    page-break-after: avoid;
    page-break-inside: avoid;
  }
</style>
</head>
<body>
  @php
    // === Foto ===
    $absPhoto = null;
    if (!empty($fotoPath) && is_file($fotoPath)) {
      $absPhoto = $fotoPath;
    } else if (!empty($tx->mitra?->photo)) {
      $try = storage_path('app/public/'.$tx->mitra->photo);
      if (is_file($try)) { $absPhoto = $try; }
    }

    // === Nama ===
    $fullName = trim($tx->mitra?->name ?? '');
    $parts = preg_split('/\s+/', $fullName, -1, PREG_SPLIT_NO_EMPTY);
    if (count($parts) > 1) {
        $last = array_pop($parts);
        $name1 = mb_strtoupper(implode(' ', $parts));
        $name2 = mb_strtoupper($last);
    } else {
        $name1 = mb_strtoupper($fullName);
        $name2 = '';
    }

    // === Survey ===
    $surveyName = $survey->masterSurvey->name ?? $survey->name ?? '';
    $wrap = wordwrap(mb_strtoupper($surveyName), 26, "\n");
  @endphp

  <div class="card">
    @php $logo = public_path('images/bps-logo.png'); @endphp
    @if (is_file($logo))
      <img class="logo" src="{{ $logo }}" alt="BPS">
    @endif
    <div class="instansi">Badan Pusat Statistik</div>

    <div class="photo-frame">
      @if($absPhoto)
        <img src="{{ $absPhoto }}" alt="Foto Mitra">
      @else
        <span style="font-size:3mm;color:#9aa5b1;display:flex;align-items:center;justify-content:center;height:100%;">FOTO</span>
      @endif
    </div>

    <div class="panel-outline"></div>
    <div class="name-line">{{ $name1 ?: '—' }}</div>
    <div class="name-sub">{{ $name2 }}</div>

    <div class="role">Petugas</div>
    <div class="survey">{{ $wrap }}</div>

    <div class="qr">
      @if($qrUrl && is_file($qrUrl))
        <img src="{{ $qrUrl }}" alt="QR">
      @else
        <span style="font-size:2.6mm;color:#6b7280;">QR</span>
      @endif
    </div>
  </div>
</body>
</html>
