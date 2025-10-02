<x-app-layout>

    <x-content-header title="Manajemen Log Aktivitas" breadcrumb-parent="SuperAdmin"
        breadcrumb-url="{{ url('superadmin/logs') }}" />

    <?php if ($can_write ?? false): ?>
    <div id="custom-buttons" class="ms-3 mb-2">
        <a href="<?= site_url('superadmin/logs/create') ?>" class="btn btn-primary" id="btn-create-logs"
            title="Tambah Log Aktivitas Baru">
            <i class="bi bi-person-plus"></i> Tambah Log Aktivitas Baru
        </a>
    </div>
    <?php endif; ?>

    <div class="content">
        <div class="container-fluid mb-3">
            <div class="table-responsive" id="logs-table-container">
                <table class="table  table-sm table-striped table-hover table-bordered align-middle" id="logsTable">
                    <thead>
                        <tr class="text-center">
                            <th style="width:5%;">No</th>
                            <th style="width:15%;">Username</th>
                            <th style="width:45%;">Aktivitas</th>
                            <th style="width:25%;">Waktu Aktivitas</th>
                            <?php if ($can_write ?? false): ?>
                            <th style="width:10%;">Aksi</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($logs)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-inbox display-1"></i>
                                    <p class="mt-2">
                                        Belum ada data log aktivitas.
                                    </p>
                                    <?php if ($can_write ?? false): ?>
                                    <a href="<?= site_url('superadmin/logs/create') ?>" class="btn btn-primary">
                                        <i class="bi bi-plus"></i> Tambah Log Pertama
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php $no = 1;
                        foreach ($logs as $log): ?>
                        <tr class="text-center">
                            <td><?= $no++ ?></td>
                            <td><?= esc($log['username']) ?></td>
                            <td><?= esc($log['aktivitas']) ?></td>
                            <?php
                            if (!function_exists('bulanIndo')) {
                                function bulanIndo($bln)
                                {
                                    $arr = [
                                        '01' => 'Januari',
                                        '02' => 'Februari',
                                        '03' => 'Maret',
                                        '04' => 'April',
                                        '05' => 'Mei',
                                        '06' => 'Juni',
                                        '07' => 'Juli',
                                        '08' => 'Agustus',
                                        '09' => 'September',
                                        '10' => 'Oktober',
                                        '11' => 'November',
                                        '12' => 'Desember',
                                    ];
                                    return $arr[$bln] ?? $bln;
                                }
                            }
                            ?>
                            <td>
                                <?php
                                $waktu = $log['created_at'];
                                if (!empty($log['updated_at']) && $log['updated_at'] !== $log['created_at']) {
                                    $waktu = $log['updated_at'];
                                }
                                // Format: 15 September 2025 (10:55:58)
                                $dt = date_create($waktu);
                                $tanggal = date_format($dt, 'd') . ' ' . bulanIndo(date_format($dt, 'm')) . ' ' . date_format($dt, 'Y');
                                $jam = date_format($dt, 'H:i:s');
                                echo esc($tanggal) . ' (' . esc($jam) . ')';
                                ?>
                            </td>
                            <?php if ($can_write ?? false): ?>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="<?= site_url('superadmin/logs/edit/' . $log['id']) ?>"
                                        class="btn btn-warning btn-sm" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="<?= site_url('superadmin/logs/delete/' . $log['id']) ?>"
                                        class="btn btn-danger btn-sm btn-hapus-log" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </a>
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

    <style>
        .table th,
        .table td {
            font-size: 0.8rem;
        }
    </style>

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

            document.querySelectorAll('.btn-hapus-log').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = btn.getAttribute('href');
                    Swal.fire({
                        title: 'Yakin ingin menghapus log ini?',
                        text: 'Data log yang dihapus tidak bisa dikembalikan!',
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

            var table = $('#logsTable').DataTable({
                dom: '<"d-flex justify-content-between align-items-center mb-2"<"d-flex align-items-center"<"dataTables_length"l><"#custom-buttons-container">><"dataTables_filter"f>>rtip',
                paging: true,
                searching: true,
                info: true,
                ordering: true,
                pagingType: 'full_numbers',
                lengthMenu: [
                    [20, 50, 100, 200, 500],
                    [20, 50, 100, 200, 500]
                ],
                language: {
                    info: "Halaman _PAGE_ dari _PAGES_ (_TOTAL_ Total Log Aktivitas)",
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
