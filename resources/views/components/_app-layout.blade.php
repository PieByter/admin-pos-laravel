<!-- filepath: resources/views/components/app-layout.blade.php -->
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dashboard' }}</title>

    <!-- CSS Assets -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet"
        href="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/jqvmap/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/summernote/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <!-- SweetAlert2 & Toastr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet">

    @stack('styles')
</head>

<body class="hold-transition sidebar layout-fixed">
    @php
        $permissions = auth()->user()->getAllPermissions()->pluck('name')->toArray() ?? [];
        $user = auth()->user();
    @endphp

    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item ms-3">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>

                <!-- Dashboard -->
                <li class="nav-item d-none d-sm-inline-block">
                    <a class="nav-link{{ request()->is('dashboard') ? ' active' : '' }}"
                        href="{{ route('dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>

                <!-- Dropdown Transaksi -->
                @if (in_array('pre_purchase_orders_view', $permissions) ||
                        in_array('purchase_orders_view', $permissions) ||
                        in_array('sales_orders_view', $permissions) ||
                        in_array('transactions_view', $permissions))
                    <li class="nav-item d-none d-md-block dropdown">
                        <a class="nav-link dropdown-toggle{{ request()->is(['po*', 'pembelian*', 'penjualan*', 'transaksi*']) ? ' active' : '' }}"
                            href="#" id="transaksiDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-coins"></i> Transaksi
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="transaksiDropdown">
                            @can('transactions_view')
                                <li><a class="dropdown-item" href="{{ route('transaksi.index') }}">Akumulasi Transaksi</a>
                                </li>
                            @endcan
                            @can('pre_purchase_orders_view')
                                <li><a class="dropdown-item" href="{{ route('po.index') }}">Purchase Order</a></li>
                            @endcan
                            @can('purchase_orders_view')
                                <li><a class="dropdown-item" href="{{ route('pembelian.index') }}">Pembelian</a></li>
                            @endcan
                            @can('sales_orders_view')
                                <li><a class="dropdown-item" href="{{ route('penjualan.index') }}">Penjualan</a></li>
                            @endcan
                        </ul>
                    </li>
                @endif

                <!-- Dropdown Master Data -->
                @if (in_array('items_view', $permissions) ||
                        in_array('suppliers_view', $permissions) ||
                        in_array('customers_view', $permissions))
                    <li class="nav-item d-none d-sm-inline-block dropdown">
                        <a class="nav-link dropdown-toggle{{ request()->is(['barang*', 'supplier*', 'customer*', 'satuan*', 'jenis-barang*', 'group-barang*']) ? ' active' : '' }}"
                            href="#" id="masterDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-box-open"></i> Master Data
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="masterDropdown">
                            @can('items_view')
                                <li><a class="dropdown-item" href="{{ route('barang.index') }}">Barang</a></li>
                            @endcan
                            @can('suppliers_view')
                                <li><a class="dropdown-item" href="{{ route('supplier.index') }}">Supplier</a></li>
                            @endcan
                            @can('customers_view')
                                <li><a class="dropdown-item" href="{{ route('customer.index') }}">Customer</a></li>
                            @endcan
                            @can('unit_conversions_view')
                                <li><a class="dropdown-item" href="{{ route('satuan-konversi.index') }}">Satuan
                                        Konversi</a></li>
                            @endcan
                            @can('units_view')
                                <li><a class="dropdown-item" href="{{ route('satuan.index') }}">Satuan Barang</a></li>
                            @endcan
                            @can('item_categories_view')
                                <li><a class="dropdown-item" href="{{ route('jenis-barang.index') }}">Jenis Barang</a></li>
                            @endcan
                            @can('item_groups_view')
                                <li><a class="dropdown-item" href="{{ route('group-barang.index') }}">Group Barang</a></li>
                            @endcan
                        </ul>
                    </li>
                @endif

                <!-- Dropdown Admin -->
                @if (in_array('users_view', $permissions) || in_array('activity_logs_view', $permissions))
                    <li class="nav-item d-none d-md-block dropdown">
                        <a class="nav-link dropdown-toggle{{ request()->is('superadmin/*') ? ' active' : '' }}"
                            href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-cog"></i> Admin
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="adminDropdown">
                            @can('users_view')
                                <li><a class="dropdown-item" href="{{ route('superadmin.users.index') }}">Manajemen
                                        User</a></li>
                            @endcan
                            @can('activity_logs_view')
                                <li><a class="dropdown-item" href="{{ route('superadmin.logs.index') }}">Log
                                        Aktivitas</a></li>
                            @endcan
                        </ul>
                    </li>
                @endif
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ms-auto">
                <!-- Dark Mode Toggle -->
                <li class="nav-item d-flex align-items-center">
                    <i class="fas fa-sun me-2"></i>
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" id="darkModeSwitch">
                        <label class="form-check-label" for="darkModeSwitch">
                            <i class="fas fa-moon"></i>
                        </label>
                    </div>
                </li>

                <!-- Fullscreen -->
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>

                <!-- User Menu -->
                <li class="nav-item dropdown user-menu">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        @if ($user->foto && file_exists(public_path('uploads/profile/' . $user->foto)))
                            <img src="{{ asset('uploads/profile/' . $user->foto) }}"
                                class="user-image rounded-circle shadow" alt="User Image"
                                style="width:25px; height:25px; object-fit:cover;">
                        @else
                            <img src="{{ asset('img/avatar.png') }}" class="user-image rounded-circle shadow"
                                alt="Default User Image">
                        @endif
                        <span class="d-none d-md-inline">{{ $user->username ?? 'User' }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                        <li class="user-header d-flex flex-column align-items-center justify-content-center"
                            style="background: url('{{ asset('img/bg_picture.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
                            @if ($user->foto && file_exists(public_path('uploads/profile/' . $user->foto)))
                                <img src="{{ asset('uploads/profile/' . $user->foto) }}"
                                    class="rounded-circle shadow" alt="User Image"
                                    style="width:80px; height:80px; object-fit:cover;">
                            @else
                                <img src="{{ asset('img/avatar.png') }}" class="rounded-circle shadow"
                                    alt="Default User Image">
                            @endif
                            <p>
                                {{ $user->username ?? 'User' }} - {{ $user->roles->first()->name ?? 'Role' }}<br>
                                <small>Member since
                                    {{ $user->created_at ? $user->created_at->format('M. Y') : '-' }}</small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="row">
                                <div class="col-6">
                                    <a class="btn btn-block btn-outline-primary" href="{{ route('profile') }}">
                                        <i class="fas user-circle"></i> Profile
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a class="btn btn-block btn-outline-danger float-end"
                                        href="{{ route('logout') }}">
                                        <i class="fas fa-sign-out-alt"></i> Sign Out
                                    </a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-lightblue elevation-4">
            <a href="{{ route('dashboard') }}" class="brand-link text-decoration-none">
                <img src="{{ asset('img/logo.png') }}" alt="AdminLTE Logo"
                    class="brand-image img-circle elevation-3">
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
                    <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview"
                        role="menu" data-accordion="false">
                        <!-- Dashboard -->
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}"
                                class="nav-link{{ request()->is('dashboard') ? ' active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        <!-- Transaksi Group -->
                        @if (in_array('pre_purchase_orders_view', $permissions) ||
                                in_array('purchase_orders_view', $permissions) ||
                                in_array('sales_orders_view', $permissions) ||
                                in_array('transactions_view', $permissions))
                            <li
                                class="nav-item{{ request()->is(['po*', 'pembelian*', 'penjualan*', 'transaksi*']) ? ' menu-open' : '' }}">
                                <a href="#"
                                    class="nav-link{{ request()->is(['po*', 'pembelian*', 'penjualan*', 'transaksi*']) ? ' active' : '' }}">
                                    <i class="nav-icon fas fa-coins"></i>
                                    <p>
                                        Transaksi
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @can('transactions_view')
                                        <li class="nav-item">
                                            <a href="{{ route('transaksi.index') }}"
                                                class="nav-link{{ request()->is('transaksi*') ? ' active' : '' }}">
                                                <i class="nav-icon fas fa-money-check"></i>
                                                <p>Akumulasi Transaksi</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('pre_purchase_orders_view')
                                        <li class="nav-item">
                                            <a href="{{ route('po.index') }}"
                                                class="nav-link{{ request()->is('po*') ? ' active' : '' }}">
                                                <i class="nav-icon "></i>
                                                <p>Purchase Order</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('purchase_orders_view')
                                        <li class="nav-item">
                                            <a href="{{ route('pembelian.index') }}"
                                                class="nav-link{{ request()->is('pembelian*') ? ' active' : '' }}">
                                                <i class="nav-icon fas fa-cart-plus"></i>
                                                <p>Pembelian</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('sales_orders_view')
                                        <li class="nav-item">
                                            <a href="{{ route('penjualan.index') }}"
                                                class="nav-link{{ request()->is('penjualan*') ? ' active' : '' }}">
                                                <i class="nav-icon fas fa-coins"></i>
                                                <p>Penjualan</p>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endif

                        <!-- Master Data Group -->
                        @if (in_array('items_view', $permissions) ||
                                in_array('suppliers_view', $permissions) ||
                                in_array('customers_view', $permissions))
                            <li
                                class="nav-item{{ request()->is(['barang*', 'supplier*', 'customer*', 'satuan*', 'jenis-barang*', 'group-barang*']) ? ' menu-open' : '' }}">
                                <a href="#"
                                    class="nav-link{{ request()->is(['barang*', 'supplier*', 'customer*', 'satuan*', 'jenis-barang*', 'group-barang*']) ? ' active' : '' }}">
                                    <i class="nav-icon fas fa-box-open"></i>
                                    <p>
                                        Master Data
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @can('items_view')
                                        <li class="nav-item">
                                            <a href="{{ route('barang.index') }}"
                                                class="nav-link{{ request()->is('barang*') ? ' active' : '' }}">
                                                <i class="nav-icon fas fa-box"></i>
                                                <p>Barang</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('suppliers_view')
                                        <li class="nav-item">
                                            <a href="{{ route('supplier.index') }}"
                                                class="nav-link{{ request()->is('supplier*') ? ' active' : '' }}">
                                                <i class="nav-icon fas fa-truck"></i>
                                                <p>Supplier</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('customers_view')
                                        <li class="nav-item">
                                            <a href="{{ route('customer.index') }}"
                                                class="nav-link{{ request()->is('customer*') ? ' active' : '' }}">
                                                <i class="nav-icon fas fa-user-friends"></i>
                                                <p>Customer</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('unit_conversions_view')
                                        <li class="nav-item">
                                            <a href="{{ route('satuan-konversi.index') }}"
                                                class="nav-link{{ request()->is('satuan-konversi*') ? ' active' : '' }}">
                                                <i class="nav-icon fas fa-money-check-alt"></i>
                                                <p>Satuan Konversi</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('units_view')
                                        <li class="nav-item">
                                            <a href="{{ route('satuan.index') }}"
                                                class="nav-link{{ request()->is('satuan*') && !request()->is('satuan-konversi*') ? ' active' : '' }}">
                                                <i class="nav-icon fa-ruler-combined"></i>
                                                <p>Satuan Barang</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('item_categories_view')
                                        <li class="nav-item">
                                            <a href="{{ route('jenis-barang.index') }}"
                                                class="nav-link{{ request()->is('jenis-barang*') ? ' active' : '' }}">
                                                <i class="nav-icon fas fa-sitemap"></i>
                                                <p>Jenis Barang</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('item_groups_view')
                                        <li class="nav-item">
                                            <a href="{{ route('group-barang.index') }}"
                                                class="nav-link{{ request()->is('group-barang*') ? ' active' : '' }}">
                                                <i class="nav-icon fas fa-boxes"></i>
                                                <p>Group Barang</p>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endif

                        <!-- Admin Group -->
                        @if (in_array('users_view', $permissions) || in_array('activity_logs_view', $permissions))
                            <li class="nav-item{{ request()->is('superadmin/*') ? ' menu-open' : '' }}">
                                <a href="#"
                                    class="nav-link{{ request()->is('superadmin/*') ? ' active' : '' }}">
                                    <i class="nav-icon fas fa-user-cog"></i>
                                    <p>
                                        Admin & Log
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @can('users_view')
                                        <li class="nav-item">
                                            <a href="{{ route('superadmin.users.index') }}"
                                                class="nav-link{{ request()->is('superadmin/users*') ? ' active' : '' }}">
                                                <i class="nav-icon fas fa-user-edit"></i>
                                                <p>Manajemen User</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('activity_logs_view')
                                        <li class="nav-item">
                                            <a href="{{ route('superadmin.logs.index') }}"
                                                class="nav-link{{ request()->is('superadmin/logs*') ? ' active' : '' }}">
                                                <i class="nav-icon fas fa-address-book-text"></i>
                                                <p>Log Aktivitas</p>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endif

                        <!-- Profil -->
                        <li class="nav-item">
                            <a href="{{ route('profile.index') }}"
                                class="nav-link{{ request()->is('profile*') ? ' active' : '' }}">
                                <i class="nav-icon fas user-circle"></i>
                                <p>Profil Pengguna</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            {{ $slot }}
        </div>

        <!-- Footer -->
        <footer class="main-footer text-sm">
            <div class="float-end d-none d-sm-inline"><b>Version</b> 1.0.0</div>
            <strong>
                Copyright &copy; {{ date('Y') }}&nbsp;
                <a href="https://www.sttc.co.id/" class="text-decoration-none">
                    PT. Sumatra Tobacco Trading Company
                </a>.
            </strong>
            All rights reserved.
        </footer>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/sparklines/sparkline.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <script src="{{ asset('adminlte/dist/js/adminlte.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables -->
    <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

    <!-- SweetAlert2 & Toastr -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>

    <script>
        // Dark Mode Toggle
        const darkModeSwitch = document.getElementById('darkModeSwitch');

        if (localStorage.getItem('darkMode') === 'on') {
            document.body.classList.add('dark-mode');
            darkModeSwitch.checked = true;
        } else {
            darkModeSwitch.checked = false;
        }

        darkModeSwitch.addEventListener('change', function() {
            document.body.classList.toggle('dark-mode');
            if (document.body.classList.contains('dark-mode')) {
                localStorage.setItem('darkMode', 'on');
            } else {
                localStorage.setItem('darkMode', 'off');
            }
        });

        // Sidebar State
        const body = document.body;

        function saveSidebarState() {
            if (body.classList.contains('sidebar-collapse')) {
                localStorage.setItem('sidebarOpen', 'false');
            } else {
                localStorage.setItem('sidebarOpen', 'true');
            }
        }

        if (localStorage.getItem('sidebarOpen') === 'true') {
            body.classList.remove('sidebar-collapse');
        } else {
            body.classList.add('sidebar-collapse');
        }

        const observer = new MutationObserver(saveSidebarState);
        observer.observe(body, {
            attributes: true,
            attributeFilter: ['class']
        });

        // Toastr Configuration
        function toasterOptions() {
            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-center",
                "preventDuplicates": true,
                "onclick": null,
                "showDuration": "100",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "show",
                "hideMethod": "hide"
            };
        }
        toasterOptions();
    </script>

    @stack('scripts')
</body>

</html>
