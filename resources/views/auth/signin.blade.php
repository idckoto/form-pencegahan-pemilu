@extends('layouts.opd.appme')
@section('content')

<body class="hold-transition login-page">

<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
        <img src="{{ Storage::url('public/logo-login.png') }}" width="200px">
        <h4>Update Sistem</h4>
    </div>
    <div class="card-body">
    <h2><center>Layanan aplikasi di tutup sementara mulai 21 Oktober 2024, jam 13:00 WIB.</center></h2>


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

<script>
    $(document).ready(function () {
        $('#reload').click(function(){
            $.ajax({
                type: 'GET',
                url: 'reload-captcha',
                success:function(data){
                    $(".captcha span").html(data.captcha)
                }
            })
        });
    });
</script>
<!-- /.modal -->
@endsection
