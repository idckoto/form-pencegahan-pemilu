@extends('layouts.opd.app')
@section('content')
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
            <center>
          <h1>List User</h1>
         </center>
        </div>
      </div>
    </div><!-- /.container-fluid -->
</section>
<div class="card card-primary card-outline">
          <div class="card-header">
            <h3 class="card-title">
                @if (Auth::user()->id_admin == '0' or Auth::user()->id_admin == '1') 
                  @if (Auth::user()->Jabatan <> 'Bawaslu Kecamatan') 
                          <a href="/user-akses-create" class="btn btn-outline-primary btn-block"><i class="fa fa-plus">
                          </i> Tambah Data User</a>
                    @endif
                @endif
            </h3>
          </div>
          <div class="card-body">
            {{--  <h4>Custom Content Below</h4>  --}}
            <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Data User</a>
              </li>
        @if (Auth::user()->id_admin == '0') 
              <li class="nav-item">
                <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">User Provinsi</a>
              </li>
        @endif

        @if (Auth::user()->id_admin == '1') 
        @if (Auth::user()->Jabatan == 'Ketua atau Anggota Bawaslu Provinsi') 
              <li class="nav-item">
                <a class="nav-link" id="custom-content-below-messages-tab" data-toggle="pill" href="#custom-content-below-messages" role="tab" aria-controls="custom-content-below-messages" aria-selected="false">User Kabupaten/Kota</a>
              </li>
        @endif
        @endif

        @if (Auth::user()->Jabatan == 'Ketua atau Anggota Bawaslu Kabupaten/Kota') 
              <li class="nav-item">
                <a class="nav-link" id="custom-content-below-settings-tab" data-toggle="pill" href="#custom-content-below-settings" role="tab" aria-controls="custom-content-below-settings" aria-selected="false">User Kecamatan</a>
              </li>
        @endif
        
            </ul>
            
            <div class="tab-content" id="custom-content-below-tabContent">

            {{--  user utama  --}}
              <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                <br>
                 <table id="example3" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Pengguna</th>
                    <th>Jabatan</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Aksi</th>
                    
                 
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($user as $no => $btk)
                    <tr>
                        <td>{{++$no}}</td>
                        <td>{{$btk->name}} </td>
                           <td>
                           @if (Auth::user()->Jabatan == 'Sekretariat Bawaslu Provinsi')
                           Bawaslu RI
                           
                           @elseif (Auth::user()->Jabatan == 'Ketua atau Anggota Bawaslu Provinsi')
                            Bawaslu Provinsi

                            @elseif (Auth::user()->Jabatan == 'Ketua atau Anggota Bawaslu Kabupaten/Kota')
                            Bawaslu Kabupaten

                            @else
                            {{$btk->Jabatan}}

                              @endif
                           </td> 
                        
   
                        <td>{{$btk->email}}</td>
                       
                        
                        <td>
                        @if ($btk->id_divisi == '')
                            <small class="badge badge-info">User Admin</small>
                        @endif
                          {{$btk->id_divisi}}
                        
                       </td>
                        @php
                        $id=Crypt::encryptString($btk->id);
                    @endphp
                        <td>
                        <div class="margin">
                            <div class="btn-xs btn-group">
                              <button type="button" class="btn-xs btn btn-primary">Menu</button>
                              <button type="button" class="btn-xs btn btn-block btn-outline-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                <span class="sr-only">Toggle Dropdown</span>
                              </button>
                              <div class="dropdown-menu" role="menu">
                                            @php
                                            $id=Crypt::encryptString($btk->id);
                                        @endphp
                                        <center>
                                <a class="dropdown-item" href="/user-akses-show/{{$id}}">Lihat</a>
                                  </center>
                                <div class="dropdown-divider"></div>

                               
                              <center>
                                      <form method="POST" class="d-inline" 
                                        onsubmit="return confirm('Data akan di hapus permanen?')" 
                                        action="/user-akses/{{ $btk->id}}/destroy">
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
                    @endforeach
                  </tbody>
                </table>
              </div>
            {{--  tutup user utama  --}}

            {{--  user provinsi  --}}
              <div class="tab-pane fade" id="custom-content-below-profile" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
             <br>
              <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Pengguna..</th>                
                    <th>Provinsi</th>                
                    <th>Email</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                  </thead>
                  <tbody>
                     @foreach ($userprovinsi as $no => $btk1)
                    
                    @php
                    $id=Crypt::encryptString($btk1->id);
                  @endphp
                  <tr>
                        <td>{{++$no}}</td>
                        <td>{{$btk1->name}}</td>
                        <td><a href="user-kab-show/{{ $id }}">{{$btk1->provinsi}} </a></td>
                        <td>{{$btk1->email}}</td>
                        <td>   
                              Bawaslu Provinsi <br>
                              @if ($btk1->id_divisi == '')
                                  <small class="badge badge-info">User Admin</small>
                              @endif
                                {{$btk1->id_divisi}}
                        </td>
                  <td>
                       <div class="margin">
                            <div class="btn-xs btn-group">
                              <button type="button" class="btn-xs btn btn-primary">Menu</button>
                              <button type="button" class="btn-xs btn btn-block btn-outline-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                <span class="sr-only">Toggle Dropdown</span>
                              </button>
                              <div class="dropdown-menu" role="menu">
                                            @php
                                            $id_2=Crypt::encryptString($btk1->ID);
                                        @endphp
                                        <center>
                                <a class="dropdown-item" href="/user-akses-show/{{$id_2}}">Lihat</a>
                                  </center>
                                <div class="dropdown-divider"></div>

                               
                              <center>
                                      <form method="POST" class="d-inline" 
                                        onsubmit="return confirm('Data akan di hapus permanen?')" 
                                        action="/user-akses/{{ $btk1->ID}}/destroy">
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
                    @endforeach
                   
                  </tbody>
                </table>
              </div>

            {{--  tutup user provinsi  --}}

            {{--  user kabupaten  --}}
              <div class="tab-pane fade" id="custom-content-below-messages" role="tabpanel" aria-labelledby="custom-content-below-messages-tab">
                 <br>
                 <table id="example4" class="table table-bordered table-striped">
                              <thead>
                              <tr>
                                <th>No</th>
                                <th>Nama Pengguna..</th>                            
                                <th>Kabupaten</th>                              
                                <th>Email</th>                              
                                <th>Status</th>
                                <th>Aksi</th>
                              </tr>
                              </thead>
                              <tbody>
                                @foreach ($userkabkota as $no => $btk1)
                                
                                @php
                                $id=Crypt::encryptString($btk1->id);
                              @endphp
            
                                <tr>
                                    <td>{{++$no}}</td>
                                    <td>{{$btk1->name}}</td>
                                    <td><a href="/user-kec-show/{{ $id }}">{{$btk1->kabupaten}}</a></td>
                                    <td>{{$btk1->email}}</td>
                                    <td>
                                    Bawaslu Kabupaten/Kota
                                    <br>
                                    @if ($btk1->id_divisi == '')
                                        <small class="badge badge-info">User Admin</small>
                                    @endif
                                      {{$btk1->id_divisi}}
                                    
                                   </td>
                                   <td>
                       <div class="margin">
                            <div class="btn-xs btn-group">
                              <button type="button" class="btn-xs btn btn-primary">Menu</button>
                              <button type="button" class="btn-xs btn btn-block btn-outline-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                <span class="sr-only">Toggle Dropdown</span>
                              </button>
                              <div class="dropdown-menu" role="menu">
                                            @php
                                            $id_2=Crypt::encryptString($btk1->ID);
                                        @endphp
                                        <center>
                                <a class="dropdown-item" href="/user-akses-show/{{$id_2}}">Lihat</a>
                                  </center>
                                <div class="dropdown-divider"></div>

                               
                              <center>
                                      <form method="POST" class="d-inline" 
                                        onsubmit="return confirm('Data akan di hapus permanen?')" 
                                        action="/user-akses/{{ $btk1->ID}}/destroy">
                                          @csrf
                                          <input type="hidden" value="DELETE" name="_method">
                                          <button type="submit" value="Delete" class="btn btn-xs btn-danger">
                                            <i class="fas fa-trash"></i>  Hapus</button>
                                      </form>
                              </center>

                              </div>
                          </div>
                          
                  </td>
            
                                </tr>
                                @endforeach
                              </tbody>
                            </table>
              </div>
            {{--  tutup user kabupaten  --}}

            {{--  user kecamatan  --}}
              <div class="tab-pane fade" id="custom-content-below-settings" role="tabpanel" aria-labelledby="custom-content-below-settings-tab">
              <table id="example4" class="table table-bordered table-striped">
                              <thead>
                              <tr>
                                <th>No</th>
                                <th>Nama Pengguna</th>                            
                                <th>Kabupaten</th>                              
                                <th>Email</th>                              
                                <th>Status</th>
                                <th>Aksi</th>
                              </tr>
                              </thead>
                              <tbody>
                                @foreach ($userkecamatan as $no => $btk1)
                                
                                @php
                                $id=Crypt::encryptString($btk1->id);
                              @endphp
            
                                <tr>
                                    <td>{{++$no}}</td>
                                    <td>{{$btk1->name}}</td>
                                    <td>{{$btk1->kecamatan}}</td>
                                    <td>{{$btk1->email}}</td>
                                    <td>
                                    Bawaslu Kecamatan
                                    <br>
                                    @if ($btk1->id_divisi == '')
                                        <small class="badge badge-info">User Admin</small>
                                    @endif
                                      {{$btk1->id_divisi}}
                                    
                                   </td>
                                   <td>
                       <div class="margin">
                            <div class="btn-xs btn-group">
                              <button type="button" class="btn-xs btn btn-primary">Menu</button>
                              <button type="button" class="btn-xs btn btn-block btn-outline-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                <span class="sr-only">Toggle Dropdown</span>
                              </button>
                              <div class="dropdown-menu" role="menu">
                                            @php
                                            $id_2=Crypt::encryptString($btk1->ID);
                                        @endphp
                                        <center>
                                <a class="dropdown-item" href="/user-akses-show/{{$id_2}}">Lihat</a>
                                  </center>
                                <div class="dropdown-divider"></div>

                               
                              <center>
                                      <form method="POST" class="d-inline" 
                                        onsubmit="return confirm('Data akan di hapus permanen?')" 
                                        action="/user-akses/{{ $btk1->ID}}/destroy">
                                          @csrf
                                          <input type="hidden" value="DELETE" name="_method">
                                          <button type="submit" value="Delete" class="btn btn-xs btn-danger">
                                            <i class="fas fa-trash"></i> {{$btk1->ID}} Hapus</button>
                                      </form>
                              </center>

                              </div>
                          </div>
                          
                  </td>
            
                                </tr>
                                @endforeach
                              </tbody>
                            </table>
              </div>
              {{--  tutup user kecamatan  --}}
            </div>
         
            
            
          </div>
          <!-- /.card -->
        </div>
        <!-- /.card -->

@endsection