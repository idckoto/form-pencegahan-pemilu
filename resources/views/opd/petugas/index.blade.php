@extends('layouts.opd.app')
@section('content')
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
            <center>
          <h1>LIST DIVISI</h1>
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
                        @if (Auth::user()->id_admin == '0' or Auth::user()->id_admin == '1') 
                            <a href="/tambah-petugas" class="btn btn-outline-primary btn-block"><i class="fa fa-plus"></i> Tambah Divisi</a>
                         @endif
                        </h3>
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                          <thead>
                          <tr>
                            <th>No</th>
                            <th>Kode Divisi</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                          </tr>
                          </thead>
                          <tbody>
                            @foreach ($petugas as $no => $ptg)
                            <tr>
                                <td>{{++$no}}</td>
                                <td>{{$ptg->kd_petugas}}</td>
                                <td>{{$ptg->ket}}</td>
                                <td>
                                 @if (Auth::user()->id_admin == '0' or Auth::user()->id_admin == '1')
                                    <div class="timeline-footer">
                                        @php
                                            $id=Crypt::encryptString($ptg->id);
                                        @endphp
                                        <a href="/edit-petugas/{{$id}}"class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
                                        <button onClick="Delete(this.id)" class="btn btn-danger btn-sm" id="{{$ptg->id}}"><i class="fas fa-trash"></i> Hapus</button>
                                      </div>
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
        url: "{{ url('/hapus-petugas') }}",
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
			</script>
	@endpush
