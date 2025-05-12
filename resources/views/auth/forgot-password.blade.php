@extends('layouts.guest')
@section('title', 'Forgot your password | New Bie Management System')
@section('content')

<div class="container">
    <h1 class="text-center my-4">New Bie Management System</h1>
    <p class="mb-4 text-sm text-gray-600">
        Forgot your password? No problem. <br/>Just let us know your email address. <br/>we will email you a password reset link that will allow you to choose a new one.
    </p>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</div>

@endsection