<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="{{ asset('storage/logo-3.png')}}" alt=""
            class="brand-image img-square">

        <span class="brand-text font-weight" >
            BAWASLU RI
        </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        {{--  <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                @if (Auth::user()->profile_photo_path!=null)
                <img src="{{ Storage::url('public/staff/'.Auth::user()->profile_photo_path.'') }}" class="img-thumbnail"
                    alt="{{ Auth::user()->NamaLengkap }}" />

                @else
                <img src="{{ asset('blackend/dist/img/avatar5.png')}}" class="img-circle elevation-2" alt="User Image">

                @endif

            </div>
            <div class="info">
                <a href="/profil" class="d-block">{{ Auth::user()->NamaLengkap }}</a>
            </div>
        </div>  --}}



        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->

                
                <li class="nav-item">
                    <a href="/welcome" class="nav-link {{ $active_menu == 'welcome' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home text-warning"></i>
                        <p>
                            Beranda
                            <span class="right badge badge-danger"></span>
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/dashboard" class="nav-link {{ $active_menu == 'dashboard' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-pie text-warning"></i>
                        <p>
                            Grafik
                            <span class="right badge badge-danger"></span>
                        </p>
                    </a>
                </li>
                    {{-- <li class="nav-item">
                    <a href="dashboard-jenis" class="nav-link">
                        <i class="nav-icon fas fa-chart-pie"></i>
                        <p>
                            Grafik Jenis
                            <span class="right badge badge-danger"></span>
                        </p>
                    </a>
                </li> --}}
            {{--  @can('bawaslu')  --}}
            @if(Auth::user()->id_admin==0)
                <li class="nav-item {{ $open_menu == 'data_master' ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-file text-warning"></i>
                        <p>
                            Data Master
                            <i class="fas fa-angle-right right"></i>

                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/petugas" class="nav-link {{ $active_menu == 'petugas' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Divisi</p>
                            </a>
                        </li>
                        {{--  <li class="nav-item">
                            <a href="/wilayah" class="nav-link {{ $active_menu == 'wilayah' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Wilayah</p>
                            </a>
                        </li>  --}}
                        <li class="nav-item">
                            <a href="/tahapan" class="nav-link {{ $active_menu == 'tahapan' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Tahapan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/bentuk" class="nav-link {{ $active_menu == 'bentuk' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Bentuk Pencegahan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/jenis" class="nav-link {{ $active_menu == 'jenis' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Jenis Pencegahan</p>
                            </a>
                        </li>
                        {{--  <li class="nav-item">
                            <a href="/tujuan" class="nav-link {{ $active_menu == 'tujuan' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Tujuan Pencegahan</p>
                            </a>
                        </li>  --}}
                        {{--  <li class="nav-item">
                            <a href="/sasaran" class="nav-link {{ $active_menu == 'sasaran' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Sasaran</p>
                            </a>
                        </li>  --}}
                    </ul>
                </li>
                @endif
            {{--  @endcan  --}}
                <li class="nav-item {{ $open_menu == 'input-laporan' ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-edit text-warning"></i>
                        <p>
                            Form pencegahan
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/list-form" class="nav-link {{ $active_menu == 'input_list' ? 'active' : '' }}"><i class="nav-icon far fa-circle text-white"></i><p>Input Form Pencegahan</p></a>
                        </li>
                        <li class="nav-item">
                            <a href="/laporan-form" class="nav-link {{ $active_menu == 'laporan_list' ? 'active' : '' }}"><i class="far fa-circle nav-icon"></i><p>Lap. Form Pencegahan</p></a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="/user-akses" class="nav-link">
                        <i class="nav-icon fas fa-users text-success"></i>
                        <p>
                            Data User
                            <span class="right badge badge-danger"></span>
                        </p>
                    </a>
                </li>
                {{--  <li class="nav-item">
                    <a href="/graph" class="nav-link">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>
                            Grafik
                            <span class="right badge badge-danger"></span>
                        </p>
                    </a>
                </li>  --}}


                <li class="nav-item">
                    <a href="/signout" class="nav-link">
                        <i class="nav-icon fas fa-power-off text-danger"></i> <p>Logout</p>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
