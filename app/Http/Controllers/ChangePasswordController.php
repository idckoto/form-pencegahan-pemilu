<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\notifPassChangeMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class ChangePasswordController extends Controller
{
    public function submitForgot(Request $request)
    {
        $request->validate([
            'email' => 'required|exists:users,email'
        ]);

        //cek user
        $pengguna = User::where('email',$request->email)->first();

        $details = [
            'user' => $pengguna->name,
            'id' => $pengguna->id,
        ];

        //$recipient = 'ozanfauzi39@gmail.com';
        $recipient = $pengguna->email;

        Mail::to($recipient)
        ->send(new notifPassChangeMail($details));

        return redirect('/')->with('statusForgot','Success Sent Email Request Change Password, Please Check Your Inbox');
    }

    public function submitChange($id)
    {
        $id=base64_decode($id);
        return view('user.change_password',compact('id'));
        //dd($id);
    }

    public function confirmForgot(Request $request)
    {
        //dd($request->id_user);
        $request->validate([
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password'
        ]);

        DB::beginTransaction();
        try {
            User::where('id',$request->id_user)
            ->update([
                'Sandi' => $request->confirm_password
            ]);
            DB::commit();
            // all good

            return redirect('/')->with('statusConfirm','Success Change Password, Please Login');
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return redirect('/')->with('statusForgot','Failed Change Password');
        }
    }
}