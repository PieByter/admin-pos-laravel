<x-app-layout title="Sales Orders">

    <x-content-header title="Manajemen Penjualan" breadcrumb-parent="Transaksi"
        breadcrumb-url="{{ route('sales.index') }}" />


    <div id="custom-buttons" class="ms-3 mb-2">
        <a href="{{ route('sales.create') }}" class="btn btn-primary" id="btn-create-penjualan">
            <i class="fas fa-plus"></i> Tambah Penjualan
        </a>
    </div>


    <div class="content">
        <div class="container-fluid mb-3">
            <div class="table-responsive" id="penjualan-table-container">
                <table class="table table-striped table-hover table-bordered align-middle table-sm small"
                    id="penjualanTable">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Nomor Faktur</th>
                            <th>Tanggal Terbit</th>
                            <th>Customer</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Detail Barang</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($sales->isEmpty())
                            <tr>
                                <td colspan="{{ $can_write ?? false ? '9' : '8' }}" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inboxdisplay-1"></i>
                                        <p class="mt-2">
                                            @if (!empty($search))
                                                Tidak ada penjualan yang sesuai dengan pencarian "{{ $search }}"
                                            @else
                                                Belum ada data penjualan
                                            @endif
                                        </p>

                                        <a href="{{ route('sales.create') }}" class="btn btn-primary">
                                            <i class="fas fa-cart-plus"></i> Tambah Penjualan Pertama
                                        </a>

                                    </div>
                                </td>
                            </tr>
                        @else
                            @foreach ($sales as $index => $salesOrder)
                                <tr style="cursor:pointer;"
                                    onclick="window.location='{{ route('sales.show', $salesOrder->id) }}'">
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td class="text-center">{{ $salesOrder->invoice_number }}</td>
                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($salesOrder->issue_date)->format('d M Y') }}</td>
                                    <td class="text-center">{{ $salesOrder->customer->name ?? '-' }}</td>
                                    <td class="text-center">Rp.
                                        {{ number_format($salesOrder->total_amount, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        @php
                                            $statusConfig = [
                                                'draft' => ['badge' => 'secondary', 'text' => 'Draft'],
                                                'process' => ['badge' => 'warning', 'text' => 'Proses'],
                                                'completed' => ['badge' => 'success', 'text' => 'Selesai (Lunas)'],
                                                'debt' => ['badge' => 'info', 'text' => 'Utang'],
                                                'return' => ['badge' => 'orange', 'text' => 'Retur'],
                                                'cancelled' => ['badge' => 'danger', 'text' => 'Batal'],
                                            ];
                                            $status = $statusConfig[$salesOrder->status] ?? [
                                                'badge' => 'secondary',
                                                'text' => ucfirst($salesOrder->status),
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $status['badge'] }}">
                                            {{ $status['text'] }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $paymentConfig = [
                                                'cash' => ['badge' => 'success', 'text' => 'Cash'],
                                                'credit' => ['badge' => 'warning', 'text' => 'Kredit'],
                                                'transfer' => ['badge' => 'info', 'text' => 'Transfer'],
                                                'debit' => ['badge' => 'primary', 'text' => 'Debit'],
                                                'e-wallet' => ['badge' => 'secondary', 'text' => 'E-Wallet'],
                                            ];
                                            $payment = $paymentConfig[$salesOrder->payment_method] ?? [
                                                'badge' => 'dark',
                                                'text' => ucfirst($salesOrder->payment_method ?? '-'),
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $payment['badge'] }}">
                                            {{ $payment['text'] }}
                                        </span>
                                    </td>
                                    <td>
                                        @if (!empty($salesOrder->salesOrderItems) && count($salesOrder->salesOrderItems) > 0)
                                            @foreach ($salesOrder->salesOrderItems as $index => $detail)
                                                <div>
                                                    {{ $detail->item->item_name ?? '-' }},
                                                    Qty: {{ number_format($detail->quantity, 0) }}
                                                    {{ $detail->unit->unit_name ?? '-' }},
                                                    Harga: Rp. {{ number_format($detail->sell_price, 0, ',', '.') }}
                                                    Subtotal: Rp. {{ number_format($detail->subtotal, 0, ',', '.') }}
                                                </div>
                                                @if ($index < count($salesOrder->salesOrderItems) - 1)
                                                    <hr class="my-1">
                                                @endif
                                            @endforeach
                                        @else
                                            <span class="text-muted">Tidak ada detail</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('sales.show', $salesOrder->id) }}"
                                                class="btn btn-info btn-sm" title="Detail"
                                                onclick="event.stopPropagation();">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('sales.edit', $salesOrder->id) }}"
                                                class="btn btn-warning btn-sm" title="Edit"
                                                onclick="event.stopPropagation();">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <a href="#" class="btn btn-danger btn-sm btn-hapus-penjualan"
                                                data-id="{{ $salesOrder->id }}" title="Hapus"
                                                onclick="event.stopPropagation();">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <form id="form-delete-penjualan" method="POST" style="display:none;">
                @csrf
                @method('DELETE')
            </form>

            <div class="card mt-3">
                <div class="card-body d-flex justify-content-end">
                    <form class="row align-items-center g-2" method="get" action="{{ route('sales.export') }}">
                        <div class="col-auto fw-bold">
                            <label for="jenis-export" class="form-label mb-0">Export Penjualan</label>
                        </div>
                        <div class="col-auto">
                            <select name="type" id="jenis-export" class="form-select" onchange="toggleExportInput()">
                                <option value="daily">Harian</option>
                                <option value="monthly" selected>Bulanan</option>
                                <option value="yearly">Tahunan</option>
                            </select>
                        </div>
                        <div class="col-auto" id="export-harian" style="display:none;">
                            <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-auto" id="export-bulanan">
                            <select name="month" id="bulan-export" class="form-select">
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $i == date('n') ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-auto" id="export-tahun">
                            <select name="year" id="tahun-export" class="form-select">
                                @for ($y = date('Y') - 3; $y <= date('Y'); $y++)
                                    <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .table th,
        .table td {
            font-size: 0.8rem;
        }

        .badge.bg-orange {
            background-color: #fd7e14 !important;
        }
    </style>

    <script>
        function toggleExportInput() {
            const jenis = document.getElementById('jenis-export').value;
            document.getElementById('export-harian').style.display = (jenis === 'daily') ? '' : 'none';
            document.getElementById('export-bulanan').style.display = (jenis === 'monthly') ? '' : 'none';
            document.getElementById('export-tahun').style.display = (jenis === 'yearly' || jenis === 'monthly') ? '' :
                'none';
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleExportInput();

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });

            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: '{{ session('success') }}'
                });
            @endif

            @if (session('error'))
                Toast.fire({
                    icon: 'error',
                    title: '{{ session('error') }}'
                });
            @endif

            // Handle delete confirmation
            document.querySelectorAll('.btn-hapus-penjualan').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const salesId = btn.getAttribute('data-id');
                    Swal.fire({
                        title: 'Yakin ingin menghapus data ini?',
                        text: 'Data yang dihapus tidak bisa dikembalikan!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = document.getElementById('form-delete-penjualan');
                            form.setAttribute('action', '/sales/' + salesId);
                            form.submit();
                        }
                    });
                });
            });

            // DataTable initialization
            if ($.fn.DataTable.isDataTable('#penjualanTable')) {
                $('#penjualanTable').DataTable().destroy();
            }

            var table = $('#penjualanTable').DataTable({
                dom: '<"d-flex justify-content-between align-items-center mb-2"<"d-flex align-items-center"<"dataTables_length"l><"#custom-buttons-container">><"dataTables_filter"f>>rtip',
                paging: true,
                searching: true,
                info: true,
                ordering: true,
                pagingType: 'full_numbers',
                language: {
                    info: "Halaman _PAGE_ dari _PAGES_ (_TOTAL_ Total Penjualan)",
                    infoEmpty: "Tidak ada data penjualan untuk ditampilkan",
                    infoFiltered: "(difilter dari _MAX_ total penjualan)",
                    emptyTable: "Belum ada Data Penjualan",
                    zeroRecords: "Tidak ada data yang sesuai dengan pencarian",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    search: "Pencarian:",
                    paginate: {
                        first: '&laquo;',
                        last: '&raquo;',
                        previous: '&lsaquo;',
                        next: '&rsaquo;'
                    }
                },
            });

            $('#custom-buttons').appendTo('#custom-buttons-container');
            document.getElementById('jenis-export').addEventListener('change', toggleExportInput);
        });
    </script>
</x-app-layout>
