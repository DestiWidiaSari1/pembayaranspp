@extends('layouts.admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/laporan-pembayaran.css') }}">

<!-- HEADER -->
<div class="page-header">
    <div>
        <h2>Laporan Pembayaran SPP</h2>
        <p>Lihat dan Cetak Laporan Pembayaran SPP</p>
    </div>
    <div class="export-group">
        <button class="btn-export btn-pdf">
            <i class="fa fa-file-pdf"></i> Export PDF
        </button>
        <button class="btn-export btn-excel">
            <i class="fa fa-file-excel"></i> Export Excel
        </button>
    </div>
</div>

<div class="content-box">

    <!-- FILTER -->
<form method="GET" action="{{ route('laporan-pembayaran') }}">
<div class="filter-wrapper">
    <div class="filter-item">
        <label>Filter Bulan</label>
        <div class="select-wrap">
            <i class="fa fa-calendar"></i>
            <select name="bulan">
                <option value="">Semua Bulan</option>
                @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $b)
                <option value="{{ $b }}" {{ request('bulan') == $b ? 'selected' : '' }}>{{ $b }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="filter-item">
        <label>Filter Tahun</label>
        <div class="select-wrap">
            <i class="fa fa-calendar"></i>
            <select name="tahun">
                <option value="">Semua Tahun</option>
                @foreach(['2024','2025','2026'] as $y)
                <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="filter-item">
        <label>Status</label>
        <div class="select-wrap">
            <select name="status">
                <option value="">Semua Status</option>
                <option value="lunas"  {{ request('status') == 'lunas'  ? 'selected' : '' }}>Lunas</option>
                <option value="belum"  {{ request('status') == 'belum'  ? 'selected' : '' }}>Belum Lunas</option>
            </select>
        </div>
    </div>
    <div class="filter-item" style="justify-content:flex-end;">
        <label style="opacity:0;">.</label>
        <button type="submit" class="btn-filter">
            <i class="fa fa-search"></i> Filter
        </button>
    </div>
</div>
</form>

    <!-- SUMMARY -->
<div class="summary-box">
    <div class="summary-card">
        <div class="summary-icon bg-green"><i class="fa fa-users"></i></div>
        <div class="summary-text">
            <h4>Total Siswa</h4>
            <p>{{ $totalSiswa }}</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-icon bg-orange"><i class="fa fa-money-bill-wave"></i></div>
        <div class="summary-text">
            <h4>Total Transaksi</h4>
            <p>{{ $totalTransaksi }}</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-icon bg-blue"><i class="fa fa-wallet"></i></div>
        <div class="summary-text">
            <h4>Total Pemasukan</h4>
            <p>Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-icon bg-purple"><i class="fa fa-chart-pie"></i></div>
        <div class="summary-text">
            <h4>Lunas</h4>
            <p>{{ $persen_lunas }}%</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-icon bg-pink"><i class="fa fa-clock"></i></div>
        <div class="summary-text">
            <h4>Belum Lunas</h4>
            <p>{{ $persen_belum }}%</p>
        </div>
    </div>
</div>

<!-- TABLE -->
<div class="table-box">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Bayar</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Jumlah Bayar</th>
                <th>Metode</th>
                <th>Status Pembayaran</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksis as $i => $t)
            <tr>
                <td>{{ $transaksis->firstItem() + $i }}</td>
                <td>{{ $t->tanggal_bayar }}</td>
                <td>{{ $t->siswa->nis ?? '-' }}</td>
                <td class="text-left">{{ $t->siswa->nama_lengkap ?? '-' }}</td>
                <td>{{ $t->siswa->kelas ?? '-' }}</td>
                <td>{{ $t->jumlah_bayar ? 'Rp ' . number_format($t->jumlah_bayar, 0, ',', '.') : '–' }}</td>
                <td>{{ $t->metode ?? '–' }}</td>
                <td>
                    <span class="badge {{ $t->status }}">
                        {{ $t->status === 'lunas' ? 'Lunas' : 'Belum Lunas' }}
                    </span>
                </td>
                <td>
                    <button class="btn-detail">
                        <i class="fa fa-eye"></i> Lihat Detail
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align:center; padding:28px; color:#7b6a58;">
                    Belum ada data transaksi.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination-box">
        <div class="pagination-left">
            @if($transaksis->onFirstPage())
                <span class="page-nav disabled">‹ Previous</span>
            @else
                <a href="{{ $transaksis->previousPageUrl() }}" class="page-nav">‹ Previous</a>
            @endif

            @foreach($transaksis->getUrlRange(1, $transaksis->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="page-num {{ $page == $transaksis->currentPage() ? 'active' : '' }}">{{ $page }}</a>
            @endforeach

            @if($transaksis->hasMorePages())
                <a href="{{ $transaksis->nextPageUrl() }}" class="page-nav">Next ›</a>
            @else
                <span class="page-nav disabled">Next ›</span>
            @endif
        </div>
        <span>Menampilkan {{ $transaksis->firstItem() ?? 0 }} sampai {{ $transaksis->lastItem() ?? 0 }} dari {{ $transaksis->total() }} data</span>
    </div>
</div>
@endsection