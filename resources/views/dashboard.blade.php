@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="header">
    <h1 class="header-title">Dashboard</h1>
    <p class="header-subtitle">Welcome back, {{ auth()->user()->name }}!</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon licenses">
            <i class="fas fa-key"></i>
        </div>
        <div class="stat-value">{{ $licensesCount ?? 0 }}</div>
        <div class="stat-label">Total Licenses</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon users">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-value">{{ $usersCount ?? 0 }}</div>
        <div class="stat-label">Total Users</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon devices">
            <i class="fas fa-wifi"></i>
        </div>
        <div class="stat-value">{{ $devicesCount ?? 0 }}</div>
        <div class="stat-label">Active Devices</div>
    </div>
</div>

<div class="welcome-section">
    <h2>Welcome to broadcast Platform</h2>
    <p>Your comprehensive license and device management system is ready to help you manage your broadcasting operations efficiently.</p>
</div>

<div class="quick-actions">
    <h3>Quick Actions</h3>
    <div class="action-grid">
        @if (auth()->user()->role === 'admin')
            <a href="{{ route('licenses.create') }}" class="action-card">
                <i class="fas fa-plus-circle "></i>
                <div class="action-card-title">Create License</div>
                <div class="action-card-desc">Generate a new license key</div>
            </a>
            <a href="{{ route('licenses.index') }}" class="action-card">
                <i class="fas fa-key "></i>
                <div class="action-card-title">Manage Licenses</div>
                <div class="action-card-desc">View and manage all licenses</div>
            </a>
            <a href="{{ route('devices.index') }}" class="action-card">
                <i class="fas fa-wifi "></i>
                <div class="action-card-title">Manage Devices</div>
                <div class="action-card-desc">Monitor connected devices</div>
            </a>
        @endif
        <a href="{{ route('settings.index') }}" class="action-card">
            <i class="fas fa-cog "></i>
            <div class="action-card-title">Settings</div>
            <div class="action-card-desc">Configure platform settings</div>
        </a>
    </div>
</div>
@endsection

@push('styles')
<style>
    .header {
        margin-bottom: 32px;
    }
    .header-title {
        font-size: 32px;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 8px;
    }
    .header-subtitle {
        color: #666;
        font-size: 16px;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 24px;
        margin-bottom: 32px;
    }
    .stat-card {
        background: linear-gradient(135deg, #ffffff 0%, #fef9f7 100%);
        padding: 36px 32px;
        border-radius: 24px;
        box-shadow: 0 8px 32px rgba(194, 65, 12, 0.1);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid rgba(249, 115, 22, 0.15);
        position: relative;
        overflow: hidden;
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgba(249, 115, 22, 0.1) 0%, transparent 70%);
        transition: all 0.4s ease;
    }
    .stat-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, #f97316, #c2410c);
        transform: scaleX(0);
        transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .stat-card:hover {
        transform: translateY(-12px) scale(1.02);
        box-shadow: 0 16px 48px rgba(194, 65, 12, 0.2);
        border-color: rgba(249, 115, 22, 0.4);
    }
    .stat-card:hover::before {
        top: -30%;
        right: -30%;
        width: 120%;
        height: 120%;
    }
    .stat-card:hover::after {
        transform: scaleX(1);
    }
    .stat-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        margin-bottom: 20px;
        position: relative;
        z-index: 1;
    }
    .stat-icon.licenses {
        color: #f97316;
        filter: drop-shadow(0 4px 8px rgba(249, 115, 22, 0.3));
    }
    .stat-icon.users {
        color: #f97316;
        filter: drop-shadow(0 4px 8px rgba(249, 115, 22, 0.3));
    }
    .stat-icon.devices {
        color: #f97316;
        filter: drop-shadow(0 4px 8px rgba(249, 115, 22, 0.3));
    }
    .stat-value {
        font-size: 40px;
        font-weight: 800;
        color: #1a1a2e;
        margin-bottom: 4px;
        position: relative;
        z-index: 1;
        letter-spacing: -1px;
    }
    .stat-label {
        color: #6b7280;
        font-size: 14px;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        position: relative;
        z-index: 1;
    }
    .welcome-section {
        background: white;
        padding: 36px;
        border-radius: 20px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
        margin-bottom: 32px;
        border: 1px solid rgba(249, 115, 22, 0.1);
        transition: all 0.3s ease;
    }
    .welcome-section:hover {
        box-shadow: 0 8px 32px rgba(194, 65, 12, 0.12);
    }
    .welcome-section h2 {
        font-size: 24px;
        color: #1a1a2e;
        margin-bottom: 12px;
    }
    .welcome-section p {
        color: #666;
        font-size: 15px;
        line-height: 1.6;
    }
    .welcome-section strong {
        color: #1a1a2e;
        font-weight: 600;
    }
    .quick-actions {
        background: white;
        padding: 36px;
        border-radius: 20px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(249, 115, 22, 0.1);
    }
    .quick-actions h3 {
        font-size: 20px;
        color: #1a1a2e;
        margin-bottom: 20px;
    }
    .action-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }
    .action-card {
        padding: 32px;
        border: 2px solid rgba(249, 115, 22, 0.15);
        border-radius: 20px;
        text-decoration: none;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        background: linear-gradient(135deg, #ffffff 0%, #fef9f7 100%);
        position: relative;
        overflow: hidden;
    }
    .action-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgba(249, 115, 22, 0.1) 0%, transparent 70%);
        transition: all 0.4s ease;
    }
    .action-card::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #f97316, #c2410c);
        transform: scaleX(0);
        transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .action-card:hover::before {
        top: -30%;
        right: -30%;
        width: 120%;
        height: 120%;
    }
    .action-card:hover::after {
        transform: scaleX(1);
    }
    .action-card:hover {
        border-color: rgba(249, 115, 22, 0.5);
        background: linear-gradient(135deg, #fff7ed 0%, #fff 100%);
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 16px 40px rgba(194, 65, 12, 0.2);
    }
    .action-card i {
        font-size: 36px;
        color: #f97316;
        margin-bottom: 16px;
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
    }
    .action-card:hover i {
        transform: scale(1.15);
        color: #c2410c;
    }
    .action-card:hover i {
        transform: scale(1.1);
    }
    .action-card-title {
        color: #1a1a2e;
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 8px;
        letter-spacing: -0.5px;
        position: relative;
        z-index: 1;
    }
    .action-card:hover .action-card-title {
        color: #c2410c;
    }
    .action-card-desc {
        color: #666;
        font-size: 14px;
        line-height: 1.5;
        position: relative;
        z-index: 1;
    }
    .action-card:hover .action-card-desc {
        color: #9a3412;
    }
</style>
@endpush
