<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>FORMULIR LAPORAN </title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        {{--  href="{{ asset('blackend/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">  --}}
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('blackend/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <!-- JQVMap -->
    {{--  <link rel="stylesheet" href="{{ asset('blackend/plugins/jqvmap/jqvmap.min.css')}}">  --}}
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('blackend/plugins/summernote/summernote-bs4.min.css')}}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('blackend/plugins/select2/css/select2.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('blackend/dist/css/adminlte.min.css')}}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('blackend/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('blackend/plugins/daterangepicker/daterangepicker.css')}}">
    <!-- summernote -->
    {{--  <link rel="stylesheet" href="{{ asset('blackend/plugins/summernote/summernote-bs4.min.css')}}">  --}}
    <!-- BS Stepper -->
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('blackend/plugins/fontawesome-free/css/all.min.css')}}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('blackend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet"
        href="{{ asset('blackend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('blackend/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('blackend/dist/css/adminlte.min.css')}}">
    {{--  <link rel="stylesheet" href="{{ asset('blackend/plugins/summernote/summernote-bs4.min.css')}}">  --}}
    <!-- Sweetalert style -->
    <link rel="stylesheet" href="{{ asset('blackend/sweetalert/sweetalert2.min.css')}}">
</head>

<body class="hold-transition sidebar-mini layout-fixed text-sm sidebar-collapse sidebar-mini-xs layout-navbar-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        @include('layouts.opd.navbar')
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        @include('layouts.opd.sidebarmenu')


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            {{--  @include('layouts.blackand.header')  --}}


            <!-- Main content -->
            <section class="content">
<div class="flash-tambah" data-flashdata="{{ session('status') }}"></div>
<div class="flash-error" data-flasherror="{{ session('error') }}"></div>
                @yield('content')
                {{--  @include('sweetalert::alert')  --}}
                <!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
            <strong><a href="/">BAWASLU</a> &copy; 2022 </strong>
            </div>
            <span class="badge badge-secondary badge-md">USER</span> : <span class="badge badge-info badge-md">{{ strtoupper(Auth::user()->name) }}</span> | <span class="badge badge-warning badge-md">{{ strtoupper(Auth::user()->Jabatan) }} </span>  | <span class="badge badge-secondary badge-md">{{ strtolower(Auth::user()->email) }} </span>
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    @stack('scripts')
    <!-- ./wrapper -->
    {{--  <script src="{{ asset('js/sweetalert.min.js')}}"></script>  --}}

    <!-- ChartJS -->
    {{--  <script src="{{ asset('blackend/plugins/chart.js/Chart.min.js')}}"></script>  --}}
    <!-- Sparkline -->
    {{--  <script src="{{ asset('blackend/plugins/sparklines/sparkline.js')}}"></script>  --}}
    <!-- JQVMap -->
    {{--  <script src="{{ asset('blackend/plugins/jqvmap/jquery.vmap.min.js')}}"></script>
    <script src="{{ asset('blackend/plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>  --}}
    <!-- jQuery Knob Chart -->
    {{--  <script src="{{ asset('blackend/plugins/jquery-knob/jquery.knob.min.js')}}"></script>  --}}
    <!-- daterangepicker -->
    <script src="{{ asset('blackend/plugins/moment/moment.min.js')}}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    {{--  <script src="{{ asset('blackend/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>  --}}
    <!-- Summernote -->
    {{--  <script src="{{ asset('blackend/plugins/summernote/summernote-bs4.min.js')}}"></script>  --}}
    <!-- overlayScrollbars -->
    {{--  <script src="{{ asset('blackend/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>  --}}
    <!-- AdminLTE App -->
    {{--  <script src="{{ asset('blackend/dist/js/adminlte.js')}}"></script>
    <script src="{{ asset('blackend/dist/js/pages/dashboard.js')}}"></script>  --}}
    <!-- jQuery -->
    <script src="{{ asset('blackend/plugins/jquery/jquery.min.js')}}"></script>
    {{--  <script src="{{ asset('blackend/plugins/jquery/jqueryx.min.js')}}"></script>  --}}
    <!-- Bootstrap 4 -->
    <script src="{{ asset('blackend/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('blackend/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('blackend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('blackend/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{ asset('blackend/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('blackend/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('blackend/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('blackend/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{ asset('blackend/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{ asset('blackend/plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
    <script src="{{ asset('blackend/plugins/jszip/jszip.min.js')}}"></script>
    <script src="{{ asset('blackend/plugins/pdfmake/pdfmake.min.js')}}"></script>
    <script src="{{ asset('blackend/plugins/pdfmake/vfs_fonts.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('blackend/dist/js/adminlte.min.js')}}"></script>
    <script src="{{ asset('blackend/plugins/summernote/summernote-bs4.min.js')}}"></script>
    <!-- AdminLTE for demo purposes -->
    <!-- Page specific script -->
    <!-- SweetAlert2 -->
    <script src="{{ asset('blackend/sweetalert/sweetalert2.min.js')}}"></script>
    <script src="{{ asset('blackend/sweetalert/script.js')}}"></script>
    {{--  <script src="{{ asset('blackend/sweetalert/jquery-ui.min.js')}}"></script>  --}}
    <!-- Select2 -->
    <script src="{{ asset('blackend/plugins/select2/js/select2.full.min.js')}}"></script>
    <script>
        $(function () {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

            $("#example3").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#example3_wrapper .col-md-6:eq(0)');

            $("#example4").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#example4_wrapper .col-md-6:eq(0)');

            $("#example5").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#example5_wrapper .col-md-6:eq(0)');

            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });



        });

    </script>
</body>
</html>
