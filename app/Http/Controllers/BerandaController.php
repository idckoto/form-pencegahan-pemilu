<?php

namespace App\Http\Controllers;
use App\Models\Twp;
use App\Models\Tkp;
use App\Traits\RefreshTokenTrait;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use App\Models\Formcegah;
use App\Models\Tahapan;
use App\Models\Bentuk;
use App\Models\Tujuan;
use App\Models\Sasaran;
use App\Models\Jenis;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Petuga;
use App\Models\Wilayah;
use App\Models\Jabatan;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BerandaController extends Controller
{
    public function __construct()
    {
        if (!$this->middleware('auth:sanctum')) {
            return redirect('/signin');
        }
    }

    public function index()
    {
        return view('beranda.index');
    }


    public function statistik()
    {
        $user = User::where('id', Auth::user()->id)->first();

        $id_kec = $user->Kecamatan;
        $id_kabkota = $user->Kabkota;
        $id_provinsi = $user->Provinsi;

        if(Auth::user()->Jabatan=="Bawaslu Kecamatan") {
            $twp = Twp::where('kabkot', '=', Auth::user()->Provinsi.'00')
                    ->orWhere('kabkot', '=' , $user->KabKota)
                    ->get();
        } else if(Auth::user()->Jabatan=="Ketua atau Anggota Bawaslu Kabupaten/Kota") {
            $twp = Twp::where('kabkot', '=', Auth::user()->Provinsi.'00')
                    ->orWhere('kabkot', '=' , $user->KabKota)
                    ->get();
        } else if(Auth::user()->Jabatan=="Ketua atau Anggota Bawaslu Provinsi") {
            $twp = Twp::where('kabkot', '=', Auth::user()->Provinsi.'00')
                    ->orWhere('kabkot', '=' , $user->KabKota)
                    ->get();            
        } else if(Auth::user()->Jabatan=="Sekretariat Bawaslu Provinsi") {
            $twp = Twp::all();
        }

        if(isset(Auth::user()->Kecamatan)){
            $provinsi = Provinsi::where('id', Auth::user()->Provinsi)->first();
            $kabupaten = Kabupaten::where('id', Auth::user()->KabKota)->first();
            $kecamatan = Kecamatan::where('id', Auth::user()->Kecamatan)->first();
            $wilpil = "";
        }elseif(isset(Auth::user()->KabKota)){
            $provinsi = Provinsi::where('id', Auth::user()->Provinsi)->first();
            $kabupaten = Kabupaten::where('id', Auth::user()->KabKota)->first();
            $kecamatan = null;
            $wilpil = "";
        }elseif(isset(Auth::user()->Provinsi)){
            $provinsi = Provinsi::where('id', Auth::user()->Provinsi)->first();
            $kabupaten = null;
            $kecamatan = null;
            $wilpil = "";
        }else{
            $provinsi = null;
            $kabupaten = null;
            $kecamatan = null;
            $wilpil = null;
        }

        $warna = array(
            0 => 'bg-danger',
            1 => 'bg-success',
            2 => 'bg-warning'
        );
        $no = 0;
        return view('beranda.statistik', compact('twp','warna','no','provinsi','kabupaten','kecamatan','wilpil'));
    }

    public function statistikPemilihan($id,Request $request)
    {
        $twp_title = Twp::where('id', Crypt::decryptString($id))->first();
        $twp = Twp::where('id', Crypt::decryptString($id))->get();
        
        //$idUser = '1121';
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

        return view('beranda.statistikPemilihan', compact(
            'id',
            'twp',
            'twp_title',
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

    public function statistikShow($id,Request $request)
    {
        $wp = Twp::where('id', Crypt::decryptString($id))->first();
        
        //$idUser = '1121';
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

        return view('beranda.statistikShow', compact(
            'wp',
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

}
