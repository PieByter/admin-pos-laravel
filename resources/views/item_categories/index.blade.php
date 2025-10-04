<x-app-layout title="Manajemen Kategori Barang">
    <x-content-header title="Manajemen Jenis Barang" breadcrumb-parent="Master Data"
        breadcrumb-url="{{ route('item-categories.index') }}" />

    <div id="custom-buttons" class="ms-3 mb-2">
        <a href="{{ route('item-categories.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Jenis Barang
        </a>
    </div>

    <div class="content">
        <div class="container-fluid mb-3">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="jenisBarangTable">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Nama Jenis</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($categories->isEmpty())
                            <tr>
                                <td colspan="{{ $can_write ?? false ? '4' : '3' }}" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox display-1"></i>
                                        <p class="mt-2">Belum ada data jenis barang</p>

                                        <a href="{{ route('item-categories.create') }}" class="btn btn-primary">
                                            <i class="bi bi-plus"></i> Tambah Jenis Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @else
                            @foreach ($categories as $index => $category)
                                <tr class="text-center">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $category->category_name }}</td>
                                    <td>{{ $category->description ?? '-' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('item-categories.show', $category->id) }}"
                                                class="btn btn-info btn-sm" title="Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('item-categories.edit', $category->id) }}"
                                                class="btn btn-warning btn-sm" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="#" class="btn btn-danger btn-sm btn-hapus-jenis"
                                                data-action="{{ route('item-categories.destroy', $category->id) }}"
                                                title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                            {{-- <form action="{{ route('item-categories.destroy', $category->id) }}"
                                                method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm btn-hapus-jenis"
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

    <form id="form-hapus-jenis" method="POST" style="display:none;">
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

            // // Handle delete confirmation
            // document.querySelectorAll('.btn-hapus-jenis').forEach(function(btn) {
            //     btn.addEventListener('click', function(e) {
            //         e.preventDefault();
            //         const form = btn.closest('.delete-form');

            //         Swal.fire({
            //             title: 'Yakin ingin menghapus jenis barang ini?',
            //             text: 'Data jenis barang yang dihapus tidak bisa dikembalikan!',
            //             icon: 'warning',
            //             showCancelButton: true,
            //             confirmButtonColor: '#d33',
            //             cancelButtonColor: '#3085d6',
            //             confirmButtonText: 'Ya, hapus!',
            //             cancelButtonText: 'Batal'
            //         }).then((result) => {
            //             if (result.isConfirmed) {
            //                 form.submit();
            //             }
            //         });
            //     });
            // });

            // DataTable initialization
            if ($.fn.DataTable.isDataTable('#jenisBarangTable')) {
                $('#jenisBarangTable').DataTable().destroy();
            }

            var table = $('#jenisBarangTable').DataTable({
                dom: '<"d-flex justify-content-between align-items-center mb-2"<"d-flex align-items-center"<"dataTables_length"l><"#custom-buttons-container">><"dataTables_filter"f>>rtip',
                paging: true,
                searching: true,
                info: true,
                ordering: true,
                pagingType: 'full_numbers',
                language: {
                    info: "Halaman _PAGE_ dari _PAGES_ (_TOTAL_ Total Jenis Barang)",
                    infoEmpty: "Tidak ada data jenis barang untuk ditampilkan",
                    infoFiltered: "(difilter dari _MAX_ total jenis barang)",
                    emptyTable: "Belum ada data jenis barang",
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

            $(document).on('click', '.btn-hapus-jenis', function(e) {
                e.preventDefault();
                const action = $(this).data('action');
                Swal.fire({
                    title: 'Yakin ingin menghapus jenis barang ini?',
                    text: 'Data jenis barang yang dihapus tidak bisa dikembalikan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('form-hapus-jenis');
                        form.setAttribute('action', action);
                        form.submit();
                    }
                });
            });
        });
    </script>
</x-app-layout>
