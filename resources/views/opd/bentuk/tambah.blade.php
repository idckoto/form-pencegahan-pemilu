@extends('layouts.opd.app')

@section('content')
<form action="/simpan-bentuk" method="post">
    @csrf
<section class="content mt-5">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Form Tambah Bentuk Pencegahan :</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Bentuk Pencegahan</label>
                        <input type="text" class="form-control @error('bentuk') is-invalid @enderror" value="{{ old('bentuk') }}" name="bentuk" placeholder="Tidak Boleh Kosong" required>
                        @error('bentuk')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Type Bentuk Pencegahan</label>
                         <select name="type" class="custom-select rounded-0 @error('type') is-invalid @enderror">
                            <option value="">Pilih Tahapan</option>
                            <option value="1">Tahapan</option>
                            <option value="0">Non Tahapan</option>
                        </select>
                        @error('type')
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
