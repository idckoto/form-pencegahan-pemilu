<?php

namespace App\Http\Controllers\api;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Models\Formcegah;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Jenis;
use App\Models\Tahapan;
use App\Models\Bentuk;
use App\Models\Tujuan;
use App\Models\Sasaran;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Petuga;
use App\Traits\RefreshTokenTrait;
use Illuminate\Support\Facades\Storage;


class PencegahanController extends Controller
{
    use RefreshTokenTrait;

    private function getParams($request)
    {
        return [
            'limit' => $request->input('limit', 10),
            'offset' => $request->input('offset', 0),
            'search' => $request->input('search', ''),
            'order_by' => $request->input('order_by', 'id'),
            'sort_by' => $request->input('sort_by', 'asc'),
        ];
    }

    public function getFormcegah(Request $request, $id)
    {

        // Get additional query parameters from the request
        $limit = $request->input('limit', 10);
        $offset = $request->input('offset', 0);
        $search = $request->input('search');
        $order_by = $request->input('order_by', 'id');
        $sort_by = $request->input('sort_by', 'desc');

        // Query the User table to find a user based on the provided email
        $user = User::where('email', $id)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Continue with the rest of your query and response code
        $query = Formcegah::leftjoin('provinsis', 'formcegahs.id_provinsi', '=', 'provinsis.id')
            ->leftjoin('kabupatens', 'formcegahs.id_kabupaten', '=', 'kabupatens.id')
            ->leftjoin('kecamatans', 'formcegahs.id_kecamatan', '=', 'kecamatans.id')
            ->leftjoin('tahapans', 'formcegahs.tahaps', '=', 'tahapans.id')
            ->leftjoin('bentuks', 'formcegahs.bentuk', '=', 'bentuks.id')
            ->leftjoin('jenis', 'formcegahs.jenis', '=', 'jenis.id')
            ->select(
                'formcegahs.*',
                'provinsis.provinsi',
                'kabupatens.kabupaten',
                'kecamatans.kecamatan',
                'tahapans.tahapan as nm_tahapan',
                'bentuks.bentuk as nm_bentuk',
                'jenis.jenis as nm_jenis'
            )
            ->where('userinput', $user->id);

        // Apply search filter if provided
        if ($search) {
            $query->where(function ($innerQuery) use ($search) {
                $innerQuery->where('formcegahs.tahap', 'like', '%' . $search . '%')
                    ->orWhere('provinsis.provinsi', 'like', '%' . $search . '%')
                    ->orWhere('kabupatens.kabupaten', 'like', '%' . $search . '%')
                    ->orWhere('kecamatans.kecamatan', 'like', '%' . $search . '%');
            });
        }

        // Apply sorting and ordering
        $query->orderBy($order_by, $sort_by);

        // Apply limit and offset
        $query->limit($limit)->offset($offset);

        // Get the final result
        $data = $query->get();

        // Modify the namapt, jabatan, and bukti format for each data item
        foreach ($data as &$item) {
            if (isset($item->namapt)) {
                $item->namapt = json_decode($item->namapt);  // Parse the string into an array
            }
            if (isset($item->jabatan)) {
                $item->jabatan = json_decode($item->jabatan);  // Parse the string into an array
            }
            if (isset($item->bukti)) {
                $item->bukti = json_decode($item->bukti);  // Parse the string into an array
            }
        }

        // Return the retrieved data as a JSON response
        return response()->json($data);


    }

