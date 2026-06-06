<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devices Management - Broadcaster Platform</title>
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
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }
        .header-title {
            font-size: 32px;
            font-weight: 700;
            color: #1a1a2e;
        }
        .statistics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }
        .stat-card {
            background: white;
            padding: 24px;
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
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 16px;
        }
        .stat-icon-blue {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }
        .stat-icon-green {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        .stat-icon-yellow {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }
        .stat-icon-red {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }
        .stat-icon-purple {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: white;
        }
        .stat-label {
            font-size: 14px;
            color: #6b7280;
            font-weight: 500;
            margin-bottom: 8px;
        }
        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #1a1a2e;
        }
        .filter-section {
            background: white;
            padding: 24px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            margin-bottom: 24px;
        }
        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }
        .filter-group {
            display: flex;
            flex-direction: column;
        }
        .filter-label {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }
        .filter-select {
            padding: 10px 14px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            color: #374151;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .filter-select:hover {
            border-color: #667eea;
        }
        .filter-select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .filter-apply-btn {
            padding: 10px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }
        .filter-apply-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }
        .filter-clear-btn {
            padding: 10px 20px;
            background: #6b7280;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(107, 114, 128, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }
        .filter-clear-btn:hover {
            background: #4b5563;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(107, 114, 128, 0.4);
        }
        .filter-buttons-group {
            flex-direction: row !important;
            align-items: flex-end;
            gap: 12px;
        }
        .filter-buttons-group {
            flex-direction: row !important;
            align-items: flex-end;
            gap: 12px;
        }
        .table-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        th, td {
            padding: 16px 20px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        th {
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        tbody tr:hover {
            background: #f8f9ff;
        }
        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            display: inline-block;
        }
        .status-active {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        .status-suspended {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }
        .status-revoked {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }
        .status-online {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        .status-offline {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
            color: white;
        }
        .action-buttons {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            align-items: center;
        }
        .action-btn {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }
        .action-btn:hover {
            transform: translateY(-1px);
        }
        .action-btn:active {
            transform: translateY(0);
        }
        .action-btn i {
            font-size: 11px;
        }
        .view-btn {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }
        .view-btn:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }
        .activate-btn {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        .activate-btn:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }
        .suspend-btn {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }
        .suspend-btn:hover {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
        }
        .revoke-btn {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }
        .revoke-btn:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        }
        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert i {
            font-size: 20px;
        }
        .empty-state {
            text-align: center;
            padding: 64px 24px;
        }
        .empty-state-icon {
            font-size: 64px;
            color: #d1d5db;
            margin-bottom: 16px;
        }
        .empty-state-text {
            font-size: 18px;
            color: #6b7280;
        }
        .time-ago {
            font-size: 13px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-logo">
            <i class="fas fa-broadcast-tower sidebar-logo-icon"></i>
            <span class="sidebar-logo-text">Broadcaster</span>
        </div>
        
        <div class="sidebar-menu">
            <a href="{{ route('dashboard') }}" class="menu-item">
                <i class="fas fa-home"></i>
                <span class="menu-item-text">Dashboard</span>
            </a>
            <a href="{{ route('licenses.index') }}" class="menu-item">
                <i class="fas fa-key"></i>
                <span class="menu-item-text">Licenses</span>
            </a>
            <a href="{{ route('devices.index') }}" class="menu-item active">
                <i class="fas fa-desktop"></i>
                <span class="menu-item-text">Devices</span>
            </a>
        </div>
        
        <div class="sidebar-footer">
            <div class="user-card">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="user-info-sidebar">
                    <div class="user-name-sidebar">{{ auth()->user()->name }}</div>
                    <div class="user-role-sidebar">{{ auth()->user()->role }}</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-sidebar" style="background: none; border: none; cursor: pointer; width: 100%;">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="header">
            <h1 class="header-title">Devices Management</h1>
        </div>

        <!-- Statistics Cards -->
        <div class="statistics-grid">
            <div class="stat-card">
                <div class="stat-icon stat-icon-blue">
                    <i class="fas fa-desktop"></i>
                </div>
                <div class="stat-label">Total Devices</div>
                <div class="stat-value">{{ $statistics['total'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon stat-icon-green">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-label">Active Devices</div>
                <div class="stat-value">{{ $statistics['active'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon stat-icon-yellow">
                    <i class="fas fa-pause-circle"></i>
                </div>
                <div class="stat-label">Suspended Devices</div>
                <div class="stat-value">{{ $statistics['suspended'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon stat-icon-purple">
                    <i class="fas fa-wifi"></i>
                </div>
                <div class="stat-label">Online Devices</div>
                <div class="stat-value">{{ $statistics['online'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon stat-icon-red">
                    <i class="fas fa-ban"></i>
                </div>
                <div class="stat-label">Revoked Devices</div>
                <div class="stat-value">{{ $statistics['revoked'] }}</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-section">
            <form method="GET" action="{{ route('devices.index') }}">
                <div class="filter-grid">
                    <div class="filter-group">
                        <label class="filter-label">Filter by License</label>
                        <select name="license_id" class="filter-select">
                            <option value="">All Licenses</option>
                            @foreach($licenses as $license)
                                <option value="{{ $license->id }}" {{ request('license_id') == $license->id ? 'selected' : '' }}>
                                    {{ $license->license_key }} - {{ $license->user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Filter by Client</label>
                        <select name="user_id" class="filter-select">
                            <option value="">All Clients</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ request('user_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Filter by Status</label>
                        <select name="status" class="filter-select">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            <option value="revoked" {{ request('status') == 'revoked' ? 'selected' : '' }}>Revoked</option>
                        </select>
                    </div>
                    <div class="filter-group filter-buttons-group">
                        <button type="submit" class="filter-apply-btn">
                            <i class="fas fa-filter"></i> Apply Filters
                        </button>
                        <a href="{{ route('devices.index') }}" class="filter-clear-btn">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Devices Table -->
        <div class="table-container">
            @if($devices->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Device ID</th>
                            <th>Device Name</th>
                            <th>License</th>
                            <th>Client</th>
                            <th>Status</th>
                            <th>Online Status</th>
                            <th>Last Seen</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($devices as $device)
                            <tr>
                                <td>
                                    <code>{{ $device->device_id }}</code>
                                </td>
                                <td>{{ $device->device_name }}</td>
                                <td>
                                    <a href="{{ route('licenses.show', $device->license) }}" style="color: #667eea; text-decoration: none; font-weight: 600;">
                                        {{ $device->license->license_key }}
                                    </a>
                                </td>
                                <td>{{ $device->license->user->name }}</td>
                                <td>
                                    <span class="status-badge {{ $device->status_badge_class }}">
                                        {{ ucfirst($device->status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge {{ $device->online_status_badge_class }}">
                                        {{ $device->online_status }}
                                    </span>
                                </td>
                                <td>
                                    <span class="time-ago">
                                        @if($device->last_seen)
                                            {{ $device->last_seen->diffForHumans() }}
                                        @else
                                            Never
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('devices.show', $device) }}" class="action-btn view-btn">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        @if($device->isActive())
                                            <form method="POST" action="{{ route('devices.suspend', $device) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="action-btn suspend-btn" onclick="return confirm('Are you sure you want to suspend this device?')">
                                                    <i class="fas fa-pause"></i> Suspend
                                                </button>
                                            </form>
                                        @endif
                                        @if($device->isActive() || $device->isSuspended())
                                            <form method="POST" action="{{ route('devices.revoke', $device) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="action-btn revoke-btn" onclick="return confirm('Are you sure you want to revoke this device?')">
                                                    <i class="fas fa-ban"></i> Revoke
                                                </button>
                                            </form>
                                        @endif
                                        @if($device->isSuspended() || $device->isRevoked())
                                            <form method="POST" action="{{ route('devices.activate', $device) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="action-btn activate-btn" onclick="return confirm('Are you sure you want to activate this device?')">
                                                    <i class="fas fa-check"></i> Activate
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <i class="fas fa-desktop empty-state-icon"></i>
                    <p class="empty-state-text">No devices found</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
