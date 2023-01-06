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
        <tr v-else :class="{ 'active': item.status && item.deleted_at == null, 'inactive': !item.status }">
            <th scope="row" :class="{ 'tw-pl-1.5': !item.status }" class="tw-align-middle tw-py-2 ywf-check-column">
                <input v-model="selectedItems" type="checkbox" :value="item.id" :disabled="busy.isBusy" />
            </th>
            <td width="25%" class="tw-align-middle">
                <strong>
                    {{ item.title }}
                </strong>
                <div class="row-actions visible">
                    <span class="tw-text-gray-400">ID: {{ item.id }}</span>
                    |
                    <template v-if="item.deleted_at == null">
                        <a :class="{ 'tw-cursor-wait': busy.isBusy }" class="tw-text-red-700 tw-cursor-pointer hover:tw-text-red-800" @click="$emit('updateStatus')">
                            <template v-if="item.status">
                                {{ item.isUpdatingStatus ? 'Deactivating...' : 'Deactivate' }}
                            </template>
                            <template v-else>
                                {{ item.isUpdatingStatus ? 'Activating...' : 'Activate' }}
                            </template>
                        </a>
                        |
                        <router-link :to="{ name: 'fonts.edit.custom', params: { id: item.id } }"> {{ __('Edit', 'yabe-webfont') }} </router-link>
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
                </div>
            </td>
            <td width="20%" class="tw-align-middle">
                <div class="tw-flex tw-items-center tw-space-x-3">
                    {{ item.family }}
                </div>
            </td>
            <td class="tw-align-middle ">
                <ContentEditable tag="div" v-model="preview.text" :style="previewInlineStyle()" class="preview-text tw-leading-tight" />
            </td>
        </tr>
    </transition>
</template>

<script setup>
import { computed, inject, onBeforeMount, onBeforeUnmount, onUnmounted } from 'vue';
import { useBusy } from '../../../stores/busy';

import { useApi } from '../../../library/api';
import { useNotifier } from '../../../library/notifier';

import ContentEditable from 'vue-contenteditable';

const busy = useBusy();
const api = useApi();
const notifier = useNotifier();

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

            if (typeof fontFace.weight !== 'number' && fontFace.weight.split(' ').length > 1) {
                css += `\tfont-stretch: 100%;\n`;
            }

            css += `\tfont-display: ${fontFace.display || props.item.metadata.display};\n`;

            if (fontFace.files.length > 0) {
                css += `\tsrc: `;

                let files = fontFace.files.map(file => {
                    return `url('${file.attachment_url}') format(${fontFormatMap(file.extension)})`;
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