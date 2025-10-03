<x-app-layout>

    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white text-center">
                        <h5 class="card-title mb-0"><i class="bi bi-person-gear"></i> Form Edit User</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('superadmin.users.update', $user['id']) }}" method="post">
                            @csrf
                            <div class="row mb-3 align-items-center">
                                <label for="username" class="col-md-3 col-form-label"><b>Username</b></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="username" name="username"
                                        value="{{ old('username', $user['username']) }}" required>
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="email" class="col-md-3 col-form-label"><b>Email</b></label>
                                <div class="col-md-9">
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email', $user['email']) }}" required>
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="password" class="col-md-3 col-form-label"><b>Password (isi jika ingin
                                        ganti)</b></label>
                                <div class="col-md-9">
                                    <input type="password" class="form-control" id="password" name="password">
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
                                            $selectedBagian = old('bagian', $user['bagian'] ?? '');
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
                                        <option value="useradmin"
                                            {{ old('role', $user['role']) == 'useradmin' ? 'selected' : '' }}>
                                            User Admin
                                        </option>
                                        <option value="superadmin"
                                            {{ old('role', $user['role']) == 'superadmin' ? 'selected' : '' }}>
                                            Super Admin
                                        </option>
                                        <option value="kasir"
                                            {{ old('role', $user['role']) == 'kasir' ? 'selected' : '' }}>
                                            Kasir
                                        </option>
                                        <option value="gudang"
                                            {{ old('role', $user['role']) == 'gudang' ? 'selected' : '' }}>
                                            Gudang
                                        </option>
                                        <option value="viewer"
                                            {{ old('role', $user['role']) == 'viewer' ? 'selected' : '' }}>
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
                                            @php
                                                // Get permission names dari user_permissions untuk check current state
                                                $currentPermissions = [];
                                                foreach ($permissions as $perm) {
                                                    if (in_array($perm['id'], $user_permissions)) {
                                                        $currentPermissions[] = $perm['name'];
                                                    }
                                                }

                                                // Helper function untuk check radio status
                                                function getRadioStatus($currentPerms, $readPerm, $fullPerm)
                                                {
                                                    if (in_array($fullPerm, $currentPerms)) {
                                                        return 'full';
                                                    }
                                                    if (in_array($readPerm, $currentPerms)) {
                                                        return 'read';
                                                    }
                                                    return 'none';
                                                }
                                            @endphp

                                            <!-- Barang -->
                                            <tr>
                                                <td><i class="bi bi-box"></i> <strong>Barang</strong></td>
                                                <td class="text-center">
                                                    <input type="radio" name="barang_access" value="none"
                                                        id="barang_none" class="form-check-input radio-none"
                                                        {{ getRadioStatus($currentPermissions, 'barang_read', 'barang') == 'none' ? 'checked' : '' }}>
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="barang_access" value="read"
                                                        id="barang_read" class="form-check-input radio-read"
                                                        {{ getRadioStatus($currentPermissions, 'barang_read', 'barang') == 'read' ? 'checked' : '' }}>
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="barang_access" value="full"
                                                        id="barang_full" class="form-check-input radio-full"
                                                        {{ getRadioStatus($currentPermissions, 'barang_read', 'barang') == 'full' ? 'checked' : '' }}>
                                                </td>
                                            </tr>

                                            <!-- Supplier -->
                                            <tr>
                                                <td><i class="bi bi-truck"></i> <strong>Supplier</strong></td>
                                                <td class="text-center">
                                                    <input type="radio" name="supplier_access" value="none"
                                                        id="supplier_none" class="form-check-input radio-none"
                                                        {{ getRadioStatus($currentPermissions, 'supplier_read', 'supplier') == 'none' ? 'checked' : '' }}>
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="supplier_access" value="read"
                                                        id="supplier_read" class="form-check-input radio-read"
                                                        {{ getRadioStatus($currentPermissions, 'supplier_read', 'supplier') == 'read' ? 'checked' : '' }}>
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="supplier_access" value="full"
                                                        id="supplier_full" class="form-check-input radio-full"
                                                        {{ getRadioStatus($currentPermissions, 'supplier_read', 'supplier') == 'full' ? 'checked' : '' }}>
                                                </td>
                                            </tr>

                                            <!-- Customer -->
                                            <tr>
                                                <td><i class="bi bi-people"></i> <strong>Customer</strong></td>
                                                <td class="text-center">
                                                    <input type="radio" name="customer_access" value="none"
                                                        id="customer_none" class="form-check-input radio-none"
                                                        {{ getRadioStatus($currentPermissions, 'customer_read', 'customer') == 'none' ? 'checked' : '' }}>
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="customer_access" value="read"
                                                        id="customer_read" class="form-check-input radio-read"
                                                        {{ getRadioStatus($currentPermissions, 'customer_read', 'customer') == 'read' ? 'checked' : '' }}>
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="customer_access" value="full"
                                                        id="customer_full" class="form-check-input radio-full"
                                                        {{ getRadioStatus($currentPermissions, 'customer_read', 'customer') == 'full' ? 'checked' : '' }}>
                                                </td>
                                            </tr>

                                            <!-- Satuan -->
                                            <tr>
                                                <td><i class="bi bi-calculator"></i> <strong>Satuan</strong></td>
                                                <td class="text-center">
                                                    <input type="radio" name="satuan_access" value="none"
                                                        id="satuan_none" class="form-check-input radio-none"
                                                        {{ getRadioStatus($currentPermissions, 'satuan_read', 'satuan') == 'none' ? 'checked' : '' }}>
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="satuan_access" value="read"
                                                        id="satuan_read" class="form-check-input radio-read"
                                                        {{ getRadioStatus($currentPermissions, 'satuan_read', 'satuan') == 'read' ? 'checked' : '' }}>
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="satuan_access" value="full"
                                                        id="satuan_full" class="form-check-input radio-full"
                                                        {{ getRadioStatus($currentPermissions, 'satuan_read', 'satuan') == 'full' ? 'checked' : '' }}>
                                                </td>
                                            </tr>

                                            <!-- Jenis Barang -->
                                            <tr>
                                                <td><i class="bi bi-tags"></i> <strong>Jenis Barang</strong></td>
                                                <td class="text-center">
                                                    <input type="radio" name="jenis_barang_access" value="none"
                                                        id="jenis_barang_none" class="form-check-input radio-none"
                                                        {{ getRadioStatus($currentPermissions, 'jenis_barang_read', 'jenis_barang') == 'none' ? 'checked' : '' }}>
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="jenis_barang_access" value="read"
                                                        id="jenis_barang_read" class="form-check-input radio-read"
                                                        {{ getRadioStatus($currentPermissions, 'jenis_barang_read', 'jenis_barang') == 'read' ? 'checked' : '' }}>
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="jenis_barang_access" value="full"
                                                        id="jenis_barang_full" class="form-check-input radio-full"
                                                        {{ getRadioStatus($currentPermissions, 'jenis_barang_read', 'jenis_barang') == 'full' ? 'checked' : '' }}>
                                                </td>
                                            </tr>

                                            <!-- Group Barang -->
                                            <tr>
                                                <td><i class="bi bi-collection"></i> <strong>Group Barang</strong></td>
                                                <td class="text-center">
                                                    <input type="radio" name="group_barang_access" value="none"
                                                        id="group_barang_none" class="form-check-input radio-none"
                                                        {{ getRadioStatus($currentPermissions, 'group_barang_read', 'group_barang') == 'none' ? 'checked' : '' }}>
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="group_barang_access" value="read"
                                                        id="group_barang_read" class="form-check-input radio-read"
                                                        {{ getRadioStatus($currentPermissions, 'group_barang_read', 'group_barang') == 'read' ? 'checked' : '' }}>
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="group_barang_access" value="full"
                                                        id="group_barang_full" class="form-check-input radio-full"
                                                        {{ getRadioStatus($currentPermissions, 'group_barang_read', 'group_barang') == 'full' ? 'checked' : '' }}>
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
                                                        class="form-check-input radio-none"
                                                        {{ getRadioStatus($currentPermissions, 'satuan_konversi_read', 'satuan_konversi') == 'none' ? 'checked' : '' }}>
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="satuan_konversi_access"
                                                        value="read" id="satuan_konversi_read"
                                                        class="form-check-input radio-read"
                                                        {{ getRadioStatus($currentPermissions, 'satuan_konversi_read', 'satuan_konversi') == 'read' ? 'checked' : '' }}>
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="satuan_konversi_access"
                                                        value="full" id="satuan_konversi_full"
                                                        class="form-check-input radio-full"
                                                        {{ getRadioStatus($currentPermissions, 'satuan_konversi_read', 'satuan_konversi') == 'full' ? 'checked' : '' }}>
                                                </td>
                                            </tr>

                                            <!-- Purchase Order -->
                                            <tr>
                                                <td><i class="bi bi-cart-plus"></i> <strong>Purchase Order</strong>
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="po_access" value="none"
                                                        id="po_none" class="form-check-input radio-none"
                                                        {{ getRadioStatus($currentPermissions, 'purchase_order_read', 'purchase_order') == 'none' ? 'checked' : '' }}>
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="po_access" value="read"
                                                        id="po_read" class="form-check-input radio-read"
                                                        {{ getRadioStatus($currentPermissions, 'purchase_order_read', 'purchase_order') == 'read' ? 'checked' : '' }}>
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="po_access" value="full"
                                                        id="po_full" class="form-check-input radio-full"
                                                        {{ getRadioStatus($currentPermissions, 'purchase_order_read', 'purchase_order') == 'full' ? 'checked' : '' }}>
                                                </td>
                                            </tr>

                                            <!-- Pembelian -->
                                            <tr>
                                                <td><i class="bi bi-bag-plus"></i> <strong>Pembelian</strong></td>
                                                <td class="text-center">
                                                    <input type="radio" name="pembelian_access" value="none"
                                                        id="pembelian_none" class="form-check-input radio-none"
                                                        {{ getRadioStatus($currentPermissions, 'pembelian_read', 'pembelian') == 'none' ? 'checked' : '' }}>
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="pembelian_access" value="read"
                                                        id="pembelian_read" class="form-check-input radio-read"
                                                        {{ getRadioStatus($currentPermissions, 'pembelian_read', 'pembelian') == 'read' ? 'checked' : '' }}>
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="pembelian_access" value="full"
                                                        id="pembelian_full" class="form-check-input radio-full"
                                                        {{ getRadioStatus($currentPermissions, 'pembelian_read', 'pembelian') == 'full' ? 'checked' : '' }}>
                                                </td>
                                            </tr>

                                            <!-- Penjualan -->
                                            <tr>
                                                <td><i class="bi bi-bag-check"></i> <strong>Penjualan</strong></td>
                                                <td class="text-center">
                                                    <input type="radio" name="penjualan_access" value="none"
                                                        id="penjualan_none" class="form-check-input radio-none"
                                                        {{ getRadioStatus($currentPermissions, 'penjualan_read', 'penjualan') == 'none' ? 'checked' : '' }}>
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="penjualan_access" value="read"
                                                        id="penjualan_read" class="form-check-input radio-read"
                                                        {{ getRadioStatus($currentPermissions, 'penjualan_read', 'penjualan') == 'read' ? 'checked' : '' }}>
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="penjualan_access" value="full"
                                                        id="penjualan_full" class="form-check-input radio-full"
                                                        {{ getRadioStatus($currentPermissions, 'penjualan_read', 'penjualan') == 'full' ? 'checked' : '' }}>
                                                </td>
                                            </tr>

                                            <!-- User Management -->
                                            <tr>
                                                <td><i class="bi bi-person-gear"></i> <strong>Kelola User</strong></td>
                                                <td class="text-center">
                                                    <input type="radio" name="user_access" value="none"
                                                        id="user_none" class="form-check-input radio-none"
                                                        {{ getRadioStatus($currentPermissions, 'user_read', 'user') == 'none' ? 'checked' : '' }}>
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="user_access" value="read"
                                                        id="user_read" class="form-check-input radio-read"
                                                        {{ getRadioStatus($currentPermissions, 'user_read', 'user') == 'read' ? 'checked' : '' }}>
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="user_access" value="full"
                                                        id="user_full" class="form-check-input radio-full"
                                                        {{ getRadioStatus($currentPermissions, 'user_read', 'user') == 'full' ? 'checked' : '' }}>
                                                </td>
                                            </tr>

                                            <!-- Logs -->
                                            <tr>
                                                <td><i class="bi bi-file-text"></i> <strong>Log Aktivitas</strong></td>
                                                <td class="text-center">
                                                    <input type="radio" name="logs_access" value="none"
                                                        id="logs_none" class="form-check-input radio-none"
                                                        {{ getRadioStatus($currentPermissions, 'aktivitas_logs_read', 'aktivitas_logs') == 'none' ? 'checked' : '' }}>
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="logs_access" value="read"
                                                        id="logs_read" class="form-check-input radio-read"
                                                        {{ getRadioStatus($currentPermissions, 'aktivitas_logs_read', 'aktivitas_logs') == 'read' ? 'checked' : '' }}>
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="logs_access" value="full"
                                                        id="logs_full" class="form-check-input radio-full"
                                                        {{ getRadioStatus($currentPermissions, 'aktivitas_logs_read', 'aktivitas_logs') == 'full' ? 'checked' : '' }}>
                                                </td>
                                            </tr>

                                            <!-- Transaksi -->
                                            <tr>
                                                <td><i class="bi bi-bar-chart-line"></i> <strong>Transaksi</strong>
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="transaksi_access" value="none"
                                                        id="transaksi_none" class="form-check-input radio-none"
                                                        {{ getRadioStatus($currentPermissions, 'transaksi_read', 'transaksi') == 'none' ? 'checked' : '' }}>
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="transaksi_access" value="read"
                                                        id="transaksi_read" class="form-check-input radio-read"
                                                        {{ getRadioStatus($currentPermissions, 'transaksi_read', 'transaksi') == 'read' ? 'checked' : '' }}>
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="transaksi_access" value="full"
                                                        id="transaksi_full" class="form-check-input radio-full"
                                                        {{ getRadioStatus($currentPermissions, 'transaksi_read', 'transaksi') == 'full' ? 'checked' : '' }}>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Hidden checkboxes for permissions -->
                                <div id="hiddenPermissions" style="display: none;">
                                    @foreach ($permissions as $perm)
                                        <input type="checkbox" name="permissions[]" value="{{ $perm['id'] }}"
                                            id="perm_{{ $perm['name'] }}" data-perm-name="{{ $perm['name'] }}"
                                            {{ in_array($perm['id'], $user_permissions) ? 'checked' : '' }}>
                                    @endforeach
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i>
                                    Update</button>
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

        // Role presets (hanya untuk role change, bukan page load)
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

        // Simpan radio state saat page load (berdasarkan data database)
        const originalRadioStates = {};

        function saveOriginalRadioStates() {
            Object.keys(permissionMapping).forEach(module => {
                const checked = document.querySelector(`input[name="${module}_access"]:checked`);
                if (checked) {
                    originalRadioStates[module] = checked.value;
                } else {
                    originalRadioStates[module] = 'none';
                }
            });
        }

        function setPermissionsByRole() {
            const role = document.getElementById('role').value;
            const preset = rolePresets[role];

            if (!preset) return;

            // Reset semua radio ke 'none' dulu
            document.querySelectorAll('input[type="radio"]').forEach(radio => {
                if (radio.value === 'none') {
                    radio.checked = true;
                } else {
                    radio.checked = false;
                }
            });

            // Reset all permission checkboxes
            document.querySelectorAll('#hiddenPermissions input[type="checkbox"]').forEach(cb => {
                cb.checked = false;
            });

            // Set radio dan permission berdasarkan preset role
            Object.keys(preset).forEach(module => {
                const level = preset[module];
                const radioBtn = document.querySelector(`input[name="${module}_access"][value="${level}"]`);
                if (radioBtn) {
                    radioBtn.checked = true;
                    updatePermissionCheckbox(module, level);
                }
            });

            // Logic disable radio
            document.querySelectorAll('input[type="radio"]').forEach(radio => {
                if (role === 'superadmin' || role === 'viewer') {
                    radio.disabled = true;
                } else {
                    radio.disabled = false;
                }
            });
        }

        function restoreOriginalRadioStates() {
            // Restore radio states berdasarkan data database
            Object.keys(originalRadioStates).forEach(module => {
                const level = originalRadioStates[module];
                const radioBtn = document.querySelector(`input[name="${module}_access"][value="${level}"]`);
                if (radioBtn) {
                    radioBtn.checked = true;
                    updatePermissionCheckbox(module, level);
                }
            });

            // Set disable logic berdasarkan role saat ini
            const role = document.getElementById('role').value;
            document.querySelectorAll('input[type="radio"]').forEach(radio => {
                if (role === 'superadmin' || role === 'viewer') {
                    radio.disabled = true;
                } else {
                    radio.disabled = false;
                }
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

        // Role change event - hanya set preset jika role berubah
        let initialRole = document.getElementById('role').value;
        document.getElementById('role').addEventListener('change', function() {
            const newRole = this.value;
            if (newRole !== initialRole) {
                // Role berubah, set preset
                setPermissionsByRole();
            } else {
                // Role sama, restore original states
                restoreOriginalRadioStates();
            }
        });

        // Initialize: simpan state original dan set disable logic
        document.addEventListener('DOMContentLoaded', function() {
            saveOriginalRadioStates();
            restoreOriginalRadioStates();

            // Update hidden checkboxes berdasarkan radio yang sudah checked
            Object.keys(permissionMapping).forEach(module => {
                const checked = document.querySelector(`input[name="${module}_access"]:checked`);
                if (checked) {
                    updatePermissionCheckbox(module, checked.value);
                }
            });
        });
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
