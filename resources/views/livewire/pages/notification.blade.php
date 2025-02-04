<?php

use function Livewire\Volt\{state, mount, on, layout, title};
use Illuminate\Notifications\DatabaseNotification;
use App\Models\User;

layout('layouts.app');
title('Semua Notifikasi');
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

<div>
    <x-ui.breadcrumbs :crumbs="[
        ['href' => route('dashboard'), 'text' => 'Dashboard'],
        ['text' => 'Semua Notifikasi'],
    ]">
    </x-ui.breadcrumbs>
    <div class="grid-cols-1 lg:grid-cols-3 grid gap-2 ">
        <div class="col-span-3 ">
            <x-ui.card class="mt-2 w-full ">
                <x-slot name="header" class="grid grid-cols-1 lg:grid-cols-2 gap-2">
                    <div>
                        <h5 class="text-xl font-medium text-gray-900 dark:text-white">Notifikasi</h5>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Daftar notifikasi yang belum dibaca.</p>
                    </div>
                </x-slot>
                <x-ui.table thead="#, Notifikasi, Pada" :action="true" wire:poll.keep-alive>
                    @if($this->user->unreadNotifications->count())
                        @foreach($this->user->unreadNotifications as $notification)
                            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                <td class="px-6 py-4">
                                    {{ $loop->iteration }}
                                </td>
                                <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                    <img class="rounded-full w-11 h-11" src="{{ $notification->data['profile_photo'] ? asset($notification->data['profile_photo']) : 'https://ui-avatars.com/api/?name=' . $notification->data['name'] }}" alt="Foto Proile">
                                    <div class="ps-3">
                                        <div class="text-base font-semibold">{{ $notification->data['letter'] }}</div>
                                        <div class="font-normal text-gray-500">{{ $notification->data['message'] ?? '' }} oleh {{ $notification->data['name'] }}</div>
                                    </div>
                                </th>
                                <td class="px-6 py-4">
                                    {{ $notification->created_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4 text-nowrap">
                                        <x-ui.button size="xs" color="blue" wire:click="markAsRead({{ $notification }})">
                                            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"><path d="M3 13c3.6-8 14.4-8 18 0"/><path d="M12 17a3 3 0 1 1 0-6a3 3 0 0 1 0 6"/></g></svg>
                                            Baca
                                        </x-ui.button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                            <td class="px-6 py-4 text-center" colspan="8">
                                Tidak Ada Notifikasi
                            </td>
                        </tr>
                    @endif
                </x-ui.table>
            </x-ui.card>
        </div>
    </div>
</div>
