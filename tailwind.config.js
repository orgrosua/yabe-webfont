/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './assets/**/*.{js,vue}',
        './src/**/*.{js,php,html}',
    ],
    important: false,
    prefix: 'tw-',
    theme: {
        extend: {},
    },
    corePlugins: {
        preflight: false,
    },
    plugins: [
        // require('@tailwindcss/forms'),
    ],
}
