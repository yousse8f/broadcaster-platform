@extends('layouts.app')

@section('title', 'License Details')

@section('content')
<div class="mb-8">
    <div class="flex items-center gap-4 mb-4">
        <a href="{{ route('client.licenses') }}" class="text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-3xl font-bold text-gray-900">License Details</h1>
    </div>
</div>

<!-- License Information -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">License Information</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div>
            <div class="text-sm text-gray-500 mb-1">License Key</div>
            <div class="flex items-center gap-2">
                <code class="bg-gray-100 px-2 py-1 rounded text-sm">{{ $license->license_key }}</code>
                <button onclick="copyToClipboard('{{ $license->license_key }}')" class="text-gray-400 hover:text-gray-600 p-1" title="Copy to clipboard">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
        </div>
        <div>
            <div class="text-sm text-gray-500 mb-1">Status</div>
            <span class="px-2 py-1 text-xs font-medium rounded-full
                @if($license->status == 'active') bg-green-100 text-green-700
                @elseif($license->status == 'suspended') bg-orange-100 text-orange-700
                @elseif($license->status == 'expired') bg-red-100 text-red-700
                @else bg-gray-100 text-gray-700
                @endif">
                {{ ucfirst($license->status) }}
            </span>
        </div>
        <div>
            <div class="text-sm text-gray-500 mb-1">Expiration</div>
            <div class="text-gray-900">{{ $license->expires_at ? $license->expires_at->format('M d, Y') : 'Never' }}</div>
        </div>
        <div>
            <div class="text-sm text-gray-500 mb-1">Created At</div>
            <div class="text-gray-900">{{ $license->created_at->format('M d, Y') }}</div>
        </div>
        <div>
            <div class="text-sm text-gray-500 mb-1">Days Remaining</div>
            @if($license->expires_at)
                @if($license->expires_at->isPast())
                    <span class="text-red-600 font-medium">Expired</span>
                @else
                    <span class="text-gray-900">{{ $license->expires_at->diffInDays(now()) }} Days</span>
                @endif
            @else
                <span class="text-gray-500">Never</span>
            @endif
        </div>
        @if($license->expires_at && $license->expires_at->diffInDays(now()) <= 30)
            <div>
                <div class="text-sm text-gray-500 mb-1">Warning</div>
                @if($license->expires_at->diffInDays(now()) <= 7)
                    <span class="text-red-600">🚨 Expires in {{ $license->expires_at->diffInDays(now()) }} days</span>
                @else
                    <span class="text-yellow-600">⚠️ Expires in {{ $license->expires_at->diffInDays(now()) }} days</span>
                @endif
            </div>
        @endif
    </div>
</div>

<!-- Usage Information -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Usage Information</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <div class="text-sm text-gray-500 mb-1">Allowed Devices</div>
            <div class="text-2xl font-bold text-gray-900">{{ $license->allowed_devices }}</div>
        </div>
        <div>
            <div class="text-sm text-gray-500 mb-1">Used Devices</div>
            <div class="text-2xl font-bold text-gray-900">{{ $license->devices()->where('status', 'active')->count() }}</div>
        </div>
        <div>
            <div class="text-sm text-gray-500 mb-1">Available Slots</div>
            <div class="text-2xl font-bold text-green-600">{{ $license->allowed_devices - $license->devices()->where('status', 'active')->count() }}</div>
        </div>
    </div>
</div>

<!-- Devices Linked -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Devices Linked</h2>
    @if($license->devices->count() > 0)
        <div class="space-y-3">
            @foreach($license->devices as $device)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <div class="font-medium text-gray-900">{{ $device->device_name }}</div>
                        <div class="text-sm text-gray-500">
                            <span>{{ ucfirst($device->operating_system) }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $device->isOnline() ? 'Online' : 'Offline' }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $device->last_seen ? $device->last_seen->diffForHumans() : 'Never' }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                            @if($device->status == 'active') bg-green-100 text-green-700
                            @elseif($device->status == 'suspended') bg-yellow-100 text-yellow-700
                            @elseif($device->status == 'revoked') bg-red-100 text-red-700
                            @else bg-gray-100 text-gray-700
                            @endif">
                            {{ ucfirst($device->status) }}
                        </span>
                        <a href="{{ route('client.devices.show', $device->id) }}" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-laptop text-4xl mb-3"></i>
            <p>No devices linked to this license yet.</p>
        </div>
    @endif
</div>

<!-- Activity Logs -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Activity Logs</h2>
    @if($activityLogs && $activityLogs->count() > 0)
        <div class="space-y-3">
            @foreach($activityLogs as $log)
                <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-lg">
                    <div class="text-gray-400 mt-1">
                        @if($log->action == 'license_created')
                            <i class="fas fa-plus-circle text-green-500"></i>
                        @elseif($log->action == 'license_updated')
                            <i class="fas fa-edit text-blue-500"></i>
                        @elseif($log->action == 'license_renewed')
                            <i class="fas fa-sync text-purple-500"></i>
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
            <p>No activity logs found for this license.</p>
        </div>
    @endif
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('License key copied to clipboard!');
    }, function(err) {
        console.error('Could not copy text: ', err);
    });
}
</script>
@endsection