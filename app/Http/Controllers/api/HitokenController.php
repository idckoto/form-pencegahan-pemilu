<?php

namespace App\Http\Controllers\api;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LogToken;

class HitokenController extends Controller
{
    
    public function index(Request $request)
    {
        // var_dump($request->header('X-APP-REFRESH-TOKEN'));die;
        $bearer = $request->header('Authorization');
        $expBearer = explode(' ',$bearer);
        $token = $expBearer[1];
        $refresh_token = $request->header('X-APP-REFRESH-TOKEN');
        $respone = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
            ])->get('https://sso.bawaslu.go.id/auth/realms/superapp/protocol/openid-connect/userinfo');
            
            // dd($respone->getStatusCode());
        if ($respone->getStatusCode() === 200) {
            $expToken = explode('.',$token);
            $payloadToken = base64_decode($expToken[1]);
            $payloadDecode = json_decode($payloadToken,true);
            $user = User::where('email', $payloadDecode['email'])->count();
            if (!$user) {
                return response()->json("User Tidak ada didata form pencegahan");
            }
            if (isset($payloadDecode['realm_access']['roles']) && in_array('app_formc',$payloadDecode['realm_access']['roles'])) {
                $cekLog=LogToken::where('username',$payloadDecode['email'])->count();
                if($cekLog > 0){
                    // dd($payloadDecode['email']);
                    LogToken::where('username', $payloadDecode['email'])
                    ->update(['token' => $token, 'refresh_token' => $refresh_token]);
                }else{
                    LogToken::create([
                        'username'          => $payloadDecode['email'],
                        'token'             => $token,
                        'refresh_token'     => $refresh_token
                    ]);
                }
                // dd($payloadDecode);
                return $respone;
            } else{
                return $respone;
            }

        }else{
            return redirect('/');
        } 
    }

}


