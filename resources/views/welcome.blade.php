<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css'])
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f9fafb;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 max-w-md text-center">
        <div class="mb-6">
            <img src="{{ asset('images/logo/logo-maester.webp') }}" alt="broadcast.nissireseaux" class="w-24 h-24 mx-auto object-contain">
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">broadcast.nissireseaux</h1>
        <p class="text-gray-600 mb-8">Your application is ready. Log in to get started.</p>
        <a href="{{ route('login') }}" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Sign In
        </a>
    </div>
</body>
</html>