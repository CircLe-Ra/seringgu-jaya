<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', 'pages.portal.home')->name('portal.home');

Route::middleware(['auth'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('profile', 'profile')->name('profile');

    //staff
    //master-data
    Volt::route('master-data/citizen-association', 'pages.staff.master-data.citizen-association')->name('master-data.citizen-association');
    Volt::route('master-data/neighborhood-association', 'pages.staff.master-data.neighborhood-association')->name('master-data.neighborhood-association');
    Volt::route('master-data/education', 'pages.staff.master-data.education')->name('master-data.education');
    Volt::route('master-data/religion', 'pages.staff.master-data.religion')->name('master-data.religion');
    Volt::route('master-data/blood-group', 'pages.staff.master-data.blood-group')->name('master-data.blood-group');
    Volt::route('master-data/employment', 'pages.staff.master-data.employment')->name('master-data.employment');
    Volt::route('master-data/user', 'pages.staff.master-data.user')->name('master-data.user');
    Volt::route('master-data/role', 'pages.staff.master-data.role')->name('master-data.role');
    Volt::route('master-data/letter-type', 'pages.staff.master-data.letter-type')->name('master-data.letter-type');
    //Letter
    Volt::route('letter/mail-box', 'pages.staff.letter.mail-box')->name('letter.mail-box');
    //portal
//    Volt::route('master-data/role', 'pages.staff.master-data.role')->name('master-data.role');

    //RT
    Volt::route('neighborhood-association/inhabitant', 'pages.neighborhood-association.inhabitant')->name('neighborhood-association.inhabitant');
    Volt::route('neighborhood-association/inhabitant-detail/{id}', 'pages.neighborhood-association.inhabitant-detail')->name('neighborhood-association.inhabitant-detail');
    Volt::route('neighborhood-association/letter', 'pages.neighborhood-association.letter')->name('neighborhood-association.letter');
    Volt::route('neighborhood-association/trash-letter', 'pages.neighborhood-association.trash-letter')->name('neighborhood-association.trash-letter');

    //citizen
    Volt::route('citizen/information', 'pages.citizen.information')->name('citizen.information');
    Volt::route('citizen/mail-box', 'pages.citizen.mail-box')->name('citizen.mail-box');

    Volt::route('notification', 'pages.notification')->name('notification');
});

require __DIR__.'/auth.php';
require __DIR__.'/portal.php';
