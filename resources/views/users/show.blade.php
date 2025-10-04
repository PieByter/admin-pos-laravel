<x-app-layout title="Detail User">
    <x-content-header title="Detail User" breadcrumb-parent="Master Data"
        breadcrumb-url="{{ route('superadmin.users.index') }}" />

    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-person"></i> Detail User</h5>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-3">
                            <dt class="col-sm-4">Username</dt>
                            <dd class="col-sm-8">{{ $user->username }}</dd>

                            <dt class="col-sm-4">Email</dt>
                            <dd class="col-sm-8">{{ $user->email }}</dd>

                            <dt class="col-sm-4">Role</dt>
                            <dd class="col-sm-8">
                                @php
                                    $roleColors = [
                                        'superadmin' => 'bg-primary',
                                        'admin' => 'bg-info',
                                        'kasir' => 'bg-warning',
                                        'gudang' => 'bg-secondary',
                                        'staff' => 'bg-success',
                                    ];
                                @endphp
                                @if ($user->roles->count() > 0)
                                    @foreach ($user->roles as $role)
                                        @php
                                            $roleColor = $roleColors[$role->name] ?? 'bg-dark';
                                        @endphp
                                        <span class="badge {{ $roleColor }} me-1">
                                            {{ ucfirst($role->name) }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="badge bg-secondary">Tidak ada role</span>
                                @endif
                            </dd>

                            <dt class="col-sm-4">Jabatan</dt>
                            <dd class="col-sm-8">
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
                                {{ $user->job_title ?? '-' }}
                            </dd>

                            <dt class="col-sm-4">Bagian</dt>
                            <dd class="col-sm-8">{{ $user->position ?? '-' }}</dd>

                            <dt class="col-sm-4">Status</dt>
                            <dd class="col-sm-8">
                                @if ($user->status == 'active')
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Nonaktif</span>
                                @endif
                            </dd>

                            <dt class="col-sm-4">Permissions</dt>
                            <dd class="col-sm-8">
                                @if ($user->permissions->count() > 0)
                                    @foreach ($user->permissions as $permission)
                                        @php
                                            $badgeClass = 'bg-secondary';
                                            $icon = 'bi-eye';

                                            if (str_contains($permission->name, 'create')) {
                                                $badgeClass = 'bg-success';
                                                $icon = 'bi-plus-circle';
                                            } elseif (str_contains($permission->name, 'edit')) {
                                                $badgeClass = 'bg-warning';
                                                $icon = 'bi-pencil';
                                            } elseif (str_contains($permission->name, 'delete')) {
                                                $badgeClass = 'bg-danger';
                                                $icon = 'bi-trash';
                                            } elseif (str_contains($permission->name, 'view')) {
                                                $badgeClass = 'bg-info';
                                                $icon = 'bi-eye';
                                            }
                                        @endphp
                                        <span class="badge {{ $badgeClass }} me-1 mb-1">
                                            <i class="{{ $icon }}"></i>
                                            {{ str_replace('.', ' ', ucfirst($permission->name)) }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-muted">Tidak ada permission langsung</span>
                                @endif
                            </dd>

                            <dt class="col-sm-4">Permissions dari Role</dt>
                            <dd class="col-sm-8">
                                @php
                                    $rolePermissions = $user->getPermissionsViaRoles();
                                @endphp
                                @if ($rolePermissions->count() > 0)
                                    @foreach ($rolePermissions as $permission)
                                        @php
                                            $badgeClass = 'bg-light text-dark';
                                            $icon = 'bi-shield-check';
                                        @endphp
                                        <span class="badge {{ $badgeClass }} me-1 mb-1">
                                            <i class="{{ $icon }}"></i>
                                            {{ str_replace('.', ' ', ucfirst($permission->name)) }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-muted">Tidak ada permission dari role</span>
                                @endif
                            </dd>
                            {{-- 
                            <dt class="col-sm-4">Dibuat</dt>
                            <dd class="col-sm-8">
                                @php
                                    $createdAt = \Carbon\Carbon::parse($user->created_at);
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
                                        $createdAt->format('d') .
                                        ' ' .
                                        $bulanIndo[$createdAt->format('m')] .
                                        ' ' .
                                        $createdAt->format('Y');
                                    $jam = $createdAt->format('H:i:s');
                                @endphp
                                {{ $tanggal }} ({{ $jam }})
                            </dd>

                            <dt class="col-sm-4">Terakhir Diupdate</dt>
                            <dd class="col-sm-8">
                                @php
                                    $updatedAt = \Carbon\Carbon::parse($user->updated_at);
                                    $tanggalUpdate =
                                        $updatedAt->format('d') .
                                        ' ' .
                                        $bulanIndo[$updatedAt->format('m')] .
                                        ' ' .
                                        $updatedAt->format('Y');
                                    $jamUpdate = $updatedAt->format('H:i:s');
                                @endphp
                                {{ $tanggalUpdate }} ({{ $jamUpdate }})
                            </dd> --}}
                        </dl>

                        <div class="mt-4 d-flex justify-content-end gap-2">
                            <a href="{{ route('superadmin.users.edit', $user->id) }}" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="{{ route('superadmin.users.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            @if (session('error'))
                Toast.fire({
                    icon: 'error',
                    title: '{{ session('error') }}'
                });
            @endif

            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: '{{ session('success') }}'
                });
            @endif
        });
    </script>
</x-app-layout>
