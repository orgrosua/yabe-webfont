<template>
    <div class="wrap">
        <h2 v-if="$route.name == 'migrations.index'" class="inline-block mr:8">
            {{ __('Migrations', 'yabe-webfont') }}
        </h2>
        <h2 v-else class="inline-block mr:8">
            <router-link :to="{ name: 'migrations' }" class="text-decoration:none">{{ __('Migrations', 'yabe-webfont') }}</router-link>
        </h2>
        <router-view>
        </router-view>
        <div class="clear"></div>
    </div>
</template>
  
<script setup>
</script>