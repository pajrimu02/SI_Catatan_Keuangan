<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Catatan Keuangan')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            background: #f5f6fa;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background: #111827;
            color: white;
            overflow-y: auto;
            z-index: 1030;
        }

        .sidebar .logo {
            padding: 20px;
            font-size: 18px;
            font-weight: bold;
            border-bottom: 1px solid #374151;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar a.nav-link {
            color: #cbd5e1;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 20px;
            border-radius: 8px;
            margin: 2px 10px;
            font-size: 14px;
            transition: background 0.15s;
        }

        .sidebar a.nav-link:hover {
            background: #1f2937;
            color: white;
        }

        .sidebar a.nav-link.active {
            background: #ffffff;
            color: #111827;
            font-weight: 600;
        }

        .nav-divider {
            height: 1px;
            background: #1f2937;
            margin: 8px 16px;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            min-height: 100vh;
        }

        .topbar {
            background: white;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .profile img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
        }

        .avatar-link {
            display: inline-block;
            border-radius: 50%;
            transition: box-shadow .18s, transform .18s;
        }

        .avatar-link:hover {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.35);
            transform: scale(1.06);
        }

        .avatar-link img {
            display: block;
            border-radius: 50%;
        }

        .bottom-logout {
            position: absolute;
            bottom: 20px;
            width: 100%;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                left: -250px;
                transition: left 0.25s ease;
            }

            .sidebar.show {
                left: 0;
            }

            .content {
                margin-left: 0;
            }
        }
    </style>

    @stack('styles')
</head>
<body>

    {{-- SIDEBAR --}}
    <div class="sidebar" id="sidebar">

        <div class="logo">
            <i class="fa-solid fa-wallet"></i> Catatan Keuangan
        </div>

        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-gauge"></i> Dashboard
        </a>

        <a href="{{ route('catatan.index') }}" class="nav-link {{ request()->routeIs('catatan.*') ? 'active' : '' }}">
            <i class="fa-solid fa-book"></i> Catatan
        </a>

        <div class="nav-divider"></div>

        <a href="{{ route('profile.index') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <i class="fa-solid fa-user"></i> Profile
        </a>

        <div class="bottom-logout px-3">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-danger w-100">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </button>
            </form>
        </div>

    </div>

    {{-- CONTENT --}}
    <div class="content">

        {{-- TOP BAR --}}
        <div class="topbar">
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-light d-lg-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h5 class="mb-0">@yield('title', 'Dashboard')</h5>
            </div>

            <div class="profile">
                <span class="d-none d-sm-inline">{{ auth()->user()->name }}</span>
                <a href="{{ route('profile.index') }}" class="avatar-link" title="Lihat Profil">
                    <img src="{{ auth()->user()->avatar_url }}" alt="profile">
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
@push('scripts')
<script>
function previewAvatar(event) {
    const preview = document.getElementById('avatarPreview');
    const file = event.target.files[0];
    if (file) {
        preview.src = URL.createObjectURL(file);
    }
}
</script>
@endpush