@extends('layouts.app')

@section('title', 'Create License')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Create License</h1>
    <a href="{{ route('licenses.index') }}" class="text-blue-600 hover:underline">← Back to Licenses</a>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 max-w-2xl">
    <form method="POST" action="{{ route('licenses.store') }}">
        @csrf

        <div class="mb-4">
            <label for="license_key" class="block text-sm font-medium text-gray-700 mb-2">License Key</label>
            <p class="text-sm text-gray-500 mb-2">Leave empty to auto-generate (format: XXX-XXX-XXX)</p>
            <input type="text" id="license_key" name="license_key" value="{{ old('license_key') }}" placeholder="ABC-123-XYZ"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @if ($errors->has('license_key'))
                <p class="mt-1 text-sm text-red-600">{{ $errors->first('license_key') }}</p>
            @endif
        </div>

        <div class="mb-4">
            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">User</label>
            <p class="text-sm text-gray-500 mb-2">Select the client user to assign this license</p>
            <select id="user_id" name="user_id" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select a user</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
            @if ($errors->has('user_id'))
                <p class="mt-1 text-sm text-red-600">{{ $errors->first('user_id') }}</p>
            @endif
        </div>

        <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select id="status" name="status" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select status</option>
                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>Expired</option>
            </select>
            @if ($errors->has('status'))
                <p class="mt-1 text-sm text-red-600">{{ $errors->first('status') }}</p>
            @endif
        </div>

        <div class="mb-4">
            <label for="expires_at" class="block text-sm font-medium text-gray-700 mb-2">Expiration Date</label>
            <p class="text-sm text-gray-500 mb-2">When will this license expire?</p>
            <input type="date" id="expires_at" name="expires_at" value="{{ old('expires_at') }}" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @if ($errors->has('expires_at'))
                <p class="mt-1 text-sm text-red-600">{{ $errors->first('expires_at') }}</p>
            @endif
        </div>

        <div class="mb-6">
            <label for="allowed_devices" class="block text-sm font-medium text-gray-700 mb-2">Allowed Devices</label>
            <p class="text-sm text-gray-500 mb-2">How many devices can use this license?</p>
            <input type="number" id="allowed_devices" name="allowed_devices" value="{{ old('allowed_devices', 1) }}" min="1" max="10" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @if ($errors->has('allowed_devices'))
                <p class="mt-1 text-sm text-red-600">{{ $errors->first('allowed_devices') }}</p>
            @endif
        </div>

        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            Create License
        </button>
    </form>
</div>
@endsection