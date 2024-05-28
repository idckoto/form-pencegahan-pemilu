<?php

namespace App\Http\Controllers;

use App\Models\Formcegah;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\User;
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
            return redirect('/login');
        }
    }

    public function index(Request $request)
    {
        // $idUser='112';

        $user=User::where('id', Auth::user()->id)->first();
        $jabatan=$user->Jabatan;

        if($request->date_finish==""){
            $now=Carbon::now();
            $date_start=$now->firstOfMonth()->format('Y-m-d');
		    $date_finish=$now->endOfMonth()->format('Y-m-d');
        }
        else{
            $date_start = $request->date_start;
            $date_finish = $request->date_finish;
        }
        //dd($idUser,$jabatan,$date_start,$date_finish);

        if($jabatan == 'Sekretariat Bawaslu Provinsi'){
            //dd('hai');
            $title=' Seluruh Provinsi';
            $categories=Formcegah::select('provinsis.provinsi as provinsi')
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->leftJoin('provinsis','formcegahs.id_provinsi','provinsis.id')
            ->groupBy('provinsis.provinsi')
            ->pluck('provinsi');

            $tahapan_pie=Formcegah::select(
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
            ->groupBy('tahap')
            ->get();

            $dataTahap = [];
            foreach ($tahapan_pie as $data) {
                $dataTahap[]=[
                    $data['tahap'],
                    $data['count']
                ];
            }

            $bentuk_pie=Formcegah::select(
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
            ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->groupBy('bentuks.bentuk')
            ->get();

            $dataBentuk = [];
            foreach ($bentuk_pie as $data) {
                $dataBentuk[]=[
                    $data['bentuk'],
                    $data['count']
                ];
            }
            
            //dd(json_encode($dataBentuk));

            $identifikasi_kerawananCount=Formcegah::select(
                'provinsis.provinsi as provinsi',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Identifikasi Kerawanan" THEN 1
                ELSE 0
                END) AS count
                ')
            )
            ->leftJoin('provinsis','formcegahs.id_provinsi','provinsis.id')
            ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->groupBy('provinsis.provinsi')
            ->pluck('count');

            $identifikasi_kerawananSum=Formcegah::where('bentuk','1')
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->count();

            $pendidikanCount=Formcegah::select(
                'provinsis.provinsi as provinsi',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Pendidikan" THEN 1
                ELSE 0
                END) AS count
                ')
            )
            ->leftJoin('provinsis','formcegahs.id_provinsi','provinsis.id')
            ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->groupBy('provinsis.provinsi')
            ->pluck('count');

            $pendidikanSum=Formcegah::where('bentuk','2')
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->count();

            $partisipasiCount=Formcegah::select(
                'provinsis.provinsi as provinsi',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Partisipasi Masyarakat" THEN 1
                ELSE 0
                END) AS count
                ')
            )
            ->leftJoin('provinsis','formcegahs.id_provinsi','provinsis.id')
            ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->groupBy('provinsis.provinsi')
            ->pluck('count');

            $partisipasiSum=Formcegah::where('bentuk','3')
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->count();

            $kerjasamaCount=Formcegah::select(
                'provinsis.provinsi as provinsi',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Kerja sama" THEN 1
                ELSE 0
                END) AS count
                ')
            )
            ->leftJoin('provinsis','formcegahs.id_provinsi','provinsis.id')
            ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->groupBy('provinsis.provinsi')
            ->pluck('count');

            $kerjasamaSum=Formcegah::where('bentuk','4')
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->count();

            $imbauanCount=Formcegah::select(
                'provinsis.provinsi as provinsi',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Imbauan" THEN 1
                ELSE 0
                END) AS count
                ')
            )
            ->leftJoin('provinsis','formcegahs.id_provinsi','provinsis.id')
            ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->groupBy('provinsis.provinsi')
            ->pluck('count');

            $imbauanSum=Formcegah::where('bentuk','5')
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->count();

            $kegiatanlainCount=Formcegah::select(
                'provinsis.provinsi as provinsi',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Kegiatan Lainnya" THEN 1
                ELSE 0
                END) AS count
                ')
            )
            ->leftJoin('provinsis','formcegahs.id_provinsi','provinsis.id')
            ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->groupBy('provinsis.provinsi')
            ->pluck('count');

            $kegiatanlainSum=Formcegah::where('bentuk','0')
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->count();

            $publikasiCount=Formcegah::select(
                'provinsis.provinsi as provinsi',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Publikasi" THEN 1
                ELSE 0
                END) AS count
                ')
            )
            ->leftJoin('provinsis','formcegahs.id_provinsi','provinsis.id')
            ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->groupBy('provinsis.provinsi')
            ->pluck('count');

            $publikasiSum=Formcegah::where('bentuk','6')
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->count();
			
			$rekapCegah=Formcegah::leftJoin('provinsis', 'formcegahs.id_provinsi', '=', 'provinsis.id')
				->leftJoin('kabupatens', 'kabupatens.id', '=', 'formcegahs.id_kabupaten')
				->leftJoin('kecamatans', 'kecamatans.id', '=', 'formcegahs.id_kecamatan')
				->leftJoin('kelurahans', 'kelurahans.id', '=', 'formcegahs.id_kelurahan')
				->leftJoin('bentuks', 'formcegahs.bentuk', '=', 'bentuks.id')
				->select('formcegahs.no_form', 'formcegahs.tahap', 'bentuks.bentuk', 'provinsis.provinsi', 'kabupatens.kabupaten', 'kecamatans.kecamatan', 'kelurahans.kelurahan')
				->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at, '%Y-%m-%d'))"), [$date_start, $date_finish])
				->orderBy('provinsis.provinsi','asc')
				->get();

            //dd(json_encode($categories),json_encode($kegiatanlainCount));
        }
        else if($jabatan == 'Ketua atau Anggota Bawaslu Provinsi'){
            //dd($user->Provinsi);
            if ($user->Provinsi != null) {
                //dd('hai');
                $provinsi=Provinsi::where('id',$user->Provinsi)->first();
                $title=' Kabupaten/Kota di Seluruh Provinsi '.$provinsi->provinsi;
                //dd($title);
                $categories=Formcegah::select('kabupatens.kabupaten as kabupaten')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->leftJoin('kabupatens','formcegahs.id_kabupaten','kabupatens.id')
                ->where('formcegahs.id_provinsi',$user->Provinsi)
                ->groupBy('kabupatens.kabupaten')
                ->pluck('kabupaten');
                //dd($categories);

                $tahapan_pie=Formcegah::select(
                    'tahap',
                    DB::raw('
                    SUM(CASE
                    WHEN tahap="Tahapan" THEN 1
                    WHEN tahap="Non Tahapan" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                ->where('formcegahs.id_provinsi',$user->Provinsi)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->groupBy('tahap')
                ->get();
    
                $dataTahap = [];
                foreach ($tahapan_pie as $data) {
                    $dataTahap[]=[
                        $data['tahap'],
                        $data['count']
                    ];
                }
    
                $bentuk_pie=Formcegah::select(
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
                ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
                ->where('formcegahs.id_provinsi',$user->Provinsi)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->groupBy('bentuks.bentuk')
                ->get();
    
                $dataBentuk = [];
                foreach ($bentuk_pie as $data) {
                    $dataBentuk[]=[
                        $data['bentuk'],
                        $data['count']
                    ];
                }
                
                //dd(json_encode($dataBentuk));

                $identifikasi_kerawananCount=Formcegah::select(
                    'kabupatens.kabupaten as kabupaten',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Identifikasi Kerawanan" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                ->leftJoin('kabupatens','formcegahs.id_kabupaten','kabupatens.id')
                ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
                ->where('formcegahs.id_provinsi',$user->Provinsi)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->groupBy('kabupatens.kabupaten')
                ->pluck('count');
    
                $identifikasi_kerawananSum=Formcegah::where('bentuk','1')
                ->where('formcegahs.id_provinsi',$user->Provinsi)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->count();
    
                $pendidikanCount=Formcegah::select(
                    'kabupatens.kabupaten as kabupaten',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Pendidikan" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                ->leftJoin('kabupatens','formcegahs.id_kabupaten','kabupatens.id')
                ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
                ->where('formcegahs.id_provinsi',$user->Provinsi)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->groupBy('kabupatens.kabupaten')
                ->pluck('count');
    
                $pendidikanSum=Formcegah::where('bentuk','2')
                ->where('formcegahs.id_provinsi',$user->Provinsi)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->count();

                $partisipasiCount=Formcegah::select(
                    'kabupatens.kabupaten as kabupaten',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Partisipasi Masyarakat" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                ->leftJoin('kabupatens','formcegahs.id_kabupaten','kabupatens.id')
                ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
                ->where('formcegahs.id_provinsi',$user->Provinsi)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->groupBy('kabupatens.kabupaten')
                ->pluck('count');
    
                $partisipasiSum=Formcegah::where('bentuk','3')
                ->where('formcegahs.id_provinsi',$user->Provinsi)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->count();
    
                $kerjasamaCount=Formcegah::select(
                    'kabupatens.kabupaten as kabupaten',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Kerja sama" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                ->leftJoin('kabupatens','formcegahs.id_kabupaten','kabupatens.id')
                ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
                ->where('formcegahs.id_provinsi',$user->Provinsi)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->groupBy('kabupatens.kabupaten')
                ->pluck('count');
    
                $kerjasamaSum=Formcegah::where('bentuk','4')
                ->where('formcegahs.id_provinsi',$user->Provinsi)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->count();
    
                $imbauanCount=Formcegah::select(
                    'kabupatens.kabupaten as kabupaten',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Imbauan" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                ->leftJoin('kabupatens','formcegahs.id_kabupaten','kabupatens.id')
                ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
                ->where('formcegahs.id_provinsi',$user->Provinsi)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->groupBy('kabupatens.kabupaten')
                ->pluck('count');
    
                $imbauanSum=Formcegah::where('bentuk','5')
                ->where('formcegahs.id_provinsi',$user->Provinsi)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->count();
    
                $kegiatanlainCount=Formcegah::select(
                    'kabupatens.kabupaten as kabupaten',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Kegiatan Lainnya" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                ->leftJoin('kabupatens','formcegahs.id_kabupaten','kabupatens.id')
                ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
                ->where('formcegahs.id_provinsi',$user->Provinsi)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->groupBy('kabupatens.kabupaten')
                ->pluck('count');
    
                $kegiatanlainSum=Formcegah::where('bentuk','0')
                ->where('formcegahs.id_provinsi',$user->Provinsi)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->count();

                $publikasiCount=Formcegah::select(
                    'provinsis.provinsi as provinsi',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Publikasi" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                ->leftJoin('provinsis','formcegahs.id_provinsi','provinsis.id')
                ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
                ->where('formcegahs.id_provinsi',$user->Provinsi)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->groupBy('provinsis.provinsi')
                ->pluck('count');
    
                $publikasiSum=Formcegah::where('bentuk','6')
                ->where('formcegahs.id_provinsi',$user->Provinsi)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->count();
				
				$rekapCegah=Formcegah::leftJoin('provinsis', 'formcegahs.id_provinsi', '=', 'provinsis.id')
				->leftJoin('kabupatens', 'kabupatens.id', '=', 'formcegahs.id_kabupaten')
				->leftJoin('kecamatans', 'kecamatans.id', '=', 'formcegahs.id_kecamatan')
				->leftJoin('kelurahans', 'kelurahans.id', '=', 'formcegahs.id_kelurahan')
				->leftJoin('bentuks', 'formcegahs.bentuk', '=', 'bentuks.id')
				->select('formcegahs.no_form', 'formcegahs.tahap', 'bentuks.bentuk', 'provinsis.provinsi', 'kabupatens.kabupaten', 'kecamatans.kecamatan', 'kelurahans.kelurahan')
				->where('formcegahs.id_provinsi',$user->Provinsi)
				->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at, '%Y-%m-%d'))"), [$date_start, $date_finish])
				->orderBy('provinsis.provinsi','asc')
				->get();

                //dd(json_encode($categories),json_encode($identifikasi_kerawananCount),json_encode($pendidikanCount));
            }
            else{
                //dd('hai');
                $title=' Seluruh Provinsi';
                $categories=Formcegah::select('provinsis.provinsi as provinsi')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->leftJoin('provinsis','formcegahs.id_provinsi','provinsis.id')
                ->groupBy('provinsis.provinsi')
                ->pluck('provinsi');

                $tahapan_pie=Formcegah::select(
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
                ->groupBy('tahap')
                ->get();
    
                $dataTahap = [];
                foreach ($tahapan_pie as $data) {
                    $dataTahap[]=[
                        $data['tahap'],
                        $data['count']
                    ];
                }
    
                $bentuk_pie=Formcegah::select(
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
                ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->groupBy('bentuks.bentuk')
                ->get();
    
                $dataBentuk = [];
                foreach ($bentuk_pie as $data) {
                    $dataBentuk[]=[
                        $data['bentuk'],
                        $data['count']
                    ];
                }
                
                //dd(json_encode($dataBentuk));

                $identifikasi_kerawananCount=Formcegah::select(
                    'provinsis.provinsi as provinsi',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Identifikasi Kerawanan" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                ->leftJoin('provinsis','formcegahs.id_provinsi','provinsis.id')
                ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->groupBy('provinsis.provinsi')
                ->pluck('count');
    
                $identifikasi_kerawananSum=Formcegah::where('bentuk','1')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->count();
    
                $pendidikanCount=Formcegah::select(
                    'provinsis.provinsi as provinsi',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Pendidikan" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                ->leftJoin('provinsis','formcegahs.id_provinsi','provinsis.id')
                ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->groupBy('provinsis.provinsi')
                ->pluck('count');
    
                $pendidikanSum=Formcegah::where('bentuk','2')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->count();

                $partisipasiCount=Formcegah::select(
                    'provinsis.provinsi as provinsi',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Partisipasi Masyarakat" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                ->leftJoin('provinsis','formcegahs.id_provinsi','provinsis.id')
                ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->groupBy('provinsis.provinsi')
                ->pluck('count');
    
                $partisipasiSum=Formcegah::where('bentuk','3')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->count();
    
                $kerjasamaCount=Formcegah::select(
                    'provinsis.provinsi as provinsi',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Kerja sama" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                ->leftJoin('provinsis','formcegahs.id_provinsi','provinsis.id')
                ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->groupBy('provinsis.provinsi')
                ->pluck('count');
    
                $kerjasamaSum=Formcegah::where('bentuk','4')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->count();
    
                $imbauanCount=Formcegah::select(
                    'provinsis.provinsi as provinsi',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Imbauan" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                ->leftJoin('provinsis','formcegahs.id_provinsi','provinsis.id')
                ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->groupBy('provinsis.provinsi')
                ->pluck('count');
    
                $imbauanSum=Formcegah::where('bentuk','5')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->count();
    
                $kegiatanlainCount=Formcegah::select(
                    'provinsis.provinsi as provinsi',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Kegiatan Lainnya" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                ->leftJoin('provinsis','formcegahs.id_provinsi','provinsis.id')
                ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->groupBy('provinsis.provinsi')
                ->pluck('count');
    
                $kegiatanlainSum=Formcegah::where('bentuk','0')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->count();

                $publikasiCount=Formcegah::select(
                    'provinsis.provinsi as provinsi',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Publikasi" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                ->leftJoin('provinsis','formcegahs.id_provinsi','provinsis.id')
                ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->groupBy('provinsis.provinsi')
                ->pluck('count');
    
                $publikasiSum=Formcegah::where('bentuk','6')
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->count();
				
				$rekapCegah=Formcegah::leftJoin('provinsis', 'formcegahs.id_provinsi', '=', 'provinsis.id')
				->leftJoin('kabupatens', 'kabupatens.id', '=', 'formcegahs.id_kabupaten')
				->leftJoin('kecamatans', 'kecamatans.id', '=', 'formcegahs.id_kecamatan')
				->leftJoin('kelurahans', 'kelurahans.id', '=', 'formcegahs.id_kelurahan')
				->leftJoin('bentuks', 'formcegahs.bentuk', '=', 'bentuks.id')
				->select('formcegahs.no_form', 'formcegahs.tahap', 'bentuks.bentuk', 'provinsis.provinsi', 'kabupatens.kabupaten', 'kecamatans.kecamatan', 'kelurahans.kelurahan')
				->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at, '%Y-%m-%d'))"), [$date_start, $date_finish])
				->orderBy('provinsis.provinsi','asc')
				->get();

                //dd(json_encode($categories));
            }
        }
        else if($jabatan == 'Ketua atau Anggota Bawaslu Kabupaten/Kota'){
            //dd($user->KabKota);
            $KabKota=Kabupaten::where('id',$user->KabKota)->first();
            $title=' Kecamatan di Seluruh '.$KabKota->kabupaten;
            //dd($title);
            $categories=Formcegah::select('kecamatans.kecamatan as kecamatan')
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->leftJoin('kecamatans','formcegahs.id_kecamatan','kecamatans.id')
            ->where('formcegahs.id_kabupaten',$user->KabKota)
            ->groupBy('kecamatans.kecamatan')
            ->pluck('kecamatan');

            //dd(json_encode($categories));

            $tahapan_pie=Formcegah::select(
                'tahap',
                DB::raw('
                SUM(CASE
                WHEN tahap="Tahapan" THEN 1
                WHEN tahap="Non Tahapan" THEN 1
                ELSE 0
                END) AS count
                ')
            )
            ->where('formcegahs.id_kabupaten',$user->KabKota)
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->groupBy('tahap')
            ->get();

            $dataTahap = [];
            foreach ($tahapan_pie as $data) {
                $dataTahap[]=[
                    $data['tahap'],
                    $data['count']
                ];
            }

            $bentuk_pie=Formcegah::select(
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
            ->where('formcegahs.id_kabupaten',$user->KabKota)
            ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->groupBy('bentuks.bentuk')
            ->get();

            $dataBentuk = [];
            foreach ($bentuk_pie as $data) {
                $dataBentuk[]=[
                    $data['bentuk'],
                    $data['count']
                ];
            }
            
            //dd(json_encode($dataBentuk));
            
            $identifikasi_kerawananCount=Formcegah::select(
                'kecamatans.kecamatan as kecamatan',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Identifikasi Kerawanan" THEN 1
                ELSE 0
                END) AS count
                ')
            )
            ->leftJoin('kecamatans','formcegahs.id_kecamatan','kecamatans.id')
            ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
            ->where('formcegahs.id_kabupaten',$user->KabKota)
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->groupBy('kecamatans.kecamatan')
            ->pluck('count');

            $identifikasi_kerawananSum=Formcegah::where('bentuk','1')
            ->where('formcegahs.id_kabupaten',$user->KabKota)
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->count();

            $pendidikanCount=Formcegah::select(
                'kecamatans.kecamatan as kecamatan',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Pendidikan" THEN 1
                ELSE 0
                END) AS count
                ')
            )
            ->leftJoin('kecamatans','formcegahs.id_kecamatan','kecamatans.id')
            ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
            ->where('formcegahs.id_kabupaten',$user->KabKota)
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->groupBy('kecamatans.kecamatan')
            ->pluck('count');

            $pendidikanSum=Formcegah::where('bentuk','2')
            ->where('formcegahs.id_kabupaten',$user->KabKota)
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->count();

            $partisipasiCount=Formcegah::select(
                'kecamatans.kecamatan as kecamatan',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Partisipasi Masyarakat" THEN 1
                ELSE 0
                END) AS count
                ')
            )
            ->leftJoin('kecamatans','formcegahs.id_kecamatan','kecamatans.id')
            ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
            ->where('formcegahs.id_kabupaten',$user->KabKota)
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->groupBy('kecamatans.kecamatan')
            ->pluck('count');

            $partisipasiSum=Formcegah::where('bentuk','3')
            ->where('formcegahs.id_kabupaten',$user->KabKota)
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->count();

            $kerjasamaCount=Formcegah::select(
                'kecamatans.kecamatan as kecamatan',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Kerja sama" THEN 1
                ELSE 0
                END) AS count
                ')
            )
            ->leftJoin('kecamatans','formcegahs.id_kecamatan','kecamatans.id')
            ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
            ->where('formcegahs.id_kabupaten',$user->KabKota)
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->groupBy('kecamatans.kecamatan')
            ->pluck('count');

            $kerjasamaSum=Formcegah::where('bentuk','4')
            ->where('formcegahs.id_kabupaten',$user->KabKota)
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->count();

            $imbauanCount=Formcegah::select(
                'kecamatans.kecamatan as kecamatan',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Imbauan" THEN 1
                ELSE 0
                END) AS count
                ')
            )
            ->leftJoin('kecamatans','formcegahs.id_kecamatan','kecamatans.id')
            ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
            ->where('formcegahs.id_kabupaten',$user->KabKota)
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->groupBy('kecamatans.kecamatan')
            ->pluck('count');

            $imbauanSum=Formcegah::where('bentuk','5')
            ->where('formcegahs.id_kabupaten',$user->KabKota)
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->count();

            $kegiatanlainCount=Formcegah::select(
                'kecamatans.kecamatan as kecamatan',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Kegiatan Lainnya" THEN 1
                ELSE 0
                END) AS count
                ')
            )
            ->leftJoin('kecamatans','formcegahs.id_kecamatan','kecamatans.id')
            ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
            ->where('formcegahs.id_kabupaten',$user->KabKota)
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->groupBy('kecamatans.kecamatan')
            ->pluck('count');

            $kegiatanlainSum=Formcegah::where('bentuk','0')
            ->where('formcegahs.id_kabupaten',$user->KabKota)
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->count();

            $publikasiCount=Formcegah::select(
                'provinsis.provinsi as provinsi',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Publikasi" THEN 1
                ELSE 0
                END) AS count
                ')
            )
            ->leftJoin('provinsis','formcegahs.id_provinsi','provinsis.id')
            ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
            ->where('formcegahs.id_kabupaten',$user->KabKota)
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->groupBy('provinsis.provinsi')
            ->pluck('count');

            $publikasiSum=Formcegah::where('bentuk','6')
            ->where('formcegahs.id_kabupaten',$user->KabKota)
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->count();
			
			$rekapCegah=Formcegah::leftJoin('provinsis', 'formcegahs.id_provinsi', '=', 'provinsis.id')
				->leftJoin('kabupatens', 'kabupatens.id', '=', 'formcegahs.id_kabupaten')
				->leftJoin('kecamatans', 'kecamatans.id', '=', 'formcegahs.id_kecamatan')
				->leftJoin('kelurahans', 'kelurahans.id', '=', 'formcegahs.id_kelurahan')
				->leftJoin('bentuks', 'formcegahs.bentuk', '=', 'bentuks.id')
				->select('formcegahs.no_form', 'formcegahs.tahap', 'bentuks.bentuk', 'provinsis.provinsi', 'kabupatens.kabupaten', 'kecamatans.kecamatan', 'kelurahans.kelurahan')
				->where('formcegahs.id_kabupaten',$user->KabKota)
				->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at, '%Y-%m-%d'))"), [$date_start, $date_finish])
				->orderBy('provinsis.provinsi','asc')
				->get();

            //dd(json_encode($categories),json_encode($identifikasi_kerawananCount),json_encode($pendidikanCount));
        }
        else if($jabatan == 'Bawaslu Kecamatan'){
            //dd('hai');
            $kecamatan=Kecamatan::where('id',$user->Kecamatan)->first();
            $title=' Kelurahan di Seluruh Kecamatan '.$kecamatan->kecamatan;
            //dd($title);
            
            $categories=Formcegah::select('kelurahans.kelurahan as kelurahan')
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->leftJoin('kelurahans','formcegahs.id_kelurahan','kelurahans.id')
            ->where('formcegahs.id_provinsi',$user->Provinsi)
            ->groupBy('kelurahans.kelurahan')
            ->pluck('kelurahan');
            
            $tahapan_pie=Formcegah::select(
                'tahap',
                DB::raw('
                SUM(CASE
                WHEN tahap="Tahapan" THEN 1
                WHEN tahap="Non Tahapan" THEN 1
                ELSE 0
                END) AS count
                ')
            )
            ->where('formcegahs.id_kecamatan',$user->Kecamatan)
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->groupBy('tahap')
            ->get();

            $dataTahap = [];
            foreach ($tahapan_pie as $data) {
                $dataTahap[]=[
                    $data['tahap'],
                    $data['count']
                ];
            }

            $bentuk_pie=Formcegah::select(
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
            ->where('formcegahs.id_kecamatan',$user->Kecamatan)
            ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->groupBy('bentuks.bentuk')
            ->get();

            $dataBentuk = [];
            foreach ($bentuk_pie as $data) {
                $dataBentuk[]=[
                    $data['bentuk'],
                    $data['count']
                ];
            }
            
            //dd(json_encode($dataBentuk));


            $identifikasi_kerawananCount=Formcegah::select(
                'kelurahans.kelurahan as kelurahan',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Identifikasi Kerawanan" THEN 1
                ELSE 0
                END) AS count
                ')
            )
            ->leftJoin('kelurahans','formcegahs.id_kelurahan','kelurahans.id')
            ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
            ->where('formcegahs.id_kecamatan',$user->Kecamatan)
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->groupBy('kelurahans.kelurahan')
            ->pluck('count');

            $identifikasi_kerawananSum=Formcegah::where('bentuk','1')
            ->where('formcegahs.id_kecamatan',$user->Kecamatan)
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->count();

            $pendidikanCount=Formcegah::select(
                'kelurahans.kelurahan as kelurahan',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Pendidikan" THEN 1
                ELSE 0
                END) AS count
                ')
            )
            ->leftJoin('kelurahans','formcegahs.id_kelurahan','kelurahans.id')
            ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
            ->where('formcegahs.id_kecamatan',$user->Kecamatan)
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->groupBy('kelurahans.kelurahan')
            ->pluck('count');

            $pendidikanSum=Formcegah::where('bentuk','2')
            ->where('formcegahs.id_kecamatan',$user->Kecamatan)
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->count();

            $partisipasiCount=Formcegah::select(
                'kelurahans.kelurahan as kelurahan',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Partisipasi Masyarakat" THEN 1
                ELSE 0
                END) AS count
                ')
            )
            ->leftJoin('kelurahans','formcegahs.id_kelurahan','kelurahans.id')
            ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
            ->where('formcegahs.id_kecamatan',$user->Kecamatan)
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->groupBy('kelurahans.kelurahan')
            ->pluck('count');

            $partisipasiSum=Formcegah::where('bentuk','3')
            ->where('formcegahs.id_kecamatan',$user->Kecamatan)
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->count();

            $kerjasamaCount=Formcegah::select(
                'kelurahans.kelurahan as kelurahan',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Kerja sama" THEN 1
                ELSE 0
                END) AS count
                ')
            )
            ->leftJoin('kelurahans','formcegahs.id_kelurahan','kelurahans.id')
            ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
            ->where('formcegahs.id_kecamatan',$user->Kecamatan)
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->groupBy('kelurahans.kelurahan')
            ->pluck('count');

            $kerjasamaSum=Formcegah::where('bentuk','4')
            ->where('formcegahs.id_kecamatan',$user->Kecamatan)
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->count();

            $imbauanCount=Formcegah::select(
                'kelurahans.kelurahan as kelurahan',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Imbauan" THEN 1
                ELSE 0
                END) AS count
                ')
            )
            ->leftJoin('kelurahans','formcegahs.id_kelurahan','kelurahans.id')
            ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
            ->where('formcegahs.id_kecamatan',$user->Kecamatan)
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->groupBy('kelurahans.kelurahan')
            ->pluck('count');

            $imbauanSum=Formcegah::where('bentuk','5')
            ->where('formcegahs.id_kecamatan',$user->Kecamatan)
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->count();

            $kegiatanlainCount=Formcegah::select(
                'kelurahans.kelurahan as kelurahan',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Kegiatan Lainnya" THEN 1
                ELSE 0
                END) AS count
                ')
            )
            ->leftJoin('kelurahans','formcegahs.id_kelurahan','kelurahans.id')
            ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
            ->where('formcegahs.id_kecamatan',$user->Kecamatan)
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->groupBy('kelurahans.kelurahan')
            ->pluck('count');

            $kegiatanlainSum=Formcegah::where('bentuk','0')
            ->where('formcegahs.id_kecamatan',$user->Kecamatan)
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->count();

            $publikasiCount=Formcegah::select(
                'provinsis.provinsi as provinsi',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Publikasi" THEN 1
                ELSE 0
                END) AS count
                ')
            )
            ->leftJoin('provinsis','formcegahs.id_provinsi','provinsis.id')
            ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
            ->where('formcegahs.id_kecamatan',$user->Kecamatan)
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->groupBy('provinsis.provinsi')
            ->pluck('count');

            $publikasiSum=Formcegah::where('bentuk','6')
            ->where('formcegahs.id_kecamatan',$user->Kecamatan)
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->count();
			
			$rekapCegah=Formcegah::leftJoin('provinsis', 'formcegahs.id_provinsi', '=', 'provinsis.id')
				->leftJoin('kabupatens', 'kabupatens.id', '=', 'formcegahs.id_kabupaten')
				->leftJoin('kecamatans', 'kecamatans.id', '=', 'formcegahs.id_kecamatan')
				->leftJoin('kelurahans', 'kelurahans.id', '=', 'formcegahs.id_kelurahan')
				->leftJoin('bentuks', 'formcegahs.bentuk', '=', 'bentuks.id')
				->select('formcegahs.no_form', 'formcegahs.tahap', 'bentuks.bentuk', 'provinsis.provinsi', 'kabupatens.kabupaten', 'kecamatans.kecamatan', 'kelurahans.kelurahan')
				->where('formcegahs.id_kecamatan',$user->Kecamatan)
				->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at, '%Y-%m-%d'))"), [$date_start, $date_finish])
				->orderBy('provinsis.provinsi','asc')
				->get();

            //dd(json_encode($nonTahapanCount),json_encode($tahapanCount));
        }
        else{
            //dd('hai');
            $categories=[];

            $identifikasi_kerawananCount=[];
            $identifikasi_kerawananSum=[];

            $pendidikanCount=[];
            $pendidikanSum=[];

            $partisipasiCount=[];
            $partisipasiSum=[];

            $kerjasamaCount=[];
            $kerjasamaSum=[];

            $imbauanCount=[];
            $imbauanSum=[];

            $kegiatanlainCount=[];
            $kegiatanlainSum=[];

            $publikasiCount=[];
            $publikasiSum=[];
			
			$rekapCegah=[];

            //dd(json_encode($tahapanCount));
        }

        return view('graph.index',compact(
			'rekapCegah',
            'categories',
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
            'date_start',
            'date_finish',
            'dataTahap',
            'dataBentuk',
        ));
    }

    public function indexDetail($name,$date_start,$date_finish)
    {
        //dd($name,$date_start,$date_finish);
        $idUser='112';

        $user=User::where('id', Auth::user()->id)->first();
        $jabatan=$user->Jabatan;
        //dd($jabatan);   
        if($jabatan == 'Sekretariat Bawaslu Provinsi'){
            //serach by name
            $qProvinsi=Provinsi::where('provinsi',$name)->first();
            //dd($qProvinsi->id);

            $title=' Seluruh Provinsi '.$name;
            $categories=Formcegah::select('bentuks.bentuk as nama_bentuk')
            ->where('id_provinsi',$qProvinsi->id)
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
            ->leftJoin('provinsis','formcegahs.id_provinsi','provinsis.id')
            ->groupBy('bentuks.bentuk')
            ->pluck('nama_bentuk');

            //dd($categories);

            $identifikasi_kerawananCount=Formcegah::select(
                'bentuks.bentuk as nama_bentuk',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Identifikasi Kerawanan" THEN 1
                ELSE 0
                END) AS count
                ')
            )
            ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
            ->where('id_provinsi',$qProvinsi->id)
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->groupBy('bentuks.bentuk')
            ->pluck('count');

            $pendidikanCount=Formcegah::select(
                'bentuks.bentuk as nama_bentuk',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Pendidikan" THEN 1
                ELSE 0
                END) AS count
                ')
            )
            ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
            ->where('id_provinsi',$qProvinsi->id)
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->groupBy('bentuks.bentuk')
            ->pluck('count');

            $partisipasiCount=Formcegah::select(
                'bentuks.bentuk as nama_bentuk',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Partisipasi Masyarakat" THEN 1
                ELSE 0
                END) AS count
                ')
            )
            ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
            ->where('id_provinsi',$qProvinsi->id)
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->groupBy('bentuks.bentuk')
            ->pluck('count');

            $kerjasamaCount=Formcegah::select(
                'bentuks.bentuk as nama_bentuk',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Kerjasama" THEN 1
                ELSE 0
                END) AS count
                ')
            )
            ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
            ->where('id_provinsi',$qProvinsi->id)
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->groupBy('bentuks.bentuk')
            ->pluck('count');

            $imbauanCount=Formcegah::select(
                'bentuks.bentuk as nama_bentuk',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Imbauan" THEN 1
                ELSE 0
                END) AS count
                ')
            )
            ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
            ->where('id_provinsi',$qProvinsi->id)
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->groupBy('bentuks.bentuk')
            ->pluck('count');

            $kegiatanlainCount=Formcegah::select(
                'bentuks.bentuk as nama_bentuk',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Kegiatan Lainnya" THEN 1
                ELSE 0
                END) AS count
                ')
            )
            ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
            ->where('id_provinsi',$qProvinsi->id)
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->groupBy('bentuks.bentuk')
            ->pluck('count');

            $publikasiCount=Formcegah::select(
                'bentuks.bentuk as nama_bentuk',
                DB::raw('
                SUM(CASE
                WHEN bentuks.bentuk="Publikasi" THEN 1
                ELSE 0
                END) AS count
                ')
            )
            ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
            ->where('id_provinsi',$qProvinsi->id)
            ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
            ->groupBy('bentuks.bentuk')
            ->pluck('count');
            //dd(json_encode($categories),json_encode($publikasiSum));
        }
        else if($jabatan == 'Ketua atau Anggota Bawaslu Provinsi'){
            if ($user->Provinsi != null) {
                //serach by name
                $qKabupaten=Kabupaten::where('kabupaten',$name)->first();
                //dd($qKabupaten->id);

                $provinsi=Provinsi::where('id',$user->Provinsi)->first();
                $title=' di '.$name;

                $categories=Formcegah::select('bentuks.bentuk as nama_bentuk')
                ->where('id_kabupaten',$qKabupaten->id)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
                ->leftJoin('kabupatens','formcegahs.id_kabupaten','kabupatens.id')
                ->groupBy('bentuks.bentuk')
                ->pluck('nama_bentuk');

                //dd($categories);

                $identifikasi_kerawananCount=Formcegah::select(
                    'bentuks.bentuk as nama_bentuk',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Identifikasi Kerawanan" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
                ->where('id_kabupaten',$qKabupaten->id)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->groupBy('bentuks.bentuk')
                ->pluck('count');

                $pendidikanCount=Formcegah::select(
                    'bentuks.bentuk as nama_bentuk',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Pendidikan" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
                ->where('id_kabupaten',$qKabupaten->id)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->groupBy('bentuks.bentuk')
                ->pluck('count');

                $partisipasiCount=Formcegah::select(
                    'bentuks.bentuk as nama_bentuk',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Partisipasi Masyarakat" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
                ->where('id_kabupaten',$qKabupaten->id)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->groupBy('bentuks.bentuk')
                ->pluck('count');

                $kerjasamaCount=Formcegah::select(
                    'bentuks.bentuk as nama_bentuk',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Kerjasama" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
                ->where('id_kabupaten',$qKabupaten->id)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->groupBy('bentuks.bentuk')
                ->pluck('count');

                $imbauanCount=Formcegah::select(
                    'bentuks.bentuk as nama_bentuk',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Imbauan" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
                ->where('id_kabupaten',$qKabupaten->id)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->groupBy('bentuks.bentuk')
                ->pluck('count');

                $kegiatanlainCount=Formcegah::select(
                    'bentuks.bentuk as nama_bentuk',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Kegiatan Lainnya" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
                ->where('id_kabupaten',$qKabupaten->id)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->groupBy('bentuks.bentuk')
                ->pluck('count');

                $publikasiCount=Formcegah::select(
                    'bentuks.bentuk as nama_bentuk',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Publikasi" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
                ->where('id_kabupaten',$qKabupaten->id)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->groupBy('bentuks.bentuk')
                ->pluck('count');

            }
            else{
                //serach by name
                $qProvinsi=Provinsi::where('provinsi',$name)->first();
                //dd($qProvinsi->id);

                $title=' Seluruh Provinsi '.$name;
                $categories=Formcegah::select('bentuks.bentuk as nama_bentuk')
                ->where('id_provinsi',$qProvinsi->id)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
                ->leftJoin('provinsis','formcegahs.id_provinsi','provinsis.id')
                ->groupBy('bentuks.bentuk')
                ->pluck('nama_bentuk');

                //dd($categories);

                $identifikasi_kerawananCount=Formcegah::select(
                    'bentuks.bentuk as nama_bentuk',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Identifikasi Kerawanan" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
                ->where('id_provinsi',$qProvinsi->id)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->groupBy('bentuks.bentuk')
                ->pluck('count');

                $pendidikanCount=Formcegah::select(
                    'bentuks.bentuk as nama_bentuk',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Pendidikan" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
                ->where('id_provinsi',$qProvinsi->id)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->groupBy('bentuks.bentuk')
                ->pluck('count');

                $partisipasiCount=Formcegah::select(
                    'bentuks.bentuk as nama_bentuk',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Partisipasi Masyarakat" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
                ->where('id_provinsi',$qProvinsi->id)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->groupBy('bentuks.bentuk')
                ->pluck('count');

                $kerjasamaCount=Formcegah::select(
                    'bentuks.bentuk as nama_bentuk',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Kerjasama" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
                ->where('id_provinsi',$qProvinsi->id)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->groupBy('bentuks.bentuk')
                ->pluck('count');

                $imbauanCount=Formcegah::select(
                    'bentuks.bentuk as nama_bentuk',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Imbauan" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
                ->where('id_provinsi',$qProvinsi->id)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->groupBy('bentuks.bentuk')
                ->pluck('count');

                $kegiatanlainCount=Formcegah::select(
                    'bentuks.bentuk as nama_bentuk',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Kegiatan Lainnya" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
                ->where('id_provinsi',$qProvinsi->id)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->groupBy('bentuks.bentuk')
                ->pluck('count');

                $publikasiCount=Formcegah::select(
                    'bentuks.bentuk as nama_bentuk',
                    DB::raw('
                    SUM(CASE
                    WHEN bentuks.bentuk="Publikasi" THEN 1
                    ELSE 0
                    END) AS count
                    ')
                )
                ->leftJoin('bentuks','formcegahs.bentuk','bentuks.id')
                ->where('id_provinsi',$qProvinsi->id)
                ->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$date_start, $date_finish])
                ->groupBy('bentuks.bentuk')
                ->pluck('count');
                //dd(json_encode($categories),json_encode($publikasiSum));
            }
        }
        else{
            return redirect('/graph');
        }

        //dd($qCegah->count());

        return view('graph.detail',compact(
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
}
