@extends('layouts.opd.appme')
@section('content')
<body class="hold-transition login-page">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
        <img src="{{ Storage::url('public/logo-login.png') }}" width="200px">
        <h4>Form Reset Password</h4>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Silahkan Ubah Password Anda</p>
        @if($errors->any())
        @foreach($errors->all() as $err)
        <p class="alert alert-danger">{{ $err }}</p>
        @endforeach
        @endif

        @if (session('statusForgot'))
        <p class="alert alert-success">{{ session('statusForgot') }}</p> 
        @endif
      <form action="{{ url('/forgot/confirm') }}" method="POST">
      @csrf
        <input type="hidden" name="id_user" value="{{ $id }}">
        <div class="input-group mb-3">
          <input class="form-control" type="password" name="new_password" value="{{ old('new_password') }}" placeholder="New Password" autoFocus>
        </div>
        <div class="input-group mb-3">
          <input class="form-control" type="password" name="confirm_password" value="{{ old('confirm_password') }}" placeholder="Confirm Password" autoFocus>
        </div>
      <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Submit</button>
          </div>
          <!-- /.col -->
      </div>
      </form>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
@endsection