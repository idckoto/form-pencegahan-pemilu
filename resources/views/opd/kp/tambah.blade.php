@extends('layouts.opd.app')

@section('content')
<form action="/simpan-kp" method="post">
    @csrf
<section class="content mt-5">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Form Tambah Kategori Pemilu :</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Nama Kategori Pemilu </label>
                        <input type="text" class="form-control @error('nama_kp') is-invalid @enderror" name="nama_kp" placeholder="Input disini" value="{{ old('nama_kp') }}">
                        @error('nama_kp')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
