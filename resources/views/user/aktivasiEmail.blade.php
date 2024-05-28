@extends('layouts.opd.appmobile')
@section('content')
<body class="hold-transition lockscreen">
<!-- Automatic element centering -->
<div class="lockscreen-wrapper">
  <div class="lockscreen-logo">
    <a href="../../index2.html"><b>Verifikasi</b>Email</a>
  </div>
  <!-- User name -->
  <div class="lockscreen-name">{{$username}}</div>

  <!-- START LOCK SCREEN ITEM -->
  <div class="lockscreen-item">
    <!-- lockscreen image -->
    <div class="lockscreen-image">
      <img src="{{ Storage::url('public/logo-email.png') }}" alt="User Image">
    </div>
    <!-- /.lockscreen-image -->

    <!-- lockscreen credentials (contains the form) -->
    <form action="/kirim-aktivasi" method="POST">
      @csrf
      <div class="input-group">
        <input type="email" class="form-control" placeholder="Email" value="{{$email}}" style="text-align:right">
        <input type="hidden" class="form-control" value="{{$id}}" name="id" style="text-align:right">
        <div class="input-group-append">
          <button type="submit" class="btn">
            <i class="fas fa-arrow-right text-muted"></i>
          </button>
        </div>
      </div>
    </form>
    <!-- /.lockscreen credentials -->

  </div>
  <!-- /.lockscreen-item -->
  <div class="help-block text-center">
    Kirim kode Verifikasi
  </div>
  <div class="text-center">
    <a href="/">Halaman Login</a>
  </div>
</div>
<!-- /.center -->
@endsection