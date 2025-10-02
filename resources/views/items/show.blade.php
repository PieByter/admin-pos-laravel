<x-app-layout>
    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-eye"></i> Detail Barang</h5>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-4">
                            <dt class="col-sm-5">Kode Barang</dt>
                            <dd class="col-sm-7"><?= esc($barang['kode_barang']) ?></dd>
                            <dt class="col-sm-5">Nama Barang</dt>
                            <dd class="col-sm-7"><?= esc($barang['nama_barang']) ?></dd>
                            <dt class="col-sm-5">Jenis Barang</dt>
                            <dd class="col-sm-7"><?= esc($barang['jenis_nama'] ?? '-') ?></dd>
                            <dt class="col-sm-5">Group Barang</dt>
                            <dd class="col-sm-7"><?= esc($barang['group_nama'] ?? '-') ?></dd>
                            <dt class="col-sm-5">Satuan Utama</dt>
                            <dd class="col-sm-7"><?= esc($barang['satuan_nama'] ?? '-') ?></dd>
                            <dt class="col-sm-5">Harga Beli</dt>
                            <dd class="col-sm-7">
                                Rp
                                <?= $barang['harga_beli'] == intval($barang['harga_beli']) ? number_format($barang['harga_beli'], 0, ',', '.') : number_format($barang['harga_beli'], 2, ',', '.') ?>
                            </dd>
                            <dt class="col-sm-5">Harga Jual</dt>
                            <dd class="col-sm-7">
                                Rp
                                <?= $barang['harga_jual'] == intval($barang['harga_jual']) ? number_format($barang['harga_jual'], 0, ',', '.') : number_format($barang['harga_jual'], 2, ',', '.') ?>
                            </dd>
                            <dt class="col-sm-5">Stok</dt>
                            <dd class="col-sm-7"><?= number_format($barang['stok']) ?></dd>
                            <dt class="col-sm-5">Keterangan</dt>
                            <dd class="col-sm-7"><?= esc($barang['keterangan']) ?></dd>
                        </dl>
                        <div class="mt-4 d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-info text-white" data-bs-toggle="modal"
                                data-bs-target="#modalKonversi">
                                <i class="bi bi-arrows-expand"></i> Konversi Satuan
                            </button>
                            <?php if ($can_write ?? false): ?>
                            <a href="<?= site_url('barang/edit/' . $barang['id']) ?>" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <?php endif; ?>
                            <a href="<?= site_url('barang') ?>" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalKonversi" tabindex="-1" aria-labelledby="modalKonversiLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalKonversiLabel">Konversi Satuan Barang</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Satuan</th>
                                    <th>Konversi</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($konversi as $row): ?>
                                <tr>
                                    <td><?= esc($row['satuan_nama']) ?></td>
                                    <td><?= esc($row['konversi']) ?></td>
                                    <td><?= esc($row['keterangan']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
</x-app-layout>
