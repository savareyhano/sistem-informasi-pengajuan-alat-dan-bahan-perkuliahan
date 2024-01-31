@section('style')
    <style>
        .user-panel .image {
            padding-left: .5rem;
        }
    </style>
@endsection
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="{{ asset('images/Politeknik-TEDC.png') }}" alt="Poltek TEDC Logo"
             class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image d-flex" style="color: #C2C7D0">
                <i class="fas fa-user fa-3x my-auto"></i>
            </div>
            <div class="info">
                <p class="m-0" style="color: #C2C7D0">{{ ucwords(Auth::user()->name) }}</p>
                <small style="color: #C2C7D0">{{ ucwords(str_replace('_', ' ', Auth::user()->role)) }}</small>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                       class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#"
                       class="nav-link {{ request()->routeIs('pengajuan*') || request()->routeIs('realisasi*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p>
                            Alat & Bahan
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('pengajuan') }}"
                               class="nav-link {{ request()->routeIs('pengajuan*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pengajuan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('realisasi') }}"
                               class="nav-link {{ request()->routeIs('realisasi*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Realisasi</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @can('access-menu', 'wakil_direktur')
                    <li class="nav-item">
                        <a href="{{ route('prodi') }}"
                           class="nav-link {{ request()->routeIs('prodi') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-book-open"></i>
                            <p>Prodi</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('user') }}" class="nav-link {{ request()->routeIs('user') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user"></i>
                            <p>User</p>
                        </a>
                    </li>
                @endcan
                <li class="nav-item">
                    <a href="{{ route('option') }}" class="nav-link {{ request()->routeIs('option') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>Option</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="javascript:" onclick="$('#logout_form').submit()" class="nav-link">
                        <i class="nav-icon fas fa-power-off text-danger"></i>
                        <p>Logout</p>
                    </a>
                    <form action="{{ route('logout') }}" method="post" id="logout_form" class="d-none">@csrf</form>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
