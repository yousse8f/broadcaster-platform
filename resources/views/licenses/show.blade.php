@extends('layouts.app')

@section('title', 'License Details')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">License Details</h1>
    <a href="{{ route('admin.licenses.index') }}" class="text-blue-600 hover:underline">← Back to Licenses</a>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
    <div class="mb-4 pb-4 border-b border-gray-200">
        <div class="text-sm text-gray-500 mb-1">License Key</div>
        <div class="text-xl font-semibold text-gray-900">{{ $license->license_key }}</div>
    </div>

    <div class="mb-4 pb-4 border-b border-gray-200">
        <div class="text-sm text-gray-500 mb-1">Owner</div>
        <div class="text-xl font-semibold text-gray-900">
            {{ $license->user ? $license->user->name : 'N/A' }}
            @if ($license->user)
                <span class="text-gray-500 text-base ml-2">({{ $license->user->email }})</span>
            @endif
        </div>
    </div>

    <div class="mb-4 pb-4 border-b border-gray-200">
        <div class="text-sm text-gray-500 mb-1">Status</div>
        <div>
            <span class="px-3 py-1 text-sm font-medium rounded-full
                @if($license->status == 'active') bg-green-100 text-green-700
                @elseif($license->status == 'suspended') bg-red-100 text-red-700
                @elseif($license->status == 'expired') bg-yellow-100 text-yellow-700
                @else bg-gray-100 text-gray-700
                @endif">
                {{ ucfirst($license->status) }}
            </span>
        </div>
    </div>

    <div class="mb-4 pb-4 border-b border-gray-200">
        <div class="text-sm text-gray-500 mb-1">Expiration Date</div>
        <div class="text-xl font-semibold text-gray-900">
            {{ $license->expires_at ? $license->expires_at->format('Y-m-d') : 'No expiration' }}
        </div>
    </div>

    <div class="mb-6">
        <div class="text-sm text-gray-500 mb-1">Device Usage</div>
        <div class="text-xl font-semibold text-gray-900">
            <a href="{{ route('admin.devices.index', ['license_id' => $license->id]) }}" class="text-blue-600 hover:underline">
                {{ $license->active_devices_count }} / {{ $license->allowed_devices }} devices registered
            </a>
            @if($license->online_devices_count > 0)
                <span class="text-blue-600 text-sm ml-2">
                    <i class="fas fa-circle"></i> {{ $license->online_devices_count }} online
                </span>
            @endif
        </div>
    </div>

    <div class="flex gap-3">
        <a href="{{ route('admin.licenses.edit', $license) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">Edit License</a>
        <div class="relative inline-block">
            <button onclick="document.getElementById('renewDropdown').classList.toggle('hidden')" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Renew License</button>
            <div id="renewDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
                <form method="POST" action="{{ route('admin.licenses.renew', $license) }}" class="p-2">
                    @csrf
                    <input type="hidden" name="days" value="30">
                    <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">30 Days</button>
                </form>
                <form method="POST" action="{{ route('admin.licenses.renew', $license) }}" class="p-2">
                    @csrf
                    <input type="hidden" name="days" value="90">
                    <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">90 Days</button>
                </form>
                <form method="POST" action="{{ route('admin.licenses.renew', $license) }}" class="p-2">
                    @csrf
                    <input type="hidden" name="days" value="180">
                    <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">180 Days</button>
                </form>
                <form method="POST" action="{{ route('admin.licenses.renew', $license) }}" class="p-2">
                    @csrf
                    <input type="hidden" name="days" value="365">
                    <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">365 Days</button>
                </form>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.licenses.destroy', $license) }}" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700" onclick="return confirm('Are you sure you want to suspend this license?')">Suspend License</button>
        </form>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">Registered Devices</h2>
    @if ($license->devices->count() > 0)
        @foreach ($license->devices as $device)
            <a href="{{ route('admin.devices.show', $device) }}" class="block p-4 bg-gray-50 rounded-lg mb-3 hover:bg-gray-100">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="font-semibold text-gray-900 mb-1">{{ $device->device_name }}</div>
                        <div class="text-sm text-gray-500">ID: {{ $device->device_id }}</div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                            @if($device->status == 'online') bg-green-100 text-green-700
                            @elseif($device->status == 'offline') bg-gray-100 text-gray-700
                            @else bg-red-100 text-red-700
                            @endif">
                            {{ ucfirst($device->status) }}
                        </span>
                        <span class="text-sm text-gray-400">{{ $device->last_seen ? $device->last_seen->diffForHumans() : 'Never' }}</span>
                    </div>
                </div>
            </a>
        @endforeach
    @else
        <div class="text-center py-8 text-gray-500 bg-gray-50 rounded-lg">
            No devices registered for this license
        </div>
    @endif
</div>
@endsection