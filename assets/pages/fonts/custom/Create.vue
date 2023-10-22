<template>
    <span class="mr-2 text-2xl">» {{ __('New', 'yabe-webfont') }} </span>

    <div id="poststuff">
        <form id="post-body" @submit="sendForm" class="metabox-holder columns-2 !columns-auto">
            <div id="post-body-content">
                <div id="titlediv">
                    <div id="titlewrap">
                        <label class="screen-reader-text" for="title">Add title</label>
                        <input type="text" name="title" id="title" size="30" required v-model="title" :placeholder="__('Title', 'yabe-webfont')" autocomplete="off">
                    </div>
                    <div class="inside">
                    </div>
                </div><!-- /titlediv -->
                <div class="postarea wp-editor-expand">

                    <div id="wp-content-wrap" class="wp-core-ui mt-8">
                        <h3>Meta</h3>
                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-4 flex flex-col gap-1.5">
                                <label for="family" class="text-sm font-semibold">Font Family</label>
                                <input type="text" name="family" id="family" v-model="family" required placeholder="Font Family" autofocus autocomplete="off">
                            </div>

                            <div class="col-span-2 flex flex-col gap-1.5">
                                <label for="display" class="text-sm font-semibold">Font Display</label>
                                <select name="display" id="display" v-model="display" class="capitalize [&_option]:capitalize">
                                    <option value="auto">auto</option>
                                    <option value="block">block</option>
                                    <option value="swap">swap</option>
                                    <option value="fallback">fallback</option>
                                    <option value="optional">optional</option>
                                </select>
                            </div>
                            <div class="col-span-4 flex flex-col gap-1.5">
                                <label for="selector" class="text-sm font-semibold">CSS Selector | Fallback Font</label>
                                <input type="text" name="selector" id="selector" v-model="selector" placeholder="h1, .title, #lyric | Arial, Helvetica, sans-serif" autocomplete="off">
                            </div>
                            <div class="col-span-2 flex flex-col gap-1.5">
                                <span class="text-sm font-semibold">Preload</span>
                                <div class="flex-1 flex items-center">
                                    <label for="preload" class="text-sm">
                                        <input type="checkbox" name="preload" id="preload" v-model="preload">
                                        Preload files
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center space-x-2 mt-8 mb-3">
                            <h3 class="flex-1">
                                Variants
                                <svg @click="sortFontFaces" title="Sort variants" class="w-4 h-4 ml-1 cursor-pointer" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                    <path d="M410.7 38c8.3 6 13.3 15.7 13.3 26v96h16c17.7 0 32 14.3 32 32s-14.3 32-32 32H392 344c-17.7 0-32-14.3-32-32s14.3-32 32-32h16V108.4l-5.9 2c-16.8 5.6-34.9-3.5-40.5-20.2s3.5-34.9 20.2-40.5l48-16c9.8-3.3 20.5-1.6 28.8 4.4zM120 32c9 0 17.5 3.8 23.6 10.4l88 96c11.9 13 11.1 33.3-2 45.2s-33.3 11.1-45.2-2L152 146.3V448c0 17.7-14.3 32-32 32s-32-14.3-32-32V146.3L55.6 181.6c-11.9 13-32.2 13.9-45.2 2s-13.9-32.2-2-45.2l88-96C102.5 35.8 111 32 120 32zM405.7 364.9A32 32 0 1 0 378.3 307a32 32 0 1 0 27.4 57.9zm-40.7 54.9C329.6 408.4 304 375.2 304 336c0-48.6 39.4-88 88-88s88 39.4 88 88c0 23.5-7.5 46.3-21.5 65.2L409.7 467c-10.5 14.2-30.6 17.2-44.8 6.7s-17.2-30.6-6.7-44.8l6.8-9.2z" />
                                </svg>
                            </h3>

                            <TheBulkUpload :font-faces="fontFaces" :family="family" />
                            <button type="button" @click="createNewFontFace" v-ripple class="button my-4">Add variant</button>
                        </div>

                        <div class="font-files">
                            <div class="grid gap-4">
                                <Draggable v-model="fontFaces" tag="transition-group" item-key="id" :component-data="{ name: 'font-face' }" ghost-class="dragged-placeholder" animation="200">
                                    <template #item="{ element }">
                                        <div>
                                            <TheFontFace :item="element" :preview="preview" :font-family="family" />
                                        </div>
                                    </template>
                                </Draggable>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- /post-body-content -->
            <div id="postbox-container-1" class="postbox-container">
                <div>
                    <div id="submitdiv" class="postbox ">
                        <div class="postbox-header">
                            <h2 class="">Publish</h2>
                        </div>
                        <div class="inside">
                            <div class="submitbox" id="submitpost">

                                <div id="minor-publishing">
                                    <div id="misc-publishing-actions">
                                        <div class="misc-pub-section">
                                            <!-- Font Info: Publish Status -->
                                            <div class="py-1.5 flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="h-4 w-4 shrink-0 text-[#8c8f94] fill-current"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                                    <path v-if="status" d="M117.8 128H207C286.9-3.7 409.5-8.5 483.9 5.3c11.6 2.2 20.7 11.2 22.8 22.8c13.8 74.4 9 197-122.7 276.9v89.3c0 25.4-13.4 49-35.3 61.9l-88.5 52.5c-7.4 4.4-16.6 4.5-24.1 .2s-12.1-12.2-12.1-20.9l0-114.7c0-22.6-9-44.3-25-60.3s-37.7-25-60.3-25H24c-8.6 0-16.6-4.6-20.9-12.1s-4.2-16.7 .2-24.1l52.5-88.5c13-21.9 36.5-35.3 61.9-35.3zM424 128c0-22.1-17.9-40-40-40s-40 17.9-40 40s17.9 40 40 40s40-17.9 40-40zM166.5 470C117 519.5 .5 511.5 .5 511.5s-8-116.5 41.5-166c34.4-34.4 90.1-34.4 124.5 0s34.4 90.1 0 124.5zm-46.7-36.4c11.4-11.4 11.4-30 0-41.4s-30-11.4-41.4 0c-16.5 16.5-13.8 55.2-13.8 55.2s38.7 2.7 55.2-13.8z" />
                                                    <path v-else d="M156.6 384.9L125.7 354c-8.5-8.5-11.5-20.8-7.7-32.2c3-8.9 7-20.5 11.8-33.8L24 288c-8.6 0-16.6-4.6-20.9-12.1s-4.2-16.7 .2-24.1l52.5-88.5c13-21.9 36.5-35.3 61.9-35.3l82.3 0c2.4-4 4.8-7.7 7.2-11.3C289.1-4.1 411.1-8.1 483.9 5.3c11.6 2.1 20.6 11.2 22.8 22.8c13.4 72.9 9.3 194.8-111.4 276.7c-3.5 2.4-7.3 4.8-11.3 7.2v82.3c0 25.4-13.4 49-35.3 61.9l-88.5 52.5c-7.4 4.4-16.6 4.5-24.1 .2s-12.1-12.2-12.1-20.9V380.8c-14.1 4.9-26.4 8.9-35.7 11.9c-11.2 3.6-23.4 .5-31.8-7.8zM384 168c22.1 0 40-17.9 40-40s-17.9-40-40-40s-40 17.9-40 40s17.9 40 40 40z" />
                                                </svg>

                                                <span class="pl-2.5 pr-2">Status:</span>
                                                <SwitchGroup as="div" :class="{ 'opacity-50': !status }" class="flex items-center">
                                                    <!-- Font Info: Publish Status -->
                                                    <Switch v-model="status" :class="[status ? 'bg-sky-600' : 'bg-gray-200']" class="relative inline-flex p-0 h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                                                        <span :class="[status ? 'translate-x-5' : 'translate-x-0']" class="pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out">
                                                            <span aria-hidden="true" :class="[status ? 'opacity-0 ease-out duration-100' : 'opacity-100 ease-in duration-200']" class="absolute inset-0 flex h-full w-full items-center justify-center transition-opacity">
                                                                <svg class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 12 12">
                                                                    <path d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                                </svg>
                                                            </span>
                                                            <span aria-hidden="true" :class="[status ? 'opacity-100 ease-in duration-200' : 'opacity-0 ease-out duration-100']" class="absolute inset-0 flex h-full w-full items-center justify-center transition-opacity">
                                                                <svg class="h-3 w-3 text-sky-600" fill="currentColor" viewBox="0 0 12 12">
                                                                    <path d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z" />
                                                                </svg>
                                                            </span>
                                                        </span>
                                                    </Switch>
                                                    <SwitchLabel as="span" :class="[status ? 'text-black' : 'text-gray-500']" class="ml-2 font-medium cursor-pointer">
                                                        {{ status ? 'published' : 'draft' }}
                                                    </SwitchLabel>
                                                </SwitchGroup>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                </div>

                                <div id="major-publishing-actions">
                                    <div id="publishing-action">
                                        <button type="submit" name="save" id="save" :disabled="busy.isBusy" class="button button-primary button-large" value="save">Save</button>
                                    </div>
                                    <div class="clear"></div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="flex flex-col space-y-2 border border-solid py-2 px-2 !border-gray-300">

                        <div class="font-semibold text-base">Preview :</div>

                        <div class="h-fit flex rounded-md shadow-sm">
                            <span class="inline-flex items-center rounded-l-md border border-solid !border-r-0 !border-gray-300 bg-gray-50 px-3 text-gray-500 !text-xs">width</span>
                            <input type="number" v-model="preview.width.current" :min="preview.width.min" :max="preview.width.max" class="!block !min-w-0 w-16 !min-h-0 !h-6 !mx-0 !py-0 !px-2 !border !border-solid !rounded-none !rounded-r-md !border-gray-300 !text-xs" />
                            <input type="range" v-model="preview.width.current" :min="preview.width.min" :max="preview.width.max" class="flex-1 !appearance-none !accent-[#0050FF] !h-1 self-center" />
                        </div>

                        <div class="h-fit flex rounded-md shadow-sm">
                            <span class="inline-flex items-center rounded-l-md border border-solid !border-r-0 !border-gray-300 bg-gray-50 px-3 text-gray-500 !text-xs">weight</span>
                            <input type="number" v-model="preview.weight.current" :min="preview.weight.min" :max="preview.weight.max" class="!block !min-w-0 w-16 !min-h-0 !h-6 !mx-0 !py-0 !px-2 !border !border-solid !rounded-none !rounded-r-md !border-gray-300 !text-xs" />
                            <input type="range" v-model="preview.weight.current" :min="preview.weight.min" :max="preview.weight.max" class="flex-1 !appearance-none !accent-[#0050FF] !h-1 self-center" />
                        </div>

                        <div class="h-fit flex rounded-md shadow-sm">
                            <span class="inline-flex items-center rounded-l-md border border-solid !border-r-0 !border-gray-300 bg-gray-50 px-3 text-gray-500 !text-xs">size</span>
                            <div class="h-fit flex items-stretch relative rounded-md shadow-sm">
                                <input type="number" v-model="preview.fontSize" class="!block !min-w-0 w-16 !min-h-0 !h-6 !mx-0 !py-0 !px-2 !border !border-solid !rounded-none !rounded-r-md !border-gray-300 !text-xs" />
                                <div class="absolute inset-y-0 right-0 flex py-1 pr-1.5">
                                    <kbd class="inline-flex items-center rounded border border-gray-200 px-1 text-gray-500">px</kbd>
                                </div>
                            </div>
                            <input type="range" v-model="preview.fontSize" max="200" class="flex-1 !appearance-none !accent-[#0050FF] !h-1 self-center" />
                        </div>

                    </div>
                </div>

            </div>
            <div id="postbox-container-2" class="postbox-container mt-3">
                <Transition name="css-preview">
                    <div v-if="cssPreviewStylesheet" class="mt-4">
                        <h3 class="mt-5">CSS Preview</h3>
                        <highlightjs language="css" :code="cssPreviewStylesheet" />
                    </div>
                </Transition>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, reactive, watch, computed, provide, onBeforeMount, onBeforeUnmount } from 'vue';
