<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = [
        'siswa_id',
        'tanggal_bayar',
        'bulan',
        'tahun',
        'metode',
        'jumlah_bayar',
        'status',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
