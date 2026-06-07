@extends('layouts.app')

@section('title', 'Licenses Management')

@section('content')
<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Licenses Management</h1>
    <a href="{{ route('licenses.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
        <i class="fas fa-plus"></i>
        Create License
    </a>
</div>

@if(session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-700 rounded-lg flex items-center gap-2">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">License Key</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Allowed Devices</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expiry Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($licenses as $license)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <code class="bg-gray-100 px-2 py-1 rounded text-sm">{{ $license->license_key }}</code>
                            <button onclick="copyToClipboard('{{ $license->license_key }}')" class="text-gray-400 hover:text-gray-600 p-1">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div>
                            <div class="font-medium text-gray-900">{{ $license->user->name ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500">{{ $license->user->email ?? 'N/A' }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-wifi text-gray-400"></i>
                            <span>{{ $license->allowed_devices ?? 0 }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                            @if($license->status == 'active') bg-green-100 text-green-700
                            @elseif($license->status == 'suspended') bg-red-100 text-red-700
                            @elseif($license->status == 'expired') bg-yellow-100 text-yellow-700
                            @else bg-gray-100 text-gray-700
                            @endif">
                            {{ ucfirst($license->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ $license->expires_at ? $license->expires_at->format('M d, Y') : ($license->expiry_date ? $license->expiry_date->format('M d, Y') : 'Never') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <a href="{{ route('licenses.show', $license->id) }}" class="p-2 text-gray-400 hover:text-blue-600 rounded hover:bg-blue-50">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('licenses.edit', $license->id) }}" class="p-2 text-gray-400 hover:text-blue-600 rounded hover:bg-blue-50">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('licenses.destroy', $license->id) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-600 rounded hover:bg-red-50" onclick="return confirm('Are you sure you want to delete this license?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="text-gray-400">
                            <i class="fas fa-key text-4xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No licenses found</h3>
                            <p class="text-gray-500 mb-4">Create your first license to get started</p>
                            <a href="{{ route('licenses.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                <i class="fas fa-plus"></i> Create License
                            </a>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text);
}
</script>
@endsection