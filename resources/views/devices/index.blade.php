@extends('layouts.app')

@section('title', 'Devices Management')

@section('content')
<div class="header">
    <h1 class="header-title">Devices Management</h1>
    <a href="{{ route('devices.create') }}" class="create-btn">
        <i class="fas fa-plus"></i>
        Add Device
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle "></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<div class="statistics-grid">
    <div class="stat-card">
        <div class="stat-icon stat-icon-blue">
            <i class="fas fa-wifi"></i>
        </div>
        <div class="stat-label">Total Devices</div>
        <div class="stat-value">{{ $devicesCount ?? 0 }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon stat-icon-green">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-label">Online Devices</div>
        <div class="stat-value">{{ $onlineCount ?? 0 }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon stat-icon-yellow">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-label">Offline Devices</div>
        <div class="stat-value">{{ $offlineCount ?? 0 }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon stat-icon-red">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="stat-label">Suspended</div>
        <div class="stat-value">{{ $suspendedCount ?? 0 }}</div>
    </div>
</div>

<div class="filter-section">
    <form method="GET" action="{{ route('devices.index') }}" class="filter-grid">
        <div class="filter-group">
            <label class="filter-label">Status</label>
            <select name="status" class="filter-select">
                <option value="">All Status</option>
                <option value="online" {{ request('status') === 'online' ? 'selected' : '' }}>Online</option>
                <option value="offline" {{ request('status') === 'offline' ? 'selected' : '' }}>Offline</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
            </select>
        </div>
        
        <div class="filter-group">
            <label class="filter-label">Operating System</label>
            <select name="os" class="filter-select">
                <option value="">All OS</option>
                <option value="windows" {{ request('os') === 'windows' ? 'selected' : '' }}>Windows</option>
                <option value="mac" {{ request('os') === 'mac' ? 'selected' : '' }}>Mac</option>
                <option value="linux" {{ request('os') === 'linux' ? 'selected' : '' }}>Linux</option>
            </select>
        </div>
        
        <div class="filter-group">
            <label class="filter-label">Search</label>
            <input type="text" name="search" class="filter-select" value="{{ request('search') }}" placeholder="Device name or ID...">
        </div>
        
        <div class="filter-group" style="justify-content: flex-end; flex-direction: row; gap: 12px; align-items: flex-end;">
            <button type="submit" class="filter-apply-btn">
                <i class="fas fa-search "></i> Filter
            </button>
            <a href="{{ route('devices.index') }}" class="filter-clear-btn">
                <i class="fas fa-times "></i> Clear
            </a>
        </div>
    </form>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Device Name</th>
                <th>Device ID</th>
                <th>Status</th>
                <th>OS</th>
                <th>Last Seen</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($devices as $device)
                <tr>
                    <td>
                        <div class="device-name">
                            <div class="device-avatar">{{ substr($device->name, 0, 1) }}</div>
                            <div>
                                <div class="name-text">{{ $device->name }}</div>
                                <div class="device-meta">{{ $device->client_name ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </td>
                    <td><code>{{ $device->device_id }}</code></td>
                    <td>
                        <span class="status-badge status-{{ $device->status }}">
                            {{ $device->status }}
                        </span>
                    </td>
                    <td>
                        <span class="os-badge os-{{ $device->operating_system }}">
                            <i class="{{ $device->operating_system === 'windows' ? 'fab fa-windows' : ($device->operating_system === 'mac' ? 'fab fa-apple' : 'fab fa-linux') }} "></i>
                            {{ ucfirst($device->operating_system) }}
                        </span>
                    </td>
                    <td>{{ $device->last_seen ? $device->last_seen->diffForHumans() : 'Never' }}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('devices.show', $device->id) }}" class="action-btn view-btn">
                                <i class="fas fa-eye "></i> View
                            </a>
                            @if($device->status === 'online')
                                <form method="POST" action="{{ route('devices.suspend', $device->id) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="action-btn suspend-btn" onclick="return confirm('Are you sure you want to suspend this device?')">
                                        <i class="fas fa-pause "></i> Suspend
                                    </button>
                                </form>
                            @endif
                            @if($device->status === 'suspended')
                                <form method="POST" action="{{ route('devices.activate', $device->id) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="action-btn activate-btn" onclick="return confirm('Are you sure you want to activate this device?')">
                                        <i class="fas fa-play "></i> Activate
                                    </button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('devices.destroy', $device->id) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this device?')">
                                    <i class="fas fa-trash "></i> Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 40px;">
                        <div class="empty-state">
                            <i class="fas fa-wifi "></i>
                            <h3>No devices found</h3>
                            <p>Add your first device to get started</p>
                            <a href="{{ route('devices.create') }}" class="create-btn">
                                <i class="fas fa-plus "></i> Add Device
                            </a>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($devices->hasPages())
    <div class="pagination">
        {{ $devices->appends(request()->except('page'))->links() }}
    </div>
@endif
@endsection

@push('styles')
<style>
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
        background: white;
        color: #f97316;
        border: 2px solid #f97316;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 15px rgba(249, 115, 22, 0.15);
    }
    .create-btn:hover {
        background: #fff7ed;
        color: #c2410c;
        border-color: #c2410c;
        transform: translateY(-2px);
        box-shadow: 0 6px 25px rgba(194, 65, 12, 0.25);
    }
    .create-btn i {
        color: #f97316;
    }
    .create-btn:hover i {
        color: #c2410c;
    }
    .create-btn:active {
        transform: translateY(0);
    }
    .create-btn i {
        font-size: 14px;
    }
    .alert {
        padding: 16px 20px;
        border-radius: 10px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .alert-success {
        background: #10b981;
        color: white;
    }
    .alert-success i {
        font-size: 20px;
    }
    .statistics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 24px;
        margin-bottom: 32px;
    }
    .stat-card {
        background: linear-gradient(135deg, #ffffff 0%, #fef9f7 100%);
        padding: 32px 28px;
        border-radius: 24px;
        box-shadow: 0 8px 32px rgba(194, 65, 12, 0.1);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid rgba(249, 115, 22, 0.15);
        position: relative;
        overflow: hidden;
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgba(249, 115, 22, 0.1) 0%, transparent 70%);
        transition: all 0.4s ease;
    }
    .stat-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, #f97316, #c2410c);
        transform: scaleX(0);
        transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .stat-card:hover {
        transform: translateY(-12px) scale(1.02);
        box-shadow: 0 16px 48px rgba(194, 65, 12, 0.2);
        border-color: rgba(249, 115, 22, 0.4);
    }
    .stat-card:hover::before {
        top: -30%;
        right: -30%;
        width: 120%;
        height: 120%;
    }
    .stat-card:hover::after {
        transform: scaleX(1);
    }
    .stat-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        margin-bottom: 20px;
        position: relative;
        z-index: 1;
    }
    .stat-icon-blue {
        color: #f97316;
        filter: drop-shadow(0 4px 8px rgba(249, 115, 22, 0.3));
    }
    .stat-icon-green {
        color: #f97316;
        filter: drop-shadow(0 4px 8px rgba(249, 115, 22, 0.3));
    }
    .stat-icon-yellow {
        color: #f97316;
        filter: drop-shadow(0 4px 8px rgba(249, 115, 22, 0.3));
    }
    .stat-icon-red {
        color: #f97316;
        filter: drop-shadow(0 4px 8px rgba(249, 115, 22, 0.3));
    }
    .stat-label {
        font-size: 14px;
        color: #6b7280;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-bottom: 8px;
        position: relative;
        z-index: 1;
    }
    .stat-value {
        font-size: 36px;
        font-weight: 800;
        color: #1a1a2e;
        position: relative;
        z-index: 1;
        letter-spacing: -1px;
    }
    .filter-section {
        background: white;
        padding: 28px;
        border-radius: 20px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
        margin-bottom: 24px;
        border: 1px solid rgba(249, 115, 22, 0.1);
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
        padding: 14px 18px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 14px;
        color: #374151;
        background: white;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .filter-select:hover {
        border-color: #f97316;
        transform: translateY(-2px);
    }
    .filter-select:focus {
        outline: none;
        border-color: #f97316;
        box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.15);
        transform: translateY(-2px);
    }
    .filter-apply-btn {
        padding: 10px 20px;
        background: white;
        color: #f97316;
        border: 2px solid #f97316;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(249, 115, 22, 0.15);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
        text-decoration: none;
    }
    .filter-apply-btn:hover {
        background: #fff7ed;
        color: #c2410c;
        border-color: #c2410c;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(194, 65, 12, 0.25);
    }
    .filter-apply-btn i {
        color: #f97316;
    }
    .filter-apply-btn:hover i {
        color: #c2410c;
    }
    .filter-clear-btn {
        padding: 10px 20px;
        background: white;
        color: #f97316;
        border: 2px solid #f97316;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
        text-decoration: none;
    }
    .filter-clear-btn:hover {
        background: #fff7ed;
        color: #c2410c;
        border-color: #c2410c;
    }
    .filter-clear-btn i {
        color: #f97316;
    }
    .filter-clear-btn:hover i {
        color: #c2410c;
    }
    .table-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        border: 1px solid rgba(249, 115, 22, 0.1);
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    thead {
        background: #f97316;
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
    .device-name {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .device-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 16px;
    }
    .name-text {
        font-weight: 600;
        color: #1a1a2e;
    }
    .device-meta {
        font-size: 12px;
        color: #6b7280;
    }
    .status-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        display: inline-block;
    }
    .status-online {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    .status-offline {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }
    .status-suspended {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }
    .os-badge {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .os-windows {
        background: #0078d4;
        color: white;
    }
    .os-mac {
        background: #000000;
        color: white;
    }
    .os-linux {
        background: #f39c12;
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
        background: white;
        color: #f97316;
        border: 2px solid #f97316;
    }
    .view-btn:hover {
        background: #fff7ed;
        color: #c2410c;
        border-color: #c2410c;
        box-shadow: 0 4px 12px rgba(194, 65, 12, 0.25);
    }
    .suspend-btn {
        background: white;
        color: #f97316;
        border: 2px solid #f97316;
    }
    .suspend-btn:hover {
        background: #fff7ed;
        color: #c2410c;
        border-color: #c2410c;
        box-shadow: 0 4px 12px rgba(194, 65, 12, 0.25);
    }
    .activate-btn {
        background: white;
        color: #f97316;
        border: 2px solid #f97316;
    }
    .activate-btn:hover {
        background: #fff7ed;
        color: #c2410c;
        border-color: #c2410c;
        box-shadow: 0 4px 12px rgba(194, 65, 12, 0.25);
    }
    .delete-btn {
        background: white;
        color: #f97316;
        border: 2px solid #f97316;
    }
    .delete-btn:hover {
        background: #fff7ed;
        color: #c2410c;
        border-color: #c2410c;
        box-shadow: 0 4px 12px rgba(194, 65, 12, 0.25);
    }
    .empty-state {
        text-align: center;
        padding: 40px;
        color: #6b7280;
    }
    .empty-state i {
        font-size: 48px;
        margin-bottom: 16px;
        color: #d1d5db;
    }
    .empty-state h3 {
        font-size: 18px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }
    .empty-state p {
        margin-bottom: 16px;
    }
    .pagination {
        margin-top: 24px;
        display: flex;
        justify-content: center;
    }
    .pagination a, .pagination span {
        padding: 8px 16px;
        margin: 0 4px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        text-decoration: none;
        color: #374151;
        transition: all 0.2s ease;
    }
    .pagination a:hover {
        background: #f3f4f6;
    }
    .pagination .active {
        background: #f97316;
        color: white;
        border-color: #f97316;
    }
</style>
@endpush
