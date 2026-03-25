<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Tugas Lembur - {{ $overtime->employee_name }}</title>
    <style>
        body { margin: 0; padding: 0; font-family: 'Helvetica Neue', Arial, sans-serif; font-size: 14px; color: #000; }
        .container { width: 100%; max-width: 800px; margin: 0 auto; padding: 40px; box-sizing: border-box; }
        
        .header { display: flex; align-items: flex-start; margin-bottom: 40px; }
        .logo-img { width: 50px; height: 50px; background-color: #2563eb; clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%); display: flex; align-items: center; justify-content: center; margin-right: 15px; position:relative; }
        .logo-inner { width: 25px; height: 25px; background-color: white; border-radius: 50%; border: 4px solid #f97316; }
        .logo-text { display: flex; flex-direction: column; justify-content: center; }
        .logo-title { font-size: 24px; font-weight: bold; color: #1e3a8a; letter-spacing: -0.5px; }
        .logo-subtitle { font-size: 13px; color: #4b5563; }
        
        .title { text-align: center; text-decoration: underline; font-weight: bold; font-size: 18px; margin-bottom: 40px; text-transform: uppercase; }
        .section { margin-bottom: 24px; line-height: 1.5; }
        .row { display: flex; margin-bottom: 8px; }
        .label { width: 180px; }
        .colon { width: 20px; }
        .value { flex: 1; }
        
        .border-bottom-line { border-bottom: 1px solid #000; padding-bottom: 5px; margin-top: 5px; min-height: 20px; }
        
        .footer-note { font-weight: bold; margin-top: 40px; font-size: 12px; line-height: 1.6; }
        .footer-note span { font-weight: normal; font-style: italic; }
        
        .statement { font-style: italic; font-weight: bold; margin-top: 15px; text-align: justify; }
        
        .signatures { display: flex; justify-content: space-between; margin-top: 50px; text-align: center; }
        .sign-box { width: 280px; }
        .sign-title { min-height: 40px; font-weight: normal; }
        .sign-space { height: 90px; }
        
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .container { padding: 0; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="container">
        <!-- Logo Header -->
        <div class="header">
            <div class="logo-img">
                <div class="logo-inner"></div>
            </div>
            <div class="logo-text">
                <div class="logo-title">lintas media danawa</div>
                <div class="logo-subtitle">Your AI Smart Solutions</div>
            </div>
        </div>

        <div class="title">SURAT TUGAS LEMBUR</div>

        <div class="section">
            <p>Di instruksikan kepada :</p>
            <div class="row"><div class="label">Nama</div><div class="colon">:</div><div class="value">{{ $overtime->employee_name }}</div></div>
            <div class="row"><div class="label">NIK</div><div class="colon">:</div><div class="value">{{ $overtime->employee_no }}</div></div>
            <div class="row"><div class="label">Bagian/Divisi</div><div class="colon">:</div><div class="value">{{ $overtime->bagian }} / {{ $overtime->divisi }}</div></div>
            <div class="row"><div class="label">Lokasi Kerja</div><div class="colon">:</div><div class="value">{{ $overtime->lokasi_kerja }}</div></div>
        </div>

        <div class="section" style="margin-top:20px;">
            <p>Untuk melaksanakan lembur pada :</p>
            <div class="row"><div class="label">Hari/ Tanggal</div><div class="colon">:</div><div class="value">{{ \Carbon\Carbon::parse($overtime->jam_masuk ?: $overtime->created_at)->translatedFormat('l, d F Y') }}</div></div>
            <div class="row"><div class="label">Durasi Jam Lembur</div><div class="colon">:</div><div class="value">{{ $overtime->total_jam }} Jam</div></div>
        </div>

        <div class="section" style="margin-top:20px;">
            <p>Pelaksanaan Lembur tersebut di perlukan untuk menyelesaikan tugas sebagai berikut :</p>
            <div class="border-bottom-line">{{ $overtime->nama_lemburan }}</div>
        </div>

        <div class="footer-note">
            <div>Media approval: Email / WhatsApp (Screenshot percakapan/email approval terlampir) <span>*coret yang tidak perlu</span></div>
            <div>Lampiran : realisasi KJK/ Pengajuan Overtime Talenta/Lampiran Bukti Approval <span>*coret yang tidak perlu</span></div>
        </div>

        <div class="section">
            <p><strong>Pernyataan:</strong></p>
            <p class="statement">Dengan ini saya yang bertanda tangan sebagai yang di beri tugas, menyatakan bahwa dokumen dan bukti yang dilampirkan adalah benar dan sesuai dengan pelaksanaan lembur yang dilakukan.</p>
        </div>

        <div class="signatures">
            <div class="sign-box">
                <div class="sign-title">Mengetahui atasan langsung</div>
                <div class="sign-space">
                    @if($pimpinan && $pimpinan->tanda_tangan)
                        @php
                            $pathP = str_starts_with($pimpinan->tanda_tangan, 'data:image') 
                                ? $pimpinan->tanda_tangan 
                                : public_path('storage/' . $pimpinan->tanda_tangan);
                        @endphp
                        @if(file_exists(public_path('storage/' . $pimpinan->tanda_tangan)) || str_starts_with($pimpinan->tanda_tangan, 'data:image'))
                            <img src="{{ $pathP }}" style="max-height: 80px; width: auto; margin-top: 5px;">
                        @endif
                    @endif
                </div>
                <div>(( {{ $pimpinan ? $pimpinan->name : str_repeat('&nbsp;', 25) }} ))</div>
                <div>NIK : {{ $pimpinan ? $pimpinan->nik : '___________________' }}</div>
            </div>
            <div class="sign-box">
                <div class="sign-title">Jakarta, {{ \Carbon\Carbon::parse($overtime->jam_masuk ?: $overtime->created_at)->translatedFormat('d F Y') }}<br>Yang menyatakan dan di beri tugas</div>
                <div class="sign-space">
                    @if($overtime->tanda_tangan)
                        @php
                            $pathO = str_starts_with($overtime->tanda_tangan, 'data:image') 
                                ? $overtime->tanda_tangan 
                                : public_path('storage/' . $overtime->tanda_tangan);
                        @endphp
                        @if(file_exists(public_path('storage/' . $overtime->tanda_tangan)) || str_starts_with($overtime->tanda_tangan, 'data:image'))
                            <img src="{{ $pathO }}" style="max-height: 80px; width: auto; margin-top: 5px;">
                        @endif
                    @endif
                </div>
                <div>(( {{ $overtime->employee_name }} ))</div>
                <div>NIK : {{ $overtime->employee_no }}</div>
            </div>
        </div>
    </div>
</body>
</html>
