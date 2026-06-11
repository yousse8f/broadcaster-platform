@extends('layouts.auth')

@section('title', 'Admin Login')

@section('content')
<div class="w-full max-w-md bg-white/80 backdrop-blur-md rounded-lg shadow-lg border border-gray-200 overflow-hidden">
    <div class="bg-white p-8 border-b border-gray-200">
        <div class="text-center">
            <img src="{{ asset('images/logo/logo-maester.webp') }}" alt="PONGAME MEJOR ESTE NR- BROAD CASTER" class="w-24 h-24 mx-auto mb-4 object-contain">
            <h1 class="text-2xl font-bold text-gray-900">NR- BROADCASTER</h1>
            <p class="text-gray-600 text-sm mt-1">Admin Access Only</p>
        </div>
    </div>

    <div class="p-8">
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center gap-2">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ $errors->first('email') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500 focus:outline-none">
                @if ($errors->has('email'))
                    <p class="mt-1 text-sm text-red-600">{{ $errors->first('email') }}</p>
                @endif
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500 focus:outline-none">
                @if ($errors->has('password'))
                    <p class="mt-1 text-sm text-red-600">{{ $errors->first('password') }}</p>
                @endif
            </div>

            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="w-4 h-4 text-gray-600 border-gray-300 rounded focus:ring-gray-500">
                    <span class="ml-2 text-sm text-gray-700">Remember me</span>
                </label>
            </div>

            <button type="submit" class="w-full px-4 py-3 bg-gray-800 text-white rounded-lg hover:bg-gray-900 focus:ring-2 focus:ring-gray-500 focus:outline-none transition-colors font-medium">
                Admin Sign In
            </button>
        </form>
    </div>
</div>
@endsection
