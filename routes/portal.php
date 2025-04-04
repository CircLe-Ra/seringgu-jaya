<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/portal', 'pages.portal.home')->name('portal.home');
Volt::route('portal/read-news/{slug}', 'pages.portal.read-news')->name('portal.read-news');
Volt::route('portal/vision-mission', 'pages.portal.vision-mission')->name('portal.vision-mission');
Volt::route('portal/history', 'pages.portal.history')->name('portal.history');
Volt::route('portal/geography', 'pages.portal.geography')->name('portal.geography');
Volt::route('portal/structure-organization', 'pages.portal.structure')->name('portal.structure');
Route::middleware(['auth'])->group(function () {
  Volt::route('staff/portal/news-category', 'pages.staff.portal.news-category')->name('admin.portal.news-category');
  Volt::route('staff/portal/news', 'pages.staff.portal.news')->name('admin.portal.news');
  Volt::route('staff/portal/add-news', 'pages.staff.portal.add-news')->name('admin.portal.add-news');
  Volt::route('staff/portal/trash-news', 'pages.staff.portal.trash-news')->name('admin.portal.trash-news');
  Volt::route('staff/portal/{id}/update-news', 'pages.staff.portal.update-news')->name('admin.portal.update-news');
  Volt::route('staff/portal/vision-mission', 'pages.staff.portal.vision-mission')->name('staff.portal.vision-mission');
  Volt::route('staff/portal/history', 'pages.staff.portal.history')->name('staff.portal.history');
  Volt::route('staff/portal/geography', 'pages.staff.portal.geography')->name('staff.portal.geography');
  Volt::route('staff/portal/structure', 'pages.staff.portal.structure')->name('staff.portal.structure');
});
