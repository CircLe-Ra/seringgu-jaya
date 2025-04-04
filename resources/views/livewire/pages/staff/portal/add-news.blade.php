<?php

use function Livewire\Volt\{state, layout, title, mount, computed, updated, on, usesFileUploads};
use Masmerise\Toaster\Toaster;
use App\Models\NewsCategory;
use App\Models\CategoryInNews;
use App\Models\TagInNews;
use App\Models\News;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

layout('layouts.app');
title('Memposting Berita');
usesFileUploads();
state(['categories' => [], 'tags' => [], 'published' => 'private']);
state(['tag', 'category_name', 'image', 'schedule', 'title', 'slug', 'content', 'image']);

mount(function () {
});

$news_categories = computed(function () {
    return NewsCategory::all();
});

on(['close-modal-reset' => function ($wireModels) {
    $this->reset($wireModels);
    $this->resetErrorBag($wireModels);
},'trix_value_updated' => function ($value) {
    $this->content = $value;
}]);

updated(['title' => fn() => $this->slug = Str::slug($this->title)]);

$addCategory = function () {
    $this->validate([
        'category_name' => 'required|unique:news_categories,name'
    ]);
    try {
        NewsCategory::create([
            'name' => $this->category_name
        ]);
        $this->dispatch('close-modal', id: 'add-category-modal');
        $this->reset('category_name');
    } catch (\Exception $e) {
        Toaster::error($e->getMessage());
    }
};

$addTags = function () {
    if ($this->tag == null) return;
    if (in_array($this->tag, $this->tags)) {
        Toaster::error('Tag sudah ada');
        return;
    }
    $this->tags = array_merge($this->tags, [$this->tag]);
    $this->reset('tag');
};

$removeTags = function ($tag) {
    $this->tags = array_filter($this->tags, function ($item) use ($tag) {
        return $item !== $tag;
    });
};

$post = function () {
    $validator = Validator::make([
        'title' => $this->title,
        'slug' => $this->slug,
        'content' => $this->content,
        'published' => $this->published,
        'image' => $this->image,
        'categories' => $this->categories,
        'tags' => $this->tags
    ], [
        'title' => 'required',
        'slug' => 'required',
        'content' => 'required',
        'published' => 'required',
        'image' => 'nullable|image|max:2048',
        'categories' => 'required|array',
        'tags' => 'required|array'
    ]);

    if ($validator->fails()) {
        foreach ($validator->errors()->all() as $error) {
            Toaster::error($error);
        }
        return;
    }
    $validatedData = $validator->validated();
    $validatedData = Arr::except($validatedData, ['image', 'tags', 'categories']);
    try {
        if ($this->image) {
            $image = $this->image->store('news');
            $validatedData['image'] = $image;
        }
        $validatedData['user_id'] = auth()->user()->id;
        $validatedData['published_at'] = $this->published == 'published' ? now() : null;
        $news = News::create($validatedData);
        foreach ($this->categories as $category) {
            CategoryInNews::create([
                'news_id' => $news->id,
                'news_category_id' => $category
            ]);
        }
        foreach ($this->tags as $tag) {
            TagInNews::create([
                'news_id' => $news->id,
                'tag' => $tag
            ]);
        }
        $this->reset('title', 'slug', 'content', 'image');
        $this->published = 'private';
        $this->categories = [];
        $this->tags = [];
        Toaster::success('Berita berhasil dipublikasikan');
        $this->redirect(route('admin.portal.news'), navigate: true);
    } catch (\Exception $e) {
        Toaster::error('Berita gagal dipublikasikan');
        Toaster::error($e->getMessage());
        dd($e->getMessage());
    }
};

?>

