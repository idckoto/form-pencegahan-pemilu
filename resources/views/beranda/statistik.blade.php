@extends('layouts.opd.app')
@section('content')
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Data Pemilu dan Pilkada</h1>
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
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-info elevation-1"><i class="ion ion-pie-graph"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Data Tahapan & Non Tahapan</span>
                <span class="info-box-number">
                  <div>Lihat selengkapnya</div>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="ion ion-pie-graph"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Tahapan Berdasarkan Bentuk</span>
                <span class="info-box-number"><div>Lihat selengkapnya</div></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->

          <!-- fix for small devices only -->
          <div class="clearfix hidden-md-up"></div>

          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="ion ion-pie-graph"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Data Sebaran (Bentuk)</span>
                <span class="info-box-number"><div>Lihat selengkapnya</div></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="ion ion-pie-graph"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Data Sebaran (Jenis)</span>
                <span class="info-box-number"><div>Lihat selengkapnya</div></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
        </div>
        
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                    <div class="card-header border-transparent">
                      <h3 class="card-title text-bold">Wilayah Pemilihan</h3>
                      <div class="card-tools">
                      </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                      <div class="table-responsive">
                        <table class="table m-0">
                          <thead>
                          <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            <th>Wilayah</th>
                            <th>Grafik</th>
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
                              <a class="badge badge-success" href="/statistik-show/{{$id}}">Lihat Grafik</a>
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
                
              </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>
    </section>

@endsection
