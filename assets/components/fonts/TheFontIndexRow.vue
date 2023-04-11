<template>
    <transition mode="out-in">
        <tr v-if="item.isDeleted" class="plugin-deleted-tr inactive deleted">
            <td colspan="5" class="plugin-update colspanchange">
                <strong>{{ item.title }}</strong> was successfully {{ item.deleted_at == null ? 'moved to the Trash' : 'permanently deleted' }}.
            </td>
        </tr>
        <tr v-else-if="item.isRestored" class="plugin-deleted-tr inactive deleted">
            <td colspan="5" class="plugin-update colspanchange">
                <strong>{{ item.title }}</strong> was successfully restored.
            </td>
        </tr>
        <tr v-else :class="{ 'active': item.status && item.deleted_at == null, 'inactive': (!item.status || item.deleted_at != null) }" class="tw-group">
            <th scope="row" :class="{ 'tw-pl-1.5': !item.status, 'tw-pl-2': item.deleted_at !== null }" class="tw-align-middle tw-py-2 ywf-check-column">
                <input v-model="selectedItems" type="checkbox" :value="item.id" :disabled="busy.isBusy" />
            </th>
            <td v-if="item.deleted_at == null" width="1%" class="manage-column tw-align-middle">
                <Switch :aria-disabled="busy.isBusy" :checked="item.status" @click="$emit('updateStatus')" @keyup="handleKeyUp" :class="[item.status ? 'tw-bg-sky-600' : 'tw-opacity-50 tw-bg-gray-200']" class="tw-relative tw-inline-flex tw-p-0 tw-h-6 tw-w-11 tw-shrink-0 tw-cursor-pointer tw-rounded-full tw-border-2 tw-border-transparent tw-transition-colors tw-duration-200 tw-ease-in-out focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-sky-500 focus:tw-ring-offset-2">
                    <span :class="[item.status ? 'tw-translate-x-5' : 'tw-translate-x-0']" class="tw-pointer-events-none tw-relative tw-inline-block tw-h-5 tw-w-5 tw-transform tw-rounded-full tw-bg-white tw-shadow tw-ring-0 tw-transition tw-duration-200 tw-ease-in-out">
                        <span aria-hidden="true" :class="[item.status ? 'tw-opacity-0 tw-ease-out tw-duration-100' : 'tw-opacity-100 tw-ease-in tw-duration-200']" class="tw-absolute tw-inset-0 tw-flex tw-h-full tw-w-full tw-items-center tw-justify-center tw-transition-opacity">
                            <svg v-if="!item.isUpdatingStatus" class="tw-h-3 tw-w-3 tw-text-gray-400" fill="none" viewBox="0 0 12 12">
                                <path d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" class="tw-animate-spin tw-h-3 tw-w-3 tw-text-gray-400" fill="currentColor" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                <path d="M304 48c0-26.5-21.5-48-48-48s-48 21.5-48 48s21.5 48 48 48s48-21.5 48-48zm0 416c0-26.5-21.5-48-48-48s-48 21.5-48 48s21.5 48 48 48s48-21.5 48-48zM48 304c26.5 0 48-21.5 48-48s-21.5-48-48-48s-48 21.5-48 48s21.5 48 48 48zm464-48c0-26.5-21.5-48-48-48s-48 21.5-48 48s21.5 48 48 48s48-21.5 48-48zM142.9 437c18.7-18.7 18.7-49.1 0-67.9s-49.1-18.7-67.9 0s-18.7 49.1 0 67.9s49.1 18.7 67.9 0zm0-294.2c18.7-18.7 18.7-49.1 0-67.9S93.7 56.2 75 75s-18.7 49.1 0 67.9s49.1 18.7 67.9 0zM369.1 437c18.7 18.7 49.1 18.7 67.9 0s18.7-49.1 0-67.9s-49.1-18.7-67.9 0s-18.7 49.1 0 67.9z" />
                            </svg>
                        </span>
                        <span aria-hidden="true" :class="[item.status ? 'tw-opacity-100 tw-ease-in tw-duration-200' : 'tw-opacity-0 tw-ease-out tw-duration-100']" class="tw-absolute tw-inset-0 tw-flex tw-h-full tw-w-full tw-items-center tw-justify-center tw-transition-opacity">
                            <svg v-if="!item.isUpdatingStatus" class="tw-h-3 tw-w-3 tw-text-sky-600" fill="currentColor" viewBox="0 0 12 12">
                                <path d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z" />
                            </svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" class="tw-animate-spin tw-h-3 tw-w-3 tw-text-sky-600" fill="currentColor" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                <path d="M304 48c0-26.5-21.5-48-48-48s-48 21.5-48 48s21.5 48 48 48s48-21.5 48-48zm0 416c0-26.5-21.5-48-48-48s-48 21.5-48 48s21.5 48 48 48s48-21.5 48-48zM48 304c26.5 0 48-21.5 48-48s-21.5-48-48-48s-48 21.5-48 48s21.5 48 48 48zm464-48c0-26.5-21.5-48-48-48s-48 21.5-48 48s21.5 48 48 48s48-21.5 48-48zM142.9 437c18.7-18.7 18.7-49.1 0-67.9s-49.1-18.7-67.9 0s-18.7 49.1 0 67.9s49.1 18.7 67.9 0zm0-294.2c18.7-18.7 18.7-49.1 0-67.9S93.7 56.2 75 75s-18.7 49.1 0 67.9s49.1 18.7 67.9 0zM369.1 437c18.7 18.7 49.1 18.7 67.9 0s18.7-49.1 0-67.9s-49.1-18.7-67.9 0s-18.7 49.1 0 67.9z" />
                            </svg>
                        </span>
                    </span>
                </Switch>
            </td>
            <td width="20%" class="tw-align-middle tw-relative">
                <div class="tw-flex tw-items-center">
                    <div v-if="item.type === 'google-fonts'" title="Google Fonts" class="tw-flex tw-items-center tw-mr-1.5">
                        <svg class="tw-w-5 tw-h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
                            <path fill="none" d="M0 0h16v16H0z" />
                            <path fill="#F29900" d="M13.5 2H8L1 13h5.5z" />
                            <path fill="#1A73E8" d="M8 2h5v11H8z" />
                            <circle fill="#EA4335" cx="3.25" cy="4.25" r="2.25" />
                            <path fill="#0D652D" d="M13.33 10L13 13c-1.66 0-3-1.34-3-3s1.34-3 3-3l.33 3z" />
                            <path fill="#174EA6" d="M10.5 4.5A2.5 2.5 0 0113 2l.45 2.5L13 7a2.5 2.5 0 01-2.5-2.5z" />
                            <path fill="#1A73E8" d="M13 2a2.5 2.5 0 010 5" />
                            <path fill="#34A853" d="M13 7c1.66 0 3 1.34 3 3s-1.34 3-3 3" />
                        </svg>
                    </div>
                    <div v-else-if="item.type === 'adobe-fonts'" title="Adobe Fonts" class="tw-flex tw-items-center tw-mr-1.5">
                        <svg class="tw-w-5 tw-h-5" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
                            <g>
                                <rect class="cls-1" fill="#000b1d" y="0.5" width="32" height="31" rx="5.64848"></rect>
                                <path class="cls-2" fill="#fff" d="M17.63921,13.46488c-.74711,2.504-1.37579,4.91772-2.12289,7.28029a12.90012,12.90012,0,0,1-1.47406,3.41265,4.1921,4.1921,0,0,1-3.31166,1.777c-1.02992,0-2.03957-.48468-2.03957-1.5549A1.40176,1.40176,0,0,1,9.92281,23.0876a.61424.61424,0,0,1,.56539.32311c.50483.90867.98951,1.43364,1.21164,1.43364s.40383-.30284.76725-1.61534l2.65045-9.76247-1.90691-.00211a.91358.91358,0,0,1,.30208-1.03289h1.89816a17.53964,17.53964,0,0,1,1.3978-3.43866,5.04817,5.04817,0,0,1,4.36161-2.928c1.51448,0,2.14044.72695,2.14044,1.65589A1.52543,1.52543,0,0,1,22.01837,9.215c-.323,0-.48456-.24228-.58555-.58555-.34326-1.29235-.78752-1.676-1.05007-1.676s-.66638.48456-1.11052,1.49421a25.74394,25.74394,0,0,0-1.343,3.99058l2.30933-.003a.86867.86867,0,0,1-.31678,1.02946Z"></path>
                            </g>
                        </svg>
                    </div>

                    <a v-if="item.type === 'adobe-fonts'" :href="`https://fonts.adobe.com/fonts/${item.slug}`" target="_blank" class="tw-font-semibold">
                        {{ item.title }}
                    </a>
                    <router-link v-else-if="item.deleted_at == null" :to="{ name: getRouteName(), params: { id: item.id } }" :class="{
                        'tw-font-semibold': item.status
                    }">
                        {{ item.title }}
                    </router-link>
                    <template v-else>
                        {{ item.title }}
                    </template>
                    <span class="tw-invisible group-hover:tw-visible tw-text-gray-400 tw-font-normal tw-pl-1">ID: {{ item.id }}</span>
                </div>
                <div class="row-actions visible">
                    <template v-if="item.type !== 'adobe-fonts'">
                        <template v-if="item.deleted_at == null">
                            <router-link :to="{ name: getRouteName(), params: { id: item.id } }"> {{ __('Edit', 'yabe-webfont') }} </router-link>
                            |
                            <a :class="{ 'tw-cursor-wait': busy.isBusy }" class="tw-text-red-700 tw-cursor-pointer hover:tw-text-red-800" @click="$emit('delete')">
                                {{ item.isDeleting ? 'Deleting...' : 'Trash' }}
                            </a>
                        </template>
                        <template v-else>
                            <a :class="{ 'tw-cursor-wait': busy.isBusy }" class="tw-cursor-pointer " @click="$emit('restore')">
                                {{ item.isRestoring ? 'Restoring...' : 'Restore' }}
                            </a>
                            |
                            <a :class="{ 'tw-cursor-wait': busy.isBusy }" class="tw-text-red-700 tw-cursor-pointer hover:tw-text-red-800" @click="$emit('delete')">
                                {{ item.isDeleting ? 'Deleting...' : 'Delete Permanently' }}
                            </a>
                        </template>
                    </template>

                </div>
            </td>
            <td width="20%" class="tw-align-middle">
                <div class="tw-flex tw-items-center tw-space-x-3">
                    {{ item.family }}
                </div>
            </td>
            <td width="10%" class="tw-align-middle">
                <div class="tw-flex tw-items-center tw-space-x-3">
                    <span :title="new Date(item.updated_at * 1000)" class="tw-underline tw-decoration-dotted tw-text-gray-700">{{ ago(new Date(item.updated_at * 1000)) }}</span>
                </div>
            </td>
            <td class="tw-align-middle ">
                <ContentEditable tag="div" v-model="preview.text" :style="previewInlineStyle()" class="preview-text tw-leading-tight" />
            </td>
        </tr>
    </transition>
