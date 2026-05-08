<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with('siswa');

        // Filter bulan
        if ($request->bulan) {
            $query->where('bulan', $request->bulan);
        }

        // Filter tahun
        if ($request->tahun) {
            $query->where('tahun', $request->tahun);
        }

        // Filter status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $transaksis = $query->paginate(10)->withQueryString();

        // Summary
        $totalSiswa      = \App\Models\Siswa::count();
        $totalTransaksi  = Transaksi::count();
        $totalPemasukan  = Transaksi::where('status', 'lunas')->sum('jumlah_bayar');
        $totalLunas      = Transaksi::where('status', 'lunas')->count();
        $totalBelum      = Transaksi::where('status', 'belum')->count();
        $persen_lunas    = $totalTransaksi > 0 ? round(($totalLunas / $totalTransaksi) * 100) : 0;
        $persen_belum    = $totalTransaksi > 0 ? round(($totalBelum / $totalTransaksi) * 100) : 0;

        return view('admin.laporan-pembayaran', compact(
            'transaksis', 'totalSiswa', 'totalTransaksi',
            'totalPemasukan', 'persen_lunas', 'persen_belum'
        ));
    }
}