@extends('layouts.auth-app')

@section('title', 'Forgot Password')
@section('auth-title', 'Reset Your Password')
@section('auth-footer', 'We will send you a password reset link')

@section('auth-links')
    <div class="d-flex justify-content-center gap-4">
        @if(Route::has('login'))
            <a href="{{ route('login') }}">Back to Login</a>
        @endif
        @if(Route::has('register'))
            <a href="{{ route('register') }}">Create Account</a>
        @endif
    </div>
@endsection

@section('content')
@if(session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form action="{{ route('password.email') }}" method="POST">
    @csrf
    
    <div class="mb-4">
        <p class="text-gray-600 text-center mb-3">
            Enter your email address and we'll send you a link to reset your password.
        </p>
    </div>

    <div class="mb-4">
        <label for="email" class="form-label fw-semibold">Email Address</label>
        <input type="email" 
               name="email" 
               class="form-control" 
               placeholder="Enter your registered email" 
               value="{{ old('email') }}" 
               required
               autofocus>
    </div>

    <button type="submit" class="btn btn-primary w-100 py-2 mb-3 fw-semibold">
        <i class="bi bi-envelope me-2"></i>Send Reset Link
    </button>

    <div class="text-center">
        <small class="text-muted">
            Remember your password? 
            <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">Sign in here</a>
        </small>
    </div>
</form>
@endsection