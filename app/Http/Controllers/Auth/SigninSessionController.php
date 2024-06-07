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

class SigninSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.signin');
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

        return redirect('/');
    }

    public function reloadCaptcha(){
        return response()->json(['captcha'=>captcha_img('flat')]);
    }
}
