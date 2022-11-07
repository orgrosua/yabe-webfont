<template>
    <div class="ywf__font-metabox postbox">
        <div class="inside">
            <div class="tw-flex tw-items-center tw-space-x-4">
                <h2 class="title !tw-mb-3">Variants</h2>
                <div class="tw-h-fit tw-flex tw-rounded-md tw-shadow-sm">
                    <span class="tw-inline-flex tw-items-center tw-rounded-l-md tw-border tw-border-solid !tw-border-r-0 !tw-border-gray-300 tw-bg-gray-50 tw-px-3 tw-text-gray-500 !tw-text-xs">Preview size</span>
                    <input type="number" v-model="previewFontSize" class="!tw-block !tw-min-w-0 tw-w-16 !tw-min-h-0 !tw-h-6 !tw-py-0 !tw-px-2 !tw-border-1  !tw-border-solid !tw-rounded-none !tw-border-gray-300 !tw-text-xs" />
                    <span class="tw-inline-flex tw-items-center tw-rounded-r-md tw-border tw-border-solid !tw-border-l-0 !tw-border-gray-300 tw-bg-gray-50 tw-px-3 tw-text-gray-500 !tw-text-xs">px</span>
                </div>
                <div v-if="variableEnabled" class="tw-h-fit tw-flex tw-rounded-md tw-shadow-sm">
                    <span class="tw-inline-flex tw-items-center tw-rounded-l-md tw-border tw-border-solid !tw-border-r-0 !tw-border-gray-300 tw-bg-gray-50 tw-px-3 tw-text-gray-500 !tw-text-xs">Preview weight</span>
                    <input type="number" v-model="previewVariable.weight" :min="previewVariable.minWeight" :max="previewVariable.maxWeight" step="50" class="!tw-block !tw-min-w-0 tw-w-16 !tw-min-h-0 !tw-h-6 !tw-py-0 !tw-px-2 !tw-border-1 !tw-border-solid !tw-rounded-none !tw-rounded-r-md !tw-border-gray-300 !tw-text-xs" />
                </div>
            </div>
            <div class="flex tw-space-y-2">
                <div v-for="variant in variants" :key="variant.key" class="tw-flex">
                    <div :class="[{ '!tw-border-green-600': variant.isEnabled }, 'bricks-font-variant tw-flex-1 !tw-m-0']">
                        <div class="font-header">
                            <div class="bricks-font-weight-wrapper tw-border-0 tw-border-r tw-border-dashed tw-border-[#ddd] tw-w-1/6" data-balloon="Font variant" data-balloon-pos="top">
                                <SwitchGroup as="div" :class="[{ 'tw-opacity-50': variant.isEnabled && variants.reduce((acc, curr) => curr.isEnabled ? ++acc : acc, 0) === 1 }, 'tw-flex tw-items-center']">
                                    <Switch :disabled="variant.isEnabled && variants.reduce((acc, curr) => curr.isEnabled ? ++acc : acc, 0) === 1" v-model="variant.isEnabled" :class="[variant.isEnabled ? 'tw-bg-sky-600' : 'tw-bg-gray-200', 'tw-relative tw-inline-flex tw-p-0 tw-h-6 tw-w-11 tw-flex-shrink-0 tw-cursor-pointer tw-rounded-full tw-border-2 tw-border-transparent tw-transition-colors tw-duration-200 tw-ease-in-out focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-sky-500 focus:tw-ring-offset-2']">
                                        <span :class="[variant.isEnabled ? 'tw-translate-x-5' : 'tw-translate-x-0', 'tw-pointer-events-none tw-relative tw-inline-block tw-h-5 tw-w-5 tw-transform tw-rounded-full tw-bg-white tw-shadow tw-ring-0 tw-transition tw-duration-200 tw-ease-in-out']">
                                            <span :class="[variant.isEnabled ? 'tw-opacity-0 tw-ease-out tw-duration-100' : 'tw-opacity-100 tw-ease-in tw-duration-200', 'tw-absolute tw-inset-0 tw-flex tw-h-full tw-w-full tw-items-center tw-justify-center tw-transition-opacity']" aria-hidden="true">
                                                <svg class="tw-h-3 tw-w-3 tw-text-gray-400" fill="none" viewBox="0 0 12 12">
                                                    <path d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </span>
                                            <span :class="[variant.isEnabled ? 'tw-opacity-100 tw-ease-in tw-duration-200' : 'tw-opacity-0 tw-ease-out tw-duration-100', 'tw-absolute tw-inset-0 tw-flex tw-h-full tw-w-full tw-items-center tw-justify-center tw-transition-opacity']" aria-hidden="true">
                                                <svg class="tw-h-3 tw-w-3 tw-text-sky-600" fill="currentColor" viewBox="0 0 12 12">
                                                    <path d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z" />
                                                </svg>
                                            </span>
                                        </span>
                                    </Switch>
                                    <SwitchLabel as="span" :class="[variant.isEnabled ? 'tw-text-black' : 'tw-text-gray-500', 'tw-ml-3 tw-text-sm tw-font-medium']">
                                        {{ variableEnabled ? `${previewVariable.weight}${variant.style === 'italic' ? 'i' : ''}` : variant.key }}
                                    </SwitchLabel>
                                </SwitchGroup>
                            </div>
                            <div class="bricks-font-preview tw-w-full" data-balloon="Font preview" data-balloon-pos="top">
                                <ContentEditable tag="div" v-model="previewText" :style="previewInlineStyle(variant)" class="preview-text" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, inject } from 'vue';
