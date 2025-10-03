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
        @canany(['pre_purchase_orders_view', 'purchase_orders_view', 'sales_orders_view', 'purchase_returns_view',
            'sales_returns_view', 'transactions_view'])
            <li class="nav-item d-none d-md-block dropdown">
                <a class="nav-link dropdown-toggle{{ request()->is(['purchase-orders*', 'purchases*', 'sales*', 'purchase-returns*', 'sales-returns*', 'transactions*']) ? ' active' : '' }} text-gray-900 dark:text-white"
                    href="#" id="transaksiDropdown" role="button" data-bs-toggle="dropdown">
                    <i class="bi bi-cash-coin"></i> Transaksi
                </a>
                <ul class="dropdown-menu" aria-labelledby="transaksiDropdown">
                    @can('transactions_view')
                        <li><a class="dropdown-item" href="{{ route('transactions.index') }}">
                                <i class="bi bi-graph-up me-2"></i>Akumulasi Transaksi
                            </a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                    @endcan

                    @can('pre_purchase_orders_view')
                        <li><a class="dropdown-item" href="{{ route('purchase-orders.index') }}">
                                <i class="bi bi-file-earmark-plus me-2"></i>Purchase Order
                            </a></li>
                    @endcan

                    @can('purchase_orders_view')
                        <li><a class="dropdown-item" href="{{ route('purchases.index') }}">
                                <i class="bi bi-cart-plus me-2"></i>Pembelian
                            </a></li>
                    @endcan

                    @can('sales_orders_view')
                        <li><a class="dropdown-item" href="{{ route('sales.index') }}">
                                <i class="bi bi-bag-check me-2"></i>Penjualan
                            </a></li>
                    @endcan

                    @canany(['purchase_returns_view', 'sales_returns_view'])
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        {{-- <li>
                            <h6 class="dropdown-header">Retur</h6>
                        </li> --}}
                    @endcanany

                    @can('purchase_returns_view')
                        <li><a class="dropdown-item" href="{{ route('purchase-returns.index') }}">
                                <i class="bi bi-arrow-return-left me-2 text-warning"></i>Retur Pembelian
                            </a></li>
                    @endcan

                    @can('sales_returns_view')
                        <li><a class="dropdown-item" href="{{ route('sales-returns.index') }}">
                                <i class="bi bi-arrow-return-right me-2 text-info"></i>Retur Penjualan
                            </a></li>
                    @endcan
                </ul>
            </li>
        @endcanany

        <!-- Dropdown Master Data -->
        @canany(['items_view', 'suppliers_view', 'customers_view', 'unit_conversions_view', 'units_view',
            'item_categories_view', 'item_groups_view'])
            <li class="nav-item d-none d-sm-inline-block dropdown">
                <a class="nav-link dropdown-toggle{{ request()->is(['items*', 'suppliers*', 'customers*', 'units*', 'item-categories*', 'item-groups*', 'unit-conversions*']) ? ' active' : '' }}"
                    href="#" id="masterDropdown" role="button" data-bs-toggle="dropdown">
                    <i class="bi bi-box-seam"></i> Master Data
                </a>
                <ul class="dropdown-menu" aria-labelledby="masterDropdown">
                    @can('items_view')
                        <li><a class="dropdown-item" href="{{ route('items.index') }}">
                                <i class="bi bi-box me-2"></i>Barang
                            </a></li>
                    @endcan
                    @can('suppliers_view')
                        <li><a class="dropdown-item" href="{{ route('suppliers.index') }}">
                                <i class="bi bi-truck me-2"></i>Supplier
                            </a></li>
                    @endcan
                    @can('customers_view')
                        <li><a class="dropdown-item" href="{{ route('customers.index') }}">
                                <i class="bi bi-people me-2"></i>Customer
                            </a></li>
                    @endcan
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    @can('unit_conversions_view')
                        <li><a class="dropdown-item" href="{{ route('unit-conversions.index') }}">
                                <i class="bi bi-arrow-left-right me-2"></i>Satuan Konversi
                            </a></li>
                    @endcan
                    @can('units_view')
                        <li><a class="dropdown-item" href="{{ route('units.index') }}">
                                <i class="bi bi-rulers me-2"></i>Satuan Barang
                            </a></li>
                    @endcan
                    @can('item_categories_view')
                        <li><a class="dropdown-item" href="{{ route('item-categories.index') }}">
                                <i class="bi bi-tags me-2"></i>Kategori Barang
                            </a></li>
                    @endcan
                    @can('item_groups_view')
                        <li><a class="dropdown-item" href="{{ route('item-groups.index') }}">
                                <i class="bi bi-collection me-2"></i>Group Barang
                            </a></li>
                    @endcan
                </ul>
            </li>
        @endcanany

        <!-- Dropdown Admin -->
        @canany(['users_view', 'activity_logs_view'])
            <li class="nav-item d-none d-md-block dropdown">
                <a class="nav-link dropdown-toggle{{ request()->is('superadmin/*') ? ' active' : '' }}" href="#"
                    id="adminDropdown" role="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-gear"></i> Admin
                </a>
                <ul class="dropdown-menu" aria-labelledby="adminDropdown">
                    @can('users_view')
                        <li><a class="dropdown-item" href="{{ route('superadmin.users.index') }}">
                                <i class="bi bi-people me-2"></i>Manajemen User
                            </a></li>
                    @endcan
                    @can('activity_logs_view')
                        <li><a class="dropdown-item" href="{{ route('superadmin.activity-logs.index') }}">
                                <i class="bi bi-journal-text me-2"></i>Log Aktivitas
                            </a></li>
                    @endcan
                </ul>
            </li>
        @endcanany
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
