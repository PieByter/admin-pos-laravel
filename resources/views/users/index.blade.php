<x-app-layout>

    <x-content-header title="Manajemen User" breadcrumb-parent="SuperAdmin"
        breadcrumb-url="{{ url('superadmin/users') }}" />

    <?php if ($can_write ?? false): ?>
    <div id="custom-buttons" class="ms-3 mb-2">
        <a href="<?= site_url('superadmin/users/create') ?>" class="btn btn-primary" id="btn-create-user"
            title="Tambah User Baru">
            <i class="bi bi-person-plus"></i> Tambah User Baru
        </a>
    </div>
    <?php endif; ?>

    <div class="content">
        <div class="container-fluid mb-3">
            <div class="table-responsive" id="user-table-container">
                <table class="table table-striped table-hover table-bordered align-middle table-sm small text-center"
                    id="usersTable">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 10%;">Username</th>
                            <th style="width: 15%;">Email</th>
                            <th style="width: 10%;">Role</th>
                            <th style="width: 10%;">Jabatan</th>
                            <th style="width: 10%;">Bagian</th>
                            <th style="width: 40%;">Akses</th>
                            <?php if ($can_write ?? false): ?>
                            <th style="width: 10%;">Aksi</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-inbox display-1"></i>
                                    <p class="mt-2">
                                        <?= $search ? 'Tidak ada user yang sesuai dengan pencarian "' . esc($search) . '"' : 'Belum ada data user' ?>
                                    </p>
                                    <?php if ($can_write ?? false): ?>
                                    <a href="<?= site_url('superadmin/users/create') ?>" class="btn btn-primary">
                                        <i class="bi bi-person-plus"></i> Tambah User Pertama
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php $no = 1;
                        foreach ($users as $user): ?>
                        <tr style="cursor:pointer;"
                            onclick="window.location='<?= site_url('superadmin/users/detail/' . $user['id']) ?>'">
                            <td><?= $no++ ?></td>
                            <td><?= esc($user['username']) ?></td>
                            <td><?= esc($user['email'] ?? '-') ?></td>
                            <td>
                                <span class="badge
                                        <?php
                                        if ($user['role'] == 'superadmin') {
                                            echo 'bg-primary';
                                        } elseif ($user['role'] == 'useradmin') {
                                            echo 'bg-info';
                                        } elseif ($user['role'] == 'kasir') {
                                            echo 'bg-warning';
                                        } elseif ($user['role'] == 'gudang') {
                                            echo 'bg-secondary';
                                        } elseif ($user['role'] == 'viewer') {
                                            echo 'bg-success';
                                        } else {
                                            echo 'bg-secondary';
                                        }
                                        ?>">
                                    <?= ucfirst($user['role']) ?>
                                </span>
                            </td>
                            <?php
                            $jabatanList = [
                                'staff' => 'Staff',
                                'karyawan' => 'Karyawan',
                                'kadept' => 'Kepala Depot (Kadept)',
                                'wakadept' => 'Wakil Kepala Depot (Wakadept)',
                                'kabid' => 'Kepala Bidang (Kabid)',
                                'wakabid' => 'Wakil Kepala Bidang (Wakabid)',
                                'kasubid' => 'Kepala Sub Bidang (Kasubid)',
                                'wakasubid' => 'Wakil Kepala Sub Bidang (Wakasubid)',
                                'kabag' => 'Kepala Bagian (Kabag)',
                                'wakabag' => 'Wakil Kepala Bagian (Wakabag)',
                                'kasubag' => 'Kepala Sub Bagian (Kasubag)',
                                'wakasubag' => 'Wakil Kepala Sub Bagian (Wakasubag)',
                                'kasie' => 'Kepala Seksi (Kasie)',
                                'wakasie' => 'Wakil Kepala Seksi (Wakasie)',
                                'kasubsie' => 'Kepala Sub Seksi (Kasubsie)',
                                'wakasubsie' => 'Wakil Kepala Sub Seksi (Wakasubsie)',
                                'kagu' => 'Kepala Regu (Kagu)',
                                'wakagu' => 'Wakil Kepala Regu (Wakagu)',
                                'kasubgu' => 'Kepala Sub Regu (Kasubgu)',
                                'wakasubgu' => 'Wakil Kepala Sub Regu (Wakasubgu)',
                            ];
                            ?>
                            <td>
                                <?= esc($jabatanList[$user['jabatan']] ?? '-') ?>
                            </td>
                            <td><?= esc($user['bagian'] ?? '-') ?></td>
                            <td>
                                <?php if (isset($user['permissions']) && is_array($user['permissions']) && count($user['permissions'])): ?>
                                <?php
                                        $permissionGroups = [];
                                        $fullAccessPerms = [];

                                        foreach ($user['permissions'] as $perm) {
                                            $permName = $perm['display_name'];

                                            if (strpos($permName, 'Kelola') === 0) {
                                                $module = str_replace('Kelola ', '', $permName);
                                                $fullAccessPerms[] = $module;
                                                $permissionGroups[$module] = [
                                                    'type' => 'full',
                                                    'display' => $permName
                                                ];
                                            } elseif (strpos($permName, 'Lihat') === 0) {
                                                $module = str_replace('Lihat ', '', $permName);
                                                if (!in_array($module, $fullAccessPerms)) {
                                                    $permissionGroups[$module] = [
                                                        'type' => 'read',
                                                        'display' => $permName
                                                    ];
                                                }
                                            } else {
                                                $permissionGroups[$permName] = [
                                                    'type' => 'other',
                                                    'display' => $permName
                                                ];
                                            }
                                        }

                                        // Batasi tampilan maksimal 3 permission untuk tabel
                                        $displayedPerms = array_slice($permissionGroups, 0, 3);
                                        $remainingCount = count($permissionGroups) - 3;

                                        foreach ($displayedPerms as $perm):
                                            $badgeClass = 'bg-secondary';
                                            $iconClass = 'bi-eye';
                                            if ($perm['type'] === 'full') {
                                                $badgeClass = 'bg-danger';
                                                $iconClass = 'bi-gear-fill';
                                            } elseif ($perm['type'] === 'read') {
                                                $badgeClass = 'bg-success';
                                                $iconClass = 'bi-eye';
                                            } elseif ($perm['type'] === 'other') {
                                                $badgeClass = 'bg-info';
                                                $iconClass = 'bi-speedometer2';
                                            }
                                        ?>
                                <span class="badge <?= $badgeClass ?> me-1 mb-1" style="font-size: 0.7em;">
                                    <i class="bi <?= $iconClass ?>"></i>
                                    <?= esc(strlen($perm['display']) > 15 ? substr($perm['display'], 0, 15) . '...' : $perm['display']) ?>
                                </span>
                                <?php endforeach; ?>

                                <?php if ($remainingCount > 0): ?>
                                <span class="badge bg-light text-dark" style="font-size: 0.7em;">
                                    +<?= $remainingCount ?> lagi
                                </span>
                                <?php endif; ?>
                                <?php else: ?>
                                <span class="text-muted small">Tidak ada akses</span>
                                <?php endif; ?>
                            </td>
                            <?php if ($can_write ?? false): ?>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="<?= site_url('superadmin/users/edit/' . $user['id']) ?>"
                                        class="btn btn-warning btn-sm" title="Edit User">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <?php if ($user['id'] != session('user_id')): ?>
                                    <a href="<?= site_url('superadmin/users/delete/' . $user['id']) ?>"
                                        class="btn btn-danger btn-sm btn-hapus-user" onclick="event.stopPropagation();"
                                        title="Hapus User">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
            <?php if (session()->getFlashdata('success')): ?>
            Toast.fire({
                icon: 'success',
                title: '<?= session()->getFlashdata('success') ?>'
            });
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
            Toast.fire({
                icon: 'error',
                title: '<?= session()->getFlashdata('error') ?>'
            });
            <?php endif; ?>

            document.querySelectorAll('.btn-hapus-user').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = btn.getAttribute('href');
                    Swal.fire({
                        title: 'Yakin ingin menghapus user ini?',
                        text: 'Data user yang dihapus tidak bisa dikembalikan!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = url;
                        }
                    });
                });
            });

            var table = $('#usersTable').DataTable({
                dom: '<"d-flex justify-content-between align-items-center mb-2"<"d-flex align-items-center"<"dataTables_length"l><"#custom-buttons-container">><"dataTables_filter"f>>rtip',
                paging: true,
                searching: true,
                info: true,
                ordering: true,
                pagingType: 'full_numbers',
                language: {
                    info: "Halaman _PAGE_ dari _PAGES_ (_TOTAL_ Total Users)",
                    paginate: {
                        first: '&laquo;',
                        last: '&raquo;',
                        previous: '&lsaquo;',
                        next: '&rsaquo;'
                    }
                }
            });

            $('#custom-buttons').appendTo('#custom-buttons-container');
        });
    </script>

</x-app-layout>
