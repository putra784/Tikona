<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\VerifyEmailOtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function showVerifyOtp()
    {
        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $userId = session('otp_user_id');
        $otpHash = session('otp_code');
        $expiresAt = session('otp_expires_at');

        if (!$userId || !$otpHash || !$expiresAt) {
            return redirect()
                ->route('register')
                ->withErrors([
                    'otp' => 'Sesi OTP tidak ditemukan. Silakan daftar kembali.',
                ]);
        }

        if (now()->greaterThan($expiresAt)) {
            return back()->withErrors([
                'otp' => 'OTP sudah kedaluwarsa.',
            ]);
        }

        if (!Hash::check($request->otp, $otpHash)) {
            return back()->withErrors([
                'otp' => 'OTP yang dimasukkan salah.',
            ]);
        }

        $user = User::findOrFail($userId);

        $user->update([
            'email_verified_at' => now(),
        ]);

        Auth::login($user);

        $request->session()->forget([
            'otp_user_id',
            'otp_code',
            'otp_expires_at',
        ]);

        $request->session()->regenerate();

        return redirect('/login')
            ->with('success', 'Email berhasil diverifikasi.');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
            'email_verified_at' => null,
        ]);

        $otp = random_int(100000, 999999);

        session([
            'otp_user_id' => $user->id,
            'otp_code' => Hash::make($otp),
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        Mail::to($user->email)->send(
            new VerifyEmailOtpMail($otp)
        );

        return redirect()
            ->route('otp.form')
            ->with('success', 'OTP telah dikirim ke email Anda.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            return redirect('/dashboard')
                ->with('success', 'Login successful.');
        }

        return back()
            ->withErrors([
                'email' => 'Email atau password salah.',
            ])
            ->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')
            ->with('success', 'Logout successful.');
    }

    public function resendOtp()
    {
        $userId = session('otp_user_id');

        if (!$userId) {
            return redirect()
                ->route('register')
                ->withErrors([
                    'otp' => 'Sesi verifikasi tidak ditemukan. Silakan daftar kembali.',
                ]);
        }

        $user = User::findOrFail($userId);

        // Generate OTP baru
        $otp = random_int(100000, 999999);

        // Simpan OTP baru
        session([
            'otp_code' => Hash::make($otp),
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        // Kirim OTP baru ke email user
        Mail::to($user->email)->send(
            new VerifyEmailOtpMail($otp)
        );

        return back()->with(
            'success',
            'OTP baru telah dikirim ke email Anda.'
        );
    }
}
