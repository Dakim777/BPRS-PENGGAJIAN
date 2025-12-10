<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISFOR BPRS LAMPUNG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Ikon Bootstrap (Optional, agar tombol terlihat bagus) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
                <i class="bi bi-bank2"></i> SISFOR BPRS
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    @auth
                        {{-- MENU UTAMA --}}
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}" href="{{ route('employees.index') }}">Karyawan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('attendances.*') ? 'active' : '' }}" href="{{ route('attendances.index') }}">Absensi</a>
                        </li>
                        
                        {{-- MENU HARI LIBUR (BARU) --}}
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('holidays.*') ? 'active' : '' }}" href="{{ route('holidays.index') }}">Hari Libur</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('salaries.*') ? 'active' : '' }}" href="{{ route('salaries.index') }}">Penggajian</a>
                        </li>
                        
                        {{-- DIVIDER --}}
                        <li class="nav-item mx-2 d-none d-lg-block text-white-50">|</li>

                        {{-- TOMBOL LOGOUT --}}
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link text-warning fw-bold" style="text-decoration: none;">
                                    Logout <i class="bi bi-box-arrow-right"></i>
                                </button>
                            </form>
                        </li>
                    @else
                        {{-- JIKA BELUM LOGIN --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login Admin</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        {{-- SECTION ALERT (Success/Error) --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li><i class="bi bi-exclamation-circle-fill me-2"></i> {{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- KONTEN HALAMAN --}}
        <div class="mb-5">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>