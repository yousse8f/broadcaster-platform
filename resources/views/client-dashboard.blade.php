@extends('layouts.app')

@section('title', 'Client Dashboard')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">My Dashboard</h1>
    <p class="text-gray-600">Welcome back, {{ auth()->user()->name }}!</p>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="text-blue-600 text-2xl">
                <i class="fas fa-key"></i>
            </div>
        </div>
        <div class="text-3xl font-bold text-gray-900 mb-1">{{ $activeLicensesCount ?? 0 }}</div>
        <div class="text-sm text-gray-500">Active Licenses</div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="text-purple-600 text-2xl">
                <i class="fas fa-laptop"></i>
            </div>
        </div>
        <div class="text-3xl font-bold text-gray-900 mb-1">{{ $totalDevicesCount ?? 0 }}</div>
        <div class="text-sm text-gray-500">Total Devices</div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="text-green-600 text-2xl">
                <i class="fas fa-wifi"></i>
            </div>
        </div>
        <div class="text-3xl font-bold text-gray-900 mb-1">{{ $onlineDevicesCount ?? 0 }}</div>
        <div class="text-sm text-gray-500">Online Devices</div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="text-yellow-600 text-2xl">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
        </div>
        <div class="text-3xl font-bold text-gray-900 mb-1">{{ $expiringSoonCount ?? 0 }}</div>
        <div class="text-sm text-gray-500">Expiring Soon</div>
    </div>
</div>

<!-- Expiration Warnings -->
@if($expiringLicenses && $expiringLicenses->count() > 0)
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8">
        <h3 class="text-lg font-semibold text-yellow-900 mb-4">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            License Expiration Warnings
        </h3>
        <div class="space-y-3">
            @foreach($expiringLicenses as $license)
                <div class="flex items-center justify-between bg-white rounded-lg p-4 border border-yellow-200">
                    <div>
                        <div class="font-medium text-gray-900">{{ $license->license_key }}</div>
                        <div class="text-sm text-gray-600">
                            Expires in {{ $license->expires_at->diffInDays(now()) }} days
                            ({{ $license->expires_at->format('M d, Y') }})
                        </div>
                    </div>
                    @if($license->expires_at->diffInDays(now()) <= 7)
                        <span class="px-3 py-1 bg-red-100 text-red-700 text-sm font-medium rounded-full">
                            🚨 {{ $license->expires_at->diffInDays(now()) }} days left
                        </span>
                    @else
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-sm font-medium rounded-full">
                            ⚠️ {{ $license->expires_at->diffInDays(now()) }} days left
                        </span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endif

<!-- License Summary -->
@if($summaryLicense)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">License Summary</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <div class="text-sm text-gray-500 mb-1">License Key</div>
                <div class="font-mono text-gray-900">{{ $summaryLicense->license_key }}</div>
            </div>
            <div>
                <div class="text-sm text-gray-500 mb-1">Status</div>
                <span class="px-2 py-1 text-xs font-medium rounded-full
                    @if($summaryLicense->status == 'active') bg-green-100 text-green-700
                    @elseif($summaryLicense->status == 'suspended') bg-red-100 text-red-700
                    @elseif($summaryLicense->status == 'expired') bg-yellow-100 text-yellow-700
                    @else bg-gray-100 text-gray-700
                    @endif">
                    {{ ucfirst($summaryLicense->status) }}
                </span>
            </div>
            <div>
                <div class="text-sm text-gray-500 mb-1">Expiration</div>
                <div class="text-gray-900">{{ $summaryLicense->expires_at ? $summaryLicense->expires_at->format('M d, Y') : 'Never' }}</div>
            </div>
            <div>
                <div class="text-sm text-gray-500 mb-1">Devices</div>
                <div class="text-gray-900">{{ $summaryLicense->active_devices_count }} / {{ $summaryLicense->allowed_devices }}</div>
            </div>
        </div>
    </div>
@endif

<!-- Quick Actions -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ route('client.licenses') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-colors">
            <i class="fas fa-key text-blue-600 text-xl mr-3"></i>
            <div>
                <div class="font-medium text-gray-900">View Licenses</div>
                <div class="text-sm text-gray-500">Manage your licenses</div>
            </div>
        </a>
        <a href="{{ route('client.devices') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-colors">
            <i class="fas fa-laptop text-blue-600 text-xl mr-3"></i>
            <div>
                <div class="font-medium text-gray-900">View Devices</div>
                <div class="text-sm text-gray-500">Manage your devices</div>
            </div>
        </a>
        <a href="{{ route('account.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-colors">
            <i class="fas fa-user text-blue-600 text-xl mr-3"></i>
            <div>
                <div class="font-medium text-gray-900">My Account</div>
                <div class="text-sm text-gray-500">Update profile</div>
            </div>
        </a>
        <a href="mailto:support@example.com" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-colors">
            <i class="fas fa-headset text-blue-600 text-xl mr-3"></i>
            <div>
                <div class="font-medium text-gray-900">Contact Support</div>
                <div class="text-sm text-gray-500">Get help</div>
            </div>
        </a>
    </div>
</div>

<!-- Recent Activity -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
    @if($recentActivity && $recentActivity->count() > 0)
        <div class="space-y-4">
            @foreach($recentActivity as $activity)
                <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-lg">
                    <div class="text-gray-400 mt-1">
                        @if($activity->action == 'license_created')
                            <i class="fas fa-plus-circle text-green-500"></i>
                        @elseif($activity->action == 'license_updated')
                            <i class="fas fa-edit text-blue-500"></i>
                        @elseif($activity->action == 'license_renewed')
                            <i class="fas fa-sync text-purple-500"></i>
                        @elseif($activity->action == 'device_revoked')
                            <i class="fas fa-ban text-red-500"></i>
                        @else
                            <i class="fas fa-info-circle text-gray-500"></i>
                        @endif
                    </div>
                    <div class="flex-1">
                        <div class="font-medium text-gray-900">{{ $activity->description }}</div>
                        <div class="text-sm text-gray-500">{{ $activity->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-history text-4xl mb-3"></i>
            <p>No recent activity</p>
        </div>
    @endif
</div>
@endsection