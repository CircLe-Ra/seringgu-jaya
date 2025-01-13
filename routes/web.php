<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth'])->group(function () {
    Volt::route('master-data/citizen-association', 'pages.staff.master-data.citizen-association')->name('master-data.citizen-association');
    Volt::route('master-data/neighborhood-association', 'pages.staff.master-data.neighborhood-association')->name('master-data.neighborhood-association');
});

require __DIR__.'/auth.php';
