<x-app-layout>
    {{-- 
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif --}}

    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-warning text-dark text-center">
                        <h5 class="card-title mb-0"><i class="bi bi-bag-plus"></i> Form Edit Pembelian</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('purchases.update', $purchaseOrder->id) }}" method="post">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3 align-items-center">
                                <label for="invoice_number" class="col-md-3 col-form-label"><b>No.
                                        Faktur</b></label>
                                <div class="col-md-9">
                                    <input type="text" name="invoice_number" id="invoice_number" class="form-control"
                                        required value="{{ old('invoice_number', $purchaseOrder->invoice_number) }}">
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="issue_date" class="col-md-3 col-form-label"><b>Tanggal
                                        Terbit</b></label>
                                <div class="col-md-9">
                                    <input type="date" name="issue_date" id="issue_date" class="form-control"
                                        required value="{{ old('issue_date', $purchaseOrder->issue_date) }}">
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="supplier_id" class="col-md-3 col-form-label"><b>Supplier</b></label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select name="supplier_id" id="supplier_id" class="form-select" required>
                                            <option value="">- Pilih Supplier -</option>
                                            @foreach ($suppliers as $supplier)
                                                @if ($supplier->status === 'active')
                                                    <option value="{{ $supplier->id }}"
                                                        {{ $supplier->id == $purchaseOrder->supplier_id ? 'selected' : '' }}>
                                                        {{ $supplier->name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-outline-info btn-sm"
                                            onclick="openSupplierModal(this)">
                                            <i class="bi bi-search"></i> Cari
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="payment_method" class="col-md-3 col-form-label"><b>Metode
                                        Pembayaran</b></label>
                                <div class="col-md-9">
                                    <select name="payment_method" id="payment_method" class="form-select" required>
                                        <option value="">- Pilih Metode Pembayaran -</option>
                                        <option value="cash"
                                            {{ old('payment_method', $purchaseOrder->payment_method) == 'cash' ? 'selected' : '' }}>
                                            Cash</option>
                                        <option value="credit"
                                            {{ old('payment_method', $purchaseOrder->payment_method) == 'credit' ? 'selected' : '' }}>
                                            Kredit</option>
                                        <option value="transfer"
                                            {{ old('payment_method', $purchaseOrder->payment_method) == 'transfer' ? 'selected' : '' }}>
                                            Transfer</option>
                                        <option value="debit"
                                            {{ old('payment_method', $purchaseOrder->payment_method) == 'debit' ? 'selected' : '' }}>
                                            Debit</option>
                                        <option value="e-wallet"
                                            {{ old('payment_method', $purchaseOrder->payment_method) == 'e-wallet' ? 'selected' : '' }}>
                                            E-Wallet</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="created_by" class="col-md-3 col-form-label"><b>Dibuat Oleh</b></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control bg-light" readonly
                                        value="{{ $purchaseOrder->createdBy->name ?? '-' }}">
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="updated_by" class="col-md-3 col-form-label"><b>Diupdate Oleh</b></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control bg-light" readonly
                                        value="{{ $purchaseOrder->updatedBy->name ?? '-' }}">
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="status" class="col-md-3 col-form-label"><b>Status</b></label>
                                <div class="col-md-9">
                                    <select name="status" id="status" class="form-select" required>
                                        <option value="">- Pilih Status -</option>
                                        <option value="draft"
                                            {{ old('status', $purchaseOrder->status) == 'draft' ? 'selected' : '' }}>
                                            Draft</option>
                                        <option value="process"
                                            {{ old('status', $purchaseOrder->status) == 'process' ? 'selected' : '' }}>
                                            Proses</option>
                                        <option value="completed"
                                            {{ old('status', $purchaseOrder->status) == 'completed' ? 'selected' : '' }}>
                                            Selesai (Lunas)</option>
                                        <option value="debt"
                                            {{ old('status', $purchaseOrder->status) == 'debt' ? 'selected' : '' }}>
                                            Utang</option>
                                        <option value="return"
                                            {{ old('status', $purchaseOrder->status) == 'return' ? 'selected' : '' }}>
                                            Retur</option>
                                        <option value="cancelled"
                                            {{ old('status', $purchaseOrder->status) == 'cancelled' ? 'selected' : '' }}>
                                            Batal</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="description" class="col-md-3 col-form-label"><b>Keterangan</b></label>
                                <div class="col-md-9">
                                    <textarea name="description" id="description" class="form-control">{{ old('description', $purchaseOrder->description) }}</textarea>
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
                                        @foreach ($details as $i => $detail)
                                            <tr>
                                                <td>
                                                    <div class="input-group">
                                                        <select name="detail[{{ $i }}][item_id]"
                                                            class="form-select barang-select" required
                                                            onchange="showStok(this)">
                                                            <option value="">- Pilih Barang -</option>
                                                            @foreach ($items as $item)
                                                                <option value="{{ $item->id }}"
                                                                    data-stok="{{ $item->stock }}"
                                                                    data-harga="{{ $item->purchase_price }}"
                                                                    data-id_satuan="{{ $item->unit_id }}"
                                                                    {{ $item->id == $detail->item_id ? 'selected' : '' }}>
                                                                    {{ $item->name }}
                                                                </option>
                                                            @endforeach
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
                                                    <select name="detail[{{ $i }}][unit_id]"
                                                        class="form-select satuan-select" required
                                                        value="{{ $detail->unit_id }}">
                                                        <option value="">- Pilih Satuan -</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number"
                                                        name="detail[{{ $i }}][quantity]"
                                                        class="form-control qty-input" required min="1"
                                                        value="{{ $detail->quantity }}">
                                                </td>
                                                <td>
                                                    <input type="text"
                                                        name="detail[{{ $i }}][unit_price]"
                                                        class="form-control harga-input" required
                                                        value="{{ number_format($detail->unit_price, 0, ',', '.') }}">
                                                </td>
                                                <td>
                                                    <input type="text"
                                                        name="detail[{{ $i }}][subtotal]"
                                                        class="form-control subtotal-input" readonly
                                                        value="{{ number_format($detail->subtotal, 0, ',', '.') }}">
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="this.closest('tr').remove(); updateTotalHarga();">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4" class="text-end"><b>Total Harga</b></td>
                                            <td colspan="2" class="fw-bold text-success">Rp.
                                                <span id="total-harga-label">
                                                    {{ number_format($purchaseOrder->total_amount ?? 0, 0, ',', '.') }}
                                                </span>
                                                <input type="hidden" name="total_amount" id="total-harga"
                                                    value="{{ $purchaseOrder->total_amount ?? 0 }}">
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <button type="button" class="btn btn-success btn-sm mb-3" onclick="addDetailRow()">
                                <i class="bi bi-plus"></i> Tambah Barang
                            </button>
                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-warning me-2"><i class="bi bi-save"></i>
                                    Update Pembelian</button>
                                <a href="{{ route('purchases.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-lg"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Barang -->
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
                                @foreach ($items as $item)
                                    <tr data-id="{{ $item->id }}" data-nama="{{ $item->name }}"
                                        data-stok="{{ $item->stock }}" data-harga="{{ $item->purchase_price }}"
                                        data-id_satuan="{{ $item->unit_id }}">
                                        <td>{{ $item->name }}</td>
                                        <td class="text-center">{{ $item->stock }}</td>
                                        <td class="text-center">
                                            {{ number_format($item->purchase_price, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <button type="button"
                                                class="btn btn-success btn-sm pilih-barang-btn">Pilih</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Supplier -->
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
                                @foreach ($suppliers as $supplier)
                                    <tr data-id="{{ $supplier->id }}" data-nama="{{ $supplier->name }}">
                                        <td>{{ $supplier->name }}</td>
                                        <td>{{ $supplier->address ?? '-' }}</td>
                                        <td>{{ $supplier->phone ?? '-' }}</td>
                                        <td>{{ $supplier->email ?? '-' }}</td>
                                        <td>
                                            @if ($supplier->status === 'active')
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td>{{ $supplier->description ?? '-' }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-success btn-sm pilih-supplier-btn"
                                                {{ $supplier->status === 'active' ? '' : 'disabled' }}>
                                                Pilih</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Update!',
                    text: '{{ session('error') }}'
                });
            });
        </script>
    @endif

    <script>
        let detailIndex = {{ count($details) }};
        const satuanKonversiMap = {{ json_encode($unitConversionMap) }};

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

        function updateSatuanOptions(barangSelect, satuanSelect, selectedId = null) {
            const idBarang = barangSelect.value;
            satuanSelect.innerHTML = '<option value="">- Pilih Satuan -</option>';
            let defaultSatuanId = null;

            if (satuanKonversiMap[idBarang]) {
                satuanKonversiMap[idBarang].forEach(function(konv) {
                    const konversi = parseInt(konv.konversi) || 1;
                    let labelSatuan = konv.nama_satuan;
                    if (konversi > 1) labelSatuan += ` (${konversi} pcs)`;
                    satuanSelect.innerHTML +=
                        `<option value="${konv.id_satuan}" data-konversi="${konv.konversi}">${labelSatuan}</option>`;
                    if (konversi === 1 || konv.nama_satuan.toLowerCase() === 'pcs') {
                        defaultSatuanId = konv.id_satuan;
                    }
                });

                if (selectedId) {
                    satuanSelect.value = selectedId;
                } else if (defaultSatuanId) {
                    satuanSelect.value = defaultSatuanId;
                }
                satuanSelect.dispatchEvent(new Event('change'));
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

            if (!qtyInput || !hargaInput || !subtotalInput) return;

            const qty = parseFloat(qtyInput.value) || 0;
            const harga = getAngkaMentah(hargaInput.value);
            const subtotal = qty * harga;

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
                <select name="detail[${detailIndex}][item_id]" class="form-select barang-select" required>
                    <option value="">- Pilih Barang -</option>
                    @foreach ($items as $item)
                        <option value="{{ $item->id }}"
                            data-stok="{{ $item->stock }}"
                            data-harga="{{ $item->purchase_price }}"
                            data-id_satuan="{{ $item->unit_id }}">
                            {{ $item->name }}
                        </option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="openBarangModal(this)">
                    <i class="bi bi-search"></i> Cari
                </button>
            </div>
            <span class="text-success small stok-info" style="display:none;"></span>
        </td>
        <td>
            <select name="detail[${detailIndex}][unit_id]" class="form-select satuan-select" required>
                <option value="">- Pilih Satuan -</option>
            </select>
        </td>
        <td>
            <input type="number" name="detail[${detailIndex}][quantity]" class="form-control qty-input" required min="1">
        </td>
        <td>
            <input type="text" name="detail[${detailIndex}][unit_price]" class="form-control harga-input" required>
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
                const currentSatuanId = satuanSelect.getAttribute('value') || satuanSelect.value;

                if (barangSelect && satuanSelect) {
                    updateSatuanOptions(barangSelect, satuanSelect, currentSatuanId);

                    barangSelect.addEventListener('change', function() {
                        updateSatuanOptions(barangSelect, satuanSelect, satuanSelect.value);
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

                addSubtotalListener(row);
                showStok(barangSelect);
            });

            updateTotalHarga();

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

            document.querySelector('form').addEventListener('submit', function(e) {
                document.querySelectorAll('.harga-input, .subtotal-input').forEach(function(input) {
                    input.value = getAngkaMentah(input.value);
                });
                const totalHargaInput = document.getElementById('total-harga');
                if (totalHargaInput) {
                    totalHargaInput.value = getAngkaMentah(totalHargaInput.value);
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
            })
        });

        let currentSelectSupplier = document.getElementById('supplier_id');

        function openSupplierModal(btn) {
            currentSelectSupplier = btn.closest('.input-group').querySelector('select[name="supplier_id"]');
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
