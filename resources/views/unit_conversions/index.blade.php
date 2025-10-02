<x-app-layout>

    <x-content-header title="Manajemen Satuan Konversi" breadcrumb-parent="Master Data"
        breadcrumb-url="{{ url('satuan-konversi') }}" />

    <?php if ($can_write ?? false): ?>
    <div id="custom-buttons" class="ms-3 mb-2">
        <a href=" <?= site_url('satuan-konversi/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus"></i> Tambah Konversi Baru
        </a>
    </div>
    <?php endif; ?>

    <div class="content">
        <div class="container-fluid mb-3">
            <div class="table-responsive" id="konversi-table-container">
                <table id="satuanKonversiTable"
                    class="table table-striped table-hover table-bordered table-sm align-middle small">
                    <thead>
                        <tr class="text-center">
                            <th style="width:5%;">No</th>
                            <th style="width:20%;">Barang</th>
                            <th style="width:15%;">Satuan</th>
                            <th style="width:15%;">Konversi</th>
                            <th style="width:35%;">Keterangan</th>
                            <?php if ($can_write ?? false): ?>
                            <th style="width:10%;">Aksi</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($konversis)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-inbox display-1"></i>
                                    <p class="mt-2">
                                        <?= !empty($search) ? 'Tidak ada konversi yang sesuai dengan pencarian "' . esc($search) . '"' : 'Belum ada data konversi' ?>
                                    </p>
                                    <?php if ($can_write ?? false): ?>
                                    <a href="<?= site_url('satuan-konversi/create') ?>" class="btn btn-primary">
                                        <i class="bi bi-plus"></i> Tambah Konversi Pertama
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php $no = 1;
                        foreach ($konversis as $konv): ?>
                        <tr class="text-center">
                            <td><?= $no++ ?></td>
                            <td><?= esc($konv['nama_barang'] ?? '-') ?></td>
                            <td><?= esc($konv['nama_satuan'] ?? '-') ?></td>
                            <td><?= esc($konv['konversi']) ?></td>
                            <td><?= esc($konv['keterangan'] ?? '-') ?></td>
                            <?php if ($can_write ?? false): ?>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="<?= site_url('satuan-konversi/edit/' . $konv['id']) ?>"
                                        class="btn btn-warning btn-sm" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="<?= site_url('satuan-konversi/delete/' . $konv['id']) ?>"
                                        class="btn btn-danger btn-sm btn-hapus-konversi"
                                        onclick="event.stopPropagation();">
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

            var table = $('#satuanKonversiTable').DataTable({
                dom: '<"d-flex justify-content-between align-items-center mb-2"<"d-flex align-items-center"<"dataTables_length"l><"#custom-buttons-container">><"dataTables_filter"f>>rtip',
                paging: true,
                searching: true,
                info: true,
                ordering: true,
                pagingType: 'full_numbers',
                language: {
                    info: "Halaman _PAGE_ dari _PAGES_ (_TOTAL_ Total Satuan Konversi)",
                    paginate: {
                        first: '&laquo;',
                        last: '&raquo;',
                        previous: '&lsaquo;',
                        next: '&rsaquo;'
                    }
                }
            });

            $('#custom-buttons').appendTo('#custom-buttons-container');

            document.querySelectorAll('.btn-hapus-konversi').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = btn.getAttribute('href');
                    Swal.fire({
                        title: 'Yakin ingin menghapus konversi ini?',
                        text: 'Data konversi yang dihapus tidak bisa dikembalikan!',
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
        });
    </script>
</x-app-layout>
