@extends('layouts.app')

@section('title', 'Activity History')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Activity History</h1>
    <p class="text-gray-600">View your recent account activity and changes</p>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    @if($allActivities && $allActivities->count() > 0)
        <div class="space-y-4">
            @foreach($allActivities as $activity)
                <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-lg">
                    <div class="text-gray-400 mt-1">
                        @if($activity->action == 'license_created')
                            <i class="fas fa-plus-circle text-green-500"></i>
                        @elseif($activity->action == 'license_updated')
                            <i class="fas fa-edit text-blue-500"></i>
                        @elseif($activity->action == 'license_renewed')
                            <i class="fas fa-sync text-purple-500"></i>
                        @elseif($activity->action == 'license_deleted')
                            <i class="fas fa-trash text-red-500"></i>
                        @elseif($activity->action == 'device_revoked')
                            <i class="fas fa-ban text-red-500"></i>
                        @elseif($activity->action == 'device_created')
                            <i class="fas fa-plus-circle text-green-500"></i>
                        @elseif($activity->action == 'device_updated')
                            <i class="fas fa-edit text-blue-500"></i>
                        @elseif($activity->action == 'device_deleted')
                            <i class="fas fa-trash text-red-500"></i>
                        @else
                            <i class="fas fa-info-circle text-gray-500"></i>
                        @endif
                    </div>
                    <div class="flex-1">
                        <div class="font-medium text-gray-900">{{ $activity->description }}</div>
                        <div class="text-sm text-gray-500">
                            <span>{{ $activity->created_at->format('M d, Y H:i') }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $activity->created_at->diffForHumans() }}</span>
                        </div>
                        @if($activity->ip_address)
                            <div class="text-xs text-gray-400 mt-1">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                IP: {{ $activity->ip_address }}
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if ($allActivities->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $allActivities->links() }}
            </div>
        @endif
    @else
        <div class="text-center py-12 text-gray-500">
            <i class="fas fa-history text-5xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No activity found</h3>
            <p>Your activity history will appear here as you use the platform.</p>
        </div>
    @endif
</div>
@endsection