</template>

<script setup>
import ago from 's-ago';
import { computed, inject, onBeforeMount, onBeforeUnmount } from 'vue';
import { useBusy } from '../../stores/busy';

import { Switch } from '@headlessui/vue';
import ContentEditable from 'vue-contenteditable';

const busy = useBusy();

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

const emit = defineEmits(['delete', 'restore', 'updateStatus']);

const selectedItems = inject('selectedItems');

function getRouteName() {
    switch (props.item.type) {
        case 'google-fonts':
            return 'fonts.edit.google-fonts';
        case 'custom':
        default:
            return 'fonts.edit.custom';
    }
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

    if (props.item.family) {
        props.item.font_faces.forEach(fontFace => {

            if (fontFace.comment) {
                css += `/* ${fontFace.comment} */\n`;
            }

            css += `@font-face {\n`;

            css += `\tfont-family: '${props.item.family}';\n`;

            css += `\tfont-style: ${fontFace.style};\n`;

            if (fontFace.weight !== '') {
                css += `\tfont-weight: ${fontFace.weight};\n`;
            }

            // if (typeof fontFace.weight !== 'number' && fontFace.weight.split(' ').length > 1) {
            //     css += `\tfont-stretch: 100%;\n`;
            // }

            css += `\tfont-display: ${fontFace.display || props.item.metadata.display};\n`;

            if (fontFace.files.length > 0) {
                css += `\tsrc: `;

                let files = fontFace.files.map(file => {
                    return `url('${file.attachment_url}') format("${fontFormatMap(file.extension)}")`;
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

    if (!props.item.family) {
        return css;
    }

    css += cssFontFaceRule.value;

    // replace tabs with 2 spaces
    css = css.replace(/\t/g, '  ');

    // replace <family> placeholder
    css = css.replace(/<family>/g, props.item.family);

    return css;
});

function previewInlineStyle() {
    let weight = 400;
    let style = 'normal';

    if (props.item.font_faces.length > 0) {
        const fontFace = props.item.font_faces.find(fontFace => {
            return fontFace.files.length > 0;
        });

        if (fontFace) {
            style = fontFace.style;

            if (typeof fontFace.weight === 'number') {
                weight = fontFace.weight;
            } else if (fontFace.weight.split(' ').length > 1) {
                // randomize weight
                let weight_range = fontFace.weight.split(' ');
                weight = Math.floor(Math.random() * (parseInt(weight_range[1]) - parseInt(weight_range[0]) + 1)) + parseInt(weight_range[0]);
            }
        }
    }

    return {
        fontFamily: props.item.family,
        fontSize: `${props.preview.fontSize}px`,
        fontWeight: weight,
        fontStyle: style,
    };
}

let previewStylesheetEl = null;

onBeforeMount(() => {
    previewStylesheetEl = document.querySelector(`#yabe-webfont-preview-${props.item.id}`);
    if (!previewStylesheetEl) {
        previewStylesheetEl = document.createElement('style');
        previewStylesheetEl.setAttribute('id', `yabe-webfont-preview-${props.item.id}`);
        document.head.appendChild(previewStylesheetEl);
        previewStylesheetEl.innerHTML = cssPreviewStylesheet.value;
    }
});

onBeforeUnmount(() => {
    if (previewStylesheetEl) {
        document.head.removeChild(previewStylesheetEl);
    }
});

function handleKeyUp(e) {
    if (e.code === 'Space') {
        e.preventDefault();
        emit('updateStatus');
    }
}
</script>

<style scoped>
.v-leave-active {
    transition: all 0.35s;
}

.v-leave-to,
.v-leave-to td,
.v-leave-to th {
    background-color: #faafaa !important;
}
</style>