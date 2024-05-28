@extends('layouts.opd.app')

@section('content')
<form action="/simpan-wilayah" method="post">
    @csrf
<section class="content mt-5">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Form Tambah Wilayah :</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Kode Wilayah</label>
                        <input type="text" class="form-control" name="kd_wilayah" placeholder="00.00">
                    </div>
                    <div class="form-group">
                        <label>Keterangan </label>
                        <input type="text" class="form-control" name="ket" placeholder="Bawaslu ">
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-info float-right"><i class="icon ion-ios-paperplane"></i> Simpan</button>
                </div>
            </div>
        </div>
      </div>
    </div>
</section>
</form>
@endsection
