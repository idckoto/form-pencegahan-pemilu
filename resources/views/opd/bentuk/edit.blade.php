@extends('layouts.opd.app')

@section('content')
<form action="/edit-bentuk/{{$id}}" method="post">
    <input type="hidden" name="_method" value="PUT">
    @csrf
<section class="content mt-5">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Form Update Bentuk Pencegahan :</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Bentuk Pencegahan</label>
                        <input type="text" class="form-control @error('bentuk') is-invalid @enderror" name="bentuk" placeholder="Tidak Boleh Kosong" value="{{$bentuk->bentuk}}" required>
                        @error('bentuk')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Type Bentuk Pencegahan</label>
                         <select name="type" class="custom-select rounded-0 @error('type') is-invalid @enderror">
                            <option value="">Pilih Tahapan</option>
                            <option {{$bentuk->type == '1' ? 'selected':''}} value="1">Tahapan & Non Tahapan</option>
                            <option {{$bentuk->type != '1' ? 'selected':''}} value="0">Non Tahapan</option>
                        </select>
                        @error('type')
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
