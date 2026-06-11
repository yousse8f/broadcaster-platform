@extends('layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">System Settings</h1>
        <p class="text-gray-600 mt-2">Manage your platform configuration and security settings</p>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-700 rounded-lg flex items-center gap-2">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Tabs -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="flex gap-4">
            <button onclick="showTab('general')" id="tab-general" class="tab-button px-4 py-2 border-b-2 border-blue-600 text-blue-600 font-medium">
                <i class="fas fa-cog mr-2"></i>General
            </button>
            <button onclick="showTab('license')" id="tab-license" class="tab-button px-4 py-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium">
                <i class="fas fa-key mr-2"></i>License
            </button>
            <button onclick="showTab('security')" id="tab-security" class="tab-button px-4 py-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium">
                <i class="fas fa-shield-alt mr-2"></i>Security
            </button>
        </nav>
    </div>

    <!-- General Settings Tab -->
    <div id="panel-general" class="tab-panel">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">General Settings</h2>
            <p class="text-gray-600 mb-6">Configure basic platform information and appearance</p>

            <form method="POST" action="{{ route('admin.settings.update-general') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">Company Name</label>
                        <input type="text" id="company_name" name="company_name" value="{{ old('company_name', $settings->company_name) }}" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>

                    <div>
                        <label for="support_email" class="block text-sm font-medium text-gray-700 mb-1">Support Email</label>
                        <input type="email" id="support_email" name="support_email" value="{{ old('support_email', $settings->support_email) }}" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="support@company.com">
                    </div>

                    <div>
                        <label for="support_url" class="block text-sm font-medium text-gray-700 mb-1">Support URL</label>
                        <input type="url" id="support_url" name="support_url" value="{{ old('support_url', $settings->support_url) }}" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="https://support.company.com">
                    </div>

                    <div>
                        <label for="timezone" class="block text-sm font-medium text-gray-700 mb-1">Timezone</label>
                        <select id="timezone" name="timezone" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            @foreach($timezones as $value => $label)
                                <option value="{{ $value }}" {{ $settings->timezone == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Logo</label>
                    @if($settings->logo)
                        <div class="flex items-center gap-4 mb-4">
                            <img src="{{ asset($settings->logo) }}" alt="Logo" class="h-16 w-auto">
                            <form method="POST" action="{{ route('admin.settings.delete-logo') }}" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1 text-sm text-red-600 border border-red-300 rounded hover:bg-red-50" onclick="return confirm('Are you sure you want to delete the logo?')">
                                    <i class="fas fa-trash mr-1"></i>Delete Logo
                                </button>
                            </form>
                        </div>
                    @endif
                    <input type="file" id="logo" name="logo" accept="image/jpeg,image/png,image/jpg,image/gif,image/svg,image/webp" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-sm text-gray-500 mt-1">Recommended: Square image, max 2MB (JPG, PNG, GIF, SVG, WebP)</p>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>Save General Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- License Settings Tab -->
    <div id="panel-license" class="tab-panel hidden">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">License Settings</h2>
            <p class="text-gray-600 mb-6">Configure default license parameters and validation timeouts</p>

            <form method="POST" action="{{ route('admin.settings.update-license') }}" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="default_expiration" class="block text-sm font-medium text-gray-700 mb-1">Default Expiration (Days)</label>
                        <input type="number" id="default_expiration" name="default_expiration" value="{{ old('default_expiration', $settings->default_expiration) }}" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" min="1" max="3650" required>
                        <p class="text-sm text-gray-500 mt-1">Default validity period for new licenses</p>
                    </div>

                    <div>
                        <label for="default_devices_limit" class="block text-sm font-medium text-gray-700 mb-1">Default Devices Limit</label>
                        <input type="number" id="default_devices_limit" name="default_devices_limit" value="{{ old('default_devices_limit', $settings->default_devices_limit) }}" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" min="1" max="100" required>
                        <p class="text-sm text-gray-500 mt-1">Maximum allowed devices per license</p>
                    </div>

                    <div>
                        <label for="heartbeat_timeout" class="block text-sm font-medium text-gray-700 mb-1">Heartbeat Timeout (Seconds)</label>
                        <input type="number" id="heartbeat_timeout" name="heartbeat_timeout" value="{{ old('heartbeat_timeout', $settings->heartbeat_timeout) }}" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" min="30" max="3600" required>
                        <p class="text-sm text-gray-500 mt-1">Device considered offline after no heartbeat for this duration</p>
                    </div>

                    <div>
                        <label for="validation_timeout" class="block text-sm font-medium text-gray-700 mb-1">Validation Timeout (Seconds)</label>
                        <input type="number" id="validation_timeout" name="validation_timeout" value="{{ old('validation_timeout', $settings->validation_timeout) }}" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" min="10" max="300" required>
                        <p class="text-sm text-gray-500 mt-1">Maximum time for license validation response</p>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>Save License Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Security Settings Tab -->
    <div id="panel-security" class="tab-panel hidden">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Security Settings</h2>
            <p class="text-gray-600 mb-6">Configure API security, rate limits, and access control</p>

            <form method="POST" action="{{ route('admin.settings.update-security') }}" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="rate_limit" class="block text-sm font-medium text-gray-700 mb-1">Rate Limit (per minute)</label>
                        <input type="number" id="rate_limit" name="rate_limit" value="{{ old('rate_limit', $settings->rate_limit) }}" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" min="1" max="1000" required>
                        <p class="text-sm text-gray-500 mt-1">Maximum API requests per minute per IP</p>
                    </div>

                    <div>
                        <label for="max_activations_per_day" class="block text-sm font-medium text-gray-700 mb-1">Max Activations Per Day</label>
                        <input type="number" id="max_activations_per_day" name="max_activations_per_day" value="{{ old('max_activations_per_day', $settings->max_activations_per_day) }}" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" min="1" max="100" required>
                        <p class="text-sm text-gray-500 mt-1">Maximum device activations per day per IP</p>
                    </div>
                </div>

                <div>
                    <label for="api_secret" class="block text-sm font-medium text-gray-700 mb-1">API Secret</label>
                    <div class="flex gap-2">
                        <input type="password" id="api_secret" name="api_secret" value="{{ old('api_secret', $settings->api_secret) }}" 
                            class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Leave blank to keep current">
                        <button type="button" onclick="toggleApiSecretVisibility()" class="px-3 py-2 border border-gray-300 rounded hover:bg-gray-50">
                            <i id="api-secret-eye" class="fas fa-eye"></i>
                        </button>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">Secret key for API authentication (min 16 characters)</p>
                    
                    @if($settings->api_secret)
                        <div class="mt-4">
                            <form method="POST" action="{{ route('admin.settings.generate-api-secret') }}" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-2 text-sm text-blue-600 border border-blue-300 rounded hover:bg-blue-50" onclick="return confirm('Are you sure you want to generate a new API secret? This will invalidate the current secret.')">
                                    <i class="fas fa-sync-alt mr-1"></i>Generate New Secret
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                <div>
                    <label for="allowed_origins" class="block text-sm font-medium text-gray-700 mb-1">Allowed Origins (CORS)</label>
                    <textarea id="allowed_origins" name="allowed_origins" rows="3" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                        placeholder="https://example.com, https://app.example.com">{{ old('allowed_origins', $settings->allowed_origins ? implode(', ', $settings->allowed_origins) : '') }}</textarea>
                    <p class="text-sm text-gray-500 mt-1">Comma-separated list of allowed origins for CORS. Leave blank to allow all origins (*)</p>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>Save Security Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all panels
    document.querySelectorAll('.tab-panel').forEach(panel => {
        panel.classList.add('hidden');
    });
    
    // Remove active state from all tabs
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('border-blue-600', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected panel
    document.getElementById('panel-' + tabName).classList.remove('hidden');
    
    // Add active state to selected tab
    const activeTab = document.getElementById('tab-' + tabName);
    activeTab.classList.remove('border-transparent', 'text-gray-500');
    activeTab.classList.add('border-blue-600', 'text-blue-600');
}

function toggleApiSecretVisibility() {
    const input = document.getElementById('api_secret');
    const eye = document.getElementById('api-secret-eye');
    
    if (input.type === 'password') {
        input.type = 'text';
        eye.classList.remove('fa-eye');
        eye.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        eye.classList.remove('fa-eye-slash');
        eye.classList.add('fa-eye');
    }
}
</script>
@endsection