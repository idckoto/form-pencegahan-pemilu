@extends('layouts.opd.app')
@section('content')
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
            <center>
          <h1>LIST FORM PENCEGAHAN</h1>
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
                    <div class="card-header">
                      <h3 class="card-title">
                        <a href="/input-form" class="btn btn-outline-primary btn-block"><i class="fa fa-plus"></i>
                        Tambah Form Pencegahan <b>
                         </b></a></h3>
                         
                    </div>
                    
                    <div class="card-body">
                        UserID : {{ Auth::user()->id }} 
                        <table id="example1" class="table table-small table-bordered table-striped" style="font: 12px Tahoma;">
                          <thead>
                          <tr>
                            <th>No</th>
                            <th>Nomor Form Pencegahan</th>
                            <!--<th>Tahap</th>-->
                            <th>Nama Tahapan</th>
                            <th>Nama Pelaksana</th>
                            <!--<th>Nomor Surat Perintah Tugas</th>-->
                            <th>Nama Pemilihan</th>
                            <th class="text-center">Aksi</th>
                          </tr>
                          </thead>
                          <tbody>
                            @foreach ($form as $no => $cegah)
                            <tr>
                            @php
                            $pecah=explode('/',$cegah->no_form);
                            $bulan=explode('-',explode(' ',$cegah->created_at)[0])[1];
                            @endphp
                                <td>{{++$no}}</td>
                                <td>{{$pecah[0].'/'.$pecah[1].'/'.$pecah[2].'/'.ltrim($bulan,'0').'/'.$pecah[4]}}</td>
                                <!-- <td>{{$cegah->tahap}}</td>-->
                                <td>
                                    @if ($cegah->tahap=="Tahapan")
                                        {{$cegah->tahapan->tahapan}}
                                    @else
                                        {{$cegah->tahap}}
                                    @endif
                                </td>
                                <td> 
                                @php
                                    $namaPtDecoded = json_decode($cegah->namapt);
                                @endphp
                            
                                @if (is_array($namaPtDecoded) && count($namaPtDecoded) > 0)
                                    {{ $namaPtDecoded[0] }}
                                @else
                                    {{-- Tampilkan pesan error atau nilai default --}}
                                @endif</td>
                                <td>{{$cegah->wp->nama_wp}}</td>
                                <td class="project-actions text-right">
                                        @php
                                            $id=Crypt::encryptString($cegah->id);
                                        @endphp
                                    <a class="btn btn-primary btn-xs" href="/cetak-form/{{$id}}">
                                        <i class="fas fa-folder">
                                        </i>
                                        View
                                    </a>
                                    @if ($cegah->stts=='0')
                                        <a class="btn btn-warning btn-xs" href="/edit-pencegah/{{$id}}">
                                            <i class="fas fa-pencil-alt">
                                            </i>
                                            Edit
                                        </a>
                                        <button onClick="Submit(this.id)" class="btn btn-info btn-xs" id="{{Crypt::encryptString($cegah->id)}}"><i class="fa fa-key"></i> Submit</button>
                                        <button onClick="Delete(this.id)" class="btn btn-danger btn-xs" id="{{Crypt::encryptString($cegah->id)}}"><i class="fas fa-trash"></i> Hapus</button>
                                    @endif
                                </td>
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
@push('scripts')
    <script type="text/javascript">
        function Delete(id)
        {
        var id = id;
        var token = $("meta[name='csrf-token']").attr("content");
        //console.log(id);
        Swal.fire({
            title: 'Yakin akan dihapus?',
            text: "Data yang telah dihapus tidak bisa dikembalikan.",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then(function(result) {
            // console.log(Swal.DismissReason);
            if (result.dismiss) {
            return true;
            } else {

            //ajax delete
            jQuery.ajax({
                url: "{{ url('/hapus-laporan') }}",
                data: 	{
                "id": id,
                "_token": token
                },
                type: 'DELETE',
                success: function (response) {
                if (response.status == "success") {
                    Swal.fire({
                    title: 'BERHASIL!',
                    text: 'DATA BERHASIL DIHAPUS!',
                    type: 'success',
                    timer: 1000,
                    showConfirmButton: false,
                    showCancelButton: false,
                    buttons: false,
                    }).then(function() {
                    location.reload();
                    });
                }else{
                    Swal.fire({
                    title: 'GAGAL!',
                    text: 'DATA GAGAL DIHAPUS!',
                    type: 'error',
                    timer: 1000,
                    showConfirmButton: false,
                    showCancelButton: false,
                    buttons: false,
                    }).then(function() {
                    location.reload();
                    });
                }
                }
            });
            }
        })
        }

        function Submit(id)
        {
        var id = id;
        var token = $("meta[name='csrf-token']").attr("content");
        //console.log(id);
        Swal.fire({
            title: 'Yakin akan disubmit?',
            text: "Data yang telah disubmit tidak bisa diedit ataupun dihapus.",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Submit'
        }).then(function(result) {
            // console.log(Swal.DismissReason);
            if (result.dismiss) {
            return true;
            } else {

            //ajax submit
            jQuery.ajax({
                url: "{{ url('/submit-laporan') }}",
                data: 	{
                "id": id,
                "_token": token
                },
                type: 'POST',
                success: function (response) {
                if (response.status == "success") {
                    Swal.fire({
                    title: 'BERHASIL!',
                    text: 'DATA BERHASIL DIHAPUS!',
                    type: 'success',
                    timer: 1000,
                    showConfirmButton: false,
                    showCancelButton: false,
                    buttons: false,
                    }).then(function() {
                    location.reload();
                    });
                }else{
                    Swal.fire({
                    title: 'GAGAL!',
                    text: 'DATA GAGAL DIHAPUS!',
                    type: 'error',
                    timer: 1000,
                    showConfirmButton: false,
                    showCancelButton: false,
                    buttons: false,
                    }).then(function() {
                    location.reload();
                    });
                }
                }
            });
            }
        })
        }
    </script>
	@endpush