import { storeToRefs } from 'pinia';
import { useRouter } from 'vue-router';
import debounce from 'lodash-es/debounce';
import { useApi } from '../../../library/api';
import { useBusy } from '../../../stores/busy';
import { useNotifier } from '../../../library/notifier';
import { useLocalFontStore } from '../../../stores/font/localFont';
import { useWordpressNotice } from '../../../stores/wordpressNotice';

import TheFontFace from '../../../components/fonts/local/TheFontFace.vue';
import TheBulkUpload from '../../../components/fonts/local/TheBulkUpload.vue';
import { Switch, SwitchGroup, SwitchLabel } from '@headlessui/vue';

const api = useApi();
const router = useRouter();
const notifier = useNotifier();
const busy = useBusy();
const wordpressNotice = useWordpressNotice();
const store = useLocalFontStore();

const { fontFaces } = storeToRefs(store);

const title = ref('');
const family = ref('');
const display = ref('auto');
const selector = ref('');
const preload = ref(false);
const status = ref(true);

watch(family, (newFamily, oldFamily) => {
    if (title.value === '' || title.value === oldFamily) {
        title.value = newFamily;
    }
});

const createNewFontFace = () => {
    store.add();
};

const preview = reactive({
    text: ``,
    fontSize: 18,
    lineHeight: 1.5,
    fontFamily: family,
    weight: {
        current: 400,
        min: 1,
        max: 1000,
    },
    width: {
        current: 100,
        min: 25,
        max: 200,
    }
});

provide('fontFamily', family);

function sortFontFaces() {
    fontFaces.value.sort((a, b) => {
        // sort by weight first, then by style (normal before italic)
        if (a.weight === b.weight) {
            if (a.style === 'normal') {
                return -1;
            } else {
                return 1;
            }
        } else {
            return a.weight - b.weight;
        }
    });
}

function fontFormatMap(ext) {
    switch (ext) {
        case 'woff2':
        case 'font/woff2':
            return 'woff2';
        case 'woff':
        case 'font/woff':
            return 'woff';
        case 'ttf':
        case 'font/ttf':
            return 'truetype';
        case 'otf':
        case 'font/otf':
            return 'opentype';
        case 'eot':
        case 'font/eot':
            return 'embedded-opentype';
        default:
            return 'woff2';
    }
};

const cssFontFaceRule = computed(() => {
    let css = ``;

    if (family.value) {
        fontFaces.value.forEach(fontFace => {

            if (fontFace.comment) {
                css += `/* ${fontFace.comment} */\n`;
            }

            css += `@font-face {\n`;

            css += `\tfont-family: '${family.value}';\n`;

            css += `\tfont-style: ${fontFace.style};\n`;

            if (fontFace.weight !== '') {
                css += `\tfont-weight: ${fontFace.weight};\n`;
            }

            if (fontFace.width && fontFace.width !== '') {
                css += `\tfont-stretch: ${fontFace.width};\n`;
            } else {
                css += `\tfont-stretch: 100%;\n`;
            }

            css += `\tfont-display: ${fontFace.display || display.value};\n`;

            if (fontFace.files.length > 0) {
                css += `\tsrc: `;

                let files = fontFace.files.map(file => {
                    let attachment_url = file.attachment_url;
                    try {
                        attachment_url = (new URL(file.attachment_url)).pathname;
                    } catch (e) { }

                    return `url('${attachment_url}') format("${fontFormatMap(file.extension)}")`;
                });

                css += files.join(',\n\t\t');

                css += `;\n`;
            }

            if (fontFace.unicodeRange) {
                css += `\tunicode-range: ${fontFace.unicodeRange};\n`;
            }

            css += `}\n\n`;
        });
    }

    return css;
});

