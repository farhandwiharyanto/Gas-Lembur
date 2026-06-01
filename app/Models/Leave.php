<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_name',
        'employee_no',
        'bagian',
        'divisi',
        'direktorat',
        'lokasi_kerja',
        'tanggal_mulai',
        'tanggal_selesai',
        'total_hari',
        'alasan',
        'status',
    ];

    /**
     * Get the user that owns the leave request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
