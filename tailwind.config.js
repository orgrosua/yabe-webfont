/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './assets/**/*.{js,vue}',
        './src/**/*.{js,php,html}',
    ],
    important: '#yabe-webfont-app',
    prefix: 'tw-',
    theme: {
        extend: {},
    },
    corePlugins: {
        preflight: false,
    },
    plugins: [
    ],
}
