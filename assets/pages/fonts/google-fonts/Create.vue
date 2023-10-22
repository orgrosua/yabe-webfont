<template>
    <span class="mr-2 text-2xl">» {{ __('Import Google Fonts', 'yabe-webfont') }} </span>

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
                                <label class="text-sm font-semibold">Font Family</label>
                                <TheSearchFamily :catalog="catalog" v-model="fontData"></TheSearchFamily>
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

                            <!-- TODO: 2nd row -->
                            <div class="col-span-7 flex flex-col gap-1.5">
                                <span class="text-sm font-semibold">Subsets</span>
                                <VueSelect multiple :options="fontData?.subsets.filter((item) => !subsets.includes(item))" v-model="subsets" :searchable="false" placeholder="Choose subsets">
                                    <template v-slot:no-options="{ search, searching }">
                                        <template v-if="!subsets.length">
                                            Please choose a font family first.
                                        </template>
                                    </template>
                                </VueSelect>
                            </div>
                            <div class="col-span-3 flex flex-col gap-1.5">
                                <span class="text-sm font-semibold">File Format</span>
                                <VueSelect multiple :options="variable ? ['woff2'] : ['woff2', 'woff', 'ttf']" v-model="format" :searchable="false" placeholder="Choose formats"></VueSelect>
                            </div>

                            <div class="col-span-2 flex flex-col gap-1.5">
                                <span class="text-sm font-semibold">Variable Fonts</span>
                                <div class="flex-1 flex items-center">
                                    <label for="variable" class="text-sm">
                                        <input :disabled="!fontData?.isSupportVariable" type="checkbox" name="variable" id="variable" v-model="variable">
                                        Yes
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center space-x-2 mt-8 mb-3">
                            <h3 class="flex-1">Variants <input v-if="fontFaces?.length > 0" v-model="selectAll" class="" type="checkbox" title="(De)select All" /></h3>

                            <div class="flex items-center space-x-2 border border-solid py-2 px-2 !border-gray-300">

                                <div class="font-semibold text-base">Preview :</div>

                                <template v-if="variable">
                                    <div v-if="preview.width.current !== 0" class="h-fit flex rounded-md shadow-sm">
                                        <span class="inline-flex items-center rounded-l-md border border-solid !border-r-0 !border-gray-300 bg-gray-50 px-3 text-gray-500 !text-xs">width</span>
                                        <input type="number" v-model="preview.width.current" :min="preview.width.min" :max="preview.width.max" class="!block !min-w-0 w-16 !min-h-0 !h-6 !mx-0 !py-0 !px-2 !border !border-solid !rounded-none !rounded-r-md !border-gray-300 !text-xs" />
                                        <input type="range" v-model="preview.width.current" :min="preview.width.min" :max="preview.width.max" class="w-16 !appearance-none !accent-[#0050FF] !h-1 self-center" />
                                    </div>

                                    <div v-if="preview.weight.current !== 0" class="h-fit flex rounded-md shadow-sm">
                                        <span class="inline-flex items-center rounded-l-md border border-solid !border-r-0 !border-gray-300 bg-gray-50 px-3 text-gray-500 !text-xs">weight</span>
                                        <input type="number" v-model="preview.weight.current" :min="preview.weight.min" :max="preview.weight.max" class="!block !min-w-0 w-16 !min-h-0 !h-6 !mx-0 !py-0 !px-2 !border !border-solid !rounded-none !rounded-r-md !border-gray-300 !text-xs" />
                                        <input type="range" v-model="preview.weight.current" :min="preview.weight.min" :max="preview.weight.max" class="w-16 !appearance-none !accent-[#0050FF] !h-1 self-center" />
                                    </div>
                                </template>

                                <div class="h-fit flex rounded-md shadow-sm">
                                    <span class="inline-flex items-center rounded-l-md border border-solid !border-r-0 !border-gray-300 bg-gray-50 px-3 text-gray-500 !text-xs">size</span>
                                    <div class="h-fit flex items-stretch relative rounded-md shadow-sm">
                                        <input type="number" v-model="preview.fontSize" class="!block !min-w-0 w-16 !min-h-0 !h-6 !mx-0 !py-0 !px-2 !border !border-solid !rounded-none !rounded-r-md !border-gray-300 !text-xs" />
                                        <div class="absolute inset-y-0 right-0 flex py-1 pr-1.5">
                                            <kbd class="inline-flex items-center rounded border border-gray-200 px-1 text-gray-500">px</kbd>
                                        </div>
                                    </div>
                                    <input type="range" v-model="preview.fontSize" max="200" class="w-16 !appearance-none !accent-[#0050FF] !h-1 self-center" />

                                </div>

                            </div>
                        </div>

                        <div class="font-files">
                            <div class="grid gap-4">
                                <template v-for="fontFace in fontFaces">
                                    <TheFontFace :item="fontFace" :preview="preview" :is-variable="variable" :font-data="fontData" />
                                </template>
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

                <TheFontInfoPanel :font-data="fontData" />

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
import { nanoid } from 'nanoid';
import axios from 'axios';
import { ref, reactive, watch, computed, onBeforeMount, onBeforeUnmount } from 'vue';
import { useRouter } from 'vue-router';
import debounce from 'lodash-es/debounce';
import isEqual from 'lodash-es/isEqual';
import uniq from 'lodash-es/uniq';
import sortBy from 'lodash-es/sortBy';
import { useApi } from '../../../library/api';
import { useBusy } from '../../../stores/busy';
import { useNotifier } from '../../../library/notifier';
import { useWordpressNotice } from '../../../stores/wordpressNotice';

