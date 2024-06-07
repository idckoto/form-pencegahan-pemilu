<?php

namespace App\Http\Controllers;

use App\Models\Formcegah;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use App\Models\LogAktif;
use App\Models\Bentuk;

class BentukController extends Controller
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
        $bentuk = Bentuk::orderByDesc('id')->get();
        return view('opd.bentuk.index', compact('bentuk'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('opd.bentuk.tambah');
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
            'bentuk' => 'required',
            'type' => 'required',
        ]);
        $bentuk = new Bentuk;
        $bentuk->bentuk = $request->bentuk;
        $bentuk->type = $request->type;
        $bentuk->save();

        LogAktif::create([
            'username'          => Auth::user()->email,
            'kegiatan'          => 'tambah-bentuk',
            'provinsi'          => Auth::user()->Provinsi,
            'kabupaten'         => Auth::user()->KabKota,
            'kecamatan'         => Auth::user()->kecamatan,
        ]);

        return redirect('/bentuk')->with('status', ' Ditambah');
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
        $bentuk = Bentuk::where('id', Crypt::decryptString($id))->first();
        return view('opd.bentuk.edit', compact('bentuk', 'id'));
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
            'bentuk' => 'required',
            'type' => 'required',
        ]);
        $bentuk = Bentuk::find(Crypt::decryptString($id));
        $bentuk->bentuk = $request->bentuk;
        $bentuk->type = $request->type;
        $bentuk->save();

        LogAktif::create([
            'username'          => Auth::user()->email,
            'kegiatan'          => 'update-bentuk',
            'provinsi'          => Auth::user()->Provinsi,
            'kabupaten'         => Auth::user()->KabKota,
            'kecamatan'         => Auth::user()->kecamatan,


        ]);

        return redirect('/bentuk')->with('status', ' Diupdate');
    }
    public function detail()
    {
        $bentuk = Bentuk::get();
        $total = Formcegah::groupBy('bentuk')->select('bentuk', DB::raw('count(*) as total'))->get();
        $total_provinsi = Formcegah::groupBy('bentuk')->select('bentuk', DB::raw('COUNT(DISTINCT(id_provinsi)) as total'))->get();
        foreach ($bentuk as $b) {
            foreach ($total as $t) {
                if ($b->id == $t->bentuk) {
                    $b->total = $t->total;
                }
            }
            foreach ($total_provinsi as $tv) {
                if ($b->id == $tv->bentuk) {
                    $b->total_provinsi = $tv->total;
                }
            }
        }
        return view('opd.bentuk.detail', compact('bentuk', 'total_provinsi'));
    }

    public function ajaxBentukProv(Request $request)
    {
        $totalBentukProv = Formcegah::select('formcegahs.bentuk', 'formcegahs.id_provinsi', DB::raw('count(*) as total'), DB::raw('count(distinct(formcegahs.id_kabupaten)) as totalKab'), 'provinsis.provinsi')
            ->leftJoin('provinsis', 'formcegahs.id_provinsi', 'provinsis.id')
            ->where('formcegahs.bentuk', $request->get('id'))
            ->groupBy('formcegahs.bentuk', 'formcegahs.id_provinsi')
            ->get();
        return response()->json([
            'status' => 'success',
            'data' => $totalBentukProv
        ]);
    }

    public function ajaxBentukKab(Request $request)
    {
        $totalBentukKab = Formcegah::select('formcegahs.bentuk', 'formcegahs.id_provinsi', 'provinsis.provinsi', 'formcegahs.id_kabupaten', 'kabupatens.kabupaten', DB::raw('count(*) as total'), DB::raw('count(distinct(formcegahs.id_kecamatan)) as totalKec'))
            ->leftJoin('provinsis', 'formcegahs.id_provinsi', 'provinsis.id')
            ->leftJoin('kabupatens', 'formcegahs.id_kabupaten', 'kabupatens.id')
            ->where('formcegahs.bentuk', $request->get('id'))
            ->where('formcegahs.id_provinsi', $request->get('id_provinsi'))
            ->groupBy('formcegahs.bentuk', 'formcegahs.id_provinsi', 'formcegahs.id_kabupaten')
            ->get();
        return response()->json([
            'status' => 'success',
            'data' => $totalBentukKab
        ]);
    }

    public function ajaxBentukKec(Request $request)
    {
        $totalBentukKec = Formcegah::select('formcegahs.bentuk', 'formcegahs.id_provinsi', 'provinsis.provinsi', 'formcegahs.id_kabupaten', 'kabupatens.kabupaten', 'formcegahs.id_kecamatan', 'kecamatans.kecamatan', DB::raw('count(*) as total'), DB::raw('count(distinct(formcegahs.id_kelurahan)) as totalKel'))
            ->leftJoin('provinsis', 'formcegahs.id_provinsi', 'provinsis.id')
            ->leftJoin('kabupatens', 'formcegahs.id_kabupaten', 'kabupatens.id')
            ->leftJoin('kecamatans', 'formcegahs.id_kecamatan', 'kecamatans.id')
            ->where('formcegahs.bentuk', $request->get('id'))
            ->where('formcegahs.id_provinsi', $request->get('id_provinsi'))
            ->where('formcegahs.id_kabupaten', $request->get('id_kabupaten'))
            ->groupBy('formcegahs.bentuk', 'formcegahs.id_provinsi', 'formcegahs.id_kabupaten', 'formcegahs.id_kecamatan')
            ->get();
        return response()->json([
            'status' => 'success',
            'data' => $totalBentukKec
        ]);
    }

    public function ajaxBentukKel(Request $request)
    {
        $totalBentukKec = Formcegah::select('formcegahs.bentuk', 'formcegahs.id_provinsi', 'provinsis.provinsi', 'formcegahs.id_kabupaten', 'kabupatens.kabupaten', 'formcegahs.id_kecamatan', 'kecamatans.kecamatan', 'formcegahs.id_kelurahan', 'kelurahans.kelurahan', DB::raw('count(*) as total'))
            ->leftJoin('provinsis', 'formcegahs.id_provinsi', 'provinsis.id')
            ->leftJoin('kabupatens', 'formcegahs.id_kabupaten', 'kabupatens.id')
            ->leftJoin('kecamatans', 'formcegahs.id_kecamatan', 'kecamatans.id')
            ->leftJoin('kelurahans', 'formcegahs.id_kelurahan', 'kelurahans.id')
            ->where('formcegahs.bentuk', $request->get('id'))
            ->where('formcegahs.id_provinsi', $request->get('id_provinsi'))
            ->where('formcegahs.id_kabupaten', $request->get('id_kabupaten'))
            ->where('formcegahs.id_kecamatan', $request->get('id_kecamatan'))
            ->groupBy('formcegahs.bentuk', 'formcegahs.id_provinsi', 'formcegahs.id_kabupaten', 'formcegahs.id_kecamatan', 'formcegahs.id_kelurahan')
            ->get();
        return response()->json([
            'status' => 'success',
            'data' => $totalBentukKec
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $cek = Bentuk::destroy($request->id);

        LogAktif::create([
            'username'          => Auth::user()->email,
            'kegiatan'          => 'destroy-bentuk',
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
