<x-app-layout>
    <x-content-header title="Manajemen Supplier" breadcrumb-parent="Master Data"
        breadcrumb-url="{{ route('suppliers.index') }}" />

    <div id="custom-buttons" class="ms-3 mb-2">
        <a href="{{ route('suppliers.create') }}" class="btn btn-primary" id="btn-create-supplier"
            title="Tambah Supplier Baru">
            <i class="bi bi-person-plus"></i> Tambah Supplier Baru
        </a>
    </div>

    <div class="content">
        <div class="container-fluid mb-3">
            <div class="table-responsive" id="supplier-table-container">
                <table class="table table-striped table-hover table-bordered align-middle table-sm small"
                    id="supplierTable">
                    <thead>
                        <tr class="text-center">
                            <th style="width: 4%;">No</th>
                            <th style="width: 15%;">Nama</th>
                            <th style="width: 18%;">Perusahaan</th>
                            <th style="width: 13%;">Contact Person</th>
                            <th style="width: 20%;">Alamat</th>
                            <th style="width: 13%;">No. Telepon</th>
                            <th style="width: 15%;">Email</th>
                            <th style="width: 8%;">Status</th>
                            <th style="width: 15%;">Keterangan</th>
                            <th style="width: 8%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($suppliers->isEmpty())
                            <tr>
                                <td colspan="{{ $can_write ?? false ? '8' : '7' }}" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox display-1"></i>
                                        <p class="mt-2">
                                            @if (!empty($search))
                                                Tidak ada supplier yang sesuai dengan pencarian "{{ $search }}"
                                            @else
                                                Belum ada data supplier
                                            @endif
                                        </p>
                                        <a href="{{ route('suppliers.create') }}" class="btn btn-success">
                                            <i class="bi bi-person-plus"></i> Tambah Supplier Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @else
                            @foreach ($suppliers as $index => $supplier)
                                <tr class="text-center supplier-detail-row" style="cursor:pointer;"
                                    onclick="window.location='{{ route('suppliers.show', $supplier->id) }}'">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $supplier->name }}</td>
                                    <td>{{ $supplier->company_name ?? '-' }}</td>
                                    <td>{{ $supplier->contact_person ?? '-' }}</td>
                                    <td>{{ $supplier->address ?? '-' }}</td>
                                    <td>{{ $supplier->phone_number ?? '-' }}</td>
                                    <td>{{ $supplier->contact_email ?? '-' }}</td>
                                    <td>
                                        @if ($supplier->status === 'active')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td>{{ $supplier->description ?? '-' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('suppliers.show', $supplier->id) }}"
                                                class="btn btn-sm btn-info" title="Detail Supplier"
                                                onclick="event.stopPropagation();">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('suppliers.edit', $supplier->id) }}"
                                                class="btn btn-sm btn-warning" title="Edit Supplier"
                                                onclick="event.stopPropagation();">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="#" class="btn btn-danger btn-sm btn-hapus-supplier"
                                                data-id="{{ $supplier->id }}" title="Hapus Supplier"
                                                onclick="event.stopPropagation();">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <form id="form-delete-supplier" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>

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
            document.querySelectorAll('.btn-hapus-supplier').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const supplierId = btn.getAttribute('data-id');
                    Swal.fire({
                        title: 'Yakin ingin menghapus supplier ini?',
                        text: 'Data supplier yang dihapus tidak bisa dikembalikan!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Set action form dan submit
                            const form = document.getElementById('form-delete-supplier');
                            form.setAttribute('action', '/suppliers/' + supplierId);
                            form.submit();
                        }
                    });
                });
            });

            // DataTable initialization
            var table = $('#supplierTable').DataTable({
                dom: '<"d-flex justify-content-between align-items-center mb-2"<"d-flex align-items-center"<"dataTables_length"l><"#custom-buttons-container">><"dataTables_filter"f>>rtip',
                paging: true,
                searching: true,
                info: true,
                ordering: true,
                pagingType: 'full_numbers',
                language: {
                    info: "Halaman _PAGE_ dari _PAGES_ (_TOTAL_ Total Supplier)",
                    paginate: {
                        first: '&laquo;',
                        last: '&raquo;',
                        previous: '&lsaquo;',
                        next: '&rsaquo;'
                    },
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Data tidak ditemukan",
                    emptyTable: "Tidak ada data supplier",
                    loadingRecords: "Memuat...",
                    processing: "Memproses..."
                }
            });

            // Move custom buttons to DataTable
            $('#custom-buttons').appendTo('#custom-buttons-container');
        });
    </script>
</x-app-layout>
