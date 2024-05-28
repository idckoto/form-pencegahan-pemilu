<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LogAktif;

class Useakses_pController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function pengaturan()
    {
        $user = User::findorfail(Auth::user()->id);
        return view('user.user_akses.edit_p', compact('user'));
    }
    
    public function update(Request $request)
    {
        $this->validate($request, [
            // 'name'      => 'required|',
            // 'email'     => 'required|email|unique:users,email,' . Auth::user()->id,
            'password' => 'nullable|min:8|confirmed',
            'old_password' => 'required_with:password',
        ]);
    
        $user = Auth::user(); // Get the authenticated user instance
    
        if ($request->input('password') === null) {
            $user->update([
                // 'name' => Auth::user()->name,
                'email' => $request->input('email'),
            ]);
        } else {
            // Validate old password
            if (!Hash::check($request->input('old_password'), $user->password)) {
                return back()->with('error', 'Password lama tidak cocok');
            }
    
            $user->update([
                // 'name' => Auth::user()->name,
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
            ]);
        }
    
        // Redirect with success message
        return back()->with('status', 'Password Berhasil Drubah');
    }
    

    public function update_image(Request $request)
    {
        $this->validate($request, [
            'profile_photo_path'        => 'required|image|mimes:png,jpg,jepg|max:2000',
        ]);
        //remove old image
        Storage::disk('local')->delete('public/staff/' . Auth::user()->profile_photo_path);

        //upload new image
        $profile_photo_path = $request->file('profile_photo_path');
        $profile_photo_path->storeAs('public/staff', $profile_photo_path->hashName());

        $user = User::where('id', Auth::user()->id);
        $user->update([
            'profile_photo_path'      => $profile_photo_path->hashName(),

        ]);

        if ($user) {
            //redirect dengan pesan sukses
            return back()->with('status', ' Berhasil Di Simpan');
        } else {
            //redirect dengan pesan error
            return back()->with('error', 'Berhasil Di Update');
        }
    }
}
