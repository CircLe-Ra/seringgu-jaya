<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    if(!Auth::check()){
        return redirect()->route('login');
    }
    if(Auth::user()->roles()->first()->name == 'staff' || Auth::user()->roles()->first()->name == 'lurah'){
        return redirect()->route('staff.dashboard');
    }else if(Auth::user()->roles()->first()->name == 'rt'){
        return redirect()->route('neighborhood-association.dashboard');
    }else if(Auth::user()->roles()->first()->name == 'warga'){
        return redirect()->route('citizen.information');
    }
});

Route::middleware(['auth'])->group(function () {

    Route::view('profile', 'profile')->name('profile');
    //staff
    Route::middleware(['role:staff'])->group(function () {
        Volt::route('staff/dashboard', 'pages.staff.dashboard')->name('staff.dashboard');
        //master-data
        Volt::route('master-data/education', 'pages.staff.master-data.education')->name('master-data.education');
        Volt::route('master-data/religion', 'pages.staff.master-data.religion')->name('master-data.religion');
        Volt::route('master-data/blood-group', 'pages.staff.master-data.blood-group')->name('master-data.blood-group');
        Volt::route('master-data/employment', 'pages.staff.master-data.employment')->name('master-data.employment');
        Volt::route('master-data/user', 'pages.staff.master-data.user')->name('master-data.user');
        Volt::route('master-data/role', 'pages.staff.master-data.role')->name('master-data.role');
        Volt::route('master-data/letter-type', 'pages.staff.master-data.letter-type')->name('master-data.letter-type');
        //Letter
        Volt::route('citizen-association', 'pages.staff.master-data.citizen-association')->name('citizen-association');
        Volt::route('neighborhood-association', 'pages.staff.master-data.neighborhood-association')->name('neighborhood-association');
        Volt::route('letter/mail-box', 'pages.staff.letter.mail-box')->name('letter.mail-box');
        //portal
        //Volt::route('master-data/role', 'pages.staff.master-data.role')->name('master-data.role');
        //leader
        Volt::route('report', 'pages.leader.report')->name('report');
    });

    //RT
    Route::middleware(['role:rt'])->group(function () {
        Volt::route('neighborhood-association/dashboard', 'pages.neighborhood-association.dashboard')->name('neighborhood-association.dashboard');
        Volt::route('neighborhood-association/inhabitant', 'pages.neighborhood-association.inhabitant')->name('neighborhood-association.inhabitant');
        Volt::route('neighborhood-association/inhabitant-detail/{id}', 'pages.neighborhood-association.inhabitant-detail')->name('neighborhood-association.inhabitant-detail');
        Volt::route('neighborhood-association/letter', 'pages.neighborhood-association.letter')->name('neighborhood-association.letter');
        Volt::route('neighborhood-association/trash-letter', 'pages.neighborhood-association.trash-letter')->name('neighborhood-association.trash-letter');
    });

    //citizen
    Route::middleware(['role:warga'])->group(function () {
        Volt::route('citizen/information', 'pages.citizen.information')->name('citizen.information');
        Volt::route('citizen/mail-box', 'pages.citizen.mail-box')->name('citizen.mail-box');
    });

    Volt::route('notification', 'pages.notification')->name('notification');

});

require __DIR__.'/auth.php';
require __DIR__.'/portal.php';
