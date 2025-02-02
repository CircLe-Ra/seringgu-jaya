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
                    <livewire:layout.notifications />
                    <div class="bg-white lg:flex dark:bg-gray-800 md:hidden" sidebar-bottom-menu="" bis_skin_checked="1">
                        <label class="inline-flex items-center cursor-pointer w-full">
                            <input type="checkbox" id="theme-toggle" class="sr-only peer" />
                            <span class="text-sm font-medium text-gray-900 ms-3 dark:text-gray-300">
                                    <svg x-cloak id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                                    <svg x-cloak id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="#FDB813" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
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
                                    <a wire:navigate href="{{ route('notification') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Notifikasi</a>
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
        <div class="h-full px-3 pb-4 bg-white dark:bg-gray-800 scrollbar-thumb-gray-700 scrollbar-track-white  overflow-y-scroll scrollbar-thin dark:scrollbar-track-gray-800 dark:scrollbar-thumb-gray-500">
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
                @role('rt')
                <li>
                    <a href="{{ route('neighborhood-association.inhabitant') }}" wire:navigate class="flex items-center p-2 text-gray-900 transition duration-75 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white group {{ request()->routeIs('neighborhood-association.inhabitant') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">
                        <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 16 16">
                            <g fill="none">
                                <path fill="url(#fluentColorPeopleCommunity160)" d="M10.99 7.714a1.5 1.5 0 0 0-1.838 1.061l-.388 1.449a3 3 0 1 0 5.796 1.553l.388-1.45a1.5 1.5 0 0 0-1.06-1.836z" />
                                <path fill="url(#fluentColorPeopleCommunity161)" d="M5.01 7.714a1.5 1.5 0 0 1 1.837 1.061l.388 1.449a3 3 0 1 1-5.795 1.553l-.389-1.45a1.5 1.5 0 0 1 1.061-1.836z" />
                                <path fill="url(#fluentColorPeopleCommunity162)" d="M6.5 7A1.5 1.5 0 0 0 5 8.5V11a3 3 0 1 0 6 0V8.5A1.5 1.5 0 0 0 9.5 7z" />
                                <path fill="url(#fluentColorPeopleCommunity163)" d="M8 1a2.5 2.5 0 1 0 0 5a2.5 2.5 0 0 0 0-5" />
                                <path fill="url(#fluentColorPeopleCommunity164)" d="M3 3a2 2 0 1 0 0 4a2 2 0 0 0 0-4" />
                                <path fill="url(#fluentColorPeopleCommunity165)" d="M13 3a2 2 0 1 0 0 4a2 2 0 0 0 0-4" />
                                <defs>
                                    <radialGradient id="fluentColorPeopleCommunity160" cx="0" cy="0" r="1" gradientTransform="rotate(78.837 -.336 11.297)scale(4.64914)" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#0078d4" />
                                        <stop offset="1" stop-color="#004695" />
                                    </radialGradient>
                                    <radialGradient id="fluentColorPeopleCommunity161" cx="0" cy="0" r="1" gradientTransform="matrix(3.34115 6.04144 -4.34865 2.40497 2.553 7.96)" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#008ce2" />
                                        <stop offset="1" stop-color="#0068c6" />
                                    </radialGradient>
                                    <radialGradient id="fluentColorPeopleCommunity162" cx="0" cy="0" r="1" gradientTransform="rotate(63.608 -3.915 10.713)scale(4.22417 3.87907)" gradientUnits="userSpaceOnUse">
                                        <stop offset=".339" stop-color="#3dcbff" />
                                        <stop offset="1" stop-color="#14b1ff" />
                                    </radialGradient>
                                    <radialGradient id="fluentColorPeopleCommunity163" cx="0" cy="0" r="1" gradientTransform="rotate(59.931 1.37 7.898)scale(3.12306)" gradientUnits="userSpaceOnUse">
                                        <stop offset=".339" stop-color="#3dcbff" />
                                        <stop offset="1" stop-color="#14b1ff" />
                                    </radialGradient>
                                    <radialGradient id="fluentColorPeopleCommunity164" cx="0" cy="0" r="1" gradientTransform="rotate(47.573 -3.7 4.554)scale(3.27979)" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#008ce2" />
                                        <stop offset="1" stop-color="#0068c6" />
                                    </radialGradient>
                                    <radialGradient id="fluentColorPeopleCommunity165" cx="0" cy="0" r="1" gradientTransform="rotate(78.837 3.672 9.578)scale(2.93403)" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#0078d4" />
                                        <stop offset="1" stop-color="#004695" />
                                    </radialGradient>
                                </defs>
                            </g>
                        </svg>
                        <span class="ms-3">Penduduk</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('neighborhood-association.letter') }}" wire:navigate class="flex items-center p-2 text-gray-900 transition duration-75 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white group {{ request()->routeIs('neighborhood-association.letter') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">
                        <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path fill="#00adff" d="M14.2 3H9.8C5.652 3 3.577 3 2.289 4.318S1 7.758 1 12s0 6.364 1.289 7.682S5.652 21 9.8 21h4.4c4.148 0 6.223 0 7.511-1.318S23 16.242 23 12s0-6.364-1.289-7.682S18.348 3 14.2 3" opacity="0.45" />
                            <path fill="#00adff" d="M19.128 8.033a.825.825 0 0 0-1.056-1.268l-2.375 1.98c-1.026.855-1.738 1.447-2.34 1.833c-.582.375-.977.5-1.357.5s-.774-.125-1.357-.5c-.601-.386-1.314-.978-2.34-1.834L5.928 6.765a.825.825 0 0 0-1.056 1.268l2.416 2.014c.975.812 1.765 1.47 2.463 1.92c.726.466 1.434.762 2.25.762c.814 0 1.522-.296 2.249-.763c.697-.448 1.487-1.107 2.462-1.92z" />
                        </svg>
                        <span class="ms-3">Surat</span>
                    </a>
                </li>
                @endrole
                @role('staff')
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
                            <a href="{{ route('master-data.letter-type') }}" wire:navigate class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 {{ request()->routeIs('master-data.letter-type') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">Jenis Surat</a>
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
                    <a href="{{ route('letter.mail-box') }}" wire:navigate class="flex items-center p-2 text-gray-900 transition duration-75 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white group {{ request()->routeIs('letter.mail-box') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">
                        <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 32 32">
                            <g fill="none">
                                <path fill="#7d4533" d="M16.81 30.04V23.4L14.5 22l-2.67 1.4v6.64z" />
                                <path fill="#00adff" d="M21.65 7H7.84L11 23.61h14.99a1.5 1.5 0 0 0 1.5-1.5v-9.27C27.48 9.61 24.87 7 21.65 7" />
                                <path fill="#3f60ff" d="M7.84 7C4.61 7 2 9.61 2 12.84v9.27a1.5 1.5 0 0 0 1.5 1.5h8.67a1.5 1.5 0 0 0 1.5-1.5v-9.27C13.67 9.61 11.06 7 7.84 7" />
                                <path fill="#321b41" d="M7.84 8A4.834 4.834 0 0 0 3 12.84v9.27c0 .276.224.49.5.49h1.133l7.89-10.95A4.83 4.83 0 0 0 7.84 8" />
                                <path fill="#f92f60" d="M24.132 2h-5.264c-.475 0-.868.369-.868.816v2.368c0 .447.393.816.869.816h5.262c.476 0 .869-.369.869-.816V2.816c0-.447-.393-.816-.869-.816" />
                                <path fill="#d3d3d3" d="M17.21 2.58c0-.67.54-1.21 1.21-1.21s1.21.54 1.21 1.21v8.904A2.42 2.42 0 0 1 18.42 16a2.42 2.42 0 0 1-1.21-4.516z" />
                                <path fill="#e1d8ec" d="M12.607 12.056h-8.03A.565.565 0 0 0 4 12.61v8.89c0 .308.257.555.577.555h8.093V12.84q0-.402-.063-.784" />
                                <path fill="#cdc4d6" d="M12.623 12.16H4.577a.565.565 0 0 0-.577.556v.572l7.309 4.482a1.33 1.33 0 0 0 1.361.013V12.84q0-.346-.047-.68" />
                                <path fill="#f3eef8" d="M12.513 11.61H4.577a.565.565 0 0 0-.577.556v.572l7.309 4.482a1.33 1.33 0 0 0 1.361.013V12.84q-.001-.639-.157-1.23m.157 6.173a1.33 1.33 0 0 1-1.361-.013l-.641-.393L4 21.467v.577c0 .302.257.55.577.555h7.697a.5.5 0 0 0 .396-.489z" />
                            </g>
                        </svg>
                        <span class="ms-3">Kotak Surat</span>
                    </a>
                </li>
                @endrole
                @role('warga')
                <li>
                    <a href="{{ route('citizen.information') }}" wire:navigate class="flex items-center p-2 text-gray-900 transition duration-75 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white group {{ request()->routeIs('citizen.information') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">
                        <svg class="flex-shrink-0 w-5 h-5 -mt-1 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><g fill="none"><rect width="28" height="28" x="2" y="2" fill="#00adff" rx="4"/><path fill="#fff" d="M16 9.971a1.978 1.978 0 1 0 0-3.956a1.978 1.978 0 0 0 0 3.956m1.61 3.747a1.75 1.75 0 1 0-3.5 0v10.59a1.75 1.75 0 1 0 3.5 0z"/></g></svg>
                        <span class="ms-3">Informasi</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('citizen.mail-box') }}" wire:navigate class="flex items-center p-2 text-gray-900 transition duration-75 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white group {{ request()->routeIs('citizen.mail-box') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">
                        <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 32 32">
                            <g fill="none">
                                <path fill="#7d4533" d="M16.81 30.04V23.4L14.5 22l-2.67 1.4v6.64z" />
                                <path fill="#00adff" d="M21.65 7H7.84L11 23.61h14.99a1.5 1.5 0 0 0 1.5-1.5v-9.27C27.48 9.61 24.87 7 21.65 7" />
                                <path fill="#3f60ff" d="M7.84 7C4.61 7 2 9.61 2 12.84v9.27a1.5 1.5 0 0 0 1.5 1.5h8.67a1.5 1.5 0 0 0 1.5-1.5v-9.27C13.67 9.61 11.06 7 7.84 7" />
                                <path fill="#321b41" d="M7.84 8A4.834 4.834 0 0 0 3 12.84v9.27c0 .276.224.49.5.49h1.133l7.89-10.95A4.83 4.83 0 0 0 7.84 8" />
                                <path fill="#f92f60" d="M24.132 2h-5.264c-.475 0-.868.369-.868.816v2.368c0 .447.393.816.869.816h5.262c.476 0 .869-.369.869-.816V2.816c0-.447-.393-.816-.869-.816" />
                                <path fill="#d3d3d3" d="M17.21 2.58c0-.67.54-1.21 1.21-1.21s1.21.54 1.21 1.21v8.904A2.42 2.42 0 0 1 18.42 16a2.42 2.42 0 0 1-1.21-4.516z" />
                                <path fill="#e1d8ec" d="M12.607 12.056h-8.03A.565.565 0 0 0 4 12.61v8.89c0 .308.257.555.577.555h8.093V12.84q0-.402-.063-.784" />
                                <path fill="#cdc4d6" d="M12.623 12.16H4.577a.565.565 0 0 0-.577.556v.572l7.309 4.482a1.33 1.33 0 0 0 1.361.013V12.84q0-.346-.047-.68" />
                                <path fill="#f3eef8" d="M12.513 11.61H4.577a.565.565 0 0 0-.577.556v.572l7.309 4.482a1.33 1.33 0 0 0 1.361.013V12.84q-.001-.639-.157-1.23m.157 6.173a1.33 1.33 0 0 1-1.361-.013l-.641-.393L4 21.467v.577c0 .302.257.55.577.555h7.697a.5.5 0 0 0 .396-.489z" />
                            </g>
                        </svg>
                        <span class="ms-3">Surat Permohonan</span>
                    </a>
                </li>
                @endrole
            </ul>
{{--            seprator--}}
            <ul class="pt-4 mt-4 space-y-2 font-medium border-t border-gray-200 dark:border-gray-700">
                @role('staff')
                <li>
                    <button type="button" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 group {{ request()->routeIs('admin.portal.*') ? 'bg-gray-200 dark:bg-gray-700' : '' }}" aria-controls="dropdown-portal" data-collapse-toggle="dropdown-portal">
                        <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path fill="#00adff" fill-rule="evenodd" d="M20.424 15.804a5.38 5.38 0 0 0 0-7.608S14.81 2.399 14.046 2.07c-.036-.015-.033-.028-.03-.039c.004-.02.007-.031-.31-.031a.86.86 0 0 0-.861.86v2.243c0 .475.385.86.86.86h.933c1.98 0 3.586 1.366 3.586 3.347v5.38c0 1.98-1.605 3.346-3.586 3.346h-.933a.86.86 0 0 0-.86.861v2.242a.86.86 0 0 0 1.2.79c.765-.328 6.38-6.125 6.38-6.125m-9.27-10.701V2.86a.86.86 0 0 0-1.2-.79c-.765.329-6.38 6.125-6.38 6.125a5.38 5.38 0 0 0 0 7.608s5.615 5.797 6.38 6.126a.86.86 0 0 0 1.2-.79v-2.243a.86.86 0 0 0-.86-.86h-.933c-1.98 0-3.586-1.366-3.586-3.347V9.31c0-1.98 1.605-3.346 3.586-3.346h.933a.86.86 0 0 0 .86-.861" clip-rule="evenodd" />
                        </svg>
                        <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Portal</span>
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                        </svg>
                    </button>
                    <ul id="dropdown-portal" class="{{  request()->routeIs('admin.portal.*') ? 'block' : 'hidden'}} py-2 space-y-2">
                        <li>
                            <a href="{{ route('admin.portal.news-category') }}" wire:navigate class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 {{ request()->routeIs('admin.portal.news-category') ? 'font-semibold' : '' }}">Kategori Informasi</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.portal.news') }}" wire:navigate class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 {{ request()->routeIs('admin.portal.*news') ? 'font-semibold' : '' }}">Informasi/Berita</a>
                        </li>

                    </ul>
                </li>
                @endrole
            </ul>
        </div>
    </aside>
</div>
