<x-app-layout>
    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white text-center">
                        <h5 class="card-title mb-0"><i class="bi bi-file-earmark-plus"></i> Form Tambah Purchase Order
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="<?= site_url('po/save') ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="search" value="<?= esc($_GET['search'] ?? '') ?>">
                            <input type="hidden" name="page" value="<?= esc($_GET['page'] ?? 1) ?>">
                            <div class="row mb-3 align-items-center">
                                <label for="no_po" class="col-md-3 col-form-label"><b>No. Purchase Order</b></label>
                                <div class="col-md-9">
                                    <input type="text" name="no_po" class="form-control" id="no_po"
                                        value="<?= esc($no_po) ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label for="tanggal_terbit" class="col-md-3 col-form-label"><b>Tanggal
                                        Terbit</b></label>
                                <div class="col-md-9">
                                    <input type="date" name="tanggal_terbit" class="form-control" id="tanggal_terbit"
                                        value="<?= old('tanggal_terbit', esc($tanggal ?? '')) ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label for="jatuh_tempo" class="col-md-3 col-form-label"><b>Jatuh Tempo</b></label>
                                <div class="col-md-9">
                                    <input type="date" name="jatuh_tempo" class="form-control"
                                        value="<?= old('jatuh_tempo') ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label for="ppn" class="col-md-3 col-form-label"><b>PPN (12%)</b></label>
                                <div class="col-md-9">
                                    <input type="number" name="ppn" id="ppn" class="form-control" required
                                        readonly style="display:none;">
                                    <span id="ppn-format" class="form-control bg-light"></span>
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label for="id_supplier" class="col-md-3 col-form-label"><b>Supplier</b></label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select name="id_supplier" id="id_supplier" class="form-select" required>
                                            <option value="">- Pilih Supplier -</option>
                                            <?php foreach ($suppliers as $s): ?>
                                            <?php if ($s['status'] == 'aktif'): ?>
                                            <option value="<?= $s['id'] ?>"
                                                <?= old('id_supplier') == $s['id'] ? 'selected' : '' ?>>
                                                <?= esc($s['nama']) ?>
                                            </option>
                                            <?php endif; ?>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="button" class="btn btn-outline-info btn-sm"
                                            onclick="openSupplierModal(this)">
                                            <i class="bi bi-search"></i> Cari
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label for="status" class="col-md-3 col-form-label"><b>Status</b></label>
                                <div class="col-md-9">
                                    <select name="status" id="status" class="form-select" required>
                                        <option value="">- Pilih Status -</option>
                                        <option value="draft" <?= old('status') == 'draft' ? 'selected' : '' ?>>Draft
                                        </option>
                                        <option value="proses" <?= old('status') == 'proses' ? 'selected' : '' ?>>Proses
                                        </option>
                                        <option value="selesai" <?= old('status') == 'selesai' ? 'selected' : '' ?>>
                                            Selesai
                                            (Lunas)</option>
                                        <option value="utang" <?= old('status') == 'utang' ? 'selected' : '' ?>>Utang
                                        </option>
                                        <option value="retur" <?= old('status') == 'retur' ? 'selected' : '' ?>>Retur
                                        </option>
                                        <option value="batal" <?= old('status') == 'batal' ? 'selected' : '' ?>>Batal
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label for="metode_pembayaran" class="col-md-3 col-form-label"><b>Metode
                                        Pembayaran</b></label>
                                <div class="col-md-9">
                                    <select name="metode_pembayaran" id="metode_pembayaran" class="form-select"
                                        required>
                                        <option value="">- Pilih Metode Pembayaran -</option>
                                        <option value="cash"
                                            <?= old('metode_pembayaran') == 'cash' ? 'selected' : '' ?>>
                                            Cash</option>
                                        <option value="kredit"
                                            <?= old('metode_pembayaran') == 'kredit' ? 'selected' : '' ?>>Kredit
                                        </option>
                                        <option value="transfer"
                                            <?= old('metode_pembayaran') == 'transfer' ? 'selected' : '' ?>>Transfer
                                        </option>
                                        <option value="debit"
                                            <?= old('metode_pembayaran') == 'debit' ? 'selected' : '' ?>>
                                            Debit</option>
                                        <option value="e-wallet"
                                            <?= old('metode_pembayaran') == 'e-wallet' ? 'selected' : '' ?>>E-Wallet
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <!-- <div class="row mb-3 align-items-center">
                            <label for="otorisasi" class="col-md-3 col-form-label"><b>Otorisasi</b></label>
                            <div class="col-md-9">
                                <select name="otorisasi" class="form-select" required>
                                    <option value="">- Pilih User -</option>
                                    <?php foreach ($users as $u): ?>
                                    <option value="<?= esc($u['username']) ?>"><?= esc($u['nama'] ?? $u['username']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div> -->
                            <!-- <div class="row mb-3 align-items-center">
                            <label for="otorisasi" class="col-md-3 col-form-label"><b>Otorisasi</b></label>
                            <div class="col-md-9">
                                <input type="text" name="otorisasi" class="form-control"
                                    value="<?= esc(session('username')) ?>" readonly>
                            </div>
                        </div> -->

                            <div class="row mb-3 align-items-center">
                                <label for="otorisasi" class="col-md-3 col-form-label"><b>Otorisasi</b></label>
                                <div class="col-md-9">
                                    <?php
                                    $otorisasiArr = isset($otorisasi) && is_array($otorisasi) ? array_map('intval', $otorisasi) : [(int) session('user_id')];
                                    ?>
                                    <input type="hidden" name="otorisasi"
                                        value='<?= esc(json_encode($otorisasiArr)) ?>'>
                                    <div class="form-control bg-light" readonly>
                                        <?php
                                        $usernames = [];
                                        foreach ($users as $u) {
                                            if (in_array($u['id'], $otorisasiArr)) {
                                                $usernames[] = $u['username'];
                                            }
                                        }
                                        echo esc(implode(', ', $usernames));
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label for="keterangan" class="col-md-3 col-form-label"><b>Keterangan</b></label>
                                <div class="col-md-9">
                                    <textarea name="keterangan" class="form-control"><?= old('keterangan') ?></textarea>
                                </div>
                            </div>
                            <hr>
                            <h5 class="mb-3">Detail Barang</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Barang</th>
                                            <th>Satuan</th>
                                            <th>Qty</th>
                                            <th>Harga</th>
                                            <th colspan="2">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody id="detail-barang-body">
                                        <?php
                                    $details = old('detail') ?? [[]]; // Jika tidak ada old, tampilkan 1 baris kosong
                                    foreach ($details as $i => $detail):
                                    ?>
                                        <tr>
                                            <td>
                                                <div class="input-group">
                                                    <select name="detail[<?= $i ?>][id_barang]"
                                                        class="form-select barang-select" required>
                                                        <option value="">- Pilih Barang -</option>
                                                        <?php foreach ($barangs as $b): ?>
                                                        <option value="<?= $b['id'] ?>"
                                                            data-harga="<?= $b['harga_beli'] ?>"
                                                            data-id_satuan="<?= esc($b['id_satuan']) ?>"
                                                            data-stok="<?= esc($b['stok']) ?>"
                                                            <?= isset($detail['id_barang']) && $detail['id_barang'] == $b['id'] ? 'selected' : '' ?>>
                                                            <?= esc($b['nama_barang']) ?>
                                                        </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <button type="button" class="btn btn-outline-primary btn-sm"
                                                        onclick="openBarangModal(this)">
                                                        <i class="bi bi-search"></i> Cari
                                                    </button>
                                                </div>
                                                <span class="stok-info text-success small"></span>
                                            </td>
                                            <td>
                                                <select name="detail[<?= $i ?>][id_satuan]"
                                                    class="form-select satuan-select" required>
                                                    <option value="">- Pilih Satuan -</option>
                                                    <?php if (!empty($detail['id_barang']) && isset($satuanKonversiMap[$detail['id_barang']])): ?>
                                                    <?php foreach ($satuanKonversiMap[$detail['id_barang']] as $konv): ?>
                                                    <option value="<?= $konv['id_satuan'] ?>"
                                                        data-konversi="<?= $konv['konversi'] ?>"
                                                        <?= isset($detail['id_satuan']) && $detail['id_satuan'] == $konv['id_satuan'] ? 'selected' : '' ?>>
                                                        <?=
                                                    esc($konv['nama_satuan'])
                                                    $konv['konversi'] > 1 ? " ({$konv['konversi']} pcs)" : ''
                                                    ?>
                                                    </option>
                                                    <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="detail[<?= $i ?>][qty]"
                                                    class="form-control qty-input"
                                                    value="<?= isset($detail['qty']) ? esc($detail['qty']) : '' ?>"
                                                    required>
                                            </td>
                                            <td>
                                                <input type="number" name="detail[<?= $i ?>][harga]"
                                                    class="form-control harga-input"
                                                    value="<?= isset($detail['harga']) ? esc($detail['harga']) : '' ?>"
                                                    required style="display:none;">
                                                <input type="text" class="form-control harga-format"
                                                    value="<?= isset($detail['harga']) ? number_format($detail['harga'], 0, ',', '.') : '' ?>">
                                            </td>
                                            <td>
                                                <input type="number" name="detail[<?= $i ?>][subtotal]"
                                                    class="form-control subtotal-input"
                                                    value="<?= isset($detail['subtotal']) ? esc($detail['subtotal']) : '' ?>"
                                                    readonly style="display:none;">
                                                <span class="subtotal-format form-control bg-light">
                                                    <?= isset($detail['subtotal']) ? number_format($detail['subtotal'], 0, ',', '.') : '' ?>
                                                </span>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="this.closest('tr').remove(); updateTotalHarga();">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4" class="text-end"><b>Total Harga</b></td>
                                            <td colspan="2" class="fw-bold text-success">
                                                Rp. <span id="total-harga-format">0</span>
                                                <input type="hidden" name="total_harga" id="total-harga"
                                                    value="0">
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm mb-3" onclick="addDetailRow()">
                                <i class="bi bi-plus"></i> Tambah Barang
                            </button>
                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-success me-2"><i class="bi bi-save"></i>
                                    Simpan Purchase Order</button>
                                <a href="<?= site_url('po') ?>" class="btn btn-secondary">
                                    <i class="bi bi-x-lg"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalBarang" tabindex="-1" aria-labelledby="modalBarangLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalBarangLabel">Cari Barang</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="search" id="modal-barang-search" class="form-control mb-2"
                            placeholder="Ketik nama barang...">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th class="text-center">Stok</th>
                                    <th class="text-center">Harga Beli</th>
                                    <th class="text-center">Pilih</th>
                                </tr>
                            </thead>
                            <tbody id="modal-barang-list">
                                <?php foreach ($barangs as $b): ?>
                                <tr data-id="<?= esc($b['id']) ?>" data-nama="<?= esc($b['nama_barang']) ?>"
                                    data-stok="<?= esc($b['stok']) ?>" data-harga="<?= esc($b['harga_beli']) ?>"
                                    data-id_satuan="<?= esc($b['id_satuan']) ?>">
                                    <td><?= esc($b['nama_barang']) ?></td>
                                    <td class="text-center"><?= esc($b['stok']) ?></td>
                                    <td class="text-center"><?= esc(number_format($b['harga_beli'], 0, ',', '.')) ?>
                                    </td>
                                    <td class="text-center">
                                        <button type="button"
                                            class="btn btn-success btn-sm pilih-barang-btn">Pilih</button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalSupplier" tabindex="-1" aria-labelledby="modalSupplierLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalSupplierLabel">Cari Supplier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="search" id="modal-supplier-search" class="form-control mb-2"
                            placeholder="Ketik nama supplier...">
                        <table class="table table-bordered table-hover">
                            <thead class="text-center small align-middle">
                                <tr>
                                    <th>Supplier</th>
                                    <th>Alamat</th>
                                    <th>Telepon</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                    <th>Pilih</th>
                                </tr>
                            </thead>
                            <tbody id="modal-supplier-list" class="text-center small align-middle">
                                <?php foreach ($suppliers as $s): ?>
                                <tr data-id="<?= esc($s['id']) ?>" data-nama="<?= esc($s['nama']) ?>">
                                    <td><?= esc($s['nama']) ?><amd>
                                    <td><?= esc($s['alamat'] ?? '-') ?></td>
                                    <td><?= esc($s['no_telp'] ?? '-') ?></td>
                                    <td><?= esc($s['email'] ?? '-') ?></td>
                                    <td>
                                        <?php if ($s['status'] == 'aktif'): ?>
                                        <span class="badge bg-success">Aktif</span>
                                        <?php else: ?>
                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= esc($s['keterangan'] ?? '-') ?></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-success btn-sm pilih-supplier-btn"
                                            <?= $s['status'] == 'aktif' ? '' : 'disabled' ?>>
                                            Pilih
                                        </button>
                                    </td>
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
            Swal.fire({
                icon: 'error',
                title: 'Gagal Simpan!',
                text: '<?= esc(session()->getFlashdata('error')) ?>'
            });
        });
    </script>
    <?php endif; ?>

    <script>
        let detailIndex = <?= count(old('detail') ?? [[]]) ?>;
        const satuanKonversiMap = <?= json_encode($satuanKonversiMap) ?>;

        function updateSatuanOptions(barangSelect, satuanSelect) {
            const idBarang = barangSelect.value;
            satuanSelect.innerHTML = '<option value="">- Pilih Satuan -</option>';
            let defaultSatuanId = null;
            if (satuanKonversiMap[idBarang]) {
                satuanKonversiMap[idBarang].forEach(function(konv) {
                    const konversi = parseInt(konv.konversi) || 1;
                    let labelSatuan = konv.nama_satuan;
                    if (konversi > 1) labelSatuan += ` (${konversi} pcs)`;
                    satuanSelect.innerHTML +=
                        `<option value="${konv.id_satuan}" data-konversi="${konversi}">${labelSatuan}</option>`;
                    if (konversi === 1 || konv.nama_satuan.toLowerCase() === 'pcs') {
                        defaultSatuanId = konv.id_satuan;
                    }
                });
                if (defaultSatuanId) {
                    satuanSelect.value = defaultSatuanId;
                    satuanSelect.dispatchEvent(new Event('change'));
                }
            }
        }

        function updateHargaBeli(row) {
            const barangSelect = row.querySelector('.barang-select');
            const satuanSelect = row.querySelector('.satuan-select');
            const hargaInput = row.querySelector('.harga-input');
            if (!barangSelect || !satuanSelect || !hargaInput) return;

            const hargaDasar = Number(barangSelect.selectedOptions[0]?.getAttribute('data-harga')) || 0;
            const selectedOption = satuanSelect.selectedOptions[0];
            const konversi = selectedOption ? parseInt(selectedOption.getAttribute('data-konversi')) || 1 : 1;

            hargaInput.value = formatRupiahInputValue(hargaDasar * konversi);
        }

        function updateSubtotal(row) {
            const qtyInput = row.querySelector('.qty-input');
            const hargaInput = row.querySelector('.harga-input');
            const subtotalInput = row.querySelector('.subtotal-input');
            const satuanSelect = row.querySelector('.satuan-select');
            const selectedOption = satuanSelect ? satuanSelect.selectedOptions[0] : null;
            const konversi = selectedOption ? parseInt(selectedOption.getAttribute('data-konversi')) || 1 : 1;

            if (!qtyInput || !hargaInput || !subtotalInput) return;

            const qty = parseFloat(qtyInput.value) || 0;
            const harga = getAngkaMentah(hargaInput.value);
            const subtotal = qty * harga * konversi;

            subtotalInput.value = subtotal ? formatRupiahInputValue(subtotal) : '';
        }

        function getKonversi(barangSelect, satuanSelect) {
            const idBarang = barangSelect.value;
            const idSatuan = satuanSelect.value;
            let konversi = 1;
            if (satuanKonversiMap[idBarang]) {
                const satuanData = satuanKonversiMap[idBarang].find(k => k.id_satuan == idSatuan);
                if (satuanData) konversi = parseInt(satuanData.konversi) || 1;
            }
            return konversi;
        }

        function updateHargaKonversi(row) {
            const barangSelect = row.querySelector('.barang-select');
            const satuanSelect = row.querySelector('.satuan-select');
            const hargaInput = row.querySelector('.harga-input');
            const hargaFormat = row.querySelector('.harga-format');
            if (barangSelect && satuanSelect && hargaInput) {
                const hargaDasar = Number(barangSelect.selectedOptions[0]?.getAttribute('data-harga')) || 0;
                const konversi = getKonversi(barangSelect, satuanSelect);
                const hargaFinal = hargaDasar * konversi;
                hargaInput.value = hargaFinal;
                if (hargaFormat) {
                    hargaFormat.value = formatHarga(hargaFinal);
                }
                hargaInput.dispatchEvent(new Event('input'));
            }
        }

        function addDetailRow() {
            const tbody = document.getElementById('detail-barang-body');
            const row = document.createElement('tr');
            row.innerHTML = `
        <td>
            <div class="input-group">
                <select name="detail[${detailIndex}][id_barang]" class="form-select barang-select" required>
                    <option value="">- Pilih Barang -</option>
                    <?php foreach ($barangs as $b): ?>
                        <option value="<?= esc($b['id']) ?>"
                            data-stok="<?= esc($b['stok']) ?>"
                            data-harga="<?= esc($b['harga_beli']) ?>"
                            data-id_satuan="<?= esc($b['id_satuan']) ?>">
                            <?= esc($b['nama_barang']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="openBarangModal(this)">
                    <i class="bi bi-search"></i> Cari
                </button>
            </div>
            <span class="text-success small stok-info" style="display:none;"></span>
        </td>
        <td>
            <select name="detail[${detailIndex}][id_satuan]" class="form-select satuan-select" required>
                <option value="">- Pilih Satuan -</option>
            </select>
        </td>
        <td>
            <input type="number" name="detail[${detailIndex}][qty]" class="form-control qty-input" required min="1">
        </td>
        <td>
            <input type="number" name="detail[${detailIndex}][harga]"
                class="form-control harga-input" required style="display:none;">
            <input type="text" class="form-control harga-format">
        </td>
        <td>
            <input type="number" name="detail[${detailIndex}][subtotal]"
                class="form-control subtotal-input" readonly style="display:none;">
            <span class="subtotal-format form-control bg-light"></span>
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove(); updateTotalHarga();">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
            tbody.appendChild(row);
            detailIndex++;

            const barangSelect = row.querySelector('.barang-select');
            const satuanSelect = row.querySelector('.satuan-select');
            const qtyInput = row.querySelector('.qty-input');
            const hargaInput = row.querySelector('.harga-input');
            const hargaFormat = row.querySelector('.harga-format');

            if (hargaInput && hargaFormat) {
                hargaInput.addEventListener('input', function() {
                    hargaFormat.value = formatRibuan(hargaInput.value);
                });
            }

            if (barangSelect && satuanSelect) {
                barangSelect.addEventListener('change', function() {
                    updateSatuanOptions(barangSelect, satuanSelect);
                    showStok(barangSelect);

                    const harga = barangSelect.selectedOptions[0]?.getAttribute('data-harga') || 0;
                    const hargaInput = row.querySelector('.harga-input');
                    if (hargaInput) {
                        hargaInput.value = harga;
                        hargaInput.dispatchEvent(new Event('input'));
                    }
                    setTimeout(() => {
                        updateHargaBeli(row);
                        updateSubtotal(row);
                        updateTotalHarga();
                    }, 100);
                });
                updateSatuanOptions(barangSelect, satuanSelect);
            }

            if (satuanSelect) {
                satuanSelect.addEventListener('change', function() {
                    updateHargaBeli(row);
                    updateSubtotal(row);
                    updateTotalHarga();
                });
            }

            if (qtyInput) {
                qtyInput.addEventListener('input', function() {
                    updateSubtotal(row);
                    updateTotalHarga();
                });
            }

            addSubtotalListener(row);
            showStok(barangSelect);
        }

        function addSubtotalListener(row) {
            if (!row) return;
            const qtyInput = row.querySelector('.qty-input');
            const hargaInput = row.querySelector('.harga-input');
            const subtotalInput = row.querySelector('.subtotal-input');
            const hargaFormat = row.querySelector('.harga-format');

            function updateSubtotal() {
                const qty = parseFloat(qtyInput.value) || 0;
                const harga = parseFloat(hargaInput.value) || 0;
                subtotalInput.value = qty * harga;
                updateTotalHarga();

                const subtotalFormat = row.querySelector('.subtotal-format');
                if (subtotalFormat) {
                    subtotalFormat.textContent = formatRibuan(subtotalInput.value);
                }
            }

            qtyInput.addEventListener('input', updateSubtotal);
            hargaInput.addEventListener('input', updateSubtotal);
            hargaInput.addEventListener('change', updateSubtotal);

            if (hargaFormat) {
                hargaFormat.addEventListener('input', function() {
                    let val = hargaFormat.value.replace(/[^0-9]/g, '');
                    hargaInput.value = parseInt(val) || 0;
                    hargaFormat.value = formatHarga(hargaInput.value);
                    updateSubtotal();
                });
            }
        }

        function updateTotalHarga() {
            let total = 0;
            document.querySelectorAll('.subtotal-input').forEach(function(input) {
                total += parseFloat(input.value) || 0;
                const formatSpan = input.closest('td').querySelector('.subtotal-format');
                if (formatSpan) {
                    formatSpan.textContent = formatRibuan(input.value);
                }
            });
            document.getElementById('total-harga').value = total;
            document.getElementById('ppn').value = Math.round(total * 0.12);

            document.getElementById('total-harga-format').textContent = formatRibuan(total);
            document.getElementById('ppn-format').textContent = formatRibuan(Math.round(total * 0.12));
        }

        function formatRibuan(value) {
            value = parseFloat(value) || 0;
            return value.toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
        }

        function formatHarga(value) {
            value = parseFloat(value) || 0;
            if (value % 1 === 0) {
                return value.toLocaleString('id-ID', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                });
            }
            return value.toLocaleString('id-ID', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function showStok(select) {
            const stok = select.selectedOptions[0].getAttribute('data-stok');
            const row = select.closest('tr');
            const info = row ? row.querySelector('.stok-info') : null;
            if (info) {
                if (select.value && stok !== null) {
                    info.innerHTML = `<strong>Stok: ${parseInt(stok).toLocaleString('id-ID')} pcs</strong>`;
                    info.style.display = '';
                } else {
                    info.textContent = '';
                    info.style.display = 'none';
                }
            }
        }

        function isBarangSudahDipilih(idBarang, excludeSelect = null) {
            let found = false;
            document.querySelectorAll('.barang-select').forEach(function(select) {
                if (select !== excludeSelect && select.value === idBarang && idBarang !== '') {
                    found = true;
                }
            });
            return found;
        }

        function addAntiDuplikatBarangListener(select) {
            select.addEventListener('change', function() {
                const idBarang = this.value;
                if (isBarangSudahDipilih(idBarang, this)) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Barang sudah dipilih!',
                        text: 'Barang yang sama tidak boleh diinput lebih dari satu kali dalam satu order.',
                    });
                    this.value = '';
                    this.dispatchEvent(new Event('change'));
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('#detail-barang-body tr').forEach(function(row) {
                const barangSelect = row.querySelector('.barang-select');
                const satuanSelect = row.querySelector('.satuan-select');
                if (barangSelect && satuanSelect) {
                    updateSatuanOptions(barangSelect, satuanSelect);
                    barangSelect.addEventListener('change', function() {
                        updateSatuanOptions(barangSelect, satuanSelect);
                        showStok(barangSelect);
                        setTimeout(() => {
                            updateHargaBeli(row);
                            updateSubtotal(row);
                            updateTotalHarga();
                        }, 100);
                    });
                }
                if (satuanSelect) {
                    satuanSelect.addEventListener('change', function() {
                        updateHargaBeli(row);
                        updateSubtotal(row);
                        updateTotalHarga();
                    });
                }
                const qtyInput = row.querySelector('.qty-input');
                if (qtyInput) {
                    qtyInput.addEventListener('input', function() {
                        updateSubtotal(row);
                        updateTotalHarga();
                    });
                }
                addSubtotalListener(row);
                showStok(barangSelect);
            });
            updateTotalHarga();

            document.querySelectorAll('.barang-select').forEach(function(select) {
                showStok(select);
                select.addEventListener('change', function() {
                    const row = this.closest('tr');
                    const satuanSelect = row.querySelector(
                        '.satuan-select');
                    if (satuanSelect) satuanSelect.value = "1";
                    showStok(this);
                    updateHargaKonversi(row);
                });
            });
            document.querySelectorAll('.satuan-select').forEach(function(select) {
                select.addEventListener('change', function() {
                    const row = this.closest('tr');
                    updateHargaKonversi(row);
                });
            });

            document.querySelectorAll('.harga-input').forEach(function(input) {
                input.addEventListener('input', function() {
                    const hargaFormat = input.closest('tr').querySelector('.harga-format');
                    if (hargaFormat) {
                        hargaFormat.value = formatHarga(input.value);
                    }
                });
            });

            document.getElementById('detail-barang-body').addEventListener('input', updateTotalHarga);
            document.getElementById('detail-barang-body').addEventListener('change', updateTotalHarga);

            const tanggalInput = document.getElementById('tanggal_terbit');
            const noPoInput = document.getElementById('no_po');
            if (tanggalInput && noPoInput) {
                tanggalInput.addEventListener('change', function() {
                    fetch('<?= site_url('po/generateNoPO') ?>?tanggal_terbit=' + encodeURIComponent(this
                            .value))
                        .then(response => response.json())
                        .then(data => {
                            noPoInput.value = data.no_po;
                        });
                });
            }

            // Tambahkan listener anti duplikat pada semua barang-select yang sudah ada
            document.querySelectorAll('.barang-select').forEach(function(select) {
                addAntiDuplikatBarangListener(select);
            });

            // Modifikasi addDetailRow agar listener anti duplikat otomatis ditambahkan pada row baru
            const oldAddDetailRow = window.addDetailRow;
            window.addDetailRow = function() {
                oldAddDetailRow();
                // Ambil row terakhir yang baru ditambahkan
                const rows = document.querySelectorAll('#detail-barang-body tr');
                if (rows.length > 0) {
                    const lastRow = rows[rows.length - 1];
                    const barangSelect = lastRow.querySelector('.barang-select');
                    if (barangSelect) {
                        addAntiDuplikatBarangListener(barangSelect);
                    }
                }
            };

            // Validasi juga saat pilih barang dari modal
            document.getElementById('modal-barang-list').addEventListener('click', function(e) {
                if (e.target.classList.contains('pilih-barang-btn')) {
                    const row = e.target.closest('tr');
                    const idBarang = row.getAttribute('data-id');
                    if (isBarangSudahDipilih(idBarang, currentSelectBarang)) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Barang sudah dipilih!',
                            text: 'Barang yang sama tidak boleh diinput lebih dari satu kali dalam satu order.',
                        });
                        var modal = bootstrap.Modal.getInstance(document.getElementById('modalBarang'));
                        modal.hide();
                        return;
                    }
                    if (currentSelectBarang) {
                        currentSelectBarang.value = idBarang;
                        currentSelectBarang.dispatchEvent(new Event('change'));
                        showStok(currentSelectBarang);
                    }
                    var modal = bootstrap.Modal.getInstance(document.getElementById('modalBarang'));
                    modal.hide();
                }
            });
        });

        let currentSelectBarang = null;

        function openBarangModal(btn) {
            currentSelectBarang = btn.closest('td').querySelector('.barang-select');
            document.getElementById('modal-barang-search').value = '';
            filterModalBarang('');
            var modal = new bootstrap.Modal(document.getElementById('modalBarang'));
            modal.show();
        }

        function filterModalBarang(keyword) {
            keyword = keyword.toLowerCase();
            document.querySelectorAll('#modal-barang-list tr').forEach(function(row) {
                const nama = row.getAttribute('data-nama').toLowerCase();
                if (nama.includes(keyword) || keyword === '') {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        document.getElementById('modal-barang-search').addEventListener('input', function() {
            filterModalBarang(this.value);
        });

        document.getElementById('modal-barang-list').addEventListener('click', function(e) {
            if (e.target.classList.contains('pilih-barang-btn')) {
                const row = e.target.closest('tr');
                if (currentSelectBarang) {
                    currentSelectBarang.value = row.getAttribute('data-id');
                    currentSelectBarang.dispatchEvent(new Event('change'));

                    const harga = row.getAttribute('data-harga') || 0;
                    const hargaInput = currentSelectBarang.closest('tr').querySelector('.harga-input');
                    const hargaFormat = currentSelectBarang.closest('tr').querySelector('.harga-format');
                    if (hargaInput) {
                        hargaInput.value = harga;
                        hargaInput.dispatchEvent(new Event('input'));
                    }
                    if (hargaFormat) {
                        hargaFormat.value = formatHarga(harga);
                    }

                    const idSatuanDefault = row.getAttribute('data-id_satuan');
                    const satuanSelect = currentSelectBarang.closest('tr').querySelector(
                        '.satuan-select');
                    if (satuanSelect && idSatuanDefault) {
                        satuanSelect.value = idSatuanDefault;
                        satuanSelect.dispatchEvent(new Event('change'));
                    }

                    showStok(currentSelectBarang);

                    const qtyInput = currentSelectBarang.closest('tr').querySelector('.qty-input');
                    if (qtyInput) {
                        qtyInput.dispatchEvent(new Event('input'));
                    }
                }
                var modal = bootstrap.Modal.getInstance(document.getElementById('modalBarang'));
                modal.hide();
            }
        });

        let currentSelectSupplier = document.getElementById('id_supplier');

        function openSupplierModal(btn) {
            currentSelectSupplier = btn.closest('.input-group').querySelector('select[name="id_supplier"]');
            document.getElementById('modal-supplier-search').value = '';
            filterModalSupplier('');
            var modal = new bootstrap.Modal(document.getElementById('modalSupplier'));
            modal.show();
        }

        function filterModalSupplier(keyword) {
            keyword = keyword.toLowerCase();
            document.querySelectorAll('#modal-supplier-list tr').forEach(function(row) {
                const nama = row.getAttribute('data-nama').toLowerCase();
                if (nama.includes(keyword) || keyword === '') {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        document.getElementById('modal-supplier-search').addEventListener('input', function() {
            filterModalSupplier(this.value);
        });

        document.getElementById('modal-supplier-list').addEventListener('click', function(e) {
            if (e.target.classList.contains('pilih-supplier-btn')) {
                const row = e.target.closest('tr');
                if (currentSelectSupplier) {
                    currentSelectSupplier.value = row.getAttribute('data-id');
                    currentSelectSupplier.dispatchEvent(new Event('change'));
                }
                var modal = bootstrap.Modal.getInstance(document.getElementById('modalSupplier'));
                modal.hide();
            }
        });
    </script>
</x-app-layout>
