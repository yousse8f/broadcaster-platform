@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="login-container">
    <div class="logo">
        <img src="{{ asset('images/logo/logo-maester.webp') }}" alt="Logo" class="logo-image">
        <h1 class="logo-text">broadcast Platform</h1>
    </div>
    
    @if ($errors->any())
        <div class="alert">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ $errors->first('email') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
            @if ($errors->has('email'))
                <div class="field-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>{{ $errors->first('email') }}</span>
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            @if ($errors->has('password'))
                <div class="field-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>{{ $errors->first('password') }}</span>
                </div>
            @endif
        </div>

        <button type="submit">Sign In</button>
    </form>
</div>
@endsection

@push('styles')
<style>
    .login-container {
        background: transparent;
        padding: 48px;
        border-radius: 20px;
        width: 100%;
        max-width: 420px;
        position: relative;
        z-index: 1;
    }
    .logo {
        text-align: center;
        margin-bottom: 32px;
    }
    .logo-image {
        width: 80px;
        height: 80px;
        object-fit: contain;
        margin-bottom: 16px;
    }
    .logo-text {
        font-size: 28px;
        font-weight: 800;
        color: #1a1a2e;
        letter-spacing: -0.5px;
    }
    .form-group {
        margin-bottom: 24px;
    }
    label {
        display: block;
        margin-bottom: 10px;
        color: #1a1a2e;
        font-weight: 600;
        font-size: 14px;
    }
    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 16px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 15px;
        transition: all 0.3s ease;
        background: #f9fafb;
        font-family: inherit;
    }
    input[type="email"]:hover,
    input[type="password"]:hover {
        border-color: #d1d5db;
        background: white;
    }
    input:focus {
        outline: none;
        border-color: #f97316;
        background: white;
        box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.1);
    }
    button {
        width: 100%;
        padding: 16px;
        background: white;
        color: #f97316;
        border: 2px solid #f97316;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 8px;
        position: relative;
        overflow: hidden;
    }
    button::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(249, 115, 22, 0.1), transparent);
        transition: left 0.5s ease;
    }
    button:hover {
        background: #fff7ed;
        color: #c2410c;
        border-color: #c2410c;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(194, 65, 12, 0.25);
    }
    button:hover::before {
        left: 100%;
    }
    .alert {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        color: #721c24;
        padding: 16px;
        border-radius: 12px;
        margin-bottom: 24px;
        font-size: 14px;
        border: 1px solid #f5c6cb;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .alert i {
        font-size: 20px;
    }
    .field-error {
        color: #ef4444;
        font-size: 13px;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
        background: #fef2f2;
        padding: 10px 12px;
        border-radius: 8px;
    }
    .field-error i {
        font-size: 14px;
    }
</style>
@endpush