import TheFontFace from '../../../components/fonts/google-fonts/TheFontFace.vue';
import { Switch, SwitchGroup, SwitchLabel } from '@headlessui/vue';
import TheSearchFamily from '../../../components/fonts/google-fonts/TheSearchFamily.vue';
import TheFontInfoPanel from '../../../components/fonts/google-fonts/TheFontInfoPanel.vue';

const api = useApi();
const router = useRouter();
const notifier = useNotifier();
const busy = useBusy();
const wordpressNotice = useWordpressNotice();

const catalog = ref([]);
const fontData = ref(null);
const title = ref('');
const display = ref('auto');
const selector = ref('');
const preload = ref(false);
const family = computed(() => fontData.value?.family || '');
const status = ref(true);
const subsets = ref([]);
const format = ref(['woff2']);
const variable = ref(false);
const fontFiles = ref([]);
const fontFaces = ref([]);

const preview = reactive({
    text: ``,
    fontSize: 18,
    lineHeight: 1.5,
    fontFamily: family,
    weight: {
        current: 0,
        min: 0,
        max: 0,
    },
    width: {
        current: 0,
        min: 0,
        max: 0,
    }
});

watch(family, (newFamily, oldFamily) => {
    if (title.value === '' || title.value === oldFamily) {
        title.value = newFamily;
    }
});

watch(fontData, (newValue, oldValue) => {
    if (newValue?.isSupportVariable !== oldValue?.isSupportVariable) {
        variable.value = false;
    }

    if (newValue && Array.isArray(newValue.subsets) && newValue.subsets.length) {
        subsets.value = newValue.subsets.includes('latin') ? ['latin'] : [newValue.subsets[0]];
    } else {
        subsets.value = [];
    }
});

watch(variable, (newValue, oldValue) => {
    format.value = ['woff2'];
    reComputeFontFiles();
});

watch([variable, family], ([newVariable, newFamily], [oldVariable, oldFamily]) => {
    if (newVariable) {
        preview.weight = {
            current: 0,
            min: 0,
            max: 0,
        };
        preview.width = {
            current: 0,
            min: 0,
            max: 0,
        };

        fontData.value?.axes.forEach(axis => {
            if (axis.tag === 'wght') {
                preview.weight.current = axis.defaultValue;
                preview.weight.min = axis.min;
                preview.weight.max = axis.max;
            }

            if (axis.tag === 'wdth') {
                preview.width.current = axis.defaultValue;
                preview.width.min = axis.min;
                preview.width.max = axis.max;
            }
        });
    }
});

watch(subsets, (newValue, oldValue) => {
    if (newValue.length === 0 && Array.isArray(fontData.value?.subsets) && fontData.value?.subsets?.length) {
        subsets.value = fontData.value.subsets.includes('latin') ? ['latin'] : [fontData.value.subsets[0]];
    }
});

watch(format, (newValue, oldValue) => {
    if (newValue.length === 0) {
        format.value = ['woff2'];
    }
});

