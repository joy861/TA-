<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Tampilkan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // Cek login
        $credentials = [
            'username' => $request->username,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Cek role
            if (Auth::user()->role == 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('kasir.dashboard');
            }
        }

        return back()->with('error', 'Username atau password salah');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
        // ✅ Tampilkan form forgot password
    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    // ✅ Step 1: Cek username
    public function cekUsername(Request $request)
    {
        $request->validate([
            'username' => 'required'
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return back()->with('error', 'Username tidak ditemukan.');
        }

        if (!$user->pertanyaan_keamanan) {
            return back()->with('error', 'Akun ini belum mengatur pertanyaan keamanan. Hubungi admin.');
        }

        // Simpan sementara di session
        session([
            'reset_user_id'       => $user->id_user,
            'pertanyaan_keamanan' => $user->pertanyaan_keamanan,
        ]);

        return redirect()->route('forgot.password');
    }

    // ✅ Step 2: Verifikasi jawaban + reset password
    public function prosesReset(Request $request)
    {
        $request->validate([
            'jawaban'           => 'required',
            'password_baru'     => 'required|min:6',
            'password_konfirmasi' => 'required'
        ]);

        // Pastikan session masih ada
        if (!session('reset_user_id')) {
            return redirect()->route('forgot.password')
                ->with('error', 'Sesi telah habis. Silakan ulangi.');
        }

        $user = User::findOrFail(session('reset_user_id'));

        // Cek jawaban keamanan (case-insensitive)
        if (strtolower(trim($request->jawaban)) !== strtolower(trim($user->jawaban_keamanan))) {
            return back()->with('error', 'Jawaban keamanan salah.');
        }

        // Cek konfirmasi password
        if ($request->password_baru !== $request->password_konfirmasi) {
            return back()->with('error', 'Konfirmasi password tidak cocok.');
        }

        $user->update([
            'password' => Hash::make($request->password_baru)
        ]);

        // Hapus session reset
        session()->forget(['reset_user_id', 'pertanyaan_keamanan']);

        return redirect()->route('login')
            ->with('success', 'Password berhasil direset. Silakan login.');
    }
}