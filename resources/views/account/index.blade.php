@extends('layouts.app')

@section('title', auth()->user()->role === 'admin' ? 'Settings' : 'My Account')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ auth()->user()->role === 'admin' ? 'Settings' : 'My Account' }}</h1>
    <p class="text-gray-600">Manage your account settings and preferences</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Profile Information -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-900">
                <i class="fas fa-user-circle text-blue-600 mr-2"></i>
                Profile Information
            </h2>
            <button onclick="document.getElementById('editProfileModal').classList.remove('hidden')" 
                    class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                <i class="fas fa-edit mr-1"></i> Edit
            </button>
        </div>
        
        <div class="space-y-4">
            <div>
                <label class="text-sm text-gray-500">Full Name</label>
                <div class="text-gray-900 font-medium">{{ $user->name }}</div>
            </div>
            
            <div>
                <label class="text-sm text-gray-500">Email</label>
                <div class="text-gray-900 font-medium">{{ $user->email }}</div>
            </div>
            
            <div>
                <label class="text-sm text-gray-500">Role</label>
                <div class="text-gray-900 font-medium capitalize">{{ $user->role }}</div>
            </div>
            
            <div>
                <label class="text-sm text-gray-500">Customer Since</label>
                <div class="text-gray-900 font-medium">{{ $user->created_at->format('F Y') }}</div>
            </div>
        </div>
    </div>

    <!-- Security -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-900">
                <i class="fas fa-shield-alt text-green-600 mr-2"></i>
                Security
            </h2>
            <button onclick="document.getElementById('changePasswordModal').classList.remove('hidden')" 
                    class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                <i class="fas fa-key mr-1"></i> Change Password
            </button>
        </div>
        
        <div class="space-y-4">
            <div>
                <label class="text-sm text-gray-500">Last Login</label>
                <div class="text-gray-900 font-medium">{{ $lastLogin }}</div>
            </div>
            
            <div class="pt-4 border-t border-gray-200">
                <p class="text-sm text-gray-600 mb-3">
                    <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                    Keep your account secure by using a strong password and changing it regularly.
                </p>
            </div>
        </div>
    </div>

    <!-- Subscription & Licenses -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-900">
                <i class="fas fa-crown text-yellow-600 mr-2"></i>
                Subscription & Licenses
            </h2>
            @if(auth()->user()->role === 'client')
                <a href="{{ route('licenses.my') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    <i class="fas fa-arrow-right mr-1"></i> View All
                </a>
            @endif
        </div>
        
        <div class="space-y-4">
            <div>
                <label class="text-sm text-gray-500">Current Plan</label>
                <div class="text-gray-900 font-medium">{{ $plan }}</div>
            </div>
            
            <div>
                <label class="text-sm text-gray-500">Active Licenses</label>
                <div class="text-gray-900 font-medium">{{ $activeLicenses }} Active</div>
            </div>
            
            <div>
                <label class="text-sm text-gray-500">Next Expiration</label>
                <div class="text-gray-900 font-medium">
                    @if($nextExpiration)
                        {{ $nextExpiration->expires_at->format('Y-m-d') }}
                    @else
                        No active licenses
                    @endif
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-500">Allowed Devices</label>
                    <div class="text-gray-900 font-medium">{{ $totalAllowedDevices }}</div>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Used Devices</label>
                    <div class="text-gray-900 font-medium">{{ $totalUsedDevices }}</div>
                </div>
            </div>
            
            <!-- Progress Bar -->
            @if($totalAllowedDevices > 0)
                <div class="pt-2">
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">Device Usage</span>
                        <span class="text-gray-900 font-medium">{{ $totalUsedDevices }}/{{ $totalAllowedDevices }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%" data-width="{{ $deviceUsagePercentage }}"></div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Account Actions -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">
            <i class="fas fa-cogs text-purple-600 mr-2"></i>
            Account Actions
        </h2>
        
        <div class="space-y-3">
            <button onclick="document.getElementById('editProfileModal').classList.remove('hidden')" 
                    class="w-full flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors text-left">
                <div class="bg-blue-100 p-3 rounded-lg mr-4">
                    <i class="fas fa-user-edit text-blue-600"></i>
                </div>
                <div>
                    <div class="font-medium text-gray-900">Update Profile</div>
                    <div class="text-sm text-gray-500">Change your name and email</div>
                </div>
            </button>
            
            <button onclick="document.getElementById('changePasswordModal').classList.remove('hidden')" 
                    class="w-full flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors text-left">
                <div class="bg-green-100 p-3 rounded-lg mr-4">
                    <i class="fas fa-lock text-green-600"></i>
                </div>
                <div>
                    <div class="font-medium text-gray-900">Change Password</div>
                    <div class="text-sm text-gray-500">Update your security credentials</div>
                </div>
            </button>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div id="editProfileModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Update Profile</h3>
            
            <form method="POST" action="{{ route('account.update-profile') }}">
                @csrf
                
                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" name="name" value="{{ $user->name }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ $user->email }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('editProfileModal').classList.add('hidden')" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div id="changePasswordModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Change Password</h3>
            
            <form method="POST" action="{{ route('account.update-password') }}">
                @csrf
                
                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                    <input type="password" name="current_password" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                    <input type="password" name="password" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('changePasswordModal').classList.add('hidden')" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set progress bar width dynamically
        const progressBars = document.querySelectorAll('[data-width]');
        progressBars.forEach(bar => {
            const width = bar.getAttribute('data-width');
            setTimeout(() => {
                bar.style.width = width + '%';
            }, 100);
        });
    });
</script>
@endpush
@endsection
