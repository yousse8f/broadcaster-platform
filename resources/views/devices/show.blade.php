<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Device Details - Broadcaster Platform</title>
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
        .back-btn {
            padding: 12px 24px;
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 15px rgba(107, 114, 128, 0.3);
        }
        .back-btn:hover {
            background: linear-gradient(135deg, #4b5563 0%, #374151 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(107, 114, 128, 0.4);
        }
        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 24px;
            margin-bottom: 24px;
        }
        .detail-card {
            background: white;
            padding: 32px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        }
        .detail-card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 2px solid #f3f4f6;
        }
        .detail-card-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        .detail-card-icon-blue {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }
        .detail-card-icon-green {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        .detail-card-icon-purple {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: white;
        }
        .detail-card-icon-orange {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }
        .detail-card-title {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a2e;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-size: 14px;
            color: #6b7280;
            font-weight: 500;
        }
        .detail-value {
            font-size: 14px;
            color: #1a1a2e;
            font-weight: 600;
            text-align: right;
        }
        .detail-value-code {
            font-family: 'Courier New', monospace;
            background: #f3f4f6;
            padding: 4px 8px;
            border-radius: 4px;
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
        .action-buttons {
            display: flex;
            gap: 12px;
            margin-top: 24px;
            flex-wrap: wrap;
        }
        .action-btn {
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .action-btn:hover {
            transform: translateY(-2px);
        }
        .action-btn:active {
            transform: translateY(0);
        }
        .action-btn i {
            font-size: 14px;
        }
        .activate-btn {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }
        .activate-btn:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            box-shadow: 0 6px 25px rgba(16, 185, 129, 0.4);
        }
        .suspend-btn {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
        }
        .suspend-btn:hover {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
            box-shadow: 0 6px 25px rgba(245, 158, 11, 0.4);
        }
        .revoke-btn {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }
        .revoke-btn:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            box-shadow: 0 6px 25px rgba(239, 68, 68, 0.4);
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
            <h1 class="header-title">Device Details</h1>
            <a href="{{ route('devices.index') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                Back to Devices
            </a>
        </div>

        <div class="details-grid">
            <!-- Device Information -->
            <div class="detail-card">
                <div class="detail-card-header">
                    <div class="detail-card-icon detail-card-icon-blue">
                        <i class="fas fa-desktop"></i>
                    </div>
                    <div class="detail-card-title">Device Information</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Device Name</div>
                    <div class="detail-value">{{ $device->device_name }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Device ID</div>
                    <div class="detail-value detail-value-code">{{ $device->device_id }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Status</div>
                    <div class="detail-value">
                        <span class="status-badge {{ $device->status_badge_class }}">
                            {{ ucfirst($device->status) }}
                        </span>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Online Status</div>
                    <div class="detail-value">
                        <span class="status-badge {{ $device->online_status_badge_class }}">
                            {{ $device->online_status }}
                        </span>
                    </div>
                </div>
                @if($device->operating_system)
                <div class="detail-row">
                    <div class="detail-label">Operating System</div>
                    <div class="detail-value">{{ $device->operating_system }}</div>
                </div>
                @endif
                @if($device->ip_address)
                <div class="detail-row">
                    <div class="detail-label">IP Address</div>
                    <div class="detail-value detail-value-code">{{ $device->ip_address }}</div>
                </div>
                @endif
            </div>

            <!-- License Information -->
            <div class="detail-card">
                <div class="detail-card-header">
                    <div class="detail-card-icon detail-card-icon-green">
                        <i class="fas fa-key"></i>
                    </div>
                    <div class="detail-card-title">License Information</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">License Key</div>
                    <div class="detail-value detail-value-code">{{ $device->license->license_key }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">License Status</div>
                    <div class="detail-value">
                        <span class="status-badge status-{{ $device->license->status }}">
                            {{ ucfirst($device->license->status) }}
                        </span>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Allowed Devices</div>
                    <div class="detail-value">{{ $device->license->allowed_devices }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Registered Devices</div>
                    <div class="detail-value">{{ $device->license->active_devices_count }}</div>
                </div>
                @if($device->license->expires_at)
                <div class="detail-row">
                    <div class="detail-label">Expiration Date</div>
                    <div class="detail-value">{{ $device->license->expires_at->format('M d, Y') }}</div>
                </div>
                @endif
            </div>

            <!-- Client Information -->
            <div class="detail-card">
                <div class="detail-card-header">
                    <div class="detail-card-icon detail-card-icon-purple">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="detail-card-title">Client Information</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Client Name</div>
                    <div class="detail-value">{{ $device->license->user->name }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Email</div>
                    <div class="detail-value">{{ $device->license->user->email }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Role</div>
                    <div class="detail-value">{{ ucfirst($device->license->user->role) }}</div>
                </div>
            </div>

            <!-- Activity Information -->
            <div class="detail-card">
                <div class="detail-card-header">
                    <div class="detail-card-icon detail-card-icon-orange">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="detail-card-title">Activity Information</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">First Activated</div>
                    <div class="detail-value">
                        @if($device->first_activated_at)
                            {{ $device->first_activated_at->format('M d, Y - H:i') }}
                        @else
                            Not recorded
                        @endif
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Last Seen</div>
                    <div class="detail-value">
                        @if($device->last_seen)
                            {{ $device->last_seen->format('M d, Y - H:i') }}
                        @else
                            Never
                        @endif
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Registered At</div>
                    <div class="detail-value">{{ $device->created_at->format('M d, Y - H:i') }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Last Updated</div>
                    <div class="detail-value">{{ $device->updated_at->format('M d, Y - H:i') }}</div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="detail-card">
            <div class="detail-card-header">
                <div class="detail-card-icon detail-card-icon-blue">
                    <i class="fas fa-cog"></i>
                </div>
                <div class="detail-card-title">Device Actions</div>
            </div>
            <div class="action-buttons">
                @if($device->isActive())
                    <form method="POST" action="{{ route('devices.suspend', $device) }}">
                        @csrf
                        <button type="submit" class="action-btn suspend-btn" onclick="return confirm('Are you sure you want to suspend this device?')">
                            <i class="fas fa-pause"></i> Suspend Device
                        </button>
                    </form>
                @endif
                
                @if($device->isActive() || $device->isSuspended())
                    <form method="POST" action="{{ route('devices.revoke', $device) }}">
                        @csrf
                        <button type="submit" class="action-btn revoke-btn" onclick="return confirm('Are you sure you want to revoke this device? This action cannot be undone easily.')">
                            <i class="fas fa-ban"></i> Revoke Device
                        </button>
                    </form>
                @endif
                
                @if($device->isSuspended() || $device->isRevoked())
                    <form method="POST" action="{{ route('devices.activate', $device) }}">
                        @csrf
                        <button type="submit" class="action-btn activate-btn" onclick="return confirm('Are you sure you want to activate this device?')">
                            <i class="fas fa-check"></i> Activate Device
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
