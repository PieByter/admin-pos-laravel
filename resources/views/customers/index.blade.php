<x-app-layout>
    <x-content-header title="Manajemen Customer" breadcrumb-parent="Master Data"
        breadcrumb-url="{{ route('customers.index') }}" />

    @if ($can_write ?? false)
        <div id="custom-buttons" class="ms-3 mb-2">
            <a href="{{ route('customers.create') }}" class="btn btn-primary" id="btn-create-customer"
                title="Tambah Customer Baru">
                <i class="bi bi-person-plus"></i> Tambah Customer Baru
            </a>
        </div>
    @endif

    <div class="content">
        <div class="container-fluid mb-3">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered align-middle table-sm small w-100"
                    id="customerTable">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th style="width: 4%;">No</th>
                            <th style="width: 15%;">Nama</th>
                            <th style="width: 30%;">Alamat</th>
                            <th style="width: 13%;">No. Telepon</th>
                            <th style="width: 20%;">Email</th>
                            <th style="width: 10%;">Status</th>
                            <th style="width: 10%;">Keterangan</th>
                            @if ($can_write ?? false)
                                <th style="width: 8%;">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if ($customers->isEmpty())
                            <tr>
                                <td colspan="{{ $can_write ?? false ? '8' : '7' }}" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox display-1"></i>
                                        <p class="mt-2">
                                            @if (!empty($search))
                                                Tidak ada customer yang sesuai dengan pencarian "{{ $search }}"
                                            @else
                                                Belum ada data customer
                                            @endif
                                        </p>
                                        @if ($can_write ?? false)
                                            <a href="{{ route('customers.create') }}" class="btn btn-primary">
                                                <i class="bi bi-person-plus"></i> Tambah Customer Pertama
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @else
                            @foreach ($customers as $index => $customer)
                                <tr class="text-center" style="cursor:pointer;"
                                    onclick="window.location='{{ route('customers.show', $customer->id) }}'">
                                    <td>{{ $index + 1 }}</td>
                                    <td class="text-truncate">{{ $customer->name }}</td>
                                    <td class="text-truncate">{{ $customer->address ?? '-' }}</td>
                                    <td>{{ $customer->phone ?? '-' }}</td>
                                    <td class="text-truncate">{{ $customer->email ?? '-' }}</td>
                                    <td>
                                        @if ($customer->status === 'active')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td class="text-truncate">{{ $customer->description ?? '-' }}</td>
                                    @if ($can_write ?? false)
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('customers.show', $customer->id) }}"
                                                    class="btn btn-info btn-sm" title="Detail"
                                                    onclick="event.stopPropagation();">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('customers.edit', $customer->id) }}"
                                                    class="btn btn-warning btn-sm" title="Edit"
                                                    onclick="event.stopPropagation();">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('customers.destroy', $customer->id) }}"
                                                    method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-danger btn-sm btn-hapus-customer"
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
        </div>
    </div>

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
            document.querySelectorAll('.btn-hapus-customer').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const form = btn.closest('.delete-form');

                    Swal.fire({
                        title: 'Yakin ingin menghapus customer ini?',
                        text: 'Data customer yang dihapus tidak bisa dikembalikan!',
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
            if ($.fn.DataTable.isDataTable('#customerTable')) {
                $('#customerTable').DataTable().destroy();
            }

            var table = $('#customerTable').DataTable({
                dom: '<"d-flex justify-content-between align-items-center mb-2"<"d-flex align-items-center"<"dataTables_length"l><"#custom-buttons-container">><"dataTables_filter"f>>rtip',
                paging: true,
                searching: true,
                info: true,
                ordering: true,
                pagingType: 'full_numbers',
                language: {
                    info: "Halaman _PAGE_ dari _PAGES_ (_TOTAL_ Total Customer)",
                    infoEmpty: "Tidak ada data customer untuk ditampilkan",
                    infoFiltered: "(difilter dari _MAX_ total customer)",
                    emptyTable: "Belum ada data customer",
                    zeroRecords: "Tidak ada data yang sesuai dengan pencarian",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    search: "Pencarian:",
                    paginate: {
                        first: '&laquo;',
                        last: '&raquo;',
                        previous: '&lsaquo;',
                        next: '&rsaquo;'
                    }
                }
            });

            $('#custom-buttons').appendTo('#custom-buttons-container');
        });
    </script>
</x-app-layout>