    public function lapFrom(Request $request, $id)
    {
            // Mencari pengguna dari tabel User berdasarkan email yang diberikan
            $pengguna = User::where('email', $id)->first();

            if (!$pengguna) {
                return response()->json(['error' => 'Pengguna tidak ditemukan'], 404);
            }

            // Mendapatkan parameter tambahan dari permintaan
            $limit = $request->input('limit', 10);
            $offset = $request->input('offset', 0);
            $search = $request->input('search');
            $order_by = $request->input('order_by', 'id');
            $sort_by = $request->input('sort_by', 'desc');

            // Mulai query dengan join ke tabel-tabel lainnya
            $query = Formcegah::leftjoin('provinsis', 'formcegahs.id_provinsi', '=', 'provinsis.id')
                ->leftjoin('kabupatens', 'formcegahs.id_kabupaten', '=', 'kabupatens.id')
                ->leftjoin('kecamatans', 'formcegahs.id_kecamatan', '=', 'kecamatans.id')
                ->leftjoin('tahapans', 'formcegahs.tahaps', '=', 'tahapans.id')
                ->leftjoin('bentuks', 'formcegahs.bentuk', '=', 'bentuks.id')
                ->leftjoin('jenis', 'formcegahs.jenis', '=', 'jenis.id')
                ->select(
                    'formcegahs.*',
                    'provinsis.provinsi',
                    'kabupatens.kabupaten',
                    'kecamatans.kecamatan',
                    'tahapans.tahapan as nm_tahapan',
                    'bentuks.bentuk as nm_bentuk',
                    'jenis.jenis as nm_jenis'
                )
                ->where('userinput', $pengguna->id);

            // Menambahkan filter berdasarkan KabKota dan Kecamatan
            if ($pengguna->id_admin == '0') {
                // Jika admin, tidak perlu filter tambahan
            } elseif (is_null($pengguna->KabKota) || $pengguna->KabKota == '0') {
                $query->where(['formcegahs.id_provinsi' => $pengguna->Provinsi]);
            } elseif (is_null($pengguna->Kecamatan) || $pengguna->Kecamatan == '0') {
                // dd($pengguna->KabKota);
                $query->where(['formcegahs.id_kabupaten' => $pengguna->KabKota]);
            } else {
                $query->where(['formcegahs.id_kecamatan' => $pengguna->Kecamatan]);
            }

            // Menambahkan filter pencarian
            if ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('formcegahs.tahap', 'like', '%' . $search . '%')
                        ->orWhere('provinsis.provinsi', 'like', '%' . $search . '%')
                        ->orWhere('kabupatens.kabupaten', 'like', '%' . $search . '%')
                        ->orWhere('kecamatans.kecamatan', 'like', '%' . $search . '%');
                });
            }

            // Menambahkan urutan dan pemilahan
            $query->orderBy($order_by, $sort_by);

            // Menambahkan batas dan offset
            $query->limit($limit)->offset($offset);

            // Mengambil hasil akhir
            $data = $query->get();

            // Modifikasi format namapt, jabatan, dan bukti untuk setiap data
            foreach ($data as &$item) {
                if (isset($item->namapt)) {
                    $item->namapt = json_decode($item->namapt);  // Mengurai string menjadi array
                }
                if (isset($item->jabatan)) {
                    $item->jabatan = json_decode($item->jabatan);  // Mengurai string menjadi array
                }
                if (isset($item->bukti)) {
                    $item->bukti = json_decode($item->bukti);  // Mengurai string menjadi array
                }
            }

            // Mengembalikan data yang diambil sebagai respons JSON
            return response()->json($data);

    }
    
    public function index(Request $request)
    {
        $params = $this->getParams($request);

        $query = Formcegah::query();

        $query->when(!empty($params['search']), function ($query) use ($params) {
            $query->where(function ($query) use ($params) {
                $query->where('no_form', 'like', '%' . $params['search'] . '%')
                    ->orWhere('namapt', 'like', '%' . $params['search'] . '%')
                    ->orWhere('jabatan', 'like', '%' . $params['search'] . '%')
                    ->orWhere('nspt', 'like', '%' . $params['search'] . '%')
                    ->orWhere('tahap', 'like', '%' . $params['search'] . '%')
                    ->orWhere('tahaps', 'like', '%' . $params['search'] . '%')
                    ->orWhere('tahap_lain', 'like', '%' . $params['search'] . '%')
                    ->orWhere('bentuk', 'like', '%' . $params['search'] . '%')
                    ->orWhere('id_provinsi', 'like', '%' . $params['search'] . '%')
                    ->orWhere('id_kabupaten', 'like', '%' . $params['search'] . '%')
                    ->orWhere('id_kecamatan', 'like', '%' . $params['search'] . '%')
                    ->orWhere('id_kelurahan', 'like', '%' . $params['search'] . '%');
            });
        });

        if ($params['order_by'] == 'id' || $params['order_by'] == 'no_form' || $params['order_by'] == 'tahap') {
            $query->orderBy($params['order_by'], $params['sort_by']);
        }

        $data = $query->limit($params['limit'])
            ->offset($params['offset'])
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }
    public function destroy(Request $request,$id)
    {
        $cek = Formcegah::where('id', $request->id)->first();
        if ($cek) {
            // Hapus ttd
            File::delete(public_path('ttd/' . $cek->ttd));

            // Hapus bukti gambar
            foreach (json_decode($cek->bukti) as $gambar) {
                Storage::delete('public/bukti/' . $gambar);
            }

            // Hapus record
            Formcegah::destroy($request->id);

            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error'
            ]);
        }

    }
    public function store(Request $request, $id)
{

    $user = User::where('email', $id)->first();

    if (!$user) {
        return response()->json("User Tidak ada");
    }

    $cekprovinsi = Provinsi::where('id', $user->Provinsi)
        ->select(DB::raw("CONCAT(id, '-', sni) AS id_sni"), 'id')
        ->first();

    $cekkabupaten = Kabupaten::where('id', $user->KabKota)->first();
    $cekkecamatan = Kecamatan::where('id', $user->Kecamatan)->first();
    $cekkelurahan = Kelurahan::where('id', $user->DesKel)->first();
    $cekpetugas = Petuga::where('kd_petugas', $user->id_divisi)->first();

    if (count(array_filter($request->namapt)) != count(array_filter($request->jabatan))) {
        return response()->json('Nama dan Jabatan Pelaksana Tidak Sesuai');
    }

    if (isset($request->provinsi)) {
        $provinsi = $request->provinsi;
    } elseif (isset($cekprovinsi->id)) {
        $provinsi = $cekprovinsi->id_sni;
    } else {
        return response()->json('Provinsi Tidak Boleh Kosong');
    }
    $kabupaten = isset($request->kabupaten) ? $request->kabupaten : (isset($cekkabupaten->id) ? $cekkabupaten->sni : '');
    $kecamatan = isset($request->kecamatan) ? $request->kecamatan : (isset($cekkecamatan->id) ? $cekkecamatan->id : '');
    $kelurahan = isset($request->kelurahan) ? $request->kelurahan : (isset($cekkelurahan->id) ? $cekkelurahan->id : '');
    if(isset($request->divisi)) {
        $petugas = $request->divisi;
    } elseif(isset($cekpetugas->kd_petugas)) {
        $petugas = $cekpetugas->kd_petugas;
    } else {
        return response()->json('Divisi Tidak Boleh Kosong');
    }

    $provinsiArray = explode("-", $provinsi);
    $kabArray = explode(".", $kabupaten);
    $cekKab = !isset($kabArray[1]) ? $kabArray[0] : $kabArray;
    $hasilKab = is_array($cekKab) ? $cekKab[1] : substr($cekKab, 2);

    if ($provinsiArray[0] == '') {
        $gabung = "{$petugas},," . date('Y');
    } elseif (!$kabupaten) {
        $gabung = "{$petugas},{$provinsiArray[1]}," . date('Y');
    } elseif (!$kecamatan) {
        $gabung = "{$petugas},{$provinsiArray[1]}.{$hasilKab}," . date('Y');
    } else {
        $gabung = "{$petugas},{$provinsiArray[1]}.{$hasilKab}.{$kecamatan}," . date('Y');
    }

    $imageName = uniqid() . $request->namapt[0] . '.' . $request->signed->extension();
    $imagePath = public_path('ttd/' . $imageName);
    file_put_contents($imagePath, file_get_contents($request->signed->getRealPath()));

    $files = [];
    foreach ($request->file('files') as $filex) {
        $filex->storeAs('public/bukti', $filex->hashName());
        $files[] = $filex->hashName();
    }

    $formcegah = new Formcegah;
    $formcegah->fill([
        'no_form' => $gabung,
        'namapt' => json_encode(array_filter($request->namapt)),
        'jabatan' => json_encode(array_filter($request->jabatan)),
        'nspt' => $request->nspt,
        'tahap' => $request->tahap,
        'tahaps' => $request->tahaps,
        'tahap_lain' => $request->tahap_lainnya,
        'bentuk' => $request->bentuk ?? $request->bentuknon,
        'jenis' => $request->jenis,
        'tujuan' => $request->tujuan,
        'bentuk_lain' => $request->bentuk_lain,
        'sasaran' => $request->sasaran,
        'jenis_lain' => $request->jenis_lain,
        'tanggal' => $request->tanggal,
        'id_provinsi' => $provinsiArray[0],
        'id_kabupaten' => $kabupaten ? $kabArray[0] . $kabArray[1] : null,
        'id_kecamatan' => $kecamatan ?: null,
        'id_kelurahan' => $kelurahan ?: null,
        'tempat' => $request->tempat,
        'uraian' => $request->uraian,
        'tindaklanjut' => $request->tindaklanjut,
        'userinput' => $user->id,
        'id_divisi' => $petugas,
        'ttd' => $imageName,
        'bukti' => json_encode($files),
        'repo' => $request->repo,
        'stts' => '0'
    ]);

    if ($formcegah->save()) {
        return response()->json('Berhasil Disimpan');
    } else {
        return response()->json('Gagal Disimpan');
    }
}

    function viewForm($id,$idform){
        // dd($idform);
        $form = Formcegah::join('tahapans', 'formcegahs.tahaps', '=', 'tahapans.id')
        ->join('bentuks', 'formcegahs.bentuk', '=', 'bentuks.id')
        ->join('jenis', 'formcegahs.jenis', '=', 'jenis.id')
        ->join('provinsis', 'formcegahs.id_provinsi', '=', 'provinsis.id')
        ->join('kabupatens', 'formcegahs.id_kabupaten', '=', 'kabupatens.id')
        ->where('formcegahs.id', $idform)
        ->select('formcegahs.*', 'tahapans.tahapan', 'bentuks.bentuk', 'jenis.jenis', 'provinsis.provinsi', 'kabupatens.kabupaten')
        ->first();
    
    if ($form) {
        $form->namapt = json_decode($form->namapt);
        $form->jabatan = json_decode($form->jabatan);
        $form->bukti = json_decode($form->bukti);
    
        // Mengambil bulan dari $form->created_at dan menghilangkan "0" di depan
        $monthFromCreatedAt = ltrim($form->created_at->format('m'), '0');
    
        if (isset($form->no_form)) {
            $form->no_form = preg_replace('/(\/)(\/)/', "/$monthFromCreatedAt/", $form->no_form);
        }
    }        
    if ($form) {
        return response()->json($form);
    } else {
        return response()->json(['error' => 'Data tidak ditemukan'], 404);
    }
    
    }

    public function update(Request $request, $id)
    {
        $provinsi = explode("-", $request->provinsi);

        // Pengolahan Gambar
        if ($request->hasFile('signed')) {
            $namaGambar = uniqid() . $request->namapt[0] . '.' . $request->signed->extension();
            $pathGambar = public_path('ttd/' . $namaGambar);
            file_put_contents($pathGambar, file_get_contents($request->signed->getRealPath()));
            
            // Menghapus Gambar Lama
            $pathGambarLama = public_path('ttd/' . $request->ttdOld);
            if (file_exists($pathGambarLama)) {
                unlink($pathGambarLama);
            }
        }

        // Pengolahan Berkas
        $berkas = $request->file('files');
        if (isset($berkas)) {
            $daftarBerkas = [];
            foreach ($berkas as $filex) {
                $filex->storeAs('public/bukti', $filex->hashName());
                $daftarBerkas[] = $filex->hashName();
            }
            
            $fileLama = json_decode($request->Oldfile);
            $fileGabungan = json_encode(array_merge($fileLama, $daftarBerkas));
        } else {
            $fileGabungan = $request->Oldfile;
        }

        // Memperbarui Objek Formcegah
        $formCegah = Formcegah::find($request->id_formcegah);
        $formCegah->fill([
            'namapt' => json_encode(array_filter($request->namapt)),
            'jabatan' => $request->jabatan,
            'nspt' => $request->nspt,
            'tahap' => $request->tahap,
            'tahaps' => $request->tahaps,
            'tahap_lain' => $request->tahap_lainnya,
            'bentuk' => $request->bentuk ?? $request->bentuknon,
            'bentuk_lain' => $request->bentuk_lain,
            'jenis' => $request->jenis,
            'jenis_lain' => $request->jenis_lain,
            'tujuan' => $request->tujuan,
            'sasaran' => $request->sasaran,
            'tanggal' => $request->tanggal,
            'id_provinsi' => $provinsi[0],
            'id_kabupaten' => $request->kabupaten,
            'id_kecamatan' => $request->kecamatan,
            'id_kelurahan' => $request->kelurahan,
            'tempat' => $request->tempat,
            'uraian' => $request->uraian,
            'tindaklanjut' => $request->tindaklanjut,
            'ttd' => $request->hasFile('signed') ? $namaGambar : $request->ttdOld,
            'bukti' => $fileGabungan,
        ]);

        if ($formCegah->save()) {
            return response()->json('Berhasil Diperbarui');
        } else {
            return response()->json('Gagal Diperbarui');
        }

    }

    public function destroyBukti(Request $request, $id)
    {
        try {
            // Retrieve the Formcegah record or fail if not found
            $formCegah = Formcegah::findOrFail($request->id_formcegah);
            
            // Ensure that $formCegah->bukti is a valid JSON array
            $buktis = json_decode($formCegah->bukti, true);
            if (!is_array($buktis)) {
                return response()->json('Invalid bukti data format');
            }
    
            // Remove the specified file from the list
            $down = array_diff($buktis, [$request->namaFile]);
            
            // Delete the file from storage
            Storage::delete('public/bukti/' . $request->namaFile);
            
            // Update the bukti attribute and save the changes
            $formCegah->bukti = json_encode($down);
            if ($formCegah->save()) {
                return response()->json('Berhasil DiHapusBUkti');
            } else {
                return response()->json('Gagal DiHapusBUkti');
            }
    
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json('Record not found');
        } catch (\Exception $e) {
            // Handle other potential errors
            return response()->json('An error occurred: ' . $e->getMessage());
        }
    }
    

    public function submit(Request $request, $id)
    {
        $form = Formcegah::find($request->id_formcegah);

        if (!$form) {
            // Tidak ada catatan yang ditemukan untuk ID yang diberikan
            return response()->json([
                'status' => 'error',
                'message' => 'Formcegah tidak ditemukan'
            ]);
        }

        $form->stts = '1';
        $form->save();

        return response()->json([
            'status' => 'success'
        ]);

    }
}
