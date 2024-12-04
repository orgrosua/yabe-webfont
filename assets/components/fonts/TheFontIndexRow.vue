<template>
    <transition mode="out-in">
        <tr v-if="item.isDeleted" class="plugin-deleted-tr inactive deleted">
            <td colspan="7" class="plugin-update colspanchange">
                <strong>{{ item.title }}</strong> was successfully {{ item.deleted_at == null ? 'moved to the Trash' : 'permanently deleted' }}.
            </td>
        </tr>
        <tr v-else-if="item.isRestored" class="plugin-deleted-tr inactive deleted">
            <td colspan="7" class="plugin-update colspanchange">
                <strong>{{ item.title }}</strong> was successfully restored.
            </td>
        </tr>
        <tr v-else :class="{ 'active': item.status && item.deleted_at == null, 'inactive': (!item.status || item.deleted_at != null) }" class="group">
            <th scope="row" :class="{ 'pl:6': !item.status, 'pl:8': item.deleted_at !== null }" class="vertical:middle py:8 ywf-check-column">
                <input v-model="selectedItems" type="checkbox" :value="item.id" :disabled="busy.isBusy" />
            </th>
            <td v-if="item.deleted_at == null" width="1%" class="manage-column vertical:middle">
                <Switch :aria-disabled="busy.isBusy" :checked="item.status" @click="$emit('updateStatus')" @keyup="handleKeyUp" :class="[item.status ? 'bg:sky-60' : 'opacity:.5 bg:gray-20']" class="rel inline-flex p:0 h:24 w:44 flex-shrink:0 cursor:pointer rounded b:2 b:transparent transition-property:color,background-color,border-color,text-decoration-color,fill,stroke transition-duration:200 transition-timing-function:cubic-bezier(0.4,0,0.2,1) box-shadow:rgb(255,255,255)|0|0|0|2,rgb(14,165,233)|0|0|0|4,rgba(0,0,0,0)|0|0|0|0:focus outline:2|solid|transparent:focus">
                    <span :class="[item.status ? 'translateX(20)' : 'translateX(0)']" class="pointer-events:none rel inline-block font:12 h:20 w:20 rounded bg:white transition-property:color,background-color,border-color,text-decoration-color,fill,stroke,opacity,box-shadow,transform,filter,backdrop-filter transition-duration:200 transition-timing-function:cubic-bezier(0.4,0,0.2,1) box-shadow:rgb(255,255,255)|0|0|0|0,rgba(59,130,246,0.5)|0|0|0|0,rgba(0,0,0,0.1)|0|1|3|0,rgba(0,0,0,0.1)|0|1|2|-1">
                        <span aria-hidden="true" :class="[item.status ? 'opacity:0 transition-timing-function:ease-out transition-duration:100' : 'opacity:1 transition-timing-function:ease-in transition-duration:200']" class="abs inset:0 flex h:full w:full align-items:center justify-content:center tw-transition-opacity">
                            <Icon v-if="!item.isUpdatingStatus" icon="fa6-solid:xmark" class="fg:gray-60" />
                            <Icon v-else icon="fa6-solid:spinner" class="animation:rotate|linear|1s|infinite fg:gray-60" />
                        </span>
                        <span aria-hidden="true" :class="[item.status ? 'opacity:1 transition-timing-function:ease-in transition-duration:200' : 'opacity:0 transition-timing-function:ease-out transition-duration:100']" class="abs inset:0 flex h:full w:full align-items:center justify-content:center tw-transition-opacity">
                            <Icon v-if="!item.isUpdatingStatus" icon="fa6-solid:check" class="fg:sky-60" />
                            <Icon v-else icon="fa6-solid:spinner" class="animation:rotate|linear|1s|infinite fg:sky-60" />
                        </span>
                    </span>
                </Switch>
            </td>
            <td width="20%" class="vertical:middle rel">
                <div class="flex align-items:center">
                    <div v-if="item.type === 'google-fonts'" title="Google Fonts" class="flex align-items:center mr:6">
                        <svg class="w:20 h:20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
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
                    <div v-else-if="item.type === 'adobe-fonts'" title="Adobe Fonts" class="flex align-items:center mr:6">
                        <svg class="w:20 h:20" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
                            <g>
                                <rect class="cls-1" fill="#000b1d" y="0.5" width="32" height="31" rx="5.64848"></rect>
                                <path class="cls-2" fill="#fff" d="M17.63921,13.46488c-.74711,2.504-1.37579,4.91772-2.12289,7.28029a12.90012,12.90012,0,0,1-1.47406,3.41265,4.1921,4.1921,0,0,1-3.31166,1.777c-1.02992,0-2.03957-.48468-2.03957-1.5549A1.40176,1.40176,0,0,1,9.92281,23.0876a.61424.61424,0,0,1,.56539.32311c.50483.90867.98951,1.43364,1.21164,1.43364s.40383-.30284.76725-1.61534l2.65045-9.76247-1.90691-.00211a.91358.91358,0,0,1,.30208-1.03289h1.89816a17.53964,17.53964,0,0,1,1.3978-3.43866,5.04817,5.04817,0,0,1,4.36161-2.928c1.51448,0,2.14044.72695,2.14044,1.65589A1.52543,1.52543,0,0,1,22.01837,9.215c-.323,0-.48456-.24228-.58555-.58555-.34326-1.29235-.78752-1.676-1.05007-1.676s-.66638.48456-1.11052,1.49421a25.74394,25.74394,0,0,0-1.343,3.99058l2.30933-.003a.86867.86867,0,0,1-.31678,1.02946Z"></path>
                            </g>
                        </svg>
                    </div>

                    <a v-if="item.type === 'adobe-fonts'" :href="`https://fonts.adobe.com/fonts/${item.slug}`" target="_blank" class="font:semibold">
                        {{ item.title }}
                    </a>
                    <router-link v-else-if="item.deleted_at == null" :to="{ name: getRouteName(), params: { id: item.id } }" :class="{
                        'font:semibold': item.status
                    }">
                        {{ item.title }}
                    </router-link>
                    <template v-else>
                        {{ item.title }}
                    </template>
                    <span class="invisible .group:hover_{visible} fg:gray-40 font-normal pl:4">ID: {{ item.id }}</span>
                </div>
                <div class="row-actions visible">
                    <template v-if="item.type !== 'adobe-fonts'">
                        <template v-if="item.deleted_at == null">
                            <router-link :to="{ name: getRouteName(), params: { id: item.id } }"> {{ __('Edit', 'yabe-webfont') }} </router-link>
                            |
                            <a :class="{ 'cursor:wait': busy.isBusy }" class="fg:red-80 cursor:pointer fg:red-90:hover" @click="$emit('delete')">
                                {{ item.isDeleting ? 'Deleting...' : 'Trash' }}
                            </a>
                        </template>
                        <template v-else>
                            <a :class="{ 'cursor:wait': busy.isBusy }" class="cursor:pointer " @click="$emit('restore')">
                                {{ item.isRestoring ? 'Restoring...' : 'Restore' }}
                            </a>
                            |
                            <a :class="{ 'cursor:wait': busy.isBusy }" class="fg:red-80 cursor:pointer fg:red-90:hover" @click="$emit('delete')">
                                {{ item.isDeleting ? 'Deleting...' : 'Delete Permanently' }}
                            </a>
                        </template>
                    </template>

                </div>
            </td>
            <td width="20%" class="vertical:middle">
                <div class="group flex align-items:center">
                    <span class="group-hover:hidden">
                        {{ item.family }}
                    </span>
                    <div class="hidden group-hover:block font:semibold">
                        <span class="text-[#0050ff]">var</span>(<span class="text-[#1fa87a]">--ywf--family-{{ item.family.replace(/[^a-zA-Z0-9\-_]+/g, '-').toLowerCase() }}</span>)
                    </div>
                </div>
            </td>
            <td width="10%" class="vertical:middle">
                <div class="flex align-items:center {ml:12}>*+*">
                    <span :title="new Date(item.updated_at * 1000)" class="text:underline decoration-dotted text-gray-700">{{ ago(new Date(item.updated_at * 1000)) }}</span>
                </div>
            </td>
            <td class="vertical:middle ">
                <ContentEditable tag="div" v-model="preview.text" :style="previewInlineStyle()" class="preview-text lh:1.25" />
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
    return {
        fontFamily: props.item.family,
        fontSize: `${props.preview.fontSize}px`,
        fontWeight: props.preview.fontWeight,
        fontStyle: 'normal',
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