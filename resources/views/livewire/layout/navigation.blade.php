<?php

use App\Livewire\Actions\Logout;

$logout = function (Logout $logout) {
    $logout();

    $this->redirect('/', navigate: true);
};

?>

<div >
    <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700 ">
        <div class="px-3 py-3 lg:px-5 lg:pl-3 ">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start rtl:justify-end">
                    <button data-drawer-target="sidebar-multi-level-sidebar" data-drawer-toggle="sidebar-multi-level-sidebar" aria-controls="sidebar-multi-level-sidebar" type="button" class="inline-flex items-center p-2 mt-2 text-sm text-gray-500 rounded-lg ms-3 sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600 z-[100]">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                        </svg>
                    </button>
                    <a href="https://flowbite.com" class="flex ms-2 md:me-24">
                        <x-application-logo />
                    </a>
                </div>
                <div class="flex items-center">
                    <div class="bg-white lg:flex dark:bg-gray-800 md:hidden" sidebar-bottom-menu="" bis_skin_checked="1"  x-cloak>
                        <label class="inline-flex items-center cursor-pointer w-full">
                            <input type="checkbox" id="theme-toggle" class="sr-only peer" />
                            <span class="text-sm font-medium text-gray-900 ms-3 dark:text-gray-300">
                                    <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                                    <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                                </span>
                        </label>
                    </div>
                    <div class="flex items-center ms-3">
                        <div>
                            <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                                <span class="sr-only">Open user menu</span>
                                <img x-cloak class="w-8 h-8 rounded-full object-cover"  x-data="{{ json_encode(['profile_path' => auth()->user()->profile_path, 'name' => auth()->user()->name]) }}" x-on:refresh-image.window="profile_path = $event.detail.path"
                                     alt="Tailwind CSS Navbar component"
                                     x-bind:src="profile_path ? '/storage/' + profile_path : 'https://ui-avatars.com/api/?name=' + name" />
                            </button>
                        </div>
                        <div class="z-50 hidden my-2 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600" id="dropdown-user">
                            <div class="px-4 py-3" role="none">
                                <div class="text-sm text-gray-900 dark:text-white" role="none"
                                     x-data="{{ json_encode(['name' => auth()->user()->name]) }}"
                                     x-text="name"
                                     x-on:profile-updated.window="name = $event.detail.name">
                                </div>
                                <div class="text-sm text-gray-900 dark:text-white" role="none"
                                     x-data="{{ json_encode(['email' => auth()->user()->email]) }}"
                                     x-text="email"
                                     x-on:profile-updated.window="email = $event.detail.email">
                                </div>
                            </div>
                            <ul class="py-1" role="none">
                                <li>
                                    <a wire:navigate href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Dashboard</a>
                                </li>
                                <li>
                                    <a wire:navigate href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Profil</a>
                                </li>
                                <li>
                                    <a href="#" wire:click="logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Keluar</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <aside id="sidebar-multi-level-sidebar"  class="fixed top-0 left-0 z-40 w-64 h-screen pt-16 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700 " aria-label="Sidebar">
        <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800 scrollbar-thumb-gray-700 scrollbar-track-white  overflow-y-scroll scrollbar-thin dark:scrollbar-track-gray-800 dark:scrollbar-thumb-gray-500">
            <ul class="space-y-2 font-medium">
                <li>
                    <a href="{{ route('dashboard') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('dashboard') ? 'bg-gray-200 dark:bg-gray-700' : '' }}" wire:navigate>
                        <svg class="flex-shrink-0 w-5 h-5  text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path fill="#00adff" fill-rule="evenodd" d="M19 11a2 2 0 0 1 1.995 1.85L21 13v6a2 2 0 0 1-1.85 1.995L19 21h-4a2 2 0 0 1-1.995-1.85L13 19v-6a2 2 0 0 1 1.85-1.995L15 11zm0-8a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" class="duoicon-secondary-layer" opacity="0.3" />
                            <path fill="#00adff" fill-rule="evenodd" d="M9 3a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" class="duoicon-primary-layer" />
                            <path fill="#00adff" fill-rule="evenodd" d="M9 15a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-2a2 2 0 0 1 2-2z" class="duoicon-secondary-layer" opacity="0.3" />
                        </svg>
                        <span class="ms-3">Dashboard</span>
                    </a>
                </li>
                <li>
                    <button type="button" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 group {{ request()->routeIs('master-data.*') ? 'bg-gray-200 dark:bg-gray-700' : '' }}" aria-controls="dropdown-portal" data-collapse-toggle="dropdown-portal">
                        <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path fill="#00adff" fill-rule="evenodd" d="M20.424 15.804a5.38 5.38 0 0 0 0-7.608S14.81 2.399 14.046 2.07c-.036-.015-.033-.028-.03-.039c.004-.02.007-.031-.31-.031a.86.86 0 0 0-.861.86v2.243c0 .475.385.86.86.86h.933c1.98 0 3.586 1.366 3.586 3.347v5.38c0 1.98-1.605 3.346-3.586 3.346h-.933a.86.86 0 0 0-.86.861v2.242a.86.86 0 0 0 1.2.79c.765-.328 6.38-6.125 6.38-6.125m-9.27-10.701V2.86a.86.86 0 0 0-1.2-.79c-.765.329-6.38 6.125-6.38 6.125a5.38 5.38 0 0 0 0 7.608s5.615 5.797 6.38 6.126a.86.86 0 0 0 1.2-.79v-2.243a.86.86 0 0 0-.86-.86h-.933c-1.98 0-3.586-1.366-3.586-3.347V9.31c0-1.98 1.605-3.346 3.586-3.346h.933a.86.86 0 0 0 .86-.861" clip-rule="evenodd" />
                        </svg>
                        <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Portal</span>
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                        </svg>
                    </button>
                    <ul id="dropdown-portal" class="{{  request()->routeIs('master-data.*') ? 'block' : 'hidden'}} py-2 space-y-2">
                        <li class="text-center rounded text-sm font-bold text-gray-900 dark:text-white bg-[#00adff]">
                            <span class="rounded-md px-2  text-white ">Beranda</span>
                        </li>
                        <li>
                            <a href="{{ route('master-data.citizen-association') }}" wire:navigate class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 {{ request()->routeIs('master-data.citizen-association') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">Sambutan Lurah</a>
                        </li>
                        <li>
                            <a href="{{ route('master-data.citizen-association') }}" wire:navigate class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 {{ request()->routeIs('master-data.citizen-association') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">Peta Kelurahan</a>
                        </li>
                        <li class="text-center rounded text-sm font-bold text-gray-900 dark:text-white bg-[#00adff]">
                            <span class="rounded-md px-2  text-white ">Profil</span>
                        </li>
                        <li>
                            <a href="{{ route('master-data.citizen-association') }}" wire:navigate class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 {{ request()->routeIs('master-data.citizen-association') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">Visi & Misi</a>
                        </li>
                        <li>
                            <a href="{{ route('master-data.citizen-association') }}" wire:navigate class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 {{ request()->routeIs('master-data.citizen-association') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">Bagan Kelurahan</a>
                        </li>
                        <li>
                            <a href="{{ route('master-data.citizen-association') }}" wire:navigate class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 {{ request()->routeIs('master-data.citizen-association') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">Struktur Organisasi</a>
                        </li>
                        <li class="text-center rounded text-sm font-bold text-gray-900 dark:text-white bg-[#00adff]">
                            <span class="rounded-md px-2  text-white ">Berita</span>
                        </li>
                        <li>
                            <a href="{{ route('master-data.citizen-association') }}" wire:navigate class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 {{ request()->routeIs('master-data.citizen-association') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">Berita</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <button type="button" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 group {{ request()->routeIs('master-data.*') ? 'bg-gray-200 dark:bg-gray-700' : '' }}" aria-controls="dropdown-example" data-collapse-toggle="dropdown-example">
                        <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path fill="#00adff" d="M12 10c4.418 0 8-1.79 8-4s-3.582-4-8-4s-8 1.79-8 4s3.582 4 8 4" />
                            <path fill="#00adff" d="M4 12v6c0 2.21 3.582 4 8 4s8-1.79 8-4v-6c0 2.21-3.582 4-8 4s-8-1.79-8-4" opacity="0.5" />
                            <path fill="#00adff" d="M4 6v6c0 2.21 3.582 4 8 4s8-1.79 8-4V6c0 2.21-3.582 4-8 4S4 8.21 4 6" opacity="0.7" />
                        </svg>
                        <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Master Data</span>
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                        </svg>
                    </button>
                    <ul id="dropdown-example" class="{{  request()->routeIs('master-data.*') ? 'block' : 'hidden'}} py-2 space-y-2">
                        <li>
                            <a href="{{ route('master-data.citizen-association') }}" wire:navigate class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 {{ request()->routeIs('master-data.citizen-association') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">Rukun Warga</a>
                        </li>
                        <li>
                            <a href="{{ route('master-data.neighborhood-association') }}" wire:navigate class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 {{ request()->routeIs('master-data.neighborhood-association') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">Rukun Tetangga</a>
                        </li>
                        <li>
                            <a href="{{ route('master-data.religion') }}" wire:navigate class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 {{ request()->routeIs('master-data.religion') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">Agama</a>
                        </li>
                        <li>
                            <a href="{{ route('master-data.education') }}" wire:navigate class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 {{ request()->routeIs('master-data.education') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">Pendidikan</a>
                        </li>
                        <li>
                            <a href="{{ route('master-data.employment') }}" wire:navigate class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 {{ request()->routeIs('master-data.employment') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">Pekerjaan</a>
                        </li>
                        <li>
                            <a href="{{ route('master-data.blood-group') }}" wire:navigate class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 {{ request()->routeIs('master-data.blood-group') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">Golongan Darah</a>
                        </li>
                        <li>
                            <a href="{{ route('master-data.user') }}" wire:navigate class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 {{ request()->routeIs('master-data.user') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">Pengguna</a>
                        </li>
                        <li>
                            <a href="{{ route('master-data.role') }}" wire:navigate class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 {{ request()->routeIs('master-data.role') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">Peran Pengguna</a>
                        </li>

                    </ul>
                </li>
                <li>
                    <a href="{{ route('neighborhood-association.inhabitant') }}" wire:navigate class="flex items-center p-2 text-gray-900 transition duration-75 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white group {{ request()->routeIs('neighborhood-association.inhabitant') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">
                        <svg  class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 26 26">
                            <g fill="currentColor">
                                <path d="M26 14c0 6.627-5.373 12-12 12S2 20.627 2 14S7.373 2 14 2s12 5.373 12 12" opacity="0.2" />
                                <g fill-rule="evenodd" clip-rule="evenodd">
                                    <path d="M8 12a2 2 0 1 0 0-4a2 2 0 0 0 0 4m0 1a3 3 0 1 0 0-6a3 3 0 0 0 0 6" />
                                    <path d="M6.854 11.896a.5.5 0 0 1 0 .708l-.338.337A3.47 3.47 0 0 0 5.5 15.394v1.856a.5.5 0 1 1-1 0v-1.856a4.47 4.47 0 0 1 1.309-3.16l.337-.338a.5.5 0 0 1 .708 0m11.792-.3a.5.5 0 0 0 0 .708l.338.337A3.47 3.47 0 0 1 20 15.094v2.156a.5.5 0 0 0 1 0v-2.156a4.47 4.47 0 0 0-1.309-3.16l-.337-.338a.5.5 0 0 0-.708 0" />
                                    <path d="M17 12a2 2 0 1 1 0-4a2 2 0 0 1 0 4m0 1a3 3 0 1 1 0-6a3 3 0 0 1 0 6m-4.5 3.25a2.5 2.5 0 0 0-2.5 2.5v1.3a.5.5 0 0 1-1 0v-1.3a3.5 3.5 0 0 1 7 0v1.3a.5.5 0 1 1-1 0v-1.3a2.5 2.5 0 0 0-2.5-2.5" />
                                    <path d="M12.5 14.75a2 2 0 1 0 0-4a2 2 0 0 0 0 4m0 1a3 3 0 1 0 0-6a3 3 0 0 0 0 6" />
                                </g>
                                <path fill-rule="evenodd" d="M13 24.5c6.351 0 11.5-5.149 11.5-11.5S19.351 1.5 13 1.5S1.5 6.649 1.5 13S6.649 24.5 13 24.5m0 1c6.904 0 12.5-5.596 12.5-12.5S19.904.5 13 .5S.5 6.096.5 13S6.096 25.5 13 25.5" clip-rule="evenodd" />
                            </g>
                        </svg>
                        <span class="ms-3">Penduduk</span>
                    </a>
                </li>
            </ul>
{{--            seprator--}}
            <ul class="pt-4 mt-4 space-y-2 font-medium border-t border-gray-200 dark:border-gray-700">
                <li>
                    <a href="#" class="flex items-center p-2 text-gray-900 transition duration-75 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white group">
                        <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 17 20">
                            <path d="M7.958 19.393a7.7 7.7 0 0 1-6.715-3.439c-2.868-4.832 0-9.376.944-10.654l.091-.122a3.286 3.286 0 0 0 .765-3.288A1 1 0 0 1 4.6.8c.133.1.313.212.525.347A10.451 10.451 0 0 1 10.6 9.3c.5-1.06.772-2.213.8-3.385a1 1 0 0 1 1.592-.758c1.636 1.205 4.638 6.081 2.019 10.441a8.177 8.177 0 0 1-7.053 3.795Z"/>
                        </svg>
                        <span class="ms-3">Upgrade to Pro</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>
</div>
