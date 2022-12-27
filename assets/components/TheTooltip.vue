<template>
    <div v-if="isActive" :style="{
        position: strategy,
        top: `${y ?? 0}px`,
        left: `${x ?? 0}px`,
        width: 'max-content',
    }" ref="floating" class="floating">
        <div v-html="props.content"></div>
        <div ref="floatingArrow" class="floating-arrow"></div>
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
.floating-arrow {
    position: absolute;
    background: #222;
    width: 8px;
    height: 8px;
    transform: rotate(45deg);
}

.floating {
    width: max-content;
    position: absolute;
    top: 0;
    left: 0;
    background: #222;
    color: white;
    font-weight: bold;
    padding: 5px;
    border-radius: 4px;
    font-size: 90%;
}
</style>