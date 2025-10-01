<!-- filepath: resources/views/layouts/partials/user-menu.blade.php -->
<li class="nav-item dropdown user-menu">
    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
        @if ($user && $user->profile_picture && file_exists(public_path('uploads/profile/' . $user->profile_picture)))
            <img src="{{ asset('uploads/profile/' . $user->profile_picture) }}" class="user-image rounded-circle shadow"
                alt="User Image" style="width:25px; height:25px; object-fit:cover;">
        @else
            <img src="{{ asset('img/avatar.png') }}" class="user-image rounded-circle shadow" alt="Default User Image">
        @endif
        <span class="d-none d-md-inline">{{ $user->username ?? 'User' }}</span>
    </a>
    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
        <li class="user-header d-flex flex-column align-items-center justify-content-center"
            style="background: url('{{ asset('img/bg_picture.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
            @if ($user && $user->profile_picture && file_exists(public_path('uploads/profile/' . $user->profile_picture)))
                <img src="{{ asset('uploads/profile/' . $user->profile_picture) }}" class="rounded-circle shadow"
                    alt="User Image" style="width:80px; height:80px; object-fit:cover;">
            @else
                <img src="{{ asset('img/avatar.png') }}" class="rounded-circle shadow" alt="Default User Image">
            @endif
            <p>
                {{ $user->username ?? 'User' }} - {{ $user && $user->roles->first()->name ?? 'Role' }}<br>
                <small>Member since {{ $user && $user->created_at ? $user->created_at->format('M. Y') : '-' }}</small>
            </p>
        </li>
        <li class="user-footer">
            <div class="row">
                <div class="col-6">
                    <a class="btn btn-block btn-outline-primary" href="{{ url('profile') }}">
                        <i class="bi bi-person-circle"></i> Profile
                    </a>
                </div>
                <div class="col-6">
                    <a class="btn btn-block btn-outline-danger float-end" href="{{ url('auth/logout') }}">
                        <i class="bi bi-box-arrow-right"></i> Sign Out
                    </a>
                </div>
            </div>
        </li>
    </ul>
</li>
