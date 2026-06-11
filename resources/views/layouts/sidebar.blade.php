<div class="sidebar">
    <div class="sidebar-logo">
        <div class="sidebar-logo-container">
            <img src="{{ asset('images/logo/logo-maester.webp') }}" alt="Logo" class="sidebar-logo-image">
        </div>
        <span class="sidebar-logo-text">NR- BROADCASTER</span>
    </div>

    <div class="sidebar-menu">
        @if (Auth::user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span class="menu-item-text">Dashboard</span>
            </a>
            <a href="{{ route('admin.licenses.index') }}" class="menu-item {{ request()->routeIs('admin.licenses.*') ? 'active' : '' }}">
                <i class="fas fa-key"></i>
                <span class="menu-item-text">Manage Licenses</span>
            </a>
            <a href="{{ route('admin.devices.index') }}" class="menu-item {{ request()->routeIs('admin.devices.*') ? 'active' : '' }}">
                <i class="fas fa-wifi"></i>
                <span class="menu-item-text">Manage Devices</span>
            </a>
            <a href="{{ route('admin.activation-logs') }}" class="menu-item {{ request()->routeIs('admin.activation-logs') ? 'active' : '' }}">
                <i class="fas fa-list"></i>
                <span class="menu-item-text">Activation Logs</span>
            </a>
        @endif

        @if (Auth::user()->role === 'client')
            <a href="{{ route('client.dashboard') }}" class="menu-item {{ request()->routeIs('client.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span class="menu-item-text">Dashboard</span>
            </a>
            <a href="{{ route('client.licenses') }}" class="menu-item {{ request()->routeIs('client.licenses.*') ? 'active' : '' }}">
                <i class="fas fa-laptop"></i>
                <span class="menu-item-text">My Licenses</span>
            </a>
            <a href="{{ route('client.devices') }}" class="menu-item {{ request()->routeIs('client.devices.*') ? 'active' : '' }}">
                <i class="fas fa-wifi"></i>
                <span class="menu-item-text">My Devices</span>
            </a>
            <a href="{{ route('client.security') }}" class="menu-item {{ request()->routeIs('client.security') ? 'active' : '' }}">
                <i class="fas fa-shield-alt"></i>
                <span class="menu-item-text">Security</span>
            </a>
            <a href="{{ route('client.activity') }}" class="menu-item {{ request()->routeIs('client.activity') ? 'active' : '' }}">
                <i class="fas fa-history"></i>
                <span class="menu-item-text">Activity History</span>
            </a>
        @endif

        @if (Auth::user()->role === 'admin' && Auth::user()->name === 'admin')
            <a href="{{ route('account.index') }}" class="menu-item {{ request()->routeIs('account.*') ? 'active' : '' }}">
                <i class="fas fa-user-shield"></i>
                <span class="menu-item-text">Admin User</span>
            </a>
        @endif

        @if (Auth::user()->role === 'admin')
            <a href="{{ route('admin.settings.index') }}" class="menu-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="fas fa-cog"></i>
                <span class="menu-item-text">System Settings</span>
            </a>
            <a href="{{ route('account.index') }}" class="menu-item {{ request()->routeIs('account.*') ? 'active' : '' }}">
                <i class="fas fa-user"></i>
                <span class="menu-item-text">My Account</span>
            </a>
        @else
            <a href="{{ route('account.index') }}" class="menu-item {{ request()->routeIs('account.*') ? 'active' : '' }}">
                <i class="fas fa-user"></i>
                <span class="menu-item-text">My Account</span>
            </a>
        @endif

        @if (Auth::user()->role === 'client')
            <a href="#" class="menu-item">
                <i class="fas fa-question-circle"></i>
                <span class="menu-item-text">Help</span>
            </a>
        @endif
    </div>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="user-info-sidebar">
                <div class="user-name-sidebar">{{ Auth::user()->name }}</div>
                <div class="user-role-sidebar">{{ Auth::user()->role }}</div>
            </div>
        </div>

        <form method="POST" action="{{ Auth::user()->role === 'admin' ? route('admin.logout') : route('client.logout') }}">
            @csrf
            <button type="submit" class="logout-sidebar">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</div>
