@extends('layouts.opd.app')
@section('content')

<section class="content mt-5">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">Form Tambah User Divisi {{ Auth::user()->Jabatan }}</h3>
          </div>
          <div class="card-body">
          {{--  ini internal masing2 tingkatan  --}}
            <form action="/user-akses" method="POST" enctype="multipart/form-data">
              @csrf

              <div class="input-group mb-3">
                <input type="text" class="form-control @error('name') is-invalid @enderror"
                  placeholder="Masukan Nama Lengkap*" name="name" required>
                @error('name')
                <div class="invalid-feedback" style="display: block">
                  {{ $message }}
                </div>
                @enderror
              </div>

              <div class="input-group mb-3">
                <input type="email" class="form-control @error('email') is-invalid @enderror"
                  placeholder="Masukan Email*" autocomplete="off" name="email">
                @error('email')
                <div class="invalid-feedback" style="display: block">
                  {{ $message }}
                </div>
                @enderror
              </div>

              {{--  <div class="input-group mb-3">
                <input type="text" class="form-control @error('Login') is-invalid @enderror"
                  placeholder="Masukan Username* " name="Login">
                @error('Login')
                <div class="invalid-feedback" style="display: block">
                  {{ $message }}
                </div>
                @enderror
              </div>  --}}

              <div class="input-group mb-3">
                <input type="password" min="8" class="form-control @error('password') is-invalid @enderror"
                  placeholder="Masukan Password* " name="password"  required autocomplete="new-password">
                @error('password')
                <div class="invalid-feedback" style="display: block">
                  {{ $message }}
                </div>
                @enderror
              </div>
              
              {{--  <script>
                function hidePassword(input) {
                  input.value = "*".repeat(input.value.length);
                }
              </script>  --}}
              
              <div class="input-group mb-3">
                <select class="form-control" style="width: 100%;" name="id_divisi">
                  @foreach ($petugas as $petugas)
                  <option value="{{ $petugas->kd_petugas  }}" selected> {{ $petugas->ket}}</option>
                  @endforeach
                  <option selected="selected">--Pilih Divisi--</option>
                </select>
              </div>

              <div class="card-footer">
                <button type="submit" class="btn btn-primary float-right"><i class="icon ion-ios-paperplane"></i> Simpan</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
{{--  ini tingkat pusat  --}}
@if (Auth::user()->id_admin == '0') 
<section class="content mt-5">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Form Tambah User Provinsi</h3>
          </div>
          <div class="card-body">
            <form action="/user-provinsi-akses" method="POST" enctype="multipart/form-data">
              @csrf

              <div class="input-group mb-3">
                <input type="text" class="form-control @error('name') is-invalid @enderror"
                  placeholder="Masukan Nama Lengkap*" name="name" required>
                @error('name')
                <div class="invalid-feedback" style="display: block">
                  {{ $message }}
                </div>
                @enderror
              </div>

              <div class="input-group mb-3">
                <input type="email" class="form-control @error('email') is-invalid @enderror"
                  placeholder="Masukan email*" name="email">
                @error('email')
                <div class="invalid-feedback" style="display: block">
                  {{ $message }}
                </div>
                @enderror
              </div>

              {{--  <div class="input-group mb-3">
                <input type="text" class="form-control @error('Login') is-invalid @enderror"
                  placeholder="Masukan Username* " name="Login">
                @error('Login')
                <div class="invalid-feedback" style="display: block">
                  {{ $message }}
                </div>
                @enderror
              </div>  --}}

              <div class="input-group mb-3">
                <input type="password" min="8" class="form-control @error('password') is-invalid @enderror"
                  placeholder="Masukan Password* " name="password" required autocomplete="new-password">
                @error('password')
                <div class="invalid-feedback" style="display: block">
                  {{ $message }}
                </div>
                @enderror
              </div>

              <div class="input-group mb-3">
                <select class="form-control" style="width: 100%;" name="Provinsi">
                  @foreach ($provinsi as $prov)
                  <option value="{{ $prov->id  }}" selected> {{ $prov->provinsi}}</option>
                  @endforeach
                  <option selected="selected">--Pilih Provinsi--</option>
                </select>
              </div>

              <div class="card-footer">
                <button type="submit" class="btn btn-primary float-right"><i class="icon ion-ios-paperplane"></i> Simpan</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endif

