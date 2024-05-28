@extends('layouts.opd.appme')
@section('content')
<body class="hold-transition login-page">
<div class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
        <img src="{{ Storage::url('public/logo-login.png') }}" width="200px">
    </div>
    <div class="card-body">
      <p class="login-box-msg">Silahkan cek username anda sudah terdaftar/aktivasi pada App SuperApp Bawaslu.</p>
      <form action="/cek-aktivasi" method="POST">
      @csrf
        <div class="input-group mb-3">
          <input type="text" class="form-control @error('username') is-invalid @enderror" placeholder="Username" name="username">
            @error('username')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" name="password">
            @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Activasi SuperApp</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <p class="mt-3 mb-1">
        <a href="/">Login</a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

@endsection
