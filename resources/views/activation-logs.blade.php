@extends('layouts.app')

@section('title', 'Activation Logs')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Activation Logs</h1>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div class="text-blue-600 text-2xl mb-2"><i class="fas fa-list"></i></div>
        <div class="text-sm text-gray-500">Total Logs</div>
        <div class="text-2xl font-bold text-gray-900">{{ $totalCount ?? 0 }}</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div class="text-green-600 text-2xl mb-2"><i class="fas fa-check-circle"></i></div>
        <div class="text-sm text-gray-500">Successful</div>
        <div class="text-2xl font-bold text-gray-900">{{ $successCount ?? 0 }}</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div class="text-red-600 text-2xl mb-2"><i class="fas fa-times-circle"></i></div>
        <div class="text-sm text-gray-500">Failed</div>
        <div class="text-2xl font-bold text-gray-900">{{ $failedCount ?? 0 }}</div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
    <form method="GET" action="{{ route('activation-logs') }}" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 h-10">
                <option value="">All Status</option>
                <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Success</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
        </div>
        <div class="flex-1 min-w-[200px]">
            <input type="text" name="license_key" value="{{ request('license_key') }}" placeholder="License key..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 h-10">
        </div>
        <div class="flex-1 min-w-[200px]">
            <input type="text" name="device_id" value="{{ request('device_id') }}" placeholder="Device ID..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 h-10">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 h-10">Filter</button>
            <a href="{{ route('activation-logs') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 h-10">Clear</a>
        </div>
    </form>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">License Key</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Device ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Device Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($activationLogs as $log)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-600">{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                    <td class="px-6 py-4"><code class="bg-gray-100 px-2 py-1 rounded text-sm">{{ $log->license_key }}</code></td>
                    <td class="px-6 py-4"><code class="bg-gray-100 px-2 py-1 rounded text-sm">{{ $log->device_id }}</code></td>
                    <td class="px-6 py-4 text-gray-900">{{ $log->device_name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $log->ip_address ?? 'N/A' }}</td>
                    <td class="px-6 py-4">
                        @if($log->success)
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                                <i class="fas fa-check-circle"></i> Success
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">
                                <i class="fas fa-times-circle"></i> Failed
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $log->message ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <div class="text-gray-400">
                            <i class="fas fa-list text-4xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No activation logs found</h3>
                            <p class="text-gray-500">Activation attempts will appear here</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($activationLogs->hasPages())
    <div class="mt-6">
        {{ $activationLogs->appends(request()->except('page'))->links() }}
    </div>
@endif
@endsection