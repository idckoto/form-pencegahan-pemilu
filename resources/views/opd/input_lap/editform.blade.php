@extends('layouts.opd.app')
@section('content')
    <style>
        .kbw-signature { width: 50%; height: 200px;}
        #sig canvas{
            width: 100% !important;
            height: auto;
            border: 2px solid red;
        }
        canvas {
            border: 1px solid #ccc;
            border-radius: 0.5rem;
            width: 100%;
            height: 400px;
        }
    </style>
    @php
        $jabatan=json_decode($form->jabatan);
    @endphp
<form action="/update-form" method="post" enctype="multipart/form-data">
    @csrf
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
            <center>
          <h1>FORM PENCEGAHAN</h1>

        </center>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
<section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">I.	Data Pengawas :</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                    <div class="form-group col-sm-12">
                        {{--  {{explode('.',explode("/", $form->no_form)[2])[0]}}  --}}
                        <label>Divisi </label>
                        <select name="petugas" class="custom-select rounded-0" readonly>
                            <option value="">({{$petugas->kd_petugas}}) {{$petugas->ket}}</option>
                        </select>
                    </div>
                    </div>
                    <div class="form-group">
                        <label>Nama Pelaksana Tugas </label>
                        @foreach (json_decode($form->namapt) as $no=>$nmpt)
                        <div class="input-group {{$no=='0' ? 'after-add-more':''}}">
                        <input type="hidden" class="form-control" name="id" placeholder="Nama Pelaksana Tugas" value="{{$id}}">
                        <input type="text" class="form-control" name="namapt[]" placeholder="Nama Pelaksana Tugas" value="{{$nmpt}}">
                        <input type="text" name="jabatan[]" class="form-control" placeholder="Jabatan Pelaksana Tugas" value="{{$jabatan[$no]}}">
                        <span class="input-group-append">
                              <button type="button" class="btn btn-{{$no=='0' ? 'success add-more':'danger remove'}}"><i class="icon ion-ios-{{$no=='0' ? 'plus':'trash'}}-outline"></i></button>
                        </span><hr>
                        </div>
                        @endforeach
                    </div>
                    <div class="form-group copy " style="display:none;">
                        <div class="input-group">
                            <input type="text" name="namapt[]" class="form-control " placeholder="Nama Pelaksana Tugas" >
                            <input type="text" name="jabatan[]" class="form-control" placeholder="Jabatan Pelaksana Tugas">
                            <span class="input-group-append">
                              <button type="button" class="btn btn-danger remove"><i class="icon ion-ios-trash-outline"></i></button>
                            </span>
                        </div>
                        
                    </div>
                    {{--  <div class="form-group">
                        <label>Jabatan</label>
                        <input type="text" class="form-control" name="jabatan" placeholder="Jabatan" value="{{$form->jabatan}}">
                    </div>  --}}
                    <div class="form-group">
                        <label>Nomor Surat Perintah Tugas</label>
                        <input type="text" class="form-control" name="nspt" placeholder="Nomor Surat Perintah Tugas" value="{{$form->nspt}}">
                    </div>
                    <div class="form-group">
                        <label>Tanda Tangan dibawah ini <code>*</code></label>
                        <div class="card-body">
                            <!-- canvas tanda tangan  -->
                            <canvas id="signature-pad" class="signature-pad" name="canvas"></canvas>
                            <textarea id="signature64" name="signed" style="display:none"></textarea>
                            <div style="float: right;">    
                                <!-- tombol hapus tanda tangan  -->
                                <button type="button" class="btn btn-danger" id="clear">
                                    <span class="fas fa-eraser"></span>
                                    Clear
                                </button>
                            </div>
                            <div style="float: left;">    
                                <!-- tombol hapus tanda tangan  -->
                                <button type="button" class="btn btn-info" id="open" data-id="{{ asset('ttd').'/'.$form->ttd }}">
                                    <span class="fa fa-eye"></span>
                                    Open
                                </button>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">II.	Kegiatan Pencegahan :</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Tahapan</label>
                        <select id="tahap" name="tahap" class="custom-select rounded-0">
                            <option value="">Pilih Tahapan</option>
                            <option {{$form->tahap == 'Tahapan' ? 'selected':''}} value="Tahapan">Tahapan</option>
                            <option {{$form->tahap == 'Non Tahapan' ? 'selected':''}} value="Non Tahapan">Non Tahapan</option>
                        </select>
                    </div>
                    <div class="form-group" id="tahaps" style="{{$form->tahap == "Tahapan" ? '':'display:none;'}}">
                        <select id="tahap_lain" name="tahaps" class="custom-select rounded-0 @error('tahaps') is-invalid @enderror">
                            <option value="">== Pilih Tahap Pencegahan ==</option>
                            @foreach ($tahapan as $thp)
                                <option {{$form->tahaps == $thp->id ? 'selected':''}} value="{{$thp->id}}">{{$thp->tahapan}}</option>
                            @endforeach
                        </select>
                        @error('tahaps')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    {{--  <div class="form-group" id="tahaps_non" style="{{$form->tahap == "Non Tahapan" ? '':'display:none;'}}" >
                        <select id="tahap_non" name="tahap_non" class="custom-select rounded-0 @error('tahap_non') is-invalid @enderror">
                            <option value="">== Pilih Non Tahap Pencegahan ==</option>
                            @foreach ($tahapannon as $thp_non)
                                <option {{$form->tahaps == $thp_non->id ? 'selected':''}} value="{{$thp_non->id}}">{{$thp_non->tahapan}}</option>
                            @endforeach
                        </select>
                        @error('tahap_non')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>  --}}
                    <div class="form-group" id="show_tahap" style="{{$form->tahaps == "0" ? '':'display:none;'}}" >
                        <input type="text" class="form-control @error('tahap_lainnya') is-invalid @enderror" id="tahap_kegiatan" value="{{ $form->tahap_lain }}" name="tahap_lainnya" placeholder="Kegiatan Lainnya">
                        @error('tahap_lainnya')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Bentuk Pencegahan{{$form->bentuk}}</label>
                        <select style="{{$form->tahap == "Tahapan" ? '':'display:none;'}}" id="bentuk" name="bentuk" class="custom-select rounded-0">
                            <option value="">== Pilih Betuk Pencegahan ==</option>
                            @foreach ($bentuk as $btk)
                                <option {{$form->bentuk == $btk->id ? 'selected':''}} value="{{$btk->id}}">{{$btk->bentuk}}</option>
                            @endforeach
                        </select>
                        <select style="{{$form->tahap == "Non Tahapan" ? '':'display:none;'}}" id="bentuknon" name="bentuknon" class="custom-select rounded-0 @error('bentuk') is-invalid @enderror">
                            <option value="">== Pilih Betuk Pencegahan ==</option>
                            @foreach ($bentuknon as $btknon)
                            <option {{$form->bentuk == $btknon->id ? 'selected':''}} value="{{$btknon->id}}">{{$btknon->bentuk}}</option>
                                {{--  <option value="{{$btknon->id}}">{{$btknon->bentuk}}</option>  --}}
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" id="show_bentuk" style="{{$form->bentuk == "0" ? '':'display:none;'}}" >
                        <input type="text" class="form-control @error('bentuk_lain') is-invalid @enderror" id="bentuk_lain" value="{{ $form->bentuk_lain }}" name="bentuk_lain" placeholder="Bentuk Lainnya">
                        @error('bentuk_lain')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Jenis <code>*</code></label>
                        <select id="jenis" name="jenis" class="custom-select rounded-0 @error('jenis') is-invalid @enderror">
                            <option value="">== Pilih Jenis Pencegahan ==</option>
                            @foreach ($jenis as $jns)
                                <option {{$form->jenis == $jns->id ? 'selected':''}} value="{{$jns->id}}">{{$jns->jenis}}</option>
                                {{--  <option value="{{$jns->id}}">{{$jns->jenis}}</option>  --}}
                            @endforeach
                        </select>
                        @error('jenis')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group" id="show_jenis" style="{{$form->jenis == "0" ? '':'display:none;'}}">
                        <input type="text" id="jenis_lain" class="form-control @error('jenis_lain') is-invalid @enderror" value="{{ $form->jenis_lain }}" name="jenis_lain" placeholder="Jenis Lainnya">
                        @error('jenis_lain')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Tujuan</label>
                        <input type="text" class="form-control @error('tujuan') is-invalid @enderror" value="{{ $form->tujuan }}" name="tujuan" placeholder="Tujuan">
                        @error('tujuan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Sasaran</label>
                        <input type="text" class="form-control @error('sasaran') is-invalid @enderror" value="{{ $form->sasaran }}" name="sasaran" placeholder="Sasaran">
                        @error('sasaran')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="text" class="form-control" name="tanggal" placeholder="Tanggal" value="{{$form->tanggal}}">
                    </div>
                    @if (isset($provinsi->id))
                     <div class="form-group">
                        <label class="form-label">
                           Provinsi
                        </label>
                   
                        <select id="select_province" name="provinsi" class="form-control select2">
                           
                            <option selected value="{{$provinsi->id ?? ''}}">{{$provinsi->provinsi ?? ''}}</option>
                           
                        </select>
                     </div>
                     @endif
                     @if (isset($kabupaten->id))
                     <div class="form-group">
                        <label class="form-label">
                           Kabupaten
                        </label>
                        <select id="select_regency" name="kabupaten" class="form-control select2">
                            <option selected value="{{$kabupaten->id}}">{{$kabupaten->kabupaten}}</option>
                        </select>
                     </div>
                     @endif
                     @if (isset($kecamatan->id))
                     <div class="form-group">
                        <label class="form-label">
                           Kecamatan
                        </label>
                        <select id="select_district" name="kecamatan" class="form-control select2">
                            <option selected value="{{$kecamatan->id}}">{{$kecamatan->kecamatan}}</option>
                        </select>
                     </div>
                     @endif
                     @if (isset($kelurahan->id))
                     <div class="form-group">
                        <label class="form-label">
                           Kelurahan
                        </label>
                        <select id="select_village" name="kelurahan" class="form-control select2">
                            <option selected value="{{$kelurahan->id}}">{{$kelurahan->kelurahan}}</option>
                        </select>
                     </div>
                     @endif
                     <div class="form-group">
                        <label>Tempat</label>
                        <input type="text" class="form-control" name="tempat" placeholder="Tempat" value="{{$form->tempat}}">
                    </div>

                    <div class="form-group">
                        <label>File</label>
                        <input type="file" name="files[]" multiple class="form-control @error('files') is-invalid @enderror">
                        <code>File doc,docx,pdf,jpg,jpeg,png Max 5MB</code>
                        @error('files')<div class="invalid-feedback" style="display: block">{{ $message }}</div>@enderror
                        <input type="hidden" name="Oldfile" class="form-control" value="{{$form->bukti}}">
                        <div class="card-footer bg-white">
            @foreach (json_decode($form->bukti) as $no=>$gambar)
              <ul class="mailbox-attachments d-flex align-items-stretch clearfix">
                <li>
                  <span class="mailbox-attachment-icon"><i class="fa fa-book"></i></span>
                  <div class="mailbox-attachment-info">
                    <a href="/dowload-bukti/{{$gambar}}" class="mailbox-attachment-name"><i class="fas fa-paperclip"></i> {{$gambar}}</a>
                        <span class="mailbox-attachment-size clearfix mt-1">
                          {{--  <span>1,245 KB</span>  --}}
                          <a href="/dowload-bukti/{{$gambar}}" class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt"></i></a>
                          <button type="button" class="btn btn-danger btn-sm float-left" id="hapus-data" data-id="{{$id}}" data-nama="{{$gambar}}"><i class="fas fa-trash"></i></button>
                        </span>
                  </div>
                </li>
            @endforeach
                </div>
                        
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">III.	Uraian Singkat Kegiatan Pencegahan :</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <textarea name="uraian" cols="110" rows="10" placeholder="Uraikan Kegiatan Pencegahan....">{{$form->uraian}}</textarea>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">IV.	Tindak Lanjut :</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <textarea name="tindaklanjut" cols="110" rows="10" placeholder="Berikan Tindak Lanjut....">{{$form->tindaklanjut}}</textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" id="submit" class="btn btn-info float-right"><i class="icon ion-ios-paperplane"></i> Simpan</button>
                </div>
            </div>
        </div>
      </div>
    </div>
</section>
</form>
@endsection
@push('scripts')
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js" defer></script>
    {{--  <script src="{{ asset('blackend/plugins/jquery/jqueryx.min.js')}}"></script>  --}}
    <script type="text/javascript">
        $(function() {
            if(document.getElementById('tahap').value == 'Tahapan'){
                document.getElementById('tahap_non').value = null
                document.getElementById('tahap_non').required = false
            }else{
                document.getElementById('tahap_lain').required = false
                document.getElementById('tahap_lain').value = null
            }
               var start = moment().subtract(29, 'days');
            var end = moment();
            $('input[name="tanggal"]').daterangepicker(
                {
                    locale: {
                      format: 'YYYY-MM-DD'
                    }
                }
            );
        });
        $(document).ready(function (){
            $("#bentuk").change(function() {
                if ($(this).val() == "0") {
                    $("#show_bentuk").show();
                    document.getElementById('bentuk_lain').required = true
                }else{
                    $("#show_bentuk").hide();
                    document.getElementById('bentuk_lain').required = false
                    document.getElementById('bentuk_lain').value = null
                }
            });
            $("#bentuknon").change(function() {
                if ($(this).val() == "0") {
                    $("#show_bentuk").show();
                    document.getElementById('bentuk_lain').required = true
                }else{
                    $("#show_bentuk").hide();
                    document.getElementById('bentuk_lain').required = false
                    document.getElementById('bentuk_lain').value = null
                }
            });
            $("#jenis").change(function() {
                if ($(this).val() == "0") {
                    $("#show_jenis").show();
                    document.getElementById('jenis_lain').required = true
                }else{
                    $("#show_jenis").hide();
                    document.getElementById('jenis_lain').required = false
                    document.getElementById('jenis_lain').value = null
                }
            });

            $("#tahap_lain").change(function() {
                if ($(this).val() == "0") {
                    $("#show_tahap").show();
                    document.getElementById('tahap_kegiatan').required = true
                }else{
                    $("#show_tahap").hide();
                    document.getElementById('tahap_kegiatan').required = false
                    document.getElementById('tahap_kegiatan').value = null
                }
            });
            $("#tahap_non").change(function() {
                if ($(this).val() == "0") {
                    $("#show_tahap").show();
                    document.getElementById('tahap_kegiatan').required = true
                }else{
                    $("#show_tahap").hide();
                    document.getElementById('tahap_kegiatan').required = false
                    document.getElementById('tahap_kegiatan').value = null
                }
            });
            if(document.getElementById("id_divisi").dataset.id!=""){
                if(document.getElementById("kab") == null){
                    $("#show_kab").hide();
                    $("#show_kec").hide();
                    $("#show_kel").hide();
                    document.getElementById('select_regency').required = false
                    document.getElementById('select_district').required = false
                    document.getElementById('select_village').required = false
                }else if(document.getElementById("kec") == null){
                    $("#show_kec").hide();
                    $("#show_kel").hide();
                    document.getElementById('select_district').required = false
                    document.getElementById('select_village').required = false
                }
            }
         });

         $("#tahap").change(function() {
                if ($(this).val() == "Tahapan") {
                    $("#tahaps").show();
                    $("#bentuk").show();
                    $("#bentuknon").hide();
                    $("#tahaps_non").hide();
                    $("#show_tahap").hide();
                    document.getElementById('tahap_lain').required = true
                    document.getElementById('bentuk').required = true
                    document.getElementById('bentuknon').value = null
                    document.getElementById('tahap_non').value = null
                    document.getElementById('tahap_non').required = false
                    document.getElementById('bentuknon').required = false
                }else if($(this).val() == "Non Tahapan"){
                    $("#tahaps").hide();
                    $("#bentuk").hide();
                    $("#tahaps_non").show();
                    $("#bentuknon").show();
                    $("#show_tahap").hide();
                    document.getElementById('tahap_lain').required = false
                    document.getElementById('bentuk').required = false
                    document.getElementById('bentuk').value = null
                    document.getElementById('tahap_lain').value = null
                    document.getElementById('tahap_non').required = true
                    document.getElementById('bentuknon').required = true
                }else{
                    $("#tahaps").hide();
                    $("#tahaps_non").hide();
                    $("#show_tahap").hide();
                    document.getElementById('tahap_lain').required = false
                    document.getElementById('tahap_lain').value = null
                    document.getElementById('tahap_non').required = false
                    document.getElementById('tahap_non').value = null
                }
            });
    </script>
        <script type="text/javascript">
        $(document).ready(function() {
          $(".add-more").click(function(){
              var html = $(".copy").html();
              $(".after-add-more").after(html);
          });

          // saat tombol remove dklik control group akan dihapus
          $("body").on("click",".remove",function(){
              $(this).parents(".input-group").remove();
          });
          $("body").on("click","#open",function(){
            const url = $(this).data('id');
            //console.log(url);
            Swal.fire({
            title: 'Tanda Tangan Ketua Pelaksana Petugas',
            //text: 'Modal with a custom image.',
            imageUrl: url,
            imageWidth: 400,
            imageHeight: 200,
            imageAlt: 'TTD',
            })
          });
        });
    </script>
    
    <script>
            // script di dalam ini akan dijalankan pertama kali saat dokumen dimuat
            document.addEventListener('DOMContentLoaded', function () {
                resizeCanvas();
            })
    
            //script ini berfungsi untuk menyesuaikan tanda tangan dengan ukuran canvas
            function resizeCanvas() {
                var ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext("2d").scale(ratio, ratio);
            }
    
    
            var canvas = document.getElementById('signature-pad');
    
            //warna dasar signaturepad
            var signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgb(255, 255, 255)'
            });
            //saat tombol clear diklik maka akan menghilangkan seluruh tanda tangan
            document.getElementById('clear').addEventListener('click', function () {
				var signature = signaturePad.toDataURL();
                    $("#signature64").val(signature);
                signaturePad.clear();
            });
            document.getElementById('submit').addEventListener('click', function () {
				var signature = signaturePad.toDataURL();
                    $("#signature64").val(signature);
            });
          
        </script>
    <script type="text/javascript">
     $("body").on("click","#hapus-data",function(){
        const id = $(this).data('id');
        const nama = $(this).data('nama');
        var token = $("meta[name='csrf-token']").attr("content");
        //console.log(nama+id);
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
                url: "{{ url('/hapus-bukti') }}",
                data: 	{
                "id": id,
                "nama": nama,
                "_token": token
                },
                type: 'DELETE',
                success: function (response) {
                    console.log(response);
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
        });
    </script>
    
@endpush
