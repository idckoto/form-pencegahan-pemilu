<?php

namespace App\Http\Controllers;

use App\Models\Formcegah;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\User;
use App\Models\Petuga;
use App\Models\Provinsi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class GraphController extends Controller
{
    public function __construct()
    {
        // $this->middleware(function ($request, $next) {
        //     if($this->refreshToken(Auth::user()->Login)){
        //         return $next($request);
        //     }else{
        //         return redirect('/logout');   
        //     }
        // });
        if (!$this->middleware('auth:sanctum')) {
            return redirect('/signin');
        }
    }

    public function indra(Request $request)
    {
        $idUser = '1121';
        $userProv = 'non pusat';

        // dd($idUser);

        $dropdowns = array();
        $dropdowns['divisi'] = Petuga::select('kd_petugas')
            ->get();
            
        $dropdowns['bentuk'] = Formcegah::select('bentuks.bentuk', 'formcegahs.bentuk as id_bentuk')
            ->LeftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
            ->orderBy('bentuks.bentuk', 'asc')
            ->groupBy('bentuks.bentuk','formcegahs.bentuk')
            ->get();
            
        $dropdowns['jenis'] = Formcegah::select('jenis.jenis', 'formcegahs.jenis as id_jenis')
            ->LeftJoin('jenis', 'formcegahs.jenis', 'jenis.id')
            ->orderBy('jenis.jenis', 'asc')
            ->groupBy('jenis.jenis','formcegahs.jenis')
            ->get();

        $user = User::where('id', Auth::user()->id)->first();
        $jabatan = $user->Jabatan;  
        
        if ($request->date_finish == "") {
            $now = Carbon::now();
            //$date_start = $now->firstOfMonth()->format('Y-m-d');
            $date_finish = $now->endOfMonth()->format('Y-m-d');
            $date_start = '2024-01-01';
            //$date_finish = '2024-03-31';

        } else {
            $date_start = $request->date_start;
            $date_finish = $request->date_finish;
        }

        //   dd($user,$jabatan,$date_start,$date_finish);
        if ($jabatan == 'Sekretariat Bawaslu Provinsi') {
            //dd('hai sekretariat');
            $userProv = 'non pusat';
            $title = ' Seluruh Provinsi';
        } else if ($jabatan == 'Ketua atau Anggota Bawaslu Provinsi') {
            if ($user->Provinsi != null) {
                //dd('provinsi');
                $provinsi = Provinsi::where('id', $user->Provinsi)->first();
                $title = ' Kabupaten/Kota di Seluruh Provinsi ' . $provinsi->provinsi;
            } else {
                $provinsi = Provinsi::where('id', $user->Provinsi)->first();
                $title = ' Seluruh Provinsi';
            }

        } else if ($jabatan == 'Ketua atau Anggota Bawaslu Kabupaten/Kota') {
            $userProv = 'non pusat';
            $KabKota = Kabupaten::where('id', $user->KabKota)->first();
            $title = ' Kecamatan di Seluruh ' . $KabKota->kabupaten;
        } else if ($jabatan == 'Bawaslu Kecamatan') {
            $kecamatan = Kecamatan::where('id', $user->Kecamatan)->first();
            $title = ' Kelurahan di Seluruh Kecamatan ' . $kecamatan->kecamatan;
        } else {
            $categories = [];

            $categories_RI = [];
            $count_RI = [];

            $categories_jenis = [];
            $count_jenis = [];

            $identifikasi_kerawananCount = [];
            $identifikasi_kerawananSum = [];

            $pendidikanCount = [];
            $pendidikanSum = [];

            $partisipasiCount = [];
            $partisipasiSum = [];

            $kerjasamaCount = [];
            $kerjasamaSum = [];

            $imbauanCount = [];
            $imbauanSum = [];

            $kegiatanlainCount = [];
            $kegiatanlainSum = [];

            $publikasiCount = [];
            $publikasiSum = [];

            $rekapCegah = [];
			
			$naskahdinasCount = [];
			$naskahdinasSum = [];
        }

        return view('graph.indra', compact(
            'date_start',
            'date_finish',
            'dropdowns',
            'jabatan',
            'title',
            'userProv',
            //'dataTahap',
            //'dataBentuk'

            //'identifikasi_kerawananCount',
        ));


    }    

    public function index(Request $request)
    {
        return redirect()->action([GraphController::class, 'recap']);

        $idUser = '1121';
        $userProv = 'non pusat';

        // dd($idUser);

        $dropdowns = array();
        $dropdowns['divisi'] = Petuga::select('kd_petugas')
            ->get();

        $dropdowns['bentuk'] = Formcegah::select('bentuks.bentuk', 'formcegahs.bentuk as id_bentuk')
            ->LeftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
            ->orderBy('bentuks.bentuk', 'asc')
            ->groupBy('bentuks.bentuk','formcegahs.bentuk')
            ->get();

        $dropdowns['jenis'] = Formcegah::select('jenis.jenis', 'formcegahs.jenis as id_jenis')
            ->LeftJoin('jenis', 'formcegahs.jenis', 'jenis.id')
            ->orderBy('jenis.jenis', 'asc')
            ->groupBy('jenis.jenis','formcegahs.jenis')
            ->get();

        $user = User::where('id', Auth::user()->id)->first();
        $jabatan = $user->Jabatan;

        if ($request->date_finish == "") {
            $now = Carbon::now();
            $date_start =  $now->firstOfMonth()->format('Y-m-d');
            $date_finish = $now->endOfMonth()->format('Y-m-d');

            // $date_start = '2023-11-17';
            // $date_finish = '2023-11-17';

        // Mengatur tanggal mulai sebagai Senin minggu ini
        // $date_start = $now->modify('this week')->format('Y-m-d');

        // Menambahkan 3 hari ke tanggal mulai untuk mendapatkan tanggal akhir
        // $date_finish = $now->modify('+1 days')->format('Y-m-d');



        } else {
            $date_start = $request->date_start;
            $date_finish = $request->date_finish;
        }

        //   dd($user,$jabatan,$date_start,$date_finish);

        if ($jabatan == 'Sekretariat Bawaslu Provinsi') {
            //dd('hai');
            $userProv = 'non pusat';
            $title = ' Seluruh Provinsi';
            $qcategories = Formcegah::select('provinsis.provinsi as provinsi')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->where('formcegahs.id_provinsi', '<>', '')
                ->leftJoin('provinsis', 'formcegahs.id_provinsi', 'provinsis.id');

                // dd($qcategories);

            if ($request->divisi != "") {
                $qcategories = $qcategories->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qcategories = $qcategories->where('bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qcategories = $qcategories->where('jenis', $request->jenis);
            }

            if ($request->pilih_wilayah == "provinsi") {
                $qcategories = $qcategories->where('formcegahs.id_provinsi', $request->wilayah_dropdown);
            }

            if ($request->pilih_wilayah == "kota") {
                $qcategories = $qcategories->where('formcegahs.id_kabupaten', $request->wilayah_dropdown);
            }

            $categories = $qcategories->groupBy('provinsis.provinsi')->pluck('provinsi');

            $q_categories_RI = Formcegah::select('bentuks.bentuk as bentuk')
                ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                // ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), ['2023-01-01','2023-12-01'])
                ->where('formcegahs.id_provinsi', '');

            $categories_RI = $q_categories_RI->groupBy('bentuks.bentuk')->get()->pluck('bentuk');

            $q_RI = Formcegah::select('bentuks.bentuk',DB::raw('COUNT(*) as count'))
                ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                //->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), ['2023-01-01','2023-12-01'])
                ->where('formcegahs.id_provinsi', '');

            $count_RI = $q_RI->groupBy('bentuks.bentuk')->get()->pluck('count');

            $q_categories_jenis = Formcegah::select('jenis.jenis as jenis')
                ->leftJoin('jenis', 'formcegahs.jenis', 'jenis.id')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                //->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), ['2023-01-01','2023-12-01'])
                ->where('formcegahs.jenis','<>', '');

            $categories_jenis = $q_categories_jenis->groupBy('jenis.jenis')->get()->pluck('jenis');

            $q_jenis = Formcegah::select('jenis.jenis as jenis',DB::raw('COUNT(*) as count'))
                ->leftJoin('jenis', 'formcegahs.jenis', 'jenis.id')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                //->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), ['2023-01-01','2023-12-01'])
                ->where('formcegahs.jenis','<>', '');

            $count_jenis = $q_jenis->groupBy('jenis.jenis')->get()->pluck('count');

            $qtahapan_pie = Formcegah::select(
                'tahap',
                DB::raw('
                SUM(CASE
                WHEN tahap="Tahapan" THEN 1
                WHEN tahap="Non Tahapan" THEN 1
                ELSE 0
                END) AS count
                ')
            )
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->where('formcegahs.id_provinsi', '<>', '');

                // dd( $qtahapan_pie);

            if ($request->divisi != "") {
                $qtahapan_pie = $qtahapan_pie->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qtahapan_pie = $qtahapan_pie->where('bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qtahapan_pie = $qtahapan_pie->where('jenis', $request->jenis);
            }

            if ($request->pilih_wilayah == "provinsi") {
                $qtahapan_pie = $qtahapan_pie->where('formcegahs.id_provinsi', $request->wilayah_dropdown);
            }

            if ($request->pilih_wilayah == "kota") {
                $qtahapan_pie = $qtahapan_pie->where('formcegahs.id_kabupaten', $request->wilayah_dropdown);
            }

            $tahapan_pie = $qtahapan_pie->groupBy('tahap')->get();

            $dataTahap = [];
            foreach ($tahapan_pie as $data) {
                $dataTahap[] = [
                    $data['tahap'],
                    $data['count']
                ];
            }

            $qbentuk_pie = Formcegah::select(
                'bentuks.bentuk as bentuk',
                DB::raw('
                SUM(CASE
                WHEN formcegahs.bentuk="0" THEN 1
                WHEN formcegahs.bentuk="1" THEN 1
                WHEN formcegahs.bentuk="2" THEN 1
                WHEN formcegahs.bentuk="3" THEN 1
                WHEN formcegahs.bentuk="4" THEN 1
                WHEN formcegahs.bentuk="5" THEN 1
                WHEN formcegahs.bentuk="6" THEN 1
                ELSE 0
                END) AS count
                ')
            )
                ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->where('formcegahs.id_provinsi', '<>', '');

            if ($request->divisi != "") {
                $qbentuk_pie = $qbentuk_pie->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qbentuk_pie = $qbentuk_pie->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qbentuk_pie = $qbentuk_pie->where('jenis', $request->jenis);
            }

            if ($request->pilih_wilayah == "provinsi") {
                $qbentuk_pie = $qbentuk_pie->where('formcegahs.id_provinsi', $request->wilayah_dropdown);
            }

            if ($request->pilih_wilayah == "kota") {
                $qbentuk_pie = $qbentuk_pie->where('formcegahs.id_kabupaten', $request->wilayah_dropdown);
            }

            $bentuk_pie = $qbentuk_pie->groupBy('bentuks.bentuk')->get();

            $dataBentuk = [];
            foreach ($bentuk_pie as $data) {
                $dataBentuk[] = [
                    $data['bentuk'],
                    $data['count']
                ];
            }

            //dd(json_encode($dataBentuk));

            //$qnaskahdinasCount = [];
            //$naskahdinasSum = [];
            //dd($qnaskahdinasCount);
				
            $qnaskahdinasCount = Formcegah::select(
                'provinsis.provinsi as provinsi',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Naskah Dinas" THEN 1
                ELSE 0
                END) AS count
                ')
            )
                ->leftJoin('provinsis', 'formcegahs.id_provinsi', 'provinsis.id')
                ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->where('formcegahs.id_provinsi', '<>', '');

            if ($request->divisi != "") {
                $qnaskahdinasCount = $qnaskahdinasCount->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qnaskahdinasCount = $qnaskahdinasCount->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qnaskahdinasCount = $qnaskahdinasCount->where('jenis', $request->jenis);
            }

            if ($request->pilih_wilayah == "provinsi") {
                $qnaskahdinasCount = $qnaskahdinasCount->where('formcegahs.id_provinsi', $request->wilayah_dropdown);
            }

            if ($request->pilih_wilayah == "kota") {
                $qnaskahdinasCount = $qnaskahdinasCount->where('formcegahs.id_kabupaten', $request->wilayah_dropdown);
            }

            $naskahdinasCount = $qnaskahdinasCount->groupBy('provinsis.provinsi')->pluck('count');

            //dd($naskahdinasCount);

            $qnaskahdinassum = Formcegah::where('bentuk', '4')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->where('formcegahs.id_provinsi', '<>', '');

            if ($request->divisi != "") {
                $qnaskahdinassum = $qnaskahdinassum->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qnaskahdinassum = $qnaskahdinassum->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qnaskahdinassum = $qnaskahdinassum->where('jenis', $request->jenis);
            }

            if ($request->pilih_wilayah == "provinsi") {
                $qnaskahdinassum = $qnaskahdinassum->where('formcegahs.id_provinsi', $request->wilayah_dropdown);
            }

            if ($request->pilih_wilayah == "kota") {
                $qnaskahdinassum = $qnaskahdinassum->where('formcegahs.id_kabupaten', $request->wilayah_dropdown);
            }

            $naskahdinasSum = $qnaskahdinassum->count();
            //dd($naskahdinasSum);

            $qidentifikasi_kerawananCount = Formcegah::select(
                'provinsis.provinsi as provinsi',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Identifikasi Kerawanan" THEN 1
                ELSE 0
                END) AS count
                ')
            )
                ->leftJoin('provinsis', 'formcegahs.id_provinsi', 'provinsis.id')
                ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->where('formcegahs.id_provinsi', '<>', '');

            if ($request->divisi != "") {
                $qidentifikasi_kerawananCount = $qidentifikasi_kerawananCount->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qidentifikasi_kerawananCount = $qidentifikasi_kerawananCount->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qidentifikasi_kerawananCount = $qidentifikasi_kerawananCount->where('jenis', $request->jenis);
            }

            if ($request->pilih_wilayah == "provinsi") {
                $qidentifikasi_kerawananCount = $qidentifikasi_kerawananCount->where('formcegahs.id_provinsi', $request->wilayah_dropdown);
            }

            if ($request->pilih_wilayah == "kota") {
                $qidentifikasi_kerawananCount = $qidentifikasi_kerawananCount->where('formcegahs.id_kabupaten', $request->wilayah_dropdown);
            }

            $identifikasi_kerawananCount = $qidentifikasi_kerawananCount->groupBy('provinsis.provinsi')->pluck('count');

            $qidentifikasi_kerawananSum = Formcegah::where('bentuk', '6')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->where('formcegahs.id_provinsi', '<>', '');

            if ($request->divisi != "") {
                $qidentifikasi_kerawananSum = $qidentifikasi_kerawananSum->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qidentifikasi_kerawananSum = $qidentifikasi_kerawananSum->where('bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qidentifikasi_kerawananSum = $qidentifikasi_kerawananSum->where('jenis', $request->jenis);
            }

            if ($request->pilih_wilayah == "provinsi") {
                $qidentifikasi_kerawananSum = $qidentifikasi_kerawananSum->where('formcegahs.id_provinsi', $request->wilayah_dropdown);
            }

            if ($request->pilih_wilayah == "kota") {
                $qidentifikasi_kerawananSum = $qidentifikasi_kerawananSum->where('formcegahs.id_kabupaten', $request->wilayah_dropdown);
            }

            $identifikasi_kerawananSum = $qidentifikasi_kerawananSum->count();

            $qpendidikanCount = Formcegah::select(
                'provinsis.provinsi as provinsi',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Pendidikan" THEN 1
                ELSE 0
                END) AS count
                ')
            )
                ->leftJoin('provinsis', 'formcegahs.id_provinsi', 'provinsis.id')
                ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->where('formcegahs.id_provinsi', '<>', '');

            if ($request->divisi != "") {
                $qpendidikanCount = $qpendidikanCount->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qpendidikanCount = $qpendidikanCount->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qpendidikanCount = $qpendidikanCount->where('jenis', $request->jenis);
            }

            if ($request->pilih_wilayah == "provinsi") {
                $qpendidikanCount = $qpendidikanCount->where('formcegahs.id_provinsi', $request->wilayah_dropdown);
            }

            if ($request->pilih_wilayah == "kota") {
                $qpendidikanCount = $qpendidikanCount->where('formcegahs.id_kabupaten', $request->wilayah_dropdown);
            }

            $pendidikanCount = $qpendidikanCount->groupBy('provinsis.provinsi')->pluck('count');

            $qpendidikanSum = Formcegah::where('bentuk', '1')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->where('formcegahs.id_provinsi', '<>', '');

            if ($request->divisi != "") {
                $qpendidikanSum = $qpendidikanSum->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qpendidikanSum = $qpendidikanSum->where('bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qpendidikanSum = $qpendidikanSum->where('jenis', $request->jenis);
            }

            if ($request->pilih_wilayah == "provinsi") {
                $qpendidikanSum = $qpendidikanSum->where('formcegahs.id_provinsi', $request->wilayah_dropdown);
            }

            if ($request->pilih_wilayah == "kota") {
                $qpendidikanSum = $qpendidikanSum->where('formcegahs.id_kabupaten', $request->wilayah_dropdown);
            }

            $pendidikanSum = $qpendidikanSum->count();

            $qpartisipasiCount = Formcegah::select(
                'provinsis.provinsi as provinsi',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Partisipasi Masyarakat" THEN 1
                ELSE 0
                END) AS count
                ')
            )
                ->leftJoin('provinsis', 'formcegahs.id_provinsi', 'provinsis.id')
                ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->where('formcegahs.id_provinsi', '<>', '');

            if ($request->divisi != "") {
                $qpartisipasiCount = $qpartisipasiCount->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qpartisipasiCount = $qpartisipasiCount->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qpartisipasiCount = $qpartisipasiCount->where('jenis', $request->jenis);
            }

            if ($request->pilih_wilayah == "provinsi") {
                $qpartisipasiCount = $qpartisipasiCount->where('formcegahs.id_provinsi', $request->wilayah_dropdown);
            }

            if ($request->pilih_wilayah == "kota") {
                $qpartisipasiCount = $qpartisipasiCount->where('formcegahs.id_kabupaten', $request->wilayah_dropdown);
            }

            $partisipasiCount = $qpartisipasiCount->groupBy('provinsis.provinsi')->pluck('count');

            $qpartisipasiSum = Formcegah::where('bentuk', '2')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->where('formcegahs.id_provinsi', '<>', '');

            if ($request->divisi != "") {
                $qpartisipasiSum = $qpartisipasiSum->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qpartisipasiSum = $qpartisipasiSum->where('bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qpartisipasiSum = $qpartisipasiSum->where('jenis', $request->jenis);
            }

            if ($request->pilih_wilayah == "provinsi") {
                $qpartisipasiSum = $qpartisipasiSum->where('formcegahs.id_provinsi', $request->wilayah_dropdown);
            }

            if ($request->pilih_wilayah == "kota") {
                $qpartisipasiSum = $qpartisipasiSum->where('formcegahs.id_kabupaten', $request->wilayah_dropdown);
            }

            $partisipasiSum = $qpartisipasiSum->count();

            $qkerjasamaCount = Formcegah::select(
                'provinsis.provinsi as provinsi',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Kerja sama" THEN 1
                ELSE 0
                END) AS count
                ')
            )
                ->leftJoin('provinsis', 'formcegahs.id_provinsi', 'provinsis.id')
                ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->where('formcegahs.id_provinsi', '<>', '');

            if ($request->divisi != "") {
                $qkerjasamaCount = $qkerjasamaCount->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qkerjasamaCount = $qkerjasamaCount->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qkerjasamaCount = $qkerjasamaCount->where('jenis', $request->jenis);
            }

            if ($request->pilih_wilayah == "provinsi") {
                $qkerjasamaCount = $qkerjasamaCount->where('formcegahs.id_provinsi', $request->wilayah_dropdown);
            }

            if ($request->pilih_wilayah == "kota") {
                $qkerjasamaCount = $qkerjasamaCount->where('formcegahs.id_kabupaten', $request->wilayah_dropdown);
            }

            $kerjasamaCount = $qkerjasamaCount->groupBy('provinsis.provinsi')->pluck('count');

            $qkerjasamaSum = Formcegah::where('bentuk', '3')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->where('formcegahs.id_provinsi', '<>', '');

            if ($request->divisi != "") {
                $qkerjasamaSum = $qkerjasamaSum->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qkerjasamaSum = $qkerjasamaSum->where('bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qkerjasamaSum = $qkerjasamaSum->where('jenis', $request->jenis);
            }

            if ($request->pilih_wilayah == "provinsi") {
                $qkerjasamaSum = $qkerjasamaSum->where('formcegahs.id_provinsi', $request->wilayah_dropdown);
            }

            if ($request->pilih_wilayah == "kota") {
                $qkerjasamaSum = $qkerjasamaSum->where('formcegahs.id_kabupaten', $request->wilayah_dropdown);
            }

            // $kerjasamaSum = $qkerjasamaSum->count();
            $kerjasamaSum = optional($qkerjasamaSum)->count();


            $qimbauanCount = Formcegah::select(
                'provinsis.provinsi as provinsi',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Imbauan" THEN 1
                ELSE 0
                END) AS count
                ')
            )
                ->leftJoin('provinsis', 'formcegahs.id_provinsi', 'provinsis.id')
                ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->where('formcegahs.id_provinsi', '<>', '');

            if ($request->divisi != "") {
                $qimbauanCount = $qimbauanCount->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qimbauanCount = $qimbauanCount->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qimbauanCount = $qimbauanCount->where('jenis', $request->jenis);
            }

            if ($request->pilih_wilayah == "provinsi") {
                $qimbauanCount = $qimbauanCount->where('formcegahs.id_provinsi', $request->wilayah_dropdown);
            }

            if ($request->pilih_wilayah == "kota") {
                $qimbauanCount = $qimbauanCount->where('formcegahs.id_kabupaten', $request->wilayah_dropdown);
            }

            $imbauanCount = $qimbauanCount->groupBy('provinsis.provinsi')
                ->pluck('count');

            $qimbauanSum = Formcegah::where('bentuk', '7')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->where('formcegahs.id_provinsi', '<>', '');

            if ($request->divisi != "") {
                $qimbauanSum = $qimbauanSum->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qimbauanSum = $qimbauanSum->where('bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qimbauanSum = $qimbauanSum->where('jenis', $request->jenis);
            }

            if ($request->pilih_wilayah == "provinsi") {
                $qimbauanSum = $qimbauanSum->where('formcegahs.id_provinsi', $request->wilayah_dropdown);
            }

            if ($request->pilih_wilayah == "kota") {
                $qimbauanSum = $qimbauanSum->where('formcegahs.id_kabupaten', $request->wilayah_dropdown);
            }

            $imbauanSum = $qimbauanSum->count();

            $qkegiatanlainCount = Formcegah::select(
                'provinsis.provinsi as provinsi',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Kegiatan Lainnya" THEN 1
                ELSE 0
                END) AS count
                ')
            )
                ->leftJoin('provinsis', 'formcegahs.id_provinsi', 'provinsis.id')
                ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->where('formcegahs.id_provinsi', '<>', '');

            if ($request->divisi != "") {
                $qkegiatanlainCount = $qkegiatanlainCount->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qkegiatanlainCount = $qkegiatanlainCount->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qkegiatanlainCount = $qkegiatanlainCount->where('jenis', $request->jenis);
            }

            if ($request->pilih_wilayah == "provinsi") {
                $qkegiatanlainCount = $qkegiatanlainCount->where('formcegahs.id_provinsi', $request->wilayah_dropdown);
            }

            if ($request->pilih_wilayah == "kota") {
                $qkegiatanlainCount = $qkegiatanlainCount->where('formcegahs.id_kabupaten', $request->wilayah_dropdown);
            }

            $kegiatanlainCount = $qkegiatanlainCount->groupBy('provinsis.provinsi')->pluck('count');

            $qkegiatanlainSum = Formcegah::where('bentuk', '0')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->where('formcegahs.id_provinsi', '<>', '');

            if ($request->divisi != "") {
                $qkegiatanlainSum = $qkegiatanlainSum->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qkegiatanlainSum = $qkegiatanlainSum->where('bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qkegiatanlainSum = $qkegiatanlainSum->where('jenis', $request->jenis);
            }

            if ($request->pilih_wilayah == "provinsi") {
                $qkegiatanlainSum = $qkegiatanlainSum->where('formcegahs.id_provinsi', $request->wilayah_dropdown);
            }

            if ($request->pilih_wilayah == "kota") {
                $qkegiatanlainSum = $qkegiatanlainSum->where('formcegahs.id_kabupaten', $request->wilayah_dropdown);
            }

            $kegiatanlainSum = $qkegiatanlainSum->count();

            $qpublikasiCount = Formcegah::select(
                'provinsis.provinsi as provinsi',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Publikasi" THEN 1
                ELSE 0
                END) AS count
                ')
            )
                ->leftJoin('provinsis', 'formcegahs.id_provinsi', 'provinsis.id')
                ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->where('formcegahs.id_provinsi', '<>', '');

            if ($request->divisi != "") {
                $qpublikasiCount = $qpublikasiCount->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qpublikasiCount = $qpublikasiCount->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qpublikasiCount = $qpublikasiCount->where('jenis', $request->jenis);
            }

            if ($request->pilih_wilayah == "provinsi") {
                $qpublikasiCount = $qpublikasiCount->where('formcegahs.id_provinsi', $request->wilayah_dropdown);
            }

            if ($request->pilih_wilayah == "kota") {
                $qpublikasiCount = $qpublikasiCount->where('formcegahs.id_kabupaten', $request->wilayah_dropdown);
            }

            $publikasiCount = $qpublikasiCount->groupBy('provinsis.provinsi')->pluck('count');

            $qpublikasiSum = Formcegah::where('bentuk', '5')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->where('formcegahs.id_provinsi', '<>', '');

            if ($request->divisi != "") {
                $qpublikasiSum = $qpublikasiSum->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qpublikasiSum = $qpublikasiSum->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qpublikasiSum = $qpublikasiSum->where('jenis', $request->jenis);
            }

            if ($request->pilih_wilayah == "provinsi") {
                $qpublikasiSum = $qpublikasiSum->where('formcegahs.id_provinsi', $request->wilayah_dropdown);
            }

            if ($request->pilih_wilayah == "kota") {
                $qpublikasiSum = $qpublikasiSum->where('formcegahs.id_kabupaten', $request->wilayah_dropdown);
            }

            $publikasiSum = $qpublikasiSum->count();

            //dd($publikasiSum);
            $rekapCegah = [];

            // $qrekapCegah = Formcegah::select(
            //     "formcegahs.created_at",
            //     "formcegahs.id",
            //     "formcegahs.no_form",
            //     "formcegahs.tahap",
            //     "bentuks.bentuk",
            //     "provinsis.provinsi",
            //     "kabupatens.kabupaten",
            //     "kecamatans.kecamatan",
            //     "kelurahans.kelurahan"
            // )
            //     ->leftJoin('provinsis', 'formcegahs.id_provinsi', '=', 'provinsis.id')
            //     ->leftJoin('kabupatens', 'kabupatens.id', '=', 'formcegahs.id_kabupaten')
            //     ->leftJoin('kecamatans', 'kecamatans.id', '=', 'formcegahs.id_kecamatan')
            //     ->leftJoin('kelurahans', 'kelurahans.id', '=', 'formcegahs.id_kelurahan')
            //     ->leftJoin('bentuks', 'formcegahs.bentuk', '=', 'bentuks.id')
            //     ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at, '%Y-%m-%d'))"), [$date_start, $date_finish])
            //     ->where('formcegahs.id_provinsi', '<>', '');

            // if ($request->divisi != "") {
            //     $qrekapCegah = $qrekapCegah->where('id_divisi', $request->divisi);
            // }

            // if ($request->bentuk != "") {
            //     $qrekapCegah = $qrekapCegah->where('formcegahs.bentuk', $request->bentuk);
            // }

            // if ($request->jenis != "") {
            //     $qrekapCegah = $qrekapCegah->where('jenis', $request->jenis);
            // }

            // if ($request->pilih_wilayah == "provinsi") {
            //     $qrekapCegah = $qrekapCegah->where('formcegahs.id_provinsi', $request->wilayah_dropdown);
            // }

            // if ($request->pilih_wilayah == "kota") {
            //     $qrekapCegah = $qrekapCegah->where('formcegahs.id_kabupaten', $request->wilayah_dropdown);
            // }

            // $rekapCegah = $qrekapCegah->orderBy('provinsis.provinsi', 'asc')
            //     ->get();

            //dd($rekapCegah);
        
        } else if ($jabatan == 'Ketua atau Anggota Bawaslu Provinsi') {
            //dd($user->Provinsi);
            if ($user->Provinsi != null) {
                //dd('hai');
                $provinsi = Provinsi::where('id', $user->Provinsi)->first();
                $title = ' Kabupaten/Kota di Seluruh Provinsi ' . $provinsi->provinsi;
                //dd($title);
                $qcategories = Formcegah::select('kabupatens.kabupaten as kabupaten')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                    ->leftJoin('kabupatens', 'formcegahs.id_kabupaten', 'kabupatens.id')
                    ->where('formcegahs.id_provinsi', $user->Provinsi)
                    ->where('formcegahs.id_kabupaten','<>', '');

                if ($request->divisi != "") {
                    $qcategories = $qcategories->where('id_divisi', $request->divisi);
                }

                if ($request->bentuk != "") {
                    $qcategories = $qcategories->where('formcegahs.bentuk', $request->bentuk);
                }

                if ($request->jenis != "") {
                    $qcategories = $qcategories->where('jenis', $request->jenis);
                }

                $categories = $qcategories->groupBy('kabupatens.kabupaten')->pluck('kabupaten');
                //dd($categories);

                $q_categories_RI = Formcegah::select('bentuks.bentuk as bentuk')
                ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                // ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), ['2023-01-01','2023-12-01'])
                ->where('formcegahs.id_provinsi', '');

                $categories_RI = $q_categories_RI->groupBy('bentuks.bentuk')->get()->pluck('bentuk');

                $q_RI = Formcegah::select('bentuks.bentuk',DB::raw('COUNT(*) as count'))
                    ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                    //->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), ['2023-01-01','2023-12-01'])
                    ->where('formcegahs.id_provinsi', '');

                $count_RI = $q_RI->groupBy('bentuks.bentuk')->get()->pluck('count');

                $q_categories_jenis = Formcegah::select('jenis.jenis as jenis')
                    ->leftJoin('jenis', 'formcegahs.jenis', 'jenis.id')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                    //->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), ['2023-01-01','2023-12-01'])
                    ->where('formcegahs.id_provinsi', $user->Provinsi)
                    ->where('formcegahs.jenis','<>', '');

                $categories_jenis = $q_categories_jenis->groupBy('jenis.jenis')->get()->pluck('jenis');

                $q_jenis = Formcegah::select('jenis.jenis as jenis',DB::raw('COUNT(*) as count'))
                    ->leftJoin('jenis', 'formcegahs.jenis', 'jenis.id')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                    //->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), ['2023-01-01','2023-12-01'])
                    ->where('formcegahs.id_provinsi', $user->Provinsi)
                    ->where('formcegahs.jenis','<>', '');

                $count_jenis = $q_jenis->groupBy('jenis.jenis')->get()->pluck('count');

                $qtahapan_pie = Formcegah::select(
                    'tahap',
                    DB::raw('
                    SUM(CASE
                    WHEN tahap="Tahapan" THEN 1
                    WHEN tahap="Non Tahapan" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                    ->where('formcegahs.id_provinsi', $user->Provinsi)
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

                if ($request->divisi != "") {
                    $qtahapan_pie = $qtahapan_pie->where('id_divisi', $request->divisi);
                }

                if ($request->bentuk != "") {
                    $qtahapan_pie = $qtahapan_pie->where('formcegahs.bentuk', $request->bentuk);
                }

                if ($request->jenis != "") {
                    $qtahapan_pie = $qtahapan_pie->where('jenis', $request->jenis);
                }

                $tahapan_pie = $qtahapan_pie->groupBy('tahap')->get();

                $dataTahap = [];
                foreach ($tahapan_pie as $data) {
                    $dataTahap[] = [
                        $data['tahap'],
                        $data['count']
                    ];
                }

                $qbentuk_pie = Formcegah::select(
                    'bentuks.bentuk as bentuk',
                    DB::raw('
                    SUM(CASE
                    WHEN formcegahs.bentuk="0" THEN 1
                    WHEN formcegahs.bentuk="1" THEN 1
                    WHEN formcegahs.bentuk="2" THEN 1
                    WHEN formcegahs.bentuk="3" THEN 1
                    WHEN formcegahs.bentuk="4" THEN 1
                    WHEN formcegahs.bentuk="5" THEN 1
                    WHEN formcegahs.bentuk="6" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                    ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                    ->where('formcegahs.id_provinsi', $user->Provinsi)
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

                if ($request->divisi != "") {
                    $qbentuk_pie = $qbentuk_pie->where('id_divisi', $request->divisi);
                }

                if ($request->bentuk != "") {
                    $qbentuk_pie = $qbentuk_pie->where('formcegahs.bentuk', $request->bentuk);
                }

                if ($request->jenis != "") {
                    $qbentuk_pie = $qbentuk_pie->where('jenis', $request->jenis);
                }

                $bentuk_pie = $qbentuk_pie->groupBy('bentuks.bentuk')->get();

                $dataBentuk = [];
                foreach ($bentuk_pie as $data) {
                    $dataBentuk[] = [
                        $data['bentuk'],
                        $data['count']
                    ];
                }

                //dd(json_encode($dataBentuk));

                $qidentifikasi_kerawananCount = Formcegah::select(
                    'kabupatens.kabupaten as kabupaten',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Identifikasi Kerawanan" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                    ->leftJoin('kabupatens', 'formcegahs.id_kabupaten', 'kabupatens.id')
                    ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                    ->where('formcegahs.id_provinsi', $user->Provinsi)
                    ->where('formcegahs.id_kabupaten','<>', '')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                    ->whereNotNull('kabupatens.kabupaten');

                if ($request->divisi != "") {
                    $qidentifikasi_kerawananCount = $qidentifikasi_kerawananCount->where('id_divisi', $request->divisi);
                }

                if ($request->bentuk != "") {
                    $qidentifikasi_kerawananCount = $qidentifikasi_kerawananCount->where('formcegahs.bentuk', $request->bentuk);
                }

                if ($request->jenis != "") {
                    $qidentifikasi_kerawananCount = $qidentifikasi_kerawananCount->where('jenis', $request->jenis);
                }

                $identifikasi_kerawananCount = $qidentifikasi_kerawananCount->groupBy('kabupatens.kabupaten')->pluck('count');

                $qidentifikasi_kerawananSum = Formcegah::where('bentuk', '6')
                    ->where('formcegahs.id_provinsi', $user->Provinsi)
                    ->where('formcegahs.id_kabupaten', '<>', '')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

                if ($request->divisi != "") {
                    $qidentifikasi_kerawananSum = $qidentifikasi_kerawananSum->where('id_divisi', $request->divisi);
                }

                if ($request->bentuk != "") {
                    $qidentifikasi_kerawananSum = $qidentifikasi_kerawananSum->where('formcegahs.bentuk', $request->bentuk);
                }

                if ($request->jenis != "") {
                    $qidentifikasi_kerawananSum = $qidentifikasi_kerawananSum->where('jenis', $request->jenis);
                }

                $identifikasi_kerawananSum = $qidentifikasi_kerawananSum->count();

                $qpendidikanCount = Formcegah::select(
                    'kabupatens.kabupaten as kabupaten',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Pendidikan" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                    ->leftJoin('kabupatens', 'formcegahs.id_kabupaten', 'kabupatens.id')
                    ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                    ->where('formcegahs.id_provinsi', $user->Provinsi)
                    ->where('formcegahs.id_kabupaten','<>', '')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                    ->whereNotNull('kabupatens.kabupaten');

                if ($request->divisi != "") {
                    $qpendidikanCount = $qpendidikanCount->where('id_divisi', $request->divisi);
                }

                if ($request->bentuk != "") {
                    $qpendidikanCount = $qpendidikanCount->where('formcegahs.bentuk', $request->bentuk);
                }

                if ($request->jenis != "") {
                    $qpendidikanCount = $qpendidikanCount->where('jenis', $request->jenis);
                }

                $pendidikanCount = $qpendidikanCount->groupBy('kabupatens.kabupaten')->pluck('count');

                $qpendidikanSum = Formcegah::where('bentuk', '1')
                    ->where('formcegahs.id_provinsi', $user->Provinsi)
                    ->where('formcegahs.id_kabupaten','<>', '')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

                if ($request->divisi != "") {
                    $qpendidikanSum = $qpendidikanSum->where('id_divisi', $request->divisi);
                }

                if ($request->bentuk != "") {
                    $qpendidikanSum = $qpendidikanSum->where('formcegahs.bentuk', $request->bentuk);
                }

                if ($request->jenis != "") {
                    $qpendidikanSum = $qpendidikanSum->where('jenis', $request->jenis);
                }

                $pendidikanSum = $qpendidikanSum->count();

                $qpartisipasiCount = Formcegah::select(
                    'kabupatens.kabupaten as kabupaten',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Partisipasi Masyarakat" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                    ->leftJoin('kabupatens', 'formcegahs.id_kabupaten', 'kabupatens.id')
                    ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                    ->where('formcegahs.id_provinsi', $user->Provinsi)
                    ->where('formcegahs.id_kabupaten','<>', '')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                    ->whereNotNull('kabupatens.kabupaten');

                if ($request->divisi != "") {
                    $qpartisipasiCount = $qpartisipasiCount->where('id_divisi', $request->divisi);
                }

                if ($request->bentuk != "") {
                    $qpartisipasiCount = $qpartisipasiCount->where('formcegahs.bentuk', $request->bentuk);
                }

                if ($request->jenis != "") {
                    $qpartisipasiCount = $qpartisipasiCount->where('jenis', $request->jenis);
                }

                $partisipasiCount = $qpartisipasiCount->groupBy('kabupatens.kabupaten')->pluck('count');

                $qpartisipasiSum = Formcegah::where('bentuk', '2')
                    ->where('formcegahs.id_provinsi', $user->Provinsi)
                    ->where('formcegahs.id_kabupaten','<>', '')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

                if ($request->divisi != "") {
                    $qpartisipasiSum = $qpartisipasiSum->where('id_divisi', $request->divisi);
                }

                if ($request->bentuk != "") {
                    $qpartisipasiSum = $qpartisipasiSum->where('formcegahs.bentuk', $request->bentuk);
                }

                if ($request->jenis != "") {
                    $qpartisipasiSum = $qpartisipasiSum->where('jenis', $request->jenis);
                }

                $partisipasiSum = $qpartisipasiSum->count();

                $qkerjasamaCount = Formcegah::select(
                    'kabupatens.kabupaten as kabupaten',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Kerja sama" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                    ->leftJoin('kabupatens', 'formcegahs.id_kabupaten', 'kabupatens.id')
                    ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                    ->where('formcegahs.id_provinsi', $user->Provinsi)
                    ->where('formcegahs.id_kabupaten','<>', '')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                    ->whereNotNull('kabupatens.kabupaten');

                if ($request->divisi != "") {
                    $qkerjasamaCount = $qkerjasamaCount->where('id_divisi', $request->divisi);
                }

                if ($request->bentuk != "") {
                    $qkerjasamaCount = $qkerjasamaCount->where('formcegahs.bentuk', $request->bentuk);
                }

                if ($request->jenis != "") {
                    $qkerjasamaCount = $qkerjasamaCount->where('jenis', $request->jenis);
                }

                $kerjasamaCount = $qkerjasamaCount->groupBy('kabupatens.kabupaten')->pluck('count');

                $qkerjasamaSum = Formcegah::where('bentuk', '3')
                    ->where('formcegahs.id_provinsi', $user->Provinsi)
                    ->where('formcegahs.id_kabupaten','<>', '')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

                if ($request->divisi != "") {
                    $qkerjasamaSum = $qkerjasamaSum->where('id_divisi', $request->divisi);
                }

                if ($request->bentuk != "") {
                    $qkerjasamaSum = $qkerjasamaSum->where('formcegahs.bentuk', $request->bentuk);
                }

                if ($request->jenis != "") {
                    $qkerjasamaSum = $qkerjasamaSum->where('jenis', $request->jenis);
                }

                $kerjasamaSum = $qkerjasamaSum->count();

                $qimbauanCount = Formcegah::select(
                    'kabupatens.kabupaten as kabupaten',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Imbauan" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                    ->leftJoin('kabupatens', 'formcegahs.id_kabupaten', 'kabupatens.id')
                    ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                    ->where('formcegahs.id_provinsi', $user->Provinsi)
                    ->where('formcegahs.id_kabupaten','<>', '')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                    ->whereNotNull('kabupatens.kabupaten');

                if ($request->divisi != "") {
                    $qimbauanCount = $qimbauanCount->where('id_divisi', $request->divisi);
                }

                if ($request->bentuk != "") {
                    $qimbauanCount = $qimbauanCount->where('formcegahs.bentuk', $request->bentuk);
                }

                if ($request->jenis != "") {
                    $qimbauanCount = $qimbauanCount->where('jenis', $request->jenis);
                }

                $imbauanCount = $qimbauanCount->groupBy('kabupatens.kabupaten')->pluck('count');

                $qimbauanSum = Formcegah::where('bentuk', '7')
                    ->where('formcegahs.id_provinsi', $user->Provinsi)
                    ->where('formcegahs.id_kabupaten','<>', '')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

                if ($request->divisi != "") {
                    $qimbauanSum = $qimbauanSum->where('id_divisi', $request->divisi);
                }

                if ($request->bentuk != "") {
                    $qimbauanSum = $qimbauanSum->where('formcegahs.bentuk', $request->bentuk);
                }

                if ($request->jenis != "") {
                    $qimbauanSum = $qimbauanSum->where('jenis', $request->jenis);
                }

                $imbauanSum = $qimbauanSum->count();

                $qkegiatanlainCount = Formcegah::select(
                    'kabupatens.kabupaten as kabupaten',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Kegiatan Lainnya" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                    ->leftJoin('kabupatens', 'formcegahs.id_kabupaten', 'kabupatens.id')
                    ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                    ->where('formcegahs.id_provinsi', $user->Provinsi)
                    ->where('formcegahs.id_kabupaten','<>', '')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                    ->whereNotNull('kabupatens.kabupaten');

                if ($request->divisi != "") {
                    $qkegiatanlainCount = $qkegiatanlainCount->where('id_divisi', $request->divisi);
                }

                if ($request->bentuk != "") {
                    $qkegiatanlainCount = $qkegiatanlainCount->where('formcegahs.bentuk', $request->bentuk);
                }

                if ($request->jenis != "") {
                    $qkegiatanlainCount = $qkegiatanlainCount->where('jenis', $request->jenis);
                }

                $kegiatanlainCount = $qkegiatanlainCount->groupBy('kabupatens.kabupaten')->pluck('count');

                $qkegiatanlainSum = Formcegah::where('bentuk', '0')
                    ->where('formcegahs.id_provinsi', $user->Provinsi)
                    ->where('formcegahs.id_kabupaten','<>', '')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

                if ($request->divisi != "") {
                    $qkegiatanlainSum = $qkegiatanlainSum->where('id_divisi', $request->divisi);
                }

                if ($request->bentuk != "") {
                    $qkegiatanlainSum = $qkegiatanlainSum->where('formcegahs.bentuk', $request->bentuk);
                }

                if ($request->jenis != "") {
                    $qkegiatanlainSum = $qkegiatanlainSum->where('jenis', $request->jenis);
                }

                $kegiatanlainSum = $qkegiatanlainSum->count();

                $qpublikasiCount = Formcegah::select(
                    'kabupatens.kabupaten as kabupaten',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Publikasi" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                    ->leftJoin('kabupatens', 'formcegahs.id_kabupaten', 'kabupatens.id')
                    ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                    ->where('formcegahs.id_provinsi', $user->Provinsi)
                    ->where('formcegahs.id_kabupaten','<>', '')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                    ->whereNotNull('kabupatens.kabupaten');

                if ($request->divisi != "") {
                    $qpublikasiCount = $qpublikasiCount->where('id_divisi', $request->divisi);
                }

                if ($request->bentuk != "") {
                    $qpublikasiCount = $qpublikasiCount->where('formcegahs.bentuk', $request->bentuk);
                }

                if ($request->jenis != "") {
                    $qpublikasiCount = $qpublikasiCount->where('jenis', $request->jenis);
                }

                $publikasiCount = $qpublikasiCount->groupBy('kabupatens.kabupaten')->pluck('count');
                
                $qpublikasiSum = Formcegah::where('bentuk', '5')
                    ->where('formcegahs.id_provinsi', $user->Provinsi)
                    ->where('formcegahs.id_kabupaten','<>', '')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

                if ($request->divisi != "") {
                    $qpublikasiSum = $qpublikasiSum->where('id_divisi', $request->divisi);
                }

                if ($request->bentuk != "") {
                    $qpublikasiSum = $qpublikasiSum->where('formcegahs.bentuk', $request->bentuk);
                }

                if ($request->jenis != "") {
                    $qpublikasiSum = $qpublikasiSum->where('jenis', $request->jenis);
                }

                $publikasiSum = $qpublikasiSum->count();

                $rekapCegah = [];
                // $qrekapCegah = Formcegah::select(
                //     "formcegahs.created_at",
                //     "formcegahs.id",
                //     "formcegahs.no_form",
                //     "formcegahs.tahap",
                //     "bentuks.bentuk",
                //     "provinsis.provinsi",
                //     "kabupatens.kabupaten",
                //     "kecamatans.kecamatan",
                //     "kelurahan"
                // )
                //     ->leftJoin('provinsis', 'formcegahs.id_provinsi', '=', 'provinsis.id')
                //     ->leftJoin('kabupatens', 'kabupatens.id', '=', 'formcegahs.id_kabupaten')
                //     ->leftJoin('kecamatans', 'kecamatans.id', '=', 'formcegahs.id_kecamatan')
                //     ->leftJoin('kelurahans', 'kelurahans.id', '=', 'formcegahs.id_kelurahan')
                //     ->leftJoin('bentuks', 'formcegahs.bentuk', '=', 'bentuks.id')
                //     ->where('formcegahs.id_provinsi', $user->Provinsi)
                //     ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at, '%Y-%m-%d'))"), [$date_start, $date_finish]);

                // if ($request->divisi != "") {
                //     $qrekapCegah = $qrekapCegah->where('id_divisi', $request->divisi);
                // }

                // if ($request->bentuk != "") {
                //     $qrekapCegah = $qrekapCegah->where('formcegahs.bentuk', $request->bentuk);
                // }

                // if ($request->jenis != "") {
                //     $qrekapCegah = $qrekapCegah->where('jenis', $request->jenis);
                // }

                // $rekapCegah = $qrekapCegah->orderBy('provinsis.provinsi', 'asc')->get();
				//dd('hai');
				$qnaskahdinasCount = Formcegah::select(
                    'kabupatens.kabupaten as kabupaten',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Naskah Dinas" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                    ->leftJoin('kabupatens', 'formcegahs.id_kabupaten', 'kabupatens.id')
                    ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                    ->where('formcegahs.id_provinsi', $user->Provinsi)
                    ->where('formcegahs.id_kabupaten','<>', '')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                    ->whereNotNull('kabupatens.kabupaten');

                if ($request->divisi != "") {
                    $qnaskahdinasCount = $qnaskahdinasCount->where('id_divisi', $request->divisi);
                }

                if ($request->bentuk != "") {
                    $qnaskahdinasCount = $qnaskahdinasCount->where('formcegahs.bentuk', $request->bentuk);
                }

                if ($request->jenis != "") {
                    $qnaskahdinasCount = $qnaskahdinasCount->where('jenis', $request->jenis);
                }

                $naskahdinasCount = $qnaskahdinasCount->groupBy('kabupatens.kabupaten')->pluck('count');

                $qnaskahdinasSum = Formcegah::where('bentuk', '4')
                    ->where('formcegahs.id_provinsi', $user->Provinsi)
                    ->where('formcegahs.id_kabupaten','<>', '')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

                if ($request->divisi != "") {
                    $qnaskahdinasSum = $qnaskahdinasSum->where('id_divisi', $request->divisi);
                }

                if ($request->bentuk != "") {
                    $qnaskahdinasSum = $qnaskahdinasSum->where('formcegahs.bentuk', $request->bentuk);
                }

                if ($request->jenis != "") {
                    $qnaskahdinasSum = $qnaskahdinasSum->where('jenis', $request->jenis);
                }

                $naskahdinasSum = $qnaskahdinasSum->count();

                //dd(json_encode($categories),json_encode($identifikasi_kerawananCount),json_encode($pendidikanCount));
            } else {
                //dd('hai');
                $userProv = 'pusat';
                $title = ' Seluruh Provinsi';
                $qcategories = Formcegah::select('provinsis.provinsi as provinsi')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                    ->leftJoin('provinsis', 'formcegahs.id_provinsi', 'provinsis.id');

                if ($request->divisi != "") {
                    $qcategories = $qcategories->where('id_divisi', $request->divisi);
                }

                if ($request->bentuk != "") {
                    $qcategories = $qcategories->where('formcegahs.bentuk', $request->bentuk);
                }

                if ($request->jenis != "") {
                    $qcategories = $qcategories->where('jenis', $request->jenis);
                }

                $categories = $qcategories->groupBy('provinsis.provinsi')->pluck('provinsi');
                
                $q_categories_RI = Formcegah::select('bentuks.bentuk as bentuk')
                ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                // ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), ['2023-01-01','2023-12-01'])
                ->where('formcegahs.id_provinsi', '');

                $categories_RI = $q_categories_RI->groupBy('bentuks.bentuk')->get()->pluck('bentuk');


                $q_RI = Formcegah::select('bentuks.bentuk',DB::raw('COUNT(*) as count'))
                    ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                    //->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), ['2023-01-01','2023-12-01'])
                    ->where('formcegahs.id_provinsi', '');

                $count_RI = $q_RI->groupBy('bentuks.bentuk')->get()->pluck('count');

                $q_categories_jenis = Formcegah::select('jenis.jenis as jenis')
                    ->leftJoin('jenis', 'formcegahs.jenis', 'jenis.id')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                    //->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), ['2023-01-01','2023-12-01'])
                    ->where('formcegahs.jenis','<>', '');

                $categories_jenis = $q_categories_jenis->groupBy('jenis.jenis')->get()->pluck('jenis');

                $q_jenis = Formcegah::select('jenis.jenis as jenis',DB::raw('COUNT(*) as count'))
                    ->leftJoin('jenis', 'formcegahs.jenis', 'jenis.id')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                    //->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), ['2023-01-01','2023-12-01'])
                    ->where('formcegahs.jenis','<>', '');

                $count_jenis = $q_jenis->groupBy('jenis.jenis')->get()->pluck('count');

                $qtahapan_pie = Formcegah::select(
                    'tahap',
                    DB::raw('
                    SUM(CASE
                    WHEN tahap="Tahapan" THEN 1
                    WHEN tahap="Non Tahapan" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

                if ($request->divisi != "") {
                    $qtahapan_pie = $qtahapan_pie->where('id_divisi', $request->divisi);
                }

                if ($request->bentuk != "") {
                    $qtahapan_pie = $qtahapan_pie->where('formcegahs.bentuk', $request->bentuk);
                }

                if ($request->jenis != "") {
                    $qtahapan_pie = $qtahapan_pie->where('jenis', $request->jenis);
                }

                $tahapan_pie = $qtahapan_pie->groupBy('tahap')->get();

                $dataTahap = [];
                foreach ($tahapan_pie as $data) {
                    $dataTahap[] = [
                        $data['tahap'],
                        $data['count']
                    ];
                }

                $qbentuk_pie = Formcegah::select(
                    'bentuks.bentuk as bentuk',
                    DB::raw('
                    SUM(CASE
                    WHEN formcegahs.bentuk="0" THEN 1
                    WHEN formcegahs.bentuk="1" THEN 1
                    WHEN formcegahs.bentuk="2" THEN 1
                    WHEN formcegahs.bentuk="3" THEN 1
                    WHEN formcegahs.bentuk="4" THEN 1
                    WHEN formcegahs.bentuk="5" THEN 1
                    WHEN formcegahs.bentuk="6" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                    ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

                if ($request->divisi != "") {
                    $qbentuk_pie = $qbentuk_pie->where('id_divisi', $request->divisi);
                }

                if ($request->bentuk != "") {
                    $qbentuk_pie = $qbentuk_pie->where('formcegahs.bentuk', $request->bentuk);
                }

                if ($request->jenis != "") {
                    $qbentuk_pie = $qbentuk_pie->where('jenis', $request->jenis);
                }

                $bentuk_pie = $qbentuk_pie->groupBy('bentuks.bentuk')->get();

                $dataBentuk = [];
                foreach ($bentuk_pie as $data) {
                    $dataBentuk[] = [
                        $data['bentuk'],
                        $data['count']
                    ];
                }

                //dd(json_encode($dataBentuk));

                $qidentifikasi_kerawananCount = Formcegah::select(
                    'provinsis.provinsi as provinsi',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Identifikasi Kerawanan" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                    ->leftJoin('provinsis', 'formcegahs.id_provinsi', 'provinsis.id')
                    ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

                if ($request->divisi != "") {
                    $qidentifikasi_kerawananCount = $qidentifikasi_kerawananCount->where('id_divisi', $request->divisi);
                }

                if ($request->bentuk != "") {
                    $qidentifikasi_kerawananCount = $qidentifikasi_kerawananCount->where('formcegahs.bentuk', $request->bentuk);
                }

                if ($request->jenis != "") {
                    $qidentifikasi_kerawananCount = $qidentifikasi_kerawananCount->where('jenis', $request->jenis);
                }

                $identifikasi_kerawananCount = $qidentifikasi_kerawananCount->groupBy('provinsis.provinsi')->pluck('count');

                $qidentifikasi_kerawananSum = Formcegah::where('bentuk', '6')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

                if ($request->divisi != "") {
                    $qidentifikasi_kerawananSum = $qidentifikasi_kerawananSum->where('id_divisi', $request->divisi);
                }

                if ($request->bentuk != "") {
                    $qidentifikasi_kerawananSum = $qidentifikasi_kerawananSum->where('formcegahs.bentuk', $request->bentuk);
                }

                if ($request->jenis != "") {
                    $qidentifikasi_kerawananSum = $qidentifikasi_kerawananSum->where('jenis', $request->jenis);
                }

                $identifikasi_kerawananSum = $qidentifikasi_kerawananSum->count();

                $qpendidikanCount = Formcegah::select(
                    'provinsis.provinsi as provinsi',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Pendidikan" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                    ->leftJoin('provinsis', 'formcegahs.id_provinsi', 'provinsis.id')
                    ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

                if ($request->divisi != "") {
                    $qpendidikanCount = $qpendidikanCount->where('id_divisi', $request->divisi);
                }

                if ($request->bentuk != "") {
                    $qpendidikanCount = $qpendidikanCount->where('formcegahs.bentuk', $request->bentuk);
                }

                if ($request->jenis != "") {
                    $qpendidikanCount = $qpendidikanCount->where('jenis', $request->jenis);
                }

                $pendidikanCount = $qpendidikanCount->groupBy('provinsis.provinsi')->pluck('count');

                $qpendidikanSum = Formcegah::where('bentuk', '1')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

                if ($request->divisi != "") {
                    $qpendidikanSum = $qpendidikanSum->where('id_divisi', $request->divisi);
                }

                if ($request->bentuk != "") {
                    $qpendidikanSum = $qpendidikanSum->where('formcegahs.bentuk', $request->bentuk);
                }

                if ($request->jenis != "") {
                    $qpendidikanSum = $qpendidikanSum->where('jenis', $request->jenis);
                }

                $pendidikanSum = $qpendidikanSum->count();

                $qpartisipasiCount = Formcegah::select(
                    'provinsis.provinsi as provinsi',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Partisipasi Masyarakat" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                    ->leftJoin('provinsis', 'formcegahs.id_provinsi', 'provinsis.id')
                    ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

                if ($request->divisi != "") {
                    $qpartisipasiCount = $qpartisipasiCount->where('id_divisi', $request->divisi);
                }

                if ($request->bentuk != "") {
                    $qpartisipasiCount = $qpartisipasiCount->where('formcegahs.bentuk', $request->bentuk);
                }

                if ($request->jenis != "") {
                    $qpartisipasiCount = $qpartisipasiCount->where('jenis', $request->jenis);
                }

                $partisipasiCount = $qpartisipasiCount->groupBy('provinsis.provinsi')->pluck('count');

                $qpartisipasiSum = Formcegah::where('bentuk', '2')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

                if ($request->divisi != "") {
                    $qpartisipasiSum = $qpartisipasiSum->where('id_divisi', $request->divisi);
                }

                if ($request->bentuk != "") {
                    $qpartisipasiSum = $qpartisipasiSum->where('formcegahs.bentuk', $request->bentuk);
                }

                if ($request->jenis != "") {
                    $qpartisipasiSum = $qpartisipasiSum->where('jenis', $request->jenis);
                }

                $partisipasiSum = $qpartisipasiSum->count();

                $qkerjasamaCount = Formcegah::select(
                    'provinsis.provinsi as provinsi',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Kerja sama" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                    ->leftJoin('provinsis', 'formcegahs.id_provinsi', 'provinsis.id')
                    ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

                if ($request->divisi != "") {
                    $qkerjasamaCount = $qkerjasamaCount->where('id_divisi', $request->divisi);
                }

                if ($request->bentuk != "") {
                    $qkerjasamaCount = $qkerjasamaCount->where('formcegahs.bentuk', $request->bentuk);
                }

                if ($request->jenis != "") {
                    $qkerjasamaCount = $qkerjasamaCount->where('jenis', $request->jenis);
                }

                $kerjasamaCount = $qkerjasamaCount->groupBy('provinsis.provinsi')
                    ->pluck('count');

                $qkerjasamaSum = Formcegah::where('bentuk', '3')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

                if ($request->divisi != "") {
                    $qkerjasamaSum = $qkerjasamaSum->where('id_divisi', $request->divisi);
                }

                if ($request->bentuk != "") {
                    $qkerjasamaSum = $qkerjasamaSum->where('formcegahs.bentuk', $request->bentuk);
                }

                if ($request->jenis != "") {
                    $qkerjasamaSum = $qkerjasamaSum->where('jenis', $request->jenis);
                }

                $kerjasamaSum = $qkerjasamaSum->count();

                $qimbauanCount = Formcegah::select(
                    'provinsis.provinsi as provinsi',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Imbauan" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                    ->leftJoin('provinsis', 'formcegahs.id_provinsi', 'provinsis.id')
                    ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

                if ($request->divisi != "") {
                    $qimbauanCount = $qimbauanCount->where('id_divisi', $request->divisi);
                }

                if ($request->bentuk != "") {
                    $qimbauanCount = $qimbauanCount->where('formcegahs.bentuk', $request->bentuk);
                }

                if ($request->jenis != "") {
                    $qimbauanCount = $qimbauanCount->where('jenis', $request->jenis);
                }

                $imbauanCount = $qimbauanCount->groupBy('provinsis.provinsi')->pluck('count');

                $qimbauanSum = Formcegah::where('bentuk', '7')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

                if ($request->divisi != "") {
                    $qimbauanSum = $qimbauanSum->where('id_divisi', $request->divisi);
                }

                if ($request->bentuk != "") {
                    $qimbauanSum = $qimbauanSum->where('formcegahs.bentuk', $request->bentuk);
                }

                if ($request->jenis != "") {
                    $qimbauanSum = $qimbauanSum->where('jenis', $request->jenis);
                }

                $imbauanSum = $qimbauanSum->count();

                $qkegiatanlainCount = Formcegah::select(
                    'provinsis.provinsi as provinsi',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Kegiatan Lainnya" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                    ->leftJoin('provinsis', 'formcegahs.id_provinsi', 'provinsis.id')
                    ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

                if ($request->divisi != "") {
                    $qkegiatanlainCount = $qkegiatanlainCount->where('id_divisi', $request->divisi);
                }

                if ($request->bentuk != "") {
                    $qkegiatanlainCount = $qkegiatanlainCount->where('formcegahs.bentuk', $request->bentuk);
                }

                if ($request->jenis != "") {
                    $qkegiatanlainCount = $qkegiatanlainCount->where('jenis', $request->jenis);
                }

                $kegiatanlainCount = $qkegiatanlainCount->groupBy('provinsis.provinsi')->pluck('count');

                $qkegiatanlainSum = Formcegah::where('bentuk', '0')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

                if ($request->divisi != "") {
                    $qkegiatanlainSum = $qkegiatanlainSum->where('id_divisi', $request->divisi);
                }

                if ($request->bentuk != "") {
                    $qkegiatanlainSum = $qkegiatanlainSum->where('formcegahs.bentuk', $request->bentuk);
                }

                if ($request->jenis != "") {
                    $qkegiatanlainSum = $qkegiatanlainSum->where('jenis', $request->jenis);
                }

                $kegiatanlainSum = $qkegiatanlainSum->count();

                $qpublikasiCount = Formcegah::select(
                    'provinsis.provinsi as provinsi',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Publikasi" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                    ->leftJoin('provinsis', 'formcegahs.id_provinsi', 'provinsis.id')
                    ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

                if ($request->divisi != "") {
                    $qpublikasiCount = $qpublikasiCount->where('id_divisi', $request->divisi);
                }

                if ($request->bentuk != "") {
                    $qpublikasiCount = $qpublikasiCount->where('formcegahs.bentuk', $request->bentuk);
                }

                if ($request->jenis != "") {
                    $qpublikasiCount = $qpublikasiCount->where('jenis', $request->jenis);
                }

                $publikasiCount = $qpublikasiCount->groupBy('provinsis.provinsi')->pluck('count');

                $qpublikasiSum = Formcegah::where('bentuk', '5')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

                if ($request->divisi != "") {
                    $qpublikasiSum = $qpublikasiSum->where('id_divisi', $request->divisi);
                }

                if ($request->bentuk != "") {
                    $qpublikasiSum = $qpublikasiSum->where('formcegahs.bentuk', $request->bentuk);
                }

                if ($request->jenis != "") {
                    $qpublikasiSum = $qpublikasiSum->where('jenis', $request->jenis);
                }

                $publikasiSum = $qpublikasiSum->count();

                // $qrekapCegah = Formcegah::select(
                //     "formcegahs.created_at",
                //     "formcegahs.id",
                //     "formcegahs.no_form",
                //     "formcegahs.tahap",
                //     "bentuks.bentuk",
                //     "provinsis.provinsi",
                //     "kabupatens.kabupaten",
                //     "kecamatans.kecamatan",
                //     "kelurahan"
                // )
                //     ->leftJoin('provinsis', 'formcegahs.id_provinsi', '=', 'provinsis.id')
                //     ->leftJoin('kabupatens', 'kabupatens.id', '=', 'formcegahs.id_kabupaten')
                //     ->leftJoin('kecamatans', 'kecamatans.id', '=', 'formcegahs.id_kecamatan')
                //     ->leftJoin('kelurahans', 'kelurahans.id', '=', 'formcegahs.id_kelurahan')
                //     ->leftJoin('bentuks', 'formcegahs.bentuk', '=', 'bentuks.id')
                //     ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at, '%Y-%m-%d'))"), [$date_start, $date_finish]);

                // if ($request->divisi != "") {
                //     $qrekapCegah = $qrekapCegah->where('id_divisi', $request->divisi);
                // }

                // if ($request->bentuk != "") {
                //     $qrekapCegah = $qrekapCegah->where('formcegahs.bentuk', $request->bentuk);
                // }

                // if ($request->jenis != "") {
                //     $qrekapCegah = $qrekapCegah->where('jenis', $request->jenis);
                // }

                // $rekapCegah = $qrekapCegah->orderBy('provinsis.provinsi', 'asc')->get();
				$rekapCegah =[];

				$qnaskahdinasCount = Formcegah::select(
                    'kabupatens.kabupaten as kabupaten',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Naskah Dinas" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                    ->leftJoin('kabupatens', 'formcegahs.id_kabupaten', 'kabupatens.id')
                    ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                    ->where('formcegahs.id_provinsi', $user->Provinsi)
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

                if ($request->divisi != "") {
                    $qnaskahdinasCount = $qnaskahdinasCount->where('id_divisi', $request->divisi);
                }

                if ($request->bentuk != "") {
                    $qnaskahdinasCount = $qnaskahdinasCount->where('formcegahs.bentuk', $request->bentuk);
                }

                if ($request->jenis != "") {
                    $qnaskahdinasCount = $qnaskahdinasCount->where('jenis', $request->jenis);
                }

                $naskahdinasCount = $qnaskahdinasCount->groupBy('kabupatens.kabupaten')->pluck('count');
				
				$qnaskahdinasSum = Formcegah::where('bentuk', '4')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->where('formcegahs.id_provinsi', '<>', '');

				if ($request->divisi != "") {
					$qnaskahdinasSum = $qnaskahdinasSum->where('id_divisi', $request->divisi);
				}

				if ($request->bentuk != "") {
					$qnaskahdinasSum = $qnaskahdinasSum->where('formcegahs.bentuk', $request->bentuk);
				}

				if ($request->jenis != "") {
					$qnaskahdinasSum = $qnaskahdinasSum->where('jenis', $request->jenis);
				}

				if ($request->pilih_wilayah == "provinsi") {
					$qnaskahdinassum = $qnaskahdinasSum->where('formcegahs.id_provinsi', $request->wilayah_dropdown);
				}

				if ($request->pilih_wilayah == "kota") {
					$qnaskahdinasSum = $qnaskahdinasSum->where('formcegahs.id_kabupaten', $request->wilayah_dropdown);
				}

				$naskahdinasSum = $qnaskahdinasSum->count();

                //dd(json_encode($categories));
            }
        } else if ($jabatan == 'Ketua atau Anggota Bawaslu Kabupaten/Kota') {
            //dd("hai");
            $userProv = 'non pusat';
            $KabKota = Kabupaten::where('id', $user->KabKota)->first();
            $title = ' Kecamatan di Seluruh ' . $KabKota->kabupaten;
            //dd($title);
            $qcategories = Formcegah::select('kecamatans.kecamatan as kecamatan')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->leftJoin('kecamatans', 'formcegahs.id_kecamatan', 'kecamatans.id')
                ->where('formcegahs.id_kabupaten', $user->KabKota)
                ->where('formcegahs.id_kecamatan','<>', '');

            if ($request->divisi != "") {
                $qcategories = $qcategories->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qcategories = $qcategories->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qcategories = $qcategories->where('jenis', $request->jenis);
            }

            $categories = $qcategories->groupBy('kecamatans.kecamatan')->pluck('kecamatan');

            //dd(json_encode($categories));
            $categories_RI = [];
            $count_RI = [];

            $q_categories_jenis = Formcegah::select('jenis.jenis as jenis')
                ->leftJoin('jenis', 'formcegahs.jenis', 'jenis.id')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                //->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), ['2023-01-01','2023-12-01'])
                ->where('formcegahs.id_kabupaten', $user->KabKota)
                ->where('formcegahs.jenis','<>', '');

            $categories_jenis = $q_categories_jenis->groupBy('jenis.jenis')->get()->pluck('jenis');

            $q_jenis = Formcegah::select('jenis.jenis as jenis',DB::raw('COUNT(*) as count'))
                ->leftJoin('jenis', 'formcegahs.jenis', 'jenis.id')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                //->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), ['2023-01-01','2023-12-01'])
                ->where('formcegahs.id_kabupaten', $user->KabKota)
                ->where('formcegahs.jenis','<>', '');

            $count_jenis = $q_jenis->groupBy('jenis.jenis')->get()->pluck('count');

            $qtahapan_pie = Formcegah::select(
                'tahap',
                DB::raw('
                SUM(CASE
                WHEN tahap="Tahapan" THEN 1
                WHEN tahap="Non Tahapan" THEN 1
                ELSE 0
                END) AS count
                ')
            )
                ->where('formcegahs.id_kabupaten', $user->KabKota)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

            if ($request->divisi != "") {
                $qtahapan_pie = $qtahapan_pie->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qtahapan_pie = $qtahapan_pie->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qtahapan_pie = $qtahapan_pie->where('jenis', $request->jenis);
            }

            $tahapan_pie = $qtahapan_pie->groupBy('tahap')->get();

            $dataTahap = [];
            foreach ($tahapan_pie as $data) {
                $dataTahap[] = [
                    $data['tahap'],
                    $data['count']
                ];
            }

            $qbentuk_pie = Formcegah::select(
                'bentuks.bentuk as bentuk',
                DB::raw('
                SUM(CASE
                WHEN formcegahs.bentuk="0" THEN 1
                WHEN formcegahs.bentuk="1" THEN 1
                WHEN formcegahs.bentuk="2" THEN 1
                WHEN formcegahs.bentuk="3" THEN 1
                WHEN formcegahs.bentuk="4" THEN 1
                WHEN formcegahs.bentuk="5" THEN 1
                WHEN formcegahs.bentuk="6" THEN 1
                ELSE 0
                END) AS count
                ')
            )
                ->where('formcegahs.id_kabupaten', $user->KabKota)
                ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

            if ($request->divisi != "") {
                $qbentuk_pie = $qbentuk_pie->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qbentuk_pie = $qbentuk_pie->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qbentuk_pie = $qbentuk_pie->where('jenis', $request->jenis);
            }

            $bentuk_pie = $qbentuk_pie->groupBy('bentuks.bentuk')->get();

            $dataBentuk = [];
            foreach ($bentuk_pie as $data) {
                $dataBentuk[] = [
                    $data['bentuk'],
                    $data['count']
                ];
            }

            //dd(json_encode($dataBentuk));

            $qidentifikasi_kerawananCount = Formcegah::select(
                'kecamatans.kecamatan as kecamatan',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Identifikasi Kerawanan" THEN 1
                ELSE 0
                END) AS count
                ')
            )
                ->leftJoin('kecamatans', 'formcegahs.id_kecamatan', 'kecamatans.id')
                ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                ->where('formcegahs.id_kabupaten', $user->KabKota)
                ->where('formcegahs.id_kecamatan','<>', '')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

            if ($request->divisi != "") {
                $qidentifikasi_kerawananCount = $qidentifikasi_kerawananCount->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qidentifikasi_kerawananCount = $qidentifikasi_kerawananCount->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qidentifikasi_kerawananCount = $qidentifikasi_kerawananCount->where('jenis', $request->jenis);
            }

            $identifikasi_kerawananCount = $qidentifikasi_kerawananCount->groupBy('kecamatans.kecamatan')->pluck('count');

            $qidentifikasi_kerawananSum = Formcegah::where('bentuk', '6')
                ->where('formcegahs.id_kabupaten', $user->KabKota)
                ->where('formcegahs.id_kecamatan','<>', '')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

            if ($request->divisi != "") {
                $qidentifikasi_kerawananSum = $qidentifikasi_kerawananSum->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qidentifikasi_kerawananSum = $qidentifikasi_kerawananSum->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qidentifikasi_kerawananSum = $qidentifikasi_kerawananSum->where('jenis', $request->jenis);
            }

            $identifikasi_kerawananSum = $qidentifikasi_kerawananSum->count();

            $qpendidikanCount = Formcegah::select(
                'kecamatans.kecamatan as kecamatan',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Pendidikan" THEN 1
                ELSE 0
                END) AS count
                ')
            )
                ->leftJoin('kecamatans', 'formcegahs.id_kecamatan', 'kecamatans.id')
                ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                ->where('formcegahs.id_kabupaten', $user->KabKota)
                ->where('formcegahs.id_kecamatan','<>', '')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

            if ($request->divisi != "") {
                $qpendidikanCount = $qpendidikanCount->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qpendidikanCount = $qpendidikanCount->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qpendidikanCount = $qpendidikanCount->where('jenis', $request->jenis);
            }

            $pendidikanCount = $qpendidikanCount->groupBy('kecamatans.kecamatan')->pluck('count');

            $qpendidikanSum = Formcegah::where('bentuk', '1')
                ->where('formcegahs.id_kabupaten', $user->KabKota)
                ->where('formcegahs.id_kecamatan','<>', '')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

            if ($request->divisi != "") {
                $qpendidikanSum = $qpendidikanSum->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qpendidikanSum = $qpendidikanSum->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qpendidikanSum = $qpendidikanSum->where('jenis', $request->jenis);
            }

            $pendidikanSum = $qpendidikanSum->count();

            $qpartisipasiCount = Formcegah::select(
                'kecamatans.kecamatan as kecamatan',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Partisipasi Masyarakat" THEN 1
                ELSE 0
                END) AS count
                ')
            )
                ->leftJoin('kecamatans', 'formcegahs.id_kecamatan', 'kecamatans.id')
                ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                ->where('formcegahs.id_kabupaten', $user->KabKota)
                ->where('formcegahs.id_kecamatan','<>', '')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

            if ($request->divisi != "") {
                $qpartisipasiCount = $qpartisipasiCount->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qpartisipasiCount = $qpartisipasiCount->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qpartisipasiCount = $qpartisipasiCount->where('jenis', $request->jenis);
            }

            $partisipasiCount = $qpartisipasiCount->groupBy('kecamatans.kecamatan')->pluck('count');

            $qpartisipasiSum = Formcegah::where('bentuk', '2')
                ->where('formcegahs.id_kabupaten', $user->KabKota)
                ->where('formcegahs.id_kecamatan','<>', '')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

            if ($request->divisi != "") {
                $qpartisipasiSum = $qpartisipasiSum->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qpartisipasiSum = $qpartisipasiSum->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qpartisipasiSum = $qpartisipasiSum->where('jenis', $request->jenis);
            }

            $partisipasiSum = $qpartisipasiSum->count();

            $qkerjasamaCount = Formcegah::select(
                'kecamatans.kecamatan as kecamatan',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Kerja sama" THEN 1
                ELSE 0
                END) AS count
                ')
            )
                ->leftJoin('kecamatans', 'formcegahs.id_kecamatan', 'kecamatans.id')
                ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                ->where('formcegahs.id_kabupaten', $user->KabKota)
                ->where('formcegahs.id_kecamatan','<>', '')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

            if ($request->divisi != "") {
                $qkerjasamaCount = $qkerjasamaCount->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qkerjasamaCount = $qkerjasamaCount->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qkerjasamaCount = $qkerjasamaCount->where('jenis', $request->jenis);
            }

            $kerjasamaCount = $qkerjasamaCount->groupBy('kecamatans.kecamatan')
                ->pluck('count');

            $qkerjasamaSum = Formcegah::where('bentuk', '3')
                ->where('formcegahs.id_kabupaten', $user->KabKota)
                ->where('formcegahs.id_kecamatan','<>', '')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

            if ($request->divisi != "") {
                $qkerjasamaSum = $qkerjasamaSum->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qkerjasamaSum = $qkerjasamaSum->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qkerjasamaSum = $qkerjasamaSum->where('jenis', $request->jenis);
            }

            $kerjasamaSum = $qkerjasamaSum->count();

            $qimbauanCount = Formcegah::select(
                'kecamatans.kecamatan as kecamatan',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Imbauan" THEN 1
                ELSE 0
                END) AS count
                ')
            )
                ->leftJoin('kecamatans', 'formcegahs.id_kecamatan', 'kecamatans.id')
                ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                ->where('formcegahs.id_kabupaten', $user->KabKota)
                ->where('formcegahs.id_kecamatan','<>', '')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

            if ($request->divisi != "") {
                $qimbauanCount = $qimbauanCount->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qimbauanCount = $qimbauanCount->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qimbauanCount = $qimbauanCount->where('jenis', $request->jenis);
            }

            $imbauanCount = $qimbauanCount->groupBy('kecamatans.kecamatan')
                ->pluck('count');

            $qimbauanSum = Formcegah::where('bentuk', '7')
                ->where('formcegahs.id_kabupaten', $user->KabKota)
                ->where('formcegahs.id_kecamatan','<>', '')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

            if ($request->divisi != "") {
                $qimbauanSum = $qimbauanSum->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qimbauanSum = $qimbauanSum->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qimbauanSum = $qimbauanSum->where('jenis', $request->jenis);
            }

            $imbauanSum = $qimbauanSum->count();

            $qkegiatanlainCount = Formcegah::select(
                'kecamatans.kecamatan as kecamatan',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Kegiatan Lainnya" THEN 1
                ELSE 0
                END) AS count
                ')
            )
                ->leftJoin('kecamatans', 'formcegahs.id_kecamatan', 'kecamatans.id')
                ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                ->where('formcegahs.id_kabupaten', $user->KabKota)
                ->where('formcegahs.id_kecamatan','<>', '')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

            if ($request->divisi != "") {
                $qkegiatanlainCount = $qkegiatanlainCount->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qkegiatanlainCount = $qkegiatanlainCount->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qkegiatanlainCount = $qkegiatanlainCount->where('jenis', $request->jenis);
            }

            $kegiatanlainCount = $qkegiatanlainCount->groupBy('kecamatans.kecamatan')->pluck('count');

            $qkegiatanlainSum = Formcegah::where('bentuk', '0')
                ->where('formcegahs.id_kabupaten', $user->KabKota)
                ->where('formcegahs.id_kecamatan','<>', '')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

            if ($request->divisi != "") {
                $qkegiatanlainSum = $qkegiatanlainSum->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qkegiatanlainSum = $qkegiatanlainSum->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qkegiatanlainSum = $qkegiatanlainSum->where('jenis', $request->jenis);
            }

            $kegiatanlainSum = $qkegiatanlainSum->count();

            $qpublikasiCount = Formcegah::select(
                'provinsis.provinsi as provinsi',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Publikasi" THEN 1
                ELSE 0
                END) AS count
                ')
            )
                ->leftJoin('provinsis', 'formcegahs.id_provinsi', 'provinsis.id')
                ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                ->where('formcegahs.id_kabupaten', $user->KabKota)
                ->where('formcegahs.id_kecamatan','<>', '')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

            if ($request->divisi != "") {
                $qpublikasiCount = $qpublikasiCount->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qpublikasiCount = $qpublikasiCount->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qpublikasiCount = $qpublikasiCount->where('jenis', $request->jenis);
            }

            $publikasiCount = $qpublikasiCount->groupBy('provinsis.provinsi')
                ->pluck('count');

            $qpublikasiSum = Formcegah::where('bentuk', '5')
                ->where('formcegahs.id_kabupaten', $user->KabKota)
                ->where('formcegahs.id_kecamatan','<>', '')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

            if ($request->divisi != "") {
                $qpublikasiSum = $qpublikasiSum->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qpublikasiSum = $qpublikasiSum->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qpublikasiSum = $qpublikasiSum->where('jenis', $request->jenis);
            }

            $publikasiSum = $qpublikasiSum->count();

            $qrekapCegah = Formcegah::select(
                "formcegahs.created_at",
                "formcegahs.id",
                "formcegahs.no_form",
                "formcegahs.tahap",
                "bentuks.bentuk",
                "provinsis.provinsi",
                "kabupatens.kabupaten",
                "kecamatans.kecamatan",
                "kelurahan"
            )
                ->leftJoin('provinsis', 'formcegahs.id_provinsi', '=', 'provinsis.id')
                ->leftJoin('kabupatens', 'kabupatens.id', '=', 'formcegahs.id_kabupaten')
                ->leftJoin('kecamatans', 'kecamatans.id', '=', 'formcegahs.id_kecamatan')
                ->leftJoin('kelurahans', 'kelurahans.id', '=', 'formcegahs.id_kelurahan')
                ->leftJoin('bentuks', 'formcegahs.bentuk', '=', 'bentuks.id')
                ->where('formcegahs.id_kabupaten', $user->KabKota)
                ->where('formcegahs.id_kabupaten','<>', '')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at, '%Y-%m-%d'))"), [$date_start, $date_finish]);

            if ($request->divisi != "") {
                $qrekapCegah = $qrekapCegah->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qrekapCegah = $qrekapCegah->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qrekapCegah = $qrekapCegah->where('jenis', $request->jenis);
            }

            $rekapCegah = $qrekapCegah->orderBy('provinsis.provinsi', 'asc')
                ->get();
				
			$qnaskahdinasCount = Formcegah::select(
                'kecamatans.kecamatan as kecamatan',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Naskah Dinas" THEN 1
                ELSE 0
                END) AS count
                ')
            )
                ->leftJoin('kecamatans', 'formcegahs.id_kecamatan', 'kecamatans.id')
                ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                ->where('formcegahs.id_kabupaten', $user->KabKota)
                ->where('formcegahs.id_kecamatan','<>', '')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

            if ($request->divisi != "") {
                $qnaskahdinasCount = $qnaskahdinasCount->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qnaskahdinasCount = $qnaskahdinasCount->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qnaskahdinasCount = $qnaskahdinasCount->where('jenis', $request->jenis);
            }

            $naskahdinasCount = $qnaskahdinasCount->groupBy('kecamatans.kecamatan')
                ->pluck('count');

            $qnaskahdinassum = Formcegah::where('bentuk', '4')
                ->where('formcegahs.id_kabupaten', $user->KabKota)
                ->where('formcegahs.id_kecamatan','<>', '')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

            if ($request->divisi != "") {
                $qnaskahdinassum = $qnaskahdinassum->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qnaskahdinassum = $qnaskahdinassum->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qnaskahdinassum = $qnaskahdinassum->where('jenis', $request->jenis);
            }

            $naskahdinasSum = $qnaskahdinassum->count();
            // dd('hai');
            //dd(json_encode($categories),json_encode($identifikasi_kerawananCount),json_encode($pendidikanCount));
        } else if ($jabatan == 'Bawaslu Kecamatan') {
            //dd('hai');
            $kecamatan = Kecamatan::where('id', $user->Kecamatan)->first();
            $title = ' Kelurahan di Seluruh Kecamatan ' . $kecamatan->kecamatan;
            //dd($title);

            $qcategories = Formcegah::select('kelurahans.kelurahan as kelurahan')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->leftJoin('kelurahans', 'formcegahs.id_kelurahan', 'kelurahans.id')
                ->where('formcegahs.id_provinsi', $user->Provinsi);

            if ($request->divisi != "") {
                $qcategories = $qcategories->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qcategories = $qcategories->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qcategories = $qcategories->where('jenis', $request->jenis);
            }

            $categories = $qcategories->groupBy('kelurahans.kelurahan')->pluck('kelurahan');

            $categories_RI = [];
            $count_RI = [];

            $q_categories_jenis = Formcegah::select('jenis.jenis as jenis')
                ->leftJoin('jenis', 'formcegahs.jenis', 'jenis.id')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                //->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), ['2023-01-01','2023-12-01'])
                ->where('formcegahs.id_kecamatan', $user->Kecamatan)
                ->where('formcegahs.jenis','<>', '');

            $categories_jenis = $q_categories_jenis->groupBy('jenis.jenis')->get()->pluck('jenis');

            $q_jenis = Formcegah::select('jenis.jenis as jenis',DB::raw('COUNT(*) as count'))
                ->leftJoin('jenis', 'formcegahs.jenis', 'jenis.id')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                //->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), ['2023-01-01','2023-12-01'])
                ->where('formcegahs.id_kecamatan', $user->Kecamatan)
                ->where('formcegahs.jenis','<>', '');

            $count_jenis = $q_jenis->groupBy('jenis.jenis')->get()->pluck('count');

            $qtahapan_pie = Formcegah::select(
                'tahap',
                DB::raw('
                SUM(CASE
                WHEN tahap="Tahapan" THEN 1
                WHEN tahap="Non Tahapan" THEN 1
                ELSE 0
                END) AS count
                ')
            )
                ->where('formcegahs.id_kecamatan', $user->Kecamatan)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

            if ($request->divisi != "") {
                $qtahapan_pie = $qtahapan_pie->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qtahapan_pie = $qtahapan_pie->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qtahapan_pie = $qtahapan_pie->where('jenis', $request->jenis);
            }

            $tahapan_pie = $qtahapan_pie->groupBy('tahap')->get();

            $dataTahap = [];
            foreach ($tahapan_pie as $data) {
                $dataTahap[] = [
                    $data['tahap'],
                    $data['count']
                ];
            }

            $qbentuk_pie = Formcegah::select(
                'bentuks.bentuk as bentuk',
                DB::raw('
                SUM(CASE
                WHEN formcegahs.bentuk="0" THEN 1
                WHEN formcegahs.bentuk="1" THEN 1
                WHEN formcegahs.bentuk="2" THEN 1
                WHEN formcegahs.bentuk="3" THEN 1
                WHEN formcegahs.bentuk="4" THEN 1
                WHEN formcegahs.bentuk="5" THEN 1
                WHEN formcegahs.bentuk="6" THEN 1
                ELSE 0
                END) AS count
                ')
            )
                ->where('formcegahs.id_kecamatan', $user->Kecamatan)
                ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

            if ($request->divisi != "") {
                $qbentuk_pie = $qbentuk_pie->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qbentuk_pie = $qbentuk_pie->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qbentuk_pie = $qbentuk_pie->where('jenis', $request->jenis);
            }

            $bentuk_pie = $qbentuk_pie->groupBy('bentuks.bentuk')->get();

            $dataBentuk = [];
            foreach ($bentuk_pie as $data) {
                $dataBentuk[] = [
                    $data['bentuk'],
                    $data['count']
                ];
            }

            //dd(json_encode($dataBentuk));


            $qidentifikasi_kerawananCount = Formcegah::select(
                'kelurahans.kelurahan as kelurahan',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Identifikasi Kerawanan" THEN 1
                ELSE 0
                END) AS count
                ')
            )
                ->leftJoin('kelurahans', 'formcegahs.id_kelurahan', 'kelurahans.id')
                ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                ->where('formcegahs.id_kecamatan', $user->Kecamatan)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

            if ($request->divisi != "") {
                $qidentifikasi_kerawananCount = $qidentifikasi_kerawananCount->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qidentifikasi_kerawananCount = $qidentifikasi_kerawananCount->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qidentifikasi_kerawananCount = $qidentifikasi_kerawananCount->where('jenis', $request->jenis);
            }

            $identifikasi_kerawananCount = $qidentifikasi_kerawananCount->groupBy('kelurahans.kelurahan')->pluck('count');

            $qidentifikasi_kerawananSum = Formcegah::where('bentuk', '6')
                ->where('formcegahs.id_kecamatan', $user->Kecamatan)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

            if ($request->divisi != "") {
                $qidentifikasi_kerawananSum = $qidentifikasi_kerawananSum->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qidentifikasi_kerawananSum = $qidentifikasi_kerawananSum->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qidentifikasi_kerawananSum = $qidentifikasi_kerawananSum->where('jenis', $request->jenis);
            }

            $identifikasi_kerawananSum = $qidentifikasi_kerawananSum->count();

            $qpendidikanCount = Formcegah::select(
                'kelurahans.kelurahan as kelurahan',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Pendidikan" THEN 1
                ELSE 0
                END) AS count
                ')
            )
                ->leftJoin('kelurahans', 'formcegahs.id_kelurahan', 'kelurahans.id')
                ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                ->where('formcegahs.id_kecamatan', $user->Kecamatan)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

            if ($request->divisi != "") {
                $qpendidikanCount = $qpendidikanCount->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qpendidikanCount = $qpendidikanCount->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qpendidikanCount = $qpendidikanCount->where('jenis', $request->jenis);
            }

            $pendidikanCount = $qpendidikanCount->groupBy('kelurahans.kelurahan')->pluck('count');

            $qpendidikanSum = Formcegah::where('bentuk', '1')
                ->where('formcegahs.id_kecamatan', $user->Kecamatan)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

            if ($request->divisi != "") {
                $qpendidikanSum = $qpendidikanSum->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qpendidikanSum = $qpendidikanSum->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qpendidikanSum = $qpendidikanSum->where('jenis', $request->jenis);
            }

            $pendidikanSum = $qpendidikanSum->count();

            $qpartisipasiCount = Formcegah::select(
                'kelurahans.kelurahan as kelurahan',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Partisipasi Masyarakat" THEN 1
                ELSE 0
                END) AS count
                ')
            )
                ->leftJoin('kelurahans', 'formcegahs.id_kelurahan', 'kelurahans.id')
                ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                ->where('formcegahs.id_kecamatan', $user->Kecamatan)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);


            if ($request->divisi != "") {
                $qpartisipasiCount = $qpartisipasiCount->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qpartisipasiCount = $qpartisipasiCount->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qpartisipasiCount = $qpartisipasiCount->where('jenis', $request->jenis);
            }

            $partisipasiCount = $qpartisipasiCount->groupBy('kelurahans.kelurahan')->pluck('count');

            $qpartisipasiSum = Formcegah::where('bentuk', '2')
                ->where('formcegahs.id_kecamatan', $user->Kecamatan)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

            if ($request->divisi != "") {
                $qpartisipasiSum = $qpartisipasiSum->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qpartisipasiSum = $qpartisipasiSum->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qpartisipasiSum = $qpartisipasiSum->where('jenis', $request->jenis);
            }

            $partisipasiSum = $qpartisipasiSum->count();

            $qkerjasamaCount = Formcegah::select(
                'kelurahans.kelurahan as kelurahan',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Kerja sama" THEN 1
                ELSE 0
                END) AS count
                ')
            )
                ->leftJoin('kelurahans', 'formcegahs.id_kelurahan', 'kelurahans.id')
                ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                ->where('formcegahs.id_kecamatan', $user->Kecamatan)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

            if ($request->divisi != "") {
                $qkerjasamaCount = $qkerjasamaCount->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qkerjasamaCount = $qkerjasamaCount->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qkerjasamaCount = $qkerjasamaCount->where('jenis', $request->jenis);
            }

            $kerjasamaCount = $qkerjasamaCount->groupBy('kelurahans.kelurahan')->pluck('count');

            $qkerjasamaSum = Formcegah::where('bentuk', '3')
                ->where('formcegahs.id_kecamatan', $user->Kecamatan)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

            if ($request->divisi != "") {
                $qkerjasamaSum = $qkerjasamaSum->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qkerjasamaSum = $qkerjasamaSum->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qkerjasamaSum = $qkerjasamaSum->where('jenis', $request->jenis);
            }



            $kerjasamaSum = $qkerjasamaSum->count();

            $qimbauanCount = Formcegah::select(
                'kelurahans.kelurahan as kelurahan',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Imbauan" THEN 1
                ELSE 0
                END) AS count
                ')
            )
                ->leftJoin('kelurahans', 'formcegahs.id_kelurahan', 'kelurahans.id')
                ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                ->where('formcegahs.id_kecamatan', $user->Kecamatan)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

            if ($request->divisi != "") {
                $qimbauanCount = $qimbauanCount->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qimbauanCount = $qimbauanCount->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qimbauanCount = $qimbauanCount->where('jenis', $request->jenis);
            }

            $imbauanCount = $qimbauanCount->groupBy('kelurahans.kelurahan')
                ->pluck('count');

            $qimbauanSum = Formcegah::where('bentuk', '7')
                ->where('formcegahs.id_kecamatan', $user->Kecamatan)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);
            // dd($qimbauanSum->count());
            if ($request->divisi != "") {
                $qimbauanSum = $qimbauanSum->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qimbauanSum = $qimbauanSum->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qimbauanSum = $qimbauanSum->where('jenis', $request->jenis);
            }

            if ($qimbauanSum->count() == "0") {
                $qimbauanSum = $qimbauanSum->where('jenis', $request->jenis);
            }
            if ($qimbauanSum->count() == "0") {
                $imbauanSum = 0;
            } else {
                $imbauanSum == $qimbauanSum->count();
            }

            $qkegiatanlainCount = Formcegah::select(
                'kelurahans.kelurahan as kelurahan',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Kegiatan Lainnya" THEN 1
                ELSE 0
                END) AS count
                ')
            )
                ->leftJoin('kelurahans', 'formcegahs.id_kelurahan', 'kelurahans.id')
                ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                ->where('formcegahs.id_kecamatan', $user->Kecamatan)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

            if ($request->divisi != "") {
                $qkegiatanlainCount = $qkegiatanlainCount->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qkegiatanlainCount = $qkegiatanlainCount->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qkegiatanlainCount = $qkegiatanlainCount->where('jenis', $request->jenis);
            }

            $kegiatanlainCount = $qkegiatanlainCount->groupBy('kelurahans.kelurahan')->pluck('count');

            $qkegiatanlainSum = Formcegah::where('bentuk', '0')
                ->where('formcegahs.id_kecamatan', $user->Kecamatan)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

            if ($request->divisi != "") {
                $qkegiatanlainSum = $qkegiatanlainSum->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qkegiatanlainSum = $qkegiatanlainSum->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qkegiatanlainSum = $qkegiatanlainSum->where('jenis', $request->jenis);
            }

            $kegiatanlainSum = $qkegiatanlainSum->count();

            $qpublikasiCount = Formcegah::select(
                'provinsis.provinsi as provinsi',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Publikasi" THEN 1
                ELSE 0
                END) AS count
                ')
            )
                ->leftJoin('provinsis', 'formcegahs.id_provinsi', 'provinsis.id')
                ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                ->where('formcegahs.id_kecamatan', $user->Kecamatan)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

            if ($request->divisi != "") {
                $qpublikasiCount = $qpublikasiCount->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qpublikasiCount = $qpublikasiCount->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qpublikasiCount = $qpublikasiCount->where('jenis', $request->jenis);
            }

            $publikasiCount = $qpublikasiCount->groupBy('provinsis.provinsi')->pluck('count');

            $qpublikasiSum = Formcegah::where('bentuk', '5')
                ->where('formcegahs.id_kecamatan', $user->Kecamatan)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

            if ($request->divisi != "") {
                $qpublikasiSum = $qpublikasiSum->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qpublikasiSum = $qpublikasiSum->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qpublikasiSum = $qpublikasiSum->where('jenis', $request->jenis);
            }

            $publikasiSum = $qpublikasiSum->count();

            $qrekapCegah = Formcegah::select(
                "formcegahs.created_at",
                "formcegahs.id",
                "formcegahs.no_form",
                "formcegahs.tahap",
                "bentuks.bentuk",
                "provinsis.provinsi",
                "kabupatens.kabupaten",
                "kecamatans.kecamatan",
                "kelurahan"
            )
                ->leftJoin('provinsis', 'formcegahs.id_provinsi', '=', 'provinsis.id')
                ->leftJoin('kabupatens', 'kabupatens.id', '=', 'formcegahs.id_kabupaten')
                ->leftJoin('kecamatans', 'kecamatans.id', '=', 'formcegahs.id_kecamatan')
                ->leftJoin('kelurahans', 'kelurahans.id', '=', 'formcegahs.id_kelurahan')
                ->leftJoin('bentuks', 'formcegahs.bentuk', '=', 'bentuks.id')
                ->where('formcegahs.id_kecamatan', $user->Kecamatan)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at, '%Y-%m-%d'))"), [$date_start, $date_finish]);

            if ($request->divisi != "") {
                $qrekapCegah = $qrekapCegah->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qrekapCegah = $qrekapCegah->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qrekapCegah = $qrekapCegah->where('jenis', $request->jenis);
            }

            $rekapCegah = $qrekapCegah->orderBy('provinsis.provinsi', 'asc')->get();

            $qnaskahdinasCount = Formcegah::select(
                'provinsis.provinsi as provinsi',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Naskah Dinas" THEN 1
                ELSE 0
                END) AS count
                ')
            )
                ->leftJoin('provinsis', 'formcegahs.id_provinsi', 'provinsis.id')
                ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                ->where('formcegahs.id_kecamatan', $user->Kecamatan)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

            if ($request->divisi != "") {
                $qnaskahdinasCount = $qnaskahdinasCount->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qnaskahdinasCount = $qnaskahdinasCount->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qnaskahdinasCount = $qnaskahdinasCount->where('jenis', $request->jenis);
            }

            $naskahdinasCount = $qnaskahdinasCount->groupBy('provinsis.provinsi')->pluck('count');

            $qnaskahdinassum = Formcegah::where('bentuk', '4')
                ->where('formcegahs.id_kecamatan', $user->Kecamatan)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

            if ($request->divisi != "") {
                $qnaskahdinassum = $qnaskahdinassum->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qnaskahdinassum = $qnaskahdinassum->where('formcegahs.bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qnaskahdinassum = $qnaskahdinassum->where('jenis', $request->jenis);
            }

            $naskahdinasSum = $qnaskahdinassum->count();

            //dd(json_encode($nonTahapanCount),json_encode($tahapanCount));
        } else {
            // dd('hai');
            $categories = [];

            $categories_RI = [];
            $count_RI = [];

            $categories_jenis = [];
            $count_jenis = [];

            $identifikasi_kerawananCount = [];
            $identifikasi_kerawananSum = [];

            $pendidikanCount = [];
            $pendidikanSum = [];

            $partisipasiCount = [];
            $partisipasiSum = [];

            $kerjasamaCount = [];
            $kerjasamaSum = [];

            $imbauanCount = [];
            $imbauanSum = [];

            $kegiatanlainCount = [];
            $kegiatanlainSum = [];

            $publikasiCount = [];
            $publikasiSum = [];

            $rekapCegah = [];
			
			$naskahdinasCount = [];
			$naskahdinasSum = [];

            //dd(json_encode($tahapanCount));
        }
        //dd($rekapCegah);
        $form = $rekapCegah;
        //dd($userProv);

        return view('graph.__index', compact(
            'userProv',
            'jabatan',
            'form',
            'categories',
            'categories_RI',
            'count_RI',
            'categories_jenis',
            'count_jenis',
            'identifikasi_kerawananCount',
            'pendidikanCount',
            'title',
            'identifikasi_kerawananSum',
            'pendidikanSum',
            'partisipasiCount',
            'partisipasiSum',
            'kerjasamaCount',
            'kerjasamaSum',
            'imbauanCount',
            'imbauanSum',
            'kegiatanlainCount',
            'kegiatanlainSum',
            'publikasiCount',
            'publikasiSum',
			'naskahdinasCount',
			'naskahdinasSum',
            'date_start',
            'date_finish',
            'dataTahap',
            'dataBentuk',
            'dropdowns'

        ));
    }

    public function indexDetail($name, $date_start, $date_finish)
    {
        //dd($name,$date_start,$date_finish);
        $idUser = '112';

        $user = User::where('id', Auth::user()->id)->first();
        $jabatan = $user->Jabatan;
        //dd($jabatan);   
        if ($jabatan == 'Sekretariat Bawaslu Provinsi') {
            //serach by name
            $qProvinsi = Provinsi::where('provinsi', $name)->first();
            //dd($qProvinsi->id);

            $title = ' Seluruh Provinsi ' . $name;
            $categories = Formcegah::select('bentuks.bentuk as nama_bentuk')
                ->where('id_provinsi', $qProvinsi->id)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                ->leftJoin('provinsis', 'formcegahs.id_provinsi', 'provinsis.id')
                ->groupBy('bentuks.bentuk')
                ->pluck('nama_bentuk');

            //dd($categories);

            $identifikasi_kerawananCount = Formcegah::select(
                'bentuks.bentuk as nama_bentuk',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Identifikasi Kerawanan" THEN 1
                ELSE 0
                END) AS count
                ')
            )
                ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                ->where('id_provinsi', $qProvinsi->id)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->groupBy('bentuks.bentuk')
                ->pluck('count');

            $pendidikanCount = Formcegah::select(
                'bentuks.bentuk as nama_bentuk',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Pendidikan" THEN 1
                ELSE 0
                END) AS count
                ')
            )
                ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                ->where('id_provinsi', $qProvinsi->id)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->groupBy('bentuks.bentuk')
                ->pluck('count');

            $partisipasiCount = Formcegah::select(
                'bentuks.bentuk as nama_bentuk',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Partisipasi Masyarakat" THEN 1
                ELSE 0
                END) AS count
                ')
            )
                ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                ->where('id_provinsi', $qProvinsi->id)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->groupBy('bentuks.bentuk')
                ->pluck('count');

            $kerjasamaCount = Formcegah::select(
                'bentuks.bentuk as nama_bentuk',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Kerja Sama" THEN 1
                ELSE 0
                END) AS count
                ')
            )
                ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                ->where('id_provinsi', $qProvinsi->id)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->groupBy('bentuks.bentuk')
                ->pluck('count');

            $imbauanCount = Formcegah::select(
                'bentuks.bentuk as nama_bentuk',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Imbauan" THEN 1
                ELSE 0
                END) AS count
                ')
            )
                ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                ->where('id_provinsi', $qProvinsi->id)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->groupBy('bentuks.bentuk')
                ->pluck('count');

            $kegiatanlainCount = Formcegah::select(
                'bentuks.bentuk as nama_bentuk',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Kegiatan Lainnya" THEN 1
                ELSE 0
                END) AS count
                ')
            )
                ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                ->where('id_provinsi', $qProvinsi->id)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->groupBy('bentuks.bentuk')
                ->pluck('count');

            $publikasiCount = Formcegah::select(
                'bentuks.bentuk as nama_bentuk',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Publikasi" THEN 1
                ELSE 0
                END) AS count
                ')
            )
                ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                ->where('id_provinsi', $qProvinsi->id)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->groupBy('bentuks.bentuk')
                ->pluck('count');
            //dd(json_encode($categories),json_encode($publikasiSum));
        } else if ($jabatan == 'Ketua atau Anggota Bawaslu Provinsi') {
            if ($user->Provinsi != null) {
                //serach by name
                $qKabupaten = Kabupaten::where('kabupaten', $name)->first();
                //dd($qKabupaten->id);

                $provinsi = Provinsi::where('id', $user->Provinsi)->first();
                $title = ' di ' . $name;

                $categories = Formcegah::select('bentuks.bentuk as nama_bentuk')
                    ->where('id_kabupaten', $qKabupaten->id)
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                    ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                    ->leftJoin('kabupatens', 'formcegahs.id_kabupaten', 'kabupatens.id')
                    ->groupBy('bentuks.bentuk')
                    ->pluck('nama_bentuk');

                //dd($categories);

                $identifikasi_kerawananCount = Formcegah::select(
                    'bentuks.bentuk as nama_bentuk',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Identifikasi Kerawanan" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                    ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                    ->where('id_kabupaten', $qKabupaten->id)
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                    ->groupBy('bentuks.bentuk')
                    ->pluck('count');

                $pendidikanCount = Formcegah::select(
                    'bentuks.bentuk as nama_bentuk',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Pendidikan" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                    ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                    ->where('id_kabupaten', $qKabupaten->id)
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                    ->groupBy('bentuks.bentuk')
                    ->pluck('count');

                $partisipasiCount = Formcegah::select(
                    'bentuks.bentuk as nama_bentuk',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Partisipasi Masyarakat" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                    ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                    ->where('id_kabupaten', $qKabupaten->id)
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                    ->groupBy('bentuks.bentuk')
                    ->pluck('count');

                $kerjasamaCount = Formcegah::select(
                    'bentuks.bentuk as nama_bentuk',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Kerja Sama" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                    ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                    ->where('id_kabupaten', $qKabupaten->id)
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                    ->groupBy('bentuks.bentuk')
                    ->pluck('count');

                $imbauanCount = Formcegah::select(
                    'bentuks.bentuk as nama_bentuk',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Imbauan" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                    ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                    ->where('id_kabupaten', $qKabupaten->id)
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                    ->groupBy('bentuks.bentuk')
                    ->pluck('count');

                $kegiatanlainCount = Formcegah::select(
                    'bentuks.bentuk as nama_bentuk',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Kegiatan Lainnya" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                    ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                    ->where('id_kabupaten', $qKabupaten->id)
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                    ->groupBy('bentuks.bentuk')
                    ->pluck('count');

                $publikasiCount = Formcegah::select(
                    'bentuks.bentuk as nama_bentuk',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Publikasi" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                    ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                    ->where('id_kabupaten', $qKabupaten->id)
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                    ->groupBy('bentuks.bentuk')
                    ->pluck('count');
                    //dd($publikasiCount);
            } else {
                //serach by name
                $qProvinsi = Provinsi::where('provinsi', $name)->first();
                //dd($qProvinsi->id);

                $title = ' Seluruh Provinsi ' . $name;
                $categories = Formcegah::select('bentuks.bentuk as nama_bentuk')
                    ->where('id_provinsi', $qProvinsi->id)
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                    ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                    ->leftJoin('provinsis', 'formcegahs.id_provinsi', 'provinsis.id')
                    ->groupBy('bentuks.bentuk')
                    ->pluck('nama_bentuk');

                //dd($categories);

                $identifikasi_kerawananCount = Formcegah::select(
                    'bentuks.bentuk as nama_bentuk',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Identifikasi Kerawanan" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                    ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                    ->where('id_provinsi', $qProvinsi->id)
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                    ->groupBy('bentuks.bentuk')
                    ->pluck('count');

                $pendidikanCount = Formcegah::select(
                    'bentuks.bentuk as nama_bentuk',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Pendidikan" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                    ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                    ->where('id_provinsi', $qProvinsi->id)
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                    ->groupBy('bentuks.bentuk')
                    ->pluck('count');

                $partisipasiCount = Formcegah::select(
                    'bentuks.bentuk as nama_bentuk',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Partisipasi Masyarakat" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                    ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                    ->where('id_provinsi', $qProvinsi->id)
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                    ->groupBy('bentuks.bentuk')
                    ->pluck('count');

                $kerjasamaCount = Formcegah::select(
                    'bentuks.bentuk as nama_bentuk',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Kerja Sama" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                    ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                    ->where('id_provinsi', $qProvinsi->id)
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                    ->groupBy('bentuks.bentuk')
                    ->pluck('count');

                $imbauanCount = Formcegah::select(
                    'bentuks.bentuk as nama_bentuk',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Imbauan" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                    ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                    ->where('id_provinsi', $qProvinsi->id)
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                    ->groupBy('bentuks.bentuk')
                    ->pluck('count');

                $kegiatanlainCount = Formcegah::select(
                    'bentuks.bentuk as nama_bentuk',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Kegiatan Lainnya" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                    ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                    ->where('id_provinsi', $qProvinsi->id)
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                    ->groupBy('bentuks.bentuk')
                    ->pluck('count');

                $publikasiCount = Formcegah::select(
                    'bentuks.bentuk as nama_bentuk',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Publikasi" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                    ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
                    ->where('id_provinsi', $qProvinsi->id)
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                    ->groupBy('bentuks.bentuk')
                    ->pluck('count');
                //dd(json_encode($categories),json_encode($publikasiSum));
            }
        } else {
            return redirect('/graph');
        }

        //dd($qCegah->count());

        return view('graph.detail', compact(
            'categories',
            'title',
            'identifikasi_kerawananCount',
            'pendidikanCount',
            'partisipasiCount',
            'kerjasamaCount',
            'imbauanCount',
            'kegiatanlainCount',
            'publikasiCount',
            'date_start',
            'date_finish',
        ));
    }

    public function fetchWilayah(Request $request)
    {
        //dd($request->all());

        if ($request->val_wilayah == 'provinsi') {
            $data['states'] = Provinsi::orderBy('provinsi', 'asc')->get(["provinsi as name", "id"]);
        } else if ($request->val_wilayah == 'kota') {
            $data['states'] = Kabupaten::orderBy('kabupaten', 'asc')->get(["kabupaten as name", "id"]);
        } else {
            $data['states'] = array(
                'name' => 'all',
                'id' => '-',
            );
        }
        //dd(response()->json($data)) ;
        return response()->json($data);
    }

    public function indexJenis(Request $request){
        // dd('hai');

        $idUser = '1121';
        $userProv = 'non pusat';

        // dd($idUser);

        $dropdowns = array();
        
        $dropdowns['jenis'] = Formcegah::select('jenis.jenis', 'formcegahs.jenis as id_jenis')
            ->LeftJoin('jenis', 'formcegahs.jenis', 'jenis.id')
            ->orderBy('jenis.jenis', 'asc')
            ->groupBy('jenis.jenis')
            ->get();

        $user = User::where('id', Auth::user()->id)->first();
        $jabatan = $user->Jabatan;

        if ($request->date_finish == "") {
            $now = Carbon::now();
            $date_start = $now->firstOfMonth()->format('Y-m-d');
            $date_finish = $now->endOfMonth()->format('Y-m-d');

            // $date_start = '2023-11-17';
            // $date_finish = '2023-11-17';

        // Mengatur tanggal mulai sebagai Senin minggu ini
        // $date_start = $now->modify('this week')->format('Y-m-d');

        // Menambahkan 3 hari ke tanggal mulai untuk mendapatkan tanggal akhir
        // $date_finish = $now->modify('+1 days')->format('Y-m-d');



        } else {
            $date_start = $request->date_start;
            $date_finish = $request->date_finish;
        }

        //   dd($user,$jabatan,$date_start,$date_finish);

        if ($jabatan == 'Sekretariat Bawaslu Provinsi') {
            //dd('hai');
            $userProv = 'non pusat';
            $title = ' Seluruh Provinsi';

            $q_categories_jenis = Formcegah::select('jenis.jenis as jenis')
                ->leftJoin('jenis', 'formcegahs.jenis', 'jenis.id')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->where('formcegahs.jenis','<>', '');

            $categories_jenis = $q_categories_jenis->groupBy('jenis.jenis')->get()->pluck('jenis');

            $q_jenis = Formcegah::select('jenis.jenis as jenis',DB::raw('COUNT(*) as count'))
                ->leftJoin('jenis', 'formcegahs.jenis', 'jenis.id')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->where('formcegahs.jenis','<>', '');

            $count_jenis = $q_jenis->groupBy('jenis.jenis')->get()->pluck('count');
        
        } else if ($jabatan == 'Ketua atau Anggota Bawaslu Provinsi') {
            //dd($user->Provinsi);
            if ($user->Provinsi != null) {
                //dd('hai');
                $provinsi = Provinsi::where('id', $user->Provinsi)->first();
                $title = ' Kabupaten/Kota di Seluruh Provinsi ' . $provinsi->provinsi;
                //dd($title);

                $q_categories_jenis = Formcegah::select('jenis.jenis as jenis')
                    ->leftJoin('jenis', 'formcegahs.jenis', 'jenis.id')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                    //->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), ['2023-01-01','2023-12-01'])
                    ->where('formcegahs.id_provinsi', $user->Provinsi)
                    ->where('formcegahs.jenis','<>', '');

                $categories_jenis = $q_categories_jenis->groupBy('jenis.jenis')->get()->pluck('jenis');

                $q_jenis = Formcegah::select('jenis.jenis as jenis',DB::raw('COUNT(*) as count'))
                    ->leftJoin('jenis', 'formcegahs.jenis', 'jenis.id')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                    //->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), ['2023-01-01','2023-12-01'])
                    ->where('formcegahs.id_provinsi', $user->Provinsi)
                    ->where('formcegahs.jenis','<>', '');

                $count_jenis = $q_jenis->groupBy('jenis.jenis')->get()->pluck('count');

                //dd(json_encode($categories),json_encode($identifikasi_kerawananCount),json_encode($pendidikanCount));
            } else {
                //dd('hai');
                $userProv = 'pusat';
                $title = ' Seluruh Provinsi';

                $q_categories_jenis = Formcegah::select('jenis.jenis as jenis')
                    ->leftJoin('jenis', 'formcegahs.jenis', 'jenis.id')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                    //->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), ['2023-01-01','2023-12-01'])
                    ->where('formcegahs.jenis','<>', '');

                $categories_jenis = $q_categories_jenis->groupBy('jenis.jenis')->get()->pluck('jenis');

                $q_jenis = Formcegah::select('jenis.jenis as jenis',DB::raw('COUNT(*) as count'))
                    ->leftJoin('jenis', 'formcegahs.jenis', 'jenis.id')
                    ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                    //->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), ['2023-01-01','2023-12-01'])
                    ->where('formcegahs.jenis','<>', '');

                $count_jenis = $q_jenis->groupBy('jenis.jenis')->get()->pluck('count');

                //dd(json_encode($categories));
            }
        } else if ($jabatan == 'Ketua atau Anggota Bawaslu Kabupaten/Kota') {
            //dd("hai");
            $userProv = 'non pusat';
            $KabKota = Kabupaten::where('id', $user->KabKota)->first();
            $title = ' Kecamatan di Seluruh ' . $KabKota->kabupaten;
            //dd($title);

            $q_categories_jenis = Formcegah::select('jenis.jenis as jenis')
                ->leftJoin('jenis', 'formcegahs.jenis', 'jenis.id')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->where('formcegahs.id_kabupaten', $user->KabKota)
                ->where('formcegahs.jenis','<>', '');

            $categories_jenis = $q_categories_jenis->groupBy('jenis.jenis')->get()->pluck('jenis');

            $q_jenis = Formcegah::select('jenis.jenis as jenis',DB::raw('COUNT(*) as count'))
                ->leftJoin('jenis', 'formcegahs.jenis', 'jenis.id')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->where('formcegahs.id_kabupaten', $user->KabKota)
                ->where('formcegahs.jenis','<>', '');

            $count_jenis = $q_jenis->groupBy('jenis.jenis')->get()->pluck('count');

            //dd(json_encode($categories),json_encode($identifikasi_kerawananCount),json_encode($pendidikanCount));
        } else if ($jabatan == 'Bawaslu Kecamatan') {
            //dd('hai');
            $kecamatan = Kecamatan::where('id', $user->Kecamatan)->first();
            $title = ' Kelurahan di Seluruh Kecamatan ' . $kecamatan->kecamatan;
            //dd($title);

            $q_categories_jenis = Formcegah::select('jenis.jenis as jenis')
                ->leftJoin('jenis', 'formcegahs.jenis', 'jenis.id')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->where('formcegahs.id_kecamatan', $user->Kecamatan)
                ->where('formcegahs.jenis','<>', '');

            $categories_jenis = $q_categories_jenis->groupBy('jenis.jenis')->get()->pluck('jenis');

            $q_jenis = Formcegah::select('jenis.jenis as jenis',DB::raw('COUNT(*) as count'))
                ->leftJoin('jenis', 'formcegahs.jenis', 'jenis.id')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->where('formcegahs.id_kecamatan', $user->Kecamatan)
                ->where('formcegahs.jenis','<>', '');

            $count_jenis = $q_jenis->groupBy('jenis.jenis')->get()->pluck('count');

            //dd(json_encode($nonTahapanCount),json_encode($tahapanCount));
        } else {
            // dd('hai');
            $categories_jenis = [];
            $count_jenis = [];

            //dd(json_encode($tahapanCount));
        }
        return view('graph.index_jenis', compact(
            'categories_jenis',
            'count_jenis',
            'title',
            'date_start',
            'date_finish'
        ));
    }


    public function getAllsums(Request $request)
    {
        $user = User::where('id', Auth::user()->id)->first();
        $jabatan = $user->Jabatan;
        
        if ($request->date_finish == "") {
            $now = Carbon::now();
            //$date_start = $now->firstOfMonth()->format('Y-m-d');
            //$date_finish = $now->endOfMonth()->format('Y-m-d');
            $date_start = '2024-01-01';
            $date_finish = date('Y-m-d');

        } else {
            $date_start = $request->date_start;
            $date_finish = $request->date_finish;
        }

        //dd($user,$jabatan,$date_start,$date_finish);
        if ($jabatan == 'Sekretariat Bawaslu Provinsi') {
            //dd('hai sekretariat');
            $userProv = 'non pusat';
            $title = ' Seluruh Provinsi';
            $qFormCegah = Formcegah::where('bentuk', $request->bentuk)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->where('formcegahs.id_provinsi', '<>', '');

            if ($request->divisi != "") {
                $qFormCegah = $qFormCegah->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qFormCegah = $qFormCegah->where('bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qFormCegah = $qFormCegah->where('jenis', $request->jenis);
            }

            if ($request->pilih_wilayah == "provinsi") {
                $qFormCegah = $qFormCegah->where('formcegahs.id_provinsi', $request->wilayah_dropdown);
            }

            if ($request->pilih_wilayah == "kota") {
                $qFormCegah = $qFormCegah->where('formcegahs.id_kabupaten', $request->wilayah_dropdown);
            } 

            $qFormCegah = $qFormCegah->where('formcegahs.wp_id', $request->wp_id);
            

            $qFormCegah = $qFormCegah->count(); 
            //var_dump($qFormCegah);


        } else if ($jabatan == 'Ketua atau Anggota Bawaslu Provinsi') {
            if ($user->Provinsi != null) {
                //dd('provinsi');
                $provinsi = Provinsi::where('id', $user->Provinsi)->first();
                $title = ' Kabupaten/Kota di Seluruh Provinsi ' . $provinsi->provinsi;
            } else {
                $provinsi = Provinsi::where('id', $user->Provinsi)->first();
                $title = ' Seluruh Provinsi';
            }
            $qFormCegah = Formcegah::where('bentuk', $request->bentuk)
                ->where('formcegahs.id_provinsi', $user->Provinsi)
                ->where('formcegahs.id_kabupaten', '<>', '')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);
                if ($request->divisi != "") {
                    $qFormCegah = $qFormCegah->where('id_divisi', $request->divisi);
                }
            
                if ($request->bentuk != "") {
                    $qFormCegah = $qFormCegah->where('formcegahs.bentuk', $request->bentuk);
                }
            
                if ($request->jenis != "") {
                    $qFormCegah = $qFormCegah->where('jenis', $request->jenis);
                }

                $qFormCegah = $qFormCegah->where('formcegahs.wp_id', $request->wp_id);
            
                $qFormCegah = $qFormCegah->count(); 

        } else if ($jabatan == 'Ketua atau Anggota Bawaslu Kabupaten/Kota') {
            $userProv = 'non pusat';
            $KabKota = Kabupaten::where('id', $user->KabKota)->first();
            $title = ' Kecamatan di Seluruh ' . $KabKota->kabupaten;
            $qFormCegah = Formcegah::where('bentuk', $request->bentuk)
                ->where('formcegahs.id_kabupaten', $user->KabKota)
                ->where('formcegahs.id_kecamatan','<>', '')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

                if ($request->divisi != "") {
                    $qFormCegah = $qFormCegah->where('id_divisi', $request->divisi);
                }
            
                if ($request->bentuk != "") {
                    $qFormCegah = $qFormCegah->where('formcegahs.bentuk', $request->bentuk);
                }
            
                if ($request->jenis != "") {
                    $qFormCegah = $qFormCegah->where('jenis', $request->jenis);
                }

                $qFormCegah = $qFormCegah->where('formcegahs.wp_id', $request->wp_id);
            
                $qFormCegah = $qFormCegah->count(); 

        } else if ($jabatan == 'Bawaslu Kecamatan') {
            $qFormCegah = Formcegah::where('bentuk', $request->bentuk)
                ->where('formcegahs.id_kecamatan', $user->Kecamatan)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);
                if ($request->divisi != "") {
                    $qFormCegah = $qFormCegah->where('id_divisi', $request->divisi);
                }
            
                if ($request->bentuk != "") {
                    $qFormCegah = $qFormCegah->where('formcegahs.bentuk', $request->bentuk);
                }
            
                if ($request->jenis != "") {
                    $qFormCegah = $qFormCegah->where('jenis', $request->jenis);
                }

                $qFormCegah = $qFormCegah->where('formcegahs.wp_id', $request->wp_id);
            
                $qFormCegah = $qFormCegah->count();                 
        }

        return $qFormCegah;  
    }
    public function filterAllsums(Request $request)
    {
        

        $user = User::where('id', Auth::user()->id)->first();
        $jabatan = $user->Jabatan;  
        
        if ($request->date_finish == "") {
            $now = Carbon::now();
            //$date_start = $now->firstOfMonth()->format('Y-m-d');
            //$date_finish = $now->endOfMonth()->format('Y-m-d');
            $date_start = '2024-01-01';
            $date_finish = date('Y-m-d');

        } else {
            $date_start = $request->date_start;
            $date_finish = $request->date_finish;
        }

        //dd($user,$jabatan,$date_start,$request->date_finish);

        if ($jabatan == 'Sekretariat Bawaslu Provinsi') {
            //dd('hai sekretariat');
            $userProv = 'non pusat';
            $title = ' Seluruh Provinsi';
            $qFormCegah = Formcegah::where('bentuk', $request->bentuk)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->where('formcegahs.id_provinsi', '<>', '');

            if ($request->divisi != "") {
                $qFormCegah = $qFormCegah->where('id_divisi', $request->divisi);
            }

            if ($request->bentuk != "") {
                $qFormCegah = $qFormCegah->where('bentuk', $request->bentuk);
            }

            if ($request->jenis != "") {
                $qFormCegah = $qFormCegah->where('jenis', $request->jenis);
            }

            if ($request->pilih_wilayah == "provinsi") {
                $qFormCegah = $qFormCegah->where('formcegahs.id_provinsi', $request->wilayah_dropdown);
            }

            if ($request->pilih_wilayah == "kota") {
                $qFormCegah = $qFormCegah->where('formcegahs.id_kabupaten', $request->wilayah_dropdown);
            } 

            $qFormCegah = $qFormCegah->where('formcegahs.wp_id', $request->wp_id);

            $qFormCegah = $qFormCegah->count(); 

        } else if ($jabatan == 'Ketua atau Anggota Bawaslu Provinsi') {
            if ($user->Provinsi != null) {
                //dd('provinsi');
                $provinsi = Provinsi::where('id', $user->Provinsi)->first();
                $title = ' Kabupaten/Kota di Seluruh Provinsi ' . $provinsi->provinsi;
            } else {
                $provinsi = Provinsi::where('id', $user->Provinsi)->first();
                $title = ' Seluruh Provinsi';
            }
            $qFormCegah = Formcegah::where('bentuk', $request->bentuk)
                ->where('formcegahs.id_provinsi', $user->Provinsi)
                ->where('formcegahs.id_kabupaten', '<>', '')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);
                if ($request->divisi != "") {
                    $qFormCegah = $qFormCegah->where('id_divisi', $request->divisi);
                }
            
                if ($request->bentuk != "") {
                    $qFormCegah = $qFormCegah->where('formcegahs.bentuk', $request->bentuk);
                }
            
                if ($request->jenis != "") {
                    $qFormCegah = $qFormCegah->where('jenis', $request->jenis);
                }

                $qFormCegah = $qFormCegah->where('formcegahs.wp_id', $request->wp_id);
            
                $qFormCegah = $qFormCegah->count(); 

        } else if ($jabatan == 'Ketua atau Anggota Bawaslu Kabupaten/Kota') {
            $userProv = 'non pusat';
            $KabKota = Kabupaten::where('id', $user->KabKota)->first();
            $title = ' Kecamatan di Seluruh ' . $KabKota->kabupaten;
            $qFormCegah = Formcegah::where('bentuk', $request->bentuk)
                ->where('formcegahs.id_kabupaten', $user->KabKota)
                ->where('formcegahs.id_kecamatan','<>', '')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);

                if ($request->divisi != "") {
                    $qFormCegah = $qFormCegah->where('id_divisi', $request->divisi);
                }
            
                if ($request->bentuk != "") {
                    $qFormCegah = $qFormCegah->where('formcegahs.bentuk', $request->bentuk);
                }
            
                if ($request->jenis != "") {
                    $qFormCegah = $qFormCegah->where('jenis', $request->jenis);
                }
            
                $qFormCegah = $qFormCegah->where('formcegahs.wp_id', $request->wp_id);

                $qFormCegah = $qFormCegah->count(); 

        } else if ($jabatan == 'Bawaslu Kecamatan') {
            $qFormCegah = Formcegah::where('bentuk', $request->bentuk)
                ->where('formcegahs.id_kecamatan', $user->Kecamatan)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish]);
                if ($request->divisi != "") {
                    $qFormCegah = $qFormCegah->where('id_divisi', $request->divisi);
                }
            
                if ($request->bentuk != "") {
                    $qFormCegah = $qFormCegah->where('formcegahs.bentuk', $request->bentuk);
                }
            
                if ($request->jenis != "") {
                    $qFormCegah = $qFormCegah->where('jenis', $request->jenis);
                }
            
                $qFormCegah = $qFormCegah->where('formcegahs.wp_id', $request->wp_id);

                $qFormCegah = $qFormCegah->count();                 
        }

        return $qFormCegah; 
          
    }

    public function dataTahap(Request $request)
    {
        $user = User::where('id', Auth::user()->id)->first();
        $jabatan = $user->Jabatan;
        
        if ($request->date_finish == "") {
            $now = Carbon::now();
            //$date_start = $now->firstOfMonth()->format('Y-m-d');
            //$date_finish = $now->endOfMonth()->format('Y-m-d');
            $date_start = '2024-01-01';
            $date_finish = date('Y-m-d');

        } else {
            $date_start = $request->date_start;
            $date_finish = $request->date_finish;
        }

        $qtahapan_pie = Formcegah::select(
            'tahap',
            DB::raw('
            SUM(CASE
            WHEN tahap="Tahapan" THEN 1
            WHEN tahap="Non Tahapan" THEN 1
            ELSE 0
            END) AS count
            ')
        )
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->where('formcegahs.id_provinsi', '<>', '');

            // dd( $qtahapan_pie);

        if ($request->divisi != "") {
            $qtahapan_pie = $qtahapan_pie->where('id_divisi', $request->divisi);
        }

        if ($request->bentuk != "") {
            $qtahapan_pie = $qtahapan_pie->where('bentuk', $request->bentuk);
        }

        if ($request->jenis != "") {
            $qtahapan_pie = $qtahapan_pie->where('jenis', $request->jenis);
        }

        if ($request->pilih_wilayah == "provinsi") {
            $qtahapan_pie = $qtahapan_pie->where('formcegahs.id_provinsi', $request->wilayah_dropdown);
        }

        if ($request->pilih_wilayah == "kota") {
            $qtahapan_pie = $qtahapan_pie->where('formcegahs.id_kabupaten', $request->wilayah_dropdown);
        }

        $qtahapan_pie = $qtahapan_pie->where('formcegahs.wp_id', $request->wp_id);

        $tahapan_pie = $qtahapan_pie->groupBy('tahap')->get();

        $dataTahap = [];
        foreach ($tahapan_pie as $data) {
            $dataTahap[] = [
                $data['tahap'],
                floatval($data['count'])
            ];
        }
        return json_encode($dataTahap);
        //return $dataTahap;
    }

    public function dataBentuk(Request $request)
    {
        $user = User::where('id', Auth::user()->id)->first();
        $jabatan = $user->Jabatan;

        if ($request->date_finish == "") {
            $now = Carbon::now();
            //$date_start = $now->firstOfMonth()->format('Y-m-d');
            //$date_finish = $now->endOfMonth()->format('Y-m-d');
            $date_start = '2024-01-01';
            $date_finish = date('Y-m-d');
            
        } else {
            $date_start = $request->date_start;
            $date_finish = $request->date_finish;
        }

        $qbentuk_pie = Formcegah::select(
            'bentuks.bentuk as bentuk',
            DB::raw('
            SUM(CASE
            WHEN formcegahs.bentuk="0" THEN 1
            WHEN formcegahs.bentuk="1" THEN 1
            WHEN formcegahs.bentuk="2" THEN 1
            WHEN formcegahs.bentuk="3" THEN 1
            WHEN formcegahs.bentuk="4" THEN 1
            WHEN formcegahs.bentuk="5" THEN 1
            WHEN formcegahs.bentuk="6" THEN 1
            ELSE 0
            END) AS count
            ')
        )
            ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->where('formcegahs.id_provinsi', '<>', '');

        if ($request->divisi != "") {
            $qbentuk_pie = $qbentuk_pie->where('id_divisi', $request->divisi);
        }

        if ($request->bentuk != "") {
            $qbentuk_pie = $qbentuk_pie->where('formcegahs.bentuk', $request->bentuk);
        }

        if ($request->jenis != "") {
            $qbentuk_pie = $qbentuk_pie->where('jenis', $request->jenis);
        }

        if ($request->pilih_wilayah == "provinsi") {
            $qbentuk_pie = $qbentuk_pie->where('formcegahs.id_provinsi', $request->wilayah_dropdown);
        }

        if ($request->pilih_wilayah == "kota") {
            $qbentuk_pie = $qbentuk_pie->where('formcegahs.id_kabupaten', $request->wilayah_dropdown);
        }

        $qbentuk_pie = $qbentuk_pie->where('formcegahs.wp_id', $request->wp_id);

        $bentuk_pie = $qbentuk_pie->groupBy('bentuks.bentuk')->get();

        $dataBentuk = [];
        foreach ($bentuk_pie as $data) {
            $dataBentuk[] = [
                $data['bentuk'],
                floatval($data['count'])
            ];
        }

        return json_encode($dataBentuk);
    }

    public function dataBentukRI(Request $request)
    {
        $user = User::where('id', Auth::user()->id)->first();
        $jabatan = $user->Jabatan;

        if ($request->date_finish == "") {
            $now = Carbon::now();
            //$date_start = $now->firstOfMonth()->format('Y-m-d');
            //$date_finish = $now->endOfMonth()->format('Y-m-d');
            $date_start = '2024-01-01';
            $date_finish = date('Y-m-d');

        } else {
            $date_start = $request->date_start;
            $date_finish = $request->date_finish;
        }

        $q_categories_RI = Formcegah::select('bentuks.bentuk as bentuk')
            ->join('bentuks', 'formcegahs.bentuk', 'bentuks.id')
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            //->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), ['2023-01-01','2023-12-01'])
            //->where('formcegahs.id_provinsi', '')
            ->where('formcegahs.wp_id', $request->wp_id);

        $categories_RI = $q_categories_RI->groupBy('bentuks.bentuk')->get()->pluck('bentuk');
        
        $q_RI = Formcegah::select('bentuks.bentuk',DB::raw('COUNT(*) as count'))
        ->join('bentuks', 'formcegahs.bentuk', 'bentuks.id')
        ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
        //->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), ['2023-01-01','2023-12-01'])
        //->where('formcegahs.id_provinsi', '')
        ->where('formcegahs.wp_id', $request->wp_id);

        $count_RI = $q_RI->groupBy('bentuks.bentuk')->get()->pluck('count');

        $response = [$categories_RI,$count_RI];

        return json_encode($response);

    }

    public function dataJenis(Request $request)
    {
        $user = User::where('id', Auth::user()->id)->first();
        $jabatan = $user->Jabatan;

        if ($request->date_finish == "") {
            $now = Carbon::now();
            //$date_start = $now->firstOfMonth()->format('Y-m-d');
            //$date_finish = $now->endOfMonth()->format('Y-m-d');
            $date_start = '2024-01-01';
            $date_finish = '2024-03-31';

        } else {
            $date_start = $request->date_start;
            $date_finish = $request->date_finish;
        }

        $q_categories_jenis = Formcegah::select('jenis.jenis as jenis')
            ->leftJoin('jenis', 'formcegahs.jenis', 'jenis.id')
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            //->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), ['2023-01-01','2023-12-01'])
            ->where('formcegahs.jenis','<>', '')
            ->where('formcegahs.wp_id', $request->wp_id);

        $categories_jenis = $q_categories_jenis->groupBy('jenis.jenis')->get()->pluck('jenis');

        $q_jenis = Formcegah::select('jenis.jenis as jenis',DB::raw('COUNT(*) as count'))
            ->leftJoin('jenis', 'formcegahs.jenis', 'jenis.id')
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            //->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), ['2023-01-01','2023-12-01'])
            ->where('formcegahs.jenis','<>', '')
            ->where('formcegahs.wp_id', $request->wp_id);

        $count_jenis = $q_jenis->groupBy('jenis.jenis')->get()->pluck('count');

        $response = [$categories_jenis,$count_jenis];

        return json_encode($response);

    }

    public function dataPencegahan(Request $request)
    {
        $user = User::where('id', Auth::user()->id)->first();
        $jabatan = $user->Jabatan;

        if ($request->date_finish == "") {
            $now = Carbon::now();
            //$date_start = $now->firstOfMonth()->format('Y-m-d');
            //$date_finish = $now->endOfMonth()->format('Y-m-d');
            $date_start = '2024-01-01';
            $date_finish = '2024-03-31';

        } else {
            $date_start = $request->date_start;
            $date_finish = $request->date_finish;
        }

        $qcategories = Formcegah::select('provinsis.provinsi as provinsi')
        ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
        ->where('formcegahs.id_provinsi', '<>', '')
        ->leftJoin('provinsis', 'formcegahs.id_provinsi', 'provinsis.id');

        // dd($qcategories);

        if ($request->divisi != "") {
            $qcategories = $qcategories->where('id_divisi', $request->divisi);
        }

        if ($request->bentuk != "") {
            $qcategories = $qcategories->where('bentuk', $request->bentuk);
        }

        if ($request->jenis != "") {
            $qcategories = $qcategories->where('jenis', $request->jenis);
        }

        if ($request->pilih_wilayah == "provinsi") {
            $qcategories = $qcategories->where('formcegahs.id_provinsi', $request->wilayah_dropdown);
        }

        if ($request->pilih_wilayah == "kota") {
            $qcategories = $qcategories->where('formcegahs.id_kabupaten', $request->wilayah_dropdown);
        }

        $categories = $qcategories->groupBy('provinsis.provinsi')->pluck('provinsi');

        $qidentifikasi_kerawananCount = Formcegah::select(
            'provinsis.provinsi as provinsi',
            DB::raw('
            SUM(CASE
            WHEN bentuks.bentuk="Identifikasi Kerawanan" THEN 1
            ELSE 0
            END) AS count
            ')
        )
            ->leftJoin('provinsis', 'formcegahs.id_provinsi', 'provinsis.id')
            ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->where('formcegahs.id_provinsi', '<>', '');

        if ($request->divisi != "") {
            $qidentifikasi_kerawananCount = $qidentifikasi_kerawananCount->where('id_divisi', $request->divisi);
        }

        if ($request->bentuk != "") {
            $qidentifikasi_kerawananCount = $qidentifikasi_kerawananCount->where('formcegahs.bentuk', $request->bentuk);
        }

        if ($request->jenis != "") {
            $qidentifikasi_kerawananCount = $qidentifikasi_kerawananCount->where('jenis', $request->jenis);
        }

        if ($request->pilih_wilayah == "provinsi") {
            $qidentifikasi_kerawananCount = $qidentifikasi_kerawananCount->where('formcegahs.id_provinsi', $request->wilayah_dropdown);
        }

        if ($request->pilih_wilayah == "kota") {
            $qidentifikasi_kerawananCount = $qidentifikasi_kerawananCount->where('formcegahs.id_kabupaten', $request->wilayah_dropdown);
        }

        $identifikasi_kerawananCount = $qidentifikasi_kerawananCount->groupBy('provinsis.provinsi')->pluck('count');

        $qpendidikanCount = Formcegah::select(
            'provinsis.provinsi as provinsi',
            DB::raw('
            SUM(CASE
            WHEN bentuks.bentuk="Pendidikan" THEN 1
            ELSE 0
            END) AS count
            ')
        )
            ->leftJoin('provinsis', 'formcegahs.id_provinsi', 'provinsis.id')
            ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->where('formcegahs.id_provinsi', '<>', '');

        if ($request->divisi != "") {
            $qpendidikanCount = $qpendidikanCount->where('id_divisi', $request->divisi);
        }

        if ($request->bentuk != "") {
            $qpendidikanCount = $qpendidikanCount->where('formcegahs.bentuk', $request->bentuk);
        }

        if ($request->jenis != "") {
            $qpendidikanCount = $qpendidikanCount->where('jenis', $request->jenis);
        }

        if ($request->pilih_wilayah == "provinsi") {
            $qpendidikanCount = $qpendidikanCount->where('formcegahs.id_provinsi', $request->wilayah_dropdown);
        }

        if ($request->pilih_wilayah == "kota") {
            $qpendidikanCount = $qpendidikanCount->where('formcegahs.id_kabupaten', $request->wilayah_dropdown);
        }

        $pendidikanCount = $qpendidikanCount->groupBy('provinsis.provinsi')->pluck('count');


        $qpartisipasiCount = Formcegah::select(
            'provinsis.provinsi as provinsi',
            DB::raw('
            SUM(CASE
            WHEN bentuks.bentuk="Partisipasi Masyarakat" THEN 1
            ELSE 0
            END) AS count
            ')
        )
            ->leftJoin('provinsis', 'formcegahs.id_provinsi', 'provinsis.id')
            ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->where('formcegahs.id_provinsi', '<>', '');

        if ($request->divisi != "") {
            $qpartisipasiCount = $qpartisipasiCount->where('id_divisi', $request->divisi);
        }

        if ($request->bentuk != "") {
            $qpartisipasiCount = $qpartisipasiCount->where('formcegahs.bentuk', $request->bentuk);
        }

        if ($request->jenis != "") {
            $qpartisipasiCount = $qpartisipasiCount->where('jenis', $request->jenis);
        }

        if ($request->pilih_wilayah == "provinsi") {
            $qpartisipasiCount = $qpartisipasiCount->where('formcegahs.id_provinsi', $request->wilayah_dropdown);
        }

        if ($request->pilih_wilayah == "kota") {
            $qpartisipasiCount = $qpartisipasiCount->where('formcegahs.id_kabupaten', $request->wilayah_dropdown);
        }

        $partisipasiCount = $qpartisipasiCount->groupBy('provinsis.provinsi')->pluck('count');

        $qkerjasamaCount = Formcegah::select(
            'provinsis.provinsi as provinsi',
            DB::raw('
            SUM(CASE
            WHEN bentuks.bentuk="Kerja sama" THEN 1
            ELSE 0
            END) AS count
            ')
        )
            ->leftJoin('provinsis', 'formcegahs.id_provinsi', 'provinsis.id')
            ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->where('formcegahs.id_provinsi', '<>', '');

        if ($request->divisi != "") {
            $qkerjasamaCount = $qkerjasamaCount->where('id_divisi', $request->divisi);
        }

        if ($request->bentuk != "") {
            $qkerjasamaCount = $qkerjasamaCount->where('formcegahs.bentuk', $request->bentuk);
        }

        if ($request->jenis != "") {
            $qkerjasamaCount = $qkerjasamaCount->where('jenis', $request->jenis);
        }

        if ($request->pilih_wilayah == "provinsi") {
            $qkerjasamaCount = $qkerjasamaCount->where('formcegahs.id_provinsi', $request->wilayah_dropdown);
        }

        if ($request->pilih_wilayah == "kota") {
            $qkerjasamaCount = $qkerjasamaCount->where('formcegahs.id_kabupaten', $request->wilayah_dropdown);
        }

        $kerjasamaCount = $qkerjasamaCount->groupBy('provinsis.provinsi')->pluck('count');

        $qnaskahdinasCount = Formcegah::select(
            'provinsis.provinsi as provinsi',
            DB::raw('
            SUM(CASE
            WHEN bentuks.bentuk="Naskah Dinas" THEN 1
            ELSE 0
            END) AS count
            ')
        )
            ->leftJoin('provinsis', 'formcegahs.id_provinsi', 'provinsis.id')
            ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->where('formcegahs.id_provinsi', '<>', '');

        if ($request->divisi != "") {
            $qnaskahdinasCount = $qnaskahdinasCount->where('id_divisi', $request->divisi);
        }

        if ($request->bentuk != "") {
            $qnaskahdinasCount = $qnaskahdinasCount->where('formcegahs.bentuk', $request->bentuk);
        }

        if ($request->jenis != "") {
            $qnaskahdinasCount = $qnaskahdinasCount->where('jenis', $request->jenis);
        }

        if ($request->pilih_wilayah == "provinsi") {
            $qnaskahdinasCount = $qnaskahdinasCount->where('formcegahs.id_provinsi', $request->wilayah_dropdown);
        }

        if ($request->pilih_wilayah == "kota") {
            $qnaskahdinasCount = $qnaskahdinasCount->where('formcegahs.id_kabupaten', $request->wilayah_dropdown);
        }

        $naskahdinasCount = $qnaskahdinasCount->groupBy('provinsis.provinsi')->pluck('count');

        //dd($naskahdinasCount);


        $qimbauanCount = Formcegah::select(
            'provinsis.provinsi as provinsi',
            DB::raw('
            SUM(CASE
            WHEN bentuks.bentuk="Imbauan" THEN 1
            ELSE 0
            END) AS count
            ')
        )
            ->leftJoin('provinsis', 'formcegahs.id_provinsi', 'provinsis.id')
            ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->where('formcegahs.id_provinsi', '<>', '');

        if ($request->divisi != "") {
            $qimbauanCount = $qimbauanCount->where('id_divisi', $request->divisi);
        }

        if ($request->bentuk != "") {
            $qimbauanCount = $qimbauanCount->where('formcegahs.bentuk', $request->bentuk);
        }

        if ($request->jenis != "") {
            $qimbauanCount = $qimbauanCount->where('jenis', $request->jenis);
        }

        if ($request->pilih_wilayah == "provinsi") {
            $qimbauanCount = $qimbauanCount->where('formcegahs.id_provinsi', $request->wilayah_dropdown);
        }

        if ($request->pilih_wilayah == "kota") {
            $qimbauanCount = $qimbauanCount->where('formcegahs.id_kabupaten', $request->wilayah_dropdown);
        }

        $imbauanCount = $qimbauanCount->groupBy('provinsis.provinsi')
            ->pluck('count');


        $qkegiatanlainCount = Formcegah::select(
            'provinsis.provinsi as provinsi',
            DB::raw('
            SUM(CASE
            WHEN bentuks.bentuk="Kegiatan Lainnya" THEN 1
            ELSE 0
            END) AS count
            ')
        )
            ->leftJoin('provinsis', 'formcegahs.id_provinsi', 'provinsis.id')
            ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->where('formcegahs.id_provinsi', '<>', '');

        if ($request->divisi != "") {
            $qkegiatanlainCount = $qkegiatanlainCount->where('id_divisi', $request->divisi);
        }

        if ($request->bentuk != "") {
            $qkegiatanlainCount = $qkegiatanlainCount->where('formcegahs.bentuk', $request->bentuk);
        }

        if ($request->jenis != "") {
            $qkegiatanlainCount = $qkegiatanlainCount->where('jenis', $request->jenis);
        }

        if ($request->pilih_wilayah == "provinsi") {
            $qkegiatanlainCount = $qkegiatanlainCount->where('formcegahs.id_provinsi', $request->wilayah_dropdown);
        }

        if ($request->pilih_wilayah == "kota") {
            $qkegiatanlainCount = $qkegiatanlainCount->where('formcegahs.id_kabupaten', $request->wilayah_dropdown);
        }

        $kegiatanlainCount = $qkegiatanlainCount->groupBy('provinsis.provinsi')->pluck('count');

        $qpublikasiCount = Formcegah::select(
            'provinsis.provinsi as provinsi',
            DB::raw('
            SUM(CASE
            WHEN bentuks.bentuk="Publikasi" THEN 1
            ELSE 0
            END) AS count
            ')
        )
            ->leftJoin('provinsis', 'formcegahs.id_provinsi', 'provinsis.id')
            ->leftJoin('bentuks', 'formcegahs.bentuk', 'bentuks.id')
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->where('formcegahs.id_provinsi', '<>', '');

        if ($request->divisi != "") {
            $qpublikasiCount = $qpublikasiCount->where('id_divisi', $request->divisi);
        }

        if ($request->bentuk != "") {
            $qpublikasiCount = $qpublikasiCount->where('formcegahs.bentuk', $request->bentuk);
        }

        if ($request->jenis != "") {
            $qpublikasiCount = $qpublikasiCount->where('jenis', $request->jenis);
        }

        if ($request->pilih_wilayah == "provinsi") {
            $qpublikasiCount = $qpublikasiCount->where('formcegahs.id_provinsi', $request->wilayah_dropdown);
        }

        if ($request->pilih_wilayah == "kota") {
            $qpublikasiCount = $qpublikasiCount->where('formcegahs.id_kabupaten', $request->wilayah_dropdown);
        }

        $publikasiCount = $qpublikasiCount->groupBy('provinsis.provinsi')->pluck('count');


        $response = [
            $categories, 
            $identifikasi_kerawananCount, 
            $pendidikanCount, 
            $partisipasiCount, 
            $kerjasamaCount, 
            $naskahdinasCount, 
            $kegiatanlainCount, 
            $publikasiCount
        ];

        return json_encode($response);

    }

}
