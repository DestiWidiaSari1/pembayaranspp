<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $fillable = ['nis', 'nama_lengkap', 'kelas', 'jurusan', 'email', 'password'];

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'siswa_id');
    }
}