@extends('layouts.auth-app')

@section('title', 'Login')
@section('auth-title', 'Welcome Back')
@section('auth-footer', 'Secure Access to Loan Management System')

@section('auth-links')
    <div class="d-flex justify-content-center gap-3"> 
        @if(Route::has('register'))
            <a href="{{ route('register') }}" class="text-decoration-none">Create Account</a>
        @endif
        @if(Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="text-decoration-none">Forgot Password?</a>
        @endif
    </div>
@endsection

@section('content')
@if(session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ $errors->first() }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<form action="{{ route('login.submit') }}" method="POST">
    @csrf
    
    <div class="mb-3">
        <label for="login" class="form-label fw-semibold">Email or Username</label>
        <input type="text" 
               name="login" 
               class="form-control" 
               placeholder="Enter your email or username" 
               value="{{ old('login') }}" 
               required
               autofocus>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label fw-semibold">Password</label>
        <input type="password" 
               name="password" 
               class="form-control" 
               placeholder="Enter your password" 
               required>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check">
            <input type="checkbox" name="remember" class="form-check-input" id="remember">
            <label class="form-check-label" for="remember">Remember me</label>
        </div>
    </div>

    <button type="submit" class="btn btn-primary w-100 py-2 mb-3 fw-semibold">
        <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
    </button>

    @if(Route::has('register'))
    <div class="text-center">
        <small class="text-muted">
            Don't have an account? 
            <a href="{{ route('register') }}" class="text-decoration-none fw-semibold">Sign up here</a>
        </small>
    </div>
    @endif
</form>
@endsection