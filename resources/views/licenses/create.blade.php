<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create License - Broadcaster Platform</title>
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
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            font-size: 15px;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .form-container {
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            max-width: 700px;
        }
        .form-group {
            margin-bottom: 24px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #1a1a2e;
            font-weight: 600;
            font-size: 14px;
        }
        .help-text {
            color: #666;
            font-size: 13px;
            margin-bottom: 8px;
        }
        input[type="text"],
        input[type="email"],
        input[type="number"],
        input[type="date"],
        select {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #f9fafb;
        }
        input:focus,
        select:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }
        button {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }
        .field-error {
            color: #ef4444;
            font-size: 13px;
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
            background: #fef2f2;
            padding: 10px 12px;
            border-radius: 8px;
        }
        .field-error i {
            font-size: 14px;
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
            <h1 class="header-title">Create License</h1>
            <a href="{{ route('licenses.index') }}" class="back-link">← Back to Licenses</a>
        </div>

        <div class="form-container">
            <form method="POST" action="{{ route('licenses.store') }}">
                @csrf

                <div class="form-group">
                    <label for="license_key">License Key</label>
                    <div class="help-text">Leave empty to auto-generate (format: XXX-XXX-XXX)</div>
                    <input type="text" id="license_key" name="license_key" value="{{ old('license_key') }}" placeholder="ABC-123-XYZ">
                    @if ($errors->has('license_key'))
                        <div class="field-error">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>{{ $errors->first('license_key') }}</span>
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="user_id">User</label>
                    <div class="help-text">Select the client user to assign this license</div>
                    <select id="user_id" name="user_id" required>
                        <option value="">Select a user</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('user_id'))
                        <div class="field-error">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>{{ $errors->first('user_id') }}</span>
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        <option value="">Select status</option>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                    @if ($errors->has('status'))
                        <div class="field-error">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>{{ $errors->first('status') }}</span>
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="expires_at">Expiration Date</label>
                    <div class="help-text">When will this license expire?</div>
                    <input type="date" id="expires_at" name="expires_at" value="{{ old('expires_at') }}" required>
                    @if ($errors->has('expires_at'))
                        <div class="field-error">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>{{ $errors->first('expires_at') }}</span>
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="allowed_devices">Allowed Devices</label>
                    <div class="help-text">How many devices can use this license?</div>
                    <input type="number" id="allowed_devices" name="allowed_devices" value="{{ old('allowed_devices', 1) }}" min="1" max="10" required>
                    @if ($errors->has('allowed_devices'))
                        <div class="field-error">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>{{ $errors->first('allowed_devices') }}</span>
                        </div>
                    @endif
                </div>

                <button type="submit">Create License</button>
            </form>
        </div>
    </div>
</body>
</html>