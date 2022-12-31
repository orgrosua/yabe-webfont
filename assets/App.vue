<template>
    <h1 class="wp-heading-inline">{{ __('Yabe Webfont', 'yabe-webfont') }}</h1>
    <div class="yabe-webfont-top-menu tw-p-2">
        <a href="https://rosua.org" target="_blank">Rosua.org</a> |
        <a href="https://rosua.org/news/" target="_blank">News</a> |
        <a href="https://l.suabahasa.dev/EyJym" target="_blank">Support</a> |
        <a href="https://l.suabahasa.dev/s7XEu" target="_blank">Lead developer's homepage</a> |
        <a aria-label="more plug-ins" href="https://rosua.org/" target="_blank">More plugins</a> -
        <span tabindex="0">Version: {{ yabeWebfont._version }}</span>
    </div>
    <hr class="wp-header-end">
    <!-- TODO: notification -->
    <WordpressNotice />
    <h2 class="nav-tab-wrapper">
        <router-link :to="{ name: 'fonts' }" class="nav-tab" activeClass="nav-tab-active">Fonts</router-link>
        <router-link :to="{ name: 'settings' }" class="nav-tab" activeClass="nav-tab-active">Settings</router-link>
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