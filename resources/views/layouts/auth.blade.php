<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login') - broadcast.nissireseaux</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css'])
    @stack('styles')
    <style>
        .auth-bg {
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-image: var(--auth-bg-image);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 auth-bg" style="--auth-bg-image: url('{{ asset('images/logo/466654-video-598713.webp') }}')">
    @yield('content')
    @stack('scripts')
</body>
</html>
