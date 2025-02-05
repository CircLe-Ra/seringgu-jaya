<?php

use function Livewire\Volt\{state, layout, title, mount};
use App\Models\News;

layout('layouts.portal');
title('Baca Berita');
state(['slug' => fn($slug) => $slug]);
state(['news']);
mount(function () {
    $this->news = News::where('slug', $this->slug)->first();
});

?>

<div class="mt-16">
    <section class=" bg-white dark:bg-gray-800 w-full px-4">
        <div class="flex gap-8 max-w-screen-xl mx-auto py-8 lg:flex-row flex-col">
            <div class="w-full text-center max-w-3xl mx-auto">
                <h1 class="text-3xl  font-bold leading-tight tracking-tight text-gray-900 md:text-4xl dark:text-white">
                {{ $this->news->title }}
                </h1>
                @if($this->news->image)
                    <div class="h-96 overflow-hidden rounded-xl my-5    ">
                    <img src="{{ asset('storage/' . $this->news->image) }}" class="object-cover" alt="News Image">
                    </div>
                @endif
                <div class="text-gray-700 dark:text-gray-400 text-justify text-lg py-4">
                    {!! $this->news->content !!}
                </div>
            </div>
        </div>
    </section>
</div>
