<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;

use function Livewire\Volt\form;
use function Livewire\Volt\layout;

layout('layouts.guest');

form(LoginForm::class);

$login = function () {
    $this->validate();

    $this->form->authenticate();

    Session::regenerate();

    $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
};

?>

<section>
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <div class="min-w-96 bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
        <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
            <h1 class="text-xl text-center font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                {{ __('Sign in to your account') }}
            </h1>
            <form class="space-y-4 md:space-y-6" wire:submit="login">
                <div>
                    <x-ui.input
                        :label="__('Email')"
                        wire:model="form.email"
                        id="email"
                        type="email"
                        name="email"
                        required
                        autofocus
                        placeholder="email@example.com"
                        autocomplete="username" />
                </div>
                <div>
                    <x-ui.input
                        :label="__('Password')"
                        wire:model="form.password"
                        id="password"
                        type="password"
                        name="password"
                        required
                        placeholder="••••••••"
                        autocomplete="current-password" />
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input wire:model="form.remember" id="remember" aria-describedby="remember" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-primary-600 dark:ring-offset-gray-800" >
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="remember" class="text-gray-500 dark:text-gray-300">{{ __('Remember me') }}</label>
                        </div>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" wire:navigate class="text-sm font-medium text-primary-600 hover:underline dark:text-primary-500"> {{ __('Forgot your password?') }}</a>
                    @endif
                </div>
                <button type="submit" class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800" wire:loading.attr="disabled" wire:loading.class="cursor-not-allowed">
                    <x-ui.loading class="text-black w-4 h-4 me-1" :name="__('Log in')" />
                </button>
                @if (Route::has('register'))
                    <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                        {{ __('Don’t have an account yet?') }}
                        <a href="{{ route('register') }}" class="font-medium text-primary-600 hover:underline dark:text-primary-500" wire:navigate>{{  __('Sign up') }}</a>
                    </p>
                @endif
            </form>
        </div>
    </div>
</section>
