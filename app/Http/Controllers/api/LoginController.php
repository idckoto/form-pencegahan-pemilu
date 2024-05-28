<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
      
        $credentials = $request->only('Login', 'Sandi');

        if (Auth::attempt($credentials)) {
            $user = Auth::User();
            $accessToken = str_random(60);
            $user->token = $accessToken;
            $user->save();

            return response()->json(['access_token' => $accessToken]);
        } else {
            return response()->json(['error' => 'Tidak diizinkan'], 401);
        }
    }
}
