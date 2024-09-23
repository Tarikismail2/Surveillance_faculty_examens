<!-- resources/views/auth/forgot-password.blade.php -->
@extends('layouts.guest')

@section('title', 'Forget Password')

@section('content')
    <div class="max-w-md mx-auto bg-white shadow-lg rounded-lg p-6">
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">{{ __('Forgot Password') }}</h1>

        <div class="mb-4 text-gray-600 text-sm text-center">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-6">
                <x-input-label for="email" :value="__('Email')" class="block text-gray-700 font-semibold"/>
                <x-text-input id="email" class="block w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-500" type="email" name="email" :value="old('email')" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500" />
            </div>

            <div class="flex justify-center">
                <x-primary-button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg">
                    {{ __('Send Password Reset Link') }}
                </x-primary-button>
            </div>
        </form>
    </div>
@endsection
