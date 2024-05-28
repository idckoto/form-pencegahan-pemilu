<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use App\Models\Tahapan;
use App\Models\LogAktif;


class TahapanController extends Controller
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
        $tahapan = Tahapan::orderByDesc('id')->get();
        return view('opd.tahapan.index', compact('tahapan'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('opd.tahapan.tambah');
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
            'tahapan' => 'required'
        ]);
        $tahapan = new Tahapan;
        $tahapan->tahapan = $request->tahapan;
        $tahapan->save();

        LogAktif::create([
            'username'          => Auth::user()->email,
            'kegiatan'          => 'tambah-tahapan',
            'provinsi'          => Auth::user()->Provinsi,
            'kabupaten'         => Auth::user()->KabKota,
            'kecamatan'         => Auth::user()->kecamatan,
        ]);
        return redirect('/tahapan')->with('status', ' Ditambah');
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
        $tahapan = Tahapan::where('id', Crypt::decryptString($id))->first();
        return view('opd.tahapan.edit', compact('tahapan', 'id'));
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
        $tahapan = Tahapan::find(Crypt::decryptString($id));
        $tahapan->tahapan = $request->tahapan;
        $tahapan->type = $request->type;
        $tahapan->save();

        LogAktif::create([
            'username'          => Auth::user()->email,
            'kegiatan'          => 'update-tahapan',
            'provinsi'          => Auth::user()->Provinsi,
            'kabupaten'         => Auth::user()->KabKota,
            'kecamatan'         => Auth::user()->kecamatan,
        ]);
        return redirect('/tahapan')->with('status', ' Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $cek = Tahapan::destroy($request->id);

        LogAktif::create([
            'username'          => Auth::user()->email,
            'kegiatan'          => 'destroy-Tahapan',
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