watch([subsets, family], ([newSubsets, newFamily, newVariable], [oldSubsets, oldFamily, oldVariable]) => {
    if (isEqual(newSubsets.sort(), oldSubsets.sort()) && newFamily === oldFamily && newVariable === oldVariable) {
        return;
    }

    if (newFamily !== '') {
        fetchFontFiles();
    }
});

function reComputeFontFiles() {
    if (variable.value) {
        fontFaces.value = [];

        let wdth = fontData.value.axes.find(a => a.tag === 'wdth');

        if (fontFiles.value.some(file => file.weight === 0 && file.style === 'normal')) {
            fontFaces.value.push({
                id: nanoid(10),
                key: '0',
                weight: 0,
                width: wdth ? `${wdth.min}% ${wdth.max}%` : '',
                style: 'normal',
                isEnabled: true,
                display: '',
                selector: '',
                comment: '',
                preload: false,
            });
        }
        if (fontFiles.value.some(file => file.weight === 0 && file.style === 'italic')) {
            fontFaces.value.push({
                id: nanoid(10),
                key: '0i',
                weight: 0,
                width: wdth ? `${wdth.min}% ${wdth.max}%` : '',
                style: 'italic',
                isEnabled: true,
                display: '',
                selector: '',
                comment: '',
                preload: false,
            });
        }
    } else {
        fontFaces.value = fontData.value?.variants.map(v => {
            let fWeight = Number.parseInt(v);
            let fStyle = 'normal';
            switch (v.replace('regular', '').replace(/[0-9]/g, '').trim()) {
                case 'i':
                    fStyle = 'italic';
                    break;
                case 'o':
                    fStyle = 'oblique';
                    break;
                default:
                    break;
            }

            return {
                id: nanoid(10),
                key: v,
                weight: !isNaN(fWeight) ? fWeight : 400,
                width: '',
                style: fStyle,
                isEnabled: fWeight === 400 || fWeight === 0,
                display: '',
                selector: '',
                comment: '',
                preload: false,
            };
        });
    }
}

function fetchFontFiles() {
    busy.add('fonts.create.google-fonts:fetch-font-files');

    axios
        .request({
            method: 'GET',
            url: `${yabeWebfont.hostedWakufont}/api/fonts/${fontData.value.slug}`,
            params: {
                subsets: subsets.value.join(','),
            },
        })
        .then(response => {
            const files = Object.values(response.data.files);

            fontFiles.value = files.map(file => {
                file.uid = nanoid(10);
                return file;
            });

            reComputeFontFiles();
        })
        .catch(error => {
            notifier.alert(error.message);
        })
        .finally(() => {
            busy.remove('fonts.create.google-fonts:fetch-font-files');
        });
}

const selectAll = computed({
    get() {
        if (fontFaces.value.length > 0) {
            let allChecked = true;
            for (const [index, item] of fontFaces.value.entries()) {
                if (!item.isEnabled) {
                    allChecked = false;
                }
                if (!allChecked) break;
            }
            return allChecked;
        }
        return false;
    },
    set(value) {
        fontFaces.value.forEach((item) => {
            item.isEnabled = value;
        });
    },
});

