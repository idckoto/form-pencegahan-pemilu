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
                      {{-- <h3 class="card-title">
                        <a href="/input-form" class="btn btn-outline-primary btn-block"><i class="fa fa-plus"></i>
                        Tambah Form Pencegahan <b>
                         </b></a></h3> --}}
                    </div>
                    <div class="card-body">
                        <table id="formcegahTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Nomor Form Pencegahan</th>
                                    <!--<th>Tahap</th>-->
                                    <th>Nama Tahapan</th>
                                    <th>Nama Pelaksana</th>
                                    <!--<th>Nomor Surat Perintah Tugas / NSPT</th>-->
                                    <th>Nama Pemilihan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
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
    <!-- Contoh Impor jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
       $(document).ready(function() {
    $('#formcegahTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('formcegah.data') }}",
            type: 'GET',
            dataSrc: function(json) {
                // Log seluruh response JSON ke konsol
                console.log(json);

                // Anda juga bisa log array data saja, jika ingin lebih spesifik
                // console.log(json.data);

                // Jangan lupa untuk mengembalikan data agar ditampilkan di tabel
                return json.data;
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'no_form', name: 'no_form' },
            { data: 'tahap', name: 'tahap' },
            { data: 'namapt', name: 'namapt' },
            { data: 'wp_id', name: 'wp_id' },
            { data: 'cetak', name: 'cetak', orderable: false, searchable: false },
        ]
    });
});

        </script>
        
	@endpush
