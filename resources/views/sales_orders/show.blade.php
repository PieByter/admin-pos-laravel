<x-app-layout>
    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-eye"></i> Detail Penjualan</h5>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-4">
                            <dt class="col-sm-5">No Nota</dt>
                            <dd class="col-sm-7"><?= esc($penjualan['no_nota']) ?></dd>
                            <dt class="col-sm-5">Tanggal Terbit</dt>
                            <dd class="col-sm-7"><?= esc(formatTanggalIndo($penjualan['tanggal_terbit'])) ?></dd>
                            <dt class="col-sm-5">Customer</dt>
                            <dd class="col-sm-7"><?= esc($customer['nama']) ?></dd>
                            <dt class="col-sm-5">Status</dt>
                            <dd class="col-sm-7">
                                <?php
                                $badge = 'secondary';
                                $statusText = ucfirst($penjualan['status']);
                                switch ($penjualan['status']) {
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
                                $badgePembayaran = 'secondary';
                                switch ($penjualan['metode_pembayaran']) {
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
                                    <?= ucfirst($penjualan['metode_pembayaran'] ?? '-') ?>
                                </span>
                            </dd>
                            <dt class="col-sm-5">Otorisasi</dt>
                            <dd class="col-sm-7"><?= esc($otorisasiStr ?? '-') ?></dd>
                            <dt class="col-sm-5">Keterangan</dt>
                            <dd class="col-sm-7"><?= esc($penjualan['keterangan']) ?></dd>
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
                                        <th>Harga Jual</th>
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
                                        <td>Rp. <?= number_format($d['harga_jual'], 0, ',', '.') ?></td>
                                        <td>Rp. <?= number_format($d['subtotal'], 0, ',', '.') ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5" class="text-end"><b>Total Harga</b></td>
                                        <td colspan="2" class="fw-bold text-success">Rp.
                                            <span
                                                id="total-harga-label"><?= number_format($penjualan['total_harga'], 0, ',', '.') ?></span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="mt-2 d-flex justify-content-end gap-2">
                            <?php if ($can_write ?? false): ?>
                            <a href="<?= site_url('penjualan/edit/' . $penjualan['id']) ?>" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <?php endif; ?>
                            <a href="<?= site_url('penjualan') ?>" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
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
                <dt class="col-sm-5">Harga Jual</dt><dd class="col-sm-7">Rp. ${parseInt(data.harga_jual).toLocaleString('id-ID')}</dd>
                <dt class="col-sm-5">Subtotal</dt><dd class="col-sm-7">Rp. ${parseInt(data.subtotal).toLocaleString('id-ID')}</dd>
            </dl>
        `;
                document.getElementById('modalBarangBody').innerHTML = html;
                var modal = new bootstrap.Modal(document.getElementById('modalBarang'));
                modal.show();
            });
        });
    </script>

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
