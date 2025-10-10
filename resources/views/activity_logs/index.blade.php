<x-app-layout title="Manajemen Log Aktivitas">
    <x-content-header title="Manajemen Log Aktivitas" breadcrumb-parent="SuperAdmin"
        breadcrumb-url="{{ route('superadmin.activity-logs.index') }}" />

    <div id="custom-buttons" class="ms-3 mb-2">
        <a href="{{ route('superadmin.activity-logs.create') }}" class="btn btn-primary" id="btn-create-logs"
            title="Tambah Log Aktivitas Baru">
            <i class="fas fa-plus"></i> Tambah Log Aktivitas Baru
        </a>
    </div>

    <div class="content">
        <div class="container-fluid mb-3">
            <div class="table-responsive" id="logs-table-container">
                <table class="table table-sm table-striped table-hover table-bordered align-middle" id="logsTable">
                    <thead>
                        <tr class="text-center">
                            <th style="width:5%;">No</th>
                            <th style="width:15%;">Username</th>
                            <th style="width:45%;">Aktivitas</th>
                            <th style="width:25%;">Waktu Aktivitas</th>
                            <th style="width:10%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($logs->isEmpty())
                            <tr>
                                <td colspan="{{ $can_write ?? false ? '5' : '4' }}" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inboxdisplay-1"></i>
                                        <p class="mt-2">
                                            Belum ada data log aktivitas.
                                        </p>

                                        <a href="{{ route('superadmin.activity-logs.create') }}"
                                            class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Tambah Log Pertama
                                        </a>

                                    </div>
                                </td>
                            </tr>
                        @else
                            @foreach ($logs as $index => $log)
                                <tr class="text-center">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $log->user->username ?? $log->username }}</td>
                                    <td>{{ $log->activity }}</td>
                                    <td>
                                        @php
                                            $waktu = $log->updated_at ?? $log->created_at;
                                            if (!($waktu instanceof \Carbon\Carbon)) {
                                                $waktu = \Carbon\Carbon::parse($waktu);
                                            }
                                            $bulanIndo = [
                                                '01' => 'Januari',
                                                '02' => 'Februari',
                                                '03' => 'Maret',
                                                '04' => 'April',
                                                '05' => 'Mei',
                                                '06' => 'Juni',
                                                '07' => 'Juli',
                                                '08' => 'Agustus',
                                                '09' => 'September',
                                                '10' => 'Oktober',
                                                '11' => 'November',
                                                '12' => 'Desember',
                                            ];
                                            $tanggal =
                                                $waktu->format('d') .
                                                ' ' .
                                                ($bulanIndo[$waktu->format('m')] ?? $waktu->format('m')) .
                                                ' ' .
                                                $waktu->format('Y');
                                            $jam = $waktu->format('H:i:s');
                                        @endphp
                                        {{ $tanggal }} ({{ $jam }})
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('superadmin.activity-logs.edit', $log->id) }}"
                                                class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <form action="{{ route('superadmin.activity-logs.destroy', $log->id) }}"
                                                method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm btn-hapus-log"
                                                    title="Hapus">
                                                    <i class="fas fa-trash"></i>
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

    <style>
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
            document.querySelectorAll('.btn-hapus-log').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = btn.closest('.delete-form');

                    Swal.fire({
                        title: 'Yakin ingin menghapus log ini?',
                        text: 'Data log yang dihapus tidak bisa dikembalikan!',
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
            if ($.fn.DataTable.isDataTable('#logsTable')) {
                $('#logsTable').DataTable().destroy();
            }

            var table = $('#logsTable').DataTable({
                dom: '<"d-flex justify-content-between align-items-center mb-2"<"d-flex align-items-center"<"dataTables_length"l><"#custom-buttons-container">><"dataTables_filter"f>>rtip',
                paging: true,
                searching: true,
                info: true,
                ordering: true,
                pagingType: 'full_numbers',
                lengthMenu: [
                    [20, 50, 100, 200, 500],
                    [20, 50, 100, 200, 500]
                ],
                language: {
                    info: "Halaman _PAGE_ dari _PAGES_ (_TOTAL_ Total Log Aktivitas)",
                    infoEmpty: "Tidak ada data log aktivitas untuk ditampilkan",
                    infoFiltered: "(difilter dari _MAX_ total log aktivitas)",
                    emptyTable: "Belum ada data log aktivitas",
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
