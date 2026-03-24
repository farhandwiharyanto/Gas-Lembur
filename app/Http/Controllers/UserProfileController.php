<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        return view('user.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:users,email,' . auth()->id(),
            'direktorat' => 'required|string|max:100',
            'divisi' => 'required|string|max:100',
            'bagian' => 'required|string|max:100',
            'sub_bagian' => 'required|string|max:100',
            'tanda_tangan' => 'nullable|image|mimes:png,jpg,jpeg|max:2048', // Allow image upload up to 2MB
        ]);

        $user = auth()->user();
        
        $signatureData = $user->tanda_tangan;

        if ($request->hasFile('tanda_tangan')) {
            $file = $request->file('tanda_tangan');
            $filename = 'signature_' . time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/signatures'), $filename);
            $signatureData = '/uploads/signatures/' . $filename;
        }

        $user->update([
            'name' => $request->name,
            'nik' => $request->nik,
            'email' => $request->email,
            'direktorat' => $request->direktorat,
            'divisi' => $request->divisi,
            'bagian' => $request->bagian,
            'sub_bagian' => $request->sub_bagian,
            'tanda_tangan' => $signatureData,
        ]);

        return redirect()->route('user.profile.edit')->with('success', 'Tanda tangan berhasil diperbarui.');
    }
}
