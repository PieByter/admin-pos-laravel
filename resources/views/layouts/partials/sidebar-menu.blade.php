<!-- filepath: resources/views/layouts/partials/sidebar-menu.blade.php -->
<!-- Dashboard -->
{{-- @php(app()->make(\Spatie\Permission\PermissionRegistrar::class)->initializeCache()) --}}
<li class="nav-item">
    <a href="{{ route('dashboard') }}" class="nav-link{{ request()->is('dashboard') ? ' active' : '' }}">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>Dashboard</p>
    </a>
</li>

<!-- Transaksi Group -->
@canany(['purchase_orders_view', 'sales_orders_view', 'purchase_returns_view', 'sales_returns_view',
    'transactions_view'])
    <li
        class="nav-item{{ request()->is(['purchase-orders*', 'purchases*', 'sales*', 'purchase-returns*', 'sales-returns*', 'transactions*']) ? ' menu-open' : '' }}">
        <a href="#"
            class="nav-link{{ request()->is(['purchase-orders*', 'purchases*', 'sales*', 'purchase-returns*', 'sales-returns*', 'transactions*']) ? ' active' : '' }}">
            <i class="nav-icon fas fa-coins"></i>
            <p>
                Transaksi
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            @can('transactions_view')
                <li class="nav-item">
                    <a href="{{ route('transactions.index') }}"
                        class="nav-link{{ request()->is('transactions*') ? ' active' : '' }}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Akumulasi Transaksi</p>
                    </a>
                </li>
            @endcan
            {{-- @can('pre_purchase_orders_view')
                <li class="nav-item">
                    <a href="{{ route('purchase-orders.index') }}"
                        class="nav-link{{ request()->is('purchase-orders*') ? ' active' : '' }}">
                        <asclass="nav-icon fas fa-file-medical"></i>
                        <p>Purchase Order</p>
                    </a>
                </li>
            @endcan --}}
            @can('purchase_orders_view')
                <li class="nav-item">
                    <a href="{{ route('purchases.index') }}" class="nav-link{{ request()->is('purchases*') ? ' active' : '' }}">
                        <i class="nav-icon fas fa-cart-plus"></i>
                        <p>Purchase Order</p>
                    </a>
                </li>
            @endcan
            @can('sales_orders_view')
                <li class="nav-item">
                    <a href="{{ route('sales.index') }}" class="nav-link{{ request()->is('sales*') ? ' active' : '' }}">
                        <i class="nav-icon fas fa-shopping-bag"></i>
                        <p>Sales Order</p>
                    </a>
                </li>
            @endcan
            @can('purchase_returns_view')
                <li class="nav-item">
                    <a href="{{ route('purchase-returns.index') }}"
                        class="nav-link{{ request()->is('purchase-returns*') ? ' active' : '' }}">
                        <i class="nav-icon fas fa-undo fa-arrow-left text-warning"></i>
                        <p>Retur Pembelian</p>
                    </a>
                </li>
            @endcan
            @can('sales_returns_view')
                <li class="nav-item">
                    <a href="{{ route('sales-returns.index') }}"
                        class="nav-link{{ request()->is('sales-returns*') ? ' active' : '' }}">
                        <i class="nav-icon fas fa-redo fa-arrow-right text-info"></i>
                        <p>Retur Penjualan</p>
                    </a>
                </li>
            @endcan
        </ul>
    </li>
@endcanany

<!-- Master Data Group -->
@canany(['items_view', 'suppliers_view', 'customers_view', 'unit_conversions_view', 'units_view',
    'item_categories_view', 'item_groups_view'])
    <li
        class="nav-item{{ request()->is(['items*', 'suppliers*', 'customers*', 'units*', 'item-categories*', 'item-groups*', 'unit-conversions*']) ? ' menu-open' : '' }}">
        <a href="#"
            class="nav-link{{ request()->is(['items*', 'suppliers*', 'customers*', 'units*', 'item-categories*', 'item-groups*', 'unit-conversions*']) ? ' active' : '' }}">
            <i class="nav-icon fas fa-box-open"></i>
            <p>
                Master Data
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            @can('items_view')
                <li class="nav-item">
                    <a href="{{ route('items.index') }}" class="nav-link{{ request()->is('items*') ? ' active' : '' }}">
                        <i class="nav-icon fas fa-box"></i>
                        <p>Barang</p>
                    </a>
                </li>
            @endcan
            @can('suppliers_view')
                <li class="nav-item">
                    <a href="{{ route('suppliers.index') }}"
                        class="nav-link{{ request()->is('suppliers*') ? ' active' : '' }}">
                        <i class="nav-icon fas fa-truck"></i>
                        <p>Supplier</p>
                    </a>
                </li>
            @endcan
            @can('customers_view')
                <li class="nav-item">
                    <a href="{{ route('customers.index') }}"
                        class="nav-link{{ request()->is('customers*') ? ' active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Customer</p>
                    </a>
                </li>
            @endcan
            @can('unit_conversions_view')
                <li class="nav-item">
                    <a href="{{ route('unit-conversions.index') }}"
                        class="nav-link{{ request()->is('unit-conversions*') ? ' active' : '' }}">
                        <i class="nav-icon fas fa-exchange-alt"></i>
                        <p>Satuan Konversi</p>
                    </a>
                </li>
            @endcan
            @can('units_view')
                <li class="nav-item">
                    <a href="{{ route('units.index') }}"
                        class="nav-link{{ request()->is('units*') && !request()->is('unit-conversions*') ? ' active' : '' }}">
                        <i class="nav-icon fas fa-ruler-combined"></i>
                        <p>Satuan Barang</p>
                    </a>
                </li>
            @endcan
            @can('item_categories_view')
                <li class="nav-item">
                    <a href="{{ route('item-categories.index') }}"
                        class="nav-link{{ request()->is('item-categories*') ? ' active' : '' }}">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>Kategori Barang</p>
                    </a>
                </li>
            @endcan
            @can('item_groups_view')
                <li class="nav-item">
                    <a href="{{ route('item-groups.index') }}"
                        class="nav-link{{ request()->is('item-groups*') ? ' active' : '' }}">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>Group Barang</p>
                    </a>
                </li>
            @endcan
        </ul>
    </li>
@endcanany

<!-- Admin Group -->
@canany(['users_view', 'activity_logs_view'])
    <li class="nav-item{{ request()->is('superadmin/*') ? ' menu-open' : '' }}">
        <a href="#" class="nav-link{{ request()->is('superadmin/*') ? ' active' : '' }}">
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
                        <i class="nav-icon fas fa-users"></i>
                        <p>Manajemen User</p>
                    </a>
                </li>
            @endcan
            @can('activity_logs_view')
                <li class="nav-item">
                    <a href="{{ route('superadmin.activity-logs.index') }}"
                        class="nav-link{{ request()->is('superadmin/activity-logs*') ? ' active' : '' }}">
                        <i class="nav-icon fas fa-book"></i>
                        <p>Log Aktivitas</p>
                    </a>
                </li>
            @endcan
        </ul>
    </li>
@endcanany

<!-- Profil -->
<li class="nav-item">
    <a href="{{ route('profile.index') }}" class="nav-link{{ request()->is('profile*') ? ' active' : '' }}">
        <i class="nav-icon fas fa-user-circle"></i>
        <p>Profil Pengguna</p>
    </a>
</li>
