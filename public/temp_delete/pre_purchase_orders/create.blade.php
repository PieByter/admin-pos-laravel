<x-app-layout>
    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white text-center">
                        <h5 class="card-title mb-0"><i class="fas fa-pen"></i> Edit Purchase Order</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('pre-purchase-orders.update', $prePurchaseOrder->id) }}" method="post">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3 align-items-center">
                                <label for="po_number" class="col-md-3 col-form-label"><b>No. Purchase Order</b></label>
                                <div class="col-md-9">
                                    <input type="text" name="po_number"
                                        class="form-control @error('po_number') is-invalid @enderror" id="po_number"
                                        value="{{ old('po_number', $prePurchaseOrder->po_number) }}" required>
                                    @error('po_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="issue_date" class="col-md-3 col-form-label"><b>Tanggal Terbit</b></label>
                                <div class="col-md-9">
                                    <input type="date" name="issue_date"
                                        class="form-control @error('issue_date') is-invalid @enderror" id="issue_date"
                                        value="{{ old('issue_date', $prePurchaseOrder->issue_date) }}" required>
                                    @error('issue_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="due_date" class="col-md-3 col-form-label"><b>Jatuh Tempo</b></label>
                                <div class="col-md-9">
                                    <input type="date" name="due_date"
                                        class="form-control @error('due_date') is-invalid @enderror"
                                        value="{{ old('due_date', $prePurchaseOrder->due_date) }}" required>
                                    @error('due_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="tax_amount" class="col-md-3 col-form-label"><b>PPN (12%)</b></label>
                                <div class="col-md-9">
                                    <input type="number" name="tax_amount" id="tax_amount" class="form-control"
                                        value="{{ old('tax_amount', $prePurchaseOrder->tax_amount) }}" required readonly
                                        style="display:none;">
                                    <span id="tax-amount-format" class="form-control bg-light">
                                        {{ number_format($prePurchaseOrder->tax_amount, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="supplier_id" class="col-md-3 col-form-label"><b>Supplier</b></label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select name="supplier_id" id="supplier_id"
                                            class="form-select @error('supplier_id') is-invalid @enderror" required>
                                            <option value="">- Pilih Supplier -</option>
                                            @foreach ($suppliers as $supplier)
                                                @if ($supplier->status === 'active')
                                                    <option value="{{ $supplier->id }}"
                                                        {{ $supplier->id == old('supplier_id', $prePurchaseOrder->supplier_id) ? 'selected' : '' }}>
                                                        {{ $supplier->name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-outline-info btn-sm"
                                            onclick="openSupplierModal(this)">
                                            <i class="fas fa-search"></i> Cari
                                        </button>
                                    </div>
                                    @error('supplier_id')
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
                                        <option value="draft"
                                            {{ old('status', $prePurchaseOrder->status) == 'draft' ? 'selected' : '' }}>
                                            Draft</option>
                                        <option value="process"
                                            {{ old('status', $prePurchaseOrder->status) == 'process' ? 'selected' : '' }}>
                                            Proses</option>
                                        <option value="completed"
                                            {{ old('status', $prePurchaseOrder->status) == 'completed' ? 'selected' : '' }}>
                                            Selesai (Lunas)</option>
                                        <option value="debt"
                                            {{ old('status', $prePurchaseOrder->status) == 'debt' ? 'selected' : '' }}>
                                            Utang</option>
                                        <option value="return"
                                            {{ old('status', $prePurchaseOrder->status) == 'return' ? 'selected' : '' }}>
                                            Retur</option>
                                        <option value="cancelled"
                                            {{ old('status', $prePurchaseOrder->status) == 'cancelled' ? 'selected' : '' }}>
                                            Batal</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="payment_method" class="col-md-3 col-form-label"><b>Metode
                                        Pembayaran</b></label>
                                <div class="col-md-9">
                                    <select name="payment_method"
                                        class="form-select @error('payment_method') is-invalid @enderror" required>
                                        <option value="">- Pilih Metode -</option>
                                        <option value="cash"
                                            {{ old('payment_method', $prePurchaseOrder->payment_method) == 'cash' ? 'selected' : '' }}>
                                            Cash</option>
                                        <option value="credit"
                                            {{ old('payment_method', $prePurchaseOrder->payment_method) == 'credit' ? 'selected' : '' }}>
                                            Kredit</option>
                                        <option value="transfer"
                                            {{ old('payment_method', $prePurchaseOrder->payment_method) == 'transfer' ? 'selected' : '' }}>
                                            Transfer</option>
                                        <option value="debit"
                                            {{ old('payment_method', $prePurchaseOrder->payment_method) == 'debit' ? 'selected' : '' }}>
                                            Debit</option>
                                        <option value="e-wallet"
                                            {{ old('payment_method', $prePurchaseOrder->payment_method) == 'e-wallet' ? 'selected' : '' }}>
                                            E-Wallet</option>
                                    </select>
                                    @error('payment_method')
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
                                    <textarea name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $prePurchaseOrder->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr>
                            <h5 class="mb-3">Detail Barang</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped align-middle">
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
                                        @foreach ($details as $i => $detail)
                                            <tr>
                                                <td>
                                                    <div class="input-group">
                                                        <select name="detail[{{ $i }}][item_id]"
                                                            class="form-select barang-select" required>
                                                            <option value="">- Pilih Barang -</option>
                                                            @foreach ($items as $item)
                                                                <option value="{{ $item->id }}"
                                                                    data-harga="{{ $item->buy_price }}"
                                                                    data-id_satuan="{{ $item->unit_id }}"
                                                                    data-stok="{{ $item->stock }}"
                                                                    {{ $item->id == $detail->item_id ? 'selected' : '' }}>
                                                                    {{ $item->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                                            onclick="openBarangModal(this)">
                                                            <i class="fas fa-search"></i> Cari
                                                        </button>
                                                    </div>
                                                    <span class="stok-info text-success small"></span>
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
                                                        class="form-control qty-input" required
                                                        value="{{ $detail->quantity }}">
                                                </td>
                                                <td>
                                                    <input type="number"
                                                        name="detail[{{ $i }}][unit_price]"
                                                        class="form-control harga-input" required
                                                        value="{{ $detail->unit_price }}" style="display:none;">
                                                    <input type="text" class="form-control harga-format"
                                                        value="{{ number_format($detail->unit_price, intval($detail->unit_price) == $detail->unit_price ? 0 : 2, ',', '.') }}">
                                                </td>
                                                <td>
                                                    <input type="number"
                                                        name="detail[{{ $i }}][subtotal]"
                                                        class="form-control subtotal-input" readonly
                                                        style="display:none;" value="{{ $detail->subtotal }}">
                                                    <span class="subtotal-format form-control bg-light">
                                                        {{ number_format($detail->subtotal, 0, ',', '.') }}
                                                    </span>
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
                                            <td colspan="2" class="fw-bold text-success">
                                                <span id="total-harga-format">
                                                    Rp.
                                                    {{ number_format($prePurchaseOrder->total_amount ?? 0, 0, ',', '.') }}
                                                </span>
                                                <input type="hidden" name="total_amount" id="total-harga"
                                                    value="{{ $prePurchaseOrder->total_amount ?? 0 }}">
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <button type="button" class="btn btn-success btn-sm mb-3" onclick="addDetailRow()">
                                <i class="fas fa-plus"></i> Tambah Barang
                            </button>

                            <div class="d-flex justify-content-end mt-4 gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Purchase Order
                                </button>
                                <a href="{{ route('pre-purchase-orders.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>


                    <div class="card-footer text-center">
                        <div class="d-flex justify-content-center align-items-center gap-2">
                            @if (!in_array($prePurchaseOrder->status, ['completed', 'return', 'cancelled']))
                                <form
                                    action="{{ route('pre-purchase-orders.mark-completed', $prePurchaseOrder->id) }}"
                                    method="post" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success"
                                        onclick="return confirm('Tandai PO ini selesai dan otomatis masuk ke pembelian?\n\nPerhatian: Proses ini hanya bisa dilakukan sekali!')">
                                        <i class="fas fa-check-circle"></i> Tandai Selesai & Masukkan ke Pembelian
                                    </button>
                                </form>
                            @elseif ($prePurchaseOrder->status === 'completed')
                                <span class="badge bg-success fs-5">Purchase Order Sudah Ditandai Selesai</span>
                            @elseif ($prePurchaseOrder->status === 'return')
                                <span class="badge bg-warning fs-5">Purchase Order Retur</span>
                            @elseif ($prePurchaseOrder->status === 'cancelled')
                                <span class="badge bg-danger fs-5">Purchase Order Dibatalkan</span>
                            @endif
                        </div>
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
                                        data-stok="{{ $item->stock }}" data-harga="{{ $item->buy_price }}"
                                        data-id_satuan="{{ $item->unit_id }}">
                                        <td>{{ $item->name }}</td>
                                        <td class="text-center">{{ $item->stock }}</td>
                                        <td class="text-center">
                                            {{ number_format($item->buy_price, 0, ',', '.') }}</td>
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

    <script>
        let detailIndex = {{ count(old('detail') ?? [[]]) }};
        const satuanKonversiMap = {{ json_encode($satuanKonversiMap) }};
        document.addEventListener('DOMContentLoaded', function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true
            });

            @if (session('error'))
                Toast.fire({
                    icon: 'error',
                    title: '{{ session('error') }}'
                });
            @endif

            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: '{{ session('success') }}'
                });
            @endif

            @if ($errors->any())
                Toast.fire({
                    icon: 'error',
                    title: 'Terjadi kesalahan pada input',
                    html: '{!! implode('<br>', $errors->all()) !!}'
                });
            @endif
        });

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
                <select name="detail[${detailIndex}][item_id]" class="form-select barang-select" required>
                    <option value="">- Pilih Barang -</option>
                    @foreach ($items as $item)
                        <option value="{{ $item->id }}"
                            data-stok="{{ $item->stock }}"
                            data-harga="{{ $item->buy_price }}"
                            data-id_satuan="{{ $item->unit_id }}">
                            {{ $item->name }}
                        </option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="openBarangModal(this)">
                    <i class="fas fa-search"></i> Cari
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
            <input type="number" name="detail[${detailIndex}][unit_price]"
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
                <i class="fas fa-trash"></i>
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
                    fetch('{{ route('po.generateNoPO') }}?tanggal_terbit=' + encodeURIComponent(this
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
