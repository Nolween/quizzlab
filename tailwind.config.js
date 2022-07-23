const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/views/**/*.vue',
        './resources/js/components/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Albert Sans', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                "quizzlab-primary": "#049DBF",
                "quizzlab-secondary": "#17A671",
                "quizzlab-ternary": "#D92B2B",
                "quizzlab-quaternary": "#F29F05",
                "quizzlab-quinary": "#F2958D",
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
