<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>ID Card - BPS</title>
<style>
  @page { size: 54mm 85.6mm; margin: 0; }
  
  body {
    width: 54mm;
    height: 85.6mm;
    margin: 0;
    padding: 0;
    font-family: Helvetica;
    color: #111;
  }

  * { box-sizing: border-box; }

  table {
    width: 54mm;
    height: 85.6mm;
    border-collapse: collapse;
    padding: 0;
    margin: 0;
  }

  td {
    padding: 0;
    margin: 0;
    vertical-align: top;
  }

  .main-container {
    width: 48mm;
    margin: 0 auto;
    padding-top: 2.5mm;
  }

  .text-center {
    text-align: center;
  }

  .logo {
    width: 17mm;
    height: auto;
    display: block;
    margin: 0 auto 0.8mm;
  }

  .instansi {
    font-size: 2.8mm;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.3mm;
    margin-bottom: 1.5mm;
  }

  .panel-outline {
    width: 42mm;
    border: 0.2mm solid #111;
    border-radius: 2mm;
    padding: 2mm 0;
    margin: 0 auto 1.5mm;
    text-align: center;
  }

  .photo-frame {
    width: 22mm;
    height: 25mm;
    border: 0.2mm solid #CED4DA;
    border-radius: 1.2mm;
    background: #F5F7FA;
    overflow: hidden;
    margin: 0 auto 1.5mm;
    text-align: center;
    line-height: 25mm;
  }

  .photo-frame img {
    width: 22mm;
    height: 25mm;
    vertical-align: middle;
  }

  .photo-frame span {
    font-size: 2.8mm;
    color: #9AA5B1;
    vertical-align: middle;
  }

  .name-line {
    width: 36mm;
    margin: 0 auto 1.2mm;
    font-weight: 700;
    font-size: 2.9mm;
    letter-spacing: 0.2mm;
    text-transform: uppercase;
    border-bottom: 0.2mm solid #111;
    padding-bottom: 0.3mm;
  }

  .name-sub {
    width: 36mm;
    margin: 0 auto 1.5mm;
    font-weight: 700;
    font-size: 2.9mm;
    letter-spacing: 0.3mm;
    text-transform: uppercase;
    border-bottom: 0.2mm solid #111;
    padding-bottom: 0.3mm;
  }

  .footer-row {
    width: 48mm;
    margin: 0 auto;
  }

  .footer-row table {
    width: 100%;
    height: auto;
    border-collapse: collapse;
  }

  .footer-row td {
    vertical-align: bottom;
    padding: 0;
  }

  .role {
    font-size: 2.9mm;
    font-weight: 800;
    text-transform: uppercase;
    text-decoration: underline;
    margin-bottom: 0.3mm;
  }

  .survey {
    font-size: 2mm;
    line-height: 1.15;
    text-transform: uppercase;
  }

  .qr {
    width: 17mm;
    height: 17mm;
    border: 0.35mm solid #DEE2E6;
    border-radius: 1.2mm;
    text-align: center;
    line-height: 17mm;
    float: right;
  }

  .qr img {
    width: 16mm;
    height: 16mm;
    vertical-align: middle;
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

  <table>
    <tr>
      <td>
        <div class="main-container">
          <!-- Header -->
          <div class="text-center">
            @php $logo = public_path('images/bps-logo.png'); @endphp
            @if (is_file($logo))
              <img class="logo" src="{{ $logo }}" alt="BPS">
            @endif
            <div class="instansi">Badan Pusat Statistik</div>
          </div>

          <!-- Panel -->
          <div class="panel-outline">
            <div class="photo-frame">
              @if($absPhoto)
                <img src="{{ $absPhoto }}" alt="Foto Mitra">
              @else
                <span>FOTO</span>
              @endif
            </div>
            <div class="name-line">{{ $name1 ?: '—' }}</div>
            @if($name2)
            <div class="name-sub">{{ $name2 }}</div>
            @endif
          </div>

          <!-- Footer -->
          <div class="footer-row">
            <table>
              <tr>
                <td style="width: 60%; padding-left: 2mm;">
                  <div class="role">Petugas</div>
                  <div class="survey">{!! nl2br(e($wrap)) !!}</div>
                </td>
                <td style="width: 40%; padding-right: 2mm;">
                  <div class="qr">
                    <img src="{{ $qrUrl }}" alt="QR">
                  </div>
                </td>
              </tr>
            </table>
          </div>
        </div>
      </td>
    </tr>
  </table>
</body>
</html>
