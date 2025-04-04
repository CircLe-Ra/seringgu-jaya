<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

use function Livewire\Volt\layout;
use function Livewire\Volt\rules;
use function Livewire\Volt\state;

layout('layouts.guest');

state([
    'email' => '',
    'password' => '',
    'password_confirmation' => ''
]);

rules([
    'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
    'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
]);

$register = function () {
    $validated = $this->validate();

    $validated['password'] = Hash::make($validated['password']);

    event(new Registered($user = User::create($validated)));

    Auth::login($user);

    $this->redirect('/', navigate: true);
};

?>


<div>
    <div class="min-w-96 bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="text-xl text-center font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                    {{ __('Create an account') }}
                </h1>
                <form class="space-y-4 md:space-y-6" wire:submit="register">
                    <div>
                        <x-ui.input
                            :label="__('Email')"
                            wire:model="email"
                            id="email"
                            type="email"
                            name="email"
                            required autocomplete="email"
                            placeholder="email@example.com" />
                    </div>
                    <div>
                        <x-ui.input
                            :label="__('Password')"
                            wire:model="password"
                            id="password"
                            type="password"
                            name="password"
                            required
                            placeholder="••••••••"
                            autocomplete="new-password" />
                    </div>
                    <div>
                        <x-ui.input
                            :label="__('Confirm Password')"
                            wire:model="password_confirmation"
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            required
                            placeholder="••••••••"
                            autocomplete="new-password" />
                    </div>
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="terms" aria-describedby="terms" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-primary-600 dark:ring-offset-gray-800" required="">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="terms" class="font-light text-gray-500 dark:text-gray-300">{{__('I accept the')}} <a class="font-medium text-primary-600 hover:underline dark:text-primary-500" href="#">{{__('Terms and Conditions')}}</a></label>
                        </div>
                    </div>
                    <button type="submit" class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800" wire:loading.attr="disabled" wire:loading.class="cursor-not-allowed">
                        <x-ui.loading class="text-black w-4 h-4 me-1" :name="__('Create an account')" />
                    </button>
                    <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                        {{  __('Already have an account?') }} <a class="font-medium text-primary-600 hover:underline dark:text-primary-500" href="{{ route('login') }}" wire:navigate>{{ __('Login here') }}</a>
                    </p>
                </form>
            </div>
        </div>
</div>