const cssPreviewStylesheet = computed(() => {
    let css = ``;

    if (!family.value) {
        return css;
    }

    css += `\n`;

    css += cssFontFaceRule.value;

    const rootVarName = family.value.replace(/[^a-zA-Z0-9\-_]+/g, '-').toLowerCase();

    let fallbackFamily = '';

    let selectorParts = [];

    if (selector.value) {
        selectorParts = selector.value.split('|').map(s => s.trim());
    }

    if (selectorParts.length > 1 && selectorParts[1]) {
        fallbackFamily = `, ${selectorParts[1]}`;
    }

    css += `:root {\n`;
    css += `\t--ywf--family-${rootVarName}: '${family.value}'${fallbackFamily};\n`;
    css += `}\n\n`;

    if (selectorParts.length > 0 && selectorParts[0]) {
        css += `${selectorParts[0]} {\n\tfont-family: var(--ywf--family-${rootVarName});\n}\n\n`;
    }

    fontFaces.value.forEach(fontFace => {
        if (fontFace.selector) {
            css += `${fontFace.selector} {\n`;
            css += `\tfont-family: var(--ywf--family-${rootVarName});\n`;
            css += `\tfont-style: ${fontFace.style};\n`;
            css += `\tfont-weight: ${fontFace.weight};\n`;
            css += `}\n\n`;
        }
    });

    // replace tabs with 2 spaces
    css = css.replace(/\t/g, '  ');

    // replace <family> placeholder
    css = css.replace(/<family>/g, family.value);

    return css;
});

