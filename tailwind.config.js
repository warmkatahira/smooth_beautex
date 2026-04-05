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
                status: {
                    active: {
                        text: "#15803d",   // green-700
                        bg:   "#dcfce7",   // green-100
                        dot:  "#22c55e",   // green-500
                    },
                    inactive: {
                        text: "#4b5563",   // gray-600
                        bg:   "#e5e7eb",   // gray-200
                        dot:  "#9ca3af",   // gray-400
                    },
                    ok: {
                        text: "#166534",   // green-800
                        bg:   "#bbf7d0",   // green-200
                    },
                    ng: {
                        text: "#991b1b",   // red-800
                        bg:   "#fecaca",   // red-200
                    },
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