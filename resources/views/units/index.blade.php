<x-app-layout>

    <x-content-header title="Manajemen Satuan" breadcrumb-parent="Master Data" breadcrumb-url="{{ url('satuan') }}" />

    <?php if ($can_write ?? false): ?>
    <div id="custom-buttons" class="ms-3 mb-2">
        <a href="<?= site_url('satuan/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Satuan Baru
        </a>
    </div>
    <?php endif; ?>

    <div class="content">
        <div class="container-fluid mb-3">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered" id="satuanTable">
                    <thead>
                        <tr class="text-center">
                            <th style="width:5%;">No</th>
                            <th style="width:30%;">Nama Satuan</th>
                            <th style="width:45%;">Keterangan</th>
                            <?php if ($can_write ?? false): ?>
                            <th style="width:20%;">Aksi</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($satuans)): ?>
                        <tr class="text-center">
                            <td colspan="4" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-inbox display-1"></i>
                                    <p class="mt-2">
                                        Belum ada data satuan
                                    </p>
                                    <?php if ($can_write ?? false): ?>
                                    <a href="<?= site_url('satuan/create') ?>" class="btn btn-primary">
                                        <i class="bi bi-plus"></i> Tambah Satuan Pertama
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php $no = 1;
                        foreach ($satuans as $satuan): ?>
                        <tr class="text-center">
                            <td><?= $no++ ?></td>
                            <td><?= esc($satuan['nama']) ?></td>
                            <td><?= esc($satuan['keterangan']) ?></td>
                            <?php if ($can_write ?? false): ?>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="<?= site_url('satuan/edit/' . $satuan['id']) ?>"
                                        class="btn btn-warning btn-sm" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="<?= site_url('satuan/delete/' . $satuan['id']) ?>"
                                        class="btn btn-danger btn-sm btn-hapus-satuan" title="Hapus">
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-hapus-satuan').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = btn.getAttribute('href');
                    Swal.fire({
                        title: 'Yakin ingin menghapus satuan ini?',
                        text: 'Data satuan yang dihapus tidak bisa dikembalikan!',
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

            var table = $('#satuanTable').DataTable({
                dom: '<"d-flex justify-content-between align-items-center mb-2"<"d-flex align-items-center"<"dataTables_length"l><"#custom-buttons-container">><"dataTables_filter"f>>rtip',
                paging: true,
                searching: true,
                info: true,
                ordering: true,
                pagingType: 'full_numbers',
                language: {
                    info: "Halaman _PAGE_ dari _PAGES_ (_TOTAL_ Total Satuan Barang)",
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
