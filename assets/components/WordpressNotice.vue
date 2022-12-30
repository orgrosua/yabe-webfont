<template>
    <TransitionGroup name="notice">
        <div v-for="notice in notices" :key="notice.id" :class="`notice notice-${notice.type} is-dismissible`">
            <p v-html="notice.message"></p>
            <button type="button" @click="store.remove(notice.id)" class="notice-dismiss">
                <span class="screen-reader-text">{{ __('Dismiss this notice.', 'yabe-webfont') }}</span>
            </button>
        </div>
    </TransitionGroup>
</template>

<script setup>
import { storeToRefs } from 'pinia';

import { useWordpressNotice } from '../stores/wordpressNotice.js';

const store = useWordpressNotice();
const { notices } = storeToRefs(store);
</script>

<style scoped>
.notice-enter-active,
.notice-leave-active {
    transition: transform 0.4s, opacity 0.6s;
}

.notice-enter-from,
.notice-leave-to {
    opacity: 0;
    transform: scale(0.88);
}
</style>