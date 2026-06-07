@extends('layouts.app')

@section('title', 'Devices Management')

@section('content')
<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Devices Management</h1>
    <a href="{{ route('devices.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
        <i class="fas fa-plus"></i>
        Add Device
    </a>
</div>

@if(session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-700 rounded-lg flex items-center gap-2">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div class="text-blue-600 text-2xl mb-2"><i class="fas fa-wifi"></i></div>
        <div class="text-sm text-gray-500">Total Devices</div>
        <div class="text-2xl font-bold text-gray-900">{{ $devicesCount ?? 0 }}</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div class="text-green-600 text-2xl mb-2"><i class="fas fa-check-circle"></i></div>
        <div class="text-sm text-gray-500">Online Devices</div>
        <div class="text-2xl font-bold text-gray-900">{{ $onlineCount ?? 0 }}</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div class="text-yellow-600 text-2xl mb-2"><i class="fas fa-clock"></i></div>
        <div class="text-sm text-gray-500">Offline Devices</div>
        <div class="text-2xl font-bold text-gray-900">{{ $offlineCount ?? 0 }}</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div class="text-red-600 text-2xl mb-2"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="text-sm text-gray-500">Suspended</div>
        <div class="text-2xl font-bold text-gray-900">{{ $suspendedCount ?? 0 }}</div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
    <form method="GET" action="{{ route('devices.index') }}" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 h-10">
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                <option value="revoked" {{ request('status') === 'revoked' ? 'selected' : '' }}>Revoked</option>
            </select>
        </div>
        <div class="flex-1 min-w-[200px]">
            <select name="os" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 h-10">
                <option value="">All OS</option>
                <option value="windows" {{ request('os') === 'windows' ? 'selected' : '' }}>Windows</option>
                <option value="mac" {{ request('os') === 'mac' ? 'selected' : '' }}>Mac</option>
                <option value="linux" {{ request('os') === 'linux' ? 'selected' : '' }}>Linux</option>
            </select>
        </div>
        <div class="flex-1 min-w-[200px]">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Device name or ID..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 h-10">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 h-10">Filter</button>
            <a href="{{ route('devices.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 h-10">Clear</a>
        </div>
    </form>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Device Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Device ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">OS</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Seen</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($devices as $device)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-semibold">
                                {{ substr($device->device_name, 0, 1) }}
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">{{ $device->device_name }}</div>
                                <div class="text-sm text-gray-500">{{ $device->client_name ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4"><code class="bg-gray-100 px-2 py-1 rounded text-sm">{{ $device->device_id }}</code></td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                            @if($device->status == 'active') bg-green-100 text-green-700
                            @elseif($device->status == 'suspended') bg-yellow-100 text-yellow-700
                            @elseif($device->status == 'revoked') bg-red-100 text-red-700
                            @else bg-gray-100 text-gray-700
                            @endif">
                            {{ ucfirst($device->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-700">
                            <i class="{{ $device->operating_system === 'windows' ? 'fab fa-windows' : ($device->operating_system === 'mac' ? 'fab fa-apple' : 'fab fa-linux') }}"></i>
                            {{ ucfirst($device->operating_system) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $device->last_seen ? $device->last_seen->diffForHumans() : 'Never' }}</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <a href="{{ route('devices.show', $device->id) }}" class="px-3 py-1 text-sm text-gray-700 hover:bg-gray-100 rounded">View</a>
                            @if($device->status === 'active')
                                <form method="POST" action="{{ route('devices.suspend', $device->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 text-sm text-yellow-700 hover:bg-yellow-100 rounded" onclick="return confirm('Are you sure you want to suspend this device?')">Suspend</button>
                                </form>
                            @endif
                            @if($device->status === 'suspended')
                                <form method="POST" action="{{ route('devices.activate', $device->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 text-sm text-green-700 hover:bg-green-100 rounded" onclick="return confirm('Are you sure you want to activate this device?')">Activate</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('devices.destroy', $device->id) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 text-sm text-red-700 hover:bg-red-100 rounded" onclick="return confirm('Are you sure you want to delete this device?')">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="text-gray-400">
                            <i class="fas fa-wifi text-4xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No devices found</h3>
                            <p class="text-gray-500 mb-4">Add your first device to get started</p>
                            <a href="{{ route('devices.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                <i class="fas fa-plus"></i> Add Device
                            </a>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($devices->hasPages())
    <div class="mt-6">
        {{ $devices->appends(request()->except('page'))->links() }}
    </div>
@endif
@endsection