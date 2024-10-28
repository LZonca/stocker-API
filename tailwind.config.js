import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import daisyui from "daisyui";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        "./vendor/robsontenorio/mary/src/View/Components/**/*.php"
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: "#064e3b",
                secondary: "#14532d",
                accent: "#16a34a",
                neutral: "#6b7280",
                base: "#1f2937",
                info: "#6ee7b7",
                success: "#65a30d",
                warning: "#fef08a",
                error: "#7f1d1d",
            },
        },

    },

    plugins: [
        forms,
        typography,
        require("daisyui")
    ],

    daisyui: {
        themes: [
            {
                default_theme: {

                    "primary": "#064e3b",

                    "primary-content": "#cdd9d4",

                    "secondary": "#14532d",

                    "secondary-content": "#cfdbd1",

                    "accent": "#16a34a",

                    "accent-content": "#000a02",

                    "neutral": "#6b7280",

                    "neutral-content": "#e0e1e4",

                    "base-100": "#1f2937",

                    "base-200": "#19222e",

                    "base-300": "#141c26",

                    "base-content": "#cdd0d3",

                    "info": "#6ee7b7",

                    "info-content": "#04130c",

                    "success": "#65a30d",

                    "success-content": "#030a00",

                    "warning": "#fef08a",

                    "warning-content": "#161407",

                    "error": "#7f1d1d",

                    "error-content": "#e8d1ce",
                },
            },
        ],
    },
};
