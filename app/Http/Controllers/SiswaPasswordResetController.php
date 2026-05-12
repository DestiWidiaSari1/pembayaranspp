<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SiswaPasswordResetController extends Controller
{
    // Form: minta link reset (isi email + NIS)
    public function showRequestForm()
    {
        return view('siswa.lupa-password');
    }

    // Proses: kirim link reset ke email
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'nis'   => 'required',
            'email' => 'required|email',
        ]);

        $siswa = Siswa::where('nis', $request->nis)
                      ->where('email', $request->email)
                      ->first();

        if (!$siswa) {
            return back()->withErrors(['error' => 'NIS dan email tidak cocok atau tidak ditemukan.']);
        }

        // Hapus token lama jika ada
        DB::table('siswa_password_resets')->where('email', $siswa->email)->delete();

        // Buat token baru
        $token = Str::random(64);

        DB::table('siswa_password_resets')->insert([
            'email'      => $siswa->email,
            'token'      => $token,
            'created_at' => Carbon::now(),
        ]);

        // Kirim email
        $resetUrl = route('siswa.password.reset.form', ['token' => $token, 'email' => $siswa->email]);

        Mail::send('siswa.email-reset-password', [
            'siswa'    => $siswa,
            'resetUrl' => $resetUrl,
        ], function ($message) use ($siswa) {
            $message->to($siswa->email)
                    ->subject('Reset Kata Sandi - Sistem Pembayaran SPP');
        });

        return back()->with('success', 'Link reset kata sandi telah dikirim ke email kamu. Cek inbox atau folder spam.');
    }

    // Form: isi password baru
    public function showResetForm(Request $request)
    {
        $token = $request->token;
        $email = $request->email;

        $record = DB::table('siswa_password_resets')
                    ->where('email', $email)
                    ->where('token', $token)
                    ->first();

        if (!$record) {
            return redirect()->route('siswa.password.request')
                             ->withErrors(['error' => 'Link tidak valid atau sudah digunakan.']);
        }

        $expired = Carbon::parse($record->created_at)->addHour()->isPast();
        if ($expired) {
            DB::table('siswa_password_resets')->where('email', $email)->delete();
            return redirect()->route('siswa.password.request')
                             ->withErrors(['error' => 'Link sudah kadaluarsa. Silakan minta link baru.']);
        }

        return view('siswa.reset-password', compact('token', 'email'));
    }

    // Proses: simpan password baru
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'                 => 'required|email',
            'token'                 => 'required',
            'password'              => 'required|min:6|confirmed',
        ]);

        $record = DB::table('siswa_password_resets')
                    ->where('email', $request->email)
                    ->where('token', $request->token)
                    ->first();

        if (!$record) {
            return back()->withErrors(['error' => 'Link tidak valid atau sudah digunakan.']);
        }

        $expired = Carbon::parse($record->created_at)->addHour()->isPast();
        if ($expired) {
            DB::table('siswa_password_resets')->where('email', $request->email)->delete();
            return redirect()->route('siswa.password.request')
                             ->withErrors(['error' => 'Link sudah kadaluarsa. Silakan minta link baru.']);
        }

        $siswa = Siswa::where('email', $request->email)->first();

        if (!$siswa) {
            return back()->withErrors(['error' => 'Siswa tidak ditemukan.']);
        }

        $siswa->update([
            'password' => Hash::make($request->password),
        ]);

        // Hapus token setelah dipakai
        DB::table('siswa_password_resets')->where('email', $request->email)->delete();

        return redirect()->route('siswa.login')
                         ->with('success', 'Kata sandi berhasil diubah. Silakan login.');
    }
}