@extends('layouts.app')

@section('title', 'Device Details')

@section('content')
@if(session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-700 rounded-lg flex items-center gap-2">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="mb-4 p-4 bg-red-100 border border-red-200 text-red-700 rounded-lg flex items-center gap-2">
        <i class="fas fa-exclamation-circle"></i>
        <span>{{ session('error') }}</span>
    </div>
@endif

<div class="mb-8 flex justify-between items-center">
    <h1 class="text-3xl font-bold text-gray-900">Device Details</h1>
    <a href="{{ route('admin.devices.index') }}" class="text-blue-600 hover:underline">← Back to Devices</a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center gap-3 mb-4 pb-4 border-b border-gray-200">
            <div class="text-blue-600 text-xl"><i class="fas fa-desktop"></i></div>
            <h2 class="text-lg font-semibold text-gray-900">Device Information</h2>
        </div>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-gray-500">Device Name</span>
                <span class="font-medium text-gray-900">{{ $device->device_name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Device ID</span>
                <code class="bg-gray-100 px-2 py-1 rounded text-sm">{{ $device->device_id }}</code>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Status</span>
                <span class="px-2 py-1 text-xs font-medium rounded-full
                    @if($device->status == 'active') bg-green-100 text-green-700
                    @elseif($device->status == 'suspended') bg-yellow-100 text-yellow-700
                    @elseif($device->status == 'revoked') bg-red-100 text-red-700
                    @else bg-gray-100 text-gray-700
                    @endif">
                    {{ ucfirst($device->status) }}
                </span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Online Status</span>
                <span class="px-2 py-1 text-xs font-medium rounded-full
                    @if($device->is_online) bg-green-100 text-green-700
                    @else bg-gray-100 text-gray-700
                    @endif">
                    {{ $device->is_online ? 'Online' : 'Offline' }}
                </span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center gap-3 mb-4 pb-4 border-b border-gray-200">
            <div class="text-green-600 text-xl"><i class="fas fa-cog"></i></div>
            <h2 class="text-lg font-semibold text-gray-900">System Details</h2>
        </div>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-gray-500">Operating System</span>
                <span class="font-medium text-gray-900">{{ ucfirst($device->operating_system) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Last Seen</span>
                <span class="font-medium text-gray-900">{{ $device->last_seen ? $device->last_seen->diffForHumans() : 'Never' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">License</span>
                <span class="font-medium text-gray-900">{{ $device->license ? $device->license->license_key : 'N/A' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Client Name</span>
                <span class="font-medium text-gray-900">{{ $device->client_name ?? 'N/A' }}</span>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions</h2>
    <div class="flex gap-3 flex-wrap">
        @if($device->status === 'suspended')
            <form method="POST" action="{{ route('admin.devices.activate', $device->id) }}" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700" onclick="return confirm('Are you sure you want to activate this device?')">
                    <i class="fas fa-play mr-2"></i> Activate Device
                </button>
            </form>
        @endif
        @if($device->status === 'active')
            <form method="POST" action="{{ route('admin.devices.suspend', $device->id) }}" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600" onclick="return confirm('Are you sure you want to suspend this device?')">
                    <i class="fas fa-pause mr-2"></i> Suspend Device
                </button>
            </form>
        @endif
        <form method="POST" action="{{ route('admin.devices.destroy', $device->id) }}" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700" onclick="return confirm('Are you sure you want to delete this device?')">
                <i class="fas fa-trash mr-2"></i> Delete Device
            </button>
        </form>
        <a href="{{ route('admin.devices.edit', $device->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            <i class="fas fa-edit mr-2"></i> Edit Device
        </a>
    </div>
</div>
@endsection