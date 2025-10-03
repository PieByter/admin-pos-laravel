<!-- filepath: resources/views/layouts/partials/sidebar.blade.php -->
<aside class="main-sidebar sidebar-dark-lightblue elevation-4">
    <a href="{{ route('dashboard') }}" class="brand-link text-decoration-none">
        <img src="{{ asset('img/logo/logo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3">
        <span class="brand-text">PT. STTC</span>
    </a>

    <div class="sidebar">
        <!-- Sidebar Search -->
        <div class="form-inline mt-3">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                    aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu"
                data-accordion="false">
                @include('layouts.partials.sidebar-menu')
            </ul>
        </nav>
    </div>
</aside>
