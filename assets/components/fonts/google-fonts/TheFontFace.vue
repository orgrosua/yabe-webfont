<template>
    <div :class="{ '!border-green-600': item.isEnabled }" class="font-item relative bg-white border border-solid border-gray-300 divide-y divide-x-0 divide-solid divide-gray-300">
        <!-- Header -->

        <div class="font-item__header flex items-stretch divide-x divide-y-0 divide-dashed divide-gray-300/70">
            <div class="flex p-3 items-center">
                <Switch v-model="item.isEnabled" :class="[item.isEnabled ? 'bg-sky-600' : 'bg-gray-200']" class="relative inline-flex p-0 h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                    <span :class="[item.isEnabled ? 'translate-x-5' : 'translate-x-0']" class="pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out">
                        <span aria-hidden="true" :class="[item.isEnabled ? 'opacity-0 ease-out duration-100' : 'opacity-100 ease-in duration-200']" class="absolute inset-0 flex h-full w-full items-center justify-center transition-opacity">
                            <svg class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 12 12">
                                <path d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <span aria-hidden="true" :class="[item.isEnabled ? 'opacity-100 ease-in duration-200' : 'opacity-0 ease-out duration-100']" class="absolute inset-0 flex h-full w-full items-center justify-center transition-opacity">
                            <svg class="h-3 w-3 text-sky-600" fill="currentColor" viewBox="0 0 12 12">
                                <path d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z" />
                            </svg>
                        </span>
                    </span>
                </Switch>
            </div>
            <div ref="weightStyleTooltip" class="font-item__weight grow-0 shrink-0 flex p-3 items-center space-x-2">
                <span v-if="item.weight === 0" :title="weightName" class="opacity-70 text-sm text-[#a7aaad] px-5 py-1 border border-solid rounded-sm border-[#dcdcde] bg-[#f6f7f7]">
                    {{ preview.weight.current }}
                </span>
                <span v-else :title="weightName" class="opacity-70 text-sm text-[#a7aaad] px-5 py-1 border border-solid rounded-sm border-[#dcdcde] bg-[#f6f7f7]">
                    {{ item.weight }}
                </span>
                <span :class="{ 'italic': styleName === 'italic' }" :title="styleName" class="opacity-70 text-sm capitalize text-left text-[#a7aaad] whitespace-nowrap text-clip overflow-hidden px-2 py-1 w-12 border border-solid rounded-sm border-[#dcdcde] bg-[#f6f7f7]">
                    {{ styleName }}
                </span>
                <TheTooltip :target-ref="weightStyleTooltip" content="Font weight & style" />
            </div>
            <div ref="previewTextTooltip" class="flex-1 flex p-3 items-center">
                <ContentEditable tag="div" v-model="preview.text" :style="previewInlineStyle()" class="preview-text leading-tight" />
                <TheTooltip :target-ref="previewTextTooltip" :content="__('Editable preview text', 'yabe-webfont')" />
            </div>
            <button type="button" ref="editTooltip" @click="isShowBody = !isShowBody" v-ripple class="button flex items-center px-4 py-0.5 focus:shadow-none cursor-pointer bg-inherit border-none">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="h-5 w-5 text-blue-700 hover:text-blue-500 fill-current"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                    <path d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.8 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z" />
                </svg>
                <TheTooltip :target-ref="editTooltip" :content="__('Edit', 'yabe-webfont')" />
            </button>
        </div>

        <!-- Body -->
        <div v-if="isShowBody" class="font-item__body divide-x-0 divide-y divide-solid divide-gray-300/70">
            <div class="font-item__body-meta grid grid-cols-12 items-stretch divide-x divide-y-0 divide-dashed divide-gray-300/70">
                <div class="flex col-span-2 flex-col gap-1.5 p-3">
                    <label :for="`display-${item.id}`" class="text-sm font-semibold">Font Display</label>
                    <select name="display" :id="`display-${item.id}`" v-model="item.display" class="capitalize [&_option]:capitalize">
                        <option></option>
                        <option value="auto">auto</option>
                        <option value="block">block</option>
                        <option value="swap">swap</option>
                        <option value="fallback">fallback</option>
                        <option value="optional">optional</option>
                    </select>
                </div>
                <div class="flex col-span-4 flex-col gap-1.5 p-3">
                    <label :for="`comment-${item.id}`" class="text-sm font-semibold">Comment</label>
                    <input type="text" name="comment" :id="`comment-${item.id}`" v-model="item.comment" placeholder="">
                </div>
                <div class="flex col-span-4 flex-col gap-1.5 p-3">
                    <label :for="`selector-${item.id}`" class="text-sm font-semibold">CSS Selector</label>
                    <input type="text" name="selector" :id="`selector-${item.id}`" v-model="item.selector" placeholder="h1, h2, .poetry, .haiku, p, span, #lyric, #description" autocomplete="off">
                </div>
                <div class="flex col-span-2 flex-col gap-1.5 p-3">
                        <label :for="`preload-${item.id}`" class="text-sm font-semibold">Preload</label>
                        <label :for="`preload-${item.id}`" class="text-sm">
                            <input type="checkbox" name="preload" :id="`preload-${item.id}`" v-model="item.preload">
                            Preload files
                        </label>
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
    isVariable: {
        type: Boolean,
    },
    fontData: {
        type: Object,
    },
});

const isShowBody = ref(false);

const weightStyleTooltip = ref(null);
const previewTextTooltip = ref(null);

const editTooltip = ref(null);

function previewInlineStyle() {
    return {
        fontFamily: `'${props.preview.fontFamily}'`,
        fontSize: `${props.preview.fontSize}px`,
        fontWeight: props.item.weight !== 0 ? props.item.weight : props.preview.weight.current,
        fontStyle: props.item.style,
        fontStretch: props.preview.width.current !== 0 ? `${props.preview.width.current}%` : '100%',
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

const styleName = computed(() => {
    if (props.isVariable) {
        let slnt = props.fontData?.axes.find(axis => axis.tag === 'slnt');
        if (slnt !== undefined) {
            return `oblique ${slnt.max*-1}deg ${slnt.min*-1}deg`;
        }
    }

    return props.item.style;
});
</script>
