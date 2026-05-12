<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Siswa;
use App\Models\Spp;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with('siswa');

        if ($request->search) {
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('nis', 'like', '%' . $request->search . '%')
                  ->orWhere('nama_lengkap', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->kelas) {
            $query->whereHas('siswa', fn($q) => $q->where('kelas', $request->kelas));
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $transaksis = $query->latest()->paginate(10);
        $kelasList  = Siswa::select('kelas')->distinct()->orderBy('kelas')->pluck('kelas');

        return view('admin.transaksi-pembayaran', compact('transaksis', 'kelasList'));
    }

    public function cariSiswa(Request $request)
{
    $siswa = Siswa::where('nis', $request->nis)->first();
    if (!$siswa) return response()->json(['siswa' => null], 404);

    // Ambil tingkat dari kelas (X IPA 1 → X, XI IPS 2 → XI, XII IPA 1 → XII)
    $tingkat = explode(' ', $siswa->kelas)[0];

    // Cari SPP berdasarkan tingkat SAJA (hapus filter tahun)
    $spp = Spp::where('tingkat', $tingkat)->first();

    $sudahBayar = 0;
    if ($spp) {
        $sudahBayar = Transaksi::where('siswa_id', $siswa->id)
            ->where('status', 'lunas')
            ->count();
    }

    return response()->json([
        'siswa'          => $siswa,
        'spp'            => $spp,
        'status_tagihan' => $sudahBayar > 0 ? 'Lunas' : 'Belum Lunas',
    ]);
}

    public function store(Request $request)
{
    $request->validate([
        'siswa_id'      => 'required|exists:siswas,id',
        'tanggal_bayar' => 'required|date',
        'bulan'         => 'required',
        'tahun'         => 'required|integer',
        'metode'        => 'required',
        'jumlah_bayar'  => 'required|numeric|min:1',
    ]);

    $siswa  = Siswa::findOrFail($request->siswa_id);
    $tingkat = explode(' ', $siswa->kelas)[0];
    $spp    = Spp::where('tingkat', $tingkat)->first();

    $status = 'belum';
    if ($spp) {
        if ($request->jumlah_bayar >= $spp->nominal) {
            $status = 'lunas';
        } elseif ($request->jumlah_bayar > 0) {
            $status = 'cicilan';
        }
    } else {
        $status = 'lunas';
    }

    Transaksi::create([
        'siswa_id'      => $request->siswa_id,
        'spp_id'        => $spp?->id,
        'tanggal_bayar' => $request->tanggal_bayar,
        'bulan'         => $request->bulan,
        'tahun'         => $request->tahun,
        'metode'        => $request->metode,
        'jumlah_bayar'  => $request->jumlah_bayar,
        'status'        => $status,
    ]);

    return redirect()->route('transaksi-pembayaran')->with('success', 'Pembayaran berhasil disimpan.');
}

    public function show($id)
    {
        $transaksi = Transaksi::with('siswa')->findOrFail($id);
        return response()->json(['transaksi' => $transaksi]);
    }

    public function destroy($id)
    {
        Transaksi::findOrFail($id)->delete();
        return redirect()->route('transaksi-pembayaran')->with('success', 'Transaksi berhasil dihapus.');
    }
}
