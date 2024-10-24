<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SigninRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

use App\Mail\ResetPasswordMail;
use App\Models\PasswordResetToken;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class SigninSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
    {
        return view('auth.signin_open');
        //return view('auth.signin_close');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(SigninRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy()
    {
        Session::flush();
        Auth::logout();
        Session::invalidate();
        Session::regenerateToken();

        //model sebelumnya
        //Auth::guard('web')->logout();
        //$request->session()->invalidate();
        //$request->session()->regenerateToken();

        return redirect('/signin');
    }

    public function reloadCaptcha(){
        return response()->json(['captcha'=>captcha_img('flat')]);
    }


    public function forgot_password()
    {
        return view('auth.forgot-password');
    }

    public function forgot_password_act(Request $request)
    {
        $customMessage = [
            'email.required'    => 'Email tidak boleh kosong',
            'email.email'       => 'Email tidak valid',
            'email.exists'      => 'Email tidak terdaftar di database',
        ];

        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], $customMessage);

        $token = \Str::random(60);

        PasswordResetToken::updateOrCreate(
            [
                'email' => $request->email
            ],
            [
                'email' => $request->email,
                'token' => $token,
                'created_at' => now(),
            ]
        );

        

        $send = Mail::to($request->email)->send(new ResetPasswordMail($token));

        dd([$request->email,$token,$send]);

        //return redirect()->route('forgot-password')->with('success', 'Kami telah mengirimkan link reset password ke email anda');
        return redirect('/forgot-password')->with('status', 'Kami telah mengirimkan link reset password ke email anda');
    }

    public function validasi_forgot_password_act(Request $request)
    {
        $customMessage = [
            'password.required' => 'Password tidak boleh kosong',
            'password.min'      => 'Password minimal 6 karakter',
        ];

        $request->validate([
            'password' => 'required|min:6'
        ], $customMessage);

        $token = PasswordResetToken::where('token', $request->token)->first();

        if (!$token) {
            return redirect('/signin')->with('danger', 'Token tidak valid');
        }

        $user = User::where('email', $token->email)->first();

        if (!$user) {
            return redirect('/signin')->with('warning', 'Email tidak terdaftar di database');
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        $token->delete();

        //return redirect()->route('login')->with('success', 'Password berhasil direset');
        return redirect('/signin')->with('success', 'Password berhasil direset');
    }

    public function validasi_forgot_password(Request $request, $token)
    {
        $getToken = PasswordResetToken::where('token', $token)->first();

        if (!$getToken) {
            return redirect('/signin')->with('danger', 'Token tidak valid');
        }

        return view('auth.validasi-token', compact('token'));
    }
}
