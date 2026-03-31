<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Overtime;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OvertimeController extends Controller
{
    public function create()
    {
        return view('overtime-form');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'tanda_tangan' => 'required|string',
            'nama_lemburan' => 'required|string|max:255',
            'tanggal_masuk' => 'required|date',
            'tanggal_keluar' => 'required|date',
            'jam_masuk' => 'required',
            'jam_keluar' => 'required',
            'total_jam' => 'required|string',
            'nomor_tiket' => 'required|string|max:100',
            'pemberi_lembur' => 'required|string|max:255',
        ]);

        $fileName = null;
        if (str_contains($validated['tanda_tangan'], ';base64,')) {
            $image_parts = explode(";base64,", $validated['tanda_tangan']);
            $image_base64 = base64_decode($image_parts[1]);
            $fileName = 'signatures/' . Str::uuid() . '.png';
            Storage::disk('public')->put($fileName, $image_base64);
        } else {
            // Already a path or normal string
            $fileName = $validated['tanda_tangan'];
        }

        Overtime::create([
            'employee_name' => $user->name,
            'employee_no' => $user->nik,
            'sub_bagian' => $user->sub_bagian,
            'bagian' => $user->bagian,
            'divisi' => $user->divisi,
            'direktorat' => $user->direktorat,
            'lokasi_kerja' => collect([$user->lokasi_kerja])->filter()->first() ?? 'Tidak ada data', 
            'nama_lemburan' => $validated['nama_lemburan'],
            'tanggal_masuk' => $validated['tanggal_masuk'],
            'tanggal_keluar' => $validated['tanggal_keluar'],
            'jam_masuk' => $validated['jam_masuk'],
            'jam_keluar' => $validated['jam_keluar'],
            'total_jam' => round($validated['total_jam']),
            'nomor_tiket' => $validated['nomor_tiket'],
            'pemberi_lembur' => $validated['pemberi_lembur'],
            'tanda_tangan' => $fileName,
            'user_id' => $user->id,
            'status' => 'waiting'
        ]);

        return redirect()->route('user.history.index')->with('success', 'Formulir lembur berhasil dikirim.');
    }

    public function edit($id)
    {
        $overtime = Overtime::where('user_id', auth()->id())->findOrFail($id);
        
        if ($overtime->status !== 'waiting' && $overtime->status !== 'pending') {
            return redirect()->route('user.history.index')->with('error', 'Hanya pengajuan yang masih menunggu persetujuan yang dapat diubah.');
        }

        return view('overtime-form', compact('overtime'));
    }

    public function update(Request $request, $id)
    {
        $overtime = Overtime::where('user_id', auth()->id())->findOrFail($id);

        if ($overtime->status !== 'waiting' && $overtime->status !== 'pending') {
            return redirect()->route('user.history.index')->with('error', 'Pengajuan ini sudah diproses dan tidak dapat diubah.');
        }

        $validated = $request->validate([
            'tanda_tangan' => 'required|string',
            'nama_lemburan' => 'required|string|max:255',
            'tanggal_masuk' => 'required|date',
            'tanggal_keluar' => 'required|date',
            'jam_masuk' => 'required',
            'jam_keluar' => 'required',
            'total_jam' => 'required|string',
            'nomor_tiket' => 'required|string|max:100',
            'pemberi_lembur' => 'required|string|max:255',
        ]);

        $fileName = $overtime->tanda_tangan;
        if (str_contains($validated['tanda_tangan'], ';base64,')) {
            $image_parts = explode(";base64,", $validated['tanda_tangan']);
            $image_base64 = base64_decode($image_parts[1]);
            $fileName = 'signatures/' . Str::uuid() . '.png';
            Storage::disk('public')->put($fileName, $image_base64);
        } else {
            $fileName = $validated['tanda_tangan'];
        }

        $overtime->update([
            'nama_lemburan' => $validated['nama_lemburan'],
            'tanggal_masuk' => $validated['tanggal_masuk'],
            'tanggal_keluar' => $validated['tanggal_keluar'],
            'jam_masuk' => $validated['jam_masuk'],
            'jam_keluar' => $validated['jam_keluar'],
            'total_jam' => round($validated['total_jam']),
            'nomor_tiket' => $validated['nomor_tiket'],
            'pemberi_lembur' => $validated['pemberi_lembur'],
            'tanda_tangan' => $fileName,
        ]);

        return redirect()->route('user.history.index')->with('success', 'Formulir lembur berhasil diperbarui.');
    }

    public function print($id)
    {
        $overtime = Overtime::with('user')->findOrFail($id);

        // Cari atasan langsung (Pimpinan) berdasarkan bagian yang sama
        $pimpinan = \App\Models\User::where('role', 'pimpinan')
                        ->where('bagian', $overtime->bagian)
                        ->first();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('overtime.print', compact('overtime', 'pimpinan'));
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download('Surat_Tugas_Lembur_' . $overtime->employee_name . '_' . \Carbon\Carbon::parse($overtime->jam_masuk)->format('Ymd') . '.pdf');
    }
}
