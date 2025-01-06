<?php

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use function Livewire\Volt\{state, usesFileUploads};

usesFileUploads();
state([
    'name' => fn () => auth()->user()->name,
    'email' => fn () => auth()->user()->email,
    'profile_path' => null,
]);

$updateProfileInformation = function () {
    $user = Auth::user();
    $validated = $this->validate([
        'name' => 'required|string|max:255',
        'email' => [
            'required', 'string', 'email', 'max:255', 'lowercase',
            Rule::unique(User::class)->ignore($user->id)
        ],
        'profile_path' => 'nullable|image|max:2048',
    ]);
    $user->fill(Arr::except($validated, ['profile_path']));
    if ($user->isDirty('email')) {
        $user->email_verified_at = null;
    }
    if (!empty($this->profile_path)) {
        if ($user->profile_path) {
            \Storage::disk('public')->delete($user->profile_path);
        }
        $user->profile_path = $this->profile_path->store('profile-photos', 'public');
    }
    $user->save();

    if ($this->profile_path) {
        $this->dispatch('refresh-image', path: $user->profile_path);
    }
    $this->dispatch('pond-reset');
    $this->dispatch('profile-updated', name: $user->name);
};

$sendVerification = function () {
    $user = Auth::user();

    if ($user->hasVerifiedEmail()) {
        $this->redirectIntended(default: route('dashboard', absolute: false));

        return;
    }

    $user->sendEmailVerificationNotification();

    Session::flash('status', 'verification-link-sent');
};

?>

<section >
    <header>
        <h2 class="text-lg font-medium text-gray-500 border-gray-200 dark:border-gray-700 dark:text-gray-400">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-500 border-s border-gray-200 dark:border-gray-700 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 items-center ">
        <form wire:submit="updateProfileInformation" class="mt-6 space-y-6">
            <div>
                <x-ui.input :label="__('Name')" wire:model="name" id="name" name="name" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" />
            </div>

            <div>
                <x-ui.input :label="__('Email')" wire:model="email" id="email" name="email" type="email" class="mt-1 block w-full" required autocomplete="username" />

                @if (auth()->user() instanceof MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                    <div>
                        <p class="text-sm mt-2 text-gray-800">
                            {{ __('Your email address is unverified.') }}

                            <button wire:click.prevent="sendVerification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <div>
                <x-ui.filepond :label="__('Profile Photo')" wire:model="profile_path"  />
            </div>

            <div class="flex items-center gap-4">
                <x-ui.button color="blue" size="md" submit loading-only>{{ __('Save') }}</x-ui.button>

                <x-action-message class="me-3 text-gray-600 dark:text-gray-400" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>
        @if(Auth::user()->profile_path)
            <div class="flex justify-center">
                    <img src="{{ asset('storage/' . Auth::user()->profile_path) }}" alt="Profile Photo" class="rounded-full w-40 h-40 object-cover" />
            </div>
        @endif
    </div>
</section>
