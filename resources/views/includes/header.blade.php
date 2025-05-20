<!-- Responsive Header Section -->
<header class="bg-dark text-white shadow-sm">
    <nav class="navbar navbar-expand-lg navbar-dark container py-3">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">New Bie Management System</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto">
                <!-- Common Navigation Items -->
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">Home</a>
                </li>

                <!-- Role-based Navigation Items -->
                @auth
                    @if(auth()->user()->role === 'admin')
                        <li class="nav-item"><a href="{{ route('admin.dashboard') }}" class="nav-link">Admin Dashboard</a></li>
                        <li class="nav-item"><a href="{{ route('admin.visitors.index') }}" class="nav-link">Manage Visitors</a></li>
                        <li class="nav-item"><a href="{{ route('admin.visitor.history') }}" class="nav-link">Visit History</a></li>
                        <li class="nav-item"><a href="{{ route('admin.reception.index') }}" class="nav-link">Receptionists</a></li>
                    @elseif(auth()->user()->role === 'reception')
                        <li class="nav-item"><a href="{{ route('reception.dashboard.index') }}" class="nav-link">Dashboard</a></li>
                        <li class="nav-item"><a href="{{ route('reception.visitor.index') }}" class="nav-link">Visitors</a></li>
                        <li class="nav-item"><a href="{{ route('visitor.history.index') }}" class="nav-link">Visit History</a></li>
                    @endif

                    <!-- Logout Button -->
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link text-white">Logout</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item"><a href="{{ route('login') }}" class="nav-link">Login</a></li>
                    <li class="nav-item"><a href="{{ route('register') }}" class="nav-link">Register</a></li>
                @endauth
            </ul>
        </div>
    </nav>
</header>
