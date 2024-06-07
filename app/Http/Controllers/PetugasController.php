<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use App\Models\Petuga;
use App\Models\LogAktif;

class PetugasController extends Controller
{

    public function __construct()
    {
        if (!$this->middleware('auth:sanctum')) {
            return redirect('/signin');
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $petugas = Petuga::get();
        return view('opd.petugas.index', compact('petugas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('opd.petugas.tambah');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'kd_petugas' => 'required',
            'ket' => 'required',
        ]);
        $petugas = Petuga::where('kd_petugas', $request->kd_petugas)->first();
        if (!$petugas) {
            $petugas = new Petuga;
        }
        $petugas->kd_petugas = $request->kd_petugas;
        $petugas->ket = $request->ket;
        $petugas->save();

        LogAktif::create([
            'username'          => Auth::user()->email,
            'kegiatan'          => 'tambah-petugas',
            'provinsi'          => Auth::user()->Provinsi,
            'kabupaten'         => Auth::user()->KabKota,
            'kecamatan'         => Auth::user()->kecamatan,
        ]);

        return redirect('/petugas')->with('status', ' Ditambah');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $petugas = Petuga::where('id', Crypt::decryptString($id))->first();
        return view('opd.petugas.edit', compact('petugas', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'kd_petugas' => 'required',
            'ket' => 'required',
        ]);
        $petugas = Petuga::find(Crypt::decryptString($id));
        $petugas->kd_petugas = $request->kd_petugas;
        $petugas->ket = $request->ket;
        $petugas->save();

        LogAktif::create([
            'username'          => Auth::user()->email,
            'kegiatan'          => 'update-petugas',
            'provinsi'          => Auth::user()->Provinsi,
            'kabupaten'         => Auth::user()->KabKota,
            'kecamatan'         => Auth::user()->kecamatan,
        ]);
        return redirect('/petugas')->with('status', ' Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $cek = Petuga::destroy($request->id);

        LogAktif::create([
            'username'          => Auth::user()->email,
            'kegiatan'          => 'destroy-petugas',
            'provinsi'          => Auth::user()->Provinsi,
            'kabupaten'         => Auth::user()->KabKota,
            'kecamatan'         => Auth::user()->kecamatan,
        ]);

        if ($cek) {
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error'
            ]);
        }
    }
}
