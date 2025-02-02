import './bootstrap';
import '../../vendor/masmerise/livewire-toaster/resources/js';
import 'flowbite';
import { initFlowbite } from "flowbite";

document.addEventListener('livewire:navigated', () => {
    initFlowbite();

    let themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
    let themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
    let themeToggleCheckbox = document.getElementById('theme-toggle');
    if (!themeToggleDarkIcon || !themeToggleLightIcon || !themeToggleCheckbox) {
        return;
    }
    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        themeToggleCheckbox.checked = true;
        themeToggleDarkIcon.classList.remove('hidden');
        themeToggleLightIcon.classList.add('hidden');
    } else {
        themeToggleDarkIcon.classList.add('hidden');
        themeToggleLightIcon.classList.remove('hidden');
        themeToggleCheckbox.checked = false;
    }
    const toggleDarkMode = () => {
        if (themeToggleCheckbox.checked) {
            document.documentElement.classList.add('dark');
            localStorage.setItem('color-theme', 'dark');
            themeToggleDarkIcon.classList.remove('hidden');
            themeToggleLightIcon.classList.add('hidden');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('color-theme', 'light');
            themeToggleDarkIcon.classList.add('hidden');
            themeToggleLightIcon.classList.remove('hidden');
        }
    };
    themeToggleCheckbox.addEventListener('change', toggleDarkMode);

    window.addEventListener('scroll', function() {
        const navbar = document.getElementById('navbar-portal');
        if (window.scrollY > 0) {
            navbar.classList.add('border-b', 'border-gray-200', 'dark:border-gray-600');
        } else {
            navbar.classList.remove('border-b', 'border-gray-200', 'dark:border-gray-600');
        }
    });
});
