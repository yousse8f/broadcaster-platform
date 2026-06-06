<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>License Details - Broadcaster Platform</title>
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
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            padding: 8px 16px;
            background: rgba(102, 126, 234, 0.1);
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .back-link:hover {
            background: rgba(102, 126, 234, 0.2);
            transform: translateX(-4px);
        }
        .details-container {
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            margin-bottom: 32px;
        }
        .detail-item {
            margin-bottom: 28px;
            padding-bottom: 28px;
            border-bottom: 2px solid #e5e7eb;
        }
        .detail-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .detail-label {
            color: #666;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .detail-value {
            color: #1a1a2e;
            font-size: 20px;
            font-weight: 600;
        }
        .status-badge {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .status-active {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        .status-suspended {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
        }
        .status-expired {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }
        .status-inactive {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
            color: white;
        }
        .action-buttons {
            display: flex;
            gap: 12px;
            margin-top: 32px;
            flex-wrap: wrap;
        }
        .action-btn {
            padding: 14px 28px;
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
        .edit-btn {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
        }
        .edit-btn:hover {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
            box-shadow: 0 6px 25px rgba(245, 158, 11, 0.4);
        }
        .delete-btn {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }
        .delete-btn:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            box-shadow: 0 6px 25px rgba(239, 68, 68, 0.4);
        }
        .devices-section {
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        }
        .section-title {
            font-size: 24px;
            color: #1a1a2e;
            margin-bottom: 24px;
            font-weight: 700;
        }
        .empty-devices {
            color: #999;
            font-size: 14px;
            text-align: center;
            padding: 32px;
            background: #f9fafb;
            border-radius: 10px;
        }
        .device-item {
            padding: 20px;
            background: linear-gradient(135deg, #f8f9ff 0%, #f3f4f6 100%);
            border-radius: 12px;
            margin-bottom: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: transform 0.3s ease;
        }
        .device-item:hover {
            transform: translateX(4px);
        }
        .device-name {
            font-weight: 700;
            color: #1a1a2e;
            font-size: 16px;
            margin-bottom: 4px;
        }
        .device-id {
            color: #666;
            font-size: 14px;
        }
        .device-last-seen {
            color: #999;
            font-size: 13px;
            text-align: right;
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
            <a href="{{ route('dashboard') }}" class="menu-item">
                <i class="fas fa-home"></i>
                <span class="menu-item-text">Dashboard</span>
            </a>
            
            @if (auth()->user()->role === 'admin')
                <a href="{{ route('licenses.index') }}" class="menu-item active">
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
            <h1 class="header-title">License Details</h1>
            <a href="{{ route('licenses.index') }}" class="back-link">
                <i class="fas fa-arrow-left"></i>
                Back to Licenses
            </a>
        </div>

        <div class="details-container">
            <div class="detail-item">
                <div class="detail-label">License Key</div>
                <div class="detail-value">{{ $license->license_key }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Owner</div>
                <div class="detail-value">
                    {{ $license->user ? $license->user->name : 'N/A' }}
                    @if ($license->user)
                        <span style="color: #666; font-size: 16px; margin-left: 8px;">({{ $license->user->email }})</span>
                    @endif
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Status</div>
                <div class="detail-value">
                    <span class="status-badge status-{{ $license->status }}">
                        {{ ucfirst($license->status) }}
                    </span>
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Expiration Date</div>
                <div class="detail-value">
                    {{ $license->expires_at ? $license->expires_at->format('Y-m-d') : 'No expiration' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Device Usage</div>
                <div class="detail-value">
                    <a href="{{ route('devices.index', ['license_id' => $license->id]) }}" style="color: #667eea; text-decoration: none;">
                        {{ $license->active_devices_count }} / {{ $license->allowed_devices }} devices registered
                    </a>
                    @if($license->online_devices_count > 0)
                        <span style="color: #10b981; font-size: 14px; margin-left: 12px;">
                            <i class="fas fa-circle" style="font-size: 8px;"></i> {{ $license->online_devices_count }} online
                        </span>
                    @endif
                </div>
            </div>

            <div class="action-buttons">
                <a href="{{ route('licenses.edit', $license) }}" class="action-btn edit-btn">Edit License</a>
                <form method="POST" action="{{ route('licenses.destroy', $license) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to suspend this license?')">Suspend License</button>
                </form>
            </div>
        </div>

        <div class="devices-section">
            <h2 class="section-title">Registered Devices</h2>
            @if ($license->devices->count() > 0)
                @foreach ($license->devices as $device)
                    <a href="{{ route('devices.show', $device) }}" class="device-item" style="text-decoration: none;">
                        <div>
                            <div class="device-name">{{ $device->device_name }}</div>
                            <div class="device-id">ID: {{ $device->device_id }}</div>
                        </div>
                        <div>
                            <span class="status-badge status-{{ $device->status }}" style="margin-right: 12px;">
                                {{ ucfirst($device->status) }}
                            </span>
                            <span class="device-last-seen">
                                {{ $device->isOnline() ? 'Online' : 'Offline' }} • Last seen: {{ $device->last_seen ? $device->last_seen->diffForHumans() : 'Never' }}
                            </span>
                        </div>
                    </a>
                @endforeach
            @else
                <div class="empty-devices">
                    <i class="fas fa-laptop" style="font-size: 32px; margin-bottom: 12px; color: #d1d5db;"></i>
                    <p>No devices registered for this license yet.</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>