<x-app-layout>
    <x-content-header title="Manajemen Log Aktivitas" breadcrumb-parent="SuperAdmin"
        breadcrumb-url="{{ route('activity-logs.index') }}" />

    @if ($can_write ?? false)
        <div id="custom-buttons" class="ms-3 mb-2">
            <a href="{{ route('activity-logs.create') }}" class="btn btn-primary" id="btn-create-logs"
                title="Tambah Log Aktivitas Baru">
                <i class="bi bi-journal-plus"></i> Tambah Log Aktivitas Baru
            </a>
        </div>
    @endif

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
                            @if ($can_write ?? false)
                                <th style="width:10%;">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if ($activityLogs->isEmpty())
                            <tr>
                                <td colspan="{{ $can_write ?? false ? '5' : '4' }}" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox display-1"></i>
                                        <p class="mt-2">
                                            Belum ada data log aktivitas.
                                        </p>
                                        @if ($can_write ?? false)
                                            <a href="{{ route('activity-logs.create') }}" class="btn btn-primary">
                                                <i class="bi bi-plus"></i> Tambah Log Pertama
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @else
                            @foreach ($activityLogs as $index => $log)
                                <tr class="text-center">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $log->user->username ?? $log->username }}</td>
                                    <td>{{ $log->activity }}</td>
                                    <td>
                                        @php
                                            $waktu = $log->updated_at ?? $log->created_at;
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
                                    @if ($can_write ?? false)
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('activity-logs.edit', $log->id) }}"
                                                    class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('activity-logs.destroy', $log->id) }}"
                                                    method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm btn-hapus-log"
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
