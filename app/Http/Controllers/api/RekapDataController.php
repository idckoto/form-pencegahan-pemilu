<?php

namespace App\Http\Controllers\api;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Jenis;
use App\Models\Tahapan;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Validator;
use App\Models\Formcegah;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Pengguna;
use App\Models\Provinsi;

class RekapDataController extends Controller
{
	public function rekap(Request $request)
	{
		if ($request->jenis_rekap == 'Wilayah') {
			//dd('provinsi nih');
			$categories = Formcegah::select('provinsis.provinsi as provinsi')
				->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$request->from, $request->until])
				->leftJoin('provinsis', 'formcegahs.id_provinsi', 'provinsis.id')
				->groupBy('provinsis.provinsi')
				->pluck('provinsi');

			$identifikasi_kerawananCount = Formcegah::select(
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
				->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$request->from, $request->until])
				->groupBy('provinsis.provinsi')
				->pluck('count');


			$pendidikanCount = Formcegah::select(
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
				->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$request->from, $request->until])
				->groupBy('provinsis.provinsi')
				->pluck('count');

			$partisipasiCount = Formcegah::select(
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
				->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$request->from, $request->until])
				->groupBy('provinsis.provinsi')
				->pluck('count');

			$kerjasamaCount = Formcegah::select(
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
				->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$request->from, $request->until])
				->groupBy('provinsis.provinsi')
				->pluck('count');

			$imbauanCount = Formcegah::select(
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
				->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$request->from, $request->until])
				->groupBy('provinsis.provinsi')
				->pluck('count');

			$kegiatanlainCount = Formcegah::select(
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
				->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$request->from, $request->until])
				->groupBy('provinsis.provinsi')
				->pluck('count');

			$publikasiCount = Formcegah::select(
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
				->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$request->from, $request->until])
				->groupBy('provinsis.provinsi')
				->pluck('count');

			return response()->json([
				'status'            => true,
				'message'           => 'Rekap Wiayah Sukses',
				'kategori'          => $categories,
				'kerawanan'			=> $identifikasi_kerawananCount,
				'pendidikan'		=> $pendidikanCount,
				'partisipasi'		=> $partisipasiCount,
				'kerjasama'			=> $kerjasamaCount,
				'imbauan'			=> $imbauanCount,
				'kegiatanlain'		=> $kegiatanlainCount,
				'publikasi'			=> $publikasiCount
			]);
		} else if ($request->jenis_rekap == 'Tahap') {
			$data_tahap = Formcegah::select(
				'tahap',
				DB::raw('
                    SUM(CASE
                    WHEN tahap="Tahapan" THEN 1
                    WHEN tahap="Non Tahapan" THEN 1
                    ELSE 0
                    END) AS count
                    ')
			)
				->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$request->from, $request->until])
				->groupBy('tahap')
				->get();

			return response()->json([
				'status'            => true,
				'message'           => 'Rekap Tahap Sukses',
				'data'          	=> $data_tahap
			]);
		} else if ($request->jenis_rekap == 'Bentuk') {
			$data_bentuk = Formcegah::select(
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
				->whereBetween(DB::raw("(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d'))"), [$request->from, $request->until])
				->groupBy('bentuks.bentuk')
				->get();

			return response()->json([
				'status'            => true,
				'message'           => 'Rekap Bentuk Sukses',
				'data'          	=> $data_bentuk
			]);
		} else {
			dd('ya ga gitu juga inputnya');
		}
	}
}
