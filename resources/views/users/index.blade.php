<x-app-layout title="Manajemen User">
    <x-content-header title="Manajemen User" breadcrumb-parent="SuperAdmin"
        breadcrumb-url="{{ route('superadmin.users.index') }}" />


    <div id="custom-buttons" class="ms-3 mb-2">
        <a href="{{ route('superadmin.users.create') }}" class="btn btn-primary" id="btn-create-user"
            title="Tambah User Baru">
            <i class="bi bi-person-plus"></i> Tambah User Baru
        </a>
    </div>

    <div class="content">
        <div class="container-fluid mb-3">
            <div class="table-responsive" id="user-table-container">
                <table class="table table-striped table-hover table-bordered align-middle table-sm small text-center"
                    id="usersTable">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 10%;">Username</th>
                            <th style="width: 15%;">Email</th>
                            <th style="width: 10%;">Role</th>
                            <th style="width: 10%;">Posisi</th>
                            <th style="width: 10%;">Jabatan Fungsional</th>
                            <th style="width: 10%;">Bagian</th>
                            <th style="width: 20%;">Akses</th>
                            <th style="width: 10%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (empty($users))
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox display-1"></i>
                                        <p class="mt-2">
                                            {{ $search ? 'Tidak ada user yang sesuai dengan pencarian "' . e($search) . '"' : 'Belum ada data user' }}
                                        </p>

                                        <a href="{{ route('superadmin.users.create') }}" class="btn btn-primary">
                                            <i class="bi bi-person-plus"></i> Tambah User Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @else
                            @php $no = 1; @endphp

                            @foreach ($users as $user)
                                <tr style="cursor:pointer;"
                                    onclick="window.location='{{ route('superadmin.users.show', $user['id']) }}'">
                                    <td>{{ $no++ }}</td>
                                    <td>{{ e($user['username']) }}</td>
                                    <td>{{ e($user['email'] ?? '-') }}</td>
                                    <td>
                                        @php
                                            $badgeClass = match ($user['role']) {
                                                'superadmin' => 'bg-primary',
                                                'useradmin' => 'bg-info',
                                                'kasir' => 'bg-warning',
                                                'gudang' => 'bg-secondary',
                                                'viewer' => 'bg-success',
                                                default => 'bg-secondary',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            {{ ucfirst($user['role']) }}
                                        </span>
                                    </td>
                                    {{-- @php
                                        $jabatanList = [
                                            'staff' => 'Staff',
                                            'karyawan' => 'Karyawan',
                                            'kadept' => 'Kepala Depot (Kadept)',
                                            'wakadept' => 'Wakil Kepala Depot (Wakadept)',
                                            'kabid' => 'Kepala Bidang (Kabid)',
                                            'wakabid' => 'Wakil Kepala Bidang (Wakabid)',
                                            'kasubid' => 'Kepala Sub Bidang (Kasubid)',
                                            'wakasubid' => 'Wakil Kepala Sub Bidang (Wakasubid)',
                                            'kabag' => 'Kepala Bagian (Kabag)',
                                            'wakabag' => 'Wakil Kepala Bagian (Wakabag)',
                                            'kasubag' => 'Kepala Sub Bagian (Kasubag)',
                                            'wakasubag' => 'Wakil Kepala Sub Bagian (Wakasubag)',
                                            'kasie' => 'Kepala Seksi (Kasie)',
                                            'wakasie' => 'Wakil Kepala Seksi (Wakasie)',
                                            'kasubsie' => 'Kepala Sub Seksi (Kasubsie)',
                                            'wakasubsie' => 'Wakil Kepala Sub Seksi (Wakasubsie)',
                                            'kagu' => 'Kepala Regu (Kagu)',
                                            'wakagu' => 'Wakil Kepala Regu (Wakagu)',
                                            'kasubgu' => 'Kepala Sub Regu (Kasubgu)',
                                            'wakasubgu' => 'Wakil Kepala Sub Regu (Wakasubgu)',
                                        ];
                                    @endphp --}}
                                    <td>{{ e($user->position ?? '-') }}</td>
                                    <td>
                                        {{ e($user->job_title ?? '-') }}
                                    </td>
                                    <td>{{ e($user->division ?? '-') }}</td>
                                    <td>
                                        @if (isset($user['permissions']) && is_array($user['permissions']) && count($user['permissions']))
                                            @php
                                                $permissionGroups = [];
                                                $fullAccessPerms = [];

                                                foreach ($user['permissions'] as $perm) {
                                                    $permName = $perm['display_name'];

                                                    if (str_starts_with($permName, 'Kelola')) {
                                                        $module = str_replace('Kelola ', '', $permName);
                                                        $fullAccessPerms[] = $module;
                                                        $permissionGroups[$module] = [
                                                            'type' => 'full',
                                                            'display' => $permName,
                                                        ];
                                                    } elseif (str_starts_with($permName, 'Lihat')) {
                                                        $module = str_replace('Lihat ', '', $permName);
                                                        if (!in_array($module, $fullAccessPerms)) {
                                                            $permissionGroups[$module] = [
                                                                'type' => 'read',
                                                                'display' => $permName,
                                                            ];
                                                        }
                                                    } else {
                                                        $permissionGroups[$permName] = [
                                                            'type' => 'other',
                                                            'display' => $permName,
                                                        ];
                                                    }
                                                }

                                                // Batasi tampilan maksimal 3 permission untuk tabel
                                                $displayedPerms = array_slice($permissionGroups, 0, 3);
                                                $remainingCount = count($permissionGroups) - 3;
                                            @endphp

                                            @foreach ($displayedPerms as $perm)
                                                @php
                                                    $badgeClass = 'bg-secondary';
                                                    $iconClass = 'bi-eye';
                                                    if ($perm['type'] === 'full') {
                                                        $badgeClass = 'bg-danger';
                                                        $iconClass = 'bi-gear-fill';
                                                    } elseif ($perm['type'] === 'read') {
                                                        $badgeClass = 'bg-success';
                                                        $iconClass = 'bi-eye';
                                                    } elseif ($perm['type'] === 'other') {
                                                        $badgeClass = 'bg-info';
                                                        $iconClass = 'bi-speedometer2';
                                                    }
                                                @endphp
                                                <span class="badge {{ $badgeClass }} me-1 mb-1"
                                                    style="font-size: 0.7em;">
                                                    <i class="bi {{ $iconClass }}"></i>
                                                    {{ strlen($perm['display']) > 15 ? substr($perm['display'], 0, 15) . '...' : $perm['display'] }}
                                                </span>
                                            @endforeach

                                            @if ($remainingCount > 0)
                                                <span class="badge bg-light text-dark" style="font-size: 0.7em;">
                                                    +{{ $remainingCount }} lagi
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-muted small">Tidak ada akses</span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('superadmin.users.edit', $user['id']) }}"
                                                class="btn btn-warning btn-sm" title="Edit User">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            @if ($user['id'] != session('user_id'))
                                                <a href="{{ route('superadmin.users.destroy', $user['id']) }}"
                                                    class="btn btn-danger btn-sm btn-hapus-user"
                                                    onclick="event.stopPropagation();" title="Hapus User">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            @endif
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

            document.querySelectorAll('.btn-hapus-user').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = btn.getAttribute('href');
                    Swal.fire({
                        title: 'Yakin ingin menghapus user ini?',
                        text: 'Data user yang dihapus tidak bisa dikembalikan!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = url;
                        }
                    });
                });
            });

            var table = $('#usersTable').DataTable({
                dom: '<"d-flex justify-content-between align-items-center mb-2"<"d-flex align-items-center"<"dataTables_length"l><"#custom-buttons-container">><"dataTables_filter"f>>rtip',
                paging: true,
                searching: true,
                info: true,
                ordering: true,
                pagingType: 'full_numbers',
                language: {
                    info: "Halaman _PAGE_ dari _PAGES_ (_TOTAL_ Total Users)",
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
