@extends('layouts.guest')

@section('title', 'Verify Email')

@section('content')
    <div class="max-w-md mx-auto bg-white shadow-lg rounded-lg p-6">
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">{{ __('Verify Your Email Address') }}</h1>

        <div class="mb-4 text-gray-700 text-sm">
            {{ __('Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just sent to you. If you didn\'t receive the email, we will gladly send you another.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 text-green-600 font-medium">
                {{ __('A new verification link has been sent to your email address.') }}
            </div>
        @endif

        <div class="mt-6 flex justify-between items-center">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <x-primary-button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg">
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>
@endsection
