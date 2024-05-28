<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('index');
    }

    public function index(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        if (empty($credentials['password'])) {
            return response()->json([
                'message' => 'Password is required.',
            ], 400);
        }
    
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials. Please check your email and password.',
            ], 401);
        }
    
        $user = User::where('email', $credentials['email'])->firstOrFail();
    
        $token = $user->createToken('auth_token')->plainTextToken;
    // dd($token);
        return response()->json([
            'message' => 'Login Success',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
    
    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'message' => 'Logout success'
        ]);
    }
}
