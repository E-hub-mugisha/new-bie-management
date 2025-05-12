<!-- Header Section -->
<header class="bg-dark text-white py-3">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0">New Bie Management System</h2>
            <nav>
                <ul class="nav">
                    <!-- Common Navigation Items -->
                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link text-white">Home</a>
                    </li>

                    <!-- Role-based Navigation Items -->
                    @if(auth()->check() && auth()->user()->role === 'admin')
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link text-white">Admin Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.visitors.index') }}" class="nav-link text-white">Manage Visitors</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.visitor.history') }}" class="nav-link text-white">Visit History</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.reception.index') }}" class="nav-link text-white">Receptionists</a>
                    </li>
                    @elseif(auth()->check() && auth()->user()->role === 'reception')
                    <li class="nav-item">
                        <a href="{{ route('reception.dashboard.index') }}" class="nav-link text-white">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('reception.visitor.index') }}" class="nav-link text-white">Visitors</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('visitor.history.index') }}" class="nav-link text-white">Visit History</a>
                    </li>
                    @else
                    <li class="nav-item">
                        <a href="/login" class="nav-link text-white">Login</a>
                    </li>
                    <li class="nav-item">
                        <a href="/register" class="nav-link text-white">Register</a>
                    </li>
                    @endif

                    <!-- Common Logout Link -->
                    @if(auth()->check())
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link text-white">Logout</button>
                        </form>
                    </li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>
</header>