<?php
namespace App\Traits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\LogToken;
use App\Models\User;


trait RefreshTokenTrait {
    public function refreshToken($username)
    {
        $caritoken=LogToken::where('username', $username)
        ->orderBy('id', 'DESC')
        ->first();
        // dd($caritoken);
        if (!isset($caritoken)) {
            return false;
        }
        $refresh_token=$caritoken->refresh_token;
        $respon =Http::asForm()->post('https://sso.bawaslu.go.id/auth/realms/superapp/protocol/openid-connect/token',[
            'grant_type'    => 'refresh_token',
            'client_id'     => 'login_superapp',
            'refresh_token' => $refresh_token,
            'client_secret' => 'Q1uyK7fTESPqtaUsVKUtoZzX1MWLy8Vo',
            
        ]);
        
        // return $respon;
        if ($respon->getStatusCode() === 200) {
            return true;
        }else{
            return false;
        }
    }
}