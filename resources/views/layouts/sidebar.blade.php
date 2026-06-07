@once
    @push('styles')
        <link rel="stylesheet" href="{{ asset('build/assets/sidebar-ClQ81Ax0.css') }}">
    @endpush
@endonce

<div class="sidebar">
    <div class="sidebar-logo">
        <img src="{{ asset('images/logo/logo-maester.webp') }}" alt="Logo" class="sidebar-logo-image">
        <span class="sidebar-logo-text">broadcast</span>
    </div>

    <div class="sidebar-menu">
        <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span class="menu-item-text">Dashboard</span>
        </a>

        @if (auth()->user()->role === 'admin')
            <a href="{{ route('licenses.index') }}" class="menu-item {{ request()->routeIs('licenses.*') ? 'active' : '' }}">
                <i class="fas fa-key"></i>
                <span class="menu-item-text">Manage Licenses</span>
            </a>
            <a href="{{ route('devices.index') }}" class="menu-item {{ request()->routeIs('devices.*') ? 'active' : '' }}">
                <i class="fas fa-wifi"></i>
                <span class="menu-item-text">Manage Devices</span>
            </a>
            <a href="{{ route('activation-logs') }}" class="menu-item {{ request()->routeIs('activation-logs') ? 'active' : '' }}">
                <i class="fas fa-list"></i>
                <span class="menu-item-text">Activation Logs</span>
            </a>
        @endif

        @if (auth()->user()->role === 'client')
            <a href="#" class="menu-item">
                <i class="fas fa-laptop"></i>
                <span class="menu-item-text">My Licenses</span>
            </a>
        @endif

        @if (auth()->user()->role === 'admin')
            <a href="{{ route('settings.index') }}" class="menu-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <i class="fas fa-cog"></i>
                <span class="menu-item-text">Settings</span>
            </a>
        @else
            <a href="#" class="menu-item">
                <i class="fas fa-cog"></i>
                <span class="menu-item-text">Settings</span>
            </a>
        @endif

        <a href="#" class="menu-item">
            <i class="fas fa-question-circle"></i>
            <span class="menu-item-text">Help</span>
        </a>
    </div>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div class="user-info-sidebar">
                <div class="user-name-sidebar">{{ auth()->user()->name }}</div>
                <div class="user-role-sidebar">{{ auth()->user()->role }}</div>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-sidebar">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</div>
