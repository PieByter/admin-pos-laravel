<x-app-layout>
    <x-content-header title="Manajemen Barang" breadcrumb-parent="Master Data" breadcrumb-url="{{ url('barang') }}" />

    <?php if ($can_write ?? false): ?>
    <div id="custom-buttons" class="ms-3 mb-2">
        <a href="<?= site_url('barang/create') ?>" class="btn btn-primary" id="btn-create-barang"
            title="Tambah Barang Baru">
            <i class="bi bi-person-plus"></i> Tambah Barang Baru
        </a>
    </div>
    <?php endif; ?>

    <div class="content">
        <div class="container-fluid mb-3">
            <div class="table-responsive" id="barang-table-container">
                <table class="table table-striped table-hover table-bordered align-middle table-sm small"
                    id="barangTable" style="table-layout: fixed; width: 100%;">
                    <thead class="table">
                        <tr class="text-center">
                            <th style="width: 4%;">No</th>
                            <th style="width: 10%;">Kode Barang</th>
                            <th style="width: 25%;">Nama Barang</th>
                            <th style="width: 10%;">Jenis</th>
                            <th style="width: 15%;">Group</th>
                            <th style="width: 10%;">Satuan</th>
                            <th style="width: 10%;">Harga Beli</th>
                            <th style="width: 10%;">Harga Jual</th>
                            <th style="width: 10%;">Stok</th>
                            <?php if ($can_write ?? false): ?>
                            <th style="width: 10%;">Aksi</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($barangs)): ?>
                        <tr>
                            <td colspan="11" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-inbox display-1"></i>
                                    <p class="mt-2">
                                        <?= $search ? 'Tidak ada barang yang sesuai dengan pencarian "' . esc($search) . '"' : 'Belum ada data barang' ?>
                                    </p>
                                    <?php if ($can_write ?? false): ?>
                                    <a href="<?= site_url('barang/create') ?>" class="btn btn-primary">
                                        <i class="bi bi-plus"></i> Tambah Barang Pertama
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php $no = 1;
                        foreach ($barangs as $barang): ?>
                        <tr class="text-center" style="cursor:pointer;"
                            onclick="window.location='<?= site_url('barang/detail/' . $barang['id']) ?>'">
                            <td><?= $no++ ?></td>
                            <td class="text-truncate"><?= esc($barang['kode_barang']) ?></td>
                            <td class="text-truncate"><?= esc($barang['nama_barang']) ?></td>
                            <td><?= esc($barang['jenis_nama'] ?? '-') ?></td>
                            <td><?= esc($barang['group_nama'] ?? '-') ?></td>
                            <td><?= esc($barang['satuan_nama'] ?? '-') ?></td>
                            <td>
                                Rp
                                <?= number_format($barang['harga_beli'], is_float($barang['harga_beli']) ? 2 : 0, ',', '.') ?>
                            </td>
                            <td>
                                Rp
                                <?= number_format($barang['harga_jual'], is_float($barang['harga_jual']) ? 2 : 0, ',', '.') ?>
                            </td>
                            <td>
                                <span
                                    class="badge <?= $barang['stok'] > 10 ? 'bg-success' : ($barang['stok'] > 0 ? 'bg-warning text-dark' : 'bg-danger') ?>">
                                    <?= number_format($barang['stok']) ?>
                                </span>
                            </td>
                            <?php if ($can_write ?? false): ?>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="<?= site_url('barang/edit/' . $barang['id']) ?>"
                                        class="btn btn-sm btn-warning" title="Edit barang">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="<?= site_url('barang/delete/' . $barang['id']) ?>"
                                        class="btn btn-danger btn-sm btn-hapus-barang"
                                        onclick="event.stopPropagation();" title="Hapus barang">
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
        .description-cell {
            max-width: 180px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            cursor: help;
        }

        .img-thumbnail {
            object-fit: cover;
        }

        .btn-group .btn {
            margin: 0;
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

            document.querySelectorAll('.btn-hapus-barang').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = btn.getAttribute('href');
                    Swal.fire({
                        title: 'Yakin ingin menghapus barang ini?',
                        text: 'Data barang yang dihapus tidak bisa dikembalikan!',
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

            var table = $('#barangTable').DataTable({
                dom: '<"d-flex justify-content-between align-items-center mb-2"<"d-flex align-items-center"<"dataTables_length"l><"#custom-buttons-container">><"dataTables_filter"f>>rtip',
                paging: true,
                searching: true,
                info: true,
                ordering: true,
                pagingType: 'full_numbers',
                language: {
                    info: "Halaman _PAGE_ dari _PAGES_ (_TOTAL_ Total Barang)",
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
