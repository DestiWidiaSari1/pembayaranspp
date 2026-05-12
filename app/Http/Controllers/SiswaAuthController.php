<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\SPP;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class SiswaAuthController extends Controller
{
    public function showLogin()
    {
        return view('siswa.login-siswa');
    }

    public function login(Request $request)
    {
        $siswa = Siswa::where('nis', $request->nis)->first();

        if (!$siswa || !Hash::check($request->password, $siswa->password)) {
            return back()->withErrors(['login' => 'NIS atau password salah.']);
        }

        Session::put('siswa_id', $siswa->id);
        Session::put('siswa_nama', $siswa->nama_lengkap);
        Session::put('siswa_kelas', $siswa->kelas);
        Session::put('siswa_nis', $siswa->nis);

        return redirect()->route('siswa.dashboard');
    }

    public function dashboard()
    {
    if (!Session::get('siswa_id')) {
        return redirect()->route('siswa.login');
    }

    $siswa = Siswa::find(Session::get('siswa_id'));
    $tahunIni = date('Y');

    // Ambil SPP terbaru saja (tanpa filter tahun/kelas karena kolom tidak ada)
    $spp = SPP::latest()->first();

    // Transaksi siswa ini, urutkan terbaru
    $transaksis = Transaksi::where('siswa_id', $siswa->id)
                    ->orderBy('tanggal_bayar', 'desc')
                    ->get();

    // Total sudah dibayar tahun ini (filter dari kolom yang ADA di transaksi)
    $totalBayar = Transaksi::where('siswa_id', $siswa->id)
                    ->whereYear('tanggal_bayar', $tahunIni) // pakai whereYear bukan where('tahun')
                    ->sum('jumlah_bayar');

    // Total tagihan setahun = 12 bulan x nominal SPP
    $totalTagihanSetahun = $spp ? ($spp->nominal * 12) : 0;

    $sisaTagihan = max(0, $totalTagihanSetahun - $totalBayar);
    $statusBayar = $sisaTagihan <= 0 ? 'Lunas' : 'Belum Lunas';

    return view('siswa.dashboard-siswa', compact(
        'siswa', 'transaksis', 'spp',
        'totalTagihanSetahun', 'totalBayar',
        'sisaTagihan', 'statusBayar'
    ));
    }

    public function tagihan()
{
    if (!Session::get('siswa_id')) {
        return redirect()->route('siswa.login');
    }

    $siswa = Siswa::find(Session::get('siswa_id'));
    $spp = SPP::latest()->first();
    $tahunIni = date('Y');

    $transaksis = Transaksi::where('siswa_id', $siswa->id)
                    ->whereYear('tanggal_bayar', $tahunIni)
                    ->orderBy('tanggal_bayar', 'asc')
                    ->get();

    $bayarPerBulan = $transaksis->groupBy(function($t) {
        return (int) date('n', strtotime($t->tanggal_bayar));
    });

    // Bulan pertama = bulan transaksi pertama, kalau tidak ada pakai bulan sekarang
    $bulanMulai = $transaksis->isNotEmpty()
        ? (int) date('n', strtotime($transaksis->first()->tanggal_bayar))
        : (int) date('n');

    return view('siswa.tagihan-spp', compact('siswa', 'spp', 'transaksis', 'bayarPerBulan', 'bulanMulai'));
}

    public function riwayat(Request $request)
{
    if (!Session::get('siswa_id')) {
        return redirect()->route('siswa.login');
    }

    $siswa = Siswa::find(Session::get('siswa_id'));
    $tahun = $request->tahun ?? date('Y');

    // Ganti where('tahun') → whereYear('tanggal_bayar')
    $transaksis = Transaksi::where('siswa_id', $siswa->id)
                    ->whereYear('tanggal_bayar', $tahun)
                    ->orderBy('tanggal_bayar', 'desc')
                    ->paginate(10);

    $totalBayar = Transaksi::where('siswa_id', $siswa->id)
                    ->whereYear('tanggal_bayar', $tahun)
                    ->sum('jumlah_bayar');

    $jumlahBayar = Transaksi::where('siswa_id', $siswa->id)
                    ->whereYear('tanggal_bayar', $tahun)
                    ->count();

    return view('siswa.riwayat-pembayaran', compact(
        'siswa', 'transaksis', 'totalBayar', 'jumlahBayar', 'tahun'
    ));
}

    public function logout()
    {
        Session::forget(['siswa_id', 'siswa_nama', 'siswa_kelas', 'siswa_nis']);
        return redirect()->route('siswa.login');
    }
}