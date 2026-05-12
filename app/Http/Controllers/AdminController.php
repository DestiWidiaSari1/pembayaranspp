<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Transaksi;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $jumlahSiswa       = Siswa::count();
        $tagihanBelumLunas = Transaksi::where('status', 'belum')->count();
        $totalPemasukan    = Transaksi::sum('jumlah_bayar');
        $menunggak         = 0;

        return view('dashboard', compact(
            'jumlahSiswa',
            'tagihanBelumLunas',
            'totalPemasukan',
            'menunggak'
        ));
    }
}