@php($title = 'Cek Mitra')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        * {box-sizing:border-box;}
        body {font-family: Inter, system-ui, sans-serif; margin:0; background:#f8f9fa; color:#212529;}
        main {max-width:1400px; margin:0 auto; padding:2rem 1.5rem;}
        
        .container {width:100%; padding:0 15px; margin:0 auto;}
        .row {display:flex; flex-wrap:wrap; margin:-0.5rem;}
        .col-md-4, .col-12 {flex:0 0 100%; max-width:100%; padding:0.5rem;}
        
        @media (min-width:768px) {
            .col-md-4 {flex:0 0 33.333%; max-width:33.333%;}
            .col-md-8 {flex:0 0 66.667%; max-width:66.667%;}
        }
        
        .card {background:#fff; border:1px solid #dee2e6; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,.05); margin-bottom:1rem;}
        .card-header {padding:1.25rem 1.5rem; border-bottom:1px solid #dee2e6;}
        .card-title {font-size:1.1rem; margin:0; font-weight:600; color:#212529;}
        .card-body {padding:1.5rem;}
        
        .qr-wrapper {text-align:center; padding:1.5rem; background:#f8f9fa; border-radius:8px; margin-bottom:1rem;}
        .qr-wrapper img {width:100%; max-width:200px; height:auto; display:block; margin:0 auto;}
        .qr-code {display:inline-block; padding:1rem; background:#fff; border-radius:8px;}
        .qr-code img {display:block !important; margin:0 auto !important;}
        
        .d-flex {display:flex;}
        .justify-content-center {justify-content:center;}
        .flex-column {flex-direction:column;}
        .d-grid {display:grid;}
        .gap-2 {gap:0.5rem;}
        
        .border {border:1px solid #dee2e6;}
        .shadow-sm {box-shadow:0 1px 3px rgba(0,0,0,.05);}
        .my-2 {margin-top:0.5rem; margin-bottom:0.5rem;}
        .mx-3 {margin-left:1rem; margin-right:1rem;}
        .mt-2 {margin-top:0.5rem;}
        .mb-2 {margin-bottom:0.5rem;}
        .mb-4 {margin-bottom:1.5rem;}
        .p-0 {padding:0;}
        
        /* Photo spacing */
        img.my-2 {margin-top:2rem !important; margin-bottom:2rem !important;}

        .btn {padding:.7rem 1.5rem; border:none; border-radius:6px; font-size:.9rem; font-weight:500; cursor:pointer; font-family:inherit; transition:.2s;}
        .btn-block {width:100%;}
        .btn-outline-green {background:#fff; color:#10b981; border:2px solid #10b981;}
        .btn-outline-green:hover {background:#10b981; color:#fff;}
        .btn-pill {border-radius:50px;}
        
        .badge {display:inline-block; padding:0.5rem 1.5rem; font-size:1.1rem; border-radius:0; font-weight:600; letter-spacing:0; text-transform:none; margin-top:1rem; margin-bottom:0.5rem; color:#212529 !important; background:transparent !important; border:none !important;}
        .badge-outline {background:transparent !important; border:none !important;}
        .text-blue {color:#212529 !important;}
        
        .table {width:100%; border-collapse:collapse; margin-bottom:1rem;}
        .table-bordered {border:none; table-layout:fixed;}
        .table-bordered td {border:none; border-bottom:1px solid #e9ecef; padding:.50rem 1.5rem; font-size:.88rem; word-wrap:break-word;}
        .table-bordered td:first-child {width:150px; word-break:break-word; white-space:normal;}
        .table-bordered td:nth-child(2) {width:20px; text-align:center;}
        .table-bordered td:last-child {width:auto;}
        .table-bordered tr:last-child td {border-bottom:none;}
        
        /* Responsive adjustments for small screens */
        @media (max-width:640px) {
            main {padding:1rem 0.5rem;}
            .card-body {padding:1rem;}
            .table-bordered {table-layout:fixed;}
            .table-bordered td {padding:.5rem .75rem; font-size:.82rem;}
            .table-bordered td:first-child {width:120px; font-size:.78rem; word-break:break-word; white-space:normal; display:block;}
            .table-bordered td:nth-child(2) {width:15px;}
            .badge {font-size:1rem; margin-top:0.5rem; padding:0.5rem 0.75rem;}
        }
        
        @media (max-width:480px) {
            .table-bordered td {padding:.4rem .5rem; font-size:.78rem;}
            .table-bordered td:first-child {width:100px; font-size:.75rem; word-break:break-word; white-space:normal; display:block;}
            .table-bordered td:nth-child(2) {width:12px;}
            .badge {padding:0.4rem 0.5rem;}
        }
        
        .modal {display:none; position:fixed; z-index:1050; left:0; top:0; width:100%; height:100%; overflow:auto; background:rgba(0,0,0,.5);}
        .modal.fade {opacity:0; transition:opacity .15s linear;}
    </style>
</head>
<body>
<main>
    <div class="container mt-4">
        <div class="row mb-2">
            <!-- Sidebar QR Code -->
            <div id="listsurvei" class="col-md-4 col-12 mb-2">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-center mb-2">
                            @if(!empty($qrUrl))
                                {{-- Gunakan QR code yang sudah di-generate dan disimpan --}}
                                <div class="qr-code">
                                    <img src="{{ $qrUrl }}" alt="QR Code Mitra" style="display:block; width:200px; height:200px;">
                                </div>
                            @else
                                {{-- Fallback jika QR belum ada --}}
                                <div class="qr-code" id="qr-fallback"></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Profile Card -->
            <div id="card-survei" class="col-md-8 col-12 mb-4">
                <div id="plc-result" class="card">
                    <div class="card-body">
                        <div>
                            <div>
                                <div class="flex-column">
                                    <!-- Avatar -->
                                    <div class="d-flex justify-content-center">
                                        @if($mitra->photo)
                                            <img src="{{ asset('storage/' . $mitra->photo) }}" 
                                                 alt="Foto {{ $mitra->name }}" 
                                                 class="border shadow-sm my-2" 
                                                 style="max-width: 130px;">
                                        @else
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($mitra->name) }}&background=4f46e5&color=fff&size=260" 
                                                 alt="Avatar {{ $mitra->name }}" 
                                                 class="border shadow-sm my-2" 
                                                 style="max-width: 130px;">
                                        @endif
                                    </div>
                                    
                                    <!-- Info Section -->
                                    <div class="d-flex justify-content-center mt-2">
                                        <div style="max-width: 500px; width: 100%;">
                                            <!-- Mitra Info -->
                                            <span class="badge badge-outline text-blue mb-2">Mitra</span>
                                            <table class="table table-bordered table-striped mb-4">
                                                <tr>
                                                    <td width="150">Nama</td>
                                                    <td width="10">:</td>
                                                    <td>{{ $mitra->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Sobat ID</td>
                                                    <td>:</td>
                                                    <td>{{ $maskedSobatId ?? '—' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Email</td>
                                                    <td>:</td>
                                                    <td>{{ $maskedEmail ?? '—' }}</td>
                                                </tr>
                                            </table>
                                            
                                            <!-- Survey Info -->
                                            <span class="badge badge-outline text-blue mb-2">Survei/Sensus</span>
                                            @if($latestSurvey)
                                            <table class="table table-bordered table-striped mb-4">
                                                <tr>
                                                    <td width="150">Nama Survei/Sensus</td>
                                                    <td width="10">:</td>
                                                    <td>
                                                        {{ $latestSurvey->masterSurvey->name ?? 'N/A' }} 
                                                        @if($latestSurvey->masterSurvey->code)
                                                        [{{ $latestSurvey->masterSurvey->code }}]
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Periode</td>
                                                    <td>:</td>
                                                    <td>{{ $latestSurvey->year ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Tim</td>
                                                    <td>:</td>
                                                    <td>{{ $latestSurvey->team->name ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Status</td>
                                                    <td>:</td>
                                                    <td>{{ ucfirst($latestSurvey->status ?? 'N/A') }}</td>
                                                </tr>
                                            </table>
                                            @else
                                            <p class="text-muted" style="font-size: 0.88rem; padding: 0.5rem 1.5rem;">Belum ada survei/sensus</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Modal -->
                    <div id="modal-input-nilai" 
                         data-bs-backdrop="dynamic" 
                         data-bs-keyboard="true" 
                         tabindex="-1" 
                         class="modal fade" 
                         style="display: none;" 
                         aria-hidden="true">
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    // Fallback QR generation jika QR belum tersimpan di backend
    document.addEventListener('DOMContentLoaded', function() {
        const qrFallback = document.getElementById('qr-fallback');
        
        if (qrFallback) {
            const currentUrl = encodeURIComponent(window.location.href);
            qrFallback.innerHTML = `<img src="https://api.qrserver.com/v1/create-qr-code/?size=400x400&data=${currentUrl}" alt="QR Code" style="display:block; width:200px; height:200px;">`;
        }
    });
</script>
</body>
</html>