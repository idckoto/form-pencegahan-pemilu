@extends('layouts.opd.app')

@section('content')
<form action="/simpan-sasaran" method="post">
    @csrf
<section class="content mt-5">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Form Tambah Sasaran :</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Kode Sasaran</label>
                        <input type="text" class="form-control @error('sasaran') is-invalid @enderror" name="sasaran" placeholder="Tidak Boleh Kosong" value="{{ old('sasaran') }}">
                        @error('sasaran')
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
