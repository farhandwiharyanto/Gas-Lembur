<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Formulir Cuti - {{ $leave->employee_name }}</title>
    <style>
        @page {
            margin: 0.8cm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #000;
            line-height: 1.4;
        }
        .main-box {
            border: 1.5px solid #000;
            padding: 15px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .logo-img {
            width: 160px;
            height: auto;
        }
        .form-title {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 10px;
            margin-bottom: 12px;
            letter-spacing: 0.5px;
        }
        
        /* Two Column Layout Table */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .info-table td {
            vertical-align: top;
            padding: 2px 0;
        }
        .left-col {
            width: 60%;
        }
        .right-col {
            width: 40%;
        }
        
        .field-table {
            width: 100%;
            border-collapse: collapse;
        }
        .field-table td {
            padding: 3px 0;
        }
        .label-cell {
            width: 110px;
            font-weight: normal;
        }
        .colon-cell {
            width: 15px;
            text-align: center;
        }
        .value-cell {
            font-weight: bold;
        }
        
        /* Middle Grid Contact & Signature */
        .middle-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
            margin-bottom: 12px;
        }
        .middle-table td {
            padding: 8px 12px;
            vertical-align: top;
        }
        .contact-cell {
            width: 55%;
            font-size: 10px;
            line-height: 1.5;
        }
        .contact-title {
            font-weight: bold;
            margin-bottom: 4px;
        }
        .contact-note {
            font-style: italic;
            color: #444;
            font-size: 9px;
        }
        .signature-cell {
            width: 45%;
            border-left: 1px solid #000;
            text-align: center;
            position: relative;
            height: 95px;
        }
        .signature-title {
            text-align: left;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .sign-space {
            height: 55px;
            display: block;
            margin: 0 auto;
            text-align: center;
        }
        .sign-img {
            max-height: 55px;
            max-width: 150px;
            display: inline-block;
        }
        .sign-name {
            font-weight: bold;
            margin-top: 4px;
            display: block;
        }
        
        /* Bottom Approvals */
        .bottom-section {
            width: 100%;
            border-collapse: collapse;
        }
        .bottom-section td {
            vertical-align: top;
        }
        .approval-cell {
            width: 60%;
        }
        .pimpinan-sign-cell {
            width: 40%;
            text-align: center;
        }
        
        .approval-title {
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        
        .pimpinan-box {
            text-align: left;
            display: inline-block;
            width: 180px;
        }
        .pimpinan-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .pimpinan-sign-space {
            height: 55px;
            text-align: center;
            margin-top: 2px;
        }
        .pimpinan-name {
            font-weight: bold;
            margin-top: 5px;
            border-bottom: 1px solid #000;
            display: inline-block;
            width: 100%;
            text-align: center;
            padding-bottom: 1px;
        }
        
        .distribusi-box {
            margin-top: 15px;
            font-size: 9px;
            line-height: 1.4;
        }
        .distribusi-title {
            font-weight: bold;
        }
        .distribusi-item {
            padding-left: 10px;
        }
        
        .right-date-info {
            text-align: right;
            font-size: 9.5px;
            margin-top: 15px;
            padding-right: 15px;
        }
    </style>
</head>
<body>
    @php
        // Resolve LMD Logo
        $logoPath = public_path('images/logo-lmd.png');
        if (!file_exists($logoPath)) {
            $logoPath = public_path('images/logo-gas-lembur.png');
        }
        $logoData = base64_encode(file_get_contents($logoPath));
        $logoSrc = 'data:image/png;base64,' . $logoData;

        // Resolve Employee Signature Base64
        $userSrc = null;
        if ($userPath && file_exists($userPath)) {
            $userData = base64_encode(file_get_contents($userPath));
            $userSrc = 'data:image/png;base64,' . $userData;
        }

        // Resolve Pimpinan Signature Base64
        $pimpinanSrc = null;
        if ($leave->status === 'approved' && $pimpinanPath && file_exists($pimpinanPath)) {
            $pimpinanData = base64_encode(file_get_contents($pimpinanPath));
            $pimpinanSrc = 'data:image/png;base64,' . $pimpinanData;
        }
    @endphp

    <div class="main-box">
        <!-- Logo -->
        <table class="header-table">
            <tr>
                <td style="text-align: left;">
                    <img src="{{ $logoSrc }}" class="logo-img" alt="Logo LMD">
                </td>
            </tr>
        </table>

        <!-- Form Title -->
        <div class="form-title">FORMULIR CUTI</div>

        <!-- Two Column Meta Info -->
        <table class="info-table">
            <tr>
                <!-- Left Metadata Column -->
                <td class="left-col">
                    <table class="field-table">
                        <tr>
                            <td class="label-cell">Nama</td>
                            <td class="colon-cell">:</td>
                            <td class="value-cell">{{ $leave->employee_name }}</td>
                        </tr>
                        <tr>
                            <td class="label-cell">Jabatan</td>
                            <td class="colon-cell">:</td>
                            <td class="value-cell">{{ $jabatan }}</td>
                        </tr>
                        <tr>
                            <td class="label-cell">Department</td>
                            <td class="colon-cell">:</td>
                            <td class="value-cell">{{ $leave->bagian }}</td>
                        </tr>
                        <tr>
                            <td class="label-cell">Tanggal Masuk</td>
                            <td class="colon-cell">:</td>
                            <td class="value-cell">{{ $tanggalMasuk }}</td>
                        </tr>
                        <tr>
                            <td class="label-cell">Keterangan Cuti</td>
                            <td class="colon-cell">:</td>
                            <td class="value-cell">{{ $keteranganCuti }}</td>
                        </tr>
                    </table>
                </td>
                
                <!-- Right Metadata Column -->
                <td class="right-col" style="padding-left: 20px;">
                    <table class="field-table">
                        <tr>
                            <td class="label-cell" style="width: 100px;">Sisa cuti 2026</td>
                            <td class="colon-cell">:</td>
                            <td class="value-cell" style="font-size: 12px;">{{ $sisaCutiVal }}</td>
                        </tr>
                        <tr>
                            <td class="label-cell" style="width: 100px;">Mulai Cuti (tgl)</td>
                            <td class="colon-cell">:</td>
                            <td class="value-cell">{{ $mulaiCutiStr }}</td>
                        </tr>
                        <tr>
                            <td class="label-cell" style="width: 100px;">Total hari</td>
                            <td class="colon-cell">:</td>
                            <td class="value-cell">{{ $leave->total_hari }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Middle Contact & Employee Signature Section -->
        <table class="middle-table">
            <tr>
                <!-- Contact info -->
                <td class="contact-cell">
                    <div class="contact-title">Informasi contact yang dapat dihubungi selama cuti :</div>
                    <div class="contact-note">
                        *jika untuk penggantian tugas cantumkan contact dan email rekan kerja yang sementara backup
                    </div>
                </td>
                
                <!-- Employee signature -->
                <td class="signature-cell">
                    <div class="signature-title">Tanda Tangan Karyawan :</div>
                    <div class="sign-space">
                        @if($userSrc)
                            <img src="{{ $userSrc }}" class="sign-img" alt="Sign User">
                        @endif
                    </div>
                    <span class="sign-name">( {{ $leave->employee_name }} )</span>
                </td>
            </tr>
        </table>

        <!-- Bottom Approvals & Signatures -->
        <div style="width: 100%; margin-top: 10px;">
            <!-- Title: Aprroval Pimpinan -->
            <div style="font-weight: bold; font-size: 11px; margin-bottom: 6px; text-transform: uppercase;">Aprroval Pimpinan</div>
            
            <!-- Table for Cuti Stats + date stamp on the right -->
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 12px; font-size: 11px;">
                <tr>
                    <td style="width: 130px; padding: 2px 0;">Cuti disetujui penuh</td>
                    <td style="width: 15px; text-align: center;">:</td>
                    <td style="font-weight: bold;">{{ $leave->total_hari }} Hari</td>
                    <td></td>
                </tr>
                <tr>
                    <td style="width: 130px; padding: 2px 0;">Cuti yang akan diberikan</td>
                    <td style="width: 15px; text-align: center;">:</td>
                    <td style="font-weight: bold;">{{ $leave->total_hari }} Hari</td>
                    <td style="text-align: right; font-size: 9.5px; padding-right: 15px;">hari kerja, mulai &nbsp;&nbsp;&nbsp;&nbsp; {{ $returnDateStr }}</td>
                </tr>
            </table>

            <!-- Pimpinan Unit Kerja Signature Box -->
            <div style="margin-top: 10px; margin-bottom: 15px; font-size: 11px;">
                <div style="font-weight: bold; line-height: 1.2;">Menyetujui,</div>
                <div style="font-weight: bold; line-height: 1.2; margin-bottom: 5px;">Pimpinan Unit Kerja</div>
                
                <div style="height: 55px; position: relative; margin-top: 5px;">
                    @if($pimpinanSrc)
                        <img src="{{ $pimpinanSrc }}" style="max-height: 55px; max-width: 150px; display: block;" alt="Sign Pimpinan">
                    @else
                        <div style="height: 55px;"></div>
                    @endif
                </div>
                <div style="margin-top: 5px;">
                    ( <span style="display: inline-block; width: 140px; text-align: center; font-weight: bold; border-bottom: 1px solid #000; padding-bottom: 1px;">{{ $pimpinan ? $pimpinan->name : '                   ' }}</span> )
                </div>
            </div>

            <!-- Distribusi Section -->
            <table style="border-collapse: collapse; font-size: 9px; line-height: 1.4; margin-top: 15px;">
                <tr>
                    <td style="width: 70px; font-weight: bold; vertical-align: top;">Distribusi</td>
                    <td style="width: 15px; text-align: center; vertical-align: top;">:</td>
                    <td style="vertical-align: top;">
                        1. Pegawai Ybs<br>
                        2. Pimp. Bag SDM<br>
                        3. Bag. Keuangan
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
