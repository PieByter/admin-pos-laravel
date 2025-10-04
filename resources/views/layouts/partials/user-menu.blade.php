<!-- filepath: resources/views/layouts/partials/user-menu.blade.php -->
<li class="nav-item dropdown user-menu">
    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
        @if ($user && $user->photo && file_exists(public_path('uploads/profile/' . $user->photo)))
            <img src="{{ asset('uploads/profile/' . $user->photo) }}" class="user-image rounded-circle shadow"
                alt="User Image" style="width:25px; height:25px; object-fit:cover;">
        @else
            <img src="{{ asset('img/avatar.png') }}" class="user-image rounded-circle shadow" alt="Default User Image">
        @endif
        <span class="d-none d-md-inline">{{ $user->username ?? 'User' }}</span>
    </a>
    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
        <li class="user-header d-flex flex-column align-items-center justify-content-center"
            style="background: url('{{ asset('img/bg_picture.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
            @if ($user && $user->photo && file_exists(public_path('uploads/profile/' . $user->photo)))
                <img src="{{ asset('uploads/profile/' . $user->photo) }}" class="rounded-circle shadow" alt="User Image"
                    style="width:80px; height:80px; object-fit:cover;">
            @else
                <img src="{{ asset('img/avatar.png') }}" class="rounded-circle shadow" alt="Default User Image">
            @endif
            <p>
                {{ $user->username ?? 'User' }} -
                @if (isset($user->role))
                    {{ $user->role }}
                @elseif($user->roles && $user->roles->isNotEmpty())
                    {{ $user->roles->first()->name }}
                @else
                    Role
                @endif
                <small>Member since {{ $user && $user->created_at ? $user->created_at->format('M. Y') : '-' }}</small>
            </p>
        </li>
        <li class="user-footer">
            <div class="row">
                <div class="col-6">
                    <a class="btn btn-block btn-outline-primary" href="{{ route('profile.index') }}">
                        <i class="bi bi-person-circle"></i> Profile
                    </a>
                </div>
                <div class="col-6">
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-block btn-outline-danger float-end">
                            <i class="bi bi-box-arrow-right"></i> Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </li>
    </ul>
</li>
