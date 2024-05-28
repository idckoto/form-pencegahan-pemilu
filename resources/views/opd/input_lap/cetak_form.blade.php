@php
function tgl_indo($tanggal){
	$bulan = array (
		1 =>   'Januari',
		'Februari',
		'Maret',
		'April',
		'Mei',
		'Juni',
		'Juli',
		'Agustus',
		'September',
		'Oktober',
		'November',
		'Desember'
	);
	$pecahkan = explode('-', $tanggal);
	return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}
  $pecah=explode('/',$form->no_form);
  $bulan=explode('-',explode(' ',$form->created_at)[0])[1];
  $jabatan=json_decode($form->jabatan);
@endphp
<table width=100%>
  <tbody>
    <tr>
      <td colspan="4"><strong><center>FORMULIR LAPORAN HASIL<br>
        PELAKSANAAN PENCEGAHAN PELANGGARAN<br>
        DAN SENGKETA PROSES PEMILU DAN<br>
        PEMILIHAN GUBERNUR DAN WAKIL GUBERNUR,<br>
      BUPATI DAN WAKIL BUPATI, SERTA WALIKOTA DAN WAKIL WALIKOTA</center></strong><br></td>
    </tr>
    <tr>
      <td colspan="4"><center><strong>FORM PENCEGAHAN</strong><br>
        NOMOR : {{$pecah[0].'/'.$pecah[1].'/'.$pecah[2].'/'.ltrim($bulan,'0').'/'.$pecah[4]}}<br>
      </center><br></td>
    </tr>
    <tr>
      <td width="3%"><strong>I</strong></td>
      <td colspan="3"><b>DATA PENGAWAS</b></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td width="29%">a. Nama Pelaksana Tugas</td>
      <td width="1%">:</td>
      <td width="67%">
      @foreach (json_decode($form->namapt) as $no=>$nmpt)
      {{$no+1}}.{{$nmpt}}({{$jabatan[$no]}})
      @endforeach
      </td>
    </tr>
    {{--  <tr>
      <td>&nbsp;</td>
      <td>b. Jabatan</td>
      <td>:</td>
      <td>{{$form->jabatan}}</td>
    </tr>  --}}
    <tr>
      <td>&nbsp;</td>
      <td>c. Nomor Surat Perintah Tugas</td>
      <td>:</td>
      <td>{{$form->nspt}}</td>
    </tr>
    <tr>
      <td><strong>II</strong></td>
      <td colspan="3"><strong>Kegiatan Pencegahan</strong></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>a. Tahapan Pencegahan</td>
      <td>:</td>
      <td>{{$form->tahap=="Non Tahapan"?"Non Tahapan":($tahapan->id=="0"?$form->tahap_lain:$tahapan->tahapan)}}</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>b. Bentuk Pencegahan</td>
      <td>:</td>
      <td>{{$bentuk->id=="0"?$form->bentuk_lain:$bentuk->bentuk}}</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>c. Jenis Pencegahan</td>
      <td>:</td>
      <td>{{$jenis->id=="0"?$form->jenis_lain:$jenis->jenis}}</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>d. Tujuan Pencegahan</td>
      <td>:</td>
      <td>{{$form->tujuan}}</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>e. Sasaran Pencegahan</td>
      <td>:</td>
      <td>{{$form->sasaran}}</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>f. Tanggal Pencegahan</td>
      <td>:</td>
      <td>
          {{tgl_indo(explode(' - ',$form->tanggal)[0])}} s/d {{tgl_indo(explode(' - ',$form->tanggal)[1])}}
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>g. Bukti Pencegahan</td>
      <td>:</td>
      <td>
      @foreach (json_decode($form->bukti) as $no=>$gambar)
          *<a href="/dowload-bukti/{{$gambar}}">File_{{$no+1}}.{{explode('.',$gambar)[1]}}</a>
      @endforeach
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>h. Link Repository</td>
      <td>:</td>
      <td>
        {{$form->repo}}
      </td>
    </tr>
    <tr>
      <td><strong>III</strong></td>
      <td colspan="3"><strong>Uraian Singkat Kegiatan Pencegahan</strong></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>a. Uraian Kegiatan</td>
      <td>:</td>
      <td>{{$form->uraian}}</td>
    </tr>
    <tr>
      <td><strong>IV</strong></td>
      <td colspan="3"><div>
        <div><strong>Tindak Lanjut</strong></div>
      </div></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>a. Uraian Tindak Lanjut</td>
      <td>:</td>
      <td>{{$form->tindaklanjut}}</td>
    </tr>
  </tbody>
</table>
<br>
<br>
<table width="362" style="float:right">
    <tbody>
      <tr>
        <td align="center"><p>{{isset($kabupaten->kabupaten)?$kabupaten->kabupaten:(!isset($provinsi->provinsi)?'Jakarta':$provinsi->provinsi)}}, {{tgl_indo(explode(' ',$form->created_at)[0])}}</p></td>
      </tr>
      <tr>
        <td align="center"><p><img src="{{ asset('ttd').'/'.$form->ttd }}" alt="TTD" width="150" height="150"></p></td>
      </tr>
      <tr>
        <td height="22" align="center"><p>({{json_decode($form->namapt)[0]}})</p></td>
      </tr>
    </tbody>
</table>
           <script>

                window.addEventListener("load", window.print());

              </script>