<?php

namespace App\Http\Controllers\api;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
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
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Validator;

class MasterdataController extends Controller
{
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

    public function petugas(Request $request)
    {
        $params = $this->getParams($request);
        $query = Petuga::query();

        $query->when(!empty($params['search']), function ($query) use ($params) {
            return $query->where(function ($query) use ($params) {
                $query->where('kd_petugas', 'like', '%' . $params['search'] . '%')
                    ->orWhere('ket', 'like', '%' . $params['search'] . '%');
            });
        });

        if (in_array($params['order_by'], ['id', 'kd_petugas', 'ket'])) {
            $query->orderBy($params['order_by'], $params['sort_by']);
        }

        $data = $query->limit($params['limit'])
            ->offset($params['offset'])
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }


    public function tahapans(Request $request)
    {
        $params = $this->getParams($request);

        $query = Tahapan::query();

        $query->when(!empty($params['search']), function ($query) use ($params) {
            return $query->where('tahapan', 'like', '%' . $params['search'] . '%');
        });

        if ($params['order_by'] == 'id' || $params['order_by'] == 'tahapan' || $params['order_by'] == 'id') {
            $query->orderBy($params['order_by'], $params['sort_by']);
        }

        $data = $query->limit($params['limit'])
            ->offset($params['offset'])
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }


    public function jenis(Request $request)
    {
        $params = $this->getParams($request);

        $query = Jenis::query();

        $query->when(!empty($params['search']), function ($query) use ($params) {
            return $query->where('jenis', 'like', '%' . $params['search'] . '%');
        });

        if ($params['order_by'] == 'id' || $params['order_by'] == 'jenis' || $params['order_by'] == 'id') {
            $query->orderBy($params['order_by'], $params['sort_by']);
        }

        $data = $query->limit($params['limit'])
            ->offset($params['offset'])
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function bentuks(Request $request)
    {
        $params = $this->getParams($request);

        $query = Bentuk::query();

        $query->when(!empty($params['search']), function ($query) use ($params) {
            return $query->where('bentuk', 'like', '%' . $params['search'] . '%');
        });

        if ($params['order_by'] == 'id' || $params['order_by'] == 'bentuk' || $params['order_by'] == 'id') {
            $query->orderBy($params['order_by'], $params['sort_by']);
        }

        $data = $query->limit($params['limit'])
            ->offset($params['offset'])
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function tujuans(Request $request)
    {
        $params = $this->getParams($request);

        $query = Tujuan::query();

        $query->when(!empty($params['search']), function ($query) use ($params) {
            return $query->where('tujuan', 'like', '%' . $params['search'] . '%');
        });

        if ($params['order_by'] == 'id' || $params['order_by'] == 'tujuan' || $params['order_by'] == 'id') {
            $query->orderBy($params['order_by'], $params['sort_by']);
        }

        $data = $query->limit($params['limit'])
            ->offset($params['offset'])
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function sasarans(Request $request)
    {
        $params = $this->getParams($request);

        $query = Sasaran::query();

        $query->when(!empty($params['search']), function ($query) use ($params) {
            return $query->where('sasaran', 'like', '%' . $params['search'] . '%');
        });

        if ($params['order_by'] == 'id' || $params['order_by'] == 'sasaran' || $params['order_by'] == 'id') {
            $query->orderBy($params['order_by'], $params['sort_by']);
        }

        $data = $query->limit($params['limit'])
            ->offset($params['offset'])
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function provinsi(Request $request)
    {
        $params = $this->getParams($request);

        $query = Provinsi::query();

        $query->when(!empty($params['search']), function ($query) use ($params) {
            return $query->where('provinsi', 'like', '%' . $params['search'] . '%');
        });

        if ($params['order_by'] == 'id' || $params['order_by'] == 'provinsi' || $params['order_by'] == 'id') {
            $query->orderBy($params['order_by'], $params['sort_by']);
        }

        $data = $query->limit($params['limit'])
            ->offset($params['offset'])
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }
    public function kab(Request $request)
    {
        $provinsiId = $request->input('provinsi_id');
        $limit = $request->input('limit', 10);
        $sortBy = $request->input('sort_by', 'id');
        $sortOrder = $request->input('sort_order', 'asc'); // Nilai default 'asc' jika tidak diberikan
    
        $data = Provinsi::leftJoin('kabupatens', 'provinsis.id', '=', 'kabupatens.provinsi_id')
            ->where('kabupatens.provinsi_id', $provinsiId)
            ->orderBy($sortBy, $sortOrder) // Menambahkan parameter sort order
            ->select('kabupatens.*')
            ->limit($limit)
            ->get();
    
        return response()->json([
            'data' => $data
        ]);
    }
    
    

    public function kec(Request $request)
    {
        $params = $this->getParams($request);

        $query = Kecamatan::query();

        $query->when(!empty($params['search']), function ($query) use ($params) {
            return $query->where('kecamatan', 'like', '%' . $params['search'] . '%');
        });

        if ($params['order_by'] == 'id' || $params['order_by'] == 'kecamatan' || $params['order_by'] == 'id') {
            $query->orderBy($params['order_by'], $params['sort_by']);
        }

        $data = $query->limit($params['limit'])
            ->offset($params['offset'])
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }
    public function desa(Request $request)
    {
        $params = $this->getParams($request);

        $query = Kelurahan::query();

        $query->when(!empty($params['search']), function ($query) use ($params) {
            return $query->where('kelurahan', 'like', '%' . $params['search'] . '%');
        });

        if ($params['order_by'] == 'id' || $params['order_by'] == 'kelurahan' || $params['order_by'] == 'id') {
            $query->orderBy($params['order_by'], $params['sort_by']);
        }

        $data = $query->limit($params['limit'])
            ->offset($params['offset'])
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }
    public function updatePengguna(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_pengguna' => 'required',
            'email' => 'email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()
            ]);
        }

        $updatePengguna = DB::table('pengguna_ori')
            ->where('ID', $request->id_pengguna)
            ->update([
                'email' => $request->email
            ]);

        if ($updatePengguna) {
            return response()->json([
                'status'            => true,
                'message'           => 'Sukses Update Email Pengguna'
            ]);
        }
    }
}
