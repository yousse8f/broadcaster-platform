@extends('layouts.app')

@section('title', 'Device Details')

@section('content')
<div class="mb-8">
    <div class="flex items-center gap-4 mb-4">
        <a href="{{ route('client.devices') }}" class="text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Device Details</h1>
    </div>
</div>

<!-- Device Information -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Device Information</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div>
            <div class="text-sm text-gray-500 mb-1">Device Name</div>
            <div class="text-gray-900 font-medium">{{ $device->device_name }}</div>
        </div>
        <div>
            <div class="text-sm text-gray-500 mb-1">Device ID</div>
            <div class="font-mono text-gray-900 text-sm">{{ $device->device_id }}</div>
        </div>
        <div>
            <div class="text-sm text-gray-500 mb-1">Operating System</div>
            <div class="text-gray-900">{{ ucfirst($device->operating_system) }}</div>
        </div>
        <div>
            <div class="text-sm text-gray-500 mb-1">Status</div>
            <span class="px-2 py-1 text-xs font-medium rounded-full
                @if($device->status == 'active') bg-green-100 text-green-700
                @elseif($device->status == 'suspended') bg-yellow-100 text-yellow-700
                @elseif($device->status == 'revoked') bg-red-100 text-red-700
                @else bg-gray-100 text-gray-700
                @endif">
                {{ ucfirst($device->status) }}
            </span>
        </div>
        <div>
            <div class="text-sm text-gray-500 mb-1">Online Status</div>
            <span class="px-2 py-1 text-xs font-medium rounded-full
                @if($device->isOnline()) bg-green-100 text-green-700
                @else bg-gray-100 text-gray-700
                @endif">
                {{ $device->isOnline() ? 'Online' : 'Offline' }}
            </span>
        </div>
    </div>
</div>

<!-- Connection Information -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Connection Information</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div>
            <div class="text-sm text-gray-500 mb-1">First Activated</div>
            <div class="text-gray-900">{{ $device->first_activated_at ? $device->first_activated_at->format('M d, Y H:i') : 'Unknown' }}</div>
        </div>
        <div>
            <div class="text-sm text-gray-500 mb-1">Last Seen</div>
            <div class="text-gray-900">{{ $device->last_seen ? $device->last_seen->format('M d, Y H:i') : 'Never' }}</div>
        </div>
        <div>
            <div class="text-sm text-gray-500 mb-1">Current State</div>
            <div class="text-gray-900">
                @if($device->isOnline())
                    <span class="text-green-600 font-medium">Connected</span>
                @else
                    <span class="text-gray-500">{{ $device->last_seen ? $device->last_seen->diffForHumans() : 'Never connected' }}</span>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- License Information -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">License Information</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div>
            <div class="text-sm text-gray-500 mb-1">License Key</div>
            <div class="font-mono text-gray-900">{{ $device->license->license_key }}</div>
        </div>
        <div>
            <div class="text-sm text-gray-500 mb-1">License Status</div>
            <span class="px-2 py-1 text-xs font-medium rounded-full
                @if($device->license->status == 'active') bg-green-100 text-green-700
                @elseif($device->license->status == 'suspended') bg-orange-100 text-orange-700
                @elseif($device->license->status == 'expired') bg-red-100 text-red-700
                @else bg-gray-100 text-gray-700
                @endif">
                {{ ucfirst($device->license->status) }}
            </span>
        </div>
        <div>
            <div class="text-sm text-gray-500 mb-1">License Expiration</div>
            <div class="text-gray-900">{{ $device->license->expires_at ? $device->license->expires_at->format('M d, Y') : 'Never' }}</div>
        </div>
        <div>
            <div class="text-sm text-gray-500 mb-1">Allowed Devices</div>
            <div class="text-gray-900">{{ $device->license->allowed_devices }}</div>
        </div>
        <div>
            <div class="text-sm text-gray-500 mb-1">Used Devices</div>
            <div class="text-gray-900">{{ $device->license->devices()->where('status', 'active')->count() }}</div>
        </div>
    </div>
</div>

<!-- Actions -->
@if($device->status == 'active')
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Device Actions</h2>
        <form action="{{ route('client.devices.revoke', $device->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this device? This will revoke its access to the license.');">
            @csrf
            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                <i class="fas fa-ban mr-2"></i>
                Remove Device
            </button>
        </form>
        <p class="text-sm text-gray-500 mt-2">
            <i class="fas fa-info-circle mr-1"></i>
            This will revoke the device's access to the license. Use this when you format your PC or get a new device.
        </p>
    </div>
@endif

<!-- Activity Logs -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Activity Logs</h2>
    @if($activityLogs && $activityLogs->count() > 0)
        <div class="space-y-3">
            @foreach($activityLogs as $log)
                <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-lg">
                    <div class="text-gray-400 mt-1">
                        @if($log->action == 'device_created')
                            <i class="fas fa-plus-circle text-green-500"></i>
                        @elseif($log->action == 'device_updated')
                            <i class="fas fa-edit text-blue-500"></i>
                        @elseif($log->action == 'device_revoked')
                            <i class="fas fa-ban text-red-500"></i>
                        @else
                            <i class="fas fa-info-circle text-gray-500"></i>
                        @endif
                    </div>
                    <div class="flex-1">
                        <div class="font-medium text-gray-900">{{ $log->description }}</div>
                        <div class="text-sm text-gray-500">{{ $log->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-history text-4xl mb-3"></i>
            <p>No activity logs found for this device.</p>
        </div>
    @endif
</div>
@endsection