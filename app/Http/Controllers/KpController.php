<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use App\Models\Tkp;
use App\Models\LogAktif;

class KpController extends Controller
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
        $kp = Tkp::get();
        return view('opd.kp.index', compact('kp'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('opd.kp.tambah');
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
            'nama_kp' => 'required',
            //'ket' => 'required',
        ]);
        $kp = Tkp::where('nama_kp', $request->nama_kp)->first();
        if (!$kp) {
            $kp = new Tkp;
        }
        $kp->nama_kp = $request->nama_kp;
        //$kp->ket = $request->ket;
        $kp->save();

        LogAktif::create([
            'username'          => Auth::user()->email,
            'kegiatan'          => 'tambah-kp',
            'provinsi'          => Auth::user()->Provinsi,
            'kabupaten'         => Auth::user()->KabKota,
            'kecamatan'         => Auth::user()->kecamatan,
        ]);

        return redirect('/kp')->with('status', ' Ditambah');
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
        $kp = Tkp::where('id', Crypt::decryptString($id))->first();
        return view('opd.kp.edit', compact('kp', 'id'));
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
            'nama_kp' => 'required',
            //'ket' => 'required',
        ]);
        $kp = Tkp::find(Crypt::decryptString($id));
        $kp->nama_kp = $request->nama_kp;
        //$kp->ket = $request->ket;
        $kp->save();

        LogAktif::create([
            'username'          => Auth::user()->email,
            'kegiatan'          => 'update-kp',
            'provinsi'          => Auth::user()->Provinsi,
            'kabupaten'         => Auth::user()->KabKota,
            'kecamatan'         => Auth::user()->kecamatan,
        ]);
        return redirect('/kp')->with('status', ' Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $cek = Tkp::destroy($request->id);

        LogAktif::create([
            'username'          => Auth::user()->email,
            'kegiatan'          => 'destroy-kp',
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
