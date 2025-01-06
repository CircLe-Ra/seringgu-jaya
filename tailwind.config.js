import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import flowbite from "flowbite/plugin";
import { addIconSelectors } from "@iconify/tailwind";




/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        "./node_modules/flowbite/**/*.js",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: [
                    "GT Walsheim Pro",
                    ...defaultTheme.fontFamily.sans,
                ],
            },
            colors: {
                primary: {"50":"#eff6ff","100":"#dbeafe","200":"#bfdbfe","300":"#93c5fd","400":"#60a5fa","500":"#3b82f6","600":"#2563eb","700":"#1d4ed8","800":"#1e40af","900":"#1e3a8a","950":"#172554"}
            }
        },
    },

    plugins: [
        forms,
        flowbite,
        addIconSelectors([
            "mdi-light",
            "mdi",
            "fa-solid",
            "fa-regular",
            "fa-brands",
            "ion",
            "carbon",
            "eos-icons",
            "feather",
            "heroicons",
            "simple-line-icons",
            "entypo",
            "fontisto",
            "twemoji",
            "vscode-icons",
            "codicon",
            "devicon",
            "material-symbols",
            "tabler",
            "lucide",
            "feather",
            "duo-icons"
        ]),
    ],
};
