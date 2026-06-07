@extends('layouts.app')

@section('title', 'License Details')

@push('styles')
<style>
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
        color: #f97316;
        text-decoration: none;
        font-weight: 600;
        font-size: 15px;
        padding: 8px 16px;
        background: rgba(249, 115, 22, 0.1);
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    .back-link:hover {
        background: rgba(249, 115, 22, 0.2);
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
@endpush

@section('content')
    <div class="header">
        <h1 class="header-title">License Details</h1>
        <a href="{{ route('licenses.index') }}" class="back-link">
            <i class="fas fa-arrow-left "></i>
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
                <a href="{{ route('devices.index', ['license_id' => $license->id]) }}" style="color: #f97316; text-decoration: none;">
                    {{ $license->active_devices_count }} / {{ $license->allowed_devices }} devices registered
                </a>
                @if($license->online_devices_count > 0)
                    <span style="color: #f97316; font-size: 14px; margin-left: 12px;">
                        <i class="fas fa-circle "></i> {{ $license->online_devices_count }} online
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
                <i class="fas fa-laptop "></i>
                <p>No devices registered for this license yet.</p>
            </div>
        @endif
    </div>
@endsection
