<!-- filepath: resources/views/layouts/partials/sidebar-menu.blade.php -->
<!-- Dashboard -->
{{-- @php(app()->make(\Spatie\Permission\PermissionRegistrar::class)->initializeCache()) --}}
<li class="nav-item">
    <a href="{{ url('dashboard') }}" class="nav-link{{ request()->is('dashboard') ? ' active' : '' }}">
        <i class="nav-icon bi bi-speedometer2"></i>
        <p>Dashboard</p>
    </a>
</li>

<!-- Transaksi Group -->
@if (in_array('pre_purchase_orders_view', $permissions) ||
        in_array('purchase_orders_view', $permissions) ||
        in_array('sales_orders_view', $permissions) ||
        in_array('transactions_view', $permissions))
    <li class="nav-item{{ request()->is(['po*', 'pembelian*', 'penjualan*', 'transaksi*']) ? ' menu-open' : '' }}">
        <a href="#"
            class="nav-link{{ request()->is(['po*', 'pembelian*', 'penjualan*', 'transaksi*']) ? ' active' : '' }}">
            <i class="nav-icon bi bi-cash-coin"></i>
            <p>
                Transaksi
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            @can('transactions_view')
                <li class="nav-item">
                    <a href="{{ route('transactions.index') }}"
                        class="nav-link{{ request()->is('transaksi*') ? ' active' : '' }}">
                        <i class="nav-icon bi bi-cash-stack"></i>
                        <p>Akumulasi Transaksi</p>
                    </a>
                </li>
            @endcan
            @if (in_array('pre_purchase_orders_view', $permissions))
                <li class="nav-item">
                    <a href="{{ url('po') }}" class="nav-link{{ request()->is('po*') ? ' active' : '' }}">
                        <i class="nav-icon bi bi-file-earmark-text"></i>
                        <p>Purchase Order</p>
                    </a>
                </li>
            @endif
            @if (in_array('purchase_orders_view', $permissions))
                <li class="nav-item">
                    <a href="{{ url('pembelian') }}" class="nav-link{{ request()->is('pembelian*') ? ' active' : '' }}">
                        <i class="nav-icon bi bi-cart-plus"></i>
                        <p>Pembelian</p>
                    </a>
                </li>
            @endif
            @if (in_array('sales_orders_view', $permissions))
                <li class="nav-item">
                    <a href="{{ url('penjualan') }}"
                        class="nav-link{{ request()->is('penjualan*') ? ' active' : '' }}">
                        <i class="nav-icon bi bi-cash-coin"></i>
                        <p>Penjualan</p>
                    </a>
                </li>
            @endif
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
            <i class="nav-icon bi bi-box-seam"></i>
            <p>
                Master Data
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            @if (in_array('items_view', $permissions))
                <li class="nav-item">
                    <a href="{{ url('barang') }}" class="nav-link{{ request()->is('barang*') ? ' active' : '' }}">
                        <i class="nav-icon bi bi-box"></i>
                        <p>Barang</p>
                    </a>
                </li>
            @endif
            @if (in_array('suppliers_view', $permissions))
                <li class="nav-item">
                    <a href="{{ url('supplier') }}" class="nav-link{{ request()->is('supplier*') ? ' active' : '' }}">
                        <i class="nav-icon bi bi-truck"></i>
                        <p>Supplier</p>
                    </a>
                </li>
            @endif
            @if (in_array('customers_view', $permissions))
                <li class="nav-item">
                    <a href="{{ url('customer') }}" class="nav-link{{ request()->is('customer*') ? ' active' : '' }}">
                        <i class="nav-icon bi bi-people"></i>
                        <p>Customer</p>
                    </a>
                </li>
            @endif
            @if (in_array('unit_conversions_view', $permissions))
                <li class="nav-item">
                    <a href="{{ url('satuan-konversi') }}"
                        class="nav-link{{ request()->is('satuan-konversi*') ? ' active' : '' }}">
                        <i class="nav-icon bi bi-currency-exchange"></i>
                        <p>Satuan Konversi</p>
                    </a>
                </li>
            @endif
            @if (in_array('units_view', $permissions))
                <li class="nav-item">
                    <a href="{{ url('satuan') }}"
                        class="nav-link{{ request()->is('satuan*') && !request()->is('satuan-konversi*') ? ' active' : '' }}">
                        <i class="nav-icon bi bi-grid"></i>
                        <p>Satuan Barang</p>
                    </a>
                </li>
            @endif
            @if (in_array('item_categories_view', $permissions))
                <li class="nav-item">
                    <a href="{{ url('jenis-barang') }}"
                        class="nav-link{{ request()->is('jenis-barang*') ? ' active' : '' }}">
                        <i class="nav-icon bi bi-diagram-2"></i>
                        <p>Jenis Barang</p>
                    </a>
                </li>
            @endif
            @if (in_array('item_groups_view', $permissions))
                <li class="nav-item">
                    <a href="{{ url('group-barang') }}"
                        class="nav-link{{ request()->is('group-barang*') ? ' active' : '' }}">
                        <i class="nav-icon bi bi-boxes"></i>
                        <p>Group Barang</p>
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif

<!-- Admin Group -->
@if (in_array('users_view', $permissions) || in_array('activity_logs_view', $permissions))
    <li class="nav-item{{ request()->is('superadmin/*') ? ' menu-open' : '' }}">
        <a href="#" class="nav-link{{ request()->is('superadmin/*') ? ' active' : '' }}">
            <i class="nav-icon bi bi-person-gear"></i>
            <p>
                Admin & Log
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            @if (in_array('users_view', $permissions))
                <li class="nav-item">
                    <a href="{{ url('superadmin/users') }}"
                        class="nav-link{{ request()->is('superadmin/users*') ? ' active' : '' }}">
                        <i class="nav-icon bi bi-person-fill-gear"></i>
                        <p>Manajemen User</p>
                    </a>
                </li>
            @endif
            @if (in_array('activity_logs_view', $permissions))
                <li class="nav-item">
                    <a href="{{ url('superadmin/logs') }}"
                        class="nav-link{{ request()->is('superadmin/logs*') ? ' active' : '' }}">
                        <i class="nav-icon bi bi-journal-text"></i>
                        <p>Log Aktivitas</p>
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif

<!-- Profil -->
<li class="nav-item">
    <a href="{{ url('profile') }}" class="nav-link{{ request()->is('profile*') ? ' active' : '' }}">
        <i class="nav-icon bi bi-person-circle"></i>
        <p>Profil Pengguna</p>
    </a>
</li>
