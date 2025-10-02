<x-app-layout>

    <x-content-header title="Daftar Jenis Barang" breadcrumb-parent="Master Data"
        breadcrumb-url="{{ url('jenis-barang') }}" />

    <?php if ($can_write ?? false): ?>
    <div id="custom-buttons" class="ms-3 mb-2">
        <a href="<?= site_url('jenis-barang/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Jenis Barang
        </a>
    </div>
    <?php endif; ?>

    <div class="content">
        <div class="container-fluid mb-3">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="jenisBarangTable">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Nama Jenis</th>
                            <th>Keterangan</th>
                            <?php if ($can_write ?? false): ?>
                            <th>Aksi</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($jenisList)): ?>
                        <tr>
                            <td colspan="4" class="text-center">Belum ada data</td>
                        </tr>
                        <?php else: ?>
                        <?php $no = 1;
                        foreach ($jenisList as $jenis): ?>
                        <tr class="text-center">
                            <td><?= $no++ ?></td>
                            <td><?= esc($jenis['nama']) ?></td>
                            <td><?= esc($jenis['keterangan']) ?></td>
                            <?php if ($can_write ?? false): ?>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="<?= site_url('jenis-barang/edit/' . $jenis['id']) ?>"
                                        class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
                                    <a href="<?= site_url('jenis-barang/delete/' . $jenis['id']) ?>"
                                        class="btn btn-danger btn-sm btn-hapus-jenis"><i class=" bi bi-trash"></i></a>
                                </div>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach ?>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-hapus-jenis').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = btn.getAttribute('href');
                    Swal.fire({
                        title: 'Yakin ingin menghapus jenis barang ini?',
                        text: 'Data jenis barang yang dihapus tidak bisa dikembalikan!',
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

            var table = $('#jenisBarangTable').DataTable({
                dom: '<"d-flex justify-content-between align-items-center mb-2"<"d-flex align-items-center"<"dataTables_length"l><"#custom-buttons-container">><"dataTables_filter"f>>rtip',
                paging: true,
                searching: true,
                info: true,
                ordering: true,
                pagingType: 'full_numbers',
                language: {
                    info: "Halaman _PAGE_ dari _PAGES_ (_TOTAL_ Total Jenis Barang)",
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