import { __ } from '@wordpress/i18n';

import { Switch, SwitchGroup, SwitchLabel } from '@headlessui/vue';
import ContentEditable from 'vue-contenteditable';

let fontFaceStylesheet = document.querySelector('#yabe-webfont-preview-style');

if (!fontFaceStylesheet) {
    fontFaceStylesheet = document.createElement('style');
    fontFaceStylesheet.setAttribute('id', 'yabe-webfont-preview-style');
    document.head.appendChild(fontFaceStylesheet);
}

const notifier = inject('notifier');

const variants = inject('variants');
const font = inject('font');
const subsets = inject('subsets');
const mimes = inject('mimes');

const fontFiles = ref([]);

const variableEnabled = inject('variableEnabled');

// Preview
const previewText = ref(`I can do all things through Christ which strengtheneth me. [Philippians 4:13]`);
const previewFontSize = ref(20);

const previewVariable = ref({
    weight: 0,
    minWeight: 0,
    maxWeight: 0,
});

function previewInlineStyle(variant) {
    return {
        fontFamily: font.value.family,
        fontWeight: variant.weight !== 0 ? variant.weight : previewVariable.value.weight,
        fontStyle: variant.style,
        fontSize: `${previewFontSize.value}px`,
    };
}

watch(() => subsets.value, async (newValue, oldValue) => {
    reloadPreview(newValue);
}, { deep: true, immediate: true });

async function fetchFontDetail(font_slug, subsets) {
    const fetchUrl = new URL(`${yabeWebfontImport.hostedWakufont}/api/fonts/${font_slug}`);
    fetchUrl.searchParams.append('subsets', subsets);

    let font = false;

    await fetch(fetchUrl.toString(), {
        method: 'GET',
        headers: {
            Accept: 'application/json',
        },
    })
        .then(response => response.json())
        .then(data => {
            font = data;
        })
        .catch(error => {
            notifier.alert('Failed to load font detail');
        });

    return font;
}

function reloadPreview() {
    let previewPromise = new Promise(async (resolve, reject) => {
        const fontDetail = await fetchFontDetail(font.value.slug, subsets.value.filter(subset => subset.isEnabled).map(subset => subset.key));

        if (!fontDetail) {
            reject('Error fetching font detail');
            return;
        }

        fontFiles.value = Object.values(fontDetail.files);

        let styleSheetContent = generateStylesheet();

        fontFaceStylesheet.innerHTML = styleSheetContent;

        await document.fonts.ready;

        resolve();
    });

    notifier.asyncBlock(
        previewPromise,
        __('Font loaded', 'yabe-webfont'),
        __('Font failed to load', 'yabe-webfont'),
        __('Loading font', 'yabe-webfont'),
        {
            labels: {
                success: '',
            }
        }
    );
}

watch(variableEnabled, (newValue, oldValue) => {
    if (newValue) {
        mimes.value = [{
            key: 'woff2',
            isEnabled: true,
        }];

        font.value.axes.find(axis => {
            if (axis.tag === 'wght') {
                previewVariable.value.weight = axis.defaultValue;
                previewVariable.value.minWeight = axis.min;
                previewVariable.value.maxWeight = axis.max;
            }
        });

        variants.value = [];

        if (fontFiles.value.some(file => file.weight === 0 && file.style === 'normal')) {
            variants.value.push({
                key: '0',
                isEnabled: true,
                weight: 0,
                style: 'normal',
            });
        }

        if (fontFiles.value.some(file => file.weight === 0 && file.style === 'italic')) {
            variants.value.push({
                key: '0i',
                isEnabled: true,
                weight: 0,
                style: 'italic',
            });
        }
    } else {
        mimes.value = yabeWebfontImport.mimeTypes.map(mime => {
            return {
                key: mime,
                isEnabled: mime === 'woff2',
            }
        });

        variants.value = font.value.variants.map(variant => {
            let fontWeight = Number.parseInt(variant);
            let fontStyle = 'normal';
            switch (variant.replace('regular', '').replace(/[0-9]/g, '').trim()) {
                case 'i':
                    fontStyle = 'italic';
                    break;
                case 'o':
                    fontStyle = 'oblique';
                    break;
                default:
                    break;
            }

            return {
                key: variant,
                weight: !isNaN(fontWeight) ? fontWeight : 400,
                style: fontStyle,
                isEnabled: fontWeight === 400 || fontWeight === 0,
            };
        });
    }

    reloadPreview();
});

