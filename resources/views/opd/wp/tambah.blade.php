@extends('layouts.opd.app')

@section('content')
<form action="/simpan-wp" method="post">
    @csrf
<section class="content mt-5">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Form Tambah Nama Pemilihan Berdasarkan Wilayah</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                            <label>Kategori Pemilihan  <code>*</code></label>
                            <select id="kp_id" name="kp_id" class="custom-select rounded-0 @error('kp_id') is-invalid @enderror">
                            <option value="">== Pilih ==</option>
                            @foreach ($tkp as $kp)
                                <option value="{{$kp->id}}">{{$kp->nama_kp}}</option>
                            @endforeach
                            </select>
                            @error('kp_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                    </div>
                    <div class="form-group">
                            <label>Propinsi Pemilihan  <code>*</code></label>
                            <select id="kdpro"  name="kdpro" class="custom-select rounded-0 @error('kdpro') is-invalid @enderror">
                            <option value="">== Pilih ==</option>
                            @foreach ($propinsi as $prov)
                                <option value="{{$prov->id}}">{{$prov->provinsi}}</option>
                            @endforeach
                            </select>
                            @error('kdpro')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                    </div>
                    <div class="form-group">
                            <label>Kabupaten/Kota Pemilihan  <code>*</code></label>
                            <select id="kdkab"  name="kdkab" class="custom-select rounded-0 @error('kdkab') is-invalid @enderror" readonly>
                                <option value="">== Pilih ==</option>
                            </select>
                            @error('kdkab')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                    </div>
                    <div class="form-group">
                        <label>Nama Pemilihan <code>*</code></label>
                        <input type="text" class="form-control @error('nama_wp') is-invalid @enderror" name="nama_wp" placeholder="Input disini" value="{{ old('nama_wp') }}">
                        @error('nama_wp')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small>Misal: Pilkada SUMBAR 2024</small>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-info float-right"><i class="icon ion-ios-paperplane"></i> Simpan</button>
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
<script>
    $(document).ready(function (){
        
        $("#kp_id").change(function() {
            
            var kp_id = $('#kp_id').val();
            //alert(kp_id);

            if ( kp_id === '1' ) { //nasional
                $('#kdpro').attr('disabled', true).siblings().removeAttr('disabled');  
                $('#kdkab').attr('disabled', true).siblings().removeAttr('disabled');  
            } else if ( kp_id === '2' ) { //provinsi
                $('#kdpro').attr('disabled', false);  
                $('#kdkab').attr('disabled', true);  
            } else if ( kp_id === '3' ) { //kabupaten/kota
                $('#kdpro').attr('disabled', false);  
                $('#kdkab').attr('disabled', false);  
            } else if ( kp_id === '4' ) { //kota
                $('#kdpro').attr('disabled', false);  
                $('#kdkab').attr('disabled', false);  
            } else {

            }
        });

        $("#kdpro").change(function() {
            var kdpro = $('#kdpro').val();
            $('#kdkab').empty();
            //alert(kdpro);
            $.ajax({
                url: "{{ route('regencies.select') }}?provinceID=" + kdpro,
                type: "GET",
                dataType: 'json',
                success: function(data) {
                    //var json_obj = jQuery.parseJSON(data);
                    console.log(data);
                    $('#kdkab').append('<option value="00"> Pilih </option>');
                    data.forEach(element => {
                        $('#kdkab').append('<option value="'+element.id+'"> '+element.kabupaten+'</option>');
                    }); 
                },
                error: function (xhr, ajaxOptions, thrownError){
                    console.log(xhr.statusText);
                    console.log(thrownError);
                }
                

            });
        });

    });
</script>
@endpush