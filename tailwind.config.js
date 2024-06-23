import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import plugin from 'tailwindcss/plugin';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [
        forms,
        plugin(function({ addBase, theme }) {
            addBase({
                'input[type="time"]::-webkit-datetime-edit-hour-field:focus': {
                    color: theme('colors.blue.700'), // Pas hier de kleur aan naar de standaard Tailwind focus kleur
                },
                'input[type="time"]::-webkit-datetime-edit-minute-field:focus': {
                    color: theme('colors.blue.700'),
                },
                'input[type="time"]::-webkit-datetime-edit-second-field:focus': {
                    color: theme('colors.blue.700'),
                },
                'input[type="time"]::-webkit-datetime-edit-ampm-field:focus': {
                    color: theme('colors.blue.700'),
                },
                'input[type="date"]::-webkit-datetime-edit-year-field:focus': {
                    color: theme('colors.blue.700'),
                },
                'input[type="date"]::-webkit-datetime-edit-month-field:focus': {
                    color: theme('colors.blue.700'),
                },
                'input[type="date"]::-webkit-datetime-edit-day-field:focus': {
                    color: theme('colors.blue.700'),
                },
            });
        }),
    ],
};
