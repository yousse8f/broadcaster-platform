@extends('layouts.app')

@section('title', 'My Devices')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">My Devices</h1>
    <p class="text-gray-600">Manage your registered devices</p>
</div>

<!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="text-gray-500 text-sm mb-1">Total Devices</div>
        <div class="text-3xl font-bold text-gray-900">{{ $devicesCount }}</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="text-gray-500 text-sm mb-1">Online</div>
        <div class="text-3xl font-bold text-green-600">{{ $onlineCount }}</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="text-gray-500 text-sm mb-1">Offline</div>
        <div class="text-3xl font-bold text-gray-400">{{ $offlineCount }}</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="text-gray-500 text-sm mb-1">Active</div>
        <div class="text-3xl font-bold text-blue-600">{{ $activeCount }}</div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
    <form method="GET" action="{{ route('client.devices') }}" class="flex gap-4">
        <div class="flex-1">
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                <option value="revoked" {{ request('status') == 'revoked' ? 'selected' : '' }}>Revoked</option>
            </select>
        </div>
        <div class="flex-1">
            <select name="os" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">All Operating Systems</option>
                <option value="windows" {{ request('os') == 'windows' ? 'selected' : '' }}>Windows</option>
                <option value="mac" {{ request('os') == 'mac' ? 'selected' : '' }}>Mac</option>
                <option value="linux" {{ request('os') == 'linux' ? 'selected' : '' }}>Linux</option>
            </select>
        </div>
        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Filter</button>
        <a href="{{ route('client.devices') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Clear</a>
    </form>
</div>

<!-- Devices List -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    @if ($devices->count() > 0)
        <div class="divide-y divide-gray-200">
            @foreach ($devices as $device)
                <div class="p-6 hover:bg-gray-50">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $device->device_name }}</h3>
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    @if($device->status == 'active') bg-green-100 text-green-700
                                    @elseif($device->status == 'suspended') bg-yellow-100 text-yellow-700
                                    @elseif($device->status == 'revoked') bg-red-100 text-red-700
                                    @else bg-gray-100 text-gray-700
                                    @endif">
                                    {{ ucfirst($device->status) }}
                                </span>
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    @if($device->isOnline()) bg-green-100 text-green-700
                                    @else bg-gray-100 text-gray-700
                                    @endif">
                                    {{ $device->isOnline() ? 'Online' : 'Offline' }}
                                </span>
                            </div>
                            <div class="text-sm text-gray-500 mb-2">
                                <div><strong>Device ID:</strong> {{ $device->device_id }}</div>
                                <div><strong>Operating System:</strong> {{ ucfirst($device->operating_system ?? 'Unknown') }}</div>
                                <div><strong>License:</strong> {{ $device->license->license_key }}</div>
                                <div><strong>Last Seen:</strong> {{ $device->last_seen ? $device->last_seen->diffForHumans() : 'Never' }}</div>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('client.devices.show', $device->id) }}" class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors">
                                Details
                            </a>
                            @if($device->status == 'active')
                                <form action="{{ route('client.devices.revoke', $device->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this device? This will revoke its access to the license.');">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition-colors">
                                        Remove
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="p-6 border-t border-gray-200">
            {{ $devices->links() }}
        </div>
    @else
        <div class="text-center py-12 text-gray-500">
            <div class="text-6xl mb-4">📱</div>
            <p class="text-lg mb-2">No devices registered yet</p>
            <p class="text-sm">Devices will appear here when you activate your license on different machines.</p>
        </div>
    @endif
</div>
@endsection
