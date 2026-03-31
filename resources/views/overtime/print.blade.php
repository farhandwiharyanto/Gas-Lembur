<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Tugas Lembur - {{ $overtime->employee_name }}</title>
    <style>
        /* PDF Page Setup */
        @page { 
            margin: 0; 
        }
        
        body { 
            padding: 1.5cm 1.5cm 1.5cm 1.5cm; /* Top 1.5cm to allow logo move up, Sides 1.5cm */
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 13px; 
            color: #000; 
            line-height: 1.5; 
        }
        
        .header { 
            width: 100%; 
            margin-left: -1.2cm; /* Logo mentok kiri */
            margin-top: -0.8cm; /* Logo mentok atas */
            margin-bottom: 15px; /* Reduced gap */
        }
        
        .logo-img { 
            width: 250px; 
            height: auto; 
        }
        
        .title { 
            text-align: center; 
            font-weight: bold; 
            font-size: 18px; 
            text-decoration: underline; 
            margin-top: 10px; /* Moved up */
            margin-bottom: 25px; /* Slightly reduced */
            text-transform: uppercase;
        }
        
        .section-intro {
            margin-bottom: 5px; /* Reduced gap */
        }

        /* Identity Table for Perfect Colon Alignment */
        .identity-table {
            width: 100%;
            margin-bottom: 15px; /* Reduced gap */
            border-collapse: collapse;
        }
        .identity-table td {
            padding: 1px 0; /* Reduced padding */
            vertical-align: top;
        }
        .label-col {
            width: 160px;
        }
        .colon-col {
            width: 20px;
            text-align: center;
        }
        .value-col {
            font-weight: bold;
        }

        .task-section {
            margin-top: 15px; /* Moved up */
        }
        .task-content {
            padding-bottom: 3px;
            min-height: 50px; 
            font-weight: bold;
            margin-bottom: 25px; /* Slightly reduced */
        }
        /* Specific underline for the task text only */
        .underline-text {
            border-bottom: 1.5px solid #000;
            padding-bottom: 1px;
            display: inline-block;
        }

        .footer-note {
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 15px;
        }
        .footer-note span {
            font-weight: normal;
            font-style: italic;
        }

        .statement-box {
            margin-top: 15px; /* Moved up */
            margin-bottom: 30px; /* Jarak sebelum tanda tangan */
        }
        .statement-title {
            font-weight: bold;
            margin-bottom: 3px;
        }
        .statement-body {
            font-style: italic;
            font-size: 12px;
            text-align: justify;
        }

        /* Footer / Signatures Table (Symmetric) */
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .sign-cell {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }
        .sign-title {
            font-weight: normal;
            margin-bottom: 5px;
            min-height: 40px; /* Space for Jakarta row and Title */
        }
        .sign-space {
            height: 90px; 
            position: relative;
        }
        .sign-img {
            max-height: 85px;
            max-width: 180px;
            display: block;
            margin: 0 auto;
        }
        .name-row {
            font-weight: bold;
            margin-top: 5px;
        }
        .nik-row {
            font-size: 12px;
            margin-top: 2px;
        }
        .underline {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="header">
        @php
            \Carbon\Carbon::setLocale('id');
            $logoPath = public_path('images/logo-lmd.png');
            if(!file_exists($logoPath)) {
                $logoPath = public_path('images/logo-gas-lembur.png');
            }
            $logoData = base64_encode(file_get_contents($logoPath));
            $logoSrc = 'data:image/png;base64,' . $logoData;
        @endphp
        <img src="{{ $logoSrc }}" class="logo-img" alt="Logo LMD">
    </div>

    <div class="title">SURAT TUGAS LEMBUR</div>

    <div class="section-intro">Di instruksikan kepada :</div>
    
    <table class="identity-table">
        <tr>
            <td class="label-col">Nama</td>
            <td class="colon-col">:</td>
            <td class="value-col">{{ $overtime->employee_name }}</td>
        </tr>
        <tr>
            <td class="label-col">NIK</td>
            <td class="colon-col">:</td>
            <td class="value-col">{{ $overtime->employee_no }}</td>
        </tr>
        <tr>
            <td class="label-col">Bagian/Divisi</td>
            <td class="colon-col">:</td>
            <td class="value-col">{{ $overtime->bagian }} / {{ $overtime->divisi }}</td>
        </tr>
        <tr>
            <td class="label-col">Lokasi Kerja</td>
            <td class="colon-col">:</td>
            <td class="value-col">{{ $overtime->lokasi_kerja }}</td>
        </tr>
    </table>

    <div class="section-intro">Untuk melaksanakan lembur pada :</div>
    
    <table class="identity-table">
        <tr>
            <td class="label-col">Hari/ Tanggal</td>
            <td class="colon-col">:</td>
            <td class="value-col">
                {{ \Carbon\Carbon::parse($overtime->tanggal_masuk)->translatedFormat('l, d F Y') }}
                @if($overtime->tanggal_masuk !== $overtime->tanggal_keluar)
                    s/d {{ \Carbon\Carbon::parse($overtime->tanggal_keluar)->translatedFormat('l, d F Y') }}
                @endif
            </td>
        </tr>
        <tr>
            <td class="label-col">Durasi Jam Lembur</td>
            <td class="colon-col">:</td>
            <td class="value-col">{{ (int)$overtime->total_jam }} Jam</td>
        </tr>
    </table>

    <div class="task-section">
        <div class="section-intro">Pelaksanaan Lembur tersebut di perlukan untuk menyelesaikan tugas sebagai berikut :</div>
        <div class="task-content">
            <span class="underline-text">{{ $overtime->nama_lemburan }}</span>
        </div>
    </div>

    <div class="footer-note">
        Media persetujuan: Email / WhatsApp (Screenshot percakapan/email persetujuan terlampir) <span>*coret yang tidak perlu</span><br>
        Lampiran : Realisasi KJK/ Pengajuan Lembur Talenta/Lampiran Bukti Persetujuan <span>*coret yang tidak perlu</span>
    </div>

    <div class="statement-box">
        <div class="statement-title">Pernyataan:</div>
        <div class="statement-body">
            Dengan ini saya yang bertanda tangan sebagai yang di beri tugas, menyatakan bahwa dokumen dan bukti yang dilampirkan adalah benar dan sesuai dengan pelaksanaan lembur yang dilakukan.
        </div>
    </div>

    @php
        $resolvePath = function($path) {
            if (!$path) return null;
            // Jika path dimulai dengan /uploads, ambil dari public directory
            if (str_starts_with($path, '/uploads')) {
                $fullPath = public_path($path);
            } else {
                // Default ke storage directory untuk signatures baru
                $fullPath = public_path('storage/' . $path);
            }
            return file_exists($fullPath) ? $fullPath : null;
        };

        $pimpinanPath = $resolvePath($pimpinan->tanda_tangan ?? null);
        $userPath = $resolvePath($overtime->tanda_tangan ?? null);
    @endphp

    <table class="signature-table">
        <tr>
            <td class="sign-cell">
                <div class="sign-title"><br>Mengetahui atasan langsung</div>
                <div class="sign-space">
                    @if($overtime->status === 'approved' && $pimpinanPath)
                        <img src="{{ $pimpinanPath }}" class="sign-img">
                    @endif
                </div>
                <div class="name-row">(( <span class="underline">{{ $pimpinan ? $pimpinan->name : str_repeat('&nbsp;', 25) }}</span> ))</div>
                <div class="nik-row">NIK : {{ $pimpinan ? $pimpinan->nik : '___________________' }}</div>
            </td>
            <td class="sign-cell">
                <div class="sign-title">Jakarta, {{ \Carbon\Carbon::parse($overtime->created_at)->translatedFormat('d F Y') }}<br>Yang menyatakan dan di beri tugas</div>
                <div class="sign-space">
                    @if($userPath)
                        <img src="{{ $userPath }}" class="sign-img">
                    @endif
                </div>
                <div class="name-row">(( <span class="underline">{{ $overtime->employee_name }}</span> ))</div>
                <div class="nik-row">NIK : {{ $overtime->employee_no }}</div>
            </td>
        </tr>
    </table>
</body>
</html>
