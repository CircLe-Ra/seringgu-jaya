<?php

use function Livewire\Volt\{state, layout, title, mount};
use App\Models\News;
use App\Models\TagInNews;
use Carbon\Carbon;

layout('layouts.portal');
title('Beranda');
state(['oneNews','manyNews']);
mount(function () {
    $this->oneNews = News::where('published', 'published')->latest()->first();
    $this->manyNews = News::where('published', 'published')->latest()->skip(1)->take(3)->get();
});



?>
<div class="mt-16">
<section class="bg-white dark:bg-gray-800 ">
    <div class="grid py-10 mx-auto max-w-screen-xl grid-cols-1 {{ $this->manyNews->count() >= 1 ? 'lg:grid-cols-2' : 'lg:grid-cols-1' }} gap-10">
        <div>
            <div class="rounded-xl overflow-hidden max-h-96 ">
                <img class="object-cover " src="{{ asset('storage/' . $this->oneNews->image) }}" alt="News Image" />
            </div>
            <div class="my-6 gap-2 flex flex-wrap">
                @foreach($this->oneNews->categories as $newsCategory)
                    <span class="px-3 py-1 text-sm bg-blue-700 text-gray-50 rounded"> {{ $newsCategory->name }} </span>
                @endforeach
            </div>
            <a href="#" class="hover:underline text-2xl font-bold text-gray-900 dark:text-white text-justify"> {!! Str::of($this->oneNews->title)->limit(60) !!}</a>
            <div class="flex items-center py-4 text-gray-900 whitespace-nowrap dark:text-white">
                <img class="rounded-full w-8 h-8" src="{{ $this->oneNews->user->profile_path ? asset($this->oneNews->user->profile_path) : 'https://ui-avatars.com/api/?name=' . $this->oneNews->user->name }}" alt="Foto Proile" />
                <div class="ps-3">
                    <div class="text-base font-semibold">{{ ucfirst($this->oneNews->user->name) }}</div>
                    <div class="font-normal text-gray-500">Dipublikasikan, {{ Carbon::parse($this->oneNews->created_at)->locale('id')->isoFormat('D MMMM Y') }}</div>
                </div>
            </div>
            <div class="text-gray-700 dark:text-gray-400 text-justify text-lg">
                {{ str()->limit(strip_tags($this->oneNews->content),100,'...') }}
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
<section class="bg-white dark:bg-gray-800 ">

</section>
</div>
