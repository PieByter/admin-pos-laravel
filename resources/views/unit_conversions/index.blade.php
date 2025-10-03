<x-app-layout>

    <x-content-header title="Manajemen Satuan Konversi" breadcrumb-parent="Master Data"
        breadcrumb-url="{{ route('unit-conversions.index') }}" />


    <div id="custom-buttons" class="ms-3 mb-2">
        <a href="{{ route('unit-conversions.create') }}" class="btn btn-primary">
            <i class="bi bi-plus"></i> Tambah Konversi Baru
        </a>
    </div>


    <div class="content">
        <div class="container-fluid mb-3">
            <div class="table-responsive" id="konversi-table-container">
                <table id="satuanKonversiTable"
                    class="table table-striped table-hover table-bordered table-sm align-middle small">
                    <thead>
                        <tr class="text-center">
                            <th style="width:5%;">No</th>
                            <th style="width:20%;">Barang</th>
                            <th style="width:15%;">Satuan</th>
                            <th style="width:15%;">Konversi</th>
                            <th style="width:35%;">Keterangan</th>

                            <th style="width:10%;">Aksi</th>

                        </tr>
                    </thead>
                    <tbody>
                        @if ($conversions->isEmpty())
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox display-1"></i>
                                        <p class="mt-2">
                                            @if (!empty($search))
                                                Tidak ada konversi yang sesuai dengan pencarian "{{ $search }}"
                                            @else
                                                Belum ada data konversi
                                            @endif
                                        </p>

                                        <a href="{{ route('unit-conversions.create') }}" class="btn btn-primary">
                                            <i class="bi bi-plus"></i> Tambah Konversi Pertama
                                        </a>

                                    </div>
                                </td>
                            </tr>
                        @else
                            @foreach ($conversions as $index => $conversion)
                                <tr class="text-center">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $conversion->item_name ?? '-' }}</td>
                                    <td>{{ $conversion->unit_name ?? '-' }}</td>
                                    <td>{{ number_format($conversion->conversion_rate, 2) }}</td>
                                    <td>{{ $conversion->description ?? '-' }}</td>

                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('unit-conversions.show', $conversion->id) }}"
                                                class="btn btn-info btn-sm" title="Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('unit-conversions.edit', $conversion->id) }}"
                                                class="btn btn-warning btn-sm" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('unit-conversions.destroy', $conversion->id) }}"
                                                method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm btn-hapus-konversi"
                                                    title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
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

            var table = $('#satuanKonversiTable').DataTable({
                dom: '<"d-flex justify-content-between align-items-center mb-2"<"d-flex align-items-center"<"dataTables_length"l><"#custom-buttons-container">><"dataTables_filter"f>>rtip',
                paging: true,
                searching: true,
                info: true,
                ordering: true,
                pagingType: 'full_numbers',
                language: {
                    info: "Halaman _PAGE_ dari _PAGES_ (_TOTAL_ Total Satuan Konversi)",
                    paginate: {
                        first: '&laquo;',
                        last: '&raquo;',
                        previous: '&lsaquo;',
                        next: '&rsaquo;'
                    },
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Data tidak ditemukan",
                    emptyTable: "Tidak ada data konversi",
                    loadingRecords: "Memuat...",
                    processing: "Memproses..."
                }
            });

            $('#custom-buttons').appendTo('#custom-buttons-container');

            // Handle delete confirmation
            document.querySelectorAll('.btn-hapus-konversi').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = btn.closest('.delete-form');

                    Swal.fire({
                        title: 'Yakin ingin menghapus konversi ini?',
                        text: 'Data konversi yang dihapus tidak bisa dikembalikan!',
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
        });
    </script>
</x-app-layout>
