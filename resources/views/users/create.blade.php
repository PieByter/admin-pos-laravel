{{-- filepath: c:\laragon\www\admin-pos\resources\views\users\create.blade.php --}}
<x-app-layout>
    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white text-center">
                        <h5 class="card-title mb-0"><i class="bi bi-person-plus"></i> Form Tambah User Baru</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('superadmin.users.store') }}" method="post">
                            @csrf

                            <div class="row mb-3 align-items-center">
                                <label for="username" class="col-md-3 col-form-label"><b>Username</b></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="username" name="username"
                                        value="{{ old('username') }}" required>
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="email" class="col-md-3 col-form-label"><b>Email</b></label>
                                <div class="col-md-9">
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email') }}" required>
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="password" class="col-md-3 col-form-label"><b>Password</b></label>
                                <div class="col-md-9">
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="position" class="col-md-3 col-form-label"><b>Posisi</b></label>
                                <div class="col-md-9">
                                    <select class="form-select" id="position" name="position" required>
                                        <option value="">- Pilih Posisi -</option>
                                        @php
                                            $positionList = [
                                                'staff' => 'Staff',
                                                'supervisor' => 'Supervisor',
                                                'manager' => 'Manager',
                                                'assistant_manager' => 'Assistant Manager',
                                                'team_lead' => 'Team Lead',
                                                'coordinator' => 'Coordinator',
                                                'executive' => 'Executive',
                                            ];
                                        @endphp
                                        @foreach ($positionList as $value => $label)
                                            <option value="{{ $value }}"
                                                {{ old('position') == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="job_title" class="col-md-3 col-form-label"><b>Jabatan Fungsional</b></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="job_title" name="job_title"
                                        value="{{ old('job_title') }}"
                                        placeholder="Contoh: AI Engineer, Web Developer, System Analyst">
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="division" class="col-md-3 col-form-label"><b>Bagian/Divisi</b></label>
                                <div class="col-md-9">
                                    <select class="form-select" id="division" name="division" required>
                                        <option value="">- Pilih Bagian/Divisi -</option>
                                        @php
                                            $divisionList = [
                                                'IT',
                                                'HR',
                                                'Finance',
                                                'Marketing',
                                                'Operasional',
                                                'Purchasing',
                                                'Warehouse',
                                                'Sales',
                                                'Production',
                                                'R&D',
                                                'Quality Control',
                                                'Customer Service',
                                                'Legal',
                                                'Administration',
                                                'Personalia',
                                            ];
                                        @endphp
                                        @foreach ($divisionList as $division)
                                            <option value="{{ $division }}"
                                                {{ old('division') == $division ? 'selected' : '' }}>
                                                {{ $division }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="roles" class="col-md-3 col-form-label"><b>Role</b></label>
                                <div class="col-md-9">
                                    <select class="form-select" id="roles" name="roles[]" multiple>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}"
                                                {{ in_array($role->id, old('roles', [])) ? 'selected' : '' }}>
                                                {{ ucfirst($role->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Tahan Ctrl/Cmd untuk memilih multiple role</small>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><b>Permissions (Hak Akses)</b></label>

                                <!-- Quick Role Presets -->
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-2">Quick Preset:</small>
                                    <button type="button" class="btn btn-sm btn-outline-primary me-2"
                                        onclick="setPreset('superadmin')">Super Admin</button>
                                    <button type="button" class="btn btn-sm btn-outline-info me-2"
                                        onclick="setPreset('admin')">Admin</button>
                                    <button type="button" class="btn btn-sm btn-outline-warning me-2"
                                        onclick="setPreset('cashier')">Cashier</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary me-2"
                                        onclick="setPreset('warehouse')">Warehouse</button>
                                    <button type="button" class="btn btn-sm btn-outline-success me-2"
                                        onclick="setPreset('viewer')">Viewer</button>
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="clearAll()">Clear All</button>
                                </div>

                                <div class="row">
                                    @php
                                        $modules = [
                                            'items' => ['label' => 'Items (Barang)', 'icon' => 'bi-box'],
                                            'suppliers' => ['label' => 'Suppliers', 'icon' => 'bi-truck'],
                                            'customers' => ['label' => 'Customers', 'icon' => 'bi-people'],
                                            'units' => ['label' => 'Units (Satuan)', 'icon' => 'bi-calculator'],
                                            'item_categories' => ['label' => 'Item Categories', 'icon' => 'bi-tags'],
                                            'item_groups' => ['label' => 'Item Groups', 'icon' => 'bi-collection'],
                                            'unit_conversions' => [
                                                'label' => 'Unit Conversions',
                                                'icon' => 'bi-arrow-left-right',
                                            ],
                                            'purchase_orders' => [
                                                'label' => 'Purchase Orders',
                                                'icon' => 'bi-cart-plus',
                                            ],
                                            'purchases' => ['label' => 'Purchases', 'icon' => 'bi-bag-plus'],
                                            'sales' => ['label' => 'Sales', 'icon' => 'bi-bag-check'],
                                            'purchase_returns' => [
                                                'label' => 'Purchase Returns',
                                                'icon' => 'bi-arrow-return-left',
                                            ],
                                            'sales_returns' => [
                                                'label' => 'Sales Returns',
                                                'icon' => 'bi-arrow-return-right',
                                            ],
                                            'users' => ['label' => 'Users Management', 'icon' => 'bi-person-gear'],
                                            'activity_logs' => ['label' => 'Activity Logs', 'icon' => 'bi-file-text'],
                                            'transactions' => [
                                                'label' => 'Transactions',
                                                'icon' => 'bi-bar-chart-line',
                                            ],
                                        ];
                                        $actions = ['view', 'create', 'update', 'delete'];
                                    @endphp

                                    @foreach ($modules as $module => $moduleData)
                                        <div class="col-md-6 mb-3">
                                            <div class="card border-light">
                                                <div class="card-header py-2 bg-light">
                                                    <h6 class="mb-0">
                                                        <i class="{{ $moduleData['icon'] }}"></i>
                                                        {{ $moduleData['label'] }}
                                                    </h6>
                                                </div>
                                                <div class="card-body py-2">
                                                    <div class="row">
                                                        @foreach ($actions as $action)
                                                            @php
                                                                $permissionName = $module . '_' . $action;
                                                                $permission = $permissions
                                                                    ->where('name', $permissionName)
                                                                    ->first();
                                                            @endphp
                                                            @if ($permission)
                                                                <div class="col-6 mb-2">
                                                                    <div class="form-check">
                                                                        <input
                                                                            class="form-check-input permission-checkbox"
                                                                            type="checkbox" name="permissions[]"
                                                                            value="{{ $permission->id }}"
                                                                            id="perm_{{ $permission->id }}"
                                                                            data-module="{{ $module }}"
                                                                            data-action="{{ $action }}"
                                                                            {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                                                        <label class="form-check-label small"
                                                                            for="perm_{{ $permission->id }}">
                                                                            {{ ucfirst($action) }}
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                    <!-- Select All Module Button -->
                                                    <div class="mt-2">
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-secondary"
                                                            onclick="toggleModule('{{ $module }}')">
                                                            Select All {{ $moduleData['label'] }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Global Actions -->
                                <div class="mt-3">
                                    <small class="text-muted d-block mb-2">Global Actions:</small>
                                    <button type="button" class="btn btn-sm btn-outline-success me-2"
                                        onclick="selectAllAction('view')">Select All View</button>
                                    <button type="button" class="btn btn-sm btn-outline-info me-2"
                                        onclick="selectAllAction('create')">Select All Create</button>
                                    <button type="button" class="btn btn-sm btn-outline-warning me-2"
                                        onclick="selectAllAction('update')">Select All Update</button>
                                    <button type="button" class="btn btn-sm btn-outline-danger me-2"
                                        onclick="selectAllAction('delete')">Select All Delete</button>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-save"></i> Simpan
                                </button>
                                <a href="{{ route('superadmin.users.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-lg"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Permission presets
        const presets = {
            superadmin: [
                'items_view', 'items_create', 'items_update', 'items_delete',
                'suppliers_view', 'suppliers_create', 'suppliers_update', 'suppliers_delete',
                'customers_view', 'customers_create', 'customers_update', 'customers_delete',
                'units_view', 'units_create', 'units_update', 'units_delete',
                'item_categories_view', 'item_categories_create', 'item_categories_update',
                'item_categories_delete',
                'item_groups_view', 'item_groups_create', 'item_groups_update', 'item_groups_delete',
                'unit_conversions_view', 'unit_conversions_create', 'unit_conversions_update',
                'unit_conversions_delete',
                'purchase_orders_view', 'purchase_orders_create', 'purchase_orders_update',
                'purchase_orders_delete',
                'purchases_view', 'purchases_create', 'purchases_update', 'purchases_delete',
                'sales_view', 'sales_create', 'sales_update', 'sales_delete',
                'purchase_returns_view', 'purchase_returns_create', 'purchase_returns_update',
                'purchase_returns_delete',
                'sales_returns_view', 'sales_returns_create', 'sales_returns_update', 'sales_returns_delete',
                'users_view', 'users_create', 'users_update', 'users_delete',
                'activity_logs_view', 'activity_logs_create', 'activity_logs_update', 'activity_logs_delete',
                'transactions_view', 'transactions_create', 'transactions_update', 'transactions_delete'
            ],
            admin: [
                'items_view', 'items_create', 'items_update',
                'suppliers_view', 'suppliers_create', 'suppliers_update',
                'customers_view', 'customers_create', 'customers_update',
                'units_view', 'units_create', 'units_update',
                'item_categories_view', 'item_categories_create', 'item_categories_update',
                'item_groups_view', 'item_groups_create', 'item_groups_update',
                'purchase_orders_view', 'purchase_orders_create', 'purchase_orders_update',
                'purchases_view', 'purchases_create', 'purchases_update',
                'sales_view', 'sales_create', 'sales_update',
                'users_view',
                'activity_logs_view',
                'transactions_view'
            ],
            cashier: [
                'items_view',
                'customers_view', 'customers_create', 'customers_update',
                'sales_view', 'sales_create', 'sales_update',
                'transactions_view'
            ],
            warehouse: [
                'items_view', 'items_create', 'items_update',
                'suppliers_view', 'suppliers_create', 'suppliers_update',
                'units_view', 'units_create', 'units_update',
                'item_categories_view', 'item_categories_create', 'item_categories_update',
                'item_groups_view', 'item_groups_create', 'item_groups_update',
                'unit_conversions_view', 'unit_conversions_create', 'unit_conversions_update',
                'purchase_orders_view', 'purchase_orders_create', 'purchase_orders_update',
                'purchases_view', 'purchases_create', 'purchases_update',
                'purchase_returns_view', 'purchase_returns_create', 'purchase_returns_update'
            ],
            viewer: [
                'items_view', 'suppliers_view', 'customers_view', 'units_view',
                'item_categories_view', 'item_groups_view', 'unit_conversions_view',
                'purchase_orders_view', 'purchases_view', 'sales_view',
                'purchase_returns_view', 'sales_returns_view', 'users_view',
                'activity_logs_view', 'transactions_view'
            ]
        };

        function setPreset(presetName) {
            // Clear all checkboxes first
            document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = false);

            // Check permissions in preset
            if (presets[presetName]) {
                presets[presetName].forEach(permName => {
                    const checkbox = document.querySelector(
                        `[data-module="${permName.split('_')[0]}"][data-action="${permName.split('_').slice(1).join('_')}"]`
                    );
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                });
            }
        }

        function clearAll() {
            document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = false);
        }

        function toggleModule(module) {
            const moduleCheckboxes = document.querySelectorAll(`[data-module="${module}"]`);
            const allChecked = Array.from(moduleCheckboxes).every(cb => cb.checked);

            moduleCheckboxes.forEach(cb => {
                cb.checked = !allChecked;
            });
        }

        function selectAllAction(action) {
            const actionCheckboxes = document.querySelectorAll(`[data-action="${action}"]`);
            const allChecked = Array.from(actionCheckboxes).every(cb => cb.checked);

            actionCheckboxes.forEach(cb => {
                cb.checked = !allChecked;
            });
        }
    </script>

    @if (session()->has('validation'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true,
                    icon: 'error'
                });
                Toast.fire({
                    title: '{{ implode('<br>', session('validation')->all()) }}'
                });
            });
        </script>
    @endif
</x-app-layout>
