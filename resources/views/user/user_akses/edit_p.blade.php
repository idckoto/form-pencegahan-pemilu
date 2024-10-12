@extends('layouts.opd.app')

@section('content')
<br>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-3">

        <!-- Profile Image -->
        <div class="card card-primary card-outline">
          <div class="card-body box-profile">
            <div class="text-center">
              <div class="image">
                @if (Auth::user()->profile_photo_path != null)
                  <img src="{{ asset('staff/' . Auth::user()->profile_photo_path) }}" class="img-thumbnail" alt="{{ Auth::user()->name }}">
                @else
                  <img src="{{ asset('blackend/dist/img/avatar5.png') }}" class="img-circle elevation-2" alt="User Image">
                @endif
              </div>
            </div>
            <br>
            <p class="text-muted text-center">{{ Auth::user()->Jabatan }}</p>
            <form action="/profil-foto" method="POST" enctype="multipart/form-data">
              @csrf
              @method('patch')
              <input type="hidden" name="id" value="{{ $user->id }}">
              <input type="file" name="profile_photo_path" class="form-control @error('profile_photo_path') is-invalid @enderror">
              <br>
              <button class="btn btn-primary btn-block" type="submit"><i class="fa fa-paper-plane"></i> Update Foto</button>
            </form>
          </div>
        </div>

      </div>

      <div class="col-md-9">
        <div class="card">
          <div class="card card-warning card-outline">
            <div class="card-header">
              <h3 class="card-title">
                <a href="">
                  @php
                  $tgl = date('Y-m-d');
                  @endphp
                  Update Profil. <b>
                    {{--  {{ hari_ini() }}, {{ dateIndonesia($tgl) }}  --}}
                  </b>
                </a>
              </h3>
            </div>
            <div class="card-body">
              <form action="/profil-update" method="POST" enctype="multipart/form-data">
                @csrf
                @method('patch')

                <div class="box-body" style="padding-bottom: 0;">
                  <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="name"value="{{ old('name',$user->name) }}" 
                              class="form-control @error('name') is-invalid @enderror" id="name" 
                              placeholder="Masukan Nama">
                    @error('name')
                    <div class="invalid-feedback" style="display: block">
                        {{ $message }}
                    </div>
                    @enderror
                  </div> 

                  <div class="form-group">
                    <label>Email</label>
                          <input type="email" name="email"value="{{ old('email',$user->email) }}" 
                      class="form-control @error('email') is-invalid @enderror" id="exampleInputEmail1" 
                      placeholder="Masukan Telpon " readonly>
                      @error('email')
                      <div class="invalid-feedback" style="display: block">
                          {{ $message }}
                      </div>
                      @enderror
                  </div>
                  <div class="form-group">
                    <label>Password Lama</label>
                    <input type="password" name="old_password" placeholder="Masukkan Password Lama" class="form-control">
                    @error('old_password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                  </div>
                  <div class="form-group">
                    <label>Password Baru</label>
                          <input type="password" name="password" value="{{ old('password') }}"
                          placeholder="Masukkan Password"
                          class="form-control @error('password') is-invalid @enderror">
                        
                  </div>
                  <div class="form-group">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" placeholder="password_confirmation" class="form-control @error('password') is-invalid @enderror">
                    @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                  </div>


                  <div class="form-horizontal">
                    <div class="box-body">
                      <div class="form-group">
                        <div class="col-sm-9 col-sm-offset-3">
                          <button type="submit" class="btn btn-primary btn-md">Update</button>
                          <div id="notif" style="display: none; margin: 15px 0 0 0"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection
