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
            'jam_masuk' => $validated['jam_masuk'],
            'jam_keluar' => $validated['jam_keluar'],
            'total_jam' => $validated['total_jam'],
            'nomor_tiket' => $validated['nomor_tiket'],
            'pemberi_lembur' => $validated['pemberi_lembur'],
            'tanda_tangan' => $fileName,
            'user_id' => $user->id,
            'status' => 'waiting'
        ]);

        return redirect()->route('user.history.index')->with('success', 'Formulir lembur berhasil dikirim.');
    }
}
