<x-app-layout>
    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-eye"></i> Detail Pembelian</h5>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-4">
                            <dt class="col-sm-5">Nomor Faktur</dt>
                            <dd class="col-sm-7">{{ $purchaseOrder->invoice_number }}</dd>

                            <dt class="col-sm-5">Tanggal Terbit</dt>
                            <dd class="col-sm-7">
                                {{ \Carbon\Carbon::parse($purchaseOrder->issue_date)->translatedFormat('l, d F Y') }}
                            </dd>

                            <dt class="col-sm-5">Supplier</dt>
                            <dd class="col-sm-7">{{ $purchaseOrder->supplier->name ?? '-' }}</dd>

                            <dt class="col-sm-5">Total Harga</dt>
                            <dd class="col-sm-7">Rp. {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}</dd>

                            <dt class="col-sm-5">Status</dt>
                            <dd class="col-sm-7">
                                @php
                                    $statusConfig = [
                                        'draft' => ['badge' => 'secondary', 'text' => 'Draft'],
                                        'process' => ['badge' => 'warning', 'text' => 'Proses'],
                                        'completed' => ['badge' => 'success', 'text' => 'Selesai (Lunas)'],
                                        'debt' => ['badge' => 'info', 'text' => 'Utang'],
                                        'return' => ['badge' => 'orange', 'text' => 'Retur'],
                                        'cancelled' => ['badge' => 'danger', 'text' => 'Batal'],
                                    ];
                                    $status = $statusConfig[$purchaseOrder->status] ?? [
                                        'badge' => 'secondary',
                                        'text' => ucfirst($purchaseOrder->status),
                                    ];
                                @endphp
                                <span class="badge bg-{{ $status['badge'] }}">
                                    {{ $status['text'] }}
                                </span>
                            </dd>

                            <dt class="col-sm-5">Metode Pembayaran</dt>
                            <dd class="col-sm-7">
                                @php
                                    $paymentConfig = [
                                        'cash' => ['badge' => 'success', 'text' => 'Cash'],
                                        'credit' => ['badge' => 'warning', 'text' => 'Kredit'],
                                        'transfer' => ['badge' => 'info', 'text' => 'Transfer'],
                                        'debit' => ['badge' => 'primary', 'text' => 'Debit'],
                                        'e-wallet' => ['badge' => 'secondary', 'text' => 'E-Wallet'],
                                    ];
                                    $payment = $paymentConfig[$purchaseOrder->payment_method] ?? [
                                        'badge' => 'dark',
                                        'text' => ucfirst($purchaseOrder->payment_method ?? '-'),
                                    ];
                                @endphp
                                <span class="badge bg-{{ $payment['badge'] }}">
                                    {{ $payment['text'] }}
                                </span>
                            </dd>

                            <dt class="col-sm-5">Otorisasi</dt>
                            <dd class="col-sm-7">{{ $authorizedUsernames ?? '-' }}</dd>

                            <dt class="col-sm-5">Keterangan</dt>
                            <dd class="col-sm-7">{{ $purchaseOrder->description ?? '-' }}</dd>
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
                                        <th>Harga Beli</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($purchaseOrder->details as $index => $detail)
                                        <tr class="barang-row" data-barang='@json($detail)'
                                            style="cursor: pointer;">
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $detail->item->name ?? '-' }}</td>
                                            <td>{{ $detail->unit->name ?? '-' }}</td>
                                            <td>{{ number_format($detail->quantity, 0, ',', '.') }}</td>
                                            <td>Rp. {{ number_format($detail->unit_price, 0, ',', '.') }}</td>
                                            <td>Rp. {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-3">
                                                <div class="text-muted">
                                                    <i class="bi bi-inbox"></i>
                                                    <p class="mb-0 mt-2">Tidak ada detail barang</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if ($purchaseOrder->details->count() > 0)
                                    <tfoot>
                                        <tr>
                                            <td colspan="5" class="text-end"><b>Total Harga</b></td>
                                            <td class="fw-bold text-success">
                                                Rp. <span
                                                    id="total-harga-label">{{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}</span>
                                            </td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>

                        <div class="mt-4 d-flex justify-content-end gap-2">
                            @if ($can_write ?? false)
                                <a href="{{ route('purchase-orders.edit', $purchaseOrder->id) }}"
                                    class="btn btn-warning">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                            @endif
                            <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Barang -->
    <div class="modal fade" id="modalBarang" tabindex="-1" aria-labelledby="modalBarangLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBarangLabel">Detail Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBarangBody">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <style>
        .badge.bg-orange {
            background-color: #fd7e14 !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });

            // Success message
            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: '{{ session('success') }}'
                });
            @endif

            // Error message
            @if (session('error'))
                Toast.fire({
                    icon: 'error',
                    title: '{{ session('error') }}'
                });
            @endif

            // Handle item row click
            document.querySelectorAll('.barang-row').forEach(function(row) {
                row.addEventListener('click', function() {
                    const data = JSON.parse(this.dataset.barang);
                    const itemName = this.children[1].textContent;
                    const unitName = data.unit ? data.unit.name : '-';

                    let html = `
                        <dl class="row">
                            <dt class="col-sm-5">Barang</dt>
                            <dd class="col-sm-7">${itemName}</dd>
                            <dt class="col-sm-5">Satuan</dt>
                            <dd class="col-sm-7">${unitName}</dd>
                            <dt class="col-sm-5">Qty</dt>
                            <dd class="col-sm-7">${parseInt(data.quantity).toLocaleString('id-ID')}</dd>
                            <dt class="col-sm-5">Harga Beli</dt>
                            <dd class="col-sm-7">Rp. ${parseInt(data.unit_price).toLocaleString('id-ID')}</dd>
                            <dt class="col-sm-5">Subtotal</dt>
                            <dd class="col-sm-7">Rp. ${parseInt(data.subtotal).toLocaleString('id-ID')}</dd>
                        </dl>
                    `;

                    document.getElementById('modalBarangBody').innerHTML = html;
                    var modal = new bootstrap.Modal(document.getElementById('modalBarang'));
                    modal.show();
                });
            });
        });
    </script>

    @php
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
    @endphp
    @if (session()->getFlashdata('error'))
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
                    title: '{{ session()->getFlashdata('error') }}'
                });
            });
        </script>
    @endif
</x-app-layout>
