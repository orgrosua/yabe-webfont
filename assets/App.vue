<template>
    <h1 class="wp-heading-inline">
        {{ __('Yabe Webfont', 'yabe-webfont') }}
    </h1>
    <div class="yabe-webfont-top-menu p-2">
        <a href="https://rosua.org" target="_blank">Rosua.org</a> |
        <a href="https://rosua.org/support-portal" target="_blank">{{ __('Support', 'yabe-webfont') }}</a> |
        <a href="https://yabe.land" target="_blank">{{ __('More plugins', 'yabe-webfont') }}</a>
        -
        <span tabindex="0">Version: {{ yabeWebfont._version }}</span>
    </div>
    <hr class="wp-header-end">
    <WordpressNotice />
    <h2 class="nav-tab-wrapper">
        <router-link :to="{ name: 'fonts' }" class="nav-tab" activeClass="nav-tab-active">Fonts</router-link>
        <router-link :to="{ name: 'settings' }" class="nav-tab" activeClass="nav-tab-active">Settings</router-link>
        <router-link :to="{ name: 'migrations' }" class="nav-tab" activeClass="nav-tab-active">Migrations</router-link>
        <a class="nav-tab" href="https://webfont.yabe.land/en" target="_blank">
            Docs
            <svg class="w-[15px] h-[15px]" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" viewBox="0 0 100 100">
                <path fill="currentColor" d="M18.8,85.1h56l0,0c2.2,0,4-1.8,4-4v-32h-8v28h-48v-48h28v-8h-32l0,0c-2.2,0-4,1.8-4,4v56C14.8,83.3,16.6,85.1,18.8,85.1z"></path>
                <polygon fill="currentColor" points="45.7,48.7 51.3,54.3 77.2,28.5 77.2,37.2 85.2,37.2 85.2,14.9 62.8,14.9 62.8,22.9 71.5,22.9"></polygon>
            </svg>
        </a>
        <a v-if="yabeWebfont.lite_edition" class="nav-tab nav-tab-inactive flex flex-column bg-transparent" href="https://webfont.yabe.land/#pricing" target="_blank">
            <div class="flex flex-column items-center">
                Upgrade to
                <svg class="mx-1 text-lg text-rose-700" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 640 512">
                    <path d="M64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H576c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64zm40 128h56c35.3 0 64 28.7 64 64s-28.7 64-64 64H128v40c0 13.3-10.7 24-24 24s-24-10.7-24-24V264 184c0-13.3 10.7-24 24-24zm56 80c8.8 0 16-7.2 16-16s-7.2-16-16-16H128v32h32zm80-56c0-13.3 10.7-24 24-24h56c35.3 0 64 28.7 64 64c0 21.9-11 41.2-27.7 52.7l24.1 38.5c7 11.2 3.6 26-7.6 33.1s-26 3.6-33.1-7.6l-33-52.7H288v40c0 13.3-10.7 24-24 24s-24-10.7-24-24V264 184zm48 56h32c8.8 0 16-7.2 16-16s-7.2-16-16-16H288v32zm200-80c48.6 0 88 39.4 88 88v16c0 48.6-39.4 88-88 88s-88-39.4-88-88V248c0-48.6 39.4-88 88-88zm-40 88v16c0 22.1 17.9 40 40 40s40-17.9 40-40V248c0-22.1-17.9-40-40-40s-40 17.9-40 40z" />
                </svg>
            </div>

            <!-- <span class="mx-1 px-1.5 py-0.5 rounded-md bg-blue-100">Pro</span> -->
            <svg class="w-[15px] h-[15px]" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" viewBox="0 0 100 100">
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
import { useWordpressNotice } from './stores/wordpressNotice';
import { nextTick } from 'vue';

const wordpressNotice = useWordpressNotice();

const askForReview = localStorage.getItem('yabe-webfont-ask-for-review') ?? -1;

