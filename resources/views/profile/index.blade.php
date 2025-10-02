<x-app-layout>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row  align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0">Pengaturan Akun</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="<?= site_url('profile') ?>">Profile</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Setting Akun</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <x-content-header title="Pengaturan Akun" breadcrumb-parent="Profile" breadcrumb-url="{{ url('profile') }}" />

    <div class="content">
        <div class="container-fluid">
            <div class="row align-items-stretch">
                <div class="col-md-8">
                    <div class="card h-100">
                        <div class="card-header d-flex align-items-center">
                            <h5 class="card-title mb-0">Pengaturan Profil</h5>
                            <a href="<?= site_url('profile/edit') ?>" class="btn btn-primary btn-sm ms-auto">
                                <i class="bi bi-pencil"></i> Edit Profil
                            </a>
                        </div>
                        <div class="card-body">
                            <dl class="row mb-3 table-detail">
                                <dt class="col-sm-3">Username</dt>
                                <dd class="col-sm-9"><?= esc($user['username']) ?></dd>
                                <dt class="col-sm-3">Email</dt>
                                <dd class="col-sm-9"><?= esc($user['email'] ?? '-') ?></dd>
                                <dt class="col-sm-3">Role</dt>
                                <dd class="col-sm-9">
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
                                </dd>
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
                                <dt class="col-sm-3">Jabatan</dt>
                                <dd class="col-sm-8"><?= esc($jabatanList[$user['jabatan']] ?? '-') ?></dd>
                                <dt class="col-sm-3">Bagian/Divisi</dt>
                                <dd class="col-sm-8"><?= esc($user['bagian'] ?? '-') ?></dd>
                                <dt class="col-sm-3">Akses</dt>
                                <dd class="col-sm-9">
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
                                    foreach ($permissionGroups as $perm):
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
                                    <span class="badge <?= $badgeClass ?> me-1 mb-1" style="font-size: 0.8em;">
                                        <i class="bi <?= $iconClass ?>"></i>
                                        <?= esc($perm['display']) ?>
                                    </span>
                                    <?php endforeach; ?>
                                    <?php else: ?>
                                    <span class="text-muted">Tidak ada akses</span>
                                    <?php endif; ?>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Akun</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-4">
                                <div class="flex-shrink-0">
                                    <div
                                        class="avatar avatar-xl text-dark d-flex align-items-center justify-content-center">
                                        <?php if ($user['foto'] && file_exists(FCPATH . 'uploads/profile/' . $user['foto'])): ?>
                                        <img src="<?= base_url('uploads/profile/' . $user['foto']) ?>"
                                            alt="Profile Photo" class="rounded-circle"
                                            style="width:60px; height:60px; object-fit:cover;">
                                        <?php else: ?>
                                        <i class="bi bi-person-circle" style="font-size:3rem;"></i>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <h5 class="mb-0"><?= $user['username'] ?></h5>
                                    <p class="text-muted mb-0"><?= ucfirst($user['role']) ?></p>
                                </div>
                            </div>

                            <div class="mb-3">
                                <h6 class="mb-1">Terakhir Login:</h6>
                                <p class="text-muted"><?= date('d-m-Y H:i:s') ?></p>
                            </div>

                            <a href="<?= site_url('auth/logout') ?>" class="btn btn-danger btn-sm">
                                <i class="bi bi-box-arrow-right"></i> Sign Out
                            </a>
                        </div>
                    </div>
                </div>
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
        });
    </script>
</x-app-layout>
