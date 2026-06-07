@extends('layouts.app')

@section('title', 'Activation Logs')

@section('content')
<div class="header">
    <h1 class="header-title">Activation Logs</h1>
</div>

<div class="statistics-grid">
    <div class="stat-card">
        <div class="stat-icon stat-icon-blue">
            <i class="fas fa-list"></i>
        </div>
        <div class="stat-label">Total Logs</div>
        <div class="stat-value">{{ $totalCount ?? 0 }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon stat-icon-green">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-label">Successful</div>
        <div class="stat-value">{{ $successCount ?? 0 }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon stat-icon-red">
            <i class="fas fa-times-circle"></i>
        </div>
        <div class="stat-label">Failed</div>
        <div class="stat-value">{{ $failedCount ?? 0 }}</div>
    </div>
</div>

<div class="filter-section">
    <form method="GET" action="{{ route('activation-logs') }}" class="filter-grid">
        <div class="filter-group">
            <label class="filter-label">Status</label>
            <select name="status" class="filter-select">
                <option value="">All Status</option>
                <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Success</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
        </div>
        
        <div class="filter-group">
            <label class="filter-label">License Key</label>
            <input type="text" name="license_key" class="filter-select" value="{{ request('license_key') }}" placeholder="License key...">
        </div>
        
        <div class="filter-group">
            <label class="filter-label">Device ID</label>
            <input type="text" name="device_id" class="filter-select" value="{{ request('device_id') }}" placeholder="Device ID...">
        </div>
        
        <div class="filter-group" style="justify-content: flex-end; flex-direction: row; gap: 12px; align-items: flex-end;">
            <button type="submit" class="filter-apply-btn">
                <i class="fas fa-search"></i> Filter
            </button>
            <a href="{{ route('activation-logs') }}" class="filter-clear-btn">
                <i class="fas fa-times"></i> Clear
            </a>
        </div>
    </form>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Time</th>
                <th>License Key</th>
                <th>Device ID</th>
                <th>Device Name</th>
                <th>IP Address</th>
                <th>Status</th>
                <th>Message</th>
            </tr>
        </thead>
        <tbody>
            @forelse($activationLogs as $log)
                <tr>
                    <td>{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                    <td><code>{{ $log->license_key }}</code></td>
                    <td><code>{{ $log->device_id }}</code></td>
                    <td>{{ $log->device_name ?? 'N/A' }}</td>
                    <td>{{ $log->ip_address ?? 'N/A' }}</td>
                    <td>
                        @if($log->success)
                            <span class="status-badge status-active">
                                <i class="fas fa-check-circle"></i> Success
                            </span>
                        @else
                            <span class="status-badge status-revoked">
                                <i class="fas fa-times-circle"></i> Failed
                            </span>
                        @endif
                    </td>
                    <td>{{ $log->message ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px;">
                        <div class="empty-state">
                            <i class="fas fa-list"></i>
                            <h3>No activation logs found</h3>
                            <p>Activation attempts will appear here</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($activationLogs->hasPages())
    <div class="pagination">
        {{ $activationLogs->appends(request()->except('page'))->links() }}
    </div>
@endif
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
    .create-btn {
        padding: 12px 24px;
        background: white;
        color: #f97316;
        border: 2px solid #f97316;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }
    .create-btn:hover {
        background: #f97316;
        color: white;
    }
    .alert {
        padding: 16px 20px;
        border-radius: 8px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .alert-success {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #86efac;
    }
    .statistics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 24px;
        margin-bottom: 32px;
    }
    .stat-card {
        background: white;
        padding: 24px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    .stat-icon-blue {
        background: #dbeafe;
        color: #1d4ed8;
    }
    .stat-icon-green {
        background: #dcfce7;
        color: #166534;
    }
    .stat-icon-yellow {
        background: #fef9c3;
        color: #854d0e;
    }
    .stat-icon-red {
        background: #fee2e2;
        color: #991b1b;
    }
    .stat-label {
        color: #64748b;
        font-size: 14px;
        font-weight: 500;
    }
    .stat-value {
        color: #1a1a2e;
        font-size: 28px;
        font-weight: 700;
    }
    .filter-section {
        background: white;
        padding: 24px;
        border-radius: 12px;
        margin-bottom: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        align-items: end;
    }
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .filter-label {
        font-size: 14px;
        font-weight: 600;
        color: #475569;
    }
    .filter-select {
        padding: 10px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        color: #1a1a2e;
        background: white;
    }
    .filter-select:focus {
        outline: none;
        border-color: #f97316;
    }
    .filter-apply-btn {
        padding: 10px 20px;
        background: #f97316;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: background 0.2s;
    }
    .filter-apply-btn:hover {
        background: #ea580c;
    }
    .filter-clear-btn {
        padding: 10px 20px;
        background: white;
        color: #64748b;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }
    .filter-clear-btn:hover {
        background: #f1f5f9;
        color: #475569;
    }
    .table-container {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    thead {
        background: #f8fafc;
    }
    th {
        padding: 16px;
        text-align: left;
        font-weight: 600;
        color: #475569;
        font-size: 14px;
        border-bottom: 1px solid #e2e8f0;
    }
    td {
        padding: 16px;
        border-bottom: 1px solid #e2e8f0;
        color: #1a1a2e;
        font-size: 14px;
    }
    tr:hover {
        background: #f8fafc;
    }
    code {
        background: #f1f5f9;
        padding: 4px 8px;
        border-radius: 4px;
        font-family: 'Courier New', monospace;
        font-size: 12px;
    }
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .status-active {
        background: #dcfce7;
        color: #166534;
    }
    .status-revoked {
        background: #fee2e2;
        color: #991b1b;
    }
    .empty-state {
        text-align: center;
        padding: 40px;
        color: #64748b;
    }
    .empty-state i {
        font-size: 48px;
        margin-bottom: 16px;
        color: #cbd5e1;
    }
    .empty-state h3 {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 8px;
        color: #475569;
    }
    .empty-state p {
        font-size: 14px;
        margin-bottom: 24px;
    }
    .pagination {
        margin-top: 24px;
        display: flex;
        justify-content: center;
    }
    .pagination a {
        padding: 8px 16px;
        margin: 0 4px;
        border-radius: 6px;
        background: white;
        color: #475569;
        text-decoration: none;
        border: 1px solid #e2e8f0;
    }
    .pagination a:hover {
        background: #f8fafc;
        border-color: #f97316;
    }
    .pagination .active {
        background: #f97316;
        color: white;
        border-color: #f97316;
    }
</style>
@endpush
