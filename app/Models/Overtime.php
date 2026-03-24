<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Overtime extends Model
{
    protected $fillable = [
        'employee_name',
        'employee_no',
        'sub_bagian',
        'bagian',
        'divisi',
        'direktorat',
        'lokasi_kerja',
        'nama_lemburan',
        'jam_masuk',
        'jam_keluar',
        'total_jam',
        'nomor_tiket',
        'pemberi_lembur',
        'tanda_tangan',
        'user_id',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
