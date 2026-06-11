@extends('layouts.app')

@section('title', 'Security')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Security</h1>
    <p class="text-gray-600">Monitor your account security and device activations</p>
</div>

<!-- Recent Device Activations -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Device Activations</h2>
    @if($recentActivations && $recentActivations->count() > 0)
        <div class="space-y-3">
            @foreach($recentActivations as $activation)
                <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-lg">
                    <div class="text-green-500 mt-1">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="flex-1">
                        <div class="font-medium text-gray-900">Device Activated</div>
                        <div class="text-sm text-gray-500">
                            <span>Device ID: {{ $activation->device_id }}</span>
                            <span class="mx-2">•</span>
                            <span>IP: {{ $activation->ip_address ?? 'Unknown' }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $activation->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-laptop text-4xl mb-3"></i>
            <p>No recent device activations</p>
        </div>
    @endif
</div>

<!-- Failed Login Attempts -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Failed Login Attempts</h2>
    @if($failedLogins && $failedLogins->count() > 0)
        <div class="space-y-3">
            @foreach($failedLogins as $login)
                <div class="flex items-start gap-3 p-4 bg-red-50 rounded-lg border border-red-200">
                    <div class="text-red-500 mt-1">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="flex-1">
                        <div class="font-medium text-red-900">Failed Login Attempt</div>
                        <div class="text-sm text-gray-600">
                            <span>IP: {{ $login->ip_address ?? 'Unknown' }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $login->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-shield-alt text-4xl mb-3"></i>
            <p>No failed login attempts detected</p>
        </div>
    @endif
</div>

<!-- Security Events -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Security Events</h2>
    @if($securityEvents && $securityEvents->count() > 0)
        <div class="space-y-3">
            @foreach($securityEvents as $event)
                <div class="flex items-start gap-3 p-4 @if($event->severity == 'high') bg-red-50 border border-red-200 @elseif($event->severity == 'medium') bg-yellow-50 border border-yellow-200 @else bg-gray-50 rounded-lg @endif">
                    <div class="@if($event->severity == 'high') text-red-500 @elseif($event->severity == 'medium') text-yellow-500 @else text-gray-400 mt-1 @endif">
                        @if($event->event_type == 'api_abuse')
                            <i class="fas fa-ban"></i>
                        @elseif($event->event_type == 'rate_limit_exceeded')
                            <i class="fas fa-tachometer-alt"></i>
                        @elseif($event->event_type == 'blocked_request')
                            <i class="fas fa-shield-alt"></i>
                        @else
                            <i class="fas fa-info-circle"></i>
                        @endif
                    </div>
                    <div class="flex-1">
                        <div class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $event->event_type)) }}</div>
                        <div class="text-sm text-gray-500">
                            <span>IP: {{ $event->ip_address ?? 'Unknown' }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $event->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-shield-alt text-4xl mb-3"></i>
            <p>No security events detected</p>
        </div>
    @endif
</div>
@endsection