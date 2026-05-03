<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Lembur</title>
    <style>
        @page { margin: 1cm; }
        body { 
            font-family: 'Helvetica', sans-serif; 
            font-size: 10px; 
            color: #333;
            line-height: 1.4;
        }
        .header {
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 2px solid #444;
            padding-bottom: 10px;
        }
        .logo-img { width: 180px; }
        .report-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
            text-transform: uppercase;
            text-decoration: underline;
        }
        .period {
            text-align: center;
            margin-bottom: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #f8fafc;
            color: #1e293b;
            font-weight: bold;
            text-transform: uppercase;
            border: 1px solid #cbd5e1;
            padding: 8px 4px;
            text-align: center;
        }
        td {
            border: 1px solid #cbd5e1;
            padding: 6px 4px;
            vertical-align: middle;
        }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        
        .footer {
            margin-top: 30px;
            width: 100%;
        }
        .signature-box {
            float: right;
            width: 200px;
            text-align: center;
        }
        .status-approved { color: #16a34a; font-weight: bold; }
        .status-waiting { color: #ca8a04; font-weight: bold; }
        .status-rejected { color: #dc2626; font-weight: bold; }
        
        .summary-row {
            background-color: #f1f5f9;
            font-weight: bold;
        }
    </style>
</head>
<body>
    @php
        $logoPath = public_path('images/logo-lmd.png');
        if(!file_exists($logoPath)) {
            $logoPath = public_path('images/logo-gas-lembur.png');
        }
        $logoData = base64_encode(file_get_contents($logoPath));
        $logoSrc = 'data:image/png;base64,' . $logoData;
    @endphp

    <div class="header">
        <img src="{{ $logoSrc }}" class="logo-img">
    </div>

    <div class="report-title">Rekapitulasi Pengajuan Lembur Karyawan</div>
    <div class="period">
        Periode: {{ $monthName ?? 'Semua Bulan' }} {{ $year ?? 'Semua Tahun' }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 80px;">Tanggal</th>
                <th>Nama Karyawan</th>
                <th>Bagian</th>
                <th>Lokasi</th>
                <th>Uraian Tugas</th>
                <th style="width: 50px;">Durasi</th>
                <th style="width: 70px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @php $totalJam = 0; @endphp
            @forelse($overtimes as $idx => $ot)
                @php $totalJam += $ot->total_jam; @endphp
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($ot->tanggal_masuk)->format('d/m/Y') }}</td>
                    <td>
                        <span class="font-bold">{{ $ot->employee_name }}</span><br>
                        <span style="font-size: 8px; color: #64748b;">NIK: {{ $ot->employee_no }}</span>
                    </td>
                    <td>{{ $ot->bagian }}</td>
                    <td>{{ $ot->lokasi_kerja }}</td>
                    <td>{{ $ot->nama_lemburan }}</td>
                    <td class="text-center">{{ round($ot->total_jam) }} Jam</td>
                    <td class="text-center">
                        <span class="status-{{ $ot->status }}">
                            {{ strtoupper($ot->status == 'waiting' || $ot->status == 'pending' ? 'Menunggu' : ($ot->status == 'approved' ? 'Disetujui' : 'Ditolak')) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 20px;">Tidak ada data ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="summary-row">
                <td colspan="6" style="text-align: right; padding-right: 10px;">TOTAL DURASI LEMBUR:</td>
                <td class="text-center">{{ round($totalJam) }} Jam</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <div class="signature-box">
            <p>Jakarta, {{ now()->translatedFormat('d F Y') }}</p>
            <p style="margin-bottom: 60px;">Dicetak oleh Administrator,</p>
            <p class="font-bold">__________________________</p>
            <p>Sistem Gas-Lembur</p>
        </div>
    </div>
</body>
</html>
