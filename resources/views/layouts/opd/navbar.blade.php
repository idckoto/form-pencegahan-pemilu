<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item"><a href="/welcome" class="nav-link">Form Pencegahan Online</a></li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item"><a href="/profil" class="nav-link"><span class="badge badge-info badge-md p-2"><i class="fas fa-user mr-2"></i>{{ strtoupper(Auth::user()->name) }}</span> &nbsp; <span class="badge badge-warning badge-md p-2">{{ strtoupper(Auth::user()->Jabatan) }} </span> &nbsp; <span class="badge badge-secondary badge-md p-2">{{ strtolower(Auth::user()->email) }} </span></a></li>
    </ul>
</nav>
