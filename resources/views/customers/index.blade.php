<x-app-layout>
    <x-content-header title="Manajemen Customer" breadcrumb-parent="Master Data" breadcrumb-url="{{ url('customer') }}" />

    <?php if ($can_write ?? false): ?>
    <div id="custom-buttons" class="ms-3 mb-2">
        <a href="<?= site_url('customer/create') ?>" class="btn btn-primary" id="btn-create-customer"
            title="Tambah Customer Baru">
            <i class="bi bi-person-plus"></i> Tambah Customer Baru
        </a>
    </div>
    <?php endif; ?>

    <div class="content">
        <div class="container-fluid mb-3">
            <table class="table table-striped table-hover table-bordered align-middle table-sm small w-100"
                id="customerTable">
                <thead>
                    <tr class="text-center">
                        <th style="width: 4%;">No</th>
                        <th style="width: 15%;">Nama</th>
                        <th style="width: 30;">Alamat</th>
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
                    <?php if (!empty($customers)): ?>
                    <?php $no = 1;
                    foreach ($customers as $customer): ?>
                    <tr class="text-center" style="cursor:pointer;"
                        onclick="window.location='<?= site_url('customer/detail/' . $customer['id']) ?>'">
                        <td><?= $no++ ?></td>
                        <td><?= esc($customer['nama']) ?></td>
                        <td><?= esc($customer['alamat']) ?></td>
                        <td><?= esc($customer['no_telp']) ?></td>
                        <td><?= esc($customer['email']) ?></td>
                        <td>
                            <?php if ($customer['status'] == 'aktif'): ?>
                            <span class="badge bg-success">Aktif</span>
                            <?php else: ?>
                            <span class="badge bg-secondary">Tidak Aktif</span>
                            <?php endif; ?>
                        <td><?= esc($customer['keterangan']) ?></td>
                        <?php if ($can_write ?? false): ?>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="<?= site_url('customer/edit/' . $customer['id']) ?>"
                                    class="btn btn-sm btn-warning" title="Edit customer">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="<?= site_url('customer/delete/' . $customer['id']) ?>"
                                    class="btn btn-danger btn-sm btn-hapus-customer" onclick="event.stopPropagation();"
                                    title="Hapus customer">
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
                                    <?= !empty($search) ? 'Tidak ada customer yang sesuai dengan pencarian "' . esc($search) . '"' : 'Belum ada data customer' ?>
                                </p>
                                <?php if ($can_write ?? false): ?>
                                <a href="<?= site_url('customer/create') ?>" class="btn btn-success">
                                    <i class="bi bi-person-plus"></i> Tambah Customer Pertama
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

            document.querySelectorAll('.btn-hapus-customer').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const url = btn.getAttribute('href');
                    Swal.fire({
                        title: 'Yakin ingin menghapus customer ini?',
                        text: 'Data customer yang dihapus tidak bisa dikembalikan!',
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

            var table = $('#customerTable').DataTable({
                dom: '<"d-flex justify-content-between align-items-center mb-2"<"d-flex align-items-center"<"dataTables_length"l><"#custom-buttons-container">><"dataTables_filter"f>>rtip',
                paging: true,
                searching: true,
                info: true,
                ordering: true,
                pagingType: 'full_numbers',
                language: {
                    info: "Halaman _PAGE_ dari _PAGES_ (_TOTAL_ Total Customer)",
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
