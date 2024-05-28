<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\LogAktif;
use App\Models\Jabatan;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Petuga;
use App\Models\User;

class UseaksesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
    if (Auth::user()->id_admin == '0' or Auth::user()->id_admin == '1') {
        if (Auth::user()->id_divisi == null) {
                if (Auth::user()->Jabatan <> 'Bawaslu Kecamatan') {

                $user = User::where('Provinsi', Auth::user()->Provinsi)
                    ->where('KabKota', Auth::user()->KabKota)
                    ->where('Kecamatan', Auth::user()->Kecamatan)
                    ->where('DesaKel', Auth::user()->DesaKel)
                    ->where('id', '<>', Auth::user()->id)
                    ->get();

                $ajuanuser = User::where('Provinsi', Auth::user()->Provinsi)
                    ->where('KabKota', Auth::user()->KabKota)
                    ->where('Kecamatan', Auth::user()->Kecamatan)
                    ->where('DesaKel', Auth::user()->DesaKel)

                    ->get();

                // User Tingkat Provinsi
                $userprovinsi = User::join('provinsis', 'provinsis.id', '=', 'users.Provinsi')
                    ->select(
                        'users.id as ID',
                        'users.name',
                        'users.email',
                        'users.Aktif',
                        'users.id_divisi',
                        'provinsis.*'
                    )
                    ->where('users.KabKota', 0)->get();

                // User Tingkat Kabupaten Kota
                $userkabkota = User::join('kabupatens', 'kabupatens.id', '=', 'users.KabKota')
                    ->select(
                        'users.id as ID',
                        'users.name',
                        'users.email',
                        'users.Aktif',
                        'users.id_divisi',
                        'kabupatens.*'
                    )
                    ->where('users.Provinsi', Auth::user()->Provinsi)
                    // ->where('users.KabKota', Auth::user()->KabKota)
                    ->where('users.Kecamatan', '=', 0)->get();

                // dd($userkabkota);

                // User Tingkat Kecamatan
                $userkecamatan = User::join('kecamatans', 'kecamatans.id', '=', 'users.Kecamatan')
                    ->select(
                        'users.id as ID',
                        'users.name',
                        'users.email',
                        'users.Aktif',
                        'users.id_divisi',
                        'kecamatans.*'
                    )
                    ->where('users.KabKota', Auth::user()->KabKota)->get();

                // dd($userkecamatan);
                $userkabupaten = User::where('KabKota', 0)->get();
                return view('user.user_akses.index', compact(
                    'ajuanuser',
                    'user',
                    'userprovinsi',
                    'userkabupaten',
                    'userkabkota',
                    'userkecamatan'
                ));
            }else{
                return redirect('/profil');
            }
            } else {
                return redirect('/profil');
            }
        } else {
            return redirect('/profil');
        }
    }

    public function show_user_kab($id)
    {

        if (Auth::user()->id_admin == '0' or Auth::user()->id_admin == '1') {
            $provinsi1 = Provinsi::where('provinsis.id', Crypt::decryptString($id))->first();

            $show_user_kab = User::join('kabupatens', 'users.KabKota', '=', 'kabupatens.id')
                ->select(
                    'users.id as ID',
                    'users.Jabatan',
                    'users.email',
                    'users.name',
                    'kabupatens.*'
                )
                ->where('users.Provinsi', Crypt::decryptString($id))
                ->where('users.Kecamatan',0)
                ->get();

            // dd($show_user_kab);
            return view('user.user_akses.show_user_kab', compact('show_user_kab', 'id', 'provinsi1'));
        } else {
            return redirect('/dashboard')->with('error', 'Anda Tidak Memiliki Akses');
        }
    }

    public function show_user_kec($id)
    {

        if (Auth::user()->id_admin == '0' or Auth::user()->id_admin == '1') {
            $kecamatan = Kabupaten::where('kabupatens.id', Crypt::decryptString($id))->first();

            // dd($kecamatan);
            $show_user_kec = User::join('kecamatans', 'users.Kecamatan', '=', 'kecamatans.id')
                ->select(
                    'users.id as ID',
                    'users.Jabatan',
                    'users.email',
                    'users.name',
                    'kecamatans.kecamatan'
                )
                ->where('users.KabKota', Crypt::decryptString($id))
                ->get();
            // dd($show_user_kec);
            return view('user.user_akses.show_user_kec', compact('show_user_kec', 'id', 'kecamatan'));
        } else {
            return redirect('/dashboard')->with('error', 'Anda Tidak Memiliki Akses');
        }
    }

    public function update(Request $request, $id, User $user)
    {
        $user = User::findOrFail(Crypt::decryptString($id));
        $this->validate($request, [
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8',
        ]);
        if ($request->input('password') == "") {
            $user->update([
                'email' => $request->input('email')
            ]);
        } else {
            $user->update([
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password'))
            ]);
        }

        LogAktif::create([
            'username'          => Auth::user()->email,
            'kegiatan'          => 'update_user',
            'provinsi'          => Auth::user()->Provinsi,
            'kabupaten'         => Auth::user()->KabKota,
            'kecamatan'         => Auth::user()->kecamatan,
        ]);
        if ($user) {
            //redirect dengan pesan sukses
            return back()->with('status', ' Berhasil Di Simpan');
        } else {
            //redirect dengan pesan error
            return back()->with('error', ' Gagal Di Update');
        }
    }

    public function create()
    {
        if (Auth::user()->id_admin == '0' or Auth::user()->id_admin == '1') {

            $jabatan = Jabatan::get();
            $provinsi = Provinsi::get();
            $kabupaten_kota = DB::table('kabupatens')->where('provinsi_id', Auth::user()->Provinsi)->get();
            $petugas = Petuga::get();

            $kecamatan = DB::table('kecamatans')->where('kabupaten_id', Auth::user()->KabKota)->get();

            return view('user.user_akses.create', compact('jabatan', 'provinsi', 'petugas', 'kabupaten_kota', 'kecamatan'));
        } else {
            return redirect('/dashboard')->with('error', 'Anda Tidak Memiliki Akses');
        }
    }

    public function getKabupaten(Request $request)
    {
        $kabupaten = Kabupaten::where("provinsi_id", $request->provID)->pluck('id', 'kabupaten');
        return response()->json($kabupaten);
    }
    public function getKecamatan(Request $request)
    {
        $kecamatan = Kecamatan::where("kabupaten_id", $request->kabID)->pluck('id', 'kecamatan');
        return response()->json($kecamatan);
    }
    public function getDesa(Request $request)
    {
        $desa = Kelurahan::where("kecamatan_id", $request->kecID)->pluck('id', 'kelurahan');
        return response()->json($desa);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'       => 'required',
            'email'      => 'required|email|unique:users',
            'password' => ['required', Rules\Password::defaults()],
            'id_divisi'  => 'required',
        ]);

        // $getToken = Http::asForm()->post('https://dev.pencarian.me/auth/realms/superapp/protocol/openid-connect/token', [
        //     'grant_type' => 'client_credentials',
        //     'client_id'  => 'login_superapp',
        //     'client_secret' => 'Q1uyK7fTESPqtaUsVKUtoZzX1MWLy8Vo',
        // ]);
        $ap = User::create([
            'IdLevel'         => Auth::user()->IdLevel,
            'IdAtasan'        => Auth::user()->IdAtasan,
            'name'            => $request->input('name'),
            'Jabatan'         => Auth::user()->Jabatan,
            'email'           => $request->input('email'),
            'password' => Hash::make($request->password),
            'Aktif'           => 1,
            'Provinsi'        => Auth::user()->Provinsi,
            'KabKota'         => Auth::user()->KabKota,
            'Kecamatan'       => Auth::user()->Kecamatan,
            'DesaKel'         => Auth::user()->DesaKel,
            'id_divisi'       => $request->input('id_divisi'),
            'id_admin'        => Auth::user()->id_admin,
        ]);
        // $createUser = Http::withHeaders([
        //     'Authorization' => 'Bearer ' . $getToken['access_token'],
        //     'Accept' => 'application/json',
        // ])->post('https://dev.apigw.pencarian.me/api/v1/backoffice/app/user/register', [
        //     'username' => $request->input('Login'),
        //     'firstName' => $request->input('NamaLengkap'),
        //     'lastName' => '',
        //     'email' => $request->input('Email'),
        //     'password' => $request->input('Sandi')
        // ]);

        LogAktif::create([
            'username'          => Auth::user()->email,
            'kegiatan'          => 'tambah-user-divisi',
            'provinsi'          => Auth::user()->Provinsi,
            'kabupaten'         => Auth::user()->KabKota,
            'kecamatan'         => Auth::user()->kecamatan,
        ]);

        if ($ap) {
            return back()->with('status', ' Berhasil Di Simpan');
        } else {
            return back()->with('status', ' Gagal Di Simpan');
        }
    }

    public function store_user_provinsi(Request $request)
    {
        $this->validate($request, [
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);
        $user_prov = User::create([
            'IdLevel'     => 2,
            'IdAtasan'    => 1,
            'name'        => $request->input('name'),
            'Jabatan'     => 'Ketua atau Anggota Bawaslu Provinsi',
            'email'       => $request->input('email'),
            'password'    => Hash::make($request->input('password')),
            'Aktif'       => 1,
            'Provinsi'    => $request->input('Provinsi'),
            'KabKota'     => 0,
            'id_admin'    => 1
        ]);
        LogAktif::create([
            'username'          => Auth::user()->email,
            'kegiatan'          => 'store_user_provinsi',
            'provinsi'          => Auth::user()->Provinsi,
            'kabupaten'         => Auth::user()->KabKota,
            'kecamatan'         => Auth::user()->kecamatan,
        ]);
        if ($user_prov) {
            return back()->with('status', ' User Provinsi Berhasil Di Simpan');
        } else {
            return back()->with('status', 'User Provinsi Gagal Di Simpan');
        }
    }

    // store kabupaten
    public function store_user_kabupaten(Request $request)
    {
        $this->validate($request, [
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);
        $user_prov = User::create([
            'IdLevel'    => 3,
            'IdAtasan'   => Auth::user()->Provinsi,
            'name'       => $request->input('name'),
            'Jabatan'    => 'Ketua atau Anggota Bawaslu Kabupaten/Kota',
            'email'      => $request->input('email'),
            'password'   => Hash::make($request->input('password')),
            'Aktif'      => 1,
            'Provinsi'   => Auth::user()->Provinsi,
            'KabKota'    => $request->input('KabKota'),
            'id_admin'   => 1
        ]);

        LogAktif::create([
            'username'          => Auth::user()->email,
            'kegiatan'          => 'store_user_kabupaten',
            'provinsi'          => Auth::user()->Provinsi,
            'kabupaten'         => Auth::user()->KabKota,
            'kecamatan'         => Auth::user()->kecamatan,
        ]);
        if ($user_prov) {
            return back()->with('status', ' User Kab/Kota Berhasil Di Simpan');
        } else {
            return back()->with('status', 'User Kab/Kota Gagal Di Simpan');
        }
    }

    // store kecamatan
    public function store_user_kecamatan(Request $request)
    {
        $this->validate($request, [
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);
        $user_prov = User::create([
            'IdLevel'         => Auth::user()->KabKota,
            'IdAtasan'        => 4,
            'name'            => $request->input('name'),
            'Jabatan'         => 'Bawaslu Kecamatan',
            'email'           => $request->input('email'),
            'password'        => Hash::make($request->input('password')),
            'Aktif'           => 1,
            'Provinsi'        => Auth::user()->Provinsi,
            'KabKota'         => Auth::user()->KabKota,
            'Kecamatan'       => $request->input('Kecamatan'),
            'id_admin'        => 1
        ]);
        LogAktif::create([
            'username'          => Auth::user()->email,
            'kegiatan'          => 'store_user_kecamatan',
            'provinsi'          => Auth::user()->Provinsi,
            'kabupaten'         => Auth::user()->KabKota,
            'kecamatan'         => Auth::user()->kecamatan,
        ]);
        if ($user_prov) {
            return back()->with('status', ' User Kecamatan Berhasil Di Simpan');
        } else {
            return back()->with('status', 'User Kecamatan Gagal Di Simpan');
        }
    }

    public function show($id)
    {

        if (Auth::user()->id_admin == '0' or Auth::user()->id_admin == '1') {

            $show_user = User::where('id', Crypt::decryptString($id))->first();

            // dd($show_user);
            return view('user.user_akses.show', compact('show_user', 'id'));
        } else {
            return redirect('/dashboard')->with('error', 'Anda Tidak Memiliki Akses');
        }
    }

    public function destroy($id)
    {
        $benner = User::findOrFail($id);
        $benner->delete();

        LogAktif::create([
            'username'          => Auth::user()->email,
            'kegiatan'          => 'destroy_user',
            'provinsi'          => Auth::user()->Provinsi,
            'kabupaten'         => Auth::user()->KabKota,
            'kecamatan'         => Auth::user()->kecamatan,
        ]);
        return back()->with('status', ' Berhasil Di hapus');
    }
}
