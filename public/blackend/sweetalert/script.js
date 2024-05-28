
//=============================================================
const flashData = $('.flash-tambah').data('flashdata');
if(flashData){
	Swal.fire({
		title:'Data Anda',
		text:'Berhasil ' + flashData,
		type:'success'
	});
}
const flashError = $('.flash-error').data('flasherror');
if(flashError){
	Swal.fire({
		title: flashError,
		text:'Silakan Cek Kembali ' ,
		type:'error'
	});
}
const flashJam = $('.flash-jam').data('flashjam');
if(flashJam){
	Swal.fire({
		title: flashJam,
		text:'Silakan Cek Kembali ' ,
		type:'error'
	});
}

///=================Alert Hapus=====================


$('.tombol-hapus').on('click',function(e){
  e.preventDefault();
  const href=$(this).attr('href');
  Swal.fire({
    title: 'Apakah anda yakin',
    text: "Data akan dihapus",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Hapus Data!'
  }).then((result) => {
    if (result.value) {
      document.location.href=href;
      
    }
  })
  });

$('.tombol-aktif').on('click',function(e){
  e.preventDefault();
  const href=$(this).attr('href');
  Swal.fire({
    title: 'Apakah anda yakin',
    text: "Data akan diaktifkan",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Aktifkan User!'
  }).then((result) => {
    if (result.value) {
      document.location.href=href;
      
    }
  })
  });
  $('.tombol-nonaktif').on('click',function(e){
    e.preventDefault();
    const href=$(this).attr('href');
    Swal.fire({
      title: 'Apakah anda yakin',
      text: "Data akan dinonaktifkan",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Nonaktifkan User!'
    }).then((result) => {
      if (result.value) {
        document.location.href=href;
        
      }
    })
    });

$('.hapus-kategori').on('click',function(e){
e.preventDefault();
const hrefKategori=$(this).attr('href');
Swal.fire({
  title: 'Apakah anda yakin',
  text: "Data Merk akan dihapus",
  type: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Hapus Data!'
}).then((result) => {
  if (result.value) {
    document.location.href=hrefKategori;
    
  }
})
});