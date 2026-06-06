<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Broadcaster Platform</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
            display: flex;
        }
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #1a1a2e 0%, #16213e 100%);
            padding: 24px;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
        }
        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .sidebar-logo-icon {
            font-size: 28px;
            color: #667eea;
        }
        .sidebar-logo-text {
            font-size: 20px;
            font-weight: bold;
            color: white;
        }
        .sidebar-menu {
            flex: 1;
        }
        .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            color: #b8b8b8;
            text-decoration: none;
            border-radius: 10px;
            margin-bottom: 8px;
            transition: all 0.3s ease;
        }
        .menu-item:hover {
            background: rgba(102, 126, 234, 0.2);
            color: white;
        }
        .menu-item.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        .menu-item i {
            font-size: 18px;
            width: 24px;
            text-align: center;
        }
        .menu-item-text {
            font-size: 14px;
            font-weight: 500;
        }
        .sidebar-footer {
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        .user-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            margin-bottom: 12px;
        }
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 16px;
        }
        .user-info-sidebar {
            flex: 1;
        }
        .user-name-sidebar {
            color: white;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 2px;
        }
        .user-role-sidebar {
            color: #b8b8b8;
            font-size: 11px;
            text-transform: uppercase;
        }
        .logout-sidebar {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            color: #e74c3c;
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .logout-sidebar:hover {
            background: rgba(231, 76, 60, 0.1);
        }
        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 32px;
        }
        .header {
            margin-bottom: 32px;
        }
        .header-title {
            font-size: 32px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 8px;
        }
        .header-subtitle {
            color: #666;
            font-size: 16px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }
        .stat-card {
            background: white;
            padding: 28px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        }
        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 16px;
        }
        .stat-icon.licenses {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .stat-icon.users {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        .stat-icon.devices {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }
        .stat-value {
            font-size: 36px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 4px;
        }
        .stat-label {
            color: #666;
            font-size: 14px;
            font-weight: 500;
        }
        .welcome-section {
            background: white;
            padding: 32px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            margin-bottom: 32px;
        }
        .welcome-section h2 {
            font-size: 24px;
            color: #1a1a2e;
            margin-bottom: 12px;
        }
        .welcome-section p {
            color: #666;
            font-size: 15px;
            line-height: 1.6;
        }
        .welcome-section strong {
            color: #1a1a2e;
            font-weight: 600;
        }
        .quick-actions {
            background: white;
            padding: 32px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        }
        .quick-actions h3 {
            font-size: 20px;
            color: #1a1a2e;
            margin-bottom: 20px;
        }
        .action-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }
        .action-card {
            padding: 24px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.3s ease;
            background: white;
        }
        .action-card:hover {
            border-color: #667eea;
            background: linear-gradient(135deg, #f8f9ff 0%, #f3f4f6 100%);
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.2);
        }
        .action-card i {
            font-size: 32px;
            color: #667eea;
            margin-bottom: 12px;
            transition: transform 0.3s ease;
        }
        .action-card:hover i {
            transform: scale(1.1);
        }
        .action-card-title {
            color: #1a1a2e;
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 6px;
        }
        .action-card-desc {
            color: #666;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-logo">
            <i class="fas fa-broadcast-tower sidebar-logo-icon"></i>
            <span class="sidebar-logo-text">Broadcaster</span>
        </div>
        
        <div class="sidebar-menu">
            <a href="{{ route('dashboard') }}" class="menu-item active">
                <i class="fas fa-home"></i>
                <span class="menu-item-text">Dashboard</span>
            </a>
            
            @if (auth()->user()->role === 'admin')
                <a href="{{ route('licenses.index') }}" class="menu-item">
                    <i class="fas fa-key"></i>
                    <span class="menu-item-text">Manage Licenses</span>
                </a>
                <a href="{{ route('devices.index') }}" class="menu-item">
                    <i class="fas fa-desktop"></i>
                    <span class="menu-item-text">Manage Devices</span>
                </a>
            @endif
            
            @if (auth()->user()->role === 'client')
                <a href="#" class="menu-item">
                    <i class="fas fa-laptop"></i>
                    <span class="menu-item-text">My Licenses</span>
                </a>
            @endif
            
            <a href="#" class="menu-item">
                <i class="fas fa-cog"></i>
                <span class="menu-item-text">Settings</span>
            </a>
            
            <a href="#" class="menu-item">
                <i class="fas fa-question-circle"></i>
                <span class="menu-item-text">Help</span>
            </a>
        </div>
        
        <div class="sidebar-footer">
            <div class="user-card">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="user-info-sidebar">
                    <div class="user-name-sidebar">{{ auth()->user()->name }}</div>
                    <div class="user-role-sidebar">{{ ucfirst(auth()->user()->role) }}</div>
                </div>
            </div>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-sidebar" style="width: 100%; background: none; border: none; cursor: pointer; font-size: 14px;">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <h1 class="header-title">Welcome, {{ auth()->user()->name }}!</h1>
            <p class="header-subtitle">{{ ucfirst(auth()->user()->role) }} Dashboard</p>
        </div>

        <div class="stats-grid">
            @if (auth()->user()->role === 'admin')
                <div class="stat-card">
                    <div class="stat-icon licenses">
                        <i class="fas fa-key"></i>
                    </div>
                    <div class="stat-value">{{ $stats['total_licenses'] }}</div>
                    <div class="stat-label">Total Licenses</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon devices">
                        <i class="fas fa-desktop"></i>
                    </div>
                    <div class="stat-value">{{ $stats['total_devices'] }}</div>
                    <div class="stat-label">Total Devices</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon devices" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                        <i class="fas fa-wifi"></i>
                    </div>
                    <div class="stat-value">{{ $stats['online_devices'] }}</div>
                    <div class="stat-label">Online Devices</div>
                </div>
            @endif

            <div class="stat-card">
                <div class="stat-icon users">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-value">{{ $stats['total_users'] }}</div>
                <div class="stat-label">Total Users</div>
            </div>
        </div>

        <div class="welcome-section">
            <h2>Good to see you, {{ auth()->user()->name }}!</h2>
            <p>You're logged in as <strong>{{ ucfirst(auth()->user()->role) }}</strong> with email <strong>{{ auth()->user()->email }}</strong>.</p>
            
            @if (auth()->user()->role === 'admin')
                <p style="margin-top: 12px;">You have full access to manage licenses and users in the system.</p>
            @else
                <p style="margin-top: 12px;">You can view and manage your assigned licenses from the sidebar menu.</p>
            @endif
        </div>

        @if (auth()->user()->role === 'admin')
            <div class="quick-actions">
                <h3>Quick Actions</h3>
                <div class="action-grid">
                    <a href="{{ route('licenses.create') }}" class="action-card">
                        <i class="fas fa-plus-circle"></i>
                        <div class="action-card-title">Create License</div>
                        <div class="action-card-desc">Issue new license</div>
                    </a>
                    <a href="{{ route('licenses.index') }}" class="action-card">
                        <i class="fas fa-list"></i>
                        <div class="action-card-title">View All Licenses</div>
                        <div class="action-card-desc">Manage existing</div>
                    </a>
                    <a href="{{ route('devices.index') }}" class="action-card">
                        <i class="fas fa-desktop"></i>
                        <div class="action-card-title">Manage Devices</div>
                        <div class="action-card-desc">View all devices</div>
                    </a>
                </div>
            </div>
        @endif
    </div>
</body>
</html>