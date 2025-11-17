<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Cinema Ticket Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,.75);
        }
        .sidebar .nav-link:hover {
            color: #fff;
        }
        .sidebar .nav-link.active {
            color: #fff;
            background-color: #0d6efd;
        }
        .main-content {
            margin-left: 250px;
        }
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar p-3" style="width: 250px;">
            <h5 class="text-white mb-4">Admin Panel</h5>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" href="{{ route('admin.users') }}">
                        <i class="fas fa-users me-2"></i>Users
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.films*') ? 'active' : '' }}" href="{{ route('admin.films') }}">
                        <i class="fas fa-film me-2"></i>Films
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.prices') ? 'active' : '' }}" href="{{ route('admin.prices') }}">
                        <i class="fas fa-dollar-sign me-2"></i>Prices
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.seats') ? 'active' : '' }}" href="{{ route('admin.seats') }}">
                        <i class="fas fa-chair me-2"></i>Seats
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.studios') ? 'active' : '' }}" href="{{ route('admin.studios') }}">
                        <i class="fas fa-building me-2"></i>Studios
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.schedules*') ? 'active' : '' }}" href="{{ route('admin.schedules') }}">
                        <i class="fas fa-calendar me-2"></i>Schedules
                    </a>
                </li>
                <li class="nav-item mt-3">
                    <a class="nav-link text-danger" href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="main-content flex-grow-1">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <span class="navbar-brand mb-0 h1">Cinema Admin</span>
                    <div class="d-flex">
                        <span class="navbar-text me-3">
                            Welcome, {{ auth()->user()->name }}
                        </span>
                    </div>
                </div>
            </nav>

            <div class="container-fluid p-4">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
