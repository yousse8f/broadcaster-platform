@extends('layouts.app')

@section('title', 'Edit Device')

@section('content')
<div class="header">
    <h1 class="header-title">Edit Device</h1>
    <a href="{{ route('devices.index') }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Devices
    </a>
</div>

<div class="form-container">
    <form method="POST" action="{{ route('devices.update', $device->id) }}">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    <strong>Error:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="form-group">
            <label for="name">Device Name</label>
            <input type="text" id="name" name="name" value="{{ old('name', $device->name) }}" required>
            <p class="help-text">A descriptive name for this device</p>
        </div>

        <div class="form-group">
            <label for="device_id">Device ID</label>
            <input type="text" id="device_id" name="device_id" value="{{ old('device_id', $device->device_id) }}" required>
            <p class="help-text">Unique identifier for this device</p>
        </div>

        <div class="form-group">
            <label for="license_id">License</label>
            <select id="license_id" name="license_id" required>
                <option value="">Select a license</option>
                @foreach($licenses as $license)
                    <option value="{{ $license->id }}" {{ old('license_id', $device->license_id) == $license->id ? 'selected' : '' }}>
                        {{ $license->license_key }} - {{ $license->user->name ?? 'Unknown' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="operating_system">Operating System</label>
            <select id="operating_system" name="operating_system" required>
                <option value="">Select OS</option>
                <option value="windows" {{ old('operating_system', $device->operating_system) === 'windows' ? 'selected' : '' }}>Windows</option>
                <option value="mac" {{ old('operating_system', $device->operating_system) === 'mac' ? 'selected' : '' }}>Mac</option>
                <option value="linux" {{ old('operating_system', $device->operating_system) === 'linux' ? 'selected' : '' }}>Linux</option>
            </select>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status" required>
                <option value="online" {{ old('status', $device->status) === 'online' ? 'selected' : '' }}>Online</option>
                <option value="offline" {{ old('status', $device->status) === 'offline' ? 'selected' : '' }}>Offline</option>
                <option value="suspended" {{ old('status', $device->status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
            </select>
        </div>

        <button type="submit">
            <i class="fas fa-save"></i> Update Device
        </button>
    </form>
</div>
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
    .back-link {
        color: #f97316;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .back-link:hover {
        text-decoration: underline;
    }
    .form-container {
        background: white;
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        max-width: 600px;
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
        margin-top: 6px;
    }
    input[type="text"],
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
        border-color: #f97316;
        background: white;
        box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.1);
    }
    button {
        width: 100%;
        padding: 16px;
        background: #f97316;
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(249, 115, 22, 0.4);
    }
    .alert {
        padding: 16px 20px;
        border-radius: 10px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .alert-error {
        background: #ef4444;
        color: white;
    }
    .alert-error i {
        font-size: 20px;
    }
    .alert-error ul {
        margin: 0;
        padding-left: 20px;
    }
</style>
@endpush
