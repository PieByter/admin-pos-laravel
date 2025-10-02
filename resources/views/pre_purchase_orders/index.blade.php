{{-- filepath: c:\laragon\www\admin-pos\resources\views\pre_purchase_orders\index.blade.php --}}
<x-app-layout>
    <x-content-header title="Manajemen Pre Purchase Order" breadcrumb-parent="Transaksi"
        breadcrumb-url="{{ route('pre-purchase-orders.index') }}" />

    @if ($can_write ?? false)
        <div id="custom-buttons" class="ms-3 mb-2">
            <a href="{{ route('pre-purchase-orders.create') }}" class="btn btn-primary" id="btn-create-po">
                <i class="bi bi-plus-lg"></i> Tambah PO Baru
            </a>
        </div>
    @endif

    <div class="content">
        <div class="container-fluid mb-3">
            <div class="table-responsive" id="po-table-container">
                <table class="table table-striped table-hover table-bordered align-middle table-sm small" id="POTable"
                    style="width:100%; table-layout: fixed;">
                    <thead>
                        <tr class="text-center">
                            <th style="width:4%;">No</th>
                            <th style="width:12%;">Nomor PO</th>
                            <th style="width:10%;">Tanggal Terbit</th>
                            <th style="width:10%;">Supplier</th>
                            <th style="width:12%;">Total Harga</th>
                            <th style="width:10%;">Jatuh Tempo</th>
                            <th style="width:8%;">Status</th>
                            <th style="width:8%;">Payment</th>
                            <th style="width:18%;">Detail Barang</th>
                            @if ($can_write ?? false)
                                <th style="width:8%;">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if ($prePurchaseOrders->isEmpty())
                            <tr>
                                <td colspan="{{ $can_write ?? false ? '10' : '9' }}" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox display-1"></i>
                                        <p class="mt-2">
                                            @if (!empty($search))
                                                Tidak ada Purchase Order yang sesuai dengan pencarian
                                                "{{ $search }}"
                                            @else
                                                Belum ada data Purchase Order
                                            @endif
                                        </p>
                                        @if ($can_write ?? false)
                                            <a href="{{ route('pre-purchase-orders.create') }}" class="btn btn-primary">
                                                <i class="bi bi-file-earmark-plus"></i> Tambah PO Pertama
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @else
                            @foreach ($prePurchaseOrders as $index => $po)
                                <tr style="cursor:pointer;"
                                    onclick="window.location='{{ route('pre-purchase-orders.show', $po->id) }}'">
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td class="text-center">{{ $po->po_number }}</td>
                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($po->issue_date)->format('d M Y') }}</td>
                                    <td class="text-center">{{ $po->supplier->name ?? '-' }}</td>
                                    <td class="text-center">Rp. {{ number_format($po->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($po->due_date)->format('d M Y') }}
                                    </td>
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
                                            $status = $statusConfig[$po->status] ?? [
                                                'badge' => 'secondary',
                                                'text' => ucfirst($po->status),
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
                                            $payment = $paymentConfig[$po->payment_method] ?? [
                                                'badge' => 'dark',
                                                'text' => ucfirst($po->payment_method ?? '-'),
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $payment['badge'] }}">
                                            {{ $payment['text'] }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($po->details->count() > 0)
                                            @foreach ($po->details as $index => $detail)
                                                <div>
                                                    {{ $detail->item->name ?? '-' }},
                                                    Qty: {{ number_format($detail->quantity, 0) }}
                                                    {{ $detail->unit->name ?? '-' }},
                                                    Harga: Rp. {{ number_format($detail->unit_price, 0, ',', '.') }}
                                                    Subtotal: Rp. {{ number_format($detail->subtotal, 0, ',', '.') }}
                                                </div>
                                                @if ($index < $po->details->count() - 1)
                                                    <hr class="my-1">
                                                @endif
                                            @endforeach
                                        @else
                                            <span class="text-muted">Tidak ada detail</span>
                                        @endif
                                    </td>
                                    @if ($can_write ?? false)
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('pre-purchase-orders.show', $po->id) }}"
                                                    class="btn btn-info btn-sm" title="Detail"
                                                    onclick="event.stopPropagation();">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('pre-purchase-orders.edit', $po->id) }}"
                                                    class="btn btn-warning btn-sm" title="Edit"
                                                    onclick="event.stopPropagation();">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('pre-purchase-orders.destroy', $po->id) }}"
                                                    method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm btn-hapus-po"
                                                        onclick="event.stopPropagation();" title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="card mt-3">
                <div class="card-body d-flex justify-content-end">
                    <form class="row align-items-center g-2" method="get"
                        action="{{ route('pre-purchase-orders.export') }}">
                        <div class="col-auto fw-bold">
                            <label for="jenis-export" class="form-label mb-0">Export Purchase Order</label>
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
                                <i class="bi bi-file-earmark-excel"></i> Export Excel
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
            document.querySelectorAll('.btn-hapus-po').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const form = btn.closest('.delete-form');

                    Swal.fire({
                        title: 'Yakin ingin menghapus PO ini?',
                        text: 'Data PO yang dihapus tidak bisa dikembalikan!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // DataTable initialization
            if ($.fn.DataTable.isDataTable('#POTable')) {
                $('#POTable').DataTable().destroy();
            }

            var table = $('#POTable').DataTable({
                dom: '<"d-flex justify-content-between align-items-center mb-2"<"d-flex align-items-center"<"dataTables_length"l><"#custom-buttons-container">><"dataTables_filter"f>>rtip',
                paging: true,
                searching: true,
                info: true,
                ordering: true,
                pagingType: 'full_numbers',
                language: {
                    info: "Halaman _PAGE_ dari _PAGES_ (_TOTAL_ Total Purchase Order)",
                    infoEmpty: "Tidak ada data Purchase Order untuk ditampilkan",
                    infoFiltered: "(difilter dari _MAX_ total Purchase Order)",
                    emptyTable: "Belum ada Data Purchase Order",
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
