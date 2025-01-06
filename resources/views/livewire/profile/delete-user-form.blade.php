<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;

use function Livewire\Volt\{rules, state, on};

state(['password' => '']);

rules(['password' => ['required', 'string', 'current_password']]);

on(['close-modal-reset' => function ($wireModels) {
    $this->reset($wireModels);
    $this->resetErrorBag($wireModels);
}]);


$deleteUser = function (Logout $logout) {
    $this->validate();

    tap(Auth::user(), $logout(...))->delete();

    $this->redirect('/', navigate: true);
};

?>

<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 text-justify">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <x-ui.button color="red" wire:click="$dispatch('open-modal', { id: 'confirm-user-deletion'})">{{ __('Delete Account') }}</x-ui.button>

    <x-ui.modal id="confirm-user-deletion">
        <x-slot name="header">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>
        </x-slot>
        <x-slot name="content">
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>
            <div class="mt-6">
                <x-ui.input
                    wire:model="password"
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full"
                    placeholder="{{ __('Password') }}"
                    label="{{ __('Password') }}"
                />

            </div>
        </x-slot>
        <x-slot name="footer">
            <div class="mt-6 flex justify-end">
                <x-ui.button color="light" wire:click="$dispatch('close-modal', { id: 'confirm-user-deletion' })">
                    {{ __('Cancel') }}
                </x-ui.button>

                <x-ui.button color="red" class="ms-3" wire:click="deleteUser">
                    {{ __('Delete Account') }}
                </x-ui.button>
            </div>
        </x-slot>
    </x-ui.modal>
</section>
