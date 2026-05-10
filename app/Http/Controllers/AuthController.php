<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ================= LOGIN VIEW =================
    public function showLogin()
    {
        return view('auth.login');
    }

    // ================= REGISTER VIEW =================
    public function showRegister()
    {
        return view('auth.register');
    }

    // ================= REGISTER =================
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Simpan user
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        // ❌ HAPUS AUTO LOGIN
        // Auth::login($user);

        // ✅ Redirect ke login + pesan sukses
        return redirect()
            ->route('login')
            ->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // ================= LOGIN =================
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            // Cek role
            if (Auth::user()->role == 'admin') {
                return redirect()->intended('/admin/dashboard');
            }

            return redirect()->intended('/user/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // ================= LOGOUT =================
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // ================= FORGOT PASSWORD =================
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where('email', $request->email)->first();
        
        // Generate 6 digit OTP
        $otp = sprintf("%06d", mt_rand(1, 999999));
        
        $user->update([
            'otp_code' => $otp,
            'otp_expired_at' => now()->addMinutes(10) // 10 menit expired
        ]);

        // Normally we'd send an email here using Laravel Mail
        // Mail::raw("Kode OTP Anda adalah: $otp", function($msg) use ($user) {
        //     $msg->to($user->email)->subject('Reset Password OTP');
        // });

        return back()->with([
            'success' => 'Kode OTP telah dikirim ke email Anda. (Dummy: ' . $otp . ')',
            'email_sent' => $request->email
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp_code' => 'required|numeric'
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->otp_code !== $request->otp_code || now()->greaterThan($user->otp_expired_at)) {
            return back()->with('error', 'Kode OTP salah atau sudah kadaluarsa.')->with('email_sent', $request->email);
        }

        return back()->with([
            'success' => 'Kode OTP valid. Silakan buat password baru.',
            'email_verified' => $request->email
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed'
        ]);

        $user = User::where('email', $request->email)->first();

        $user->update([
            'password' => Hash::make($request->password),
            'otp_code' => null,
            'otp_expired_at' => null
        ]);

        return redirect()->route('login')->with('success', 'Password berhasil direset. Silakan login dengan password baru.');
    }
}