let fontPreviewStylesheetEl;

watch(cssFontFaceRule, debounce((newCss, oldCss) => {
    if (fontPreviewStylesheetEl) {
        // replace tabs with 2 spaces and assign
        fontPreviewStylesheetEl.innerHTML = newCss.replace(/\t/g, '  ');
    }
}, 1000));

function resetForm() {
    title.value = '';
    family.value = '';
    selector.value = '';
    display.value = 'auto';
    preload.value = false;
    store.reset();
    createNewFontFace();

    preview.text = `The quick brown fox jumps over a lazy dog`;
    preview.fontSize = 18;
    preview.weight = {
        current: 400,
        min: 1,
        max: 1000,
    };
    preview.width = {
        current: 100,
        min: 25,
        max: 200,
    }
}

onBeforeMount(() => {
    resetForm();

    fontPreviewStylesheetEl = document.querySelector('#yabe-webfont-preview');
    if (!fontPreviewStylesheetEl) {
        fontPreviewStylesheetEl = document.createElement('style');
        fontPreviewStylesheetEl.setAttribute('id', 'yabe-webfont-preview');
        document.head.appendChild(fontPreviewStylesheetEl);
    }
});

onBeforeUnmount(() => {
    if (fontPreviewStylesheetEl) {
        document.head.removeChild(fontPreviewStylesheetEl);
    }
});

function sendForm(e) {
    e.preventDefault();

    busy.add('fonts.create.custom:send-form');

    let promise = api
        .request({
            method: 'POST',
            url: '/fonts/custom/store',
            data: {
                title: title.value,
                family: family.value,
                status: status.value,
                font_faces: fontFaces.value,
                metadata: {
                    preload: preload.value,
                    selector: selector.value,
                    display: display.value,
                }
            },
        })
        .then(response => {
            const editUrl = router.resolve({
                name: 'fonts.edit.custom',
                params: {
                    id: response.data.id,
                },
            }).href;

            wordpressNotice.add({
                type: 'success',
                message: `<p>Font saved successfully. <a href="${editUrl}">Edit Font</a></p>`,
            });

            resetForm();
        })
        .finally(() => {
            busy.remove('fonts.create.custom:send-form');
        });

    notifier.async(
        promise,
        'Font stored successfully.',
        undefined,
        'Storing font...'
    );
}
</script>

<style lang="scss">
/* Transition for <TheFontFace/> list */
.font-face-list-move,
.font-face-move,

/* apply transition to moving elements */
.font-face-enter-active,
.font-face-leave-active {
    transition: all 0.5s ease;
}

.font-face-enter-from,
.font-face-leave-to {
    opacity: 0;
    transform: translateX(30px);
}

/* ensure leaving items are taken out of layout flow so that moving
   animations can be calculated correctly. */
/* .font-face-leave-active {
    position: absolute;
} */

/* Transition for the CSS Preview syntax highlight */
.css-preview-enter-active,
.css-preview-leave-active {
    transition: opacity 0.5s ease;
}

.css-preview-enter-from,
.css-preview-leave-to {
    opacity: 0;
}

.dragged-placeholder {
    opacity: 0.3;
}
</style>