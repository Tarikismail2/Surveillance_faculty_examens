@extends('layouts.guest')

@section('title', 'Confirm Password')

@section('content')
    <div class="max-w-md mx-auto bg-white shadow-lg rounded-lg p-6">
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">{{ __('Confirm Your Password') }}</h1>

        <div class="mb-4 text-gray-700 text-sm">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </div>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <!-- Password -->
            <div class="mb-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block w-full" type="password" name="password" required autofocus autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500" />
            </div>

            <div class="flex justify-end mt-6">
                <x-primary-button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg">
                    {{ __('Confirm') }}
                </x-primary-button>
            </div>
        </form>
    </div>
@endsection
