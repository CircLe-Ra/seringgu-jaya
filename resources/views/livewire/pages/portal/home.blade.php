<?php

use function Livewire\Volt\{state, layout, title, mount, computed};
use App\Models\News;
use App\Models\TagInNews;
use Carbon\Carbon;
use App\Models\NewsCategory;

layout('layouts.portal');
title('Beranda');
state(['oneNews','manyNews', 'allCategories']);
mount(function () {
    $this->oneNews = News::where('published', 'published')->latest()->first();
    $this->manyNews = News::where('published', 'published')->latest()->skip(1)->take(3)->get();
    $this->allCategories = NewsCategory::all();
});

$news = computed(function () {
    return News::where('published', 'published')->latest()->get();
})



?>
<div class="mt-16">
<section class="bg-white dark:bg-gray-800 w-full px-4">
    <div class="grid py-10 mx-auto max-w-screen-xl grid-cols-1 {{ $this->manyNews->count() >= 1 ? 'lg:grid-cols-2' : 'lg:grid-cols-1' }} gap-10">
        <div>
            <div class="rounded-xl overflow-hidden max-h-96 ">
                @if ($this->oneNews->image ?? false)
                <img class="object-cover " src="{{ asset('storage/' . $this->oneNews->image) }}" alt="News Image" />
                @endif
            </div>
            <div class="my-6 gap-2 flex flex-wrap">
                @if ($this->oneNews->categories ?? false)
                    @foreach($this->oneNews->categories as $newsCategory)
                        <span class="px-3 py-1 text-sm bg-blue-700 text-gray-50 rounded"> {{ $newsCategory->name }} </span>
                    @endforeach
                @endif
            </div>
            <a href="#" class="hover:underline text-2xl font-bold text-gray-900 dark:text-white text-justify"> {!! Str::of($this->oneNews->title ?? '')->limit(60) !!}</a>
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
                {{ str()->limit(strip_tags($this->oneNews->content ?? ''),100,'...') }}
            </div>
            <div class="pt-4">
                <a href="#" class="text-blue-600 dark:text-blue-500 hover:underline text-lg font-semibold ">Baca Selengkapnya
                    <svg aria-hidden="true" class="w-4 h-4 -mt-1 ml-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        </div>
        <div>
            @foreach($this->manyNews as $news)
                <div class="mb-8">
                    <div class="mb-6 gap-2 flex flex-wrap">
                        @foreach($news->categories as $newsCategory)
                            <span class="px-3 py-1 text-sm bg-blue-700 text-gray-50 rounded"> {{ $newsCategory->name }} </span>
                        @endforeach
                    </div>
                    <a href="#" class="hover:underline text-2xl font-bold text-gray-900 dark:text-white text-justify"> {!! Str::of($news->title)->limit(45) !!}</a>
                    <div class="text-gray-700 dark:text-gray-400 text-justify text-lg py-4">
                        {{ str()->limit(strip_tags($news->content),100,'...') }}
                    </div>
                    <div class="">
                        <a href="#" class="text-blue-600 dark:text-blue-500 hover:underline text-lg font-semibold ">Baca Selengkapnya
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
            <button class="bg-gray-200 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-full text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 text-nowrap border border-blue-600 text-blue-600 dark:text-blue-500 dark:border-blue-500">
              Semua Kategori
            </button>
            @foreach($this->allCategories as $category)
                <button class="text-gray-900 bg-gray-200 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-full text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 text-nowrap ">
                    {{ $category->name }}
                </button>
            @endforeach
        @endif
    </div>
</section>
<section class="min-h-screen bg-gray-100 dark:bg-gray-900 w-full px-4">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-screen-xl mx-auto py-8 ">
        <div class="flex flex-col gap-8">
            @foreach($this->news as $news)
                <div class="flex gap-4">
                    <div class="rounded-xl overflow-hidden max-w-64 max-h-full">
                        @if ($news->image ?? false)
                            <img class="object-cover" src="{{ asset('storage/' . $news->image) }}" alt="News Image" />
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
                        <a href="#" class="hover:underline text-2xl font-bold text-gray-900 dark:text-white text-justify"> {!! Str::of($news->title ?? '')->limit(25) !!}</a>
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
                            {{ str()->limit(strip_tags($news->content ?? ''),100,'...') }}
                        </div>
                        <div class="pt-4 flex justify-end">
                            <a href="#" class="text-blue-600 dark:text-blue-500 hover:underline text-lg font-semibold ">Baca Selengkapnya
                                <svg aria-hidden="true" class="w-4 h-4 -mt-1 ml-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
</div>
