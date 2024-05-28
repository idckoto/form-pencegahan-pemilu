<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;

class PenggunaController extends Controller
{
    public function register()
    {
        $data['title'] = 'Register';
        return view('user/register', $data);
    }

    public function register_action(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:tb_user',
            'password' => 'required',
            'password_confirm' => 'required|same:password',
        ]);

        $user = new Pengguna([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);
        $user->save();

        return redirect()->route('login')->with('success', 'Registration success. Please login!');
    }


    public function login()
    {
        $data['title'] = 'Login';
        return view('user/login', $data);
    }

    public function halUser()
    {
        return view('user/aktivasi');
    }
    public function getUser(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
        $username=$request->username;
        $password=$request->password;
        $cek_user=User::where(['Login'=>$username, 'Sandi'=>$password])->first();
        // dd($cek_user);
        if(!isset($cek_user)){
            return redirect('/cek-user')->with('error',' Username Dan Password Tidak Terdaftar');
        }else{
    $getToken =Http::asForm()->post('https://dev.pencarian.me/auth/realms/superapp/protocol/openid-connect/token',[
            'grant_type' => 'client_credentials',
            'client_id'  => 'login_superapp',
            'client_secret' => 'Q1uyK7fTESPqtaUsVKUtoZzX1MWLy8Vo',
              
     ]);
     $getUser = Http::withHeaders([
         'Authorization' => 'Bearer ' .$getToken['access_token'],
         'Accept' => 'application/json',
         ])->get('https://dev.pencarian.me/auth/admin/realms/superapp/users');
        //  dd(json_decode($respone,true));
         $data=array_values(array_filter(json_decode($getUser,true), function ($item) use($username){
            return $item['username'] == strtolower($username);
          }));          
        if(!isset($data[0]['username'])){
            $createUser = Http::withHeaders([
                'Authorization' => 'Bearer ' .$getToken['access_token'],
                'Accept' => 'application/json',
                ])->post('https://dev.pencarian.me/auth/admin/realms/superapp/users', [
                    'username' => $cek_user->Login,
                    'firstName' => $cek_user->NamaLengkap,
                    'lastName' => '',
                    'email' => $cek_user->Email,
                    'enabled' => true,
                    'totp' => false,
                    'groups' => ["/group_app"]
                ]);
                // dd($createUser);
            $userweb=strtolower($cek_user->Login);
            $getUser = Http::withHeaders([
                'Authorization' => 'Bearer ' .$getToken['access_token'],
                'Accept' => 'application/json',
                ])->get('https://dev.pencarian.me/auth/admin/realms/superapp/users');
                $data=array_values(array_filter(json_decode($getUser,true), function ($item) use($userweb){
                    return $item['username'] == $userweb;
                }));
            $createPass = Http::withHeaders([
                'Authorization' => 'Bearer ' .$getToken['access_token'],
                'Accept' => 'application/json',
                ])->put('https://dev.pencarian.me/auth/admin/realms/superapp/users/'.$data[0]['id'].'/reset-password', [
                    'type' => 'password',
                    'value' => $cek_user->Sandi,
                    'temporary' => false
                ]);
            return redirect('/cek-user')->with('status','Username Telah Diaktifasi');
        }else{
            return redirect('/')->with('status','Username '.$request->username.' Telah Aktif diSuperApp');
        }

        // if(!isset($data[0]['email'])){
        //     return redirect('/cek-user')->with('error',' User Tersebut Tidak Memiliki Email');
        // }elseif($data[0]['emailVerified'] == false){
        // $enData=Crypt::encryptString($data[0]['email'].','.$data[0]['username'].','.$data[0]['id']);
        //     return redirect('/aktivasi-email/'.$enData);
        //     // return "Username ini belum melakukan verifikasi Email";
        // }else{
        // $enData=Crypt::encryptString($data[0]['email'].','.$data[0]['username'].','.$data[0]['id']);
        //     $cek_pass = Http::withHeaders([
        //         'Authorization' => 'Bearer ' .$getToken['access_token'],
        //         'Accept' => 'application/json',
        //         ])->get('https://dev.pencarian.me/auth/admin/realms/superapp/users/'.$data[0]['id'].'/credentials');
        //         // dd(json_decode($cek_pass,true));
        //         $pass = json_decode($cek_pass,true);
        //         if($pass==null){
        //             return redirect('/password/'.$enData);
        //         }else{
        //             return redirect('/')->with('status','Terdaftar dan telah Terverifikasi');
        //         }
        // }
    }
        // echo $h['admin']['email'];
        // die;
    }

    public function halAktivasi($data)
    {
        $Val=Crypt::decryptString($data);
        $exp=explode(',',$Val);
        $email=$exp[0];
        $username=$exp[1];
        $id=$exp[2];
        // dd($email);
        return view('user/aktivasiEmail',compact('email','username','id'));
    }
    public function sendEmail(Request $request)
    {
        $respon =Http::asForm()->post('https://dev.pencarian.me/auth/realms/superapp/protocol/openid-connect/token',[
            'grant_type' => 'client_credentials',
            'client_id'  => 'login_superapp',
            'client_secret' => 'Q1uyK7fTESPqtaUsVKUtoZzX1MWLy8Vo',
              
     ]);
        $respone = Http::withHeaders([
            'Authorization' => 'Bearer ' .$respon['access_token'],
            'Accept' => 'application/json',
            ])->put('https://dev.pencarian.me/auth/admin/realms/superapp/users/'.$request->id.'/send-verify-email');
            // dd($respone->getStatusCode());
            return redirect('/')->with('status','Terdaftar dan telah Terverifikasi');
    }

    public function password($data)
    {
        $Val=Crypt::decryptString($data);
        $exp=explode(',',$Val);
        $email=$exp[0];
        $username=$exp[1];
        $id=$exp[2];
        // dd($email);
        return view('user/password',compact('email','username','id'));
    }

    public function passwordAct(Request $request)
    {
        $request->validate([
            'new_password' => 'required',
            'new_password_confirmation' => 'required|same:new_password',
        ]);
        // dd("oke");
        $respon =Http::asForm()->post('https://dev.pencarian.me/auth/realms/superapp/protocol/openid-connect/token',[
            'grant_type' => 'client_credentials',
            'client_id'  => 'login_superapp',
            'client_secret' => 'Q1uyK7fTESPqtaUsVKUtoZzX1MWLy8Vo',
              
     ]);
        $respone = Http::withHeaders([
            'Authorization' => 'Bearer ' .$respon['access_token'],
            'Accept' => 'application/json',
            ])->put('https://dev.pencarian.me/auth/admin/realms/superapp/users/'.$request->id.'/reset-password', [
                'type' => 'password',
                'value' => $request->new_password,
                'temporary' => false
            ]);
            // dd($respone->getStatusCode());
            return redirect('/')->with('status','Terdaftar dan telah Terverifikasi');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function login_action(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
        if (Auth::attempt(['Login' => $request->username, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect('/dashboard');
        }
        return back()->withErrors([
            'password' => 'Wrong username or password',
        ]);
    }
}