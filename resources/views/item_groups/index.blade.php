{{-- filepath: c:\laragon\www\admin-pos\resources\views\item_groups\index.blade.php --}}
<x-app-layout>
    <x-content-header title="Daftar Group Barang" breadcrumb-parent="Master Data"
        breadcrumb-url="{{ route('item-groups.index') }}" />

    @if ($can_write ?? false)
        <div id="custom-buttons" class="ms-3 mb-2">
            <a href="{{ route('item-groups.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Tambah Group Barang
            </a>
        </div>
    @endif

    <div class="content">
        <div class="container-fluid mb-3">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="groupBarangTable">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Nama Group</th>
                            <th>Keterangan</th>
                            @if ($can_write ?? false)
                                <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if ($itemGroups->isEmpty())
                            <tr>
                                <td colspan="{{ $can_write ?? false ? '4' : '3' }}" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox display-1"></i>
                                        <p class="mt-2">Belum ada data group barang</p>
                                        @if ($can_write ?? false)
                                            <a href="{{ route('item-groups.create') }}" class="btn btn-primary">
                                                <i class="bi bi-plus"></i> Tambah Group Pertama
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @else
                            @foreach ($itemGroups as $index => $group)
                                <tr class="text-center">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $group->name }}</td>
                                    <td>{{ $group->description ?? '-' }}</td>
                                    @if ($can_write ?? false)
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('item-groups.edit', $group->id) }}"
                                                    class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('item-groups.destroy', $group->id) }}"
                                                    method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm btn-hapus-group"
                                                        title="Hapus">
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
            document.querySelectorAll('.btn-hapus-group').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = btn.closest('.delete-form');

                    Swal.fire({
                        title: 'Yakin ingin menghapus group barang ini?',
                        text: 'Data group barang yang dihapus tidak bisa dikembalikan!',
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
            if ($.fn.DataTable.isDataTable('#groupBarangTable')) {
                $('#groupBarangTable').DataTable().destroy();
            }

            var table = $('#groupBarangTable').DataTable({
                dom: '<"d-flex justify-content-between align-items-center mb-2"<"d-flex align-items-center"<"dataTables_length"l><"#custom-buttons-container">><"dataTables_filter"f>>rtip',
                paging: true,
                searching: true,
                info: true,
                ordering: true,
                pagingType: 'full_numbers',
                language: {
                    info: "Halaman _PAGE_ dari _PAGES_ (_TOTAL_ Total Group Barang)",
                    infoEmpty: "Tidak ada data group barang untuk ditampilkan",
                    infoFiltered: "(difilter dari _MAX_ total group barang)",
                    emptyTable: "Belum ada data group barang",
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
