<x-app-layout>
    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white text-center">
                        <h5 class="card-title mb-0"><i class="bi bi-bag-plus"></i> Form Tambah Pembelian</h5>
                    </div>
                    <div class="card-body">
                        <form action="<?= site_url('pembelian/save') ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="row mb-3 align-items-center">
                                <label for="no_faktur" class="col-md-3 col-form-label"><b>No. Faktur</b></label>
                                <div class="col-md-9">
                                    <input type="text" name="no_faktur" id="no_faktur" class="form-control" required>
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label for="tanggal_terbit" class="col-md-3 col-form-label"><b>Tanggal
                                        Terbit</b></label>
                                <div class="col-md-9">
                                    <input type="date" name="tanggal_terbit" id="tanggal_terbit" class="form-control"
                                        required value="<?= date('Y-m-d') ?>">
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
                                            <option value="<?= esc($s['id']) ?>"
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
                                    <textarea name="keterangan" id="keterangan" class="form-control"><?= old('keterangan') ?></textarea>
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
                                            <th>Harga Beli</th>
                                            <th colspan="2">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody id="detail-barang-body">
                                        <?php
                                    $details = old('detail') ?? [[]];
                                    foreach ($details as $i => $detail):
                                    ?>
                                        <tr>
                                            <td>
                                                <div class="input-group">
                                                    <select name="detail[<?= $i ?>][id_barang]"
                                                        class="form-select barang-select" required
                                                        onchange="showStok(this)">
                                                        <option value="">- Pilih Barang -</option>
                                                        <?php foreach ($barangs as $b): ?>
                                                        <option value="<?= esc($b['id']) ?>"
                                                            data-stok="<?= esc($b['stok']) ?>"
                                                            data-harga="<?= esc($b['harga_beli']) ?>"
                                                            data-id_satuan="<?= esc($b['id_satuan']) ?>"
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
                                                <span class="text-success small stok-info"
                                                    style="display:none;"></span>
                                            </td>
                                            <td>
                                                <select name="detail[<?= $i ?>][id_satuan]"
                                                    class="form-select satuan-select" required>
                                                    <option value="">- Pilih Satuan -</option>
                                                    <?php
                                                    if (!empty($detail['id_barang']) && isset($satuanKonversiMap[$detail['id_barang']])):
                                                        foreach ($satuanKonversiMap[$detail['id_barang']] as $konv):
                                                    ?>
                                                    <option value="<?= $konv['id_satuan'] ?>"
                                                        data-konversi="<?= $konv['konversi'] ?>"
                                                        <?= isset($detail['id_satuan']) && $detail['id_satuan'] == $konv['id_satuan'] ? 'selected' : '' ?>>
                                                        <?=
                                                    esc($konv['nama_satuan'])
                                                    $konv['konversi'] > 1 ? " ({$konv['konversi']} pcs)" : ''
                                                    ?>
                                                    </option>
                                                    <?php
                                                        endforeach;
                                                    endif;
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="detail[<?= $i ?>][qty]"
                                                    class="form-control qty-input"
                                                    value="<?= isset($detail['qty']) ? esc($detail['qty']) : '' ?>"
                                                    required min="1">
                                            </td>
                                            <td>
                                                <input type="text" name="detail[<?= $i ?>][harga_beli]"
                                                    class="form-control harga-input"
                                                    value="<?= isset($detail['harga_beli']) ? esc($detail['harga_beli']) : '' ?>"
                                                    required>
                                            </td>
                                            <td>
                                                <input type="text" name="detail[<?= $i ?>][subtotal]"
                                                    class="form-control subtotal-input"
                                                    value="<?= isset($detail['subtotal']) ? esc($detail['subtotal']) : '' ?>"
                                                    readonly>
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
                                            <td colspan="2" class="fw-bold text-success">Rp.
                                                <span id="total-harga-label">0</span>
                                                <input type="hidden" name="total_harga" id="total-harga"
                                                    value="0">
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm" onclick="addDetailRow()">
                                <i class="bi bi-plus"></i> Tambah Barang
                            </button>
                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-success me-2"><i class="bi bi-save"></i>
                                    Simpan Pembelian</button>
                                <a href="<?= site_url('pembelian') ?>" class="btn btn-secondary">
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
                                    <td><?= esc($s['nama']) ?></td>
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
                                            Pilih</button>
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

        function formatRupiahInputValue(angka) {
            angka = Number(angka);
            if (angka % 1 === 0) {
                return angka.toLocaleString('id-ID');
            } else {
                return angka.toLocaleString('id-ID', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }
        }

        function getAngkaMentah(str) {
            str = str.replace(/\./g, '').replace(',', '.');
            if (!str) return 0;
            return Number(str);
        }

        function formatInputOnChange(input) {
            let angka = getAngkaMentah(input.value);
            input.value = angka ? formatRupiahInputValue(angka) : '';
        }

        function updateSatuanOptions(barangSelect, satuanSelect) {
            const idBarang = barangSelect.value;
            satuanSelect.innerHTML = '<option value="">- Pilih Satuan -</option>';

            let defaultSatuanId = null;

            if (satuanKonversiMap[idBarang]) {
                satuanKonversiMap[idBarang].forEach(function(konv) {
                    const konversi = parseInt(konv.konversi) || 1;
                    let labelSatuan = `${konv.nama_satuan}`;
                    if (konversi > 1) {
                        labelSatuan += ` (${konversi} pcs)`;
                    }

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

        function updateTotalHarga() {
            let total = 0;
            document.querySelectorAll('.subtotal-input').forEach(function(input) {
                total += getAngkaMentah(input.value);
            });
            const totalHargaLabel = document.getElementById('total-harga-label');
            const totalHargaInput = document.getElementById('total-harga');
            if (totalHargaLabel) {
                totalHargaLabel.textContent = total ? formatRupiahInputValue(total) : '0';
            }
            if (totalHargaInput) {
                totalHargaInput.value = total;
            }
        }

        function addSubtotalListener(row) {
            if (!row) return;
            const qtyInput = row.querySelector('.qty-input');
            const hargaInput = row.querySelector('.harga-input');
            const subtotalInput = row.querySelector('.subtotal-input');

            if (hargaInput) {
                formatInputOnChange(hargaInput);
                hargaInput.addEventListener('input', function() {
                    formatInputOnChange(hargaInput);
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
            if (subtotalInput) {
                formatInputOnChange(subtotalInput);
                subtotalInput.addEventListener('input', function() {
                    formatInputOnChange(subtotalInput);
                    updateTotalHarga();
                });
            }
            updateSubtotal(row);
        }

        function showStok(select) {
            const stok = select.selectedOptions[0]?.getAttribute('data-stok');
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

        function addDetailRow() {
            const tbody = document.getElementById('detail-barang-body');
            const row = document.createElement('tr');
            row.innerHTML = `
        <td>
            <div class="input-group">
                <select name="detail[${detailIndex}][id_barang]" class="form-select barang-select" required onchange="showStok(this)">
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
            <input type="text" name="detail[${detailIndex}][harga_beli]" class="form-control harga-input" required>
        </td>
        <td>
            <input type="text" name="detail[${detailIndex}][subtotal]" class="form-control subtotal-input" readonly>
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

            if (barangSelect && satuanSelect) {
                barangSelect.addEventListener('change', function() {
                    updateSatuanOptions(barangSelect, satuanSelect);
                    showStok(barangSelect);
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
                const nama = row.getAttribute('data-nama')?.toLowerCase();
                if (nama && (nama.includes(keyword) || keyword === '')) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
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

            document.querySelectorAll('.harga-input, .subtotal-input').forEach(function(input) {
                formatInputOnChange(input);
            });

            document.querySelector('form').addEventListener('submit', function(e) {
                document.querySelectorAll('.harga-input, .subtotal-input').forEach(function(input) {
                    input.value = getAngkaMentah(input.value);
                });
                const totalHargaInput = document.getElementById('total-harga');
                if (totalHargaInput) {
                    totalHargaInput.value = getAngkaMentah(totalHargaInput.value);
                }
            });

            const tanggalInput = document.getElementById('tanggal_terbit');
            const noFakturInput = document.getElementById('no_faktur');
            if (tanggalInput && noFakturInput) {
                function fetchNoFaktur() {
                    fetch('<?= site_url('pembelian/generateNoFakturAjax') ?>?tanggal_terbit=' + encodeURIComponent(
                            tanggalInput
                            .value))
                        .then(response => response.json())
                        .then(data => {
                            if (data && data.no_faktur) {
                                noFakturInput.value = data.no_faktur;
                            } else {
                                const d = new Date(tanggalInput.value || Date.now());
                                const tahun = d.getFullYear();
                                const bulan = (d.getMonth() + 1).toString().padStart(2, '0');
                                noFakturInput.value = `PB/${tahun}/0001/${bulan}`;
                            }
                        })
                        .catch(err => {
                            const d = new Date(tanggalInput.value || Date.now());
                            const tahun = d.getFullYear();
                            const bulan = (d.getMonth() + 1).toString().padStart(2, '0');
                            noFakturInput.value = `PB/${tahun}/0001/${bulan}`;
                            console.error('generateNoFakturAjax error:', err);
                        });
                }
                tanggalInput.addEventListener('change', fetchNoFaktur);
                fetchNoFaktur();
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
                        showStok(currentSelectBarang);
                    }
                    var modal = bootstrap.Modal.getInstance(document.getElementById('modalBarang'));
                    modal.hide();
                }
            });
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
