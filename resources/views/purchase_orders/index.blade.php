<x-app-layout>

    <x-content-header title="Manajemen Pembelian (Purchase Order)" breadcrumb-parent="Transaksi"
        breadcrumb-url="{{ url('pembelian') }}" />

    <?php if ($can_write ?? false): ?>
    <div id="custom-buttons" class="ms-3 mb-2">
        <a href="<?= site_url('pembelian/create') ?>" class="btn btn-primary" id="btn-create-pembelian">
            <i class="bi bi-plus-lg"></i> Tambah Pembelian
        </a>
    </div>
    <?php endif; ?>

    <div class="content">
        <div class="container-fluid mb-3">
            <div class="table-responsive" id="pembelian-table-container">
                <table class="table table-hover table-bordered table-sm small align-middle"
                    style="width:100%; table-layout: fixed;" id="pembelianTable">
                    <thead>
                        <tr class="text-center">
                            <th style="width:4%;">No</th>
                            <th style="width:13%;">Nomor Nota</th>
                            <th style="width:11%;">Tanggal Terbit</th>
                            <th style="width:10%;">Supplier</th>
                            <th style="width:14%;">Total Harga</th>
                            <th style="width:10%;">Status</th>
                            <th style="width:10%;">Payment</th>
                            <th style="width:19%;">Detail Barang</th>
                            <?php if ($can_write ?? false): ?>
                            <th style="width:10%;">Aksi</th>
                            <?php endif; ?>
                    </thead>
                    <tbody>
                        <?php if (!empty($pembelians)): ?>
                        <?php $no = 1; ?>
                        <?php foreach ($pembelians as $i => $p): ?>
                        <tr style="cursor:pointer;"
                            onclick="window.location='<?= site_url('pembelian/detail/' . $p['id']) ?>'">
                            <td class="text-center"><?= $no++ ?></td>
                            <td class="text-center"><?= esc($p['no_faktur']) ?></td>
                            <td class="text-center"><?= esc(formatTanggalIndo($p['tanggal_terbit'])) ?></td>
                            <td class="text-center"><?= esc($p['supplier_nama'] ?? '-') ?></td>
                            <td class="text-center">Rp. <?= number_format($p['total_harga'], 0, ',', '.') ?></td>
                            <td class="text-center">
                                <?php
                                $badge = 'secondary';
                                $statusText = ucfirst($p['status']);
                                switch ($p['status']) {
                                    case 'draft':
                                        $badge = 'secondary';
                                        break;
                                    case 'proses':
                                        $badge = 'warning';
                                        break;
                                    case 'selesai':
                                        $badge = 'success';
                                        $statusText = 'Selesai (Lunas)';
                                        break;
                                    case 'utang':
                                        $badge = 'info';
                                        break;
                                    case 'retur':
                                        $badge = 'orange';
                                        break;
                                    case 'batal':
                                        $badge = 'danger';
                                        break;
                                    default:
                                        $badge = 'secondary';
                                        break;
                                }
                                ?>
                                <span class="badge bg-<?= $badge ?>">
                                    <?= $statusText ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <?php
                                $badgePembayaran = 'primary';
                                switch ($p['metode_pembayaran']) {
                                    case 'cash':
                                        $badgePembayaran = 'success';
                                        break;
                                    case 'kredit':
                                        $badgePembayaran = 'warning';
                                        break;
                                    case 'transfer':
                                        $badgePembayaran = 'info';
                                        break;
                                    case 'debit':
                                        $badgePembayaran = 'primary';
                                        break;
                                    case 'e-wallet':
                                        $badgePembayaran = 'secondary';
                                        break;
                                    default:
                                        $badgePembayaran = 'dark';
                                        break;
                                }
                                ?>
                                <span class="badge bg-<?= $badgePembayaran ?>">
                                    <?= ucfirst($p['metode_pembayaran'] ?? '-') ?>
                                </span>
                            </td>
                            <td>
                                <?php if (!empty($p['detail'])): ?>
                                <?php foreach ($p['detail'] as $idx => $d): ?>
                                <div>
                                    <?= esc($d['nama_barang'] ?? '-') ?>,
                                    Qty: <?= esc($d['qty']) ?> <?= esc($d['nama_satuan'] ?? '-') ?>,
                                    Harga: Rp. <?= number_format($d['harga_beli'], 0, ',', '.') ?>
                                    Subtotal: Rp. <?= number_format($d['subtotal'], 0, ',', '.') ?>
                                </div>
                                <?php if ($idx < count($p['detail']) - 1): ?>
                                <hr class="my-1">
                                <?php endif; ?>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </td>
                            <?php if ($can_write ?? false): ?>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="<?= site_url('pembelian/edit/' . $p['id']) ?>"
                                        class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil"></i></a>
                                    <a href="<?= site_url('pembelian/delete/' . $p['id']) ?>"
                                        class="btn btn-danger btn-sm btn-hapus-pembelian"
                                        onclick="event.stopPropagation();">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <!-- <tr>
                        <td colspan="<?= $can_write ?? false ? 9 : 8 ?>" class="text-center py-4">
                            <div class="text-muted">
                                <i class="bi bi-inbox display-1"></i>
                                <p class="mt-2">
                                    <?= !empty($search) ? 'Tidak ada pembelian yang sesuai dengan pencarian "' . esc($search) . '"' : 'Belum ada data pembelian' ?>
                                </p>
                                <?php if ($can_write ?? false): ?>
                                <a href="<?= site_url('pembelian/create') ?>" class="btn btn-primary">
                                    <i class="bi bi-cart-plus"></i> Tambah Pembelian Pertama
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr> -->
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="card mt-3">
                <div class="card-body d-flex justify-content-end">
                    <form class="row align-items-center g-2" method="get"
                        action="<?= site_url('pembelian/export') ?>">
                        <div class="col-auto fw-bold">
                            Export Pembelian:
                        </div>
                        <div class="col-auto">
                            <select name="jenis" id="jenis-export" class="form-select" onchange="toggleExportInput()">
                                <option value="harian">Harian</option>
                                <option value="bulanan" selected>Bulanan</option>
                                <option value="tahunan">Tahunan</option>
                            </select>
                        </div>
                        <div class="col-auto" id="export-harian" style="display:none;">
                            <input type="date" name="tanggal_terbit" class="form-control">
                        </div>
                        <div class="col-auto" id="export-bulanan">
                            <select name="bulan" id="bulan-export" class="form-select">
                                <?php
                            $currentMonth = date('n');
                            for ($i = 1; $i <= 12; $i++): ?>
                                <option value="<?= $i ?>" <?= $i == $currentMonth ? 'selected' : '' ?>>
                                    <?= date('F', mktime(0, 0, 0, $i, 10)) ?>
                                </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-auto" id="export-tahun">
                            <select name="tahun" id="tahun-export" class="form-select">
                                <?php
                            $currentYear = date('Y');
                            for ($y = $currentYear - 3; $y <= $currentYear; $y++): ?>
                                <option value="<?= $y ?>" <?= $y == $currentYear ? 'selected' : '' ?>><?= $y ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-file-earmark-excel"></i> Export Excel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <script>
                function toggleExportInput() {
                    const jenis = document.getElementById('jenis-export').value;
                    document.getElementById('export-harian').style.display = (jenis === 'harian') ? '' : 'none';
                    document.getElementById('export-bulanan').style.display = (jenis === 'bulanan') ? '' : 'none';
                    document.getElementById('export-tahun').style.display = (jenis === 'tahunan' || jenis === 'bulanan') ? '' :
                        'none';
                }
                document.addEventListener('DOMContentLoaded', toggleExportInput);
                document.getElementById('jenis-export').addEventListener('change', toggleExportInput);
            </script>
        </div>
    </div>

    <style>
        .table th,
        .table td {
            font-size: 0.8rem;
        }
    </style>


    <?php
    function formatTanggalIndo($tanggal)
    {
        $bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $dateObj = date_create($tanggal);
        $tgl = date_format($dateObj, 'd');
        $bln = $bulan[(int) date_format($dateObj, 'm') - 1];
        $thn = date_format($dateObj, 'Y');
        return $tgl . ' ' . $bln . ' ' . $thn;
    }
    ?>

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

            document.querySelectorAll('.btn-hapus-pembelian').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = btn.getAttribute('href');
                    Swal.fire({
                        title: 'Yakin ingin menghapus data ini?',
                        text: 'Data yang dihapus tidak bisa dikembalikan!',
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

            var table = $('#pembelianTable').DataTable({
                dom: '<"d-flex justify-content-between align-items-center mb-2"<"d-flex align-items-center"<"dataTables_length"l><"#custom-buttons-container">><"dataTables_filter"f>>rtip',
                paging: true,
                searching: true,
                info: true,
                ordering: true,
                pagingType: 'full_numbers',
                language: {
                    info: "Halaman _PAGE_ dari _PAGES_ (_TOTAL_ Total Pembelian)",
                    infoEmpty: "Tidak ada data pembelian untuk ditampilkan",
                    infoFiltered: "(difilter dari _MAX_ total pembelian)",
                    emptyTable: "Belum ada Data Pembelian",
                    zeroRecords: "Tidak ada data yang sesuai dengan pencarian",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    search: "Pencarian:",
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
