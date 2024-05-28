@extends('layouts.opd.app')

@section('content')
<form action="/edit-tahapan/{{$id}}" method="post">
    <input type="hidden" name="_method" value="PUT">
    @csrf
<section class="content mt-5">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Form Update Tahapan :</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Tahapan</label>
                        <input type="text" class="form-control @error('tahapan') is-invalid @enderror" name="tahapan" placeholder="Tidak Boleh Kosong" value="{{$tahapan->tahapan}}">
                        @error('tahapan')
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
