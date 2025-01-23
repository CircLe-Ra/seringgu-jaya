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
    //staff
    Volt::route('master-data/citizen-association', 'pages.staff.master-data.citizen-association')->name('master-data.citizen-association');
    Volt::route('master-data/neighborhood-association', 'pages.staff.master-data.neighborhood-association')->name('master-data.neighborhood-association');
    Volt::route('master-data/education', 'pages.staff.master-data.education')->name('master-data.education');
    Volt::route('master-data/religion', 'pages.staff.master-data.religion')->name('master-data.religion');
    Volt::route('master-data/blood-group', 'pages.staff.master-data.blood-group')->name('master-data.blood-group');
    Volt::route('master-data/employment', 'pages.staff.master-data.employment')->name('master-data.employment');
    Volt::route('master-data/user', 'pages.staff.master-data.user')->name('master-data.user');
    Volt::route('master-data/role', 'pages.staff.master-data.role')->name('master-data.role');

    //RT
    Volt::route('neighborhood-association/inhabitant', 'pages.neighborhood-association.inhabitant')->name('neighborhood-association.inhabitant');
    Volt::route('neighborhood-association/inhabitant-detail/{id}', 'pages.neighborhood-association.inhabitant-detail')->name('neighborhood-association.inhabitant-detail');
});

require __DIR__.'/auth.php';
