<x-app-layout title="Manajemen Group Barang">
    <x-content-header title="Daftar Group Barang" breadcrumb-parent="Master Data"
        breadcrumb-url="{{ route('item-groups.index') }}" />

    <div id="custom-buttons" class="ms-3 mb-2">
        <a href="{{ route('item-groups.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Group Barang
        </a>
    </div>

    <div class="content">
        <div class="container-fluid mb-3">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="groupBarangTable">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Nama Group</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($itemGroups->isEmpty())
                            <tr>
                                <td colspan="{{ $can_write ?? false ? '4' : '3' }}" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox display-1"></i>
                                        <p class="mt-2">Belum ada data group barang</p>
                                        <a href="{{ route('item-groups.create') }}" class="btn btn-primary">
                                            <i class="bi bi-plus"></i> Tambah Group Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @else
                            @foreach ($itemGroups as $index => $group)
                                <tr class="text-center">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $group->group_name }}</td>
                                    <td>{{ $group->description ?? '-' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('item-groups.show', $group->id) }}"
                                                class="btn btn-info btn-sm" title="Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('item-groups.edit', $group->id) }}"
                                                class="btn btn-warning btn-sm" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="#" class="btn btn-danger btn-sm btn-hapus-group"
                                                data-action="{{ route('item-groups.destroy', $group->id) }}"
                                                title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                            {{-- <form action="{{ route('item-groups.destroy', $group->id) }}" method="POST"
                                                class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm btn-hapus-group"
                                                    title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form> --}}
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

    <form id="form-hapus-group" method="POST" style="display:none;">
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

            // ✅ EVENT DELEGATION YANG BENAR - Menangani tombol hapus di semua halaman pagination
            $(document).on('click', '.btn-hapus-group', function(e) {
                e.preventDefault();
                const action = $(this).data('action'); // ✅ Gunakan $(this), bukan btn

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
                        const form = document.getElementById('form-hapus-group');
                        form.setAttribute('action', action);
                        form.submit();
                    }
                });
            });
        });
    </script>
</x-app-layout>