<div>
    <x-ui.breadcrumbs :crumbs="[
        ['href' => '/', 'text' => 'Dashboard'],
        ['text' => 'Portal'],
        ['href' => route('admin.portal.news'), 'text' => 'Informasi/Berita'],
        ['text' => 'Memposting Informasi/Berita'],
    ]">
        <x-slot:actions>
            <x-ui.button color="blue" wire:click="post" size="xs">
                <x-slot:icon class="">
                <svg height="20" width="20" class=" mr-1 text-gray-300" xmlns="http://www.w3.org/2000/svg"
                     viewBox="0 0 24 24">
                    <path fill="currentColor"
                          d="M6 20q-.825 0-1.412-.587T4 18v-2q0-.425.288-.712T5 15t.713.288T6 16v2h12v-2q0-.425.288-.712T19 15t.713.288T20 16v2q0 .825-.587 1.413T18 20zm5-12.15L9.125 9.725q-.3.3-.712.288T7.7 9.7q-.275-.3-.288-.7t.288-.7l3.6-3.6q.15-.15.325-.212T12 4.425t.375.063t.325.212l3.6 3.6q.3.3.288.7t-.288.7q-.3.3-.712.313t-.713-.288L13 7.85V15q0 .425-.288.713T12 16t-.712-.288T11 15z"/>
                </svg>
                </x-slot:icon>
                Terbitkan
            </x-ui.button>
        </x-slot:actions>
    </x-ui.breadcrumbs>

    <x-ui.modal id="add-category-modal">
        <x-slot name="header">
            <h5 class="text-xl font-medium text-gray-900 dark:text-white">Tambah Kategori Informasi</h5>
        </x-slot>
        <x-slot name="content">
            <form wire:submit="addCategory" class="mx-auto">
                <x-ui.input id="name" name="name" wire:model="category_name" label="Nama Kategori"
                            placeholder="Masukan Nama" main-class="mb-5"/>
                <div class="flex justify-end space-x-2">
                    <x-ui.button type="reset" color="light"
                                 wire:click="$dispatch('close-modal', {id: 'add-category-modal'})">
                        Batal
                    </x-ui.button>
                    <x-ui.button submit color="blue" wire:loading.attr="disabled"
                                 wire:loading.class="cursor-not-allowed" wire:target="addCategory">
                        Simpan
                    </x-ui.button>
                </div>
            </form>
        </x-slot>
    </x-ui.modal>

    <div class="grid grid-flow-row xl:grid-cols-4 grid-cols-1">
        <div class="border-r border-l border-t rounded-xl dark:bg-gray-800 bg-white my-2 p-5 border-gray-200 dark:border-gray-700 col-span-2 xl:col-span-3 min-h-[calc(100vh-7.7rem)]">
           <div class="border-b dark:border-gray-600 mb-4">
               <h5 class="text-xl font-medium text-gray-900 dark:text-white">Tulis Informasi/Berita Kelurahan</h5>
               <p class="mb-3 mt-1 text-sm text-gray-500 dark:text-gray-400">Bagikan informasi atau berita kelurahan terbaru di sini.</p>
           </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-1">
                <x-ui.input id="title" name="title" wire:model.live="title" label="Judul Informasi"
                            placeholder="Masukan Judul" main-class="mb-5"/>
                <x-ui.input id="slug" name="slug" wire:model="slug" label="Slug Informasi" placeholder="Masukan Slug"
                            main-class="mb-5" readonly/>
            </div>
            <livewire:trix :value="$this->content" />
        </div>
        <div class="min-w-sm p-2 hidden xl:block">
            <div class="flex flex-col space-y-2 ">
                <div
                    class="p-4 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                    <div class=" w-full border-b dark:border-gray-600">
                        <div class="mb-3">
                            <svg class="inline w-5 h-5 -mt-2 mr-2 text-gray-700 dark:text-gray-300"
                                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                      d="m11 11.85l-1.875 1.875q-.3.3-.712.288T7.7 13.7q-.275-.3-.288-.7t.288-.7l3.6-3.6q.15-.15.325-.212T12 8.425t.375.063t.325.212l3.6 3.6q.3.3.288.7t-.288.7q-.3.3-.712.313t-.713-.288L13 11.85V19q0 .425-.288.713T12 20t-.712-.288T11 19zM4 8V6q0-.825.588-1.412T6 4h12q.825 0 1.413.588T20 6v2q0 .425-.288.713T19 9t-.712-.288T18 8V6H6v2q0 .425-.288.713T5 9t-.712-.288T4 8"/>
                            </svg>
                            <h5 class="inline text-md font-medium text-gray-900 dark:text-white">Status Publikasi</h5>
                        </div>
                    </div>
                    <div class="flex">
                        <div class="flex items-center me-4 mt-4">
                            <x-ui.input-radio label="Terbitkan" id="publish" value="published" :alert="false" name="published" wire:model="published"/>
                        </div>
                        <div class="flex items-center me-4 mt-4">
                            <x-ui.input-radio label="Privat" id="private" value="private" :alert="false" name="published" wire:model="published"/>
                        </div>
                    </div>
                </div>
                <div
                    class="p-4 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                    <div class=" w-full border-b dark:border-gray-600">
                        <div class="mb-3">
                            <svg class="inline w-5 h-5 -mt-2 mr-2 text-gray-700 dark:text-gray-300"
                                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                      d="M5 21q-.825 0-1.412-.587T3 19V5q0-.825.588-1.412T5 3h14q.825 0 1.413.588T21 5v14q0 .825-.587 1.413T19 21zm0-2h14V5H5zm0 0V5zm2-2h10q.3 0 .45-.275t-.05-.525l-2.75-3.675q-.15-.2-.4-.2t-.4.2L11.25 16L9.4 13.525q-.15-.2-.4-.2t-.4.2l-2 2.675q-.2.25-.05.525T7 17"/>
                            </svg>
                            <h5 class="inline text-md font-medium text-gray-900 dark:text-white">Gambar Utama</h5>
                        </div>
                    </div>
                    <div class=" mt-4">
                        <x-ui.filepond wire:model="image" allowImagePreview/>
                    </div>
                </div>
                <div
                    class="p-4 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                    <div class=" w-full border-b dark:border-gray-600">
                        <div class="mb-3">
                            <svg class="inline w-5 h-5 -mt-2 mr-2 text-gray-700 dark:text-gray-300"
                                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                      d="M11 11V2H2v9m2-2V4h5v5m11-2.5C20 7.9 18.9 9 17.5 9S15 7.9 15 6.5S16.11 4 17.5 4S20 5.11 20 6.5M6.5 14L2 22h9m-3.42-2H5.42l1.08-1.92M22 6.5C22 4 20 2 17.5 2S13 4 13 6.5s2 4.5 4.5 4.5S22 9 22 6.5M19 17v-3h-2v3h-3v2h3v3h2v-3h3v-2Z"/>
                            </svg>
                            <h5 class="inline text-md font-medium text-gray-900 dark:text-white">Kategori</h5>
                        </div>
                    </div>
                    <div class="border-b dark:border-gray-600">
                        @foreach($this->news_categories as $news_category)
                            <x-ui.input-checkbox main-class="my-2" :label="$news_category->name"
                                                 :id="str()->slug($news_category->name)" :alert="false"
                                                 name="categories" wire:model="categories" :value="$news_category->id"/>
                        @endforeach
                    </div>
                    <div>
                        <button wire:click="$dispatch('open-modal', { id: 'add-category-modal'})"
                                class="inline-flex items-center py-2 text-base font-medium text-blue-500 rounded-lg hover:text-blue-600 dark:text-blue-500 dark:hover:text-blue-600">
                            Tambah Kategori
                            <svg class="w-5 ml-1 inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                      d="M18 12.998h-5v5a1 1 0 0 1-2 0v-5H6a1 1 0 0 1 0-2h5v-5a1 1 0 0 1 2 0v5h5a1 1 0 0 1 0 2"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div
                    class="p-4 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                    <div class=" w-full border-b dark:border-gray-600">
                        <div class="mb-3">
                            <svg class="inline w-5 h-5 -mt-1 mr-2 text-gray-700 dark:text-gray-300"
                                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
                                <path fill="currentColor" fill-rule="evenodd"
                                      d="M27.831 0a7.9 7.9 0 0 0-4.888 1.704L7.48 13.921a7.9 7.9 0 0 0-2.442 9.117l7.281 18.31a7.9 7.9 0 0 0 5.3 4.715l57.318 15.36a7.903 7.903 0 0 0 9.676-5.591l8.18-30.529a7.9 7.9 0 0 0-5.588-9.676L29.887.267A8 8 0 0 0 27.83 0m.2 4.218q.214.012.425.05c19.04 5.048 38.06 10.182 57.09 15.271c1.964.251 3.58 2.188 3.273 4.18c-2.642 10.17-5.418 20.31-8.122 30.466c-.254 1.968-2.189 3.559-4.18 3.273c-18.547-4.915-37.078-9.913-55.615-14.873c-1.585-.424-3.509-.666-4.402-2.245c-2.475-5.95-4.787-11.97-7.188-17.952c-.82-1.459-.867-3.442.31-4.728c4.438-3.652 8.997-7.147 13.494-10.728c1.421-.988 2.645-2.58 4.485-2.712q.215-.014.43-.002M59.474 15.05a17.07 17.07 0 0 0-10.607 4.473l4.927 1.32a22 22 0 0 1 2.578-3.3c.976-1.016 2.02-1.86 3.102-2.493m4.352.633c-1.926-.036-4.055 1.058-6.087 3.174a19.4 19.4 0 0 0-2.008 2.503l6.134 1.645zm1.852.417l-1.982 7.396l6.472 1.733a19.4 19.4 0 0 0-.486-3.172c-.745-3.026-2.163-5.112-4.004-5.957m-47.416.074a5.53 5.53 0 0 0-1.394 10.872a5.531 5.531 0 1 0 1.394-10.872m51.938 1.93c.564 1.04 1.007 2.22 1.322 3.499c.316 1.282.509 2.675.58 4.144l4.592 1.232a17.1 17.1 0 0 0-6.494-8.875m-52.028 2.288c.636-.059 1.322.383 1.408 1.042c.277 1.062-.988 2.004-1.916 1.404c-1.157-.502-.706-2.402.508-2.446m29.196.69a17 17 0 0 0-3.165 5.68l6.15 1.65a31.4 31.4 0 0 1 2.438-5.876zm7.314 1.96c-.981 1.73-1.835 3.708-2.495 5.86l7.554 2.023l1.632-6.09zm8.523 2.284l-1.632 6.09l7.894 2.116c.504-2.194.754-4.334.768-6.323zm8.92 2.39a31.5 31.5 0 0 1-.827 6.307l5.814 1.558c.463-2.201.474-4.404.098-6.504zm-28.412.876a17 17 0 0 0-.098 6.504l5.334 1.429c.078-2.011.375-4.129.902-6.286zm7.97 2.136a29.7 29.7 0 0 0-.847 6.303l6.782 1.817l1.632-6.091zm9.4 2.52l-1.632 6.091l7.121 1.908a29.7 29.7 0 0 0 2.418-5.881zm9.732 2.607a32 32 0 0 1-2.36 5.898l5.002 1.338a17 17 0 0 0 3.163-5.68zm-26.684 1.34a17.08 17.08 0 0 0 6.5 8.879a15.2 15.2 0 0 1-1.088-3.045a22 22 0 0 1-.602-4.545zM50.869 39a19.7 19.7 0 0 0 .514 3.575c.658 2.67 1.841 4.606 3.375 5.606c.11.038.218.079.33.115l2.04-7.619zm8.091 2.169l-2.041 7.618q.398.085.795.15c1.798-.136 3.746-1.218 5.612-3.162a19.7 19.7 0 0 0 2.233-2.838zm33.154.803l-1.254 4.71l.175.354c.396.803.333 1.859-.2 2.468l-.053.06l-.041.066C85.272 58.189 79.74 66.718 74.3 75.31l-.02.034l-.02.037c-.328.622-.859 1.21-1.44 1.562c-.58.353-1.17.485-1.792.333h-.006l-.004-.002c-.76-.176-1.581-.96-2.784-1.622c-12.061-7.708-24.086-15.479-36.122-23.237c-5.12-1.524-10.434-3.106-15.078-4.557l51.13 32.781l.017.01l.016.011c3.139 1.85 7.391.715 9.21-2.434l-.025.043c5.752-8.956 11.559-17.885 17.284-26.87l.018-.026l.015-.029c1.476-2.658.914-6.105-1.303-8.173zm-24.63 1.48c-.854 1.375-1.794 2.6-2.79 3.637a15.5 15.5 0 0 1-2.243 1.94a17.1 17.1 0 0 0 9.507-4.378zm-46.55 12.22l34.862 41.785c2.281 2.938 6.788 3.408 9.625 1.003c8.22-6.853 16.463-13.685 24.666-20.567l.016-.014l.015-.015c2.725-2.478 2.854-6.93.33-9.591l-.038-.041l-.046-.04a1.34 1.34 0 0 0-.881-.304a1.3 1.3 0 0 0-.755.304c-.313.26-.407.474-.514.666s-.19.369-.257.488c-.066.119-.145.176-.018.07l.037-.03c-.287.22-.562.582-.631.994c-.07.41.05.743.15.956c.199.424.331.628.306.536l.016.058l.023.057c.4.992.076 2.354-.775 2.888l-.066.042l-.057.049c-7.956 6.798-16.073 13.43-24.107 20.17c-1 .79-2.688.827-3.503-.076C50.858 84.97 42.434 74.835 34 64.706Z"
                                      color="currentColor"/>
                            </svg>
                            <h5 class="inline text-md font-medium text-gray-900 dark:text-white">Tags</h5>
                        </div>
                    </div>
                    <x-ui.input wire:keydown.enter.prevent="addTags" wire:model="tag" :alert="false" id="tags"
                                placeholder="Tags" main-class="my-4"/>
                    <div class="flex flex-wrap">
                        @foreach ($tags as $tag)
                            <div class="flex items-center m-1">
                                <span
                                    class="inline-flex items-center px-3 py-1 text-base font-medium text-gray-800 bg-gray-200 rounded dark:bg-gray-700 dark:text-gray-400">
                                    {{ $tag }}
                                    <button type="button" wire:click="removeTags('{{ $tag }}')">
                                        <svg class="w-3 ml-3 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                             fill="none" viewBox="0 0 14 14">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                  stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                        </svg>
                                    </button>
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
