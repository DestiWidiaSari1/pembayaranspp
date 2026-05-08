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

        if (!$siswa) {
            return response()->json(['siswa' => null], 404);
        }

        // Cari SPP tahun berjalan
        $tahun = date('Y');
        $spp   = Spp::where('tahun', $tahun)->first();

        // Cek status tagihan
        $sudahBayar = Transaksi::where('siswa_id', $siswa->id)
            ->where('tahun', $tahun)
            ->where('status', 'lunas')
            ->count();

        $statusTagihan = $sudahBayar > 0 ? 'Lunas' : 'Belum Lunas';

        return response()->json([
            'siswa'          => $siswa,
            'spp'            => $spp,
            'status_tagihan' => $statusTagihan,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id'     => 'required|exists:siswas,id',
            'tanggal_bayar'=> 'required|date',
            'bulan'        => 'required',
            'tahun'        => 'required|integer',
            'metode'       => 'required',
            'jumlah_bayar' => 'required|numeric|min:1',
        ]);

        // Tentukan status otomatis
        $spp    = Spp::where('tahun', $request->tahun)->first();
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
            'siswa_id'     => $request->siswa_id,
            'tanggal_bayar'=> $request->tanggal_bayar,
            'bulan'        => $request->bulan,
            'tahun'        => $request->tahun,
            'metode'       => $request->metode,
            'jumlah_bayar' => $request->jumlah_bayar,
            'status'       => $status,
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
