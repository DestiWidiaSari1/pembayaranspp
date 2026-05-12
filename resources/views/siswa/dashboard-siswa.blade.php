<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard-siswa.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="app-wrapper">

    {{-- NAVBAR --}}
    <div class="navbar">
        <div class="nav-left">
            <i class="fa-solid fa-building logo-icon"></i>
            <span class="logo-text">Sistem Pembayaran SPP</span>
        </div>
        <div class="nav-right">
            <div class="user-box">
                <img src="{{ asset('images/fotoadmin.jpg') }}">
                <div>
                    <span class="user-name">{{ session('siswa_nama') }}</span>
                    <span class="user-kelas">{{ session('siswa_kelas') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="container">

        {{-- SIDEBAR --}}
        <div class="sidebar">
            <ul class="menu">
                <li class="active">
                    <a href="{{ route('siswa.dashboard') }}">
                        <i class="fa fa-lock"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('siswa.tagihan') }}">
                        <i class="fa fa-file-invoice"></i> Tagihan SPP
                    </a>
                </li>
                <li>
                    <a href="{{ route('siswa.riwayat') }}">
                        <i class="fa fa-history"></i> Riwayat Pembayaran
                    </a>
                </li>
            </ul>

            <form method="POST" action="{{ route('siswa.logout') }}">
                @csrf
                <button type="submit" class="logout">
                    <i class="fa fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>

        {{-- MAIN --}}
        <div class="main">

            {{-- HEADER BOX --}}
            <div class="header-box">
                <div class="text-group">
                    <h2>Selamat Datang, {{ session('siswa_nama') }}!</h2>
                    <p>Berikut adalah informasi tagihan dan riwayat pembayaran SPP.</p>
                </div>
            </div>

            <div class="summary-cards">
                <div class="s-card orange">
                    <div class="s-card-icon"><i class="fa fa-file-alt"></i></div>
                    <div class="s-card-info">
                        <span class="s-label">Tagihan SPP {{ date('Y') }} (12 Bulan)</span>
                        <span class="s-value">Rp {{ number_format($totalTagihanSetahun, 0, ',', '.') }}</span>
                    </div>
                    {{-- FIX: dulu hardcoded "belum", sekarang dinamis --}}
                    <span class="s-badge {{ $sisaTagihan <= 0 ? 'lunas' : 'belum' }}">{{ $statusBayar }}</span>
                </div>

                <div class="s-card green">
                    <div class="s-card-icon"><i class="fa fa-wallet"></i></div>
                    <div class="s-card-info">
                        <span class="s-label">Total Sudah Dibayar</span>
                        <span class="s-value">Rp {{ number_format($totalBayar, 0, ',', '.') }}</span>
                    </div>
                    <span class="s-badge lunas">Lunas</span>
                </div>

                <div class="s-card purple">
                    <div class="s-card-icon"><i class="fa fa-chart-pie"></i></div>
                    <div class="s-card-info">
                        <span class="s-label">Sisa Tagihan</span>
                        <span class="s-value">Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</span>
                    </div>
                    <span class="s-badge {{ $sisaTagihan <= 0 ? 'lunas' : 'belum' }}">{{ $statusBayar }}</span>
                </div>
            </div>

            {{-- GRID BAWAH --}}
            <div class="bottom-grid">

            {{-- RINGKASAN TAGIHAN --}}
            <div class="card">
                <div>
                    <div class="card-header">Ringkasan Tagihan SPP</div>
                    <table class="mini-table">
                        <thead>
                            <tr>
                                <th>Nominal</th>
                                <th>Status</th>
                                <th>Jatuh Tempo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($spp)
                            <tr>
                                <td>Rp {{ number_format($spp->nominal, 0, ',', '.') }} / bulan</td>
                                <td>
                                    <span class="badge-status {{ $sisaTagihan <= 0 ? 'lunas' : 'belum' }}">
                                        {{ $statusBayar }}
                                    </span>
                                </td>
                                <td>10/{{ date('m/Y') }}</td>
                            </tr>
                            @else
                            <tr><td colspan="3" class="empty">Belum ada tagihan</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <a href="{{ route('siswa.tagihan') }}" class="card-link">
                    <i class="fa fa-list"></i> Lihat Semua Tagihan ›
                </a>
            </div>

            {{-- RIWAYAT TERBARU --}}
            <div class="card">
                <div>
                    <div class="card-header">Riwayat Pembayaran Terbaru</div>
                    <table class="mini-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jumlah</th>
                                <th>Metode</th>
                                <th>Status</th>
                                <th>Bukti</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksis->take(4) as $t)
                            <tr>
                                <td>{{ $t->tanggal_bayar }}</td>
                                <td>Rp {{ number_format($t->jumlah_bayar, 0, ',', '.') }}</td>
                                <td>{{ ucfirst($t->metode) }}</td>
                                <td><span class="badge-status {{ $t->status }}">{{ ucfirst($t->status) }}</span></td>
                                <td>
                                    <a href="#" class="btn-bukti">
                                        <i class="fa fa-eye"></i> Lihat Bukti
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="empty">Belum ada riwayat</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <a href="{{ route('siswa.riwayat') }}" class="card-link">
                    <i class="fa fa-history"></i> Lihat Semua Riwayat ›
                </a>
            </div>

        </div>{{-- END bottom-grid --}}

            {{-- INFO BOX --}}
            <div class="info-box">
                <i class="fa fa-info-circle"></i>
                <div>
                    <strong>Informasi</strong>
                    <p>Pastikan pembayaran dilakukan sebelum tanggal jatuh tempo untuk menghindari keterlambatan.</p>
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>
