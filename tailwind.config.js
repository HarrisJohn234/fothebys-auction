import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    // Base palette: clean + affluent (white/cream + purple + gold accents)
                    paper: '#FFFFFF',
                    cream: '#FAF8F5',
                    ink: '#14131A',

                    purple: '#3B2A6F', // royal / subtle
                    purpleDark: '#2A1F52',
                    purpleSoft: '#F1EEFA',

                    gold: '#C9A227', // classic gold accent
                    goldSoft: '#FBF4DC',
                },
            },
        },
    },

    plugins: [forms],
};