function fontFormatMap(ext) {
    // https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/src#font_formats
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

    if (!family.value) {
        return css;
    }

    if (variable.value) {
        fontFaces.value.forEach(fontFace => {
            if (fontFace.weight !== 0) {
                return;
            }

            subsets.value.forEach(subset => {
                let files = fontFiles.value.filter(
                    f => f.weight == fontFace.weight
                        && f.style === fontFace.style
                        && f.subsets.includes(subset)
                        && format.value.includes(f.format)
                );

                files.forEach(file => {
                    if (fontFace.comment) {
                        css += `/* ${fontFace.comment} */\n`;
                    }

                    css += `/* ${subset} */\n`;

                    css += `@font-face {\n`;

                    css += `\tfont-family: '${family.value}';\n`;

                    if (fontFace.weight !== 0) {
                        css += `\tfont-weight: ${fontFace.weight};\n`;
                    } else if (preview.weight.min !== 0 || preview.weight.max !== 0) {
                        css += `\tfont-weight: ${preview.weight.min} ${preview.weight.max};\n`;
                    } else {
                        css += `\tfont-weight: 400;\n`;
                    }

                    let slnt = fontData.value.axes.find(a => a.tag === 'slnt');

                    if (slnt !== undefined) {
                        css += `\tfont-style: oblique ${slnt.max * -1}deg ${slnt.min * -1}deg;\n`;
                    } else {
                        css += `\tfont-style: ${fontFace.style};\n`;
                    }

                    let wdth = fontData.value.axes.find(a => a.tag === 'wdth');

                    if (wdth !== undefined) {
                        css += `\tfont-stretch: ${wdth.min}% ${wdth.max}%;\n`;
                    }

                    css += `\tfont-display: ${fontFace.display || display.value};\n`;

                    css += `\tsrc: url('${file.url}') format("${fontFormatMap(file.format)}");\n`;

                    if (file.unicodeRange) {
                        css += `\tunicode-range: ${file.unicodeRange};\n`;
                    }

                    css += `}\n\n`;
                });
            });
        });
    } else {
        fontFaces.value?.forEach(fontFace => {
            if (fontFace.comment) {
                css += `/* ${fontFace.comment} */\n`;
            }

            css += `@font-face {\n`;

            css += `\tfont-family: '${family.value}';\n`;

            css += `\tfont-style: ${fontFace.style};\n`;

            if (fontFace.weight !== 0) {
                css += `\tfont-weight: ${fontFace.weight};\n`;
            } else {
                css += `\tfont-weight: ${preview.weight.min} ${preview.weight.max};\n`;
            }

            css += `\tfont-display: ${fontFace.display || display.value};\n`;

            const formatPrecedence = {
                'woff2': 1,
                'woff': 2,
                'ttf': 3,
                'otf': 4,
                'eot': 5,
            };

            let files = fontFiles.value.filter(
                f => f.weight == fontFace.weight
                    && f.style === fontFace.style
                    && isEqual(uniq(f.subsets).sort(), uniq(subsets.value).sort())
                    && format.value.includes(f.format)
            );

            if (files.length) {
                files = sortBy(files, (f) => formatPrecedence[f.format]);

                css += `\tsrc: `;

                let fileSrc = files.map(f => {
                    return `url('${f.url}') format("${fontFormatMap(f.format)}")`;
                });

                css += fileSrc.join(',\n\t\t');

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

const cssFontFaceRuleFiltered = computed(() => {
    let css = ``;

    if (!family.value) {
        return css;
    }

    if (variable.value) {
        fontFaces.value.forEach(fontFace => {
            if (!fontFace.isEnabled || fontFace.weight !== 0) {
                return;
            }

            subsets.value.forEach(subset => {
                let files = fontFiles.value.filter(
                    f => f.weight == fontFace.weight
                        && f.style === fontFace.style
                        && f.subsets.includes(subset)
                        && format.value.includes(f.format)
                );

                files.forEach(file => {
                    if (fontFace.comment) {
                        css += `/* ${fontFace.comment} */\n`;
                    }

                    css += `/* ${subset} */\n`;

                    css += `@font-face {\n`;

                    css += `\tfont-family: '${family.value}';\n`;

                    if (fontFace.weight !== 0) {
                        css += `\tfont-weight: ${fontFace.weight};\n`;
                    } else if (preview.weight.min !== 0 || preview.weight.max !== 0) {
                        css += `\tfont-weight: ${preview.weight.min} ${preview.weight.max};\n`;
                    } else {
                        css += `\tfont-weight: 400;\n`;
                    }

                    let slnt = fontData.value.axes.find(a => a.tag === 'slnt');

                    if (slnt !== undefined) {
                        css += `\tfont-style: oblique ${slnt.max * -1}deg ${slnt.min * -1}deg;\n`;
                    } else {
                        css += `\tfont-style: ${fontFace.style};\n`;
                    }

                    let wdth = fontData.value.axes.find(a => a.tag === 'wdth');

                    if (wdth !== undefined) {
                        css += `\tfont-stretch: ${wdth.min}% ${wdth.max}%;\n`;
                    }

                    css += `\tfont-display: ${fontFace.display || display.value};\n`;

                    css += `\tsrc: url('${file.url}') format("${fontFormatMap(file.format)}");\n`;

                    if (file.unicodeRange) {
                        css += `\tunicode-range: ${file.unicodeRange};\n`;
                    }

                    css += `}\n\n`;
                });
            });
        });
    } else {
        fontFaces.value?.forEach(fontFace => {
            if (!fontFace.isEnabled) {
                return;
            }

            if (fontFace.comment) {
                css += `/* ${fontFace.comment} */\n`;
            }

            css += `@font-face {\n`;

            css += `\tfont-family: '${family.value}';\n`;

            css += `\tfont-style: ${fontFace.style};\n`;

            if (fontFace.weight !== 0) {
                css += `\tfont-weight: ${fontFace.weight};\n`;
            } else {
                css += `\tfont-weight: ${preview.weight.min} ${preview.weight.max};\n`;
            }

            css += `\tfont-display: ${fontFace.display || display.value};\n`;

            const formatPrecedence = {
                'woff2': 1,
                'woff': 2,
                'ttf': 3,
                'otf': 4,
                'eot': 5,
            };

            let files = fontFiles.value.filter(
                f => f.weight == fontFace.weight
                    && f.style === fontFace.style
                    && isEqual(uniq(f.subsets).sort(), uniq(subsets.value).sort())
                    && format.value.includes(f.format)
            );

            if (files.length) {
                files = sortBy(files, (f) => formatPrecedence[f.format]);

                css += `\tsrc: `;

                let fileSrc = files.map(f => {
                    return `url('${f.url}') format("${fontFormatMap(f.format)}")`;
                });

                css += fileSrc.join(',\n\t\t');

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

    css += cssFontFaceRuleFiltered.value;

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

    // replace url with placeholder
    css = css.replace(/url\(\'.*\'\)/g, `url('FONT_FILE_URL')`);

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
    fontData.value = null;
    title.value = '';
    display.value = 'auto';
    selector.value = '';
    preload.value = false;
    subsets.value = [];
    format.value = ['woff2'];
    variable.value = false;
    fontFiles.value = [];
    fontFaces.value = [];

    preview.text = `The quick brown fox jumps over a lazy dog`;
    preview.fontSize = 18;
    preview.weight = {
        current: 0,
        min: 0,
        max: 0,
    };
    preview.width = {
        current: 0,
        min: 0,
        max: 0,
    };
}

onBeforeMount(() => {
    busy.add('fonts.create.google-fonts:on-before-mount');

    axios
        .request({
            method: 'GET',
            url: `${yabeWebfont.hostedWakufont}/api/fonts`,
        })
        .then(response => {
            catalog.value = response.data.fonts;
            catalog.value.forEach(font => {
                font.subsets = font.subsets.filter(subset => subset !== 'menu');
            });
        })
        .catch(error => {
            notifier.alert(error.message);
        })
        .finally(() => {
            busy.remove('fonts.create.google-fonts:on-before-mount');
        });


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

    busy.add('fonts.create.google-fonts:send-form');

    let promise = api
        .request({
            method: 'POST',
            url: '/fonts/google-fonts/store',
            data: {
                title: title.value,
                status: status.value,
                metadata: {
                    preload: preload.value,
                    selector: selector.value,
                    display: display.value,
                    google_fonts: {
                        variable: variable.value,
                        formats: format.value,
                        font_data: fontData.value,
                        subsets: subsets.value,
                        font_files: fontFiles.value,
                        font_faces: fontFaces.value,
                    }
                }
            },
        })
        .then(response => {
            const editUrl = router.resolve({
                name: 'fonts.edit.google-fonts',
                params: {
                    id: response.data.id,
                },
            }).href;

            wordpressNotice.add({
                type: 'success',
                message: `<p>Google Fonts imported successfully. <a href="${editUrl}">Edit Font</a></p>`,
            });

            resetForm();
        })
        .finally(() => {
            busy.remove('fonts.create.google-fonts:send-form');
        });

    notifier.async(
        promise,
        'Google Fonts imported successfully.',
        undefined,
        'Importing Google Fonts...'
    );
}
</script>

<style scoped>
/* Transition for the CSS Preview syntax highlight */
.css-preview-enter-active,
.css-preview-leave-active {
    transition: opacity 0.5s ease;
}

.css-preview-enter-from,
.css-preview-leave-to {
    opacity: 0;
}
</style>