function generateStylesheet() {
    let stylesheet = '';

    if (variableEnabled.value) {
        let minWeight = 0;
        let maxWeight = 0;

        font.value.axes.find(axis => {
            if (axis.tag === 'wght') {
                minWeight = axis.min;
                maxWeight = axis.max;
            }
        });

        fontFiles.value.filter(file => file.weight === 0).forEach(file => {
            stylesheet += `
                /* ${file.subsets[0]} */
                @font-face {
                    font-family: '${font.value.family}';
                    font-style: ${file.style};
                    font-weight: ${minWeight} ${maxWeight};
                    src: url('${file.url}') format('${file.format}');
                    ${file.unicodeRange ? `unicode-range: "${file.unicodeRange}";` : ''}
                }
            `;
        });
    } else {
        variants.value.forEach(variant => {
            const woff2File = fontFiles.value.find(file => file.weight === variant.weight && file.style === variant.style && file.format === 'woff2');
            const woffFile = fontFiles.value.find(file => file.weight === variant.weight && file.style === variant.style && file.format === 'woff');
            const ttfFile = fontFiles.value.find(file => file.weight === variant.weight && file.style === variant.style && file.format === 'ttf');

            stylesheet += `
                @font-face {
                    font-family: '${font.value.family}';
                    font-style: ${variant.style};
                    font-weight: ${variant.weight};
                    src: url('${woff2File.url}') format('woff2'), /* Super Modern Browsers */
                        url('${woffFile.url}') format('woff'), /* Modern Browsers */
                        url('${ttfFile.url}') format('truetype'); /* Safari, Android, iOS */
                }
            `;
        });
    }

    return stylesheet;
}
</script>

<style>
.ywf__font-metabox {
    background-color: transparent;
    border: none
}

.ywf__font-metabox .postbox-header {
    display: none !important
}

.ywf__font-metabox .title {
    align-items: center;
    display: flex;
    font-size: 16px !important;
    font-weight: 700 !important;
    margin: 15px 0 !important;
    padding: 0 !important;
}

.ywf__font-metabox .title a {
    display: inline-block;
    margin: 0 5px;
    opacity: .5;
    text-decoration: none
}

.ywf__font-metabox .title a:hover {
    opacity: 1
}

.ywf__font-metabox .inside {
    margin: 0;
    padding: 0
}

.ywf__font-metabox .bricks-font-variant {
    background-color: #fff;
    border: 1px solid #ccd0d4;
    margin-top: 15px
}

.ywf__font-metabox .bricks-font-variant .hide {
    display: none !important
}

.ywf__font-metabox .bricks-font-variant label {
    display: inline-block
}

.ywf__font-metabox .bricks-font-variant .font-header {
    align-items: stretch;
    display: flex
}

.ywf__font-metabox .bricks-font-variant .font-header>* {
    align-items: center;
    display: flex;
    padding: 12px
}

.ywf__font-metabox .bricks-font-variant .font-header .bricks-font-style-wrapper {
    border-left: 1px dashed #ddd;
    border-right: 1px dashed #ddd
}

.ywf__font-metabox .bricks-font-variant .font-header .bricks-font-preview .pangram {
    font-size: 20px
}

.ywf__font-metabox .bricks-font-variant .font-header .actions {
    margin-left: auto
}

.ywf__font-metabox .bricks-font-variant .font-header .actions button {
    box-shadow: none;
    margin-left: 5px;
    outline: none
}

.ywf__font-metabox .bricks-font-variant .font-header .actions button.delete:hover {
    background-color: var(--bricks-text-danger);
    border-color: inherit;
    color: #fff
}

.ywf__font-metabox .bricks-font-variant .font-faces {
    border-top: 1px solid #ddd;
    margin: 0
}

.ywf__font-metabox .bricks-font-variant .font-faces li {
    align-items: center;
    display: flex;
    margin: 12px
}

.ywf__font-metabox .bricks-font-variant .font-faces label {
    cursor: inherit;
    line-height: 1;
    min-width: 86px
}

.ywf__font-metabox .bricks-font-variant .font-faces input {
    flex: 1;
    margin: 0 10px 0 5px
}

.ywf__font-metabox .bricks-font-variant .font-faces input[name=font_slug] {
    display: none !important
}

.ywf__font-metabox .bricks-font-variant .font-faces button {
    min-width: 110px
}
</style>