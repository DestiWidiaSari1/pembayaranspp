<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="app-wrapper">

<!-- NAVBAR -->
<div class="navbar">
    <div class="nav-left">
        <i class="fa-solid fa-building logo-icon"></i>
        <span class="logo-text">Sistem Pembayaran SPP</span>
    </div>

    <div class="nav-right">
        <div class="user-box">
            <img src="{{ asset('images/fotoadmin.jpg') }}">
            <span>Admin</span>
        </div>
    </div>
</div>

<div class="container">

<!-- SIDEBAR -->
<div class="sidebar">
    <ul class="menu">

        <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}">
                <i class="fa fa-lock"></i> Dashboard
            </a>
        </li>

        <li class="{{ request()->routeIs('data-siswa') ? 'active' : '' }}">
            <a href="{{ route('data-siswa') }}">
                <i class="fa fa-graduation-cap"></i> Data Siswa
            </a>
        </li>

        <li class="{{ request()->routeIs('data-spp') ? 'active' : '' }}">
            <a href="{{ route('data-spp') }}">
                <i class="fa fa-file"></i><span>Data SPP</span>
            </a>
        </li>

        <li class="{{ request()->routeIs('transaksi-pembayaran') ? 'active' : '' }}">
            <a href="{{ route('transaksi-pembayaran') }}">
                <i class="fa fa-money-bill-wave"></i>
                <span>Transaksi Pembayaran</span>
            </a>
        </li>

        <li class="{{ request()->routeIs('laporan-pembayaran') ? 'active' : '' }}">
            <a href="{{ route('laporan-pembayaran') }}">
                <i class="fa fa-chart-line"></i> 
                <span>Laporan Pembayaran</span>
            </a>
        </li>

    </ul>

    <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="logout">
        <i class="fa fa-sign-out-alt"></i> Logout
    </button>
    </form>
</div>

<!-- MAIN -->
<div class="main">
    @yield('content')
</div>

</div>
</div>

</body>
</html>