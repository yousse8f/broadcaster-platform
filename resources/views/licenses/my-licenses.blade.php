@extends('layouts.app')

@section('title', 'My Licenses')

@section('content')
<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-bold text-gray-900">My Licenses</h1>
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
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Devices</th>
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
                            <button onclick="copyToClipboard('{{ $license->license_key }}')" class="text-gray-400 hover:text-gray-600 p-1" title="Copy to clipboard">
                                <i class="fas fa-copy"></i>
                            </button>
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
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <span class="font-medium">{{ $license->devices->count() }}</span>
                            <span class="text-gray-400">/</span>
                            <span>{{ $license->allowed_devices }}</span>
                            <span class="text-gray-500 text-sm">devices</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ $license->expires_at ? $license->expires_at->format('M d, Y') : 'Never' }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <a href="{{ route('licenses.show', $license->id) }}" class="p-2 text-gray-400 hover:text-blue-600 rounded hover:bg-blue-50" title="View details">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="text-gray-400">
                            <i class="fas fa-key text-4xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No licenses found</h3>
                            <p class="text-gray-500">You don't have any licenses assigned to your account yet.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($licenses->hasPages())
    <div class="mt-6 flex justify-center">
        {{ $licenses->links() }}
    </div>
@endif

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('License key copied to clipboard!');
    }, function(err) {
        console.error('Could not copy text: ', err);
    });
}
</script>
@endsection
