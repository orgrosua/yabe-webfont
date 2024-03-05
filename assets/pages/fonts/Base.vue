<template>
    <div class="wrap">
        <h2 v-if="$route.name == 'fonts.index'" class="inline-block mr:8">
            {{ __('Fonts', 'yabe-webfont') }}
        </h2>
        <h2 v-else class="inline-block mr:8">
            <router-link :to="{ name: 'fonts' }" class="text:none">{{ __('Fonts', 'yabe-webfont') }}</router-link>
        </h2>
        <router-view>
        </router-view>
        <div class="clear"></div>
    </div>
</template>
  
<script setup>
</script>