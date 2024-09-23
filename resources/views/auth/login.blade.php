<!-- resources/views/auth/login.blade.php -->
@extends('layouts.guest')

@section('title', 'Connexion')

@section('content')
<div class="card shadow-lg border-0 rounded-lg mt-5">
    <div class="card-header text-center bg-dark text-white rounded-top">
        <h4>{{ __('Login') }}</h4>
    </div>
    <div class="card-body p-4">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-4">
                <label for="email" class="form-label">{{ __('Email') }}</label>
                <input id="email" class="form-control form-control-lg" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                @error('email')
                    <span class="text-danger mt-2">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="form-label">{{ __('Password') }}</label>
                <input id="password" class="form-control form-control-lg" type="password" name="password" required autocomplete="current-password" />
                @error('password')
                    <span class="text-danger mt-2">{{ $message }}</span>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="form-check mb-4">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                <label for="remember_me" class="form-check-label">{{ __('Remember me') }}</label>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                @if (Route::has('password.request'))
                    <a class="text-decoration-none text-dark" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <button type="submit" class="btn btn-lg btn-dark px-4">
                    {{ __('Login') }}
                </button>
            </div>
        </form>
    </div>
    <div class="card-footer text-center py-3">
        <div class="small">
            <a href="{{ route('register') }}" class="text-dark">{{ __('Don\'t have an account? Register now!') }}</a>
        </div>
    </div>
</div>
@endsection
