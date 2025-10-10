<x-app-layout>
    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white text-center">
                        <h5 class="card-title mb-0"><i class="fas fa-cart-arrow-down"></i> Form Tambah Penjualan</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('sales.store') }}" method="post">
                            @csrf
                            <div class="row mb-3 align-items-center">
                                <label for="invoice_number" class="col-md-3 col-form-label"><b>No. Nota</b></label>
                                <div class="col-md-9">
                                    <input type="text" name="invoice_number" id="invoice_number"
                                        class="form-control @error('invoice_number') is-invalid @enderror" required
                                        value="{{ old('invoice_number') }}">
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
                                        value="{{ old('issue_date', date('Y-m-d')) }}">
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
                                                        {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                        {{ $customer->name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-outline-info btn-sm"
                                            onclick="openCustomerModal(this)">
                                            <i class="fas fa-search"></i> Cari
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
                                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>
                                            Cash</option>
                                        <option value="credit"
                                            {{ old('payment_method') == 'credit' ? 'selected' : '' }}>Kredit</option>
                                        <option value="transfer"
                                            {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer
                                        </option>
                                        <option value="debit"
                                            {{ old('payment_method') == 'debit' ? 'selected' : '' }}>Debit</option>
                                        <option value="e-wallet"
                                            {{ old('payment_method') == 'e-wallet' ? 'selected' : '' }}>E-Wallet
                                        </option>
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
                                        <option value="">- Pilih Status -</option>
                                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft
                                        </option>
                                        <option value="process" {{ old('status') == 'process' ? 'selected' : '' }}>
                                            Proses</option>
                                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>
                                            Selesai (Lunas)</option>
                                        <option value="debt" {{ old('status') == 'debt' ? 'selected' : '' }}>Utang
                                        </option>
                                        <option value="return" {{ old('status') == 'return' ? 'selected' : '' }}>Retur
                                        </option>
                                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>
                                            Batal</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label class="col-md-3 col-form-label"><b>Dibuat Oleh</b></label>
                                <div class="col-md-9">
                                    <div class="form-control bg-light" readonly>
                                        {{ auth()->user()->username ?? 'Unknown' }}
                                        <small class="text-muted">
                                            ({{ now()->format('d/m/Y H:i') }})
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label class="col-md-3 col-form-label"><b>Terakhir Diubah</b></label>
                                <div class="col-md-9">
                                    <div class="form-control bg-light" readonly>
                                        {{ auth()->user()->username ?? 'Unknown' }}
                                        <small class="text-muted">
                                            ({{ now()->format('d/m/Y H:i') }})
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="description" class="col-md-3 col-form-label"><b>Keterangan</b></label>
                                <div class="col-md-9">
                                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
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
                                            <th>Subtotal</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="detail-barang-body">
                                        @php
                                            $details = old('detail') ?? [[]];
                                        @endphp
                                        @foreach ($details as $index => $detail)
                                            <tr>
                                                <td>
                                                    <div class="input-group">
                                                        <select name="details[{{ $index }}][item_id]"
                                                            class="form-select barang-select" required
                                                            onchange="showStok(this)">
                                                            <option value="">- Pilih Barang -</option>
                                                            @foreach ($items as $item)
                                                                <option value="{{ $item->id }}"
                                                                    data-stok="{{ $item->stock }}"
                                                                    data-harga="{{ $item->sell_price }}"
                                                                    data-id_satuan="{{ $item->unit_id }}"
                                                                    {{ isset($detail['item_id']) && $detail['item_id'] == $item->id ? 'selected' : '' }}>
                                                                    {{ $item->item_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                                            onclick="openBarangModal(this)">
                                                            <i class="fas fa-search"></i> Cari
                                                        </button>
                                                    </div>
                                                    <span class="text-success small stok-info"
                                                        style="display:none;"></span>
                                                </td>
                                                <td>
                                                    <select name="details[{{ $index }}][unit_id]"
                                                        class="form-select satuan-select" required>
                                                        <option value="">- Pilih Satuan -</option>
                                                        @if (!empty($detail['item_id']) && isset($unitConversionMap[$detail['item_id']]))
                                                            @foreach ($unitConversionMap[$detail['item_id']] as $conv)
                                                                @php
                                                                    $label = $conv['unit_name'];
                                                                    if ($conv['conversion_value'] > 1) {
                                                                        $label .= " ({$conv['conversion_value']} pcs)";
                                                                    }
                                                                @endphp
                                                                <option value="{{ $conv['unit_id'] }}"
                                                                    data-konversi="{{ $conv['conversion_value'] }}"
                                                                    {{ isset($detail['unit_id']) && $detail['unit_id'] == $conv['unit_id'] ? 'selected' : '' }}>
                                                                    {{ $label }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number"
                                                        name="details[{{ $index }}][quantity]"
                                                        class="form-control qty-input"
                                                        value="{{ $detail['quantity'] ?? '' }}" required
                                                        min="1">
                                                </td>
                                                <td>
                                                    <input type="text"
                                                        name="details[{{ $index }}][unit_price]"
                                                        class="form-control harga-input"
                                                        value="{{ $detail['buy_price'] ?? '' }}" required>
                                                </td>
                                                <td>
                                                    <input type="text"
                                                        name="details[{{ $index }}][subtotal]"
                                                        class="form-control subtotal-input"
                                                        value="{{ $detail['subtotal'] ?? '' }}" readonly>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="this.closest('tr').remove(); updateTotalHarga();">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4" class="text-end"><b>Total Harga</b></td>
                                            <td colspan="2" class="fw-bold text-success">Rp.
                                                <span id="total-harga-label">0</span>
                                                <input type="hidden" name="total_amount" id="total-harga"
                                                    value="0">
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <button type="button" class="btn btn-primary btn-sm mb-3" onclick="addDetailRow();">
                                <i class="fas fa-plus"></i> Tambah Barang
                            </button>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-success me-2">
                                    <i class="fas fa-save"></i> Simpan Penjualan
                                </button>
                                <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Batal
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

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Simpan!',
                    text: '{{ session('error') }}'
                });
            });
        </script>
    @endif

    <script>
        // let detailIndex = {{ count(old('detail') ?? [[]]) }};
        let detailIndex = {{ count($details) }};
        const unitConversionMap = {{ json_encode($unit_conversion_map) }};

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

            if (unitConversionMap[idBarang]) {
                unitConversionMap[idBarang].forEach(function(conv) {
                    const konversi = parseInt(conv.konversi) || 1;
                    const maxQtyUntukSatuan = konversi > 0 ? Math.floor(stok / konversi) : stok;

                    let labelSatuan = conv.nama_satuan;
                    if (konversi > 1) {
                        labelSatuan += ` (${konversi} pcs)`;
                    }

                    satuanSelect.innerHTML +=
                        `<option value="${conv.id_satuan}" data-konversi="${konversi}" data-max-qty="${maxQtyUntukSatuan}">${labelSatuan}</option>`;

                    if (konversi === 1 || conv.nama_satuan.toLowerCase() === 'pcs') {
                        defaultSatuanId = conv.id_satuan;
                    }
                });

                if (defaultSatuanId) {
                    satuanSelect.value = defaultSatuanId;
                    satuanSelect.dispatchEvent(new Event('change'));
                }
            }
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

        function updateMaxQty(row) {
            const satuanSelect = row.querySelector('.satuan-select');
            const qtyInput = row.querySelector('.qty-input');

            if (satuanSelect && qtyInput) {
                const selectedOption = satuanSelect.selectedOptions[0];
                if (selectedOption && selectedOption.value) {
                    const maxQty = parseInt(selectedOption.getAttribute('data-max-qty')) || 0;
                    qtyInput.setAttribute('max', maxQty);
                    qtyInput.setAttribute('placeholder', `Max: ${maxQty.toLocaleString('id-ID')}`);

                    if (parseInt(qtyInput.value) > maxQty) {
                        qtyInput.value = maxQty;
                        updateSubtotal(row);
                        updateTotalHarga();
                    }
                }
            }
        }

        function updateHargaJual(row) {
            const barangSelect = row.querySelector('.barang-select');
            const satuanSelect = row.querySelector('.satuan-select');
            const hargaInput = row.querySelector('.harga-input');
            if (!barangSelect || !satuanSelect || !hargaInput) return;

            const hargaDasar = Number(barangSelect.selectedOptions[0]?.getAttribute('data-harga')) || 0;
            const selectedOption = satuanSelect.selectedOptions[0];
            const konversi = selectedOption ? parseInt(selectedOption.getAttribute('data-konversi')) || 1 : 1;

            hargaInput.value = formatRupiahInputValue(hargaDasar * konversi);
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
                    info.innerHTML = `<strong>Stok Total: ${parseInt(stok).toLocaleString('id-ID')} pcs</strong>`;
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

            // // Buat options untuk select barang
            // let barangOptions = '<option value="">- Pilih Barang -</option>';
            // @foreach ($items as $item)
            //     barangOptions += `<option value="{{ $item->id }}" 
            // data-stok="{{ $item->stock }}" 
            // data-harga="{{ $item->sell_price }}" 
            // data-id_satuan="{{ $item->unit_id }}">
            //     {{ $item->item_name }}
            // </option>`;
            // @endforeach

            row.innerHTML = `
                <td>
                    <div class="input-group">
                        <select name="details[${detailIndex}][item_id]" class="form-select barang-select" required onchange="showStok(this)">
                            ${barangOptions}
                        </select>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="openBarangModal(this)">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                    <span class="text-success small stok-info" style="display:none;"></span>
                </td>
                <td>
                    <select name="details[${detailIndex}][unit_id]" class="form-select satuan-select" required>
                        <option value="">- Pilih Satuan -</option>
                    </select>
                </td>
                <td>
                    <input type="number" name="details[${detailIndex}][quantity]" class="form-control qty-input" required min="1">
                </td>
                <td>
                    <input type="text" name="details[${detailIndex}][sell_price]" class="form-control harga-input" required>
                </td>
                <td>
                    <input type="text" name="details[${detailIndex}][subtotal]" class="form-control subtotal-input" readonly>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove(); updateTotalHarga();">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;

            tbody.appendChild(row);
            detailIndex++;

            // Setup event listeners untuk row baru
            const barangSelect = row.querySelector('.barang-select');
            const satuanSelect = row.querySelector('.satuan-select');
            const qtyInput = row.querySelector('.qty-input');

            if (barangSelect && satuanSelect) {
                barangSelect.addEventListener('change', function() {
                    updateSatuanOptions(barangSelect, satuanSelect);
                    showStok(barangSelect);
                    setTimeout(() => {
                        updateMaxQty(row);
                        updateHargaJual(row);
                        updateSubtotal(row);
                        updateTotalHarga();
                    }, 100);
                });
            }

            if (satuanSelect) {
                satuanSelect.addEventListener('change', function() {
                    updateMaxQty(row);
                    updateHargaJual(row);
                    updateSubtotal(row);
                    updateTotalHarga();
                });
            }

            if (qtyInput) {
                qtyInput.addEventListener('input', function() {
                    updateMaxQty(row);
                    updateSubtotal(row);
                    updateTotalHarga();
                });
            }

            addSubtotalListener(row);
            addAntiDuplikatBarangListener(barangSelect);
            showStok(barangSelect);
        }

        window.addDetailRow = addDetailRow();
        
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
                            updateMaxQty(row);
                            updateHargaJual(row);
                            updateSubtotal(row);
                            updateTotalHarga();
                        }, 100);
                    });
                }

                if (satuanSelect) {
                    satuanSelect.addEventListener('change', function() {
                        updateMaxQty(row);
                        updateHargaJual(row);
                        updateSubtotal(row);
                        updateTotalHarga();
                    });
                }

                const qtyInput = row.querySelector('.qty-input');
                if (qtyInput) {
                    qtyInput.addEventListener('input', function() {
                        updateMaxQty(row);
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
            const noNotaInput = document.getElementById('no_nota');
            if (tanggalInput && noNotaInput) {
                function fetchNoNota() {
                    fetch('{{ route('sales.generate-invoice-number') }}?tanggal_terbit=' + encodeURIComponent(
                            tanggalInput
                            .value))
                        .then(response => response.json())
                        .then(data => {
                            if (data && data.no_nota) {
                                noNotaInput.value = data.no_nota;
                            } else {
                                const d = new Date(tanggalInput.value || Date.now());
                                const tahun = d.getFullYear();
                                const bulan = (d.getMonth() + 1).toString().padStart(2, '0');
                                noNotaInput.value = `PJ/${tahun}/0001/${bulan}`;
                            }
                        })
                        .catch(err => {
                            const d = new Date(tanggalInput.value || Date.now());
                            const tahun = d.getFullYear();
                            const bulan = (d.getMonth() + 1).toString().padStart(2, '0');
                            noNotaInput.value = `PJ/${tahun}/0001/${bulan}`;
                            console.error('generateNoNotaAjax error:', err);
                        });
                }
                tanggalInput.addEventListener('change', fetchNoNota);
                fetchNoNota();
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
            window.addDetailRow = addDetailRow();
            // const oldAddDetailRow = window.addDetailRow;
            // window.addDetailRow = function() {
            //     oldAddDetailRow();
            //     // Ambil row terakhir yang baru ditambahkan
            //     const rows = document.querySelectorAll('#detail-barang-body tr');
            //     if (rows.length > 0) {
            //         const lastRow = rows[rows.length - 1];
            //         const barangSelect = lastRow.querySelector('.barang-select');
            //         if (barangSelect) {
            //             addAntiDuplikatBarangListener(barangSelect);
            //         }
            //     }
            // };

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
