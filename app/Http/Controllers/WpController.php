<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Tkp;
use App\Models\Twp;
use App\Models\LogAktif;

class WpController extends Controller
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
        $wp = Twp::get();

        //dd($wp);
        return view('opd.wp.index', compact('wp'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /*if(isset(Auth::user()->Kecamatan)){
            $provinsi = Provinsi::where('id', Auth::user()->Provinsi)->first();
            $kabupaten = Kabupaten::where('id', Auth::user()->KabKota)->first();
            $kecamatan = Kecamatan::where('id', Auth::user()->Kecamatan)->first();
            $twp = Twp::where('kdpro', Auth::user()->Provinsi)
                        ->orWhere('kp_id',1)
                        ->get();
        }elseif(isset(Auth::user()->KabKota)){
            $provinsi = Provinsi::where('id', Auth::user()->Provinsi)->first();
            $kabupaten = Kabupaten::where('id', Auth::user()->KabKota)->first();
            $kecamatan = null;
            $twp = Twp::where('kdpro', Auth::user()->Provinsi)
                        ->orWhere('kp_id',1)
                        ->get();
        }elseif(isset(Auth::user()->Provinsi)){
            $provinsi = Provinsi::where('id', Auth::user()->Provinsi)->first();
            $kabupaten = null;
            $kecamatan = null;
            $twp = Twp::where('kdpro', Auth::user()->Provinsi)
                        ->orWhere('kp_id',1)
                        ->get();
        }else{
            $provinsi = null;
            $kabupaten = null;
            $kecamatan = null;
            $twp = null;
        }*/

        $propinsi = Provinsi::all();
        $kabupaten = Kabupaten::all();
        $kecamatan = null;
        $tkp = Tkp::get();
        return view('opd.wp.tambah', compact('tkp','propinsi','kabupaten','kecamatan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //echo "<pre>";
        //print_r($request); die();

        $request->validate([
            'nama_wp' => 'required',
            'kp_id' => 'required',
            'kdpro' => 'required'
        ]);
        $wp = Twp::where('nama_wp', $request->nama_wp)->first();
        if (!$wp) {
            $wp = new Twp;
        }
        $wp->nama_wp = $request->nama_wp;
        $wp->kp_id = $request->kp_id;
        $wp->kdpro = $request->kdpro;

        if($request->kdkab===null){
            $wp->kabkot = $request->kdpro.'00';
        } else {
            $wp->kabkot = $request->kdkab;
        }
        
        $wp->save();

        LogAktif::create([
            'username'          => Auth::user()->email,
            'kegiatan'          => 'tambah-wp',
            'provinsi'          => Auth::user()->Provinsi,
            'kabupaten'         => Auth::user()->KabKota,
            'kecamatan'         => Auth::user()->kecamatan,
        ]);

        return redirect('/wp')->with('status', ' Ditambah');
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
        $wp = Twp::where('id', Crypt::decryptString($id))->first();
        return view('opd.wp.edit', compact('wp', 'id'));
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
            'nama_wp' => 'required',
            'kp_id' => 'required',
            'kdpro' => 'required'
        ]);
        $wp = Twp::find(Crypt::decryptString($id));
        $wp->nama_wp = $request->nama_wp;
        $wp->ket = $request->ket;
        $wp->save();

        LogAktif::create([
            'username'          => Auth::user()->email,
            'kegiatan'          => 'update-wp',
            'provinsi'          => Auth::user()->Provinsi,
            'kabupaten'         => Auth::user()->KabKota,
            'kecamatan'         => Auth::user()->kecamatan,
        ]);
        return redirect('/wp')->with('status', ' Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $cek = Twp::destroy($request->id);

        LogAktif::create([
            'username'          => Auth::user()->email,
            'kegiatan'          => 'destroy-wp',
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


    public function provinsi(Request $request)
    {
        $provinces = [];

        if ($request->has('q')) {
            $search = $request->q;
            $provinces = Provinsi::select("id", "provinsi","sni")
                ->Where('provinsi', 'LIKE', "%$search%")
                ->get();
        } else {
            $provinces = Provinsi::limit(50)->get();
        }
        return response()->json($provinces);
    }

    public function kabupaten(Request $request)
    {
        $regencies = [];
        $provinceID = explode("-",$request->provinceID);
        if ($request->has('q')) {
            $search = $request->q;
            $regencies = Kabupaten::select("id", "kabupaten")
                ->where('provinsi_id', $provinceID[0])
                ->Where('kabupaten', 'LIKE', "%$search%")
                ->get();
        } else {
            $regencies = Kabupaten::where('provinsi_id', $provinceID[0])->limit(100)->get();
        }
        return response()->json($regencies);
    }

    public function kecamatan(Request $request)
    {
        $districts = [];

        $regencyID = $request->regencyID;
        if ($request->has('q')) {
            $search = $request->q;
            $districts = Kecamatan::select("id", "kecamatan")
                ->where('kabupaten_id', $regencyID)
                ->Where('kecamatan', 'LIKE', "%$search%")
                ->get();
        } else {
            $districts = Kecamatan::where('kabupaten_id', $regencyID)->limit(100)->get();
        }
        return response()->json($districts);
    }

    public function kelurahan(Request $request)
    {
        $villages = [];
        $districtID = $request->districtID;
        if ($request->has('q')) {
            $search = $request->q;
            $villages = Kelurahan::select("id", "kelurahan")
                ->where('kecamatan_id', $districtID)
                ->Where('kelurahan', 'LIKE', "%$search%")
                ->get();
        } else {
            $villages = Kelurahan::where('kecamatan_id', $districtID)->limit(100)->get();
        }
        return response()->json($villages);
    }
}
