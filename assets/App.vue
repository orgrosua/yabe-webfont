<template>
    <h1 class="wp-heading-inline">
        {{ __('Yabe Webfont', 'yabe-webfont') }}
    </h1>
    <div class="yabe-webfont-top-menu tw-p-2">
        <a href="https://rosua.org" target="_blank">Rosua.org</a> |
        <a href="https://rosua.org/blog/" target="_blank">{{ __('News', 'yabe-webfont') }}</a> |
        <a href="https://rosua.org/support-portal" target="_blank">{{ __('Support', 'yabe-webfont') }}</a>
        -
        <span tabindex="0">Version: {{ yabeWebfont._version }}</span>
    </div>
    <hr class="wp-header-end">
    <WordpressNotice />
    <h2 class="nav-tab-wrapper">
        <router-link :to="{ name: 'fonts' }" class="nav-tab" activeClass="nav-tab-active">Fonts</router-link>
        <router-link :to="{ name: 'settings' }" class="nav-tab" activeClass="nav-tab-active">Settings</router-link>
        <a class="nav-tab" href="https://webfont.yabe.land/en" target="_blank">
            Docs
            <svg class="tw-w-[15px] tw-h-[15px]" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" viewBox="0 0 100 100">
                <path fill="currentColor" d="M18.8,85.1h56l0,0c2.2,0,4-1.8,4-4v-32h-8v28h-48v-48h28v-8h-32l0,0c-2.2,0-4,1.8-4,4v56C14.8,83.3,16.6,85.1,18.8,85.1z"></path>
                <polygon fill="currentColor" points="45.7,48.7 51.3,54.3 77.2,28.5 77.2,37.2 85.2,37.2 85.2,14.9 62.8,14.9 62.8,22.9 71.5,22.9"></polygon>
            </svg>
        </a>
        <a v-if="yabeWebfont.lite_edition" class="nav-tab nav-tab-inactive tw-flex tw-flex-column tw-bg-transparent" href="https://webfont.yabe.land/#pricing" target="_blank">
            <div class="tw-flex tw-flex-column tw-items-center">
                Upgrade to
                <svg class="tw-mx-1 tw-text-lg tw-text-rose-700" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 640 512">
                    <path d="M64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H576c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64zm40 128h56c35.3 0 64 28.7 64 64s-28.7 64-64 64H128v40c0 13.3-10.7 24-24 24s-24-10.7-24-24V264 184c0-13.3 10.7-24 24-24zm56 80c8.8 0 16-7.2 16-16s-7.2-16-16-16H128v32h32zm80-56c0-13.3 10.7-24 24-24h56c35.3 0 64 28.7 64 64c0 21.9-11 41.2-27.7 52.7l24.1 38.5c7 11.2 3.6 26-7.6 33.1s-26 3.6-33.1-7.6l-33-52.7H288v40c0 13.3-10.7 24-24 24s-24-10.7-24-24V264 184zm48 56h32c8.8 0 16-7.2 16-16s-7.2-16-16-16H288v32zm200-80c48.6 0 88 39.4 88 88v16c0 48.6-39.4 88-88 88s-88-39.4-88-88V248c0-48.6 39.4-88 88-88zm-40 88v16c0 22.1 17.9 40 40 40s40-17.9 40-40V248c0-22.1-17.9-40-40-40s-40 17.9-40 40z" />
                </svg>
            </div>

            <!-- <span class="tw-mx-1 tw-px-1.5 tw-py-0.5 tw-rounded-md tw-bg-blue-100">Pro</span> -->
            <svg class="tw-w-[15px] tw-h-[15px]" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" viewBox="0 0 100 100">
                <path fill="currentColor" d="M18.8,85.1h56l0,0c2.2,0,4-1.8,4-4v-32h-8v28h-48v-48h28v-8h-32l0,0c-2.2,0-4,1.8-4,4v56C14.8,83.3,16.6,85.1,18.8,85.1z"></path>
                <polygon fill="currentColor" points="45.7,48.7 51.3,54.3 77.2,28.5 77.2,37.2 85.2,37.2 85.2,14.9 62.8,14.9 62.8,22.9 71.5,22.9"></polygon>
            </svg>
        </a>
    </h2>
    <div class="yabe-webfont-content">
        <router-view></router-view>
    </div>
</template>

<script setup>
import WordpressNotice from './components/WordpressNotice.vue';
</script>

<style>
#wpcontent {
    padding: 20px;
}
</style>