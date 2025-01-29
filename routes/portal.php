<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware(['auth'])->group(function () {
  Volt::route('admin/portal/news-category', 'pages.staff.portal.news-category')->name('admin.portal.news-category');
  Volt::route('admin/portal/news', 'pages.staff.portal.news')->name('admin.portal.news');
  Volt::route('admin/portal/add-news', 'pages.staff.portal.add-news')->name('admin.portal.add-news');
  Volt::route('admin/portal/trash-news', 'pages.staff.portal.trash-news')->name('admin.portal.trash-news');
  Volt::route('admin/portal/{id}/update-news', 'pages.staff.portal.update-news')->name('admin.portal.update-news');
});
