@extends('layouts.opd.app')

@section('content')
<link href="{{ asset('app/css/hc.css')}}" rel="stylesheet">
<script src="{{ asset('highcharts/highcharts.js')}}"></script>
<script src="{{ asset('highcharts/series-label.js')}}"></script>
<script src="{{ asset('highcharts/highcharts-3d.js')}}"></script>
<script src="{{ asset('highcharts/exporting.js')}}"></script>
<script src="{{ asset('highcharts/exporting-data.js')}}"></script>
<script src="{{ asset('highcharts/accessibility.js')}}"></script>
<!--
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/highcharts-3d.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
-->
<section class="content-header">
    {{-- <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-10">  --}}
    {{-- <h2>GRAFIK PERIODE <i>{{ date('d-m-Y', strtotime($date_start)) }}</i> SAMPAI <i>{{ date('d-m-Y', strtotime($date_finish)) }}</i></h1> --}}
    {{-- </div>
      <div class="col-sm-2">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Grafik</li>
        </ol>
      </div>
    </div>
  </div>  --}}

    <!-- /.container-fluid -->
</section>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title grafik_periode">GRAFIK PERIODE <b>{{ date('d-m-Y', strtotime($date_start)) }}</b> SAMPAI <b>{{ date('d-m-Y', strtotime($date_finish)) }}</b></h5>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <div class="btn-group">
                        <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                            <i class="fas fa-wrench"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" role="menu">
                            <a href="#" class="dropdown-item">Action</a>
                            <a href="#" class="dropdown-item">Another action</a>
                            <a href="#" class="dropdown-item">Something else here</a>
                            <a class="dropdown-divider"></a>
                            <a href="#" class="dropdown-item">Separated link</a>
                        </div>
                    </div>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="{{ url('/dashboard') }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <input type="text" onfocus="(this.type='date')" class="form-control" id="frm_date_start"  name="date_start" placeholder="Tanggal Mulai">
                        </div>
                        <div class="col-6">
                            <input type="text" onfocus="(this.type='date')" class="form-control" id="frm_date_finish" name="date_finish" placeholder="Tanggal Selesai">
                        </div>
					</div>
					<div class="row mt-2">
                        <div class="col-4">
							<select name="divisi" class="form-control" id="divisi">
								<option value="" selected> Pilih Divisi </option>
									@foreach( $dropdowns['divisi'] as $item)
										<option value="{{ $item->kd_petugas}}" {{ (old('divisi') == $item->kd_petugas) ? 'selected' : '' }}> {{ $item->kd_petugas }} </option>
									@endforeach
							</select>
                        </div>
                        <div class="col-4">
							<select name="bentuk" class="form-control" id="bentuk">
								<option value="" selected> Pilih Bentuk Pencegahan </option>
									@foreach( $dropdowns['bentuk'] as $item)
										<option value="{{ $item->id_bentuk}}" {{ (old('bentuk') == $item->id_bentuk) ? 'selected' : '' }}> {{ $item->bentuk }} </option>
									@endforeach
							</select>
                        </div>
						<div class="col-4">
							<select name="jenis" class="form-control" id="jenis">
								<option value="" selected> Pilih Jenis </option>
									@foreach( $dropdowns['jenis'] as $item)
										<option value="{{ $item->id_jenis}}" {{ (old('jenis') == $item->id_jenis) ? 'selected' : '' }}> {{ $item->jenis }} </option>
									@endforeach
							</select>
                        </div>
					</div>         
                    <div class="row mt-2">
                    @if (auth()->user()->id_admin=='0')
                            <div class="col-4">
                                <select name="pilih_wilayah" class="form-control" id="pilih_wilayah">
                                    <option value="" selected> Pilih Wilayah </option>
                                    <option value="provinsi"> Provinsi </option>
                                    <option value="kota"> Kota/Kabupaten </option>
                                </select>
                            </div>
                            <div class="col-4">
                                <select id="wilayah_dropdown" class="form-control" name="wilayah_dropdown">
                                </select>
                            </div>
                    @endif
                    <div class="col-4">
                        <!--<input type="submit" class="btn btn-primary" value="Submit">-->
                        <a class="btn btn-warning" id='tampilkan'>Tampilkan</a>
                    </div>
                    </div>
                </form>
			</div>
    <!-- ./card-body -->
    <div class="card-footer">
        <div class="row">
            <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                    {{--  <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 17%</span>  --}}
                    <h5 class="description-header" id='identifikasi_kerawananSum'>.</h5>
                    <span class="description-text">IDENTIFIKASI KERAWANAN</span>
                </div>
                <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                    {{--  <span class="description-percentage text-warning"><i class="fas fa-caret-left"></i> 0%</span>  --}}
                    <h5 class="description-header" id='pendidikanSum'>.</h5>
                    <span class="description-text">PENDIDIKAN</span>
                </div>
                <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                    {{--  <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 20%</span>  --}}
                    <h5 class="description-header" id='partisipasiSum'>.</h5>
                    <span class="description-text">PARTISIPASI MASYARAKAT</span>
                </div>
                <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-3 col-6">
                <div class="description-block">
                    {{--  <span class="description-percentage text-danger"><i class="fas fa-caret-down"></i> 18%</span>  --}}
                    <h5 class="description-header" id='kerjasamaSum'>.</h5>
                    <span class="description-text">KERJA SAMA</span>
                </div>
                <!-- /.description-block -->
            </div>
            <div class="col-sm-3 col-6">
                <div class="description-block">
                    {{--  <span class="description-percentage text-danger"><i class="fas fa-caret-down"></i> 18%</span>  --}}
                    <h5 class="description-header" id='naskahdinasSum'>.</h5>
                    <span class="description-text">Naskah Dinas</span>
                </div>
                <!-- /.description-block -->
            </div>
            <div class="col-sm-3 col-6">
                <div class="description-block">
                    {{--  <span class="description-percentage text-danger"><i class="fas fa-caret-down"></i> 18%</span>  --}}
                    <h5 class="description-header" id='kegiatanlainSum'>.</h5>
                    <span class="description-text">KEGIATAN LAINNYA</span>
                </div>
                <!-- /.description-block -->
            </div>
            <div class="col-sm-3 col-6">
                <div class="description-block">
                    {{--  <span class="description-percentage text-danger"><i class="fas fa-caret-down"></i> 18%</span>  --}}
                    <h5 class="description-header" id='publikasiSum'>.</h5>
                    <span class="description-text">PUBLIKASI</span>
                </div>
                <!-- /.description-block -->
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.card-footer -->
</div>
<!-- /.card -->
</div>
<!-- /.col -->
</div>
<!-- /.row -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">


        <div class="row">
            <!-- /.col -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card card-warning card-outline">
                        <div class="card-header">
                            <h3 class="card-title ringkasan1">
                                Ringkasan
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    {{-- 3d pie --}}
                                    <figure class="highcharts-figure">
                                        <div id="container1"></div>
                                    </figure>
                                </div>
                                <div class="col-6">
                                    {{-- 3d pie --}}
                                    <figure class="highcharts-figure">
                                        <div id="container2"></div>
                                    </figure>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        @if($jabatan == 'Sekretariat Bawaslu Provinsi')
            <div class="row">
                <!-- /.col -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card card-warning card-outline">
                            <div class="card-header">
                                <h3 class="card-title ringkasan2">
                                    Data Sebaran Bawaslu RI Berdasarkan Bentuk
                                </h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                    <figure class="highcharts-figure">
                                        <div id="containerRI"></div>
                                    </figure>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
        @endif

        @if($jabatan == 'Ketua atau Anggota Bawaslu Provinsi')
            @if($userProv == 'pusat')
                <div class="row">
                    <!-- /.col -->
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card card-warning card-outline">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        Data Sebaran Bawaslu RI Berdasarkan Bentuk
                                    </h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                        <figure class="highcharts-figure">
                                            <div id="containerRI"></div>
                                        </figure>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
            @elseif($userProv == 'non pusat')
                <div class="d-none row" hidden>
                    <!-- /.col -->
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card card-warning card-outline">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        Data Sebaran Bawaslu RI Berdasarkan Bentuk
                                    </h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                        <figure class="highcharts-figure">
                                            <div id="containerRI"></div>
                                        </figure>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
            @endif
        @endif

        @if($jabatan == 'Ketua atau Anggota Bawaslu Kabupaten/Kota' || $jabatan == 'Bawaslu Kecamatan')
            <div class="d-none row" hidden>
                <!-- /.col -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card card-warning card-outline">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Data Sebaran Bawaslu RI Berdasarkan Bentuk
                                </h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                    <figure class="highcharts-figure">
                                        <div id="containerRI"></div>
                                    </figure>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
        @endif
        <div class="row">
            <!-- /.col -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card card-warning card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                Data Sebaran Bawaslu Berdasarkan Jenis di {{ $title }}
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                <figure class="highcharts-figure">
                                    <div id="containerJenis"></div>
                                </figure>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <div class="row">
            <!-- /.col -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card card-warning card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                {{ 'Data Sebaran Form Pencegahan'.$title }}
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <figure class="highcharts-figure">
                                        <div id="container"></div>
                                    </figure>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
		
		{{-- <div class="row">
            <!-- /.col -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card card-warning card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                {{ 'Rekap Data Sebaran Form Pencegahan'.$title }}
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <table id="example1" class="table table-bordered table-striped">
									  <thead>
										<tr>
											<th>No Form</th>
											<th>Tipe</th>
											<th>Bentuk</th>
											<th>Provinsi</th>
											<th>Kota/Kab</th>
											<th>Kecamatan</th>
											<th>Kelurahan</th>
											<th>Aksi</th>
										</tr>
									  </thead>
									  <tbody>
										@foreach ($form as $cegah)
										<tr>
											<td>{{ str_replace("//","/".ltrim(date('m', strtotime($cegah->created_at)),'0')."/",$cegah->no_form) }}</td>
											<td>{{ $cegah->tahap }}</td>
											<td>{{ $cegah->bentuk }}</td>
											<td>{{ $cegah->provinsi }}</td>
											<td>{{ $cegah->kabupaten }}</td>
											<td>{{ $cegah->kecamatan }}</td>
											<td>{{ $cegah->kelurahan }}</td>
											<td>
												<a class="btn btn-primary btn-sm" href="/cetak-form/{{ Crypt::encryptString($cegah->id) }}">
												<i class="fas fa-folder">
												</i>
												View
												</a>
											</td>
										</tr>
										@endforeach
									  </tbody>
									</table>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div> --}}
        <!-- /.row -->
    </div><!-- /.container-fluid -->
    <input type="hidden" name="date_start" id="date_start" value="{{ date('Y-m-d', strtotime($date_start)) }}">
    <input type="hidden" name="date_finish" id="date_finish" value="{{ date('Y-m-d', strtotime($date_finish)) }}">
</section>
<!-- /.content -->
<?php ///echo json_encode($dataTahap, JSON_NUMERIC_CHECK); ?>

<!-- load jquery dibawah harus ditutup, krn sudah diload oleh si layout -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function () {
  
    var date_start = document.getElementById("date_start").value;
    var date_finish = document.getElementById("date_finish").value;

    var frm_date_start  = $('#frm_date_start').val();
    var frm_date_finish = $('#frm_date_finish').val();
    var divisi          = $('#divisi').val();
    var bentuk          = $('#bentuk').val();
    var jenis           = $('#jenis').val();
    var pilih_wilayah   = $('#pilih_wilayah').val();
    var wilayah_dropdown= $('#wilayah_dropdown').val();

    /*------------------------------------------
    --------------------------------------------
    Dropdown Change Event
    --------------------------------------------
    --------------------------------------------*/
    $('#pilih_wilayah').on('change', function () {
        //alert('hai');
        var valWilayah = this.value;
        $("#wilayah_dropdown").html('');
        $.ajax({
            url: "{{url('dashboard/fetch-wilayah')}}",
            type: "POST",
            data: {
                val_wilayah: valWilayah,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function (result) {
                $('#wilayah_dropdown').html('<option value="">-- Pilih Filter --</option>');
                $.each(result.states, function (key, value) {
                    $("#wilayah_dropdown").append('<option value="' + value
                        .id + '">' + value.name + '</option>');
                });
            }
        });
    });

    /*------------------------------------------
    --------------------------------------------
    State Dropdown Change Event
    --------------------------------------------
    --------------------------------------------*/
    $('#state-dropdown').on('change', function () {
        var idState = this.value;
        $("#city-dropdown").html('');
        $.ajax({
            url: "{{url('api/fetch-cities')}}",
            type: "POST",
            data: {
                state_id: idState,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function (res) {
                $('#city-dropdown').html('<option value="">-- Select City --</option>');
                $.each(res.cities, function (key, value) {
                    $("#city-dropdown").append('<option value="' + value
                        .id + '">' + value.name + '</option>');
                });
            }
        });
    });

    /*------------------------------------------
    --------------------------------------------
    click Tampilam
    --------------------------------------------
    --------------------------------------------*/
    $('#tampilkan').on('click', function () {
        //alert('anda tekan tampilkan');
        var frm_date_start  = $('#frm_date_start').val();
        var frm_date_finish = $('#frm_date_finish').val();
        var divisi          = $('#divisi').val();
        var bentuk          = $('#bentuk').val();
        var jenis           = $('#jenis').val();
        var pilih_wilayah   = $('#pilih_wilayah').val();
        var wilayah_dropdown= $('#wilayah_dropdown').val();
        
        var form_data = {
            date_start: frm_date_start,
            date_finish: frm_date_finish,
            divisi: divisi,
            bentuk: bentuk,
            jenis: jenis,
            pilih_wilayah: pilih_wilayah,
            wilayah_dropdown: wilayah_dropdown,
            _token: '{{csrf_token()}}'
        };
            $("#kegiatanlainSum").show().html('.');
            $("#pendidikanSum").show().html('');
            $("#pendidikanSum").show().html('.');
            $("#partisipasiSum").show().html('.');
            $("#kerjasamaSum").show().html('.');
            $("#naskahdinasSum").show().html('.');
            $("#publikasiSum").show().html('.');
            $("#identifikasi_kerawananSum").show().html('.');
        
        $.ajax({
            url: "{{url('recap/filter-all-sums')}}",
            type: "POST",
            data: form_data,
            dataType: 'json',
            success: function (msg) {
                //console.log(bentuk);
                if(bentuk==0){
                    $("#kegiatanlainSum").show().html(msg);
                } else if(bentuk==1) {
                    $("#pendidikanSum").show().html(msg);
                    $("#pendidikanSum").show().html(msg);
                } else if(bentuk==2) {
                    $("#partisipasiSum").show().html(msg);
                } else if(bentuk==3) {
                    $("#kerjasamaSum").show().html(msg);
                } else if(bentuk==4) {
                    $("#naskahdinasSum").show().html(msg);
                } else if(bentuk==5) {
                    $("#publikasiSum").show().html(msg);
                } else {
                    $("#identifikasi_kerawananSum").show().html(msg);
                }                
                $(".grafik_periode").show().html('GRAFIK PERIODE <b>'+frm_date_start+'</b> SAMPAI <b>'+frm_date_finish+'</b>');
            }
        });
        
        loadContainer1(frm_date_start,frm_date_finish,divisi,bentuk,jenis,pilih_wilayah,wilayah_dropdown,date_start,date_finish);
        loadContainer2(frm_date_start,frm_date_finish,divisi,bentuk,jenis,pilih_wilayah,wilayah_dropdown,date_start,date_finish);

    });

    /*--------------------------------------------
    Call Function All SUM
    ----------------------------------------------*/
    setTimeout(callSums(0), 1000);    //Kegiatan Lainnya
    setTimeout(callSums(1), 1000);  //Pendidikan
    setTimeout(callSums(2), 1000);  //Partisipasi Masyarakat
    setTimeout(callSums(3), 1000);  //Kerja Sama
    setTimeout(callSums(4), 1000);  //Naskah Dinas
    setTimeout(callSums(5), 1000);  //Publikasi
    setTimeout(callSums(6), 1000);      //Identifikasi Kerawanan
    function callSums(bentuk){        
        $(".overlay").show(); 

        $.ajax({
            url: "{{url('recap/all-sums')}}",
            type: "POST",
            data: {
                bentuk: bentuk,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function (msg) {
                if(bentuk==0){
                    $("#kegiatanlainSum").show().html(msg);
                } else if(bentuk==1) {
                    $("#pendidikanSum").show().html(msg);
                } else if(bentuk==2) {
                    $("#partisipasiSum").show().html(msg);
                } else if(bentuk==3) {
                    $("#kerjasamaSum").show().html(msg);
                } else if(bentuk==4) {
                    $("#naskahdinasSum").show().html(msg);
                } else if(bentuk==5) {
                    $("#publikasiSum").show().html(msg);
                } else {
                    $("#identifikasi_kerawananSum").show().html(msg);
                }

                $(".overlay").hide(); 
                //console.msg;
            }
        });  
        
        
    };
    /** close Call A **/
    function callFilterSums(id)
    {
        var bentuk = id;
        $.ajax({
            url: "{{url('recap/filter-all-sums')}}",
            type: "POST",
            data: {
                bentuk: bentuk,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function (msg) {
                if(bentuk==0){
                    $("#kegiatanlainSum").show().html(msg);
                } else if(bentuk==1) {
                    $("#pendidikanSum").show().html(msg);
                } else if(bentuk==2) {
                    $("#partisipasiSum").show().html(msg);
                } else if(bentuk==3) {
                    $("#kerjasamaSum").show().html(msg);
                } else if(bentuk==4) {
                    $("#naskahdinasSum").show().html(msg);
                } else if(bentuk==5) {
                    $("#publikasiSum").show().html(msg);
                } else {
                    $("#identifikasi_kerawananSum").show().html(msg);
                }

                //console.msg;
            }
        }); 
    }

    /*--------------------------------------------
    Call Container 1
    ----------------------------------------------*/
    setTimeout(loadContainer1(frm_date_start,frm_date_finish,divisi,bentuk,jenis,pilih_wilayah,wilayah_dropdown,date_start,date_finish), 1000);
    function loadContainer1(frm_date_start,frm_date_finish,divisi,bentuk,jenis,pilih_wilayah,wilayah_dropdown,date_start,date_finish)
    {
        var form_data = {
            date_start: date_start,
            date_finish: date_finish,
            divisi: divisi,
            bentuk: bentuk,
            jenis: jenis,
            pilih_wilayah: pilih_wilayah,
            wilayah_dropdown: wilayah_dropdown,
            _token: '{{csrf_token()}}'
        };

        var output = [];
        $.ajax({
            url: "{{url('recap/data-tahap')}}",
            type: "POST",
            dataType: "json",
            data: form_data,
            success: function(output_string) {
                console.log(output_string);
                //call my_chart function
                var parsed_response = JSON.parse(JSON.stringify(output_string));
                chart_container1(parsed_response);
                //chart_container1(output_string);
            },
            error: function (xhr, ajaxOptions, thrownError){
                console.log(xhr.statusText);
                console.log(thrownError);
            }
        });
    }
    function chart_container1(response)
    {
        Highcharts.chart('container1', {
            chart: {
                renderTo: 'container1',
                type: 'pie',
                options3d: {
                    enabled: true,
                    alpha: 45,
                    beta: 0
                },
                
            },
            title: {
                text: 'Berdasarkan Data Tahapan',
                align: 'left'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    depth: 35,
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}'
                    }
                }
            },
            series: [{
                type: 'pie',
                name: 'Count',
                colorByPoint: true,
                data: response
            }]
        });
    }
    /** Close Call Container1 */


    /*--------------------------------------------
    Call Container 2
    ----------------------------------------------*/
    setTimeout(loadContainer2(frm_date_start,frm_date_finish,divisi,bentuk,jenis,pilih_wilayah,wilayah_dropdown,date_start,date_finish), 1000);
    function loadContainer2(frm_date_start,frm_date_finish,divisi,bentuk,jenis,pilih_wilayah,wilayah_dropdown,date_start,date_finish)
    {
        var form_data = {
            date_start: date_start,
            date_finish: date_finish,
            divisi: divisi,
            bentuk: bentuk,
            jenis: jenis,
            pilih_wilayah: pilih_wilayah,
            wilayah_dropdown: wilayah_dropdown,
            _token: '{{csrf_token()}}'
        };

        var output = [];
        $.ajax({
            url: "{{url('recap/data-bentuk')}}",
            type: "POST",
            dataType: "json",
            data: form_data,
            success: function(output_string) {
                console.log(output_string);
                //call my_chart function
                var parsed_response = JSON.parse(JSON.stringify(output_string));
                chart_container2(parsed_response);
            },
            error: function (xhr, ajaxOptions, thrownError){
                console.log(xhr.statusText);
                console.log(thrownError);
            }
        });
    }
    function chart_container2(response)
    {
        Highcharts.chart('container2', {
            chart: {
                type: 'pie',
                options3d: {
                    enabled: true,
                    alpha: 45
                }
            },
            title: {
                text: 'Data Tahapan Berdasarkan Data Bentuk',
                align: 'left'
            },
            plotOptions: {
                pie: {
                    innerSize: 100,
                    depth: 45,
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    }
                }
            },
            series: [{
                type: 'pie',
                name: 'Count',
                colorByPoint: true,
                data: response
            }]
        });
    }
    /** Close Call Container2 */    


    /*--------------------------------------------
    Call Container RI by Bentuk
    ----------------------------------------------*/
    setTimeout(loadContainerRI(frm_date_start,frm_date_finish,divisi,bentuk,jenis,pilih_wilayah,wilayah_dropdown,date_start,date_finish), 1000);
    function loadContainerRI(frm_date_start,frm_date_finish,divisi,bentuk,jenis,pilih_wilayah,wilayah_dropdown,date_start,date_finish)
    {
        var form_data = {
            date_start: date_start,
            date_finish: date_finish,
            divisi: divisi,
            bentuk: bentuk,
            jenis: jenis,
            pilih_wilayah: pilih_wilayah,
            wilayah_dropdown: wilayah_dropdown,
            _token: '{{csrf_token()}}'
        };

        var output = [];
        $.ajax({
            url: "{{url('recap/data-bentuk-ri')}}",
            type: "POST",
            dataType: "json",
            data: form_data,
            success: function(output_string) {
                console.log(output_string);
                //call my_chart function
                var parsed_response = JSON.parse(JSON.stringify(output_string));
                chart_container_bentuk_ri(parsed_response);
            },
            error: function (xhr, ajaxOptions, thrownError){
                console.log(xhr.statusText);
                console.log(thrownError);
            }
        });
    }
    function chart_container_bentuk_ri(response)
    {
        Highcharts.chart('containerRI', {
        chart: {
            type: 'column'
        },
        title: {
            text: '',
            align: 'left'
        },
        xAxis: {
            categories: response[0],
            crosshair: true,
            accessibility: {
                description: 'Categories'
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Jumlah Form'
            }
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [
            {
                name: 'Data Bentuk',
                data: response[1]
            }
        ]
    });
    }
    /** Close Call Container RI by Bentuk */  


    /*--------------------------------------------
    Call Container Jenis Prov
    ----------------------------------------------*/
    setTimeout(loadContainerJenis(frm_date_start,frm_date_finish,divisi,bentuk,jenis,pilih_wilayah,wilayah_dropdown,date_start,date_finish), 1000);
    function loadContainerJenis(frm_date_start,frm_date_finish,divisi,bentuk,jenis,pilih_wilayah,wilayah_dropdown,date_start,date_finish)
    {
        var form_data = {
            date_start: date_start,
            date_finish: date_finish,
            divisi: divisi,
            bentuk: bentuk,
            jenis: jenis,
            pilih_wilayah: pilih_wilayah,
            wilayah_dropdown: wilayah_dropdown,
            _token: '{{csrf_token()}}'
        };

        var output = [];
        $.ajax({
            url: "{{url('recap/data-jenis')}}",
            type: "POST",
            dataType: "json",
            data: form_data,
            success: function(output_string) {
                console.log(output_string);
                //call my_chart function
                var parsed_response = JSON.parse(JSON.stringify(output_string));
                chart_container_jenis(parsed_response);
            },
            error: function (xhr, ajaxOptions, thrownError){
                console.log(xhr.statusText);
                console.log(thrownError);
            }
        });
    }
    function chart_container_jenis(response)
    {
        Highcharts.chart('containerJenis', {
            chart: {
                type: 'bar'
            },
            title: {
                text: '',
                align: 'left'
            },
            xAxis: {
                categories: response[0],
                crosshair: true,
                accessibility: {
                    description: 'Categories'
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Jumlah Form'
                }
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [
                {
                    name: 'Data Jenis',
                    data: response[1]
                }
            ]
        });
    }
    /** Close Call Container Jenis */  


    /*--------------------------------------------
    Call Container Pencegahan
    ----------------------------------------------*/
    setTimeout(loadContainerCegah(frm_date_start,frm_date_finish,divisi,bentuk,jenis,pilih_wilayah,wilayah_dropdown,date_start,date_finish), 1000);
    function loadContainerCegah(frm_date_start,frm_date_finish,divisi,bentuk,jenis,pilih_wilayah,wilayah_dropdown,date_start,date_finish)
    {
        var form_data = {
            date_start: date_start,
            date_finish: date_finish,
            divisi: divisi,
            bentuk: bentuk,
            jenis: jenis,
            pilih_wilayah: pilih_wilayah,
            wilayah_dropdown: wilayah_dropdown,
            _token: '{{csrf_token()}}'
        };

        var output = [];
        $.ajax({
            url: "{{url('recap/data-pencegahan')}}",
            type: "POST",
            dataType: "json",
            data: form_data,
            success: function(output_string) {
                console.log(output_string);
                //call my_chart function
                var parsed_response = JSON.parse(JSON.stringify(output_string));
                chart_container_pencegahan(parsed_response);
            },
            error: function (xhr, ajaxOptions, thrownError){
                console.log(xhr.statusText);
                console.log(thrownError);
            }
        });
    }
    function chart_container_pencegahan(response)
    {
        Highcharts.chart('container', {
            chart: {
                type: 'bar'
            },
            title: {
                text: '',
                align: 'left'
            },
            
            xAxis: {
                categories: response[0],
            },

            yAxis: {
                min: 0,
                title: {
                    text: 'Jumlah'
                }
            },

            legend: {
                reversed: true
            },

            plotOptions: {
                series: {
                    stacking: 'normal',
                    dataLabels: {
                        enabled: true
                    },
                }
            },

            series: [{
                    name: 'Identifikasi Kerawanan',
                    data: response[1]
                },
                {
                    name: 'Pendidikan',
                    data: response[2]
                },
                {
                    name: 'Partisipasi Masyarakat',
                    data: response[3]
                },
                {
                    name: 'Kerjasama',
                    data: response[4]
                },
                {
                    name: 'Naskah Dinas',
                    data: response[5]
                },
                {
                    name: 'Kegiatan Lainnya',
                    data: response[6]
                },
                {
                    name: 'Publikasi',
                    data: response[7]
                }
            ],


        });
    }
    /** Close Call Container Jenis */  


});
</script>
@endsection

