@extends('layouts.opd.app')
@section('content')
    <style>
        .kbw-signature { width: 50%; height: 200px;}
        	canvas {
                border: 1px solid #ccc;
                border-radius: 0.5rem;
                width: 100%;
                height: 400px;
            }
            .disabled {
  background: #ccc;
  cursor: not-allowed;
  border-width: 1px;
}
    </style>



<form action="/simpan-form" method="post" enctype="multipart/form-data">
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

                    <div class="">
                        <div class="form-group">
                            <label>Divisi  <code>*</code></label>
                            <div id="id_divisi" data-id="{{Auth::user()->id_divis}}"></div>
                            <select name="divisi" class="custom-select rounded-0 @error('divisi') is-invalid @enderror">
                            @if (Auth::user()->id_divisi<>'' || Auth::user()->id_divisi<>null)
                                @foreach ($petugas as $pts)
                                    <option value="{{$pts->kd_petugas}}">({{$pts->kd_petugas}}) {{$pts->ket}}</option>
                                @endforeach
                            @else
                                <option value="{{ old('divisi') }}">== Pilih Divisi ==</option>
                                @foreach ($petugas as $pts)
                                    <option value="{{$pts->kd_petugas}}">({{$pts->kd_petugas}}) {{$pts->ket}}</option>
                                @endforeach
                            @endif
                            </select>
                            @error('divisi')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Nama Pelaksana Tugas <code>*</code></label>
                        <div class="input-group after-add-more">
                            <input type="text" name="namapt[]" class="form-control" placeholder="Nama Ketua Pelaksana Tugas" required>
                            <input type="text" name="jabatan[]" class="form-control" placeholder="Jabatan Pelaksana Tugas" required>
                            <span class="input-group-append">
                              <button type="button" class="btn btn-success add-more"><i class="icon ion-ios-plus-outline"></i></button>
                            </span><hr>
                        </div>
                        
                    </div>
                    <div class="form-group copy " style="display:none;">
                        <div class="input-group">
                            <input type="text" name="namapt[]" class="form-control " placeholder="Nama Pelaksana Tugas">
                            <input type="text" name="jabatan[]" class="form-control" placeholder="Jabatan Pelaksana Tugas">
                            <span class="input-group-append">
                              <button type="button" class="btn btn-danger remove"><i class="icon ion-ios-trash-outline"></i></button>
                            </span>
                        </div>
                        
                    </div>
                    {{--  <div class="form-group">
                        <label>Jabatan <code>*</code></label>
                       <input type="text" class="form-control @error('jabatan') is-invalid @enderror" value="{{ old('jabatan') }}" name="jabatan" placeholder="Jabatan">
                        @error('jabatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>  --}}
                    <div class="form-group">
                        <label>Nomor Surat Perintah Tugas <code>Jika kosong isi dengan tanda -</code></label>
                        <input type="text" class="form-control @error('nspt') is-invalid @enderror" value="{{ old('nspt') }}" name="nspt" placeholder="Nomor Surat Perintah Tugas">
                        @error('nspt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Tanda Tangan dibawah ini <code>*</code></label>
                        <div class="card-body">
                            <!-- canvas tanda tangan  -->
                            <canvas id="signature-pad" class="signature-pad" name="canvas"></canvas>
                            <textarea id="signature64" name="signed" style="display:none"></textarea>
                            <!-- tombol submit  -->
                            {{--  <div style="float: left;">
                                <button id="btn-submit" class="btn btn-primary">
                                    Submit
                                </button>
                            </div>  --}}
    
                        <div style="float: right;">    
                            <!-- tombol hapus tanda tangan  -->
                            <button type="button" class="btn btn-danger" id="clear">
                                <span class="fas fa-eraser"></span>
                                Clear
                            </button>
                        </div>
                    </div>
                        {{--  <div id="sig"></div><br/>
                        <span class="btn btn-danger btn-sm" id="clear">Ulangi</span>
                        <textarea id="signature64" name="signed" style="display:none"></textarea>  --}}
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
                            <label>Nama Event Pemilihan  <code>*</code></label>
                            <select name="wp_id" class="custom-select rounded-0 @error('wp_id') is-invalid @enderror">
                            <option value="">== Pilih Nama Event ==</option>
                            @foreach ($twp as $wp)
                                <option value="{{$wp->id}}">{{$wp->nama_wp}}</option>
                            @endforeach
                            </select>
                            @error('wp_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    <div class="form-group">
                        <label>Tahapan <code>*</code></label>
                        <select id="tahap" name="tahap" class="custom-select rounded-0 @error('tahap') is-invalid @enderror">
                            <option value="">Pilih Tahapan</option>
                            <option value="Tahapan">Tahapan</option>
                            <option value="Non Tahapan">Non Tahapan</option>
                        </select>
                        @error('tahap')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group" id="tahaps" style="display:none;" >
                        <select id="tahap_lain" name="tahaps" class="custom-select rounded-0 @error('tahaps') is-invalid @enderror">
                            <option value="">== Pilih Tahap Pencegahan ==</option>
                            @foreach ($tahapan as $thp)
                                <option value="{{$thp->id}}">{{$thp->tahapan}}</option>
                            @endforeach
                        </select>
                        @error('tahaps')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    {{--  <div class="form-group" id="tahaps_non" style="display:none;" >
                        <select id="tahap_non" name="tahap_non" class="custom-select rounded-0 @error('tahap_non') is-invalid @enderror">
                            <option value="">== Pilih Tahap Pencegahan ==</option>
                            @foreach ($tahapannon as $thp_non)
                                <option value="{{$thp_non->id}}">{{$thp_non->tahapan}}</option>
                            @endforeach
                        </select>
                        @error('tahap_non')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>  --}}
                    <div class="form-group" id="show_tahap" style="display:none;" >
                        <input type="text" class="form-control @error('tahap_lainnya') is-invalid @enderror" id="tahap_kegiatan" value="{{ old('tahap_lainnya') }}" name="tahap_lainnya" placeholder="Kegiatan Lainnya">
                        @error('tahap_lainnya')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Bentuk Pencegahan <code>*</code></label>
                        <select style="display:none;" id="bentuk" name="bentuk" class="custom-select rounded-0 @error('bentuk') is-invalid @enderror">
                            <option value="">== Pilih Betuk Pencegahan ==</option>
                            @foreach ($bentuk as $btk)
                                <option value="{{$btk->id}}">{{$btk->bentuk}}</option>
                            @endforeach
                        </select>
                        <select style="display:none;" id="bentuknon" name="bentuknon" class="custom-select rounded-0 @error('bentuk') is-invalid @enderror">
                            <option value="">== Pilih Betuk Pencegahan ==</option>
                            @foreach ($bentuknon as $btknon)
                                <option value="{{$btknon->id}}">{{$btknon->bentuk}}</option>
                            @endforeach
                        </select>
                        @error('bentuk')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group" id="show_bentuk" style="display:none;" >
                        <input type="text" class="form-control @error('bentuk_lain') is-invalid @enderror" id="bentuk_lain" value="{{ old('bentuk_lain') }}" name="bentuk_lain" placeholder="Bentuk Lainnya">
                        @error('bentuk_lain')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Jenis <code>*</code></label>
                        <select id="jenis" name="jenis" class="custom-select rounded-0 @error('jenis') is-invalid @enderror">
                            <option value="">== Pilih Jenis Pencegahan ==</option>
                            @foreach ($jenis as $jns)
                                <option value="{{$jns->id}}">{{$jns->jenis}}</option>
                            @endforeach
                        </select>
                        @error('jenis')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group" id="show_jenis" style="display:none;">
                        <input type="text" id="jenis_lain" class="form-control @error('jenis_lain') is-invalid @enderror" value="{{ old('jenis_lain') }}" name="jenis_lain" placeholder="Jenis Lainnya">
                        @error('jenis_lain')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Tujuan <code>*</code></label>
                        <input type="text" class="form-control @error('tujuan') is-invalid @enderror" value="{{ old('tujuan') }}" name="tujuan" placeholder="Tujuan">
                        @error('tujuan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Sasaran <code>*</code></label>
                        <input type="text" class="form-control @error('sasaran') is-invalid @enderror" value="{{ old('sasaran') }}" name="sasaran" placeholder="Sasaran">
                        @error('sasaran')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Tanggal <code>*</code></label>
                        <input type="text" class="form-control @error('tanggal') is-invalid @enderror" name="tanggal"/>
                        @error('tanggal')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        {{--  <input type="date" class="form-control" name="tanggal" placeholder="Tanggal">  --}}
                    </div>
                     <div class="mb-3" id="show_prov" style="display:none;">
                        <label class="form-label">
                           Provinsi <code>*</code>
                        </label>
                        <select id="select_province" name="provinsi" class="form-control select2 @error('provinsi') is-invalid @enderror" >
                        @if (isset($provinsi))
                            <option selected id="prov" value="{{$provinsi->id}}-{{$provinsi->sni}}">{{$provinsi->provinsi}}</option>
                        @endif
                        </select>
                        @error('provinsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                     </div>
                     <div class="mb-3" id="show_kab" >
                        <label class="form-label">
                           Kabupaten <code>*</code>
                        </label>
                        <select id="select_regency" name="kabupaten" class="form-control select2 @error('kabupaten') is-invalid @enderror">
                        @if (isset($kabupaten))
                            <option selected id="kab" value="{{$kabupaten->id}}-{{$kabupaten->sni}}">{{$kabupaten->kabupaten}}</option>
                        @endif
                        </select>
                        @error('kabupaten')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                     </div>
                     <div class="mb-3" id="show_kec">
                        <label class="form-label">
                           Kecamatan <code>*</code>
                        </label>
                        <select id="select_district" name="kecamatan" class="form-control select2 @error('kecamatan') is-invalid @enderror">
                        @if (isset($kecamatan))
                            <option selected id="kec" value="{{$kecamatan->id}}">{{$kecamatan->kecamatan}}</option>
                        @endif
                        </select>
                        @error('kecamatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                     </div>
                     <div class="mb-3" id="show_kel" style="display:none;">
                        <label class="form-label">
                           Kelurahan <code>*</code>
                        </label>
                        <select id="select_village" name="kelurahan" class="form-control select2 @error('kelurahan') is-invalid @enderror">
                        </select>
                        @error('kelurahan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                     </div>
                     <div class="form-group">
                        <label>Tempat <code>*</code></label>
                        <input type="text" class="form-control @error('tempat') is-invalid @enderror" name="tempat" placeholder="Tempat">
                        @error('tempat')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>File <code>*</code></label>
                        <input type="file" name="files[]" multiple class="form-control @error('files') is-invalid @enderror">
                        <code>File doc,docx,pdf,jpg,jpeg,png Max 2MB</code>
                        @error('files')<div class="invalid-feedback" style="display: block">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Repository <code>Optional</code></label>
                        <input type="text" name="repo" class="form-control" placeholder="https://drive.google.com/file/d/1Pf49XT-asdyruwujdhyeJet-U/view?usp=share_link">
                        <code>Jika file bukti terlalu besar silahkan menggunakan repository pribadi</code>
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
                    <h3 class="card-title">III.	Uraian Singkat Kegiatan Pencegahan : <code>*</code></h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <textarea name="uraian" cols="110" rows="10" class="form-control @error('uraian') is-invalid @enderror summernote" placeholder="Uraikan Kegiatan Pencegahan...."></textarea>
                        @error('uraian')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                    <h3 class="card-title">IV.	Tindak Lanjut : <code>*</code></h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <textarea name="tindaklanjut" class="form-control @error('tindaklanjut') is-invalid @enderror summernote" cols="110" rows="10" placeholder="Berikan Tindak Lanjut...."></textarea>
                        @error('tindaklanjut')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
        /*$(document).ready(function() {
            $('.summernote').summernote({
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']]
            ]
            });
        });*/
        $(function() {
            //console.log(document.getElementById("id_divisi").dataset.id + "azis");
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
                //console.log(document.getElementById("kab"));

            var start = moment();
            var end = moment().add(29, 'days');
            $('input[name="tanggal"]').daterangepicker(
                {
                    locale: {
                      format: 'YYYY-MM-DD'
                    },
                    startDate: start,
                    endDate: end
                }
            );
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
        });
    </script>
    <script>
        $(document).ready(function (){
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

            //  select province:start
            if(document.getElementById('prov')==null){
               $('#select_province').select2({
                  allowClear: true,
                  ajax: {
                     url: "{{ route('provinces.select') }}",
                     dataType: 'json',
                     delay: 250,
                     processResults: function(data) {
                        return {
                           results: $.map(data, function(item) {
                              return {
                                 text: item.provinsi,
                                 id: item.id+'-'+item.sni,
                              }
                           })
                        };
                     }
                  }
               });
            }else{

            }
            //  select province:end
            //  Event on change select province:start
            if(document.getElementById('prov')==null){
                $("#show_kab").hide();
                $("#show_kec").hide();

                //console.log("asdfasf");
                $('#select_province').change(function() {
                  //clear select
                  $('#select_regency').empty();
                  $("#select_district").empty();
                  $("#select_village").empty();
                  //set id
                  let provinceID = $(this).val();
                  if (provinceID) {
                     $('#select_regency').select2({
                        allowClear: true,
                        ajax: {
                           url: "{{ route('regencies.select') }}?provinceID=" + provinceID,
                           dataType: 'json',
                           delay: 250,
                           processResults: function(data) {
                              return {
                                 results: $.map(data, function(item) {
                                    return {
                                       text: item.kabupaten,
                                       id: item.id,
                                    }
                                 })
                              };
                            //console.log(data);
                           }
                        }

                     });

                  } else {
                     $('#select_regency').empty();
                     $("#select_district").empty();
                     $("#select_village").empty();
                  }
                });
            }else{
                let provinceID = document.getElementById('prov').value;
                    $("#show_prov").show();
                 if(document.getElementById('kab')==null){
                    $("#show_kab").hide();
                $("#show_kec").hide();
                    var urlRe = "{{ route('regencies.select') }}?provinceID=" + provinceID;
                  }
                  if (provinceID) {
                     $('#select_regency').select2({
                        allowClear: true,
                        ajax: {
                           url: urlRe,
                           dataType: 'json',
                           delay: 250,
                           processResults: function(data) {
                              return {
                                 results: $.map(data, function(item) {
                                    return {
                                       text: item.kabupaten,
                                       id: item.id,
                                    }
                                 })
                              };
                            //console.log(data);
                           }
                        }

                     });

                  } else {
                     $('#select_regency').empty();
                     $("#select_district").empty();
                     $("#select_village").empty();
                  }
            }
               //  Event on change select province:end

               //  Event on change select regency:start
               //console.log(document.getElementById('kab').value);
               
               if(document.getElementById('kab')==null){
               $('#select_regency').change(function() {
                  //clear select
                  $("#select_district").empty();
                  $("#select_village").empty();
                  //set id
                  let regencyID = $(this).val();
                  if (regencyID) {
                     $('#select_district').select2({
                        allowClear: true,
                        ajax: {
                           url: "{{ route('districts.select') }}?regencyID=" + regencyID,
                           dataType: 'json',
                           delay: 250,
                           processResults: function(data) {
                              return {
                                 results: $.map(data, function(item) {
                                    return {
                                       text: item.kecamatan,
                                       id: item.id
                                    }
                                 })
                              };
                           }
                        }
                     });
                  } else {
                     $("#select_district").empty();
                     $("#select_village").empty();
                  }
               });
               }else{
                  let regencyID = document.getElementById('kab').value;
                 //clear select
                    $("#show_kab").show();
                  if(document.getElementById('kec')==null){
                    $("#show_kec").hide();
                    var urlRe = "{{ route('districts.select') }}?regencyID=" + regencyID;
                  }
                  $("#select_village").empty();
                  if (regencyID) {
                     $('#select_district').select2({
                        allowClear: true,
                        ajax: {
                           url: urlRe,
                           dataType: 'json',
                           delay: 250,
                           processResults: function(data) {
                              return {
                                 results: $.map(data, function(item) {
                                    return {
                                       text: item.kecamatan,
                                       id: item.id
                                    }
                                 })
                              };
                           }
                        }
                     });
                  } else {
                     $("#select_district").empty();
                     $("#select_village").empty();
                  }
               }
               //  Event on change select regency:end

               //  Event on change select district:Start
               if(document.getElementById('kec')==null){
               $('#select_district').change(function() {
                  //clear select
                  $("#select_village").empty();
                  //set id
                  let districtID = $(this).val();
                  if (districtID) {
                     $('#select_village').select2({
                        allowClear: true,
                        ajax: {
                           url: "{{ route('villages.select') }}?districtID=" + districtID,
                           dataType: 'json',
                           delay: 250,
                           processResults: function(data) {
                              return {
                                 results: $.map(data, function(item) {
                                    return {
                                       text: item.kelurahan,
                                       id: item.id
                                    }
                                 })
                              };
                           }
                        }
                     });
                  }
               });
               }else{
                //clear select
                  $("#select_village").empty();
                  //set id
                    $("#show_kec").show();
                //document.getElementById("select_district").disabled = true;
                  let districtID = document.getElementById('kec').value;
                  if (districtID) {
                     $('#select_village').select2({
                        allowClear: true,
                        ajax: {
                           url: "{{ route('villages.select') }}?districtID=" + districtID,
                           dataType: 'json',
                           delay: 250,
                           processResults: function(data) {
                              return {
                                 results: $.map(data, function(item) {
                                    return {
                                       text: item.kelurahan,
                                       id: item.id
                                    }
                                 })
                              };
                           }
                        }
                     });
                  }
               }
               //  Event on change select district:End

               // EVENT ON CLEAR
               $('#select_province').on('select2:clear', function(e) {
                  $("#select_regency").select2();
                  $("#select_district").select2();
                  $("#select_village").select2();
               });

               $('#select_regency').on('select2:clear', function(e) {
                  $("#select_district").select2();
                  $("#select_village").select2();
               });

               $('#select_district').on('select2:clear', function(e) {
                  $("#select_village").select2();
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
@endpush
