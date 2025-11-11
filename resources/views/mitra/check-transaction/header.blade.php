<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Cek Mitra' }}</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>
        * {box-sizing:border-box;}
        html, body {height:100%; margin:0;}
        body {
            font-family: Helvetica; 
            background:#ffffff; 
            color:#212529;
            display:flex;
            flex-direction:column;
            min-height:100vh;
        }
        main {max-width:1400px; margin:0 auto; padding:2rem 1.5rem; flex:1;}
        
        .container {width:100%; padding:0 15px; margin:0 auto;}
        .row {display:flex; flex-wrap:wrap; margin:-0.5rem;}
        .col-md-4, .col-md-6, .col-12 {flex:0 0 100%; max-width:100%; padding:0.5rem;}
        
        @media (min-width:768px) {
            .col-md-4 {flex:0 0 33.333%; max-width:33.333%;}
            .col-md-6 {flex:0 0 50%; max-width:50%;}
            .col-md-8 {flex:0 0 66.667%; max-width:66.667%;}
        }
        
        .card {background:#ffffff; border:1px solid #dee2e6; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,.05); margin-bottom:1rem; min-height:300px;}
        .card-header {padding:1.25rem 1.5rem; border-bottom:1px solid #dee2e6;}
        .card-title {font-size:1.1rem; margin:0; font-weight:600; color:#212529;}
        .card-body {padding:1.5rem;}
        
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
        .table-bordered {border:1px solid #dee2e6; table-layout:fixed;}
        .table-bordered td {border:1px solid #dee2e6; padding:.50rem 1.5rem; font-size:.88rem; word-wrap:break-word;}
        .table-bordered td:first-child {width:150px; word-break:break-word; white-space:normal;}
        .table-bordered td:nth-child(2) {width:20px; text-align:center;}
        .table-bordered td:last-child {width:auto;}
        .label-with-colon {display:flex; justify-content:space-between; align-items:center;}
        
        @media (min-width:641px) and (max-width:1024px) {
            .card {min-height:400px;}
        }
        
        @media (min-width:1025px) {
            .card {min-height:450px;}
        }
        
        @media (max-width:640px) {
            main {padding:1.5rem 1rem;}
            .card {min-height:400px;}
            .card-body {padding:1.5rem;}
            .table-bordered {table-layout:fixed;}
            .table-bordered td {padding:.65rem 1rem; font-size:.95rem; line-height:1.5;}
            .table-bordered td:first-child {width:140px; font-size:.9rem; word-break:break-word; white-space:normal;}
            .table-bordered td:nth-child(2) {width:20px;}
            .badge {font-size:1.05rem; margin-top:0.5rem; padding:0.5rem 0;}
        }
        
        @media (max-width:480px) {
            .table-bordered td {padding:.6rem .85rem; font-size:.9rem; line-height:1.5;}
            .table-bordered td:first-child {width:130px; font-size:.85rem; word-break:break-word; white-space:normal;}
            .table-bordered td:nth-child(2) {width:18px;}
            .badge {padding:0.45rem 0; font-size:1rem;}
        }
        
        .modal {display:none; position:fixed; z-index:1050; left:0; top:0; width:100%; height:100%; overflow:auto; background:rgba(0,0,0,.5);}
        .modal.fade {opacity:0; transition:opacity .15s linear;}
        .modal.show {display:block !important; opacity:1 !important;}
        .modal-dialog {position:relative; width:auto; margin:1.75rem auto; max-width:500px;}
        .modal-dialog-centered {display:flex; align-items:center; min-height:calc(100% - 3.5rem);}
        .modal-content {position:relative; display:flex; flex-direction:column; width:100%; background:#fff; border:1px solid rgba(0,0,0,.2); border-radius:0.3rem; outline:0; margin:0 1rem;}
        .modal-header {display:flex; align-items:center; justify-content:space-between; padding:1rem; border-bottom:1px solid #dee2e6;}
        .modal-title {margin:0; font-size:1.25rem; font-weight:500;}
        .modal-body {position:relative; flex:1 1 auto; padding:1rem;}
        .modal-footer {display:flex; align-items:center; justify-content:flex-end; padding:1rem; border-top:1px solid #dee2e6;}
        .btn-close {background:transparent; border:0; font-size:1.5rem; font-weight:700; line-height:1; color:#000; opacity:.5; cursor:pointer;}
        .btn-close:hover {opacity:.75;}
        .btn-secondary {color:#fff; background:#6c757d; border:1px solid #6c757d;}
        .btn-secondary:hover {background:#5a6268;}
        .btn-cancel {color:#0d6efd; background:#fff; border:none; padding:.7rem 1.5rem; font-size:.9rem; font-weight:500; cursor:pointer; font-family:inherit; transition:.2s;}
        .btn-cancel:hover {text-decoration:underline;}
        .btn-primary {color:#fff; background:#0d6efd; border:1px solid #0d6efd;}
        .btn-primary:hover {background:#0b5ed7;}
        .form-label {display:inline-block; margin-bottom:0.5rem;}
        .form-control {display:block; width:100%; padding:0.375rem 0.75rem; font-size:1rem; line-height:1.5; color:#212529; background:#fff; border:1px solid #ced4da; border-radius:0.25rem; resize:none;}
        #rating-stars {gap:0.5rem; font-size:2rem;}
        #rating-stars .star {cursor:pointer; color:#e4e5e9; transition:color 0.2s;}
        
        /* Header */
        .header-bps {
            display:flex; 
            align-items:center; 
            justify-content:center;
            gap:1rem; 
            padding:0.75rem 1.5rem;
            background:#ffffff;
            border-bottom:1px solid #dee2e6;
            width:100%;
        }
        .header-logo {width:45px; height:auto;}
        .header-text-wrapper {display:none; flex-direction:column; align-items:flex-start;}
        .header-text {font-size:1.08rem; font-weight:500; color:#000000; line-height:1.1;}
        .header-subtext {font-size:0.855rem; font-weight:400; color:#495057; margin-top:0;}
        
        @media (min-width:641px) {
            .header-text-wrapper {display:flex;}
            .header-logo {width:54px;}
        }
        
        /* Footer */
        .footer-bps {
            display:flex;
            align-items:center;
            justify-content:center;
            padding:0.5rem 1rem;
            background:#ffffff;
            border-top:1px solid #dee2e6;
            width:100%;
            font-size:0.75rem;
            color:#6c757d;
        }
    </style>
</head>
<!-- Header -->
<div class="header-bps">
    <img src="{{ asset('images/bps-logo.png') }}" alt="Logo BPS" class="header-logo">
    <div class="header-text-wrapper">
        <span class="header-text">Badan Pusat Statistik</span>
        <span class="header-subtext">Kota Malang</span>
    </div>
</div>
<body>
