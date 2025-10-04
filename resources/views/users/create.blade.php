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
                                <label for="jabatan" class="col-md-3 col-form-label"><b>Jabatan</b></label>
                                <div class="col-md-9">
                                    <select class="form-select" id="jabatan" name="jabatan" required>
                                        @php
                                            $jabatanList = [
                                                'staff' => 'Staff',
                                                'karyawan' => 'Karyawan',
                                                'kadept' => 'Kadept - Kepala Depot',
                                                'wakadept' => 'Wakadept - Wakil Kepala Depot',
                                                'kabid' => 'Kabid - Kepala Bidang',
                                                'wakabid' => 'Wakabid - Wakil Kepala Bidang',
                                                'kasubid' => 'Kasubid - Kepala Sub Bidang',
                                                'wakasubid' => 'Wakasubid - Wakil Kepala Sub Bidang',
                                                'kabag' => 'Kabag - Kepala Bagian',
                                                'wakabag' => 'Wakabag - Wakil Kepala Bagian',
                                                'kasubag' => 'Kasubag - Kepala Sub Bagian',
                                                'wakasubag' => 'Wakasubag - Wakil Kepala Sub Bagian',
                                                'kasie' => 'Kasie - Kepala Seksi',
                                                'wakasie' => 'Wakasie - Wakil Kepala Seksi',
                                                'kasubsie' => 'Kasubsie - Kepala Sub Seksi',
                                                'wakasubsie' => 'Wakasubsie - Wakil Kepala Sub Seksi',
                                                'kagu' => 'Kagu - Kepala Regu',
                                                'wakagu' => 'Wakagu - Wakil Kepala Regu',
                                                'kasubgu' => 'Kasubgu - Kepala Sub Regu',
                                                'wakasubgu' => 'Wakasubgu - Wakil Kepala Sub Regu',
                                            ];
                                            $selectedJabatan = old('jabatan', $user['jabatan'] ?? 'staff');
                                        @endphp
                                        @foreach ($jabatanList as $kode => $label)
                                            <option value="{{ $kode }}"
                                                {{ $selectedJabatan == $kode ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="bagian" class="col-md-3 col-form-label"><b>Bagian/Divisi</b></label>
                                <div class="col-md-9">
                                    <select class="form-select" id="bagian" name="bagian" required>
                                        <option value="">- Pilih Bagian/Divisi -</option>
                                        @php
                                            $bagianList = [
                                                'ITS',
                                                'HR',
                                                'Finance',
                                                'Marketing',
                                                'Operasional',
                                                'Purchasing',
                                                'Gudang',
                                                'Penjualan',
                                                'Produksi',
                                                'R&D',
                                                'Quality Control',
                                                'Customer Service',
                                                'Legal',
                                                'Admin',
                                                'Personalia',
                                            ];
                                            $selectedBagian = old('bagian', '');
                                        @endphp
                                        @foreach ($bagianList as $bagian)
                                            <option value="{{ $bagian }}"
                                                {{ $selectedBagian == $bagian ? 'selected' : '' }}>
                                                {{ $bagian }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="role" class="col-md-3 col-form-label"><b>Role</b></label>
                                <div class="col-md-9">
                                    <select class="form-select" id="role" name="role" required>
                                        <option value="useradmin" {{ old('role') == 'useradmin' ? 'selected' : '' }}>
                                            User Admin
                                        </option>
                                        <option value="superadmin" {{ old('role') == 'superadmin' ? 'selected' : '' }}>
                                            Super Admin
                                        </option>
                                        <option value="kasir" {{ old('role') == 'kasir' ? 'selected' : '' }}>
                                            Kasir
                                        </option>
                                        <option value="gudang" {{ old('role') == 'gudang' ? 'selected' : '' }}>
                                            Gudang
                                        </option>
                                        <option value="viewer" {{ old('role') == 'viewer' ? 'selected' : '' }}>
                                            Viewer (Read-Only)
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3" id="permissionGroup">
                                <label class="form-label"><b>Hak Akses Modul</b></label>

                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="30%">Modul</th>
                                                <th width="23%" class="text-center">Tidak Ada Akses</th>
                                                <th width="23%" class="text-center">Read-Only (Lihat)</th>
                                                <th width="24%" class="text-center">Full Akses (Kelola)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Barang -->
                                            <tr>
                                                <td><i class="bi bi-box"></i> <strong>Barang</strong></td>
                                                <td class="text-center">
                                                    <input type="radio" name="barang_access" value="none"
                                                        id="barang_none" class="form-check-input radio-none">
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="barang_access" value="read"
                                                        id="barang_read" class="form-check-input radio-read">
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="barang_access" value="full"
                                                        id="barang_full" class="form-check-input radio-full">
                                                </td>
                                            </tr>

                                            <!-- Supplier -->
                                            <tr>
                                                <td><i class="bi bi-truck"></i> <strong>Supplier</strong></td>
                                                <td class="text-center">
                                                    <input type="radio" name="supplier_access" value="none"
                                                        id="supplier_none" class="form-check-input radio-none">
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="supplier_access" value="read"
                                                        id="supplier_read" class="form-check-input radio-read">
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="supplier_access" value="full"
                                                        id="supplier_full" class="form-check-input radio-full">
                                                </td>
                                            </tr>

                                            <!-- Customer -->
                                            <tr>
                                                <td><i class="bi bi-people"></i> <strong>Customer</strong></td>
                                                <td class="text-center">
                                                    <input type="radio" name="customer_access" value="none"
                                                        id="customer_none" class="form-check-input radio-none">
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="customer_access" value="read"
                                                        id="customer_read" class="form-check-input radio-read">
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="customer_access" value="full"
                                                        id="customer_full" class="form-check-input radio-full">
                                                </td>
                                            </tr>

                                            <!-- Satuan -->
                                            <tr>
                                                <td><i class="bi bi-calculator"></i> <strong>Satuan</strong></td>
                                                <td class="text-center">
                                                    <input type="radio" name="satuan_access" value="none"
                                                        id="satuan_none" class="form-check-input radio-none">
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="satuan_access" value="read"
                                                        id="satuan_read" class="form-check-input radio-read">
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="satuan_access" value="full"
                                                        id="satuan_full" class="form-check-input radio-full">
                                                </td>
                                            </tr>

                                            <!-- Jenis Barang -->
                                            <tr>
                                                <td><i class="bi bi-tags"></i> <strong>Jenis Barang</strong></td>
                                                <td class="text-center">
                                                    <input type="radio" name="jenis_barang_access" value="none"
                                                        id="jenis_barang_none" class="form-check-input radio-none">
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="jenis_barang_access" value="read"
                                                        id="jenis_barang_read" class="form-check-input radio-read">
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="jenis_barang_access" value="full"
                                                        id="jenis_barang_full" class="form-check-input radio-full">
                                                </td>
                                            </tr>

                                            <!-- Group Barang -->
                                            <tr>
                                                <td><i class="bi bi-collection"></i> <strong>Group Barang</strong></td>
                                                <td class="text-center">
                                                    <input type="radio" name="group_barang_access" value="none"
                                                        id="group_barang_none" class="form-check-input radio-none">
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="group_barang_access" value="read"
                                                        id="group_barang_read" class="form-check-input radio-read">
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="group_barang_access" value="full"
                                                        id="group_barang_full" class="form-check-input radio-full">
                                                </td>
                                            </tr>

                                            <!-- Satuan Konversi -->
                                            <tr>
                                                <td><i class="bi bi-arrow-left-right"></i> <strong>Satuan
                                                        Konversi</strong>
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="satuan_konversi_access"
                                                        value="none" id="satuan_konversi_none"
                                                        class="form-check-input radio-none">
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="satuan_konversi_access"
                                                        value="read" id="satuan_konversi_read"
                                                        class="form-check-input radio-read">
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="satuan_konversi_access"
                                                        value="full" id="satuan_konversi_full"
                                                        class="form-check-input radio-full">
                                                </td>
                                            </tr>

                                            <!-- Purchase Order -->
                                            <tr>
                                                <td><i class="bi bi-cart-plus"></i> <strong>Purchase Order</strong>
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="po_access" value="none"
                                                        id="po_none" class="form-check-input radio-none">
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="po_access" value="read"
                                                        id="po_read" class="form-check-input radio-read">
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="po_access" value="full"
                                                        id="po_full" class="form-check-input radio-full">
                                                </td>
                                            </tr>

                                            <!-- Pembelian -->
                                            <tr>
                                                <td><i class="bi bi-bag-plus"></i> <strong>Pembelian</strong></td>
                                                <td class="text-center">
                                                    <input type="radio" name="pembelian_access" value="none"
                                                        id="pembelian_none" class="form-check-input radio-none">
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="pembelian_access" value="read"
                                                        id="pembelian_read" class="form-check-input radio-read">
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="pembelian_access" value="full"
                                                        id="pembelian_full" class="form-check-input radio-full">
                                                </td>
                                            </tr>

                                            <!-- Penjualan -->
                                            <tr>
                                                <td><i class="bi bi-bag-check"></i> <strong>Penjualan</strong></td>
                                                <td class="text-center">
                                                    <input type="radio" name="penjualan_access" value="none"
                                                        id="penjualan_none" class="form-check-input radio-none">
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="penjualan_access" value="read"
                                                        id="penjualan_read" class="form-check-input radio-read">
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="penjualan_access" value="full"
                                                        id="penjualan_full" class="form-check-input radio-full">
                                                </td>
                                            </tr>

                                            <!-- User Management -->
                                            <tr>
                                                <td><i class="bi bi-person-gear"></i> <strong>Kelola User</strong></td>
                                                <td class="text-center">
                                                    <input type="radio" name="user_access" value="none"
                                                        id="user_none" class="form-check-input radio-none">
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="user_access" value="read"
                                                        id="user_read" class="form-check-input radio-read">
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="user_access" value="full"
                                                        id="user_full" class="form-check-input radio-full">
                                                </td>
                                            </tr>

                                            <!-- Logs -->
                                            <tr>
                                                <td><i class="bi bi-file-text"></i> <strong>Log Aktivitas</strong></td>
                                                <td class="text-center">
                                                    <input type="radio" name="logs_access" value="none"
                                                        id="logs_none" class="form-check-input radio-none">
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="logs_access" value="read"
                                                        id="logs_read" class="form-check-input radio-read">
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="logs_access" value="full"
                                                        id="logs_full" class="form-check-input radio-full">
                                                </td>
                                            </tr>

                                            <!-- Transaksi (hanya full akses, tidak ada read) -->
                                            <tr>
                                                <td><i class="bi bi-bar-chart-line"></i> <strong>Transaksi</strong>
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="transaksi_access" value="none"
                                                        id="transaksi_none" class="form-check-input radio-none">
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="transaksi_access" value="read"
                                                        id="transaksi_read" class="form-check-input radio-read">
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="transaksi_access" value="full"
                                                        id="transaksi_full" class="form-check-input radio-full">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Hidden checkboxes for permissions -->
                                <div id="hiddenPermissions" style="display: none;">
                                    @foreach ($permissions as $perm)
                                        : ?>
                                        <input type="checkbox" name="permissions[]" value="{{ $perm['id'] }}"
                                            id="perm_{{ $perm['name'] }}" data-perm-name="{{ $perm['name'] }}">
                                    @endforeach
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <button type="submit" class="btn btn-success"><i class="bi bi-save"></i>
                                    Simpan</button>
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
        // Mapping radio to permissions
        const permissionMapping = {
            barang: {
                read: 'barang_read',
                full: 'barang'
            },
            supplier: {
                read: 'supplier_read',
                full: 'supplier'
            },
            customer: {
                read: 'customer_read',
                full: 'customer'
            },
            satuan: {
                read: 'satuan_read',
                full: 'satuan'
            },
            jenis_barang: {
                read: 'jenis_barang_read',
                full: 'jenis_barang'
            },
            group_barang: {
                read: 'group_barang_read',
                full: 'group_barang'
            },
            satuan_konversi: {
                read: 'satuan_konversi_read',
                full: 'satuan_konversi'
            },
            po: {
                read: 'purchase_order_read',
                full: 'purchase_order'
            },
            pembelian: {
                read: 'pembelian_read',
                full: 'pembelian'
            },
            penjualan: {
                read: 'penjualan_read',
                full: 'penjualan'
            },
            user: {
                read: 'user_read',
                full: 'user'
            },
            logs: {
                read: 'aktivitas_logs_read',
                full: 'aktivitas_logs'
            },
            transaksi: {
                read: 'transaksi_read',
                full: 'transaksi'
            }
        };

        // Role presets
        const rolePresets = {
            superadmin: {
                barang: 'full',
                supplier: 'full',
                customer: 'full',
                satuan: 'full',
                jenis_barang: 'full',
                group_barang: 'full',
                satuan_konversi: 'full',
                po: 'full',
                pembelian: 'full',
                penjualan: 'full',
                user: 'full',
                logs: 'full',
                transaksi: 'full',
            },
            useradmin: {
                barang: 'read',
                supplier: 'read',
                customer: 'read',
                satuan: 'read',
                jenis_barang: 'read',
                group_barang: 'read',
                satuan_konversi: 'read',
                po: 'read',
                pembelian: 'read',
                penjualan: 'read',
                user: 'read',
                logs: 'read',
                transaksi: 'read',
            },
            kasir: {
                barang: 'read',
                supplier: 'none',
                customer: 'read',
                satuan: 'read',
                jenis_barang: 'none',
                group_barang: 'none',
                satuan_konversi: 'none',
                po: 'none',
                pembelian: 'none',
                penjualan: 'full',
                user: 'none',
                logs: 'none',
                transaksi: 'full',
            },
            gudang: {
                barang: 'full',
                supplier: 'full',
                customer: 'read',
                satuan: 'full',
                jenis_barang: 'full',
                group_barang: 'full',
                satuan_konversi: 'full',
                po: 'full',
                pembelian: 'full',
                penjualan: 'read',
                user: 'none',
                logs: 'none',
                transaksi: 'none',
            },
            viewer: {
                barang: 'read',
                supplier: 'read',
                customer: 'read',
                satuan: 'read',
                jenis_barang: 'read',
                group_barang: 'read',
                satuan_konversi: 'read',
                po: 'read',
                pembelian: 'read',
                penjualan: 'read',
                user: 'read',
                logs: 'read',
                transaksi: 'read',
            }
        };

        function setPermissionsByRole() {
            const role = document.getElementById('role').value;
            const preset = rolePresets[role];

            if (!preset) return;

            // Reset all permissions
            document.querySelectorAll('#hiddenPermissions input[type="checkbox"]').forEach(cb => {
                cb.checked = false;
            });

            // Set radio buttons and permissions based on role
            Object.keys(preset).forEach(module => {
                const level = preset[module];

                // Set radio button
                const radioBtn = document.querySelector(`input[name="${module}_access"][value="${level}"]`);
                if (radioBtn) {
                    radioBtn.checked = true;
                }

                // Set hidden permission checkbox
                updatePermissionCheckbox(module, level);
            });

            // Logic disable radio
            document.querySelectorAll('input[type="radio"]').forEach(radio => {
                // Superadmin: disable semua radio
                if (role === 'superadmin') {
                    radio.disabled = true;
                }
                // Viewer: disable semua radio
                else if (role === 'viewer') {
                    radio.disabled = true;
                }
                // Role lain: radio tetap aktif (bisa diubah)
                else {
                    radio.disabled = false;
                }
            });

            // Tombol aksi (Simpan, Batal) tetap aktif untuk semua role
            document.querySelectorAll('.btn-action').forEach(btn => {
                btn.disabled = false;
            });
        }

        function updatePermissionCheckbox(module, level) {
            const mapping = permissionMapping[module];
            if (!mapping) return;

            // Uncheck all related permissions first
            Object.values(mapping).forEach(permName => {
                const checkbox = document.querySelector(`#perm_${permName}`);
                if (checkbox) checkbox.checked = false;
            });

            // Check specific permission based on level
            if (level !== 'none' && mapping[level]) {
                const checkbox = document.querySelector(`#perm_${mapping[level]}`);
                if (checkbox) checkbox.checked = true;
            }
        }

        // Event listeners for radio buttons
        document.querySelectorAll('input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const name = this.name.replace('_access', '');
                const level = this.value;
                updatePermissionCheckbox(name, level);
            });
        });

        // Role change event
        document.getElementById('role').addEventListener('change', setPermissionsByRole);

        // Initialize
        setPermissionsByRole();
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
                    icon: 'error',
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
                Toast.fire({
                    title: '{{ implode('<br>', array_map('esc', session('validation')->getErrors())) }}'
                });
            });
        </script>
    @endif

    <style>
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            text-align: center;
        }

        .table td {
            vertical-align: middle !important;
            text-align: center !important;
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
        }

        .form-check-input[type="radio"] {
            display: block;
            margin: 0 auto;
            position: relative;
            transform: translateY(0%);
            box-shadow: none !important;
        }

        .form-check-input.radio-none:checked {
            background-color: #dc3545 !important;
            border-color: #dc3545 !important;
        }

        .form-check-input.radio-read:checked {
            background-color: #ffc107 !important;
            border-color: #ffc107 !important;
        }

        .form-check-input.radio-full:checked {
            background-color: #198754 !important;
            border-color: #198754 !important;
        }

        .table-responsive {
            border-radius: 0.375rem;
            overflow: hidden;
        }

        .table {
            margin-bottom: 0;
        }
    </style>

</x-app-layout>
