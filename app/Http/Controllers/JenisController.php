<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use App\Models\LogAktif;
use App\Models\Jenis;

class JenisController extends Controller
{
    public function __construct()
    {
        if (!$this->middleware('auth:sanctum')) {
            return redirect('/login');
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jenis = Jenis::orderByDesc('id')->get();
        return view('opd.jenis.index', compact('jenis'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('opd.jenis.tambah');
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
            'jenis' => 'required',
        ]);
        $jenis = new Jenis;
        $jenis->jenis = $request->jenis;
        $jenis->save();

        LogAktif::create([
            'username'          => Auth::user()->email,
            'kegiatan'          => 'tambah-jenis',
            'provinsi'          => Auth::user()->Provinsi,
            'kabupaten'         => Auth::user()->KabKota,
            'kecamatan'         => Auth::user()->kecamatan,
        ]);
        return redirect('/jenis')->with('status', ' Ditambah');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $jenis = Jenis::where('id', Crypt::decryptString($id))->first();
        return view('opd.jenis.edit', compact('jenis', 'id'));
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
        $jenis = Jenis::find(Crypt::decryptString($id));
        $jenis->jenis = $request->jenis;
        $jenis->save();

        LogAktif::create([
            'username'          => Auth::user()->email,
            'kegiatan'          => 'update-jenis',
            'provinsi'          => Auth::user()->Provinsi,
            'kabupaten'         => Auth::user()->KabKota,
            'kecamatan'         => Auth::user()->kecamatan,
        ]);
        return redirect('/jenis')->with('status', ' Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $cek = Jenis::destroy($request->id);

        LogAktif::create([
            'username'          => Auth::user()->email,
            'kegiatan'          => 'destroy-jenis',
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
