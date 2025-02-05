<?php

use function Livewire\Volt\{state, layout, title, mount};
use App\Models\Profile;

layout('layouts.portal');
title('Sejarah');
state(['data']);
mount(function () {
    $this->data = Profile::where('id', 1)->first();
});

?>

<div class="mt-16">
    <section class=" bg-white dark:bg-gray-800 w-full px-4">
        <div class="flex gap-8 max-w-screen-xl mx-auto py-8 lg:flex-row flex-col">
            <div class="w-full text-center max-w-3xl mx-auto">
                <h1 class="text-3xl  font-bold leading-tight tracking-tight text-gray-900 md:text-4xl dark:text-white">
                Sejarah Kelurahan <br /> Seringgu Jaya
                </h1>
                <div class="text-gray-700 dark:text-gray-100 text-justify text-lg py-10">
                    <div class="article">
                    {!! $this->data->history ?? 'Sejarah' !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