if (askForReview === -1 || (askForReview !== 'done' && askForReview !== 'never' && new Date() > new Date(askForReview))) {
    wordpressNotice.add({
        type: 'info',
        message: `
        <p>
            <strong>Yabe Webfont</strong> will always try to make you smile. If you smile, please consider giving us a <span class="text-yellow-500">★★★★★</span>  rating. It would mean a lot to us!
        </p>
        <p>
            <button id="ask-for-review-action-done" class="button button-primary">
                <svg class="fa-face-smile-hearts fill-current" style="height: 1em; vertical-align: -.125em; padding-right: 4px;" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="face-smile-hearts" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" data-fa-i2svg=""><path fill="currentColor" d="M448.2 34.3c-1.3 19.7 5.5 39.8 20.5 54.9l55.5 55.8c9.2 9.3 21.1 14.2 33.2 14.9C569.4 189.6 576 222 576 256c0 37.4-8 73-22.5 105.1c-27-15.2-61.9-11.3-84.8 11.8c-25.8 26-27.5 67.1-4.9 94.9C422.8 495.7 373.3 512 320 512c-61.3 0-117.6-21.6-161.7-57.6l13.1-13.1c27.6-27.7 27.6-72.6 0-100.4c-24.8-25-63.6-27.6-91.3-7.7c-1.7-1.2-3.5-2.4-5.2-3.4C67.8 306.5 64 281.7 64 256C64 114.6 178.6 0 320 0c46.7 0 90.5 12.5 128.2 34.3zM205.5 323.9c-6.7 5.8-7.4 15.9-1.6 22.6c22.2 25.7 61 53.5 116.1 53.5s93.8-27.9 116.1-53.5c5.8-6.7 5.1-16.8-1.6-22.6s-16.8-5.1-22.6 1.6C394 346.2 363.4 368 320 368s-74-21.8-91.9-42.5c-5.8-6.7-15.9-7.4-22.6-1.6zm-.7-90.3c17.6-23.5 52.8-23.5 70.4 0c5.3 7.1 15.3 8.5 22.4 3.2s8.5-15.3 3.2-22.4c-30.4-40.5-91.2-40.5-121.6 0c-5.3 7.1-3.9 17.1 3.2 22.4s17.1 3.9 22.4-3.2zm230.4 0c5.3 7.1 15.3 8.5 22.4 3.2s8.5-15.3 3.2-22.4c-30.4-40.5-91.2-40.5-121.6 0c-5.3 7.1-3.9 17.1 3.2 22.4s17.1 3.9 22.4-3.2c17.6-23.5 52.8-23.5 70.4 0zM573.7 11.5c15.2-15.3 39.8-15.3 54.9 0s15.2 40 0 55.2l-55.4 55.8c-7.3 7.3-19.1 7.3-26.3 0L491.4 66.7c-15.2-15.3-15.2-40 0-55.2s39.8-15.3 54.9 0L560 25.3l13.7-13.8zm54.9 384c15.2 15.3 15.2 40 0 55.2l-55.4 55.8c-7.3 7.3-19.1 7.3-26.3 0l-55.5-55.8c-15.2-15.3-15.2-40 0-55.2s39.8-15.3 54.9 0L560 409.3l13.7-13.8c15.2-15.3 39.8-15.3 54.9 0zm-534.9-32c15.2-15.3 39.8-15.3 54.9 0s15.2 40 0 55.2L93.2 474.5c-7.3 7.3-19.1 7.3-26.3 0L11.4 418.7c-15.2-15.3-15.2-40 0-55.2s39.8-15.3 54.9 0L80 377.3l13.7-13.8z"></path></svg>
                OK, you deserve it!
            </button>
            <button id="ask-for-review-action-later" class="button button-secondary float-right ml-2">
                <svg class="fa-hourglass-clock fill-current" style="height: 1em; vertical-align: -.125em; padding-right: 0;" xmlns="http://www.w3.org/2000/svg" height="16" width="18" viewBox="0 0 576 512"><!--!Font Awesome Pro 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc.--><path d="M24 0C10.7 0 0 10.7 0 24S10.7 48 24 48h8V67c0 40.3 16 79 44.5 107.5L158.1 256 76.5 337.5C48 366 32 404.7 32 445v19H24c-13.3 0-24 10.7-24 24s10.7 24 24 24H330.8c-18.3-12.9-34.1-29.2-46.3-48H80V445c0-27.6 11-54 30.5-73.5L192 289.9l64.5 64.5c1.5-19.3 6-37.7 13.2-54.7L225.9 256l81.5-81.5C336 146 352 107.3 352 67V48h8c13.3 0 24-10.7 24-24s-10.7-24-24-24H24zM192 222.1l-81.5-81.5C91 121 80 94.6 80 67V48H304V67c0 27.6-11 54-30.5 73.5L192 222.1zM576 368a144 144 0 1 0 -288 0 144 144 0 1 0 288 0zM432 288c8.8 0 16 7.2 16 16v48h32c8.8 0 16 7.2 16 16s-7.2 16-16 16H432c-8.8 0-16-7.2-16-16V304c0-8.8 7.2-16 16-16z"/></svg>
                Later
            </button>
            <button id="ask-for-review-action-never" class="button button-link button-link-delete float-right">Never</button>
        </p>
        `,
    });

    nextTick(() => {
        document.getElementById('ask-for-review-action-later').addEventListener('click', (el) => {
            const date = new Date();
            date.setDate(date.getDate() + 7);
            localStorage.setItem('yabe-webfont-ask-for-review', date);
            el.target.parentElement.parentElement.nextElementSibling.click();
        });

        document.getElementById('ask-for-review-action-done').addEventListener('click', (el) => {
            localStorage.setItem('yabe-webfont-ask-for-review', 'done');
            window.open('https://wordpress.org/support/plugin/yabe-webfont/reviews/?filter=5/#new-post', '_blank');
            el.target.parentElement.parentElement.nextElementSibling.click();
        });

        document.getElementById('ask-for-review-action-never').addEventListener('click', (el) => {
            localStorage.setItem('yabe-webfont-ask-for-review', 'never');
            el.target.parentElement.parentElement.nextElementSibling.click();
        });
    });
}
</script>

<style>
#wpcontent {
    padding: 20px;
}
</style>