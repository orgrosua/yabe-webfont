<template>
    <div v-if="isActive" :style="{
        position: strategy,
        top: `${y ?? 0}px`,
        left: `${x ?? 0}px`,
        width: 'max-content',
    }" ref="floating" class="floating-ui">
        <div v-html="props.content"></div>
        <div ref="floatingArrow" class="floating-ui-arrow"></div>
    </div>
</template>

<script setup>
import { ref, toRefs, watchEffect } from 'vue';
import { arrow, useFloating, autoUpdate } from '@floating-ui/vue';

const props = defineProps({
    targetRef: {
        type: Object,
    },
    content: {
        type: String,
    },
});

const { targetRef } = toRefs(props);

const floating = ref(null);
const floatingArrow = ref(null);

const { x, y, strategy } = useFloating(targetRef, floating, {
    placement: 'top',
    strategy: 'fixed', // 'absolute' by default
    middleware: [arrow({ element: floatingArrow })],
    whileElementsMounted: autoUpdate,
});

const isActive = ref(false);

function showTooltip() {
    isActive.value = true;
}

function hideTooltip() {
    isActive.value = false;
}

watchEffect(() => {
    if (targetRef.value) {
        [
            ['mouseenter', showTooltip],
            ['mouseleave', hideTooltip],
            ['focus', showTooltip],
            ['blur', hideTooltip],
        ].forEach(([event, listener]) => {
            targetRef.value.addEventListener(event, listener);
        });
    }
});
</script>

<style scoped>
</style>