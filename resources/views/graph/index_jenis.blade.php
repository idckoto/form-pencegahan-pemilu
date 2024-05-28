@extends('layouts.opd.app')

@section('content')
<link href="{{ asset('app/css/hc.css')}}" rel="stylesheet">
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/highcharts-3d.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
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
                <form action="{{ url('/dashboard-jenis') }}" enctype="multipart/form-data" method="POST">
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
        <!-- /.row -->
    </div><!-- /.container-fluid -->
    <input type="hidden" name="date_start" id="date_start" value="{{ date('Y-m-d', strtotime($date_start)) }}">
    <input type="hidden" name="date_finish" id="date_finish" value="{{ date('Y-m-d', strtotime($date_finish)) }}">
</section>
<!-- /.content -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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

    var categories_jenis = <?php echo json_encode($categories_jenis) ?>;
    var count_jenis = <?php echo json_encode($count_jenis) ?>;
    var titleLabel = <?php echo json_encode($title) ?>;
    var date_start = document.getElementById("date_start").value;
    var date_finish = document.getElementById("date_finish").value;
    //alert(count_RI);
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
</script>
@endsection