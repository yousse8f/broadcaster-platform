<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Licenses Management - Broadcaster Platform</title>
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
        .create-btn {
            padding: 12px 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        .create-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(102, 126, 234, 0.5);
        }
        .create-btn:active {
            transform: translateY(0);
        }
        .create-btn i {
            font-size: 14px;
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
        .edit-btn {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }
        .edit-btn:hover {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
        }
        .delete-btn {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }
        .delete-btn:hover {
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
            padding: 80px 20px;
            color: #999;
        }
        .empty-state i {
            font-size: 64px;
            color: #d1d5db;
            margin-bottom: 20px;
        }
        .empty-state h3 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #666;
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
            <h1 class="header-title">Licenses Management</h1>
            <a href="{{ route('licenses.create') }}" class="create-btn">
                <i class="fas fa-plus"></i> Create License
            </a>
        </div>

        @session('success')
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endsession

        @session('error')
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endsession

        <div class="table-container">
            @if ($licenses->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>License Key</th>
                            <th>User</th>
                            <th>Status</th>
                            <th>Expires At</th>
                            <th>Devices</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($licenses as $license)
                            <tr>
                                <td><strong>{{ $license->license_key }}</strong></td>
                                <td>{{ $license->user ? $license->user->name : 'N/A' }}</td>
                                <td>
                                    <span class="status-badge status-{{ $license->status }}">
                                        {{ ucfirst($license->status) }}
                                    </span>
                                </td>
                                <td>{{ $license->expires_at ? $license->expires_at->format('Y-m-d') : 'N/A' }}</td>
                                <td>{{ $license->devices->count() }} / {{ $license->allowed_devices }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('licenses.show', $license) }}" class="action-btn view-btn">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="{{ route('licenses.edit', $license) }}" class="action-btn edit-btn">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form method="POST" action="{{ route('licenses.destroy', $license) }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to suspend this license?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <i class="fas fa-key"></i>
                    <h3>No licenses found</h3>
                    <p>Click "Create License" to add your first license.</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>