<?php

namespace App\Http\Controllers;

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
use App\Models\Twp;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\File;

class InputformController extends Controller
{

    // use RefreshTokenTrait;
    public function __construct()
    {
        if(!$this->middleware('auth:sanctum')){
            return redirect('/signin');
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function cek(){
        echo date('Ymd');
    }
    public function index()
    {

        $form = Formcegah::where(['userinput'=>Auth::user()->id])->orderByDesc('id')->get();
        return view('opd.input_lap.listformx',compact('form'));
    }
    
public function gambar()
{
// Ambil data dari tabel Formcegah yang memenuhi kondisi
$formcegahs = Formcegah::where('namapt', 'not like', '%]%')->get();

foreach ($formcegahs as $formcegah) {
    // Dekode data JSON ke array PHP
    $namaptArray = json_decode($formcegah->namapt, true);

    // Cek jika hasilnya adalah array dan lakukan konversi
    if (is_array($namaptArray)) {
        // Ambil hanya nilai dari array
        $newNamapt = array_values($namaptArray);

        // Enkode kembali ke format JSON
        $formcegah->namapt = json_encode($newNamapt);

        // Simpan perubahan ke database
        $formcegah->save();
    }
}

    // $folderPath = public_path('ttd/');
    // $image_parts = explode(";base64,", $request->signed);
    // $image_type_aux = explode("image/", $image_parts[0]);
    // $image_type = $image_type_aux[1];
    // $image_base64 = base64_decode($image_parts[1]);
    // $file = $folderPath . uniqid() . '.'.$image_type;
    // $upload=file_put_contents($file, $image_base64);
    // dump(explode("ttd/", $file)[1]);
    // dd($file);
    // // $ttd->file();
    // // $file->storeAs($file, $image_base64);
    // return view('opd.input_lap.imageUpload');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function create()
    {
       
        $user = Auth::user()->email;
      
        if(Auth::user()->id_divisi<>'' || Auth::user()->id_divisi<>null){
            $petugas = Petuga::where('kd_petugas',Auth::user()->id_divisi)->get();
        }else{
            $petugas = Petuga::get();
        }

        $user = User::where('id', Auth::user()->id)->first();

        $id_kec = $user->Kecamatan;
        $id_kabkota = $user->Kabkota;
        $id_provinsi = $user->Provinsi;

        //dd($id_provinsi);

        if(Auth::user()->Jabatan=="Bawaslu Kecamatan") {
            $twp = Twp::where('kabkot', '=', $user->KabKota)
                    ->orWhere('kabkot', '=' , Auth::user()->Provinsi.'00')
                    ->orWhere('kp_id', '=' , 1)
                    ->orderBy('kp_id', 'ASC')
                    ->orderBy('kdpro', 'ASC')
                    ->get();
        } else if(Auth::user()->Jabatan=="Ketua atau Anggota Bawaslu Kabupaten/Kota") {
            $twp = Twp::where('kabkot','=', Auth::user()->Provinsi.'00')
                        ->orWhere('kabkot', '=' , $user->KabKota)
                        ->orWhere('kp_id','=',1)
                        ->orderBy('kp_id', 'ASC')
                        ->orderBy('kdpro', 'ASC')
                        ->get();
        } else if(Auth::user()->Jabatan=="Ketua atau Anggota Bawaslu Provinsi") {
            $twp = Twp::where('kdpro', Auth::user()->Provinsi)
                        ->orWhere('kp_id',1)
                        ->orderBy('kp_id', 'ASC')
                        ->orderBy('kdpro', 'ASC')
                        ->get();            
        } else if(Auth::user()->Jabatan=="Sekretariat Bawaslu Provinsi") {
            $twp = Twp::get();  
        }

        // dd($petugas);
        $wilayah = Wilayah::get();
        $tahapan = Tahapan::orderByDesc('id')->get();
        $tahapannon = Tahapan::whereIn('type',array('2','0'))->orderByDesc('id')->get();
        $bentuk = Bentuk::whereIn('type',array('1'))->orderByDesc('id')->get();
        $bentuknon = Bentuk::orderByDesc('id')->get();
        $tujuan = Tujuan::get();
        $sasaran = Sasaran::get();
        $jabatan = Jabatan::get();
        $jenis = Jenis::orderByDesc('id')->get();
        if(isset(Auth::user()->Kecamatan)){
            $provinsi = Provinsi::where('id', Auth::user()->Provinsi)->first();
            $kabupaten = Kabupaten::where('id', Auth::user()->KabKota)->first();
            $kecamatan = Kecamatan::where('id', Auth::user()->Kecamatan)->first();
            
        }elseif(isset(Auth::user()->KabKota)){
            $provinsi = Provinsi::where('id', Auth::user()->Provinsi)->first();
            $kabupaten = Kabupaten::where('id', Auth::user()->KabKota)->first();
            $kecamatan = null;

        }elseif(isset(Auth::user()->Provinsi)){
            $provinsi = Provinsi::where('id', Auth::user()->Provinsi)->first();
            $kabupaten = null;
            $kecamatan = null;

        }else{
            $provinsi = null;
            $kabupaten = null;
            $kecamatan = null;
        }
        return view('opd.input_lap.inputform', compact('twp', 'bentuknon','tahapan','tahapannon','bentuk','tujuan','sasaran','petugas','wilayah','jabatan','jenis','user','provinsi','kabupaten','kecamatan'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'divisi'     => 'required',
            'nspt'       => 'required',
            'tahap'      => 'required',
            'jenis'      => 'required',
            'tujuan'     => 'required',
            'sasaran'    => 'required',
            'tanggal'    => 'required',
            'tempat'     => 'required',
            'uraian'     => 'required',
            'tindaklanjut' => 'required',
            'wp_id' => 'required',
            // <!-- 'files'      => 'required|mimes:doc,docx,pdf,jpg,jpeg,png|max:2048', -->
            'files'      => 'required',
            'files.*'    => 'required|mimes:doc,docx,pdf,jpg,jpeg,png|max:3048',
        ]);        
        
        if(count(array_filter($request->namapt)) != count(array_filter($request->jabatan))){
            return redirect('/input-form')->with('error','Nama dan Jabatan Pelaksana Tidak Sesuai');
        }
        $provinsi=explode("-",$request->provinsi);
        // dd($provinsi);
        $cekKab=!isset(explode(".",$request->kabupaten)[1])? explode(".",$request->kabupaten)[0]:explode(".",$request->kabupaten);
        $hasilKab=is_array($cekKab)?$cekKab[1]:substr($cekKab,2);
        // dd($request->kecamatan);
        if($provinsi[0]==''){
            $gabung=$request->divisi.','.''.','.date('Y');
        }elseif(!$request->kabupaten){
            $gabung=$request->divisi.','.$provinsi[1].','.date('Y');
        }elseif(!$request->kecamatan){
            $gabung=$request->divisi.','.$provinsi[1].'.'.$hasilKab.','.date('Y');
        }else{
            $gabung=$request->divisi.','.$provinsi[1].'.'.$hasilKab.'.'.$request->kecamatan.','.date('Y');
        }
        // $gabung=$request->divisi.','.$provinsi[1].'.'.$hasilKab.','.$request->kecamatan.','.''.','.date('Y');
        $folderPath = public_path('ttd/');
        $image_parts = explode(";base64,", $request->signed);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file = $folderPath . uniqid() .date('Ymd').'.'.$image_type;
        file_put_contents($file, $image_base64);
        $exp=explode("ttd/", $file);
        $file = $request->file('file');
        $files = [];
        foreach($request->file('files') as $filex){
            $filex->storeAs('public/bukti', $filex->hashName());
            $files[] = $filex->hashName();
        }
        $formcegah = new Formcegah;
        $formcegah->no_form = $gabung;
        $formcegah->namapt = json_encode(array_values(array_filter($request->namapt)));
        $formcegah->jabatan = json_encode(array_values(array_filter($request->jabatan)));
        $formcegah->nspt = $request->nspt;
        $formcegah->tahap = isset($request->tahap)?$request->tahap:'Tahap';
        $formcegah->tahaps = isset($request->tahaps)?$request->tahaps:$request->tahap_non;
        $formcegah->tahap_lain = $request->tahap_lainnya;
        $formcegah->bentuk = isset($request->bentuk)?$request->bentuk:$request->bentuknon;
        $formcegah->jenis = $request->jenis;
        $formcegah->tujuan = $request->tujuan;
        $formcegah->bentuk_lain = $request->bentuk_lain;
        $formcegah->sasaran = $request->sasaran;
        $formcegah->jenis_lain = $request->jenis_lain;
        $formcegah->tanggal = $request->tanggal;
        $formcegah->id_provinsi = $provinsi[0];
        $formcegah->id_kabupaten = is_array($cekKab)?explode("-",$cekKab[0])[0]:$cekKab;
        $formcegah->id_kecamatan = $request->kecamatan;
        $formcegah->id_kelurahan = $request->kelurahan;
        $formcegah->tempat = $request->tempat;
        $formcegah->uraian = $request->uraian;
        $formcegah->tindaklanjut = $request->tindaklanjut;
        $formcegah->userinput = Auth::user()->id;
        $formcegah->id_divisi = $request->divisi;
        $formcegah->ttd = $exp[1];
        $formcegah->bukti = json_encode($files);
        $formcegah->repo = $request->repo;
        $formcegah->stts = '0';
        $formcegah->wp_id = $request->wp_id;
        $formcegah->save();
        return redirect('/list-form')->with('status','Berhasil Tersimpan');
    }

    public function edit($id)
    {
        $tahapan = Tahapan::orderByDesc('id')->get();
        $wilayah = Wilayah::get();
        $bentuk = Bentuk::whereIn('type',array('1'))->orderByDesc('id')->get();
        $bentuknon = Bentuk::orderByDesc('id')->get();
        $jenis = Jenis::orderByDesc('id')->get();
        $tujuan = Tujuan::get();
        $sasaran = Sasaran::get();
        $jabatan = Jabatan::get();
        $form=Formcegah::where('id',Crypt::decryptString($id))->first();
        $petugas = Petuga::where('kd_petugas', explode('.',explode("/", $form->no_form)[2])[0])->first();
           
        $provinsi = Provinsi::where('id', $form->id_provinsi)->first();
        // $provinsi = Provinsi::where('id', $form->id_provinsi)->value('id') ?? '';
        $kabupaten = Kabupaten::where('id', $form->id_kabupaten)->first();
        $kecamatan = Kecamatan::where('id', $form->id_kecamatan)->first();
        $kelurahan = Kelurahan::where('id', $form->id_kelurahan)->first();
    //    dd($petugas);
       return view('opd.input_lap.editform',compact('jenis','bentuknon','form','id','tahapan','bentuk','tujuan','sasaran','petugas','wilayah','provinsi','kabupaten','kecamatan','kelurahan','jabatan'));
    }

    public function update(Request $request)
    {
        $request->validate([
            // <!-- 'files'      => 'required|mimes:doc,docx,pdf,jpg,jpeg,png|max:2048', -->
            'tahap'   => 'required',
            'Oldfile'      => 'required'
        ]);
        // dd($request->tahaps);
        // $request->validate([
        //     'divisi'   => 'required',
        //     'jabatan'   => 'required',
        //     'nspt'   => 'required',
        //     'tahap'   => 'required',
        //     'tahaps'   => 'required',
        //     'bentuk'   => 'required',
        //     'jenis'   => 'required',
        //     'tujuan'   => 'required',
        //     'sasaran'   => 'required',
        //     'tanggal'   => 'required',
        //     'provinsi'   => 'required',
        //     'kabupaten'   => 'required',
        //     'kecamatan'   => 'required',
        //     'kelurahan'   => 'required',
        //     'tempat'   => 'required',
        //     'uraian'   => 'required',
        //     'tindaklanjut'   => 'required',
        //     'files'          => 'required',
        //     'files.*'          => 'required|mimes:doc,docx,PDF,pdf,jpg,jpeg,png|max:5000',
        // ]);
        // dd(array_filter$request->namapt));
        if(count(array_filter($request->namapt)) != count(array_filter($request->jabatan))){
            return redirect('/edit-pencegah/'.$request->id)->with('error','Nama dan Jabatan Pelaksana Tidak Sesuai');
        }
        $provinsi=explode("-",$request->provinsi);
        // $gabung=$request->divisi.','.$provinsi[1].'.'.substr($request->kabupaten,2).','.$request->kecamatan.','. date('n').','.date('Y');
        // $folderPath = public_path('ttd/');
        // // dd($request->signed);
        // $image_parts = explode(";base64,", $request->signed);
        // $image_type_aux = explode("image/", $image_parts[0]);
        // $image_type = $image_type_aux[1];
        // $image_base64 = base64_decode($image_parts[1]);
        // $file = $folderPath . uniqid() .$request->namapt[0].'.'.$image_type;
        // file_put_contents($file, $image_base64);
        // $exp=explode("ttd/", $file);
        $file = $request->file('files');
        if (isset($file)) {
        $files = [];
        foreach($request->file('files') as $filex){
            $filex->storeAs('public/bukti', $filex->hashName());
            $files[] = $filex->hashName();
        }
        $inFile=json_decode($request->Oldfile);
        $inFile=json_encode(array_merge($inFile,$files));
    }else{
        $inFile=$request->Oldfile;
    }
        // dd(Crypt::decryptString($request->id));
        $formCegah = Formcegah::find(Crypt::decryptString($request->id));
        $formCegah->namapt = json_encode(array_filter($request->namapt));
        $formCegah->jabatan = json_encode(array_filter($request->jabatan));
        $formCegah->nspt = $request->nspt;
        // $formCegah->alamat = $request->alamat;
        $formCegah->tahap = isset($request->tahap)?$request->tahap:'Tahap';
        $formCegah->tahaps = isset($request->tahaps)?$request->tahaps:$request->tahap_non;
        $formCegah->tahap_lain = $request->tahap_lainnya;
        $formCegah->bentuk = isset($request->bentuk)?$request->bentuk:$request->bentuknon;
        $formCegah->bentuk_lain = $request->bentuk_lain;
        $formCegah->jenis = $request->jenis;
        $formCegah->jenis_lain = $request->jenis_lain;
        $formCegah->tujuan = $request->tujuan;
        $formCegah->sasaran = $request->sasaran;
        $formCegah->tanggal = $request->tanggal;
        $formCegah->id_provinsi = $provinsi[0];
        $formCegah->id_kabupaten = $request->kabupaten;
        $formCegah->id_kecamatan = $request->kecamatan;
        $formCegah->id_kelurahan = $request->kelurahan;
        $formCegah->tempat = $request->tempat;
        $formCegah->uraian = $request->uraian;
        $formCegah->tindaklanjut = $request->tindaklanjut;
        $formCegah->userinput = Auth::user()->id;
        // $formCegah->ttd = $exp[1];
        $formCegah->bukti = $inFile;
        $formCegah->save();
        return redirect('/list-form')->with('status','Berhasil Terupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $decryptedId = Crypt::decryptString($request->id);
        $cek = Formcegah::where('id', $decryptedId)->first();

        if ($cek) {
            // Hapus ttd
            File::delete(public_path('ttd/' . $cek->ttd));

            // Hapus bukti gambar
            foreach (json_decode($cek->bukti) as $gambar) {
                Storage::delete('public/bukti/' . $gambar);
            }

            // Hapus record
            Formcegah::destroy($decryptedId);

            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error'
            ]);
        }

    }
//     public function destroy_cek($id)
//     {
// // dd("asdf");
//         $cek = Formcegah::where('id',$id)->first();
//         File::delete(public_path('ttd/'.$cek->ttd));
//         foreach (json_decode($cek->bukti) as $gambar){
//             Storage::delete('public/bukti/'.$gambar);
//         }
//         Formcegah::destroy($request->id);
//         if ($cek) {
//             return response()->json([
//                 'status' => 'success'
//             ]);
//         } else {
//             return response()->json([
//                 'status' => 'error'
//             ]);
//         }
//     }

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

    public function download_file($file)
    {

        if ($file) {
            $filePath = Storage::disk('public')->path('bukti/' . $file);
            
            if (file_exists($filePath)) {
                return response()->download($filePath, $file);
            } else {
                echo "File tidak ditemukan!";
            }
        } else {
            echo "kosong";
        }

        
    }
    public function destroyBukti(Request $request)
    {
        $form = Formcegah::where('id', Crypt::decryptString($request->id))->first();

        if ($form) {
            Storage::delete('public/bukti/' . $request->nama);
            $down = array_diff(json_decode($form->bukti), [$request->nama]);
        
            $formCegah = Formcegah::find(Crypt::decryptString($request->id));
            $formCegah->bukti = $down;
            $formCegah->save();
            
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Form tidak ditemukan'
            ]);
        }
        
    }
    public function cetakForm($id)
    {
        $form=Formcegah::where('id',Crypt::decryptString($id))->first();
        $tahapan = Tahapan::where('id',$form->tahaps)->first();
        $bentuk = Bentuk::where('id',$form->bentuk)->first();
        $jenis = Jenis::where('id',$form->jenis)->first();
        $tujuan = Tujuan::where('id',$form->tujuan)->first();
        $sasaran = Sasaran::where('id',$form->sasaran)->first();
        $provinsi = Provinsi::where('id', $form->id_provinsi)->first();
        $kabupaten = Kabupaten::where('id', $form->id_kabupaten)->first();
        // dd($kabupaten);
        return view('opd.input_lap.cetak_form',compact('provinsi','form','tahapan','bentuk','tujuan','sasaran','jenis','kabupaten'));
    }
    public function submit(Request $request)
    {
        $form = Formcegah::find(Crypt::decryptString($request->id));
        $form->stts = '1';
        $form->save();
        if ($form) {
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error'
            ]);
        }
    }

    public function lapFrom()
    {
        // return $response = Http::acceptJson()->get('https://formpencegahan.bawaslu.go.id/api/petugas');

        // if (Auth::user()->id_admin=='0' || Auth::user()->id_admin=='0') {
        //     $form = Formcegah::orderByDesc('id')->get();
        // } elseif(Auth::user()->KabKota == null){
        //     $form = Formcegah::where(['id_provinsi'=>Auth::user()->Provinsi])->orderByDesc('id')->get();
        // } elseif(Auth::user()->Kecamatan == null){
        //     $form = Formcegah::where(['id_kabupaten'=>Auth::user()->KabKota])->orderByDesc('id')->get();
        // } else {
        //     $form = Formcegah::where(['id_kecamatan'=>Auth::user()->Kecamatan])->orderByDesc('id')->get();
        // }
        
        // $form = Formcegah::where(['userinput'=>Auth::user()->ID])->orderByDesc('id')->get();
        return view('opd.input_lap.laporanformx');
    }

    public function getFormcegahData(Request $request)
{
    $columns = ['id', 'no_form', 'tahap', 'namapt', 'wp_id']; // Sesuai dengan kolom database

    // Memetakan request dari DataTables
    $columnIndex = $request->input('order.0.column'); // Index kolom untuk pengurutan
    $columnName = $columns[$columnIndex]; // Nama kolom untuk pengurutan
    $columnSortOrder = $request->input('order.0.dir'); // Arah pengurutan (asc/desc)
    $searchValue = $request->input('search.value'); // Nilai pencarian

    // Query berdasarkan role atau lokasi pengguna
    //$query = Formcegah::query();
    //$query = Formcegah::join(['wp', 'wp.id', '=', 'formcegahs.wp_id']);
    $query = DB::table('formcegahs as f')
                    ->join('twp as w', 'w.id', '=', 'f.wp_id')
                    ->leftJoin('tahapans as t', 't.id', '=', 'f.tahaps');

    if (Auth::user()->id_admin == '0') {
        // Tambahkan logika untuk admin
    } elseif (Auth::user()->KabKota == null) {
        $query->where('id_provinsi', Auth::user()->Provinsi);
    } elseif (Auth::user()->Kecamatan == null) {
        $query->where('id_kabupaten', Auth::user()->KabKota);
    } else {
        $query->where('id_kecamatan', Auth::user()->Kecamatan);
    }

    // Pencarian
    if (!empty($searchValue)) {
        $query->where(function($query) use ($searchValue) {
            $query->where('no_form', 'like', '%' . $searchValue . '%')
                  ->orWhere('tahap', 'like', '%' . $searchValue . '%')
                  ->orWhere('w.nama_wp', 'like', '%' . $searchValue . '%')
                  ->orWhere('t.tahapan', 'like', '%' . $searchValue . '%');
        });
    }

    // Total record dengan filter
    $totalFiltered = $query->count();

    // Pengurutan dan Pagination
    $queryResult = $query->skip($request->input('start'))
                  ->take($request->input('length'))
                  ->orderBy($columnName, 'desc')
                  ->get(['f.id as id', 'f.no_form as no_form', 'f.tahap as tahap', 'f.tahaps as tahaps', 'f.namapt', 'w.nama_wp as wp_id', 'f.created_at', 't.tahapan as tahapan']);

    // Memodifikasi data sebelum dikirim ke DataTables
    $data = $queryResult->map(function ($item) {
        $pecah = explode('/', $item->no_form);
        $bulan = explode('-', explode(' ', $item->created_at)[0])[1];
        $item->no_form = $pecah[0].'/'.$pecah[1].'/'.$pecah[2].'/'.ltrim($bulan, '0').'/'.$pecah[4];

        if($item->tahap=="Tahapan"){
            //$item->tahap = $item->tahapan->tahapan;
            //$tahap = \App\Models\Tahapan::where('id',$item->tahaps)->get(['tahapan']);
            //$item->tahap = $tahap[0]['tahapan'];
            //$item->tahap = $item->tahaps;
            $item->tahap = $item->tahapan;
        } else {
            $item->tahap = $item->tahap;
        }

        $namaPtDecoded = json_decode($item->namapt);
        if(is_array($namaPtDecoded) && count($namaPtDecoded) > 0) 
        {
            $item->namapt = $namaPtDecoded[0] ;
        } else {
            $item->namapt;
        }
       
        $item->wp_id = $item->wp_id; //$item->wp->nama_wp;

        $encryptedId = Crypt::encryptString($item->id);
        $item->cetak = '<a class="btn btn-primary btn-sm" href="'.url('/cetak-form/' . $encryptedId).'"><i class="fas fa-print"></i> Cetak</a>';

        return $item;
    });

    //dd($data);

    // Total record tanpa filter
    $totalData = Formcegah::count();

    // Persiapan data untuk DataTables
    $response = array(
        "draw" => intval($request->input('draw')),
        "recordsTotal" => intval($totalData),
        "recordsFiltered" => intval($totalFiltered),
        "data" => $data
    );

    return response()->json($response);
}

}
