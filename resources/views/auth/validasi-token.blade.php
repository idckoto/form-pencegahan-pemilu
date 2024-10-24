@extends('layouts.opd.appme')
@section('content')

<body class="hold-transition login-page">

<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
        <img src="{{ Storage::url('public/logo-login.png') }}" width="200px">
        <h4>Masukan Password Baru</h4>
    </div>
    <div class="card-body">
                <form action="{{ route('validasi-forgot-password-act') }}" method="post">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control"
                            placeholder="Masukkan Password Baru">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
            @error('password')
                <small class="text-danger align-center">{{ $message }}</small>
            @enderror
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-block">Submit</button>
                </div>
                <!-- /.col -->
            </div>
        </form>
      <!-- /.social-auth-links -->
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>


<!-- /.modal -->
@endsection