{{--  ini tingkat propinsi  --}}
@if (Auth::user()->id_admin == '1')
@if (Auth::user()->Jabatan == 'Ketua atau Anggota Bawaslu Provinsi')

<section class="content mt-5">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-success">
          <div class="card-header">
            <h3 class="card-title">Form Tambah User Kabupaten/Kota</h3>
          </div>
          <div class="card-body">
            <form action="/user-kab-kota-akses" method="POST" enctype="multipart/form-data">
              @csrf

              <div class="input-group mb-3">
                <input type="text" class="form-control @error('name') is-invalid @enderror"
                  placeholder="Masukan Nama Lengkap*" name="name" required>
                @error('name')
                <div class="invalid-feedback" style="display: block">
                  {{ $message }}
                </div>
                @enderror
              </div>

              <div class="input-group mb-3">
                <input type="email" class="form-control @error('email') is-invalid @enderror"
                  placeholder="Masukan email*" name="email">
                @error('email')
                <div class="invalid-feedback" style="display: block">
                  {{ $message }}
                </div>
                @enderror
              </div>

              {{--  <div class="input-group mb-3">
                <input type="text" class="form-control @error('Login') is-invalid @enderror"
                  placeholder="Masukan Username* " name="Login">
                @error('Login')
                <div class="invalid-feedback" style="display: block">
                  {{ $message }}
                </div>
                @enderror
              </div>  --}}

              <div class="input-group mb-3">
                <input type="password" min="8" class="form-control @error('password') is-invalid @enderror"
                  placeholder="Masukan Password* " name="password">
                @error('password')
                <div class="invalid-feedback" style="display: block">
                  {{ $message }}
                </div>
                @enderror
              </div>

              <div class="input-group mb-3">
                <select class="form-control" style="width: 100%;" name="KabKota">
                  @foreach ($kabupaten_kota as $kab)
                  <option value="{{ $kab->id  }}" selected> {{ $kab->kabupaten}}</option>
                  @endforeach
                  <option selected="selected">--Pilih Kabupaten--</option>
                </select>
              </div>

              <div class="card-footer">
                <button type="submit" class="btn btn-success float-right"><i class="icon ion-ios-paperplane"></i> Simpan</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endif
@endif

{{--  ini tingkat kabupaten kota  --}}
@if (Auth::user()->id_admin == '1')
@if (Auth::user()->Jabatan == 'Ketua atau Anggota Bawaslu Kabupaten/Kota')

<section class="content mt-5">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-danger">
          <div class="card-header">
            <h3 class="card-title">Form Tambah User Kecamatan</h3>
          </div>
          <div class="card-body">
            <form action="/user-kec-akses" method="POST" enctype="multipart/form-data">
              @csrf

              <div class="input-group mb-3">
                <input type="text" class="form-control @error('name') is-invalid @enderror"
                  placeholder="Masukan Nama Lengkap*" name="name" required>
                @error('name')
                <div class="invalid-feedback" style="display: block">
                  {{ $message }}
                </div>
                @enderror
              </div>

              <div class="input-group mb-3">
                <input type="email" class="form-control @error('email') is-invalid @enderror"
                  placeholder="Masukan email*"  name="email">
                @error('email')
                <div class="invalid-feedback" style="display: block">
                  {{ $message }}
                </div>
                @enderror
              </div>

              {{--  <div class="input-group mb-3">
                <input type="text" class="form-control @error('Login') is-invalid @enderror"
                  placeholder="Masukan Username* " name="Login">
                @error('Login')
                <div class="invalid-feedback" style="display: block">
                  {{ $message }}
                </div>
                @enderror
              </div>  --}}

              <div class="input-group mb-3">
                <input type="password" min="8" class="form-control @error('password') is-invalid @enderror"
                  placeholder="Masukan Password* " name="password">
                @error('password')
                <div class="invalid-feedback" style="display: block">
                  {{ $message }}
                </div>
                @enderror
              </div>

              <div class="input-group mb-3">
                <select class="form-control" style="width: 100%;" name="Kecamatan">
                  @foreach ($kecamatan as $kec)
                  <option value="{{ $kec->id  }}" selected> {{ $kec->kecamatan}}</option>
                  @endforeach
                  <option selected="selected">--Pilih Kecamatan--</option>
                </select>
              </div>

              <div class="card-footer">
                <button type="submit" class="btn btn-danger float-right"><i class="icon ion-ios-paperplane"></i> Simpan</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endif
@endif
@push('scripts')

@endpush
@endsection
