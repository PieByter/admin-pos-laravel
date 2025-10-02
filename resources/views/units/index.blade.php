{{-- filepath: c:\laragon\www\admin-pos\resources\views\units\index.blade.php --}}
<x-app-layout>

    <x-content-header title="Manajemen Satuan" breadcrumb-parent="Master Data"
        breadcrumb-url="{{ route('units.index') }}" />

    @if ($can_write ?? false)
        <div id="custom-buttons" class="ms-3 mb-2">
            <a href="{{ route('units.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Tambah Satuan Baru
            </a>
        </div>
    @endif

    <div class="content">
        <div class="container-fluid mb-3">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered" id="satuanTable">
                    <thead>
                        <tr class="text-center">
                            <th style="width:5%;">No</th>
                            <th style="width:30%;">Nama Satuan</th>
                            <th style="width:45%;">Keterangan</th>
                            @if ($can_write ?? false)
                                <th style="width:20%;">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if ($units->isEmpty())
                            <tr class="text-center">
                                <td colspan="4" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox display-1"></i>
                                        <p class="mt-2">
                                            Belum ada data satuan
                                        </p>
                                        @if ($can_write ?? false)
                                            <a href="{{ route('units.create') }}" class="btn btn-primary">
                                                <i class="bi bi-plus"></i> Tambah Satuan Pertama
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @else
                            @foreach ($units as $index => $unit)
                                <tr class="text-center">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $unit->name }}</td>
                                    <td>{{ $unit->description ?? '-' }}</td>
                                    @if ($can_write ?? false)
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('units.show', $unit->id) }}"
                                                    class="btn btn-info btn-sm" title="Detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('units.edit', $unit->id) }}"
                                                    class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('units.destroy', $unit->id) }}" method="POST"
                                                    class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-danger btn-sm btn-hapus-satuan" title="Hapus">
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
            // Handle delete confirmation
            document.querySelectorAll('.btn-hapus-satuan').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = btn.closest('.delete-form');

                    Swal.fire({
                        title: 'Yakin ingin menghapus satuan ini?',
                        text: 'Data satuan yang dihapus tidak bisa dikembalikan!',
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

            // Toast notifications
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
            var table = $('#satuanTable').DataTable({
                dom: '<"d-flex justify-content-between align-items-center mb-2"<"d-flex align-items-center"<"dataTables_length"l><"#custom-buttons-container">><"dataTables_filter"f>>rtip',
                paging: true,
                searching: true,
                info: true,
                ordering: true,
                pagingType: 'full_numbers',
                language: {
                    info: "Halaman _PAGE_ dari _PAGES_ (_TOTAL_ Total Satuan Barang)",
                    paginate: {
                        first: '&laquo;',
                        last: '&raquo;',
                        previous: '&lsaquo;',
                        next: '&rsaquo;'
                    },
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Data tidak ditemukan",
                    emptyTable: "Tidak ada data satuan",
                    loadingRecords: "Memuat...",
                    processing: "Memproses..."
                }
            });

            // Move custom buttons to DataTable
            $('#custom-buttons').appendTo('#custom-buttons-container');
        });
    </script>

</x-app-layout>
