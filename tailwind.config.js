import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Enums/**/*.php',
        './app/Helpers/**/*.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                theme: {
                    'main'          : "#FEA4A4",
                    'sub'           : "#FFDEDE",
                    'body'          : "#ebe6e6",
                },
                btn: {
                    'enter'         : "#3b82f6",
                    'cancel'        : "#ef4444",
                    'check'         : "#f9f1e4",
                    'open'          : "#16a34a",
                    'close'         : "#ea580c",
                },
                common: {
                    'disabled'      : "#d1d5db",
                },
            },
            spacing: {
                'modal-window'      : "700px",
            },
            width: {
                '1/7'               : '14.2857143%',
                'form-div'          : '600px',
            },
            maxWidth: {
                '8xl': '1440px',
                '9xl': '1600px',
                '10xl': '1800px',
            }
        },
    },

    plugins: [forms],
};