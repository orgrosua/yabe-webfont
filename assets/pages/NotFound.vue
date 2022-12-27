<template>
    <div class="notfound-page tw-max-w-7xl tw-px-4 tw-mx-auto tw-text-2xl">
        <div class="tw-flex tw-my-16 tw-text-left">
            <div class="w-[70%]">
                <h1 class="tw-my-0 tw-font-light tw-text-8xl">404</h1>
                <h2 class="subtitle tw-text-4xl tw-font-semibold tw-m-0">Whoops, this page doesn’t exist :-(</h2>
            </div>
            <div class="tw-mt-0 tw-w-[30%]">
                <!-- <img id="ferris-error" src="/static/images/ferris-error.png" alt="404 not found image"> -->
            </div>
        </div>
    </div>

</template>

<script setup></script>

<style scoped lang="scss">
@font-face {
    font-family: "Alfa Slab One";
    font-weight: 400;
    font-style: normal;
    unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
    src: url("https://cdn.jsdelivr.net/gh/rust-lang/www.rust-lang.org/static/fonts/woff2/AlfaSlabOne-Regular.latin.woff2");
    font-display: swap;
}

@font-face {
    font-family: 'Fira Sans';
    font-style: normal;
    font-weight: 600;
    src: url(https://fonts.bunny.net/fira-sans/files/fira-sans-latin-600-normal.woff2) format('woff2'), url(https://fonts.bunny.net/fira-sans/files/fira-sans-latin-600-normal.woff) format('woff');
    unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}

.notfound-page {

    h1,
    h2 {
        z-index: 999999;
        position: relative;
        letter-spacing: 1px;
    }

    h1 {
        font-family: "Alfa Slab One", serif;
        line-height: 1.2;
        letter-spacing: 1px;
    }

    h2 {
        line-height: 1.25;
        font-family: "Fira Sans", Helvetica, Arial, sans-serif;
        color: #2a3439;
    }
}
</style>