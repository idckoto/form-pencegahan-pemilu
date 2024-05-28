@extends('layouts.opd.appme')
@section('content')

<body class="hold-transition login-page">

<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
        <img src="{{ Storage::url('public/logo-login.png') }}" width="200px">
        <h4>Form Pencegahan Online</h4>
    </div>
    <div class="card-body">
    {{-- <h2><center>layanan aplikasi akan di tutup sementara mulai jam 16:00 WIB</center></h2> --}}
      <p class="login-box-msg">Sign in to start your session</p>
        @if($errors->any())
        @foreach($errors->all() as $err)
        <p class="alert alert-danger">{{ $err }}</p>
        @endforeach
        @endif

        @if (session('statusForgot'))
        <p class="alert alert-success">{{ session('statusForgot') }}</p> 
        @endif

        @if (session('statusConfirm'))
        <p class="alert alert-success">{{ session('statusConfirm') }}</p> 
        @endif
      <form action="" method="POST">
      @csrf
        <div class="input-group mb-3">
            
          <input class="form-control" type="email" name="email" :value="old('email')" required autofocus>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input class="form-control" type="password" name="password" placeholder="Password"
          required autocomplete="current-password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <div class="row text-center mt-2 mb-3">
        <div class="col-12">
			<!-- Button trigger modal -->
			<button type="button" class="btn btn-block btn-warning" data-toggle="modal" data-target="#forgotPassModal">
			  Forgot Password
			</button>

			<!-- Modal -->
			<div class="modal fade" id="forgotPassModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
			  <div class="modal-dialog">
				<div class="modal-content">
				  <div class="modal-header">
					<h5 class="modal-title" id="staticBackdropLabel">Forgot Password</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
				  </div>
          <form action="{{ url('/forgot/submit') }}" enctype="multipart/form-data" method="POST">
          @csrf
          <div class="modal-body">
					<div class="input-group mb-3">
					  <input class="form-control" type="email" name="email" value="{{ old('email') }}" placeholder="Email" autoFocus>
					  <div class="input-group-append">
						<div class="input-group-text">
						  <span class="fas fa-envelope"></span>
						</div>
					  </div>
					</div>
				  </div>
				  <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
				  </div>
          </form>
				</div>
			  </div>
			</div>
        </div>
      </div>
      <div class="social-auth-links text-center mt-2 mb-3">

      </div>
      <!-- /.social-auth-links -->
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>

<!-- /.modal -->
@endsection