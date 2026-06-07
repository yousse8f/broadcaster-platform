@extends('layouts.app')

@section('title', 'Licenses Management')

@section('content')
<div class="header">
    <h1 class="header-title">Licenses Management</h1>
    <a href="{{ route('licenses.create') }}" class="create-btn">
        <i class="fas fa-plus "></i>
        Create License
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle "></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>License Key</th>
                <th>User Name</th>
                <th>Allowed Devices</th>
                <th>Status</th>
                <th>Expiry Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($licenses as $license)
                <tr>
                    <td>
                        <div class="license-key">
                            <code>{{ $license->license_key }}</code>
                            <button class="copy-btn" onclick="copyToClipboard('{{ $license->license_key }}')">
                                <i class="fas fa-copy "></i>
                            </button>
                        </div>
                    </td>
                    <td>
                        <div class="user-info">
                            <div class="user-name">{{ $license->user->name ?? 'N/A' }}</div>
                            <div class="user-email">{{ $license->user->email ?? 'N/A' }}</div>
                        </div>
                    </td>
                    <td>
                        <div class="devices-count">
                            <i class="fas fa-wifi "></i>
                            <span>{{ $license->allowed_devices ?? 0 }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="status-badge status-{{ $license->status }}">
                            {{ $license->status }}
                        </span>
                    </td>
                    <td>{{ $license->expires_at ? $license->expires_at->format('M d, Y') : ($license->expiry_date ? $license->expiry_date->format('M d, Y') : 'Never') }}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('licenses.show', $license->id) }}" class="action-btn view-btn">
                                <i class="fas fa-eye "></i>
                            </a>
                            <a href="{{ route('licenses.edit', $license->id) }}" class="action-btn edit-btn">
                                <i class="fas fa-edit "></i>
                            </a>
                            <form method="POST" action="{{ route('licenses.destroy', $license->id) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this license?')">
                                    <i class="fas fa-trash "></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 40px;">
                        <div class="empty-state">
                            <i class="fas fa-key "></i>
                            <h3>No licenses found</h3>
                            <p>Create your first license to get started</p>
                            <a href="{{ route('licenses.create') }}" class="create-btn">
                                <i class="fas fa-plus "></i> Create License
                            </a>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
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
    .create-btn {
        padding: 12px 24px;
        background: white;
        color: #f97316;
        border: 2px solid #f97316;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 15px rgba(249, 115, 22, 0.15);
    }
    .create-btn:hover {
        background: #fff7ed;
        color: #c2410c;
        border-color: #c2410c;
        transform: translateY(-2px);
        box-shadow: 0 6px 25px rgba(194, 65, 12, 0.25);
    }
    .create-btn i {
        color: #f97316;
    }
    .create-btn:hover i {
        color: #c2410c;
    }
    .create-btn:active {
        transform: translateY(0);
    }
    .create-btn i {
        font-size: 14px;
    }
    .alert {
        padding: 16px 20px;
        border-radius: 10px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .alert-success {
        background: #10b981;
        color: white;
    }
    .alert-success i {
        font-size: 20px;
    }
    .table-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    thead {
        background: #f97316;
        color: white;
    }
    th, td {
        padding: 16px 20px;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
    }
    th {
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    tbody tr:hover {
        background: #f8f9ff;
    }
    .license-key {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .license-key code {
        font-family: monospace;
        background: #f3f4f6;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        color: #374151;
    }
    .copy-btn {
        background: none;
        border: none;
        cursor: pointer;
        color: #6b7280;
        transition: color 0.2s ease;
        padding: 4px;
    }
    .copy-btn:hover {
        color: #f97316;
    }
    .status-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        display: inline-block;
    }
    .status-active {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    .status-suspended {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        color: white;
    }
    .status-expired {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }
    .status-inactive {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        color: white;
    }
    .action-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        align-items: center;
    }
    .action-btn {
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        white-space: nowrap;
        width: 40px;
        height: 40px;
    }
    .action-btn:hover {
        transform: translateY(-2px);
    }
    .action-btn:active {
        transform: translateY(0);
    }
    .action-btn i {
        font-size: 14px;
    }
    .view-btn {
        background: white;
        color: #f97316;
        border: 2px solid #f97316;
        box-shadow: 0 4px 15px rgba(249, 115, 22, 0.15);
    }
    .view-btn:hover {
        background: #fff7ed;
        color: #c2410c;
        border-color: #c2410c;
        box-shadow: 0 6px 20px rgba(194, 65, 12, 0.25);
    }
    .edit-btn {
        background: white;
        color: #f97316;
        border: 2px solid #f97316;
        box-shadow: 0 4px 15px rgba(249, 115, 22, 0.15);
    }
    .edit-btn:hover {
        background: #fff7ed;
        color: #c2410c;
        border-color: #c2410c;
        box-shadow: 0 6px 20px rgba(194, 65, 12, 0.25);
    }
    .delete-btn {
        background: white;
        color: #f97316;
        border: 2px solid #f97316;
        box-shadow: 0 4px 15px rgba(249, 115, 22, 0.15);
    }
    .delete-btn:hover {
        background: #fff7ed;
        color: #c2410c;
        border-color: #c2410c;
        box-shadow: 0 6px 20px rgba(194, 65, 12, 0.25);
    }
    .user-info {
        display: flex;
        flex-direction: column;
    }
    .user-name {
        font-weight: 600;
        color: #1a1a2e;
        font-size: 14px;
    }
    .user-email {
        color: #6b7280;
        font-size: 12px;
    }
    .devices-count {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background: linear-gradient(135deg, #f97316 0%, #c2410c 100%);
        color: white;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }
    .devices-count i {
        font-size: 12px;
    }
    .empty-state {
        text-align: center;
        padding: 40px;
        color: #6b7280;
    }
    .empty-state i {
        font-size: 48px;
        margin-bottom: 16px;
        color: #f97316;
    }
    .empty-state h3 {
        font-size: 18px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }
    .empty-state p {
        margin-bottom: 16px;
    }
</style>
@endpush

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('License key copied to clipboard!');
    }, function(err) {
        console.error('Could not copy text: ', err);
    });
}
</script>
@endpush
