<?php

use function Livewire\Volt\{state, mount, on};
use Illuminate\Notifications\DatabaseNotification;
use App\Models\User;

state('user');

mount(function () {
    $this->user =  User::find(auth()->user()->id);
});

$markAsRead = function ($notification) {
    DatabaseNotification::find($notification['id'])->markAsRead();
    if (auth()->user()->roles()->get()->first()->name == 'staff'){
        $this->redirect(route('letter.mail-box'), navigate: true);
    }elseif(auth()->user()->roles()->get()->first()->name == 'rt'){
        $this->redirect(route('neighborhood-association.letter'), navigate: true);
    }elseif(auth()->user()->roles()->get()->first()->name == 'warga'){
        $this->redirect(route('citizen.mail-box'), navigate: true);
    }
}

?>

<div class="flex items-center ms-3" x-data="{ open: false }">
    <button x-on:click="open = !open" class="relative inline-flex items-center text-sm font-medium text-center text-gray-500 hover:text-gray-900 focus:outline-none dark:hover:text-white dark:text-gray-400" type="button" wire:poll.keep-alive>
        <svg x-cloak class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="#c29843" viewBox="0 0 14 20">
            <path d="M12.133 10.632v-1.8A5.406 5.406 0 0 0 7.979 3.57.946.946 0 0 0 8 3.464V1.1a1 1 0 0 0-2 0v2.364a.946.946 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C1.867 13.018 0 13.614 0 14.807 0 15.4 0 16 .538 16h12.924C14 16 14 15.4 14 14.807c0-1.193-1.867-1.789-1.867-4.175ZM3.823 17a3.453 3.453 0 0 0 6.354 0H3.823Z"/>
        </svg>
        @if($this->user->unreadNotifications->count() > 0)
            <div class="absolute block w-3 h-3 bg-red-500 border-2 border-white rounded-full -top-0.5 start-2.5 dark:border-gray-900"></div>
        @endif
    </button>
    <div x-show="open"
         x-cloak
         x-on:click.outside="open = false"
          x-transition:enter="transition ease-out duration-100 transform"
          x-transition:enter-start="opacity-0 scale-95"
          x-transition:enter-end="opacity-100 scale-100"
          x-transition:leave="transition ease-in duration-75 transform"
          x-transition:leave-start="opacity-100 scale-100"
          x-transition:leave-end="opacity-0 scale-95" class="z-20 absolute right-2 top-[45px] w-full max-w-sm bg-white divide-y divide-gray-100 rounded-lg shadow-sm dark:bg-gray-800 dark:divide-gray-800">
        <div class="block px-4 py-2 font-medium text-center text-gray-700 rounded-t-lg bg-gray-300 dark:bg-gray-700 dark:text-white">
            Notifikasi
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-800" wire:poll.keep-alive>
            @if($this->user->unreadNotifications->count())
                @foreach($this->user->unreadNotifications as $notification)
                    <a x-on:click="open = false" wire:click="markAsRead({{ $notification }})" class="cursor-pointer flex px-4 py-3 hover:bg-gray-200 bg-gray-100 dark:hover:bg-gray-600 dark:bg-gray-700">
                        <div class="shrink-0">
                            <img class="rounded-full w-11 h-11" src="{{ $notification->data['profile_photo'] ? asset($notification->data['profile_photo']) : 'https://ui-avatars.com/api/?name=' . $notification->data['name'] }}" alt="Foto Proile">
                            <div class="absolute flex items-center justify-center w-5 h-5 ms-6 -mt-5 bg-blue-600 border border-white rounded-full dark:border-gray-800">
                                <svg class="w-2 h-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                                    <path d="M1 18h16a1 1 0 0 0 1-1v-6h-4.439a.99.99 0 0 0-.908.6 3.978 3.978 0 0 1-7.306 0 .99.99 0 0 0-.908-.6H0v6a1 1 0 0 0 1 1Z"/>
                                    <path d="M4.439 9a2.99 2.99 0 0 1 2.742 1.8 1.977 1.977 0 0 0 3.638 0A2.99 2.99 0 0 1 13.561 9H17.8L15.977.783A1 1 0 0 0 15 0H3a1 1 0 0 0-.977.783L.2 9h4.239Z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="w-full ps-3">
                            <div class="text-gray-500 text-sm mb-1.5 dark:text-gray-400">{{ $notification->data['message'] ?? '' }} oleh {{ $notification->data['name'] }} :
                                <span class="font-bold text-gray-900 dark:text-white">"{{ $notification->data['letter'] }}"</span>
                            </div>
                            <div class="text-xs text-blue-600 dark:text-blue-500">{{ $notification->created_at->diffForHumans() }}</div>
                        </div>
                    </a>
                @endforeach
            @else
                <a href="#" class="flex px-4 py-3 hover:bg-gray-200 bg-gray-100 dark:hover:bg-gray-600 dark:bg-gray-700">
                    <div class="w-full ps-3">
                        <div class="text-gray-500 text-sm my-4 dark:text-gray-100 text-center">
                            Tidak Ada Notifikasi
                        </div>
                    </div>
                </a>
            @endif
        </div>
        <a href="{{ $this->user->unreadNotifications->count() <= 0 ? '#' : route('notification') }}" wire class="block py-2 text-sm font-medium text-center text-gray-900 rounded-b-lg bg-gray-300 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white {{$this->user->unreadNotifications->count() <= 0 ? 'cursor-not-allowed opacity-50 disabled' : ''}}">
            <div class="inline-flex items-center ">
                <svg class="w-4 h-4 me-2 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 14">
                    <path d="M10 0C4.612 0 0 5.336 0 7c0 1.742 3.546 7 10 7 6.454 0 10-5.258 10-7 0-1.664-4.612-7-10-7Zm0 10a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z"/>
                </svg>
                Lihat Semua
            </div>
        </a>
    </div>
</div>
