@extends('layouts.opd.appme')
@section('content')
<body class="hold-transition register-page">
  <br>
  <br>
  <br>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
  {{--  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"> 
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> 
  <script src="https://cdn. jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js"></script> 
    --}}
  {{--  <!-- Sertakan skrip --> 
  {!! htmlScriptTagJsApi() !!} 

    <div class="alert alert-success"> 
        {{ Session::get('message') }} 
    </div>   --}}
  
<div class="register-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
        <img src="{{ Storage::url('public/logo-login.png') }}" width="150px">
    </div>
    <div class="card-body">
      <p class="login-box-msg">Register a new membership</p>

      <form action="/register-province" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="input-group mb-3">
          <input type="text" class="form-control @error('NamaLengkap') is-invalid @enderror"
           placeholder="Masukan Nama Lengkap*" name="NamaLengkap" required>
           @error('NamaLengkap')
           <div class="invalid-feedback" style="display: block">
               {{ $message }}
           </div>
           @enderror
        </div>

        <div class="input-group mb-3">
          <input type="number" class="form-control @error('NIK') is-invalid @enderror"
           placeholder="Masukan NIK*" name="NIK" required>
           @error('NIK')
           <div class="invalid-feedback" style="display: block">
               {{ $message }}
           </div>
           @enderror
          
        </div>
        <div class="input-group mb-3">
          <select class="form-control select2" style="width: 100%;" name="Jabatan">
            {{--  <option selected="selected">==Pilih Jabatan==</option>  --}}
            @foreach ($jabatan as $jab)                                                  
            <option value="{{ $jab->jabatan  }}" selected> {{ $jab->jabatan}}</option>                               
            @endforeach
            <option selected="selected" value="">--Pilih Jabatan--</option>
          </select>
        </div>
        <div class="input-group mb-3">
          <input type="number" min="10" class="form-control @error('Telepon') is-invalid @enderror" 
          placeholder="Masukan No.Telepon/HP*" name="Telepon">
          @error('Telepon')
          <div class="invalid-feedback" style="display: block">
              {{ $message }}
          </div>
          @enderror
        </div>
        <div class="input-group mb-3">
          <input type="email" class="form-control @error('Email') is-invalid @enderror" 
          placeholder="Masukan Email*" name="Email">
          @error('Email')
          <div class="invalid-feedback" style="display: block">
              {{ $message }}
          </div>
          @enderror
          </div>
          <div class="input-group mb-3">
            <input type="text" class="form-control @error('TmpLahir') is-invalid @enderror" 
            placeholder="Masukan Tempat Lahir*" name="TmpLahir">
            @error('TmpLahir')
            <div class="invalid-feedback" style="display: block">
                {{ $message }}
            </div>
            @enderror
            </div>

          <div class="input-group mb-3">
            <input type="text"onfocus="(this.type='date')"  
            class="form-control @error('TglLahir') is-invalid @enderror" 
          placeholder="Masukan Tgl Lahir*" name="TglLahir">                      
          @error('TglLahir')
          <div class="invalid-feedback" style="display: block">
              {{ $message }}
          </div>
          @enderror
          </div>

          <div class="input-group mb-3">
            <input type="text" class="form-control @error('Login') is-invalid @enderror" 
            placeholder="Masukan Username* " name="Login">
            @error('Login')
            <div class="invalid-feedback" style="display: block">
                {{ $message }}
            </div>
            @enderror
          </div>

          <div class="input-group mb-3">
            <input type="password" min="8" class="form-control @error('Sandi') is-invalid @enderror" 
            placeholder="Masukan Password* " name="Sandi">
            @error('Sandi')
            <div class="invalid-feedback" style="display: block">
                {{ $message }}
            </div>
            @enderror
          </div>

          <div class="input-group mb-3">
            <textarea class="form-control" rows="3" name="Alamat" 
            placeholder="Masukan Alamat*"></textarea>

            @error('Alamat')
            <div class="invalid-feedback" style="display: block">
                {{ $message }}
            </div>
            @enderror
          </div>

          <div class="input-group mb-3">
            <select class="form-select form-select" style="width: 100%;" name="Provinsi">
              @foreach ($provinsi as $prov)                                                  
              <option value="{{ $prov->id  }}" selected> {{ $prov->provinsi}}</option>                               
              @endforeach
              <option selected="selected">--Pilih Provinsi--</option>
            </select>
          </div>
          <div class="input-group mb-3">
            <select class="form-select form-select" style="width: 100%;" name="id_divisi">
              @foreach ($petugas as $petugas)                                                  
              <option value="{{ $petugas->kd_petugas  }}" selected> {{ $petugas->ket}}</option>                               
              @endforeach
              <option selected="selected" value="">--Pilih Divisi--</option>
            </select>
          </div>

          {{--  <div class="input-group mb-3">
            <!-- Google reCaptcha v2 --> 
         {!! htmlFormSnippet() !!} 
         @if($errors->has('g-recaptcha-response')) 
           <div> 
              <small class="text-danger">{{ $errors->first('g-recaptcha- response') }}</small> 
           </div> 
         @endif 
          </div>  --}}

        <div class="row">
          <div class="col-8">
            
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Register</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      

      <a href="/" class="text-center">I already have a membership</a>
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->

@endsection
