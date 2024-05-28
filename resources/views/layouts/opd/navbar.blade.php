<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>

    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->


        <!-- Messages Dropdown Menu -->

        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fas fa-user mr-2"></i>
                <span class="badge badge-warning navbar-badge"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">
                   <h4> <a href="/profil"> Profil User</span></h4></a>
                <div class="dropdown-divider"></div>
                {{--  <a href="#" class="dropdown-item">
                    <i class="fas fa-envelope mr-2"></i> {{ Auth::user()->Email }}
                </a>  --}}
                <div class="dropdown-divider"></div>
                {{--  <a href="" class="dropdown-item">
                    <i class="fas fa-user mr-2"></i> {{ Auth::user()->name }}
                </a>  --}}
                <div class="dropdown-divider"></div>
                {{--  <a href="#" class="dropdown-item">
          <i class="fas fa-file mr-2"></i> 3 new reports
          <span class="float-right text-muted text-sm">2 days</span>
        </a>  --}}
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                              this.closest('form').submit();">
                        <span class="dropdown-item dropdown-footer">
                            <h4> Logout</h4>
                        </span>
                    </x-dropdown-link>
                </form>
                {{--  <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>  --}}
            </div>
        </li>
     
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        {{--  <li class="nav-item">
      <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
        <i class="fas fa-th-large"></i>
      </a>
    </li>  --}}
    </ul>
</nav>
