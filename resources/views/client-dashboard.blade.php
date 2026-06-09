@extends('layouts.app')

@section('title', 'Client Dashboard')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">My Dashboard</h1>
    <p class="text-gray-600">Welcome back, {{ auth()->user()->name }}!</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="text-blue-600 text-2xl">
                <i class="fas fa-key"></i>
            </div>
        </div>
        <div class="text-3xl font-bold text-gray-900 mb-1">{{ $licensesCount ?? 0 }}</div>
        <div class="text-sm text-gray-500">My Licenses</div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="text-green-600 text-2xl">
                <i class="fas fa-user"></i>
            </div>
        </div>
        <div class="text-3xl font-bold text-gray-900 mb-1">{{ $usersCount ?? 0 }}</div>
        <div class="text-sm text-gray-500">My Account</div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="text-purple-600 text-2xl">
                <i class="fas fa-wifi"></i>
            </div>
        </div>
        <div class="text-3xl font-bold text-gray-900 mb-1">{{ $devicesCount ?? 0 }}</div>
        <div class="text-sm text-gray-500">My Devices</div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
    <h2 class="text-xl font-semibold text-gray-900 mb-2">Client Portal</h2>
    <p class="text-gray-600">Manage your licenses and devices from your personal client dashboard.</p>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">My Actions</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <a href="{{ route('client.licenses') }}" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
            <i class="fas fa-key text-blue-600 text-xl mb-2"></i>
            <div class="font-medium text-gray-900 mb-1">My Licenses</div>
            <div class="text-sm text-gray-500">View all your licenses</div>
        </a>
        <a href="{{ route('account.index') }}" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
            <i class="fas fa-cog text-blue-600 text-xl mb-2"></i>
            <div class="font-medium text-gray-900 mb-1">My Account</div>
            <div class="text-sm text-gray-500">Manage your account settings</div>
        </a>
    </div>
</div>
@endsection