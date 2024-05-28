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
                                {{ 'Data Sebaran Form Pencegahan' }}
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
    </div><!-- /.container-fluid -->
    {{-- <input type="hidden" name="date_start" id="date_start" value="{{ date('d-m-Y', strtotime($date_start)) }}">
    <input type="hidden" name="date_finish" id="date_finish" value="{{ date('d-m-Y', strtotime($date_finish)) }}"> --}}
</section>
<!-- /.content -->

<script>
    var categories = <?php echo json_encode($categories) ?>;
    var identifikasi_kerawananCount = <?php echo json_encode($identifikasi_kerawananCount, JSON_NUMERIC_CHECK) ?>;
    var pendidikanCount = <?php echo json_encode($pendidikanCount, JSON_NUMERIC_CHECK) ?>;
    var partisipasiCount = <?php echo json_encode($partisipasiCount, JSON_NUMERIC_CHECK) ?>;
    var kerjasamaCount = <?php echo json_encode($kerjasamaCount, JSON_NUMERIC_CHECK) ?>;
    var imbauanCount = <?php echo json_encode($imbauanCount, JSON_NUMERIC_CHECK) ?>;
    var kegiatanlainCount = <?php echo json_encode($kegiatanlainCount, JSON_NUMERIC_CHECK) ?>;
    var publikasiCount = <?php echo json_encode($publikasiCount, JSON_NUMERIC_CHECK) ?>;
    var titleLabel = <?php echo json_encode($title) ?>;
    //alert(titleLabel);

    Highcharts.chart('container', {
        title: {
            text: '',
            align: 'left'
        },

        subtitle: {
            text: 'Data Sebaran Form Pencegahan'+ titleLabel,
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
                allowPointSelect: true
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
                name: 'Imbauan',
                data: imbauanCount
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
</script>
@endsection