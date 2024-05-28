@extends('layouts.opd.app')

@section('content')
<form action="/edit-petugas/{{$id}}" method="post">
    @csrf
<section class="content mt-5">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Form Update Petugas :</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Kode Petugas</label>
                        <input type="text" class="form-control @error('kd_petugas') is-invalid @enderror" name="kd_petugas" placeholder="PM" value="{{$petugas->kd_petugas}}">
                        @error('kd_petugas')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <input type="hidden" name="_method" value="PUT">
                    </div>
                    <div class="form-group">
                        <label>Keterangan </label>
                        <input type="text" class="form-control @error('ket') is-invalid @enderror" name="ket" placeholder="Biro" value="{{$petugas->ket}}">
                        @error('ket')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-info float-right"><i class="icon ion-ios-paperplane"></i> Update</button>
                </div>
            </div>
        </div>
      </div>
    </div>
</section>
</form>
@endsection
