@extends('layouts.guest')

@section('title', 'Register')

@section('content')
<div class="card shadow-lg border-0 rounded-lg mt-5">
    <div class="card-header text-center bg-dark text-white rounded-top">
        <h4>{{ __('Register') }}</h4>
    </div>
    <div class="card-body p-4">
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="mb-4">
                <label for="name" class="form-label">{{ __('Name') }}</label>
                <input id="name" class="form-control form-control-lg" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                @error('name')
                    <span class="text-danger mt-2">{{ $message }}</span>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="form-label">{{ __('Email') }}</label>
                <input id="email" class="form-control form-control-lg" type="email" name="email" :value="old('email')" required />
                @error('email')
                    <span class="text-danger mt-2">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="form-label">{{ __('Password') }}</label>
                <input id="password" class="form-control form-control-lg" type="password" name="password" required autocomplete="new-password" />
                @error('password')
                    <span class="text-danger mt-2">{{ $message }}</span>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                <input id="password_confirmation" class="form-control form-control-lg" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-lg btn-dark px-4">
                    {{ __('Register') }}
                </button>
            </div>
        </form>
    </div>
    <div class="card-footer text-center py-3">
        <div class="small">
            <a href="{{ route('login') }}" class="text-dark">{{ __('Already have an account? Login') }}</a>
        </div>
    </div>
</div>
@endsection
