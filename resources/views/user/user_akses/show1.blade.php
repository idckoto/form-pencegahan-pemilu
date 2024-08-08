@extends('layouts.opd.app')
@section('content')
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
            <center>
          {{--  <h1>Profil User</h1>  --}}
         </center>
        </div>
      </div>
    </div><!-- /.container-fluid -->
</section>

<section class="content">

    <!-- Default box -->
    <div class="card">
      <div class="card-body row">
        <div class="col-5 text-center d-flex align-items-center justify-content-center">
          <div class="">

            @if (Auth::user()->profile_photo_path!=null)
            <img src="{{ Storage::url('public/staff/'.Auth::user()->profile_photo_path.'') }}" class="img-thumbnail" />

        @else
            <img src="{{ asset('blackend/dist/img/avatar4.png') }}" class="img-circle elevation-2" alt="User Image">
        @endif
        

            <h2>{{ $show_user->name }}<strong></strong></h2>
            <p class="lead mb-5">{{ $show_user->email }} <br>
            </p>
          </div>
        </div>
         
        <div class="col-7">
        <form action="/profil-update-user/{{$id}}" method="post">
    <input type="hidden" name="_method" value="PUT">
    @csrf
          <div class="form-group">
            <label for="inputName">Email</label>
             <input type="email" class="form-control @error('email') is-invalid @enderror" 
          placeholder="Masukan email*" name="email" value="{{ $show_user->email }}" >
          @error('email')
          <div class="invalid-feedback" style="display: block">
              {{ $message }}
          </div>
          @enderror
          </div>
          <div class="form-group">
            <label for="inputEmail">Password</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" 
          placeholder="Masukan password Jika Ada Perubahan" name="password"  autocomplete="new-password" >
          @error('password')
          <div class="invalid-feedback" style="display: block">
              {{ $message }}
          </div>
          @enderror
           
          </div>
          {{--  <div class="form-group">
            <label for="inputSubject">Username</label>
             <input type="Login" class="form-control @error('Login') is-invalid @enderror" 
          placeholder="Masukan Login*" name="Login" value="{{ $show_user->Login }}" >
          @error('Login')
          <div class="invalid-feedback" style="display: block">
              {{ $message }}
          </div>
          @enderror
          </div>  --}}
         
          <div class="form-group">
            <button type="submit" class="btn btn-primary">Update Data</button>
          </div>
        </div>
        </form>
      </div>
    </div>

  </section>
         
              
                @endsection