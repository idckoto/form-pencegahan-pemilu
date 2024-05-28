@extends('layouts.opd.appmobile')
@section('content')
<body class="hold-transition login-page">
<div class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
        <img src="{{ Storage::url('public/logo-login.png') }}" width="200px">
    </div>
    <div class="card-body">
      <p class="login-box-msg">You are only one step a way from your new password, recover your password now.</p>
      <form action="/password" method="POST">
      @csrf
        <div class="input-group mb-3">
          <input type="password" class="form-control @error('new_password') is-invalid @enderror" placeholder="Password" name="new_password" autoFocus>
          <input type="hidden" class="form-control" name="id" value="{{$id}}">
            @error('new_password')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror" placeholder="Confirm Password" name="new_password_confirmation">
            @error('new_password_confirmation')
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
            <button type="submit" class="btn btn-primary btn-block">Change password</button>
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
