@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div class="coming-soon-container">
    <h1 class="coming-soon-text">Coming Soon</h1>
</div>
@endsection

@push('styles')
<style>
    .coming-soon-container {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 60vh;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        padding: 40px;
    }
    .coming-soon-text {
        font-size: 48px;
        font-weight: 700;
        color: #1a1a2e;
        letter-spacing: 2px;
        text-transform: uppercase;
    }
</style>
@endpush
