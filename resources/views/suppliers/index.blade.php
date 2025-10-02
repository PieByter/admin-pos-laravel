<x-app-layout>

    <x-content-header title="Manajemen Supplier" breadcrumb-parent="Master Data" breadcrumb-url="{{ url('supplier') }}" />

    <?php if ($can_write ?? false): ?>
    <div id="custom-buttons" class="ms-3 mb-2">
        <a href="<?= site_url('supplier/create') ?>" class="btn btn-primary" id="btn-create-supplier"
            title="Tambah Supplier Baru">
            <i class="bi bi-person-plus"></i> Tambah Supplier Baru
        </a>
    </div>
    <?php endif; ?>

    <div class="content">
        <div class="container-fluid mb-3">
            <div class="table-responsive" id="supplier-table-container">
                <table class="table table-striped table-hover table-bordered align-middle table-sm small"
                    id="supplierTable">
                    <thead>
                        <tr class="text-center">
                            <th style="width: 4%;">No</th>
                            <th style="width: 15%;">Nama</th>
                            <th style="width: 30%;">Alamat</th>
                            <th style="width: 13%;">No. Telepon</th>
                            <th style="width: 20%;">Email</th>
                            <th style="width: 10%;">Status</th>
                            <th style="width: 10%;">Keterangan</th>
                            <?php if ($can_write ?? false): ?>
                            <th style="width: 8%;">Aksi</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($suppliers)): ?>
                        <?php $no = 1;
                        foreach ($suppliers as $supplier): ?>
                        <tr class="text-center supplier-detail-row" style="cursor:pointer;"
                            onclick="window.location='<?= site_url('supplier/detail/' . $supplier['id']) ?>'">
                            <td><?= $no++ ?></td>
                            <td><?= esc($supplier['nama']) ?></td>
                            <td><?= esc($supplier['alamat']) ?></td>
                            <td><?= esc($supplier['no_telp']) ?></td>
                            <td><?= esc($supplier['email']) ?></td>
                            <td>
                                <?php if ($supplier['status'] == 'aktif'): ?>
                                <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                <span class="badge bg-secondary">Tidak Aktif</span>
                                <?php endif; ?>
                            <td><?= esc($supplier['keterangan']) ?></td>
                            <?php if ($can_write ?? false): ?>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="<?= site_url('supplier/edit/' . $supplier['id']) ?>"
                                        class="btn btn-sm btn-warning" title="Edit Supplier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="<?= site_url('supplier/delete/' . $supplier['id']) ?>"
                                        class="btn btn-danger btn-sm btn-hapus-supplier"
                                        onclick="event.stopPropagation();" title="Hapus Supplier">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-inbox display-1"></i>
                                    <p class="mt-2">
                                        <?= !empty($search) ? 'Tidak ada supplier yang sesuai dengan pencarian "' . esc($search) . '"' : 'Belum ada data supplier' ?>
                                    </p>
                                    <?php if ($can_write ?? false): ?>
                                    <a href="<?= site_url('supplier/create') ?>" class="btn btn-success">
                                        <i class="bi bi-person-plus"></i> Tambah Supplier Pertama
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
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

            document.querySelectorAll('.btn-hapus-supplier').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const url = btn.getAttribute('href');
                    Swal.fire({
                        title: 'Yakin ingin menghapus supplier ini?',
                        text: 'Data supplier yang dihapus tidak bisa dikembalikan!',
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

            var table = $('#supplierTable').DataTable({
                dom: '<"d-flex justify-content-between align-items-center mb-2"<"d-flex align-items-center"<"dataTables_length"l><"#custom-buttons-container">><"dataTables_filter"f>>rtip',
                paging: true,
                searching: true,
                info: true,
                ordering: true,
                pagingType: 'full_numbers',
                language: {
                    info: "Halaman _PAGE_ dari _PAGES_ (_TOTAL_ Total Supplier)",
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
