<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class PasswordController extends Controller
{
    public function forgot()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where('email', $request->email)->first();

        $otp = random_int(100000, 999999);

        $user->update([
            'otp' => $otp,
            'otp_expired_at' => now()->addMinutes(5)
        ]);

        Mail::to($user->email)->send(new OtpMail($otp));

        return redirect()
            ->route('password.verify', ['email' => $user->email])
            ->with('success', 'OTP telah dikirim ke email Anda');
    }

    public function verifyOtpForm(Request $request)
    {
        return view('auth.verify-otp', [
            'email' => $request->email
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|digits:6'
        ]);

        $user = User::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('otp_expired_at', '>=', now())
            ->first();

        if (! $user) {
            return back()->withErrors([
                'otp' => 'OTP salah atau sudah kadaluarsa'
            ]);
        }

        return redirect()->route('password.reset.form', [
            'email' => $user->email
        ]);
    }

    public function resetPasswordForm(Request $request)
    {
        return view('auth.reset-password', [
            'email' => $request->email
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        $user->update([
            'password' => Hash::make($request->password),
            'otp' => null,
            'otp_expired_at' => null
        ]);

        return redirect()
            ->route('login')
            ->with('success', 'Password berhasil direset');
    }
}