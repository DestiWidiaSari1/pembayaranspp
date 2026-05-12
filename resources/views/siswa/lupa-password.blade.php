<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi - Sistem Pembayaran SPP</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login-siswa.css') }}">
    <style>
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 16px;
            font-size: 14px;
        }
        .alert-error {
            background: #fde8e8;
            color: #9b1c1c;
            border: 1px solid #fca5a5;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 16px;
            font-size: 14px;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 14px;
            font-size: 13px;
            color: #4a90d9;
            text-decoration: none;
        }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="main-container">

    <div class="header">
        <img src="{{ asset('images/logo1.png') }}" alt="Logo">
        <span>Sistem Pembayaran SPP Sekolah</span>
    </div>

    <div class="right-image">
        <img src="{{ asset('images/ilustrasilogin1.png') }}" alt="Ilustrasi">
    </div>

    <div class="login-box">
        <h2 class="title">Lupa Kata Sandi?</h2>
        <h3 class="subtitle">Reset Kata Sandi</h3>
        <p class="desc">
            Masukkan NIS dan email kamu yang terdaftar. Kami akan mengirimkan link untuk mengatur ulang kata sandi.
        </p>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->has('error'))
            <div class="alert-error">{{ $errors->first('error') }}</div>
        @endif

        <form method="POST" action="{{ route('siswa.password.send') }}">
            @csrf

            <div class="form-group">
                <label>NIS</label>
                <input type="text" name="nis" placeholder="Masukan NIS Anda" value="{{ old('nis') }}" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Masukan Email Terdaftar" value="{{ old('email') }}" required>
            </div>

            <button type="submit">Kirim Link Reset</button>
        </form>

        <a href="{{ route('siswa.login') }}" class="back-link">← Kembali ke Login</a>
    </div>

</div>

</body>
</html>