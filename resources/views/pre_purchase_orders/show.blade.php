<x-app-layout>
    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-eye"></i> Detail Purchase Order</h5>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-4">
                            <dt class="col-sm-5">No. PO</dt>
                            <dd class="col-sm-7"><?= esc($po['no_po']) ?></dd>
                            <dt class="col-sm-5">Tanggal Terbit</dt>
                            <dd class="col-sm-7"><?= esc(formatTanggalIndo($po['tanggal_terbit'])) ?></dd>
                            <dt class="col-sm-5">Jatuh Tempo</dt>
                            <dd class="col-sm-7"><?= esc(formatTanggalIndo($po['jatuh_tempo'])) ?></dd>
                            <dt class="col-sm-5">PPN (12%)</dt>
                            <dd class="col-sm-7">Rp. <?= number_format($po['ppn'], 0, ',', '.') ?></dd>
                            <dt class="col-sm-5">Supplier</dt>
                            <dd class="col-sm-7"><?= esc($supplier['nama']) ?></dd>
                            <dt class="col-sm-5">Otorisasi</dt>
                            <dd class="col-sm-7"><?= esc($otorisasiStr) ?></dd>
                            <dt class="col-sm-5">Status</dt>
                            <dd class="col-sm-7">
                                <?php
                                $badge = 'secondary';
                                $statusText = ucfirst($po['status']);
                                switch ($po['status']) {
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
                            </dd>
                            <dt class="col-sm-5">Metode Pembayaran</dt>
                            <dd class="col-sm-7">
                                <?php
                                $badge = 'secondary';
                                switch ($po['metode_pembayaran']) {
                                    case 'cash':
                                        $badge = 'success';
                                        break;
                                    case 'kredit':
                                        $badge = 'warning';
                                        break;
                                    case 'transfer':
                                        $badge = 'info';
                                        break;
                                    case 'debit':
                                        $badge = 'primary';
                                        break;
                                    case 'e-wallet':
                                        $badge = 'secondary';
                                        break;
                                }
                                ?>
                                <span class="badge bg-<?= $badge ?>">
                                    <?= ucfirst($po['metode_pembayaran']) ?>
                                </span>
                            </dd>
                            <dt class="col-sm-5">Keterangan</dt>
                            <dd class="col-sm-7"><?= esc($po['keterangan']) ?></dd>
                        </dl>

                        <h5 class="mb-3">Detail Barang</h5>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle small" id="table-barang">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Barang</th>
                                        <th>Satuan</th>
                                        <th>Qty</th>
                                        <th>Harga</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($details as $i => $d): ?>
                                    <tr class="barang-row" data-barang='<?= json_encode($d) ?>'>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= esc($barangMap[$d['id_barang']] ?? '-') ?></td>
                                        <td><?= esc($d['nama_satuan'] ?? '-') ?></td>
                                        <td><?= esc($d['qty']) ?></td>
                                        <td>Rp. <?= number_format($d['harga'], 0, ',', '.') ?></td>
                                        <td>Rp. <?= number_format($d['subtotal'], 0, ',', '.') ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5" class="text-end"><b>Total Harga</b></td>
                                        <td colspan="2" class="fw-bold text-success"> Rp.
                                            <span id="total-harga-label">
                                                <?= number_format($po['total_harga'], 0, ',', '.') ?></span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="mt-2 d-flex justify-content-end gap-2">
                            <a href="<?= site_url('po/print/' . $po['id']) ?>" target="_blank" class="btn btn-success">
                                <i class="bi bi-printer"></i> Print PDF
                            </a>
                            <?php if ($can_write ?? false): ?>
                            <a href="<?= site_url(relativePath: 'po/edit/' . $po['id']) ?>" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Edit PO
                            </a>
                            <?php endif; ?>
                            <a href="<?= site_url('po') ?>" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <?php if ($can_write ?? false): ?>
                    <div class="card-footer text-center">
                        <div class="d-flex justify-content-center align-items-center gap-2">
                            <?php if (!in_array($po['status'], ['selesai', 'retur', 'batal'])): ?>
                            <form action="<?= site_url('po/markSelesai/' . $po['id']) ?>" method="post"
                                style="display:inline;">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-success"
                                    onclick="return confirm('Tandai PO ini selesai dan otomatis masuk ke pembelian?\n\nPerhatian: Proses ini hanya bisa dilakukan sekali!')">
                                    <i class="bi bi-check-circle"></i> Tandai Selesai & Masukkan ke Pembelian
                                </button>
                            </form>
                            <?php elseif ($po['status'] === 'selesai'): ?>
                            <span class="badge bg-success fs-5">Purchase Order Sudah Ditandai Selesai</span>
                            <?php elseif ($po['status'] === 'retur'): ?>
                            <span class="badge bg-warning fs-5">Purchase Order Retur</span>
                            <?php elseif ($po['status'] === 'batal'): ?>
                            <span class="badge bg-danger fs-5">Purchase Order Dibatalkan</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalBarang" tabindex="-1" aria-labelledby="modalBarangLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBarangLabel">Detail Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBarangBody">
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.barang-row').forEach(function(row) {
            row.addEventListener('click', function() {
                const data = JSON.parse(this.dataset.barang);
                let html = `
            <dl class="row">
                <dt class="col-sm-5">Barang</dt><dd class="col-sm-7">${this.children[1].textContent}</dd>
                <dt class="col-sm-5">Satuan</dt><dd class="col-sm-7">${data.nama_satuan ? data.nama_satuan : '-'}</dd>
                <dt class="col-sm-5">Qty</dt><dd class="col-sm-7">${data.qty}</dd>
                <dt class="col-sm-5">Harga</dt><dd class="col-sm-7">Rp. ${parseInt(data.harga).toLocaleString('id-ID')}</dd>
                <dt class="col-sm-5">Subtotal</dt><dd class="col-sm-7">Rp. ${parseInt(data.subtotal).toLocaleString('id-ID')}</dd>
            </dl>
        `;
                document.getElementById('modalBarangBody').innerHTML = html;
                var modal = new bootstrap.Modal(document.getElementById('modalBarang'));
                modal.show();
            });
        });
    </script>

    <?php if (session()->getFlashdata('error')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                icon: 'error',
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
            Toast.fire({
                title: '<?= esc(session()->getFlashdata('error')) ?>'
            });
        });
    </script>
    <?php endif; ?>

    <?php
    function formatTanggalIndo($tanggal)
    {
        $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $bulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
        $dateObj = date_create($tanggal);
        $namaHari = $hari[(int) date_format($dateObj, 'w')];
        $tgl = date_format($dateObj, 'd');
        $bln = $bulan[(int) date_format($dateObj, 'm')];
        $thn = date_format($dateObj, 'Y');
        return $namaHari . ', ' . $tgl . ' ' . $bln . ' ' . $thn;
    }
    ?>
</x-app-layout>
