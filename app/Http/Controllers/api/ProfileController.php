<?php

namespace App\Http\Controllers\api;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
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

class ProfileController extends Controller
{

    public function index()
    {
        if (Auth::user()->id_admin == '0' or Auth::user()->id_admin == '1') {

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
                ->where('users.Kecamatan', '=', 0)->get();

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

            $userkabupaten = User::where('KabKota', 0)->get();

            $responseData = [
                'ajuanuser' => $ajuanuser,
                'user' => $user,
                'userprovinsi' => $userprovinsi,
                'userkabupaten' => $userkabupaten,
                'userkabkota' => $userkabkota,
                'userkecamatan' => $userkecamatan
            ];

            return response()->json($responseData);
        } else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }

    public function showUserKab($id)
    {
        $user = Auth::user();

        if ($user->id_admin == '0' || $user->id_admin == '1') {
            $provinsi1 = Provinsi::findOrFail($id);

            $show_user_kab = User::join('kabupatens', 'users.KabKota', '=', 'kabupatens.id')
                ->select(
                    'users.id as ID',
                    'users.Jabatan',
                    'users.email',
                    'users.name',
                    'kabupatens.*'
                )
                ->where('users.Provinsi', $id)
                ->get();

            $responseData = [
                'show_user_kab' => $show_user_kab,
                'id' => $id,
                'provinsi1' => $provinsi1
            ];

            return response()->json($responseData);
        } else {
            return response()->json(['message' => 'Anda Tidak Memiliki Akses'], 403);
        }
    }

    public function showUserKec($id)
    {
        $user = Auth::user();

        if ($user->id_admin == '0' || $user->id_admin == '1') {
            $kecamatan = Kabupaten::findOrFail($id);

            $show_user_kec = User::join('kecamatans', 'users.Kecamatan', '=', 'kecamatans.id')
                ->select(
                    'users.id as ID',
                    'users.Jabatan',
                    'users.email',
                    'users.name',
                    'kecamatans.kecamatan'
                )
                ->where('users.KabKota', $id)
                ->get();

            $responseData = [
                'show_user_kec' => $show_user_kec,
                'id' => $id,
                'kecamatan' => $kecamatan
            ];

            return response()->json($responseData);
        } else {
            return response()->json(['message' => 'Anda Tidak Memiliki Akses'], 403);
        }
    }
}
