<?php

use function Livewire\Volt\{state, layout, title, mount, computed, updated, usesPagination};
use App\Models\News;
use App\Models\TagInNews;
use Carbon\Carbon;
use App\Models\NewsCategory;

layout('layouts.portal');
title('Beranda');
usesPagination();
state(['oneNews','manyNews', 'allCategories']);
state(['category' => '', 'search' => '', 'show' => 5])->url();

mount(function () {
    $this->oneNews = News::where('published', 'published')->latest()->first();
    $this->manyNews = News::where('published', 'published')->latest()->skip(1)->take(3)->get();
    $this->allCategories = NewsCategory::all();
});

$news = computed(function () {
    return News::where('published', 'published')
        ->where(function ($query) {
            $query->where('title', 'like', '%' . $this->search . '%');
        })
        ->whereHas('categories', function ($query) {
            $query->where('news_category_id', 'like', '%' . $this->category . '%');
        })
        ->latest()
        ->paginate($this->show, pageName: 'news-portal-page');
});

$showAllCategories = function () {
    $this->category = null;
};

$showCategory = function ($id) {
    $this->category = $id;
};

?>


@persist('scrollbar')
    <div class="mt-16" wire:scroll>
        <section class="bg-white dark:bg-gray-800 w-full px-4">
            <div class="grid py-10 mx-auto max-w-screen-xl grid-cols-1 {{ $this->manyNews->count() >= 1 ? 'lg:grid-cols-2' : 'lg:grid-cols-1' }} gap-10">
                <div class="">
                    <div class=" overflow-hidden max-w-full h-96 rounded-xl">
                        @if ($this->oneNews->image ?? false)
                            <img class="object-cover rounded-xl " src="{{ asset('storage/' . $this->oneNews->image) }}" alt="News Image" />
                        @endif
                    </div>
                    <div class="my-6 gap-2 flex flex-wrap">
                        @if ($this->oneNews->categories ?? false)
                            @foreach($this->oneNews->categories as $newsCategory)
                                <span class="px-3 py-1 text-sm bg-blue-700 text-gray-50 rounded"> {{ $newsCategory->name }} </span>
                            @endforeach
                        @endif
                    </div>
                    @if($this->manyNews->count() >= 1)
                    <a href="{{ route('portal.read-news', ['slug' => $this->oneNews->slug]) }}" wire:navigate class="hover:underline text-2xl font-bold text-gray-900 dark:text-white text-justify"> {!! Str::of($this->oneNews->title ?? '')->limit(60) !!}</a>
                    <div class="flex items-center py-4 text-gray-900 whitespace-nowrap dark:text-white">
                        @if ($this->oneNews->user->profile_path ?? false)
                            <img class="rounded-full w-8 h-8" src="{{ $this->oneNews->user->profile_path != null ? asset('storage/' . $this->oneNews->user->profile_path) : 'https://ui-avatars.com/api/?name=' . $this->oneNews->user->name }}" alt="Foto Proile" />
                        @elseif($this->oneNews->user->name ?? false)
                        <img class="rounded-full w-8 h-8" src="{{ 'https://ui-avatars.com/api/?name=' . $this->oneNews->user->name }}" alt="Foto Proile" />
                        @endif
                        <div class="ps-3">
                            <div class="text-base font-semibold">{{ ucfirst($this->oneNews->user->name ?? '') }}</div>
                            <div class="font-normal text-gray-500">Dipublikasikan, {{ Carbon::parse($this->oneNews->created_at ?? '')->locale('id')->isoFormat('D MMMM Y') }}</div>
                        </div>
                    </div>
                    <div class="text-gray-700 dark:text-gray-400 text-justify text-lg">
                        {{ str()->limit(str_replace('&nbsp;', ' ', strip_tags($this->oneNews->content ?? '')),100,'...') }}
                    </div>
                    <div class="pt-4">
                        <a wire:navigate href="{{ route('portal.read-news', ['slug' => $this->oneNews->slug]) }}" class="text-blue-600 dark:text-blue-500 hover:underline text-lg font-semibold ">Baca Selengkapnya
                            <svg aria-hidden="true" class="w-4 h-4 -mt-1 ml-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                    @endif
                </div>
                <div>
                    @foreach($this->manyNews as $news)
                        <div class="mb-8">
                            <div class="mb-6 gap-2 flex flex-wrap">
                                @foreach($news->categories as $newsCategory)
                                    <span class="px-3 py-1 text-sm bg-blue-700 text-gray-50 rounded"> {{ $newsCategory->name }} </span>
                                @endforeach
                            </div>
                            <a wire:navigate href="{{ route('portal.read-news', ['slug' => $news->slug]) }}" class="hover:underline text-2xl font-bold text-gray-900 dark:text-white text-justify">{{ str()->limit(str_replace('&nbsp;', ' ', strip_tags($news->title ?? '')),45,'...') }}</a>
                            <div class="text-gray-700 dark:text-gray-400 text-justify text-lg py-4">
                                {{ str()->limit(str_replace('&nbsp;', ' ', strip_tags($news->content ?? '')),100,'...') }}
                            </div>
                            <div class="">
                                <a wire:navigate href="{{ route('portal.read-news', ['slug' => $news->slug]) }}" class="text-blue-600 dark:text-blue-500 hover:underline text-lg font-semibold ">Baca Selengkapnya
                                    <svg aria-hidden="true" class="w-4 h-4 -mt-1 ml-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        <section class="bg-gray-100 dark:bg-gray-900 border-t border-b w-full border-gray-200 dark:border-gray-700 px-4">
            <div class="mx-auto max-w-screen-xl pt-5 pb-2 flex flex-nowrap scrollbar-thumb-gray-300 scrollbar-track-gray-100  overflow-x-scroll scrollbar-thin dark:scrollbar-track-gray-900 dark:scrollbar-thumb-gray-700 items-center">
                @if($this->allCategories->count() > 0)
                    <button wire:click="showAllCategories" class="bg-gray-200 focus:outline-none hover:bg-gray-100 font-medium rounded-full text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 text-nowrap {{ $this->category == null ? ' border border-blue-600 text-blue-600 dark:text-blue-500 dark:border-blue-500' : 'dark:text-white' }}">
                      Semua Kategori
                    </button>
                    @foreach($this->allCategories as $category)
                        <button wire:click="showCategory({{ $category->id }})" class="bg-gray-200 focus:outline-none hover:bg-gray-100 font-medium rounded-full text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 text-nowrap {{ $this->category == $category->id ? ' border border-blue-600 text-blue-600 dark:text-blue-500 dark:border-blue-500' : 'dark:text-white' }}">
                            {{ $category->name }}
                        </button>
                    @endforeach
                @endif
            </div>
        </section>
        <section class=" bg-gray-100 dark:bg-gray-900 w-full px-4">
            <div class="flex gap-8 max-w-screen-xl mx-auto py-8 lg:flex-row flex-col">
                <div class="flex flex-col gap-8 {{ $this->news->count() > 0 ? '' : 'w-full' }}">
                    @if($this->news->count() > 0)
                        @foreach($this->news as $news)
                            <div class="flex gap-4 items-center">
                                <div class="rounded-xl overflow-hidden max-w-64 max-h-48 ">
                                    @if ($news->image ?? false)
                                        <img class="object-cover rounded-xl" src="{{ asset('storage/' . $news->image) }}" alt="News Image" />
                                    @endif
                                </div>
                                <div class="">
                                    <div class="gap-2 pb-2 flex flex-wrap">
                                        @if ($news->categories ?? false)
                                            @foreach($news->categories as $newsCategory)
                                                <span class="px-3 py-1 text-sm bg-blue-700 text-gray-50 rounded"> {{ $newsCategory->name }} </span>
                                            @endforeach
                                        @endif
                                    </div>
                                    <a wire:navigate href="{{ route('portal.read-news', ['slug' => $news->slug]) }}" class="hover:underline text-2xl font-bold text-gray-900 dark:text-white text-justify">{{ str()->limit(str_replace('&nbsp;', ' ', strip_tags($news->title ?? '')),40,'...') }}</a>
                                    <div class="flex items-center pb-2 text-gray-900 whitespace-nowrap dark:text-white">
                                        @if ($news->user->profile_path ?? false)
                                            <img class="rounded-full w-8 h-8" src="{{ $news->user->profile_path != null ? asset('storage/' . $news->user->profile_path) : 'https://ui-avatars.com/api/?name=' . $news->user->name }}" alt="Foto Proile" />
                                        @elseif($news->user->name ?? false)
                                            <img class="rounded-full w-8 h-8" src="{{ 'https://ui-avatars.com/api/?name=' . $news->user->name }}" alt="Foto Proile" />
                                        @endif
                                        <div class="ps-3">
                                            <div class="text-base font-semibold">{{ ucfirst($news->user->name ?? '') }}</div>
                                            <div class="font-normal text-gray-500">Dipublikasikan, {{ Carbon::parse($news->created_at ?? '')->locale('id')->isoFormat('D MMMM Y') }}</div>
                                        </div>
                                    </div>
                                    <div class="text-gray-700 dark:text-gray-400 text-justify text-lg">
                                        {{ str()->limit(str_replace('&nbsp;', ' ', strip_tags($news->content ?? '')),100,'...') }}
                                    </div>
                                    <div class="pt-4 flex justify-end">
                                        <a wire:navigate href="{{ route('portal.read-news', ['slug' => $news->slug]) }}" class="text-blue-600 dark:text-blue-500 hover:underline text-lg font-semibold ">Baca Selengkapnya
                                            <svg aria-hidden="true" class="w-4 h-4 -mt-1 ml-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-gray-500 dark:text-gray-400 text-lg text-center">
                            Berita Tidak Ditemukan.
                        </div>
                    @endif
                </div>
                <div class="lg:w-2/5 w-full lg:order-last -order-1 ">
                    <div class="bg-gray-50 rounded-xl dark:bg-gray-800 dark:border-gray-700 border border-gray-200 shadow-xl p-4 sticky top-24">
                        <form class="flex items-center max-w-sm mx-auto">
                            <label for="simple-search" class="sr-only">Cari</label>
                            <div class="relative w-full">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8zM6 4h7l5 5v8.58l-1.84-1.84a4.99 4.99 0 0 0-.64-6.28A4.96 4.96 0 0 0 12 8a5 5 0 0 0-3.53 1.46a4.98 4.98 0 0 0 0 7.05a4.98 4.98 0 0 0 6.28.63L17.6 20H6zm8.11 11.1c-.56.56-1.31.88-2.11.88s-1.55-.31-2.11-.88c-.56-.56-.88-1.31-.88-2.11s.31-1.55.88-2.11c.56-.57 1.31-.88 2.11-.88s1.55.31 2.11.88c.56.56.88 1.31.88 2.11s-.31 1.55-.88 2.11"/></svg>
                                </div>
                                <input type="text" wire:model.live="search" id="simple-search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Cari Bedasarkan Judul..." required />
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </section>
        @if($this->news->count() > 5)
        <section class="bg-gray-100 dark:bg-gray-900 border-t border-b w-full border-gray-200 dark:border-gray-700 px-4" >
            <div class="mx-auto max-w-screen-xl py-6 flex flex-nowrap items-center justify-center">
                {{ $this->news->links('livewire.pagination-portal') }}
            </div>
        </section>
        @endif
    </div>
@endpersist
