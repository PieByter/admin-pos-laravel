<x-app-layout>
    <x-content-header title="Manajemen Barang" breadcrumb-parent="Master Data"
        breadcrumb-url="{{ route('items.index') }}" />

    @if ($can_write ?? false)
        <div id="custom-buttons" class="ms-3 mb-2">
            <a href="{{ route('items.create') }}" class="btn btn-primary" id="btn-create-barang" title="Tambah Barang Baru">
                <i class="bi bi-plus"></i> Tambah Barang Baru
            </a>
        </div>
    @endif

    <div class="content">
        <div class="container-fluid mb-3">
            <div class="table-responsive" id="barang-table-container">
                <table class="table table-striped table-hover table-bordered align-middle table-sm small"
                    id="barangTable" style="table-layout: fixed; width: 100%;">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th style="width: 4%;">No</th>
                            <th style="width: 10%;">Kode Barang</th>
                            <th style="width: 25%;">Nama Barang</th>
                            <th style="width: 10%;">Jenis</th>
                            <th style="width: 15%;">Group</th>
                            <th style="width: 10%;">Satuan</th>
                            <th style="width: 10%;">Harga Beli</th>
                            <th style="width: 10%;">Harga Jual</th>
                            <th style="width: 10%;">Stok</th>
                            @if ($can_write ?? false)
                                <th style="width: 10%;">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if ($items->isEmpty())
                            <tr>
                                <td colspan="{{ $can_write ?? false ? '10' : '9' }}" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox display-1"></i>
                                        <p class="mt-2">
                                            @if (!empty($search))
                                                Tidak ada barang yang sesuai dengan pencarian "{{ $search }}"
                                            @else
                                                Belum ada data barang
                                            @endif
                                        </p>
                                        @if ($can_write ?? false)
                                            <a href="{{ route('items.create') }}" class="btn btn-primary">
                                                <i class="bi bi-plus"></i> Tambah Barang Pertama
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @else
                            @foreach ($items as $index => $item)
                                <tr class="text-center" style="cursor:pointer;"
                                    onclick="window.location='{{ route('items.show', $item->id) }}'">
                                    <td>{{ $index + 1 }}</td>
                                    <td class="text-truncate">{{ $item->code }}</td>
                                    <td class="text-truncate">{{ $item->name }}</td>
                                    <td>{{ $item->itemType->name ?? '-' }}</td>
                                    <td>{{ $item->itemGroup->name ?? '-' }}</td>
                                    <td>{{ $item->unit->name ?? '-' }}</td>
                                    <td>
                                        Rp.
                                        {{ $item->purchase_price == intval($item->purchase_price)
                                            ? number_format($item->purchase_price, 0, ',', '.')
                                            : number_format($item->purchase_price, 2, ',', '.') }}
                                    </td>
                                    <td>
                                        Rp.
                                        {{ $item->selling_price == intval($item->selling_price)
                                            ? number_format($item->selling_price, 0, ',', '.')
                                            : number_format($item->selling_price, 2, ',', '.') }}
                                    </td>
                                    <td>
                                        @php
                                            $stockBadgeClass =
                                                $item->stock > 10
                                                    ? 'bg-success'
                                                    : ($item->stock > 0
                                                        ? 'bg-warning text-dark'
                                                        : 'bg-danger');
                                        @endphp
                                        <span class="badge {{ $stockBadgeClass }}">
                                            {{ number_format($item->stock, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    @if ($can_write ?? false)
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('items.show', $item->id) }}"
                                                    class="btn btn-info btn-sm" title="Detail"
                                                    onclick="event.stopPropagation();">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('items.edit', $item->id) }}"
                                                    class="btn btn-warning btn-sm" title="Edit"
                                                    onclick="event.stopPropagation();">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('items.destroy', $item->id) }}" method="POST"
                                                    class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-danger btn-sm btn-hapus-barang"
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

            <!-- Export Section -->
            <div class="card mt-3">
                <div class="card-body d-flex justify-content-end">
                    <form class="row align-items-center g-2" method="get" action="{{ route('items.export') }}">
                        <div class="col-auto fw-bold">
                            <label for="jenis-export" class="form-label mb-0">Export Data Barang</label>
                        </div>
                        <div class="col-auto">
                            <select name="type" id="jenis-export" class="form-select">
                                <option value="all" selected>Semua Data</option>
                                <option value="low-stock">Stok Rendah</option>
                                <option value="out-of-stock">Stok Habis</option>
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
        .description-cell {
            max-width: 180px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            cursor: help;
        }

        .img-thumbnail {
            object-fit: cover;
        }

        .btn-group .btn {
            margin: 0;
        }

        .table th,
        .table td {
            font-size: 0.8rem;
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
            document.querySelectorAll('.btn-hapus-barang').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const form = btn.closest('.delete-form');

                    Swal.fire({
                        title: 'Yakin ingin menghapus barang ini?',
                        text: 'Data barang yang dihapus tidak bisa dikembalikan!',
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
            if ($.fn.DataTable.isDataTable('#barangTable')) {
                $('#barangTable').DataTable().destroy();
            }

            var table = $('#barangTable').DataTable({
                dom: '<"d-flex justify-content-between align-items-center mb-2"<"d-flex align-items-center"<"dataTables_length"l><"#custom-buttons-container">><"dataTables_filter"f>>rtip',
                paging: true,
                searching: true,
                info: true,
                ordering: true,
                pagingType: 'full_numbers',
                language: {
                    info: "Halaman _PAGE_ dari _PAGES_ (_TOTAL_ Total Barang)",
                    infoEmpty: "Tidak ada data barang untuk ditampilkan",
                    infoFiltered: "(difilter dari _MAX_ total barang)",
                    emptyTable: "Belum ada data barang",
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
        });
    </script>
</x-app-layout>
