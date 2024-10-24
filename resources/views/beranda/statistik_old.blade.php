@extends('layouts.opd.app')
@section('content')
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">STATISTIK DATA</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/welcome">Beranda</a></li>
              <li class="breadcrumb-item active">Statistik</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

  <section class="content">
    <div class="container-fluid">
        
        <div class="row">
          <div class="col-md-8">
            <div class="card">
              <!--
                <div class="card-header border-default">
                  <h3 class="card-title text-bold">Wilayah Pemilihan</h3>
                  <div class="card-tools">
                  </div>
                </div> --> 
                <!-- /.card-header -->
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                      <thead>
                      <tr>
                        <th>No.</th>
                        <th>Nama Pemilihan</th>
                        <th>Wilayah</th>
                        <th>Lihat Data</th>
                      </tr>
                      </thead>
                      <tbody>
                      @php 
                          $no = 1;
                      @endphp 
                      @foreach ($twp as $row)
                      <tr>
                        <td>{{$no}}</td>
                        <td>{{$row->nama_wp}}</td>
                        <td>{{$row->propinsi->provinsi}}</td>
                      @php 
                          $no = $no+1;
                          $id=Crypt::encryptString($row->id);
                      @endphp 
                        <td>
                          <a class="badge badge-success" href="/statistik-pemilihan/{{$id}}">Lihat Data</a>
                        </td>
                      </tr>

                      @endforeach

                      </tbody>
                    </table>
                  </div>
                  <!-- /.table-responsive -->
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">

                  <!--<a href="javascript:void(0)" class="btn btn-sm btn-secondary float-right">View All</a>-->
                </div>
                <!-- /.card-footer -->
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
    </div><!-- /.container-fluid -->
       
  </section>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function () {

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
});
</script>
@endsection
