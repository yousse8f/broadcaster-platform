@extends('layouts.app')

@section('title', 'Edit Device')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Device</h1>
    <a href="{{ route('admin.devices.index') }}" class="text-blue-600 hover:underline">
        <i class="fas fa-arrow-left"></i> Back to Devices
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 max-w-2xl">
    <form method="POST" action="{{ route('admin.devices.update', $device->id) }}">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-200 text-red-700 rounded-lg">
                <div class="flex items-center gap-2 mb-2">
                    <i class="fas fa-exclamation-circle"></i>
                    <strong>Error:</strong>
                </div>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-4">
            <label for="device_name" class="block text-sm font-medium text-gray-700 mb-2">Device Name</label>
            <input type="text" id="device_name" name="device_name" value="{{ old('device_name', $device->device_name) }}" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <p class="text-sm text-gray-500 mt-1">A descriptive name for this device</p>
        </div>

        <div class="mb-4">
            <label for="device_id" class="block text-sm font-medium text-gray-700 mb-2">Device ID</label>
            <input type="text" id="device_id" name="device_id" value="{{ old('device_id', $device->device_id) }}" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <p class="text-sm text-gray-500 mt-1">Unique identifier for this device</p>
        </div>

        <div class="mb-4">
            <label for="license_id" class="block text-sm font-medium text-gray-700 mb-2">License</label>
            <select id="license_id" name="license_id" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select a license</option>
                @foreach($licenses as $license)
                    <option value="{{ $license->id }}" {{ old('license_id', $device->license_id) == $license->id ? 'selected' : '' }}>
                        {{ $license->license_key }} - {{ $license->user->name ?? 'Unknown' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="operating_system" class="block text-sm font-medium text-gray-700 mb-2">Operating System</label>
            <select id="operating_system" name="operating_system" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select OS</option>
                <option value="windows" {{ old('operating_system', $device->operating_system) === 'windows' ? 'selected' : '' }}>Windows</option>
                <option value="mac" {{ old('operating_system', $device->operating_system) === 'mac' ? 'selected' : '' }}>Mac</option>
                <option value="linux" {{ old('operating_system', $device->operating_system) === 'linux' ? 'selected' : '' }}>Linux</option>
            </select>
        </div>

        <div class="mb-6">
            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select id="status" name="status" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="active" {{ old('status', $device->status) === 'active' ? 'selected' : '' }}>Active</option>
                <option value="suspended" {{ old('status', $device->status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                <option value="revoked" {{ old('status', $device->status) === 'revoked' ? 'selected' : '' }}>Revoked</option>
            </select>
        </div>

        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            <i class="fas fa-save mr-2"></i> Update Device
        </button>
    </form>
</div>
@endsection