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
                <h5 class="card-title">GRAFIK PERIODE <b>{{ date('d-m-Y', strtotime($date_start)) }}</b> SAMPAI <b>{{ date('d-m-Y', strtotime($date_finish)) }}</b></h5>

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
                            <input type="text" onfocus="(this.type='date')" class="form-control" name="date_start" placeholder="Tanggal Mulai">
                        </div>
                        <div class="col-6">
                            <input type="text" onfocus="(this.type='date')" class="form-control" name="date_finish" placeholder="Tanggal Selesai">
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
                        <input type="submit" class="btn btn-primary" value="Submit">
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
                    <h5 class="description-header">{{ $identifikasi_kerawananSum }}</h5>
                    <span class="description-text">IDENTIFIKASI KERAWANAN</span>
                </div>
                <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                    {{--  <span class="description-percentage text-warning"><i class="fas fa-caret-left"></i> 0%</span>  --}}
                    <h5 class="description-header">{{ $pendidikanSum }}</h5>
                    <span class="description-text">PENDIDIKAN</span>
                </div>
                <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                    {{--  <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 20%</span>  --}}
                    <h5 class="description-header">{{ $partisipasiSum }}</h5>
                    <span class="description-text">PARTISIPASI MASYARAKAT</span>
                </div>
                <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-3 col-6">
                <div class="description-block">
                    {{--  <span class="description-percentage text-danger"><i class="fas fa-caret-down"></i> 18%</span>  --}}
                    <h5 class="description-header">{{ $kerjasamaSum }}</h5>
                    <span class="description-text">KERJA SAMA</span>
                </div>
                <!-- /.description-block -->
            </div>
            <div class="col-sm-3 col-6">
                <div class="description-block">
                    {{--  <span class="description-percentage text-danger"><i class="fas fa-caret-down"></i> 18%</span>  --}}
                    <h5 class="description-header">{{ $naskahdinasSum }}</h5>
                    <span class="description-text">Naskah Dinas</span>
                </div>
                <!-- /.description-block -->
            </div>
            <div class="col-sm-3 col-6">
                <div class="description-block">
                    {{--  <span class="description-percentage text-danger"><i class="fas fa-caret-down"></i> 18%</span>  --}}
                    <h5 class="description-header">{{ $kegiatanlainSum }}</h5>
                    <span class="description-text">KEGIATAN LAINNYA</span>
                </div>
                <!-- /.description-block -->
            </div>
            <div class="col-sm-3 col-6">
                <div class="description-block">
                    {{--  <span class="description-percentage text-danger"><i class="fas fa-caret-down"></i> 18%</span>  --}}
                    <h5 class="description-header">{{ $publikasiSum }}</h5>
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


<!-- load jquery dibawah harus ditutup, krn sudah diload oleh si layout -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
<script>
    $(document).ready(function () {
  
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

    });


    var categories = <?php echo json_encode($categories) ?>;
    var categories_RI = <?php echo json_encode($categories_RI) ?>;
    var count_RI = <?php echo json_encode($count_RI) ?>;
    var categories_jenis = <?php echo json_encode($categories_jenis) ?>;
    var count_jenis = <?php echo json_encode($count_jenis) ?>;
    var identifikasi_kerawananCount = <?php echo json_encode($identifikasi_kerawananCount, JSON_NUMERIC_CHECK) ?>;
    var pendidikanCount = <?php echo json_encode($pendidikanCount, JSON_NUMERIC_CHECK) ?>;
    var partisipasiCount = <?php echo json_encode($partisipasiCount, JSON_NUMERIC_CHECK) ?>;
    var kerjasamaCount = <?php echo json_encode($kerjasamaCount, JSON_NUMERIC_CHECK) ?>;
    var imbauanCount = <?php echo json_encode($imbauanCount, JSON_NUMERIC_CHECK) ?>;
	var naskahdinasCount = <?php echo json_encode($naskahdinasCount, JSON_NUMERIC_CHECK) ?>;
    var kegiatanlainCount = <?php echo json_encode($kegiatanlainCount, JSON_NUMERIC_CHECK) ?>;
    var publikasiCount = <?php echo json_encode($publikasiCount, JSON_NUMERIC_CHECK) ?>;
    var titleLabel = <?php echo json_encode($title) ?>;
    var dataTahap = <?php echo json_encode($dataTahap, JSON_NUMERIC_CHECK) ?>;
    var dataBentuk = <?php echo json_encode($dataBentuk, JSON_NUMERIC_CHECK) ?>;
    var date_start = document.getElementById("date_start").value;
    var date_finish = document.getElementById("date_finish").value;
    //alert(count_RI);
    Highcharts.chart('containerRI', {
        chart: {
            type: 'column'
        },
        title: {
            text: '',
            align: 'left'
        },
        xAxis: {
            categories: categories_RI,
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
                data: count_RI
            }
        ]
    });

    Highcharts.chart('containerJenis', {
        chart: {
            type: 'column'
        },
        title: {
            text: '',
            align: 'left'
        },
        xAxis: {
            categories: categories_jenis,
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
                data: count_jenis
            }
        ]
    });

    Highcharts.chart('container', {
        title: {
            text: '',
            align: 'left'
        },

        subtitle: {
            //text: 'Data Sebaran Form Pencegahan'+ titleLabel,
            align: 'left'
        },

        yAxis: {
            title: {
                text: 'Jumlah Form'
            }
        },

        xAxis: {
            categories: categories,
            labels: {
                step: 0
            }
        },

        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },

        plotOptions: {
            series: {
				dataLabels: {
					enabled: true
				},
                allowPointSelect: true,
                point: {
                    events: {
                        click: function() {
                        window.open('https://formpencegahan.bawaslu.go.id/dashboard/detail/'+  this.category + '/'  + date_start + '/' + date_finish, '_blank');
                        }
                    }
                }
            }
        },

        series: [{
                name: 'Identifikasi Kerawanan',
                data: identifikasi_kerawananCount
            },
            {
                name: 'Pendidikan',
                data: pendidikanCount
            },
            {
                name: 'Partisipasi Masyarakat',
                data: partisipasiCount
            },
            {
                name: 'Kerjasama',
                data: kerjasamaCount
            },
            {
                name: 'Naskah Dinas',
                data: naskahdinasCount
            },
            {
                name: 'Kegiatan Lainnya',
                data: kegiatanlainCount
            },
            {
                name: 'Publikasi',
                data: publikasiCount
            }
        ],

        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }

    });
    
    Highcharts.chart('container1', {
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45,
                beta: 0
            }
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
        // tooltip: {
        //     pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        // },
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
            data: dataTahap
        }]
    });

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
            name: 'Count',
            data: dataBentuk
        }]
    });
</script>
@endsection