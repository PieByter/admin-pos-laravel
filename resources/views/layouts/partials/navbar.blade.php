<!-- filepath: resources/views/layouts/partials/navbar.blade.php -->
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
            <a class="nav-link{{ request()->is('dashboard') ? ' active' : '' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </li>

        <!-- Dropdown Transaksi -->
        @if (in_array('pre_purchase_orders_view', $permissions) ||
                in_array('purchase_orders_view', $permissions) ||
                in_array('sales_orders_view', $permissions) ||
                in_array('transactions_view', $permissions))
            <li class="nav-item d-none d-md-block dropdown">
                <a class="nav-link dropdown-toggle{{ request()->is(['po*', 'pembelian*', 'penjualan*', 'transaksi*']) ? ' active' : '' }} text-gray-900 dark:text-white"
                    href="#" id="transaksiDropdown" role="button" data-bs-toggle="dropdown">
                    <i class="bi bi-cash-coin"></i> Transaksi
                </a>
                <ul class="dropdown-menu" aria-labelledby="transaksiDropdown">
                    @if (in_array('transactions_view', $permissions))
                        <li><a class="dropdown-item" href="{{ url('transaksi') }}">Akumulasi Transaksi</a></li>
                    @endif
                    @if (in_array('pre_purchase_orders_view', $permissions))
                        <li><a class="dropdown-item" href="{{ url('po') }}">Purchase Order</a></li>
                    @endif
                    @if (in_array('purchase_orders_view', $permissions))
                        <li><a class="dropdown-item" href="{{ url('pembelian') }}">Pembelian</a></li>
                    @endif
                    @if (in_array('sales_orders_view', $permissions))
                        <li><a class="dropdown-item" href="{{ url('penjualan') }}">Penjualan</a></li>
                    @endif
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
                    <i class="bi bi-box-seam"></i> Master Data
                </a>
                <ul class="dropdown-menu" aria-labelledby="masterDropdown">
                    @if (in_array('items_view', $permissions))
                        <li><a class="dropdown-item" href="{{ url('barang') }}">Barang</a></li>
                    @endif
                    @if (in_array('suppliers_view', $permissions))
                        <li><a class="dropdown-item" href="{{ url('supplier') }}">Supplier</a></li>
                    @endif
                    @if (in_array('customers_view', $permissions))
                        <li><a class="dropdown-item" href="{{ url('customer') }}">Customer</a></li>
                    @endif
                    @if (in_array('unit_conversions_view', $permissions))
                        <li><a class="dropdown-item" href="{{ url('satuan-konversi') }}">Satuan Konversi</a></li>
                    @endif
                    @if (in_array('units_view', $permissions))
                        <li><a class="dropdown-item" href="{{ url('satuan') }}">Satuan Barang</a></li>
                    @endif
                    @if (in_array('item_categories_view', $permissions))
                        <li><a class="dropdown-item" href="{{ url('jenis-barang') }}">Jenis Barang</a></li>
                    @endif
                    @if (in_array('item_groups_view', $permissions))
                        <li><a class="dropdown-item" href="{{ url('group-barang') }}">Group Barang</a></li>
                    @endif
                </ul>
            </li>
        @endif

        <!-- Dropdown Admin -->
        @if (in_array('users_view', $permissions) || in_array('activity_logs_view', $permissions))
            <li class="nav-item d-none d-md-block dropdown">
                <a class="nav-link dropdown-toggle{{ request()->is('superadmin/*') ? ' active' : '' }}" href="#"
                    id="adminDropdown" role="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-gear"></i> Admin
                </a>
                <ul class="dropdown-menu" aria-labelledby="adminDropdown">
                    @if (in_array('users_view', $permissions))
                        <li><a class="dropdown-item" href="{{ url('superadmin/users') }}">Manajemen User</a></li>
                    @endif
                    @if (in_array('activity_logs_view', $permissions))
                        <li><a class="dropdown-item" href="{{ url('superadmin/logs') }}">Log Aktivitas</a></li>
                    @endif
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

        @include('layouts.partials.user-menu')
    </ul>
</nav>
