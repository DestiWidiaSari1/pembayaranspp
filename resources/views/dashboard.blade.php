@extends('layouts.admin')

@section('content')

<!-- HEADER BOX -->
<div class="header-box">
    <div class="text-group">
        <h2>Selamat Datang, Admin 👋</h2>
        <p class="sub-title">Sistem Pembayaran SPP Sekolah</p>
        <p class="desc">
             Pantau dan kelola pembayaran SPP siswa dengan mudah dan efisien.
        </p>
    </div>
</div>

<div class="card-wrapper">
    <div class="card-inner">

        <!-- CARD 1 -->
        <div class="card-box big">
            <div class="icon green"><i class="fa fa-users"></i></div>

            <div class="card-content">
                <h3>650</h3>
                <p>Jumlah Siswa</p>
            </div>

            <div class="card-icon-right">
                <i class="fa fa-users"></i>
            </div>
        </div>

        <!-- CARD 2 -->
        <div class="card-box">
            <div class="icon orange"><i class="fa fa-chart-line"></i></div>

            <div class="card-content">
                <h3>120</h3>
                <p>Tagihan Belum Lunas</p>
                <span>IDR 19,8 Juta</span>
            </div>

            <div class="btn-detail">Lihat Detail ></div>
        </div>

        <!-- CARD 3 -->
        <div class="card-box">
            <div class="icon red"><i class="fa fa-wallet"></i></div>

            <div class="card-content">
                <h3>IDR 89,5 Juta</h3>
                <p>Total Pemasukan</p>
                <span>IDR 89,5 Juta</span>
            </div>
        </div>

        <!-- CARD 4 -->
        <div class="card-box">
            <div class="icon purple"><i class="fa fa-users"></i></div>

            <div class="card-content">
                <h3>45 Siswa</h3>
                <p>Menunggak</p>
                <span>IDR 8,75 Juta</span>

                <div class="progress-bar">
                    <div class="progress"></div>
                </div>
            </div>

            <div class="badge">75%</div>
        </div>

    </div>
</div>

@endsection