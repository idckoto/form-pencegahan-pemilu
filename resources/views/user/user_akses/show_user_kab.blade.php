
@extends('layouts.opd.app')
@section('content')
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
            <center>
          <h1>List User Kabupaten..</h1>
         </center>
        </div>
      </div>
    </div><!-- /.container-fluid -->
</section>
<section class="content-header">
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card card-warning card-outline">
                    {{--  <div class="card-header">
                        <h3 class="card-title">
                        @if (Auth::user()->id_admin == '0' or Auth::user()->id_admin == '1')
                            <a href="/tambah-tahapan" class="btn btn-outline-primary btn-block"><i class="fa fa-plus"></i> Tambah Tahapan</a>
                        @endif
                        </h3>
                    </div>  --}}
                    
                    <div class="card-body">
                   <h3> Wilayah Provinsi {{$provinsi1->provinsi}}</h3>
                        <table id="example1" class="table table-bordered table-striped">
                          <thead>
                          <tr>
                            <th>No</th>
                            <th>Nama Pengguna</th>
                            <th>Kab/Kota</th>
                            <th>Email</th>
                            <th>Jabatan</th>
                            <th>Aksi</th>
                            
                          </tr>
                          </thead>
                          <tbody>
                            @foreach ($show_user_kab as $no => $kab)
                                                            @php
                                $id=Crypt::encryptString($kab->id);
                              @endphp
                            <tr>
                                <td>{{++$no}}</td>
                                <td>{{$kab->name}}</td>
                                <td><a href="/user-kec-show/{{ $id }}">{{$kab->kabupaten}}</a></td> 
                                <td>{{$kab->email}}</td>
                                
                                <td>
                                Bawaslu Kabupaten/Kota
                                {{--  {{$kab->Jabatan}}  --}}
                                <br>
                                @if ($kab->id_divisi == '')
                            <small class="badge badge-info">User Admin</small>
                        @endif
                          {{$kab->id_divisi}}
                                </td>
                               @php
                        $id=Crypt::encryptString($kab->ID);
                    @endphp
                        <td>
                        <div class="margin">
                            <div class="btn-xs btn-group">
                              <button type="button" class="btn-sm btn btn-primary">Menu</button>
                              <button type="button" class="btn-sm btn btn-block btn-outline-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                <span class="sr-only">Toggle Dropdown</span>
                              </button>
                              <div class="dropdown-menu" role="menu">
                                            @php
                                            $id=Crypt::encryptString($kab->ID);
                                        @endphp
                                        <center>
                                <a class="dropdown-item" href="/user-akses-show/{{$id}}">Lihat</a>
                                  </center>
                                <div class="dropdown-divider"></div>

                               
                              <center>
                                      <form method="POST" class="d-inline" 
                                        onsubmit="return confirm('Data akan di hapus permanen?')" 
                                        action="/user-akses/{{ $kab->ID}}/destroy">
                                          @csrf
                                          <input type="hidden" value="DELETE" name="_method">
                                          <button type="submit" value="Delete" class="btn btn-xs btn-danger">
                                            <i class="fas fa-trash"></i> Hapus</button>
                                      </form>
                              </center>

                              </div>
                            </div>
                        </td>

                    </tr>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
@endsection

