<template>
    <div class="wrap">
        <h2 v-if="$route.name == 'fonts.index'" class="tw-inline-block tw-mr-2">
            {{ __('Fonts', 'yabe-webfont') }}
        </h2>
        <h2 v-else class="tw-inline-block tw-mr-2">
            <router-link :to="{ name: 'fonts' }" class="tw-no-underline">{{ __('Fonts', 'yabe-webfont') }}</router-link>
        </h2>
        <router-view>
        </router-view>
        <div class="clear"></div>
    </div>
</template>
  
<script setup>
</script>