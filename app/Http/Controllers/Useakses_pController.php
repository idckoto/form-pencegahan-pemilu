<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LogAktif;
use Intervention\Image\Facades\Image as Image;

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
            if($request->input(key: 'name') !== Auth::user()->name) { // jika name input masih sama dengan name lama, tidak update namen-nya
                $user->update([
                    'name' => $request->input('name'),
                    //'email' => $request->input('email'),
                ]);
            }

        } else {
            // Validate old password
            if (!Hash::check($request->input('old_password'), $user->password)) {
                return back()->with('error', 'Password lama tidak cocok');
            }
    
            $user->update([
                'name' => $request->input('name'),
                //'email' => $request->input('email'),
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
        //Storage::disk('local')->delete('staff/' . Auth::user()->profile_photo_path);
        
        if ($request->hasFile('profile_photo_path')) {
            $file = $request->file('profile_photo_path');
            $hashedName = Hash::make($file->getClientOriginalName() . time()); // Tambahkan time() untuk variasi
            $hashedName = str_replace('/', '', $hashedName); // Hilangkan karakter '/' agar aman untuk nama file
            $extension = $file->getClientOriginalExtension();
            $imageName = $hashedName . '.' . $extension;
            
            $file->move(public_path('staff'), $imageName);

            //$request->profile_photo_path->move(public_path('staff'), $imageName);

            $user = User::where('id', Auth::user()->id);
            $user->update([
                'profile_photo_path'      => $imageName,
    
            ]);
    
            if ($user) {
                //redirect dengan pesan sukses
                return back()->with('status', ' Berhasil Di Simpan');
            } else {
                //redirect dengan pesan error
                return back()->with('error', 'Berhasil Di Update');
            }          
        }

        //upload new image
       // $profile_photo_path = $request->file('profile_photo_path');

        //$profile_photo_path->storeAs('public/storage/staff', $profile_photo_path->hashName());
        //Storage::disk('local')->put('staff'.'/'.$profile_photo_path->hashName(), 'public');


    }
}
