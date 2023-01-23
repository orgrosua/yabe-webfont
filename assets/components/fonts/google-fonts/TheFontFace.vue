<template>
    <div :class="{ '!tw-border-green-600': item.isEnabled }" class="font-item tw-relative tw-bg-white tw-border tw-border-solid tw-border-gray-300 tw-divide-y tw-divide-x-0 tw-divide-solid tw-divide-gray-300">
        <!-- Header -->

        <div class="font-item__header tw-flex tw-items-stretch tw-divide-x tw-divide-y-0 tw-divide-dashed tw-divide-gray-300/70">
            <div class="tw-flex tw-p-3 tw-items-center">
                <Switch v-model="item.isEnabled" :class="[item.isEnabled ? 'tw-bg-sky-600' : 'tw-bg-gray-200']" class="tw-relative tw-inline-flex tw-p-0 tw-h-6 tw-w-11 tw-flex-shrink-0 tw-cursor-pointer tw-rounded-full tw-border-2 tw-border-transparent tw-transition-colors tw-duration-200 tw-ease-in-out focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-sky-500 focus:tw-ring-offset-2">
                    <span :class="[item.isEnabled ? 'tw-translate-x-5' : 'tw-translate-x-0']" class="tw-pointer-events-none tw-relative tw-inline-block tw-h-5 tw-w-5 tw-transform tw-rounded-full tw-bg-white tw-shadow tw-ring-0 tw-transition tw-duration-200 tw-ease-in-out">
                        <span aria-hidden="true" :class="[item.isEnabled ? 'tw-opacity-0 tw-ease-out tw-duration-100' : 'tw-opacity-100 tw-ease-in tw-duration-200']" class="tw-absolute tw-inset-0 tw-flex tw-h-full tw-w-full tw-items-center tw-justify-center tw-transition-opacity">
                            <svg class="tw-h-3 tw-w-3 tw-text-gray-400" fill="none" viewBox="0 0 12 12">
                                <path d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <span aria-hidden="true" :class="[item.isEnabled ? 'tw-opacity-100 tw-ease-in tw-duration-200' : 'tw-opacity-0 tw-ease-out tw-duration-100']" class="tw-absolute tw-inset-0 tw-flex tw-h-full tw-w-full tw-items-center tw-justify-center tw-transition-opacity">
                            <svg class="tw-h-3 tw-w-3 tw-text-sky-600" fill="currentColor" viewBox="0 0 12 12">
                                <path d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z" />
                            </svg>
                        </span>
                    </span>
                </Switch>
            </div>
            <div ref="weightStyleTooltip" class="font-item__weight tw-grow-0 tw-shrink-0 tw-flex tw-p-3 tw-items-center tw-space-x-2">
                <span v-if="item.weight === 0" :title="weightName" class="tw-opacity-70 tw-text-sm tw-text-[#a7aaad] tw-px-5 tw-py-1 tw-border tw-border-solid tw-rounded-sm tw-border-[#dcdcde] tw-bg-[#f6f7f7]">
                    {{ preview.weight.current }}
                </span>
                <span v-else :title="weightName" class="tw-opacity-70 tw-text-sm tw-text-[#a7aaad] tw-px-5 tw-py-1 tw-border tw-border-solid tw-rounded-sm tw-border-[#dcdcde] tw-bg-[#f6f7f7]">
                    {{ item.weight }}
                </span>
                <span :class="{ 'tw-italic': item.style === 'italic' }" class="tw-opacity-70 tw-text-sm tw-capitalize tw-text-left tw-text-[#a7aaad] tw-px-2 tw-py-1 tw-w-12 tw-border tw-border-solid tw-rounded-sm tw-border-[#dcdcde] tw-bg-[#f6f7f7]">
                    {{ item.style }}
                </span>
                <TheTooltip :target-ref="weightStyleTooltip" content="Font weight & style" />
            </div>
            <div ref="previewTextTooltip" class="tw-flex-1 tw-flex tw-p-3 tw-items-center">
                <ContentEditable tag="div" v-model="preview.text" :style="previewInlineStyle()" class="preview-text tw-leading-tight" />
                <TheTooltip :target-ref="previewTextTooltip" :content="__('Editable preview text', 'yabe-webfont')" />
            </div>
            <button type="button" ref="editTooltip" @click="isShowBody = !isShowBody" v-ripple class="button tw-flex tw-items-center tw-px-4 tw-py-0.5 focus:tw-shadow-none tw-cursor-pointer tw-bg-inherit tw-border-none">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="tw-h-5 tw-w-5 tw-text-blue-700 hover:tw-text-blue-500 tw-fill-current"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                    <path d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.8 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z" />
                </svg>
                <TheTooltip :target-ref="editTooltip" :content="__('Edit', 'yabe-webfont')" />
            </button>
        </div>

        <!-- Body -->
        <div v-if="isShowBody" class="font-item__body tw-divide-x-0 tw-divide-y tw-divide-solid tw-divide-gray-300/70">
            <div class="font-item__body-meta tw-grid tw-grid-cols-12 tw-items-stretch tw-divide-x tw-divide-y-0 tw-divide-dashed tw-divide-gray-300/70">
                <div class="tw-flex tw-col-span-2 tw-flex-col tw-gap-1.5 tw-p-3">
                    <label :for="`display-${item.id}`" class="tw-text-sm tw-font-semibold">Font Display</label>
                    <select name="display" :id="`display-${item.id}`" v-model="item.display" class="tw-capitalize [&_option]:tw-capitalize">
                        <option></option>
                        <option value="auto">auto</option>
                        <option value="block">block</option>
                        <option value="swap">swap</option>
                        <option value="fallback">fallback</option>
                        <option value="optional">optional</option>
                    </select>
                </div>
                <div class="tw-flex tw-col-span-5 tw-flex-col tw-gap-1.5 tw-p-3">
                    <label :for="`comment-${item.id}`" class="tw-text-sm tw-font-semibold">Comment</label>
                    <input type="text" name="comment" :id="`comment-${item.id}`" v-model="item.comment" placeholder="">
                </div>
                <div class="tw-flex tw-col-span-5 tw-flex-col tw-gap-1.5 tw-p-3">
                    <label :for="`selector-${item.id}`" class="tw-text-sm tw-font-semibold">CSS Selector</label>
                    <input type="text" name="selector" :id="`selector-${item.id}`" v-model="item.selector" placeholder="h1, h2, .poetry, .haiku, p, span, #lyric, #description" autocomplete="off">
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, ref } from 'vue';

import { Switch } from '@headlessui/vue';
import ContentEditable from 'vue-contenteditable';
import TheTooltip from '../../../components/TheTooltip.vue';

const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
    preview: {
        type: Object,
        required: true,
    },
});

const isShowBody = ref(false);

const weightStyleTooltip = ref(null);
const previewTextTooltip = ref(null);

const editTooltip = ref(null);

function previewInlineStyle() {
    return {
        fontFamily: props.preview.fontFamily,
        fontSize: `${props.preview.fontSize}px`,
        fontWeight: props.item.weight !== 0 ? props.item.weight : props.preview.weight.current,
        fontStyle: props.item.style,
    };
}

const weightName = computed(() => {
    let w = props.item.weight !== 0 ? props.item.weight : Math.round(props.preview.weight.current / 100) * 100;
    switch (w) {
        case 100: return 'Thin';
        case 200: return 'Extra Light';
        case 300: return 'Light';
        case 500: return 'Medium';
        case 600: return 'Semi Bold';
        case 700: return 'Bold';
        case 800: return 'Extra Bold';
        case 900: return 'Black';
        case 400:
        default: return 'Regular';
    }
});
</script>
