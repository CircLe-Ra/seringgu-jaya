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
                    <a href="/" class="flex ms-2 md:me-24">
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

    <aside id="sidebar-multi-level-sidebar"  class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700 " aria-label="Sidebar">
        <div class="h-full px-3 pb-4 bg-white dark:bg-gray-800 scrollbar-thumb-gray-700 scrollbar-track-white  overflow-y-scroll scrollbar-thin dark:scrollbar-track-gray-800 dark:scrollbar-thumb-gray-500">
            <ul class="space-y-2 font-medium">
                @role('staff|lurah')
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
                @endrole
                @role('rt')
                    <li>
                        <a href="{{ route('neighborhood-association.inhabitant') }}" wire:navigate class="flex items-center p-2 text-gray-900 transition duration-75 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white group {{ request()->routeIs('neighborhood-association.inhabitant*') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">
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
                        <a href="{{ route('citizen-association') }}" wire:navigate class="flex items-center p-2 text-gray-900 transition duration-75 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white group {{ request()->routeIs('citizen-association') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">
                            <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><g fill="none"><g clip-path="url(#fluentColorPeopleHome209)"><path fill="url(#fluentColorPeopleHome200)" d="M5.009 11A2 2 0 0 0 3 13c0 1.691.833 2.966 2.135 3.797C6.417 17.614 8.145 18 10 18s3.583-.386 4.865-1.203C16.167 15.967 17 14.69 17 13a2 2 0 0 0-2-2z"/><path fill="url(#fluentColorPeopleHome201)" d="M5.009 11A2 2 0 0 0 3 13c0 1.691.833 2.966 2.135 3.797C6.417 17.614 8.145 18 10 18s3.583-.386 4.865-1.203C16.167 15.967 17 14.69 17 13a2 2 0 0 0-2-2z"/><path fill="url(#fluentColorPeopleHome206)" fill-opacity="0.75" d="M5.009 11A2 2 0 0 0 3 13c0 1.691.833 2.966 2.135 3.797C6.417 17.614 8.145 18 10 18s3.583-.386 4.865-1.203C16.167 15.967 17 14.69 17 13a2 2 0 0 0-2-2z"/><path fill="url(#fluentColorPeopleHome207)" fill-opacity="0.55" d="M5.009 11A2 2 0 0 0 3 13c0 1.691.833 2.966 2.135 3.797C6.417 17.614 8.145 18 10 18s3.583-.386 4.865-1.203C16.167 15.967 17 14.69 17 13a2 2 0 0 0-2-2z"/><path fill="url(#fluentColorPeopleHome208)" fill-opacity="0.55" d="M5.009 11A2 2 0 0 0 3 13c0 1.691.833 2.966 2.135 3.797C6.417 17.614 8.145 18 10 18s3.583-.386 4.865-1.203C16.167 15.967 17 14.69 17 13a2 2 0 0 0-2-2z"/><path fill="url(#fluentColorPeopleHome202)" d="M10 2a4 4 0 1 0 0 8a4 4 0 0 0 0-8"/><path fill="url(#fluentColorPeopleHome203)" d="M14 15h3v4h-3z"/><path fill="url(#fluentColorPeopleHome204)" d="M12 15.46c0-.292.127-.569.349-.759l2.826-2.422a.5.5 0 0 1 .651 0l2.825 2.422c.221.19.349.467.349.76v3.04a.5.5 0 0 1-.5.5l-2-.001v-2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5V19h-2a.5.5 0 0 1-.5-.5z"/><path fill="url(#fluentColorPeopleHome205)" fill-rule="evenodd" d="M14.518 11.359a1.5 1.5 0 0 1 1.964 0l3.26 2.824a.75.75 0 1 1-.983 1.134L15.5 12.492l-3.259 2.825a.75.75 0 1 1-.982-1.134z" clip-rule="evenodd"/></g><defs><linearGradient id="fluentColorPeopleHome200" x1="6.329" x2="8.591" y1="11.931" y2="19.153" gradientUnits="userSpaceOnUse"><stop offset=".125" stop-color="#00adff"/><stop offset="1" stop-color="#355cf7"/></linearGradient><linearGradient id="fluentColorPeopleHome201" x1="10" x2="13.167" y1="10.167" y2="22" gradientUnits="userSpaceOnUse"><stop stop-color="#001879" stop-opacity="0"/><stop offset="1" stop-color="#021d88"/></linearGradient><linearGradient id="fluentColorPeopleHome202" x1="7.902" x2="11.979" y1="3.063" y2="9.574" gradientUnits="userSpaceOnUse"><stop offset=".125" stop-color="#00adff"/><stop offset="1" stop-color="#355cf7"/></linearGradient><linearGradient id="fluentColorPeopleHome203" x1="15.5" x2="12.853" y1="15" y2="19.413" gradientUnits="userSpaceOnUse"><stop stop-color="#944600"/><stop offset="1" stop-color="#cd8e02"/></linearGradient><linearGradient id="fluentColorPeopleHome204" x1="11.764" x2="18.118" y1="12.349" y2="18.864" gradientUnits="userSpaceOnUse"><stop stop-color="#ffd394"/><stop offset="1" stop-color="#ffb357"/></linearGradient><linearGradient id="fluentColorPeopleHome205" x1="15.929" x2="15.193" y1="9.711" y2="15.112" gradientUnits="userSpaceOnUse"><stop stop-color="#ff921f"/><stop offset="1" stop-color="#eb4824"/></linearGradient><radialGradient id="fluentColorPeopleHome206" cx="0" cy="0" r="1" gradientTransform="matrix(5 0 0 4.26136 15 17)" gradientUnits="userSpaceOnUse"><stop stop-color="#0a1852" stop-opacity="0.75"/><stop offset="1" stop-color="#0a1852" stop-opacity="0"/></radialGradient><radialGradient id="fluentColorPeopleHome207" cx="0" cy="0" r="1" gradientTransform="rotate(90 2 16)scale(1.5)" gradientUnits="userSpaceOnUse"><stop stop-color="#0a1852" stop-opacity="0.322"/><stop offset="1" stop-color="#0a1852" stop-opacity="0"/></radialGradient><radialGradient id="fluentColorPeopleHome208" cx="0" cy="0" r="1" gradientTransform="matrix(0 1 -1.67609 0 12 15)" gradientUnits="userSpaceOnUse"><stop stop-color="#0a1852" stop-opacity="0.75"/><stop offset="1" stop-color="#0a1852" stop-opacity="0"/></radialGradient><clipPath id="fluentColorPeopleHome209"><path fill="#fff" d="M0 0h20v20H0z"/></clipPath></defs></g></svg>
                            <span class="ms-3">Rukun Warga</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('neighborhood-association') }}" wire:navigate class="flex items-center p-2 text-gray-900 transition duration-75 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white group {{ request()->routeIs('neighborhood-association') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">
                            <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#00adff" d="M6.5 15v-4h3v4H13V9h2L8 3L1 9h2v6zM9 16v2h6v-2l3 3l-3 3v-2H9v2l-3-3zm14-7h-2v6h-6v-5h4l-5.46-4.89L16 3z"/></svg>
                            <span class="ms-3">Rukun Tetangga</span>
                        </a>
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
                @role('lurah|staff')
                <li>
                    <a href="{{ route('report') }}" wire:navigate class="flex items-center p-2 text-gray-900 transition duration-75 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white group {{ request()->routeIs('report') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">
                        <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48"><g fill="none" stroke-linejoin="round" stroke-width="4"><path fill="#3d8cf6" stroke="#1b89bc" d="M5 7C5 5.34315 6.34315 4 8 4H32C33.6569 4 35 5.34315 35 7V44H8C6.34315 44 5 42.6569 5 41V7Z"/><path stroke="#1b89bc" d="M35 24C35 22.8954 35.8954 22 37 22H41C42.1046 22 43 22.8954 43 24V41C43 42.6569 41.6569 44 40 44H35V24Z"/><path stroke="#fff" stroke-linecap="round" d="M11 12H19"/><path stroke="#fff" stroke-linecap="round" d="M11 19H23"/></g></svg>
                        <span class="ms-3">Laporan</span>
                    </a>
                </li>
                @endrole
            </ul>
{{--            seprator--}}
            @role('staff')
            <ul class="pt-4 mt-4 space-y-2 font-medium border-t border-gray-200 dark:border-gray-700">
                <li>
                    <button type="button" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 group {{ request()->routeIs('admin.portal.*') ? 'bg-gray-200 dark:bg-gray-700' : '' }}" aria-controls="dropdown-portal" data-collapse-toggle="dropdown-portal">
                        <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path fill="#00adff" fill-rule="evenodd" d="M20.424 15.804a5.38 5.38 0 0 0 0-7.608S14.81 2.399 14.046 2.07c-.036-.015-.033-.028-.03-.039c.004-.02.007-.031-.31-.031a.86.86 0 0 0-.861.86v2.243c0 .475.385.86.86.86h.933c1.98 0 3.586 1.366 3.586 3.347v5.38c0 1.98-1.605 3.346-3.586 3.346h-.933a.86.86 0 0 0-.86.861v2.242a.86.86 0 0 0 1.2.79c.765-.328 6.38-6.125 6.38-6.125m-9.27-10.701V2.86a.86.86 0 0 0-1.2-.79c-.765.329-6.38 6.125-6.38 6.125a5.38 5.38 0 0 0 0 7.608s5.615 5.797 6.38 6.126a.86.86 0 0 0 1.2-.79v-2.243a.86.86 0 0 0-.86-.86h-.933c-1.98 0-3.586-1.366-3.586-3.347V9.31c0-1.98 1.605-3.346 3.586-3.346h.933a.86.86 0 0 0 .86-.861" clip-rule="evenodd" />
                        </svg>
                        <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Kelolah Informasi</span>
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
                <li>
                    <a href="{{ route('staff.portal.vision-mission') }}" wire:navigate class="flex items-center p-2 text-gray-900 transition duration-75 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white group {{ request()->routeIs('staff.portal.vision-mission') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">
                        <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#00adff" d="M15.827 18.789q.28 0 .477-.197q.196-.196.196-.476q0-.281-.196-.477q-.196-.197-.477-.197t-.477.197t-.196.477t.196.476q.196.197.477.197m2.173 0q.28 0 .477-.197q.196-.196.196-.476q0-.281-.196-.477q-.196-.197-.477-.197t-.477.197t-.196.477t.196.476q.196.197.477.197m2.173 0q.28 0 .477-.197q.196-.196.196-.476q0-.281-.196-.477q-.196-.197-.477-.197t-.477.197t-.196.477t.196.476q.196.197.477.197M18 22.115q-1.671 0-2.835-1.164Q14 19.787 14 18.116t1.165-2.836T18 14.116t2.836 1.164T22 18.116q0 1.67-1.164 2.835Q19.67 22.116 18 22.116M8 8.73h8q.213 0 .356-.144q.144-.144.144-.357t-.144-.356T16 7.731H8q-.213 0-.356.144t-.144.357t.144.356T8 8.73M5.616 20q-.667 0-1.141-.475T4 18.386V5.615q0-.666.475-1.14T5.615 4h12.77q.666 0 1.14.475T20 5.615v5.008q0 .377-.279.587t-.646.136q-.252-.067-.524-.091T18 11.23q-.506 0-.984.08q-.477.08-.939.226q-.112-.025-.25-.031q-.137-.006-.25-.006H8q-.213 0-.356.144T7.5 12t.144.356T8 12.5h6.087q-.758.521-1.332 1.223t-.945 1.546H8q-.213 0-.356.144q-.144.144-.144.357t.144.356t.356.143h3.46q-.108.423-.169.853q-.06.43-.06.878q0 .25.011.527q.012.277.06.567q.048.348-.155.627t-.549.279z"/></svg>
                        <span class="ms-3">Visi & Misi</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('staff.portal.history') }}" wire:navigate class="flex items-center p-2 text-gray-900 transition duration-75 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white group {{ request()->routeIs('staff.portal.history') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">
                        <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><g fill="none"><path fill="url(#fluentColorHistory200)" d="M10 5.5a1 1 0 0 1 1 1V9h1.5a1 1 0 1 1 0 2H10a1 1 0 0 1-1-1V6.5a1 1 0 0 1 1-1"/><path fill="url(#fluentColorHistory201)" d="M6.031 5.5A6 6 0 1 1 4 10a1 1 0 0 0-2 0a8 8 0 1 0 2.5-5.81V3a1 1 0 0 0-2 0v3A1.5 1.5 0 0 0 4 7.5h3a1 1 0 0 0 0-2z"/><defs><linearGradient id="fluentColorHistory200" x1="8.156" x2="20.094" y1="16.45" y2="11.414" gradientUnits="userSpaceOnUse"><stop stop-color="#d373fc"/><stop offset="1" stop-color="#6d37cd"/></linearGradient><linearGradient id="fluentColorHistory201" x1="2" x2="6.295" y1="2.941" y2="20.923" gradientUnits="userSpaceOnUse"><stop stop-color="#0fafff"/><stop offset="1" stop-color="#0067bf"/></linearGradient></defs></g></svg>
                        <span class="ms-3">Sejarah</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('staff.portal.geography') }}" wire:navigate class="flex items-center p-2 text-gray-900 transition duration-75 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white group {{ request()->routeIs('staff.portal.geography') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">
                        <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#00adff" d="M18.364 18.364A8.999 8.999 0 1 0 5.638 5.638a8.999 8.999 0 0 0 12.726 12.726m-.74-11.988a7.953 7.953 0 0 1 0 11.248A8 8 0 0 1 12 19.999a8 8 0 0 1-5.624-2.375a7.954 7.954 0 0 1 0-11.248A8 8 0 0 1 12 4a8 8 0 0 1 5.624 2.375m-.545 7.724a5.5 5.5 0 0 0-8.983-6.038a5.5 5.5 0 1 0 8.983 6.038m-7.009-2.675c.04-1.22.415-2.82 1.365-3.66c.03-.024.065-.02.065.02v3.635c0 .03-.05.075-.075.075h-1.3c-.035 0-.05-.035-.05-.07zm2.43-1.35q0-1.047.005-2.094a.8.8 0 0 0 0-.185c-.005-.06.01-.07.055-.035c.965.8 1.295 2.424 1.39 3.64c0 .039-.03.094-.065.094h-1.3c-.035 0-.085-.04-.085-.07zM8.97 11.5H7.64c-.03 0-.054-.055-.05-.09a4.47 4.47 0 0 1 2.23-3.3q.044-.02.02.026a8.2 8.2 0 0 0-.83 3.3c0 .029-.015.064-.04.064m5.234-3.37a4.47 4.47 0 0 1 2.21 3.3c0 .035-.015.07-.05.07h-1.31c-.05 0-.07-.045-.075-.09a8.1 8.1 0 0 0-.8-3.254q-.029-.061.025-.025m-4.419 7.74a4.49 4.49 0 0 1-2.2-3.29c-.004-.03.02-.08.05-.08H8.97c.03 0 .05.045.05.07c.04 1.15.305 2.235.8 3.264q.039.074-.035.035m1.715-3.275v3.584c0 .08-.05.09-.1.035c-.94-.95-1.24-2.304-1.34-3.614c-.005-.05.02-.1.07-.1h1.28c.04 0 .09.055.09.1zm1 3.6V12.53s.005-.03.015-.03h1.37c.035 0 .06.045.055.075c-.05 1.25-.415 2.735-1.35 3.65c-.045.044-.09.034-.09-.03m1.66-.33c.54-.92.784-2.27.825-3.305c0-.03.02-.06.05-.06h1.33c.03 0 .054.05.05.075c-.196 1.375-1 2.63-2.235 3.314c-.035.015-.04.01-.02-.025"/></svg>
                        <span class="ms-3">Geografis</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('staff.portal.structure') }}" wire:navigate class="flex items-center p-2 text-gray-900 transition duration-75 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white group {{ request()->routeIs('staff.portal.structure') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">
                        <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><path fill="#00adff" d="M11 7a5 5 0 1 1 5.999 4.9V15h5.268A2.733 2.733 0 0 1 25 17.732V20.1a5.002 5.002 0 0 1-1 9.9a5 5 0 0 1-1-9.9v-2.368a.733.733 0 0 0-.733-.733H9.733a.733.733 0 0 0-.733.733V20.1A5.002 5.002 0 0 1 8 30a5 5 0 0 1-1-9.9v-2.368a2.733 2.733 0 0 1 2.733-2.733H15v-3.1A5 5 0 0 1 11 7"/></svg>
                        <span class="ms-3">Struktur Oraganisasi</span>
                    </a>
                </li>
            </ul>
            @endrole
        </div>
    </aside>
</div>
