@extends('layouts.opd.app')
@section('content')
<link href="{{ asset('app/css/hc.css')}}" rel="stylesheet">
<script src="{{ asset('highcharts/highcharts.js')}}"></script>
<script src="{{ asset('highcharts/series-label.js')}}"></script>
<script src="{{ asset('highcharts/highcharts-3d.js')}}"></script>
<script src="{{ asset('highcharts/exporting.js')}}"></script>
<script src="{{ asset('highcharts/exporting-data.js')}}"></script>
<script src="{{ asset('highcharts/accessibility.js')}}"></script>

    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-md-10">
            <h5 class="m-0">STATISTIK PENCEGAHAN :: {{ strtoupper($twp_title->nama_wp) }}</h5>
          </div><!-- /.col -->
          <div class="col-md-2">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/statistik" class="btn btn-sm btn-info">ke Statistik</a></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

  <section class="content">
    <div class="container-fluid">
        
        <div class="row">
          <div class="col-md-8">
              <div class="row">
                <div class="col-md-12">
                  <div class="card">
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
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="card">
                      <div class="card-header">
                        <h3 class="card-title ringkasan1">
                            Data Tahapan
                        </h3>
                      </div>
                      <div class="card-body">
                          <figure class="highcharts-figure">
                              <div id="container1"></div>
                          </figure>
                      </div>
                  </div>
                </div>  
                
                <div class="col-md-6">
                  <div class="card">
                      <div class="card-header">
                        <h3 class="card-title ringkasan1">
                            Data Tahapan Berdasarkan Bentuk
                        </h3>
                      </div>
                      <div class="card-body">
                          <figure class="highcharts-figure">
                              <div id="container2"></div>
                          </figure>
                      </div>
                  </div>
                </div>

              </div>



              
          </div>


          <div class="col-md-4">
            <!-- Info Boxes Style 2 -->
            <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="ion ion-pie-graph"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">IDENTIFIKASI KERAWANAN</span>
                <span class="info-box-number" id='identifikasi_kerawananSum'>0</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="ion ion-pie-graph"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">PENDIDIKAN</span>
                <span class="info-box-number" id="pendidikanSum">0</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
            <div class="info-box mb-3">
              <span class="info-box-icon bg-info elevation-1"><i class="ion ion-pie-graph"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">PARTISIPASI MASYARAKAT</span>
                <span class="info-box-number" id="partisipasiSum">0</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
            <div class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="ion ion-pie-graph"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">KERJA SAMA</span>
                <span class="info-box-number" id="kerjasamaSum">0</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->

            <div class="info-box mb-3">
              <span class="info-box-icon bg-blue elevation-1"><i class="ion ion-pie-graph"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">NASKAH DINAS</span>
                <span class="info-box-number" id="naskahdinasSum">0</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
            <div class="info-box mb-3">
              <span class="info-box-icon bg-purple elevation-1"><i class="ion ion-pie-graph"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">PUBLIKASI</span>
                <span class="info-box-number" id="publikasiSum">0</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
            <div class="info-box mb-3">
              <span class="info-box-icon bg-secondary elevation-1"><i class="ion ion-pie-graph"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">KEGIATAN LAINNYA</span>
                <span class="info-box-number" id="kegiatanlainSum">0</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>

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


    </div><!-- /.container-fluid -->
       
  </section>

  <input type="hidden" name="date_start" id="date_start" value="{{ date('Y-m-d', strtotime($date_start)) }}">
  <input type="hidden" name="date_finish" id="date_finish" value="{{ date('Y-m-d', strtotime($date_finish)) }}">
  <input type="hidden" name="twp_id" id="twp_id" value="{{ $twp_title->id }}">

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


    /*--------------------------------------------
    Call Function All SUM
    ----------------------------------------------*/
    setTimeout(callSums(0), 1000);    //Kegiatan Lainnya
    setTimeout(callSums(1), 1000);  //Pendidikan
    setTimeout(callSums(2), 1000);  //Partisipasi Masyarakat
    setTimeout(callSums(3), 1000);  //Kerja Sama
    setTimeout(callSums(4), 1000);  //Naskah Dinas
    setTimeout(callSums(5), 1000);  //Publikasi
    //setTimeout(callSums(6), 1000);      //Identifikasi Kerawanan
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
                text: '',
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
                    alpha: 45,
                    beta: 0
                }
            },
            title: {
                text: '',
                align: 'left'
            },
            plotOptions: {
                pie: {
                    innerSize: 50,
                    depth: 25,
                    dataLabels: {
                        enabled: true,
                        format: '{point.percentage:.1f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    },
                    showInLegend: true
                }
            },
            series: [{
                type: 'pie',
                name: 'Percentage',
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

});
</script>
@endsection
