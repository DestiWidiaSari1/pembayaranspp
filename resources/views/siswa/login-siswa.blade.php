<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Siswa - Sistem Pembayaran SPP</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login-siswa.css') }}">
</head>
<body>

<div class="main-container">

        <!-- HEADER -->
    <div class="header">
        <img src="{{ asset('images/logo1.png') }}" alt="Logo">
        <span>Sistem Pembayaran SPP Sekolah</span>
    </div>
    
    <!-- ILUSTRASI KANAN -->
    <div class="right-image">
        <img src="{{ asset('images/ilustrasilogin1.png') }}" alt="Ilustrasi">
    </div>
    <!-- LOGIN CARD -->
    <div class="login-box">
        <h2 class="title">Selamat Datang!</h2>
        <h3 class="subtitle">Login Siswa</h3>
        <p class="desc">
            Silakan login sebagai siswa untuk mengakses informasi pembayaran SPP.
        </p>

        <form method="POST" action="{{ route('siswa.login.submit') }}">
            @csrf

            <div class="form-group">
                <label>NIS</label>
                <input type="text" name="nis" placeholder="Masukan NIS Anda" required>
            </div>

            <div class="form-group">
                <label>Kata Sandi</label>
                <input type="password" name="password" placeholder="Masukan Kata Sandi Anda" required>
            </div>
            
            <div class="forgot">
                <a href="{{ route('siswa.password.request') }}">Lupa Kata Sandi?</a>
            </div>
            
            <button type="submit">Login</button>
        </form>

    </div>

</div>

</body>
</html>