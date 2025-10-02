<x-app-layout>
    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-warning text-dark text-center">
                        <h5 class="card-title mb-0"><i class="bi bi-cart-check"></i> Form Edit Penjualan</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('sales-orders.update', $salesOrder->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3 align-items-center">
                                <label for="invoice_number" class="col-md-3 col-form-label"><b>No. Nota</b></label>
                                <div class="col-md-9">
                                    <input type="text" name="invoice_number" id="invoice_number"
                                        class="form-control @error('invoice_number') is-invalid @enderror" required
                                        value="{{ old('invoice_number', $salesOrder->invoice_number) }}">
                                    @error('invoice_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="issue_date" class="col-md-3 col-form-label"><b>Tanggal Terbit</b></label>
                                <div class="col-md-9">
                                    <input type="date" name="issue_date" id="issue_date"
                                        class="form-control @error('issue_date') is-invalid @enderror" required
                                        value="{{ old('issue_date', $salesOrder->issue_date) }}">
                                    @error('issue_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="customer_id" class="col-md-3 col-form-label"><b>Customer</b></label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select name="customer_id" id="customer_id"
                                            class="form-select @error('customer_id') is-invalid @enderror" required>
                                            <option value="">- Pilih Customer -</option>
                                            @foreach ($customers as $customer)
                                                @if ($customer->status === 'active')
                                                    <option value="{{ $customer->id }}"
                                                        {{ old('customer_id', $salesOrder->customer_id) == $customer->id ? 'selected' : '' }}>
                                                        {{ $customer->name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-outline-info btn-sm"
                                            onclick="openCustomerModal(this)">
                                            <i class="bi bi-search"></i> Cari
                                        </button>
                                    </div>
                                    @error('customer_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="payment_method" class="col-md-3 col-form-label"><b>Metode
                                        Pembayaran</b></label>
                                <div class="col-md-9">
                                    <select name="payment_method" id="payment_method"
                                        class="form-select @error('payment_method') is-invalid @enderror" required>
                                        <option value="">- Pilih Metode Pembayaran -</option>
                                        <option value="cash"
                                            {{ old('payment_method', $salesOrder->payment_method) == 'cash' ? 'selected' : '' }}>
                                            Cash</option>
                                        <option value="credit"
                                            {{ old('payment_method', $salesOrder->payment_method) == 'credit' ? 'selected' : '' }}>
                                            Kredit</option>
                                        <option value="transfer"
                                            {{ old('payment_method', $salesOrder->payment_method) == 'transfer' ? 'selected' : '' }}>
                                            Transfer</option>
                                        <option value="debit"
                                            {{ old('payment_method', $salesOrder->payment_method) == 'debit' ? 'selected' : '' }}>
                                            Debit</option>
                                        <option value="e-wallet"
                                            {{ old('payment_method', $salesOrder->payment_method) == 'e-wallet' ? 'selected' : '' }}>
                                            E-Wallet</option>
                                    </select>
                                    @error('payment_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="status" class="col-md-3 col-form-label"><b>Status</b></label>
                                <div class="col-md-9">
                                    <select name="status" id="status"
                                        class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="draft"
                                            {{ old('status', $salesOrder->status ?? 'draft') == 'draft' ? 'selected' : '' }}>
                                            Draft</option>
                                        <option value="process"
                                            {{ old('status', $salesOrder->status ?? 'draft') == 'process' ? 'selected' : '' }}>
                                            Proses</option>
                                        <option value="completed"
                                            {{ old('status', $salesOrder->status ?? 'draft') == 'completed' ? 'selected' : '' }}>
                                            Selesai (Lunas)</option>
                                        <option value="debt"
                                            {{ old('status', $salesOrder->status ?? 'draft') == 'debt' ? 'selected' : '' }}>
                                            Utang</option>
                                        <option value="return"
                                            {{ old('status', $salesOrder->status ?? 'draft') == 'return' ? 'selected' : '' }}>
                                            Retur</option>
                                        <option value="cancelled"
                                            {{ old('status', $salesOrder->status ?? 'draft') == 'cancelled' ? 'selected' : '' }}>
                                            Batal</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label class="col-md-3 col-form-label"><b>Otorisasi</b></label>
                                <div class="col-md-9">
                                    <input type="hidden" name="authorized_by"
                                        value="{{ json_encode($authorizedUsers) }}">
                                    <div class="form-control bg-light" readonly>
                                        {{ implode(', ', $authorizedUsernames) }}
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="description" class="col-md-3 col-form-label"><b>Keterangan</b></label>
                                <div class="col-md-9">
                                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $salesOrder->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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
                                            <th>Harga Jual</th>
                                            <th colspan="2">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody id="detail-barang-body">
                                        @foreach ($details as $index => $detail)
                                            <tr>
                                                <td>
                                                    <div class="input-group">
                                                        <select name="detail[{{ $index }}][item_id]"
                                                            class="form-select barang-select" required
                                                            onchange="showStok(this)">
                                                            <option value="">- Pilih Barang -</option>
                                                            @foreach ($items as $item)
                                                                <option value="{{ $item->id }}"
                                                                    data-stok="{{ $item->stock }}"
                                                                    data-harga="{{ $item->selling_price }}"
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
                                                    <select name="detail[{{ $index }}][unit_id]"
                                                        class="form-select satuan-select" required
                                                        data-satuan-awal="{{ $detail->unit_id }}">
                                                        <option value="">- Pilih Satuan -</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number"
                                                        name="detail[{{ $index }}][quantity]"
                                                        class="form-control qty-input" required min="1"
                                                        value="{{ $detail->quantity }}">
                                                </td>
                                                <td>
                                                    <input type="text"
                                                        name="detail[{{ $index }}][unit_price]"
                                                        class="form-control harga-input" required
                                                        value="{{ number_format($detail->unit_price, 0, ',', '.') }}">
                                                </td>
                                                <td>
                                                    <input type="text"
                                                        name="detail[{{ $index }}][subtotal]"
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
                                                    {{ number_format($salesOrder->total_amount ?? 0, 0, ',', '.') }}
                                                </span>
                                                <input type="hidden" name="total_amount" id="total-harga"
                                                    value="{{ $salesOrder->total_amount ?? 0 }}">
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <button type="button" class="btn btn-success btn-sm mb-3" onclick="addDetailRow()">
                                <i class="bi bi-plus"></i> Tambah Barang
                            </button>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-warning me-2">
                                    <i class="bi bi-save"></i> Update Penjualan
                                </button>
                                <a href="{{ route('sales-orders.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-lg"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Cari Barang -->
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
                                    <th class="text-center">Harga Jual</th>
                                    <th class="text-center">Pilih</th>
                                </tr>
                            </thead>
                            <tbody id="modal-barang-list">
                                @foreach ($items as $item)
                                    <tr data-id="{{ $item->id }}" data-nama="{{ $item->name }}"
                                        data-stok="{{ $item->stock }}" data-harga="{{ $item->selling_price }}"
                                        data-id_satuan="{{ $item->unit_id }}">
                                        <td>{{ $item->name }}</td>
                                        <td class="text-center">{{ $item->stock }}</td>
                                        <td class="text-center">{{ number_format($item->selling_price, 0, ',', '.') }}
                                        </td>
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

        <!-- Modal Cari Customer -->
        <div class="modal fade" id="modalCustomer" tabindex="-1" aria-labelledby="modalCustomerLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCustomerLabel">Cari Customer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="search" id="modal-customer-search" class="form-control mb-2"
                            placeholder="Ketik nama customer...">
                        <table class="table table-bordered table-hover">
                            <thead class="text-center small align-middle">
                                <tr>
                                    <th>Customer</th>
                                    <th>Alamat</th>
                                    <th>Telepon</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                    <th>Pilih</th>
                                </tr>
                            </thead>
                            <tbody id="modal-customer-list" class="text-center small align-middle">
                                @foreach ($customers as $customer)
                                    <tr data-id="{{ $customer->id }}" data-nama="{{ $customer->name }}">
                                        <td>{{ $customer->name }}</td>
                                        <td>{{ $customer->address ?? '-' }}</td>
                                        <td>{{ $customer->phone ?? '-' }}</td>
                                        <td>{{ $customer->email ?? '-' }}</td>
                                        <td>
                                            @if ($customer->status === 'active')
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td>{{ $customer->description ?? '-' }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-success btn-sm pilih-customer-btn"
                                                {{ $customer->status === 'active' ? '' : 'disabled' }}>
                                                Pilih
                                            </button>
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

    @if (session()->getFlashdata('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Simpan!',
                    text: '{{ session()->getFlashdata('error') }}'
                });
            });
        </script>
    @endif

    <script>
        let detailIndex = <?= count($details) ?>;
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
            const stok = parseInt(barangSelect.selectedOptions[0]?.getAttribute('data-stok')) || 0;

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
            }

            const satuanAwal = satuanSelect.getAttribute('data-satuan-awal');
            if (satuanAwal) {
                satuanSelect.value = satuanAwal;
                satuanSelect.dispatchEvent(new Event('change'));
                satuanSelect.removeAttribute('data-satuan-awal');
            } else if (defaultSatuanId) {
                satuanSelect.value = defaultSatuanId;
                satuanSelect.dispatchEvent(new Event('change'));

                const row = satuanSelect.closest('tr');
                const qtyInput = row.querySelector('.qty-input');
                if (qtyInput && !qtyInput.value) {
                    qtyInput.value = 1;
                    qtyInput.dispatchEvent(new Event('input'));
                }
            }
        }

        function setMaxQty(row) {
            const barangSelect = row.querySelector('.barang-select');
            const satuanSelect = row.querySelector('.satuan-select');
            const qtyInput = row.querySelector('.qty-input');
            if (!barangSelect || !satuanSelect || !qtyInput) return;

            const stokSaatIni = parseInt(barangSelect.selectedOptions[0]?.getAttribute('data-stok')) || 0;
            const idSatuan = satuanSelect.value;
            const selectedOption = satuanSelect.querySelector(`option[value="${idSatuan}"]`);
            const konversiBaru = selectedOption ? parseInt(selectedOption.getAttribute('data-konversi')) || 1 : 1;

            const qtyLama = parseInt(qtyInput.defaultValue) || 0;

            const satuanLama = satuanSelect.getAttribute('data-satuan-awal') || idSatuan;
            let konversiLama = 1;

            const idBarang = barangSelect.value;
            if (satuanKonversiMap[idBarang]) {
                const satuanLamaData = satuanKonversiMap[idBarang].find(k => k.id_satuan == satuanLama);
                konversiLama = satuanLamaData ? parseInt(satuanLamaData.konversi) : 1;
            }

            const qtyLamaDasar = qtyLama * konversiLama;

            const stokTersedia = stokSaatIni + qtyLamaDasar;

            const maxQty = konversiBaru > 0 ? Math.floor(stokTersedia / konversiBaru) : stokTersedia;

            qtyInput.setAttribute('max', maxQty);
            qtyInput.setAttribute('placeholder', `Max: ${maxQty.toLocaleString('id-ID')}`);

            if (parseInt(qtyInput.value) > maxQty) {
                qtyInput.value = maxQty;
                updateSubtotal(row);
                updateTotalHarga();
            }
        }

        function updateHargaJual(row) {
            const barangSelect = row.querySelector('.barang-select');
            const satuanSelect = row.querySelector('.satuan-select');
            const hargaInput = row.querySelector('.harga-input');
            if (!barangSelect || !satuanSelect || !hargaInput) return;

            const hargaDasar = Number(barangSelect.selectedOptions[0]?.getAttribute('data-harga')) || 0;
            const idSatuan = satuanSelect.value;
            const selectedOption = satuanSelect.querySelector(`option[value="${idSatuan}"]`);
            const konversi = selectedOption ? parseInt(selectedOption.getAttribute('data-konversi')) || 1 : 1;

            hargaInput.value = formatRupiahInputValue(hargaDasar * konversi);
            hargaInput.dispatchEvent(new Event('input'));
        }

        function updateSubtotal(row) {
            const qtyInput = row.querySelector('.qty-input');
            const hargaInput = row.querySelector('.harga-input');
            const subtotalInput = row.querySelector('.subtotal-input');

            if (!qtyInput || !hargaInput || !subtotalInput) return;

            const maxQty = parseInt(qtyInput.getAttribute('max')) || 999999;
            let qty = parseFloat(qtyInput.value) || 0;

            if (qty > maxQty) {
                qty = maxQty;
                qtyInput.value = maxQty;
            }

            const harga = getAngkaMentah(hargaInput.value);
            const subtotal = qty * harga;

            subtotalInput.value = subtotal ? formatRupiahInputValue(subtotal) : '';
        }

        function updateTotalHarga() {
            let total = 0;
            document.querySelectorAll('.subtotal-input').forEach(function(input) {
                total += getAngkaMentah(input.value);
            });

            const totalHargaInput = document.getElementById('total-harga');
            const totalHargaLabel = document.getElementById('total-harga-label');

            if (totalHargaInput) {
                totalHargaInput.value = total ? formatRupiahInputValue(total) : '';
            }
            if (totalHargaLabel) {
                totalHargaLabel.textContent = total ? formatRupiahInputValue(total) : '0';
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

            if (info && select.value && stok !== null) {
                info.innerHTML = `<strong>Stok: ${parseInt(stok).toLocaleString('id-ID')} pcs</strong>`;
                info.style.display = '';
            } else if (info) {
                info.textContent = '';
                info.style.display = 'none';
            }
        }

        function addDetailRow() {
            const tbody = document.getElementById('detail-barang-body');
            const row = document.createElement('tr');

            row.innerHTML = `
<td>
    <div class="input-group">
        <select name="detail[{{ '${detailIndex}' }}][id_barang]" class="form-select barang-select" required>
            <option value="">- Pilih Barang -</option>
            @foreach ($barangs as $b)
                <option value="{{ $b->id }}"
                    data-stok="{{ $b->stok }}"
                    data-harga="{{ $b->harga_beli }}"
                    data-id_satuan="{{ $b->id_satuan }}">
                    {{ $b->nama_barang }}
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
    <select name="detail[{{ '${detailIndex}' }}][id_satuan]" class="form-select satuan-select" required>
        <option value="">- Pilih Satuan -</option>
    </select>
</td>
<td>
    <input type="number" name="detail[{{ '${detailIndex}' }}][qty]" class="form-control qty-input" required min="1">
</td>
<td>
    <input type="text" name="detail[{{ '${detailIndex}' }}][harga_beli]" class="form-control harga-input" required>
</td>
<td>
    <input type="text" name="detail[{{ '${detailIndex}' }}][subtotal]" class="form-control subtotal-input" readonly>
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
                        setMaxQty(row);
                        updateHargaJual(row);

                        // Auto set qty = 1 jika kosong
                        const qtyInput = row.querySelector('.qty-input');
                        if (qtyInput && !qtyInput.value) {
                            qtyInput.value = 1;
                            qtyInput.dispatchEvent(new Event('input'));
                        }

                        updateSubtotal(row);
                        updateTotalHarga();
                    }, 100);
                });

                updateSatuanOptions(barangSelect, satuanSelect);
            }

            if (satuanSelect) {
                satuanSelect.addEventListener('change', function() {
                    const oldSatuanId = this.getAttribute('data-last-satuan') || this.getAttribute(
                        'data-satuan-awal');
                    const newSatuanId = this.value;

                    this.setAttribute('data-last-satuan', newSatuanId);

                    autoConvertQty(row, oldSatuanId, newSatuanId);

                    setMaxQty(row);
                    updateHargaJual(row);
                    updateSubtotal(row);
                    updateTotalHarga();
                });
            }

            if (qtyInput) {
                qtyInput.addEventListener('input', function() {
                    setMaxQty(row);
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
                const qtyInput = row.querySelector('.qty-input');

                if (qtyInput) {
                    qtyInput.defaultValue = qtyInput.value;
                }

                if (barangSelect && satuanSelect) {
                    updateSatuanOptions(barangSelect, satuanSelect);

                    barangSelect.addEventListener('change', function() {
                        updateSatuanOptions(barangSelect, satuanSelect);
                        showStok(barangSelect);
                        setTimeout(() => {
                            setMaxQty(row);
                            updateHargaJual(row);
                            updateSubtotal(row);
                            updateTotalHarga();
                        }, 100);
                    });
                }

                if (satuanSelect) {
                    satuanSelect.addEventListener('change', function() {
                        const oldSatuanId = this.getAttribute('data-last-satuan') || this
                            .getAttribute(
                                'data-satuan-awal');
                        const newSatuanId = this.value;

                        this.setAttribute('data-last-satuan', newSatuanId);

                        autoConvertQty(row, oldSatuanId, newSatuanId);

                        setMaxQty(row);
                        updateHargaJual(row);
                        updateSubtotal(row);
                        updateTotalHarga();
                    });
                }

                if (qtyInput) {
                    qtyInput.addEventListener('input', function() {
                        updateSubtotal(row);
                        updateTotalHarga();
                    });

                    qtyInput.addEventListener('change', function() {
                        updateSubtotal(row);
                        updateTotalHarga();
                    });
                }

                addSubtotalListener(row);
                showStok(barangSelect);
                setMaxQty(row);
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

        function autoConvertQty(row, oldSatuanId, newSatuanId) {
            const barangSelect = row.querySelector('.barang-select');
            const qtyInput = row.querySelector('.qty-input');
            const idBarang = barangSelect.value;

            if (!idBarang || !qtyInput || !qtyInput.value) return;

            let oldKonversi = 1;
            let newKonversi = 1;

            if (satuanKonversiMap[idBarang]) {
                if (oldSatuanId) {
                    const oldSatuanData = satuanKonversiMap[idBarang].find(k => k.id_satuan == oldSatuanId);
                    if (oldSatuanData) oldKonversi = parseInt(oldSatuanData.konversi) || 1;
                }

                if (newSatuanId) {
                    const newSatuanData = satuanKonversiMap[idBarang].find(k => k.id_satuan == newSatuanId);
                    if (newSatuanData) newKonversi = parseInt(newSatuanData.konversi) || 1;
                }
            }

            if (qtyInput.value && oldKonversi && newKonversi) {
                const currentQty = parseFloat(qtyInput.value) || 0;

                const qtyDasar = currentQty * oldKonversi;
                const newQty = newKonversi > 0 ? Math.floor(qtyDasar / newKonversi) : qtyDasar;

                qtyInput.value = newQty;
                qtyInput.dispatchEvent(new Event('input'));
            }
        }

        let currentSelectCustomer = document.getElementById('id_customer');

        function openCustomerModal(btn) {
            currentSelectCustomer = btn.closest('.input-group').querySelector('select[name="id_customer"]');
            document.getElementById('modal-customer-search').value = '';
            filterModalCustomer('');
            var modal = new bootstrap.Modal(document.getElementById('modalCustomer'));
            modal.show();
        }

        function filterModalCustomer(keyword) {
            keyword = keyword.toLowerCase();
            document.querySelectorAll('#modal-customer-list tr').forEach(function(row) {
                const nama = row.getAttribute('data-nama').toLowerCase();
                if (nama.includes(keyword) || keyword === '') {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        document.getElementById('modal-customer-search').addEventListener('input', function() {
            filterModalCustomer(this.value);
        });

        document.getElementById('modal-customer-list').addEventListener('click', function(e) {
            if (e.target.classList.contains('pilih-customer-btn')) {
                const row = e.target.closest('tr');
                if (currentSelectCustomer) {
                    currentSelectCustomer.value = row.getAttribute('data-id');
                    currentSelectCustomer.dispatchEvent(new Event('change'));
                }
                var modal = bootstrap.Modal.getInstance(document.getElementById('modalCustomer'));
                modal.hide();
            }
        });
    </script>
</x-app-layout>
