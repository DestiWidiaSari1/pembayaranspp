<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Poppins', Arial, sans-serif; background: #f5f0eb; margin: 0; padding: 0; }
        .container { max-width: 520px; margin: 40px auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,0.08); }
        .header { background: #4a90d9; padding: 28px 32px; text-align: center; }
        .header h1 { color: #fff; margin: 0; font-size: 20px; font-weight: 600; }
        .body { padding: 32px; }
        .body p { color: #4b3b2a; font-size: 14px; line-height: 1.7; margin: 0 0 16px; }
        .btn { display: block; width: fit-content; margin: 24px auto; background: #4a90d9; color: #fff; text-decoration: none; padding: 12px 32px; border-radius: 8px; font-size: 15px; font-weight: 600; }
        .warning { background: #fff8e1; border-left: 4px solid #f59e0b; padding: 12px 16px; border-radius: 6px; font-size: 13px; color: #7a6d5c; margin-top: 16px; }
        .footer { background: #f5f0eb; text-align: center; padding: 16px; font-size: 12px; color: #9a8c7e; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>🔐 Reset Kata Sandi</h1>
    </div>
    <div class="body">
        <p>Halo, <strong>{{ $siswa->nama_lengkap }}</strong>!</p>
        <p>Kami menerima permintaan untuk mereset kata sandi akun siswa kamu di <strong>Sistem Pembayaran SPP Sekolah</strong>.</p>
        <p>Klik tombol di bawah ini untuk membuat kata sandi baru:</p>

        <a href="{{ $resetUrl }}" class="btn">Reset Kata Sandi</a>

        <div class="warning">
            ⚠️ Link ini hanya berlaku selama <strong>1 jam</strong>. Jika kamu tidak merasa meminta reset kata sandi, abaikan email ini — kata sandi kamu tetap aman.
        </div>

        <p style="margin-top:20px; font-size:13px; color:#9a8c7e;">
            Jika tombol di atas tidak berfungsi, salin dan tempel link berikut ke browser:<br>
            <a href="{{ $resetUrl }}" style="color:#4a90d9; word-break:break-all;">{{ $resetUrl }}</a>
        </p>
    </div>
    <div class="footer">
        &copy; {{ date('Y') }} Sistem Pembayaran SPP Sekolah
    </div>
</div>
</body>
</html>