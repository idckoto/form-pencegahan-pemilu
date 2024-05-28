<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;


class ResetPasswordController extends Controller
{
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'Login' => 'required',
            'Sandi' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        if ($request->Login != 'fauzi' && $request->Sandi != '123456789')
            return response()->json(['message' => 'kredensial salah']);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => $password
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Password berhasil direset'])
            : response()->json(['error' => 'Terjadi kesalahan saat mereset password'], 500);
    }
}
