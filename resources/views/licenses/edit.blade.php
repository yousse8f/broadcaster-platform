@extends('layouts.app')

@section('title', 'Edit License')

@push('styles')
<style>
    .form-container {
        background: white;
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        max-width: 700px;
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
        margin-bottom: 8px;
    }
    input[type="text"],
    input[type="email"],
    input[type="number"],
    input[type="date"],
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
        border-color: #667eea;
        background: white;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
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
    }
    button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }
    .field-error {
        color: #ef4444;
        font-size: 13px;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
        background: #fef2f2;
        padding: 10px 12px;
        border-radius: 8px;
    }
    .field-error i {
        font-size: 14px;
    }
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
        color: #667eea;
        text-decoration: none;
        font-weight: 500;
        font-size: 15px;
    }
    .back-link:hover {
        text-decoration: underline;
    }
</style>
@endpush

@section('content')
    <div class="header">
        <h1 class="header-title">Edit License</h1>
        <a href="{{ route('licenses.index') }}" class="back-link">← Back to Licenses</a>
    </div>

    <div class="form-container">
        <form method="POST" action="{{ route('licenses.update', $license) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="license_key">License Key</label>
                <div class="help-text">Current: {{ $license->license_key }} (Leave empty to keep unchanged, or enter new value)</div>
                <input type="text" id="license_key" name="license_key" placeholder="Leave empty to keep unchanged">
                @if ($errors->has('license_key'))
                    <div class="field-error">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>{{ $errors->first('license_key') }}</span>
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="user_id">User</label>
                <div class="help-text">Select the client user to assign this license</div>
                <select id="user_id" name="user_id" required>
                    <option value="">Select a user</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ $license->user_id == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @if ($errors->has('user_id'))
                    <div class="field-error">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>{{ $errors->first('user_id') }}</span>
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="">Select status</option>
                    <option value="active" {{ $license->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="suspended" {{ $license->status == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    <option value="expired" {{ $license->status == 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
                @if ($errors->has('status'))
                    <div class="field-error">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>{{ $errors->first('status') }}</span>
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="expires_at">Expiration Date</label>
                <div class="help-text">When will this license expire?</div>
                <input type="date" id="expires_at" name="expires_at" value="{{ $license->expires_at ? $license->expires_at->format('Y-m-d') : '' }}" required>
                @if ($errors->has('expires_at'))
                    <div class="field-error">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>{{ $errors->first('expires_at') }}</span>
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="allowed_devices">Allowed Devices</label>
                <div class="help-text">How many devices can use this license?</div>
                <input type="number" id="allowed_devices" name="allowed_devices" value="{{ $license->allowed_devices }}" min="1" max="10" required>
                @if ($errors->has('allowed_devices'))
                    <div class="field-error">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>{{ $errors->first('allowed_devices') }}</span>
                    </div>
                @endif
            </div>

            <button type="submit">Update License</button>
        </form>
    </div>
@endsection
