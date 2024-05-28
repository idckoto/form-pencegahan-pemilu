@extends('layouts.opd.app')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <center>
                        <h1>Detail Bentuk Pencegahan Nasional</h1>
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
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Bentuk Pencegahan</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bentuk as $no => $btk)
                                            <tr>
                                                <td>{{ ++$no }}</td>
                                                <td>{{ $btk->bentuk }}</td>
                                                <td>
                                                    <div class="timeline-footer">
                                                        @php
                                                            $id = Crypt::encryptString($btk->id);
                                                        @endphp
                                                        @isset($btk->total)
                                                            {{ $btk->total }} data, terdiri dari
                                                            <span class="badge-md badge bg-primary" onClick="Detail(this.id)"
                                                                id="{{ $btk->id }}" data-bentuk="{{ $btk->bentuk }}"
                                                                data-toggle="modal" data-target="#modal-lg"
                                                                style="cursor: pointer">
                                                                {{ $btk->total_provinsi }} provinsi</span>
                                                        @else
                                                            0
                                                        @endisset
                                                    </div>
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

        {{--  Modal Detail  --}}
        <div class="modal fade" id="modal-lg">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="bentukPencegahan">Large Modal</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table id="example" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Provinsi</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody id="records_table">
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        {{--  Modal Detail Kabupaten --}}
        <div class="modal fade" id="modal-lg-kab">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="detailKab">Large Modal</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kabupaten</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody id="records_tableKab">
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        {{--  Modal Detail Kecamatan --}}
        <div class="modal fade" id="modal-lg-kec">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="detailKec">Large Modal</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kecamatan</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody id="records_tableKec">
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        {{--  Modal Detail Kelurahan --}}
        <div class="modal fade" id="modal-lg-kel">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="detailKel">Large Modal</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kelurahan</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody id="records_tableKel">
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script type="text/javascript">
        function Detail(id) {
            var id = id;
            let bentuk = $('#' + id).attr("data-bentuk");

            $("#bentukPencegahan").text(bentuk + " Tingkat Nasional");
            $("#records_table").empty();
            //ajax detail
            jQuery.ajax({
                processing: true,
                serverSide: true,
                url: "{{ url('/ajax-bentuk-prov') }}",
                data: {
                    "id": id,
                },
                type: 'GET',
                success: function(response) {
                    var trHTML = '';
                    var no = 1;
                    $.each(response.data, function(index, value) {
                        trHTML +=
                            '<tr><td>' + no +
                            '</td><td>' + value.provinsi +
                            '</td><td>' + value.total +
                            ' data, terdiri dari  <span class="badge-md badge bg-primary" onClick="DetailKab(this.id)"id=' +
                            id + "-" + value.id_provinsi +
                            ' data-toggle="modal" data-target="#modal-lg-kab"style="cursor: pointer">' +
                            value.totalKab + ' kabupaten/kota</span>'
                        '</td></tr>';
                        no++
                    });
                    $('#records_table').append(trHTML);
                }
            });
        }

        function DetailKab(id) {
            const myArray = id.split("-");
            var id = myArray[0];
            var id_provinsi = myArray[1];
            $("#records_tableKab").empty();
            //ajax detail
            jQuery.ajax({
                processing: true,
                serverSide: true,
                url: "{{ url('/ajax-bentuk-kab') }}",
                data: {
                    "id": id,
                    "id_provinsi": id_provinsi,
                },
                type: 'GET',
                success: function(response) {
                    $("#detailKab").text("Provinsi " + response.data[0].provinsi);
                    var trHTML = '';
                    var no = 1;
                    $.each(response.data, function(index, value) {
                        trHTML +=
                            '<tr><td>' + no +
                            '</td><td>' + value.kabupaten +
                            '</td><td>' + value.total +
                            ' data, terdiri dari  <span class="badge-md badge bg-primary" onClick="DetailKec(this.id)"id=' +
                            id + "-" + value.id_provinsi + "-" + value.id_kabupaten +
                            ' data-toggle="modal" data-target="#modal-lg-kec"style="cursor: pointer">' +
                            value.totalKec + ' kecamatan</span>'
                        '</td></tr>';
                        no++
                    });
                    $('#records_tableKab').append(trHTML);
                    {{--  console.log(trHTML)  --}}
                }
            });
        }

        function DetailKec(id) {
            const myArray = id.split("-");
            var id = myArray[0];
            var id_provinsi = myArray[1];
            var id_kabupaten = myArray[2];
            $("#records_tableKec").empty();
            //ajax detail
            jQuery.ajax({
                processing: true,
                serverSide: true,
                url: "{{ url('/ajax-bentuk-kec') }}",
                data: {
                    "id": id,
                    "id_provinsi": id_provinsi,
                    "id_kabupaten": id_kabupaten,
                },
                type: 'GET',
                success: function(response) {
                    $("#detailKec").text("Kabupaten " + response.data[0].kabupaten);
                    var trHTML = '';
                    var no = 1;
                    $.each(response.data, function(index, value) {
                        trHTML +=
                            '<tr><td>' + no +
                            '</td><td>' + value.kecamatan +
                            '</td><td>' + value.total +
                            ' data, terdiri dari  <span class="badge-md badge bg-primary" onClick="DetailKel(this.id)"id=' +
                            id + "-" + value.id_provinsi + "-" + value.id_kabupaten + "-" + value
                            .id_kecamatan +
                            ' data-toggle="modal" data-target="#modal-lg-kel"style="cursor: pointer">' +
                            value.totalKel + ' kelurahan</span>'
                        '</td></tr>';
                        no++
                    });
                    $('#records_tableKec').append(trHTML);
                    {{--  console.log(trHTML)  --}}
                }
            });
        }

        function DetailKel(id) {
            const myArray = id.split("-");
            var id = myArray[0];
            var id_provinsi = myArray[1];
            var id_kabupaten = myArray[2];
            var id_kecamatan = myArray[3];
            $("#records_tableKel").empty();
            //ajax detail
            jQuery.ajax({
                processing: true,
                serverSide: true,
                url: "{{ url('/ajax-bentuk-kel') }}",
                data: {
                    "id": id,
                    "id_provinsi": id_provinsi,
                    "id_kabupaten": id_kabupaten,
                    "id_kecamatan": id_kecamatan,
                },
                type: 'GET',
                success: function(response) {
                    $("#detailKel").text("Kecamatan " + response.data[0].kecamatan);
                    var trHTML = '';
                    var no = 1;
                    $.each(response.data, function(index, value) {
                        trHTML +=
                            '<tr><td>' + no +
                            '</td><td>' + value.kelurahan +
                            '</td><td>' + value.total +
                            ' data'
                        '</td></tr>';
                        no++
                    });
                    $('#records_tableKel').append(trHTML);
                    {{--  console.log(trHTML)  --}}
                }
            });
        }
    </script>
@endpush
