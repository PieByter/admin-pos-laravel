<x-app-layout title="Manajemen Customer">
    <x-content-header title="Manajemen Customer" breadcrumb-parent="Master Data"
        breadcrumb-url="{{ route('customers.index') }}" />

    <div id="custom-buttons" class="ms-3 mb-2">
        <a href="{{ route('customers.create') }}" class="btn btn-primary" id="btn-create-customer"
            title="Tambah Customer Baru">
            <i class="bi bi-person-plus"></i> Tambah Customer Baru
        </a>
    </div>

    <div class="content">
        <div class="container-fluid mb-3">
            <div class="table-responsive" id="customer-table-container">
                <table class="table table-striped table-hover table-bordered align-middle table-sm small"
                    id="customerTable">
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
                                        <a href="{{ route('customers.create') }}" class="btn btn-primary">
                                            <i class="bi bi-person-plus"></i> Tambah Customer Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @else
                            @foreach ($customers as $index => $customer)
                                <tr class="text-center" style="cursor:pointer;"
                                    onclick="window.location='{{ route('customers.show', $customer->id) }}'">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $customer->name }}</td>
                                    <td>{{ $customer->company_name ?? '-' }}</td>
                                    <td>{{ $customer->contact_person ?? '-' }}</td>
                                    <td>{{ $customer->address ?? '-' }}</td>
                                    <td>{{ $customer->phone_number ?? '-' }}</td>
                                    <td>{{ $customer->contact_email ?? '-' }}</td>
                                    <td>
                                        @if ($customer->status === 'active')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td>{{ $customer->description ?? '-' }}</td>
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
                                            <a href="#" class="btn btn-danger btn-sm btn-hapus-customer"
                                                data-id="{{ $customer->id }}" title="Hapus"
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

    <form id="form-delete-customer" method="POST" style="display:none;">
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

            $('#custom-buttons').appendTo('#custom-buttons-container');
            document.querySelectorAll('.btn-hapus-customer').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const customerId = btn.getAttribute('data-id');
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
                            // Set action form dan submit
                            const form = document.getElementById('form-delete-customer');
                            form.setAttribute('action', '/customers/' + customerId);
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
