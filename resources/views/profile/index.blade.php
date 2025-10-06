<x-app-layout title="Profile Page">
    <x-content-header title="Pengaturan Akun" breadcrumb-parent="Profile" breadcrumb-url="{{ route('profile.index') }}" />

    <div class="content">
        <div class="container-fluid">
            <div class="row align-items-stretch">
                <div class="col-md-8">
                    <div class="card h-100">
                        <div class="card-header d-flex align-items-center">
                            <h5 class="card-title mb-0">Pengaturan Profil</h5>
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm ms-auto">
                                <i class="bi bi-pencil"></i> Edit Profil
                            </a>
                        </div>
                        <div class="card-body">
                            <dl class="row mb-3 table-detail">
                                <dt class="col-sm-3">Username</dt>
                                <dd class="col-sm-9">{{ $user->username }}</dd>

                                <dt class="col-sm-3">Email</dt>
                                <dd class="col-sm-9">{{ $user->email ?? '-' }}</dd>

                                <dt class="col-sm-3">Role</dt>
                                <dd class="col-sm-9">
                                    @php
                                        $roleConfig = [
                                            'superadmin' => 'bg-primary',
                                            'useradmin' => 'bg-info',
                                            'cashier' => 'bg-warning',
                                            'warehouse' => 'bg-secondary',
                                            'viewer' => 'bg-success',
                                        ];
                                        $badgeClass = $roleConfig[$user->role] ?? 'bg-secondary';
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </dd>

                                <dt class="col-sm-3">Jabatan</dt>
                                <dd class="col-sm-9">
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
                                    {{ $user->position ?? '-' }}
                                </dd>

                                <dt class="col-sm-3">Jabatan Fungsional</dt>
                                <dd class="col-sm-9">{{ $user->job_title ?? '-' }}</dd>

                                <dt class="col-sm-3">Bagian/Divisi</dt>
                                <dd class="col-sm-9">{{ $user->division ?? '-' }}</dd>

                                <dt class="col-sm-3">Akses</dt>
                                <dd class="col-sm-9">
                                    @if ($user->permissions && $user->permissions->count() > 0)
                                        @php
                                            $permissionGroups = [];
                                            $fullAccessPerms = [];

                                            foreach ($user->permissions as $perm) {
                                                $permName = $perm->display_name;
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
                                        @endphp

                                        @foreach ($permissionGroups as $perm)
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
                                            <span class="badge {{ $badgeClass }} me-1 mb-1" style="font-size: 0.8em;">
                                                <i class="bi {{ $iconClass }}"></i>
                                                {{ $perm['display'] }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">Tidak ada akses</span>
                                    @endif
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Akun</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-4">
                                <div class="flex-shrink-0">
                                    <div
                                        class="avatar avatar-xl text-dark d-flex align-items-center justify-content-center">
                                        @if ($user->foto && Storage::disk('public')->exists('profile/' . $user->foto))
                                            <img src="{{ Storage::url('profile/' . $user->foto) }}" alt="Profile Photo"
                                                class="rounded-circle"
                                                style="width:60px; height:60px; object-fit:cover;">
                                        @else
                                            <i class="bi bi-person-circle" style="font-size:3rem;"></i>
                                        @endif
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <h5 class="mb-0">{{ $user->username }}</h5>
                                    <p class="text-muted mb-0">{{ ucfirst($user->role) }}</p>
                                </div>
                            </div>

                            <div class="mb-3">
                                <h6 class="mb-1">Terakhir Login:</h6>
                                <p class="text-muted">
                                    @if ($user->last_login_at)
                                        {{ \Carbon\Carbon::parse($user->last_login_at)->format('d-m-Y H:i:s') }}
                                    @else
                                        {{ now()->format('d-m-Y H:i:s') }}
                                    @endif
                                </p>
                            </div>

                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="bi bi-box-arrow-right"></i> Sign Out
                                </button>
                            </form>
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
        });
    </script>
</x-app-layout>
