<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Tampilkan formulir login Administrator TI
     */
    public function showLoginForm()
    {
        // Jika sudah login, langsung alihkan ke Dashboard
        if (Auth::check()) {
            return redirect()->route('bridge.index');
        }

        return view('auth.login');
    }

    /**
     * Proses autentikasi login pengguna
     */
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $remember = $request->boolean('remember');

        // Autentikasi dengan Auth::attempt() terhadap tabel users
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Catat riwayat aktivitas login ke database
            try {
                $riwayat = \App\Models\RiwayatLogin::create([
                    'user_id'       => $user->id,
                    'nama_pengguna' => $user->name,
                    'username'      => $user->username ?? $user->email,
                    'role'          => $user->role ?? 'Admin',
                    'ip_address'    => $request->ip(),
                    'user_agent'    => $request->userAgent(),
                    'login_at'      => now(),
                    'logout_at'     => null,
                    'status'        => 'Aktif',
                ]);
                $request->session()->put('login_riwayat_id', $riwayat->id);
            } catch (\Throwable $e) {
                // Abaikan jika pencatatan riwayat terkendala
            }

            return redirect()->intended(route('bridge.index'))->with('success', 'Selamat datang di Dashboard AutoXML Gateway, ' . ($user->name ?? 'Admin') . '!');
        }

        // Jika gagal, kembali dengan error dan old input username
        return back()->withErrors([
            'auth_failed' => 'Username atau password yang Anda masukkan salah. Silakan coba lagi.',
        ])->withInput($request->only('username', 'remember'));
    }

    /**
     * Proses logout pengguna
     */
    public function logout(Request $request)
    {
        // Update jam logout pada riwayat sesi aktif
        try {
            $historyId = $request->session()->get('login_riwayat_id');
            if ($historyId) {
                \App\Models\RiwayatLogin::where('id', $historyId)->update([
                    'logout_at' => now(),
                    'status'    => 'Logout',
                ]);
            } elseif (Auth::check()) {
                \App\Models\RiwayatLogin::where('user_id', Auth::id())
                    ->where('status', 'Aktif')
                    ->whereNull('logout_at')
                    ->latest('login_at')
                    ->first()?->update([
                        'logout_at' => now(),
                        'status'    => 'Logout',
                    ]);
            }
        } catch (\Throwable $e) {
            // Abaikan jika tabel riwayat belum ada
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'Anda telah berhasil keluar dari sistem.');
    }
}

