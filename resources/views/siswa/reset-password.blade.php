<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Kata Sandi Baru - Sistem Pembayaran SPP</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login-siswa.css') }}">
    <style>
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
        .hint {
            font-size: 12px;
            color: #9a8c7e;
            margin-top: 4px;
        }
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
        <h2 class="title">Buat Kata Sandi Baru</h2>
        <h3 class="subtitle">Reset Kata Sandi</h3>
        <p class="desc">
            Masukkan kata sandi baru untuk akun kamu.
        </p>

        @if($errors->any())
            <div class="alert-error">
                <ul style="margin:0; padding-left:16px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('siswa.password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="form-group">
                <label>Kata Sandi Baru</label>
                <input type="password" name="password" placeholder="Minimal 6 karakter" required>
                <p class="hint">Minimal 6 karakter</p>
            </div>

            <div class="form-group">
                <label>Konfirmasi Kata Sandi</label>
                <input type="password" name="password_confirmation" placeholder="Ulangi kata sandi baru" required>
            </div>

            <button type="submit">Simpan Kata Sandi Baru</button>
        </form>

        <a href="{{ route('siswa.login') }}" class="back-link">← Kembali ke Login</a>
    </div>

</div>

</body>
</html>