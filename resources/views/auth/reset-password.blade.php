@extends('layouts.guest')

@section('title', 'Reset Password')

@section('content')
    <div class="max-w-md mx-auto bg-white shadow-lg rounded-lg p-6">
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">{{ __('Reset Password') }}</h1>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div class="mb-6">
                <x-input-label for="email" :value="__('Email')" class="block text-gray-700 font-semibold"/>
                <x-text-input id="email" class="block w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-500" type="email" name="email" :value="old('email', $request->email)" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500" />
            </div>

            <!-- Password -->
            <div class="mb-6">
                <x-input-label for="password" :value="__('Password')" class="block text-gray-700 font-semibold"/>
                <x-text-input id="password" class="block w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-500" type="password" name="password" required />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500" />
            </div>

            <!-- Confirm Password -->
            <div class="mb-6">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="block text-gray-700 font-semibold"/>
                <x-text-input id="password_confirmation" class="block w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-500" type="password" name="password_confirmation" required />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-500" />
            </div>

            <div class="flex justify-center">
                <x-primary-button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg">
                    {{ __('Reset Password') }}
                </x-primary-button>
            </div>
        </form>
    </div>
@endsection
