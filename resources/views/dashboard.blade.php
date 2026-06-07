@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard</h1>
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
        <div class="text-sm text-gray-500">Total Licenses</div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="text-green-600 text-2xl">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="text-3xl font-bold text-gray-900 mb-1">{{ $usersCount ?? 0 }}</div>
        <div class="text-sm text-gray-500">Total Users</div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="text-purple-600 text-2xl">
                <i class="fas fa-wifi"></i>
            </div>
        </div>
        <div class="text-3xl font-bold text-gray-900 mb-1">{{ $devicesCount ?? 0 }}</div>
        <div class="text-sm text-gray-500">Active Devices</div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
    <h2 class="text-xl font-semibold text-gray-900 mb-2">Welcome to broadcast.nissireseaux</h2>
    <p class="text-gray-600">Your comprehensive license and device management system is ready to help you manage your broadcasting operations efficiently.</p>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @if (auth()->user()->role === 'admin')
            <a href="{{ route('licenses.create') }}" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="fas fa-plus-circle text-blue-600 text-xl mb-2"></i>
                <div class="font-medium text-gray-900 mb-1">Create License</div>
                <div class="text-sm text-gray-500">Generate a new license key</div>
            </a>
            <a href="{{ route('licenses.index') }}" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="fas fa-key text-blue-600 text-xl mb-2"></i>
                <div class="font-medium text-gray-900 mb-1">Manage Licenses</div>
                <div class="text-sm text-gray-500">View and manage all licenses</div>
            </a>
            <a href="{{ route('devices.index') }}" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="fas fa-wifi text-blue-600 text-xl mb-2"></i>
                <div class="font-medium text-gray-900 mb-1">Manage Devices</div>
                <div class="text-sm text-gray-500">Monitor connected devices</div>
            </a>
        @endif
        <a href="{{ route('settings.index') }}" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
            <i class="fas fa-cog text-blue-600 text-xl mb-2"></i>
            <div class="font-medium text-gray-900 mb-1">Settings</div>
            <div class="text-sm text-gray-500">Configure platform settings</div>
        </a>
    </div>
</div>
@endsection