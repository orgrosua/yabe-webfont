<script setup>
import { ref } from 'vue';
import { nanoid } from 'nanoid';
import prettyBytes from 'pretty-bytes';
import { parse as otParse } from '@konghayao/opentype.js';
// import decompress from 'wawoff2/decompress.mjs';
import decompress from 'wawoff2';

import { useLocalFontStore } from '../../../stores/font/localFont';

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

const store = useLocalFontStore();

let mediaFrame = null;

function uploadFont(e) {
    e.preventDefault();

    if (mediaFrame) {
        mediaFrame.open();
        return;
    }

    mediaFrame = wp.media.frames.file_frame = wp.media({
        title: 'Upload Fonts',
        multiple: true,
        library: {
            type: 'font',
            uploadedTo: null, // Attached to a specific post (ID).
        }
    });

    mediaFrame.on('ready', () => {
        _wpPluploadSettings.defaults.filters.mime_types[0].extensions = 'woff2,woff,ttf,otf,eot';
    });

    mediaFrame.on('insert select', async () => {
        let attachments = mediaFrame.state().get('selection').toJSON();

        attachments.forEach(async (attachment) => {
            // Detect variable font axes
            if (props.item.files.length === 0 && (attachment.mime === 'font/woff2' || attachment.mime === 'font/ttf')) {
                const buffer = await fetch(attachment.url)
                    .then(res => res.arrayBuffer())
                    .then(async ab => {
                        return attachment.mime !== 'font/woff2' ? ab : await decompress(ab).then(ab => Uint8Array.from(ab).buffer);
                    });

                const otFont = otParse(buffer);

                const axes = otFont.tables['fvar']?.axes;

                const wghtVar = axes?.find(axis => axis.tag === 'wght');
                const wdthVar = axes?.find(axis => axis.tag === 'wdth');
                const slntVar = axes?.find(axis => axis.tag === 'slnt');

                if (wghtVar) {
                    props.item.weight = `${wghtVar.minValue} ${wghtVar.maxValue}`;
                }

                if (wdthVar) {
                    props.item.width = `${wdthVar.minValue}% ${wdthVar.maxValue}%`;
                }

                if (slntVar) {
                    props.item.style = `oblique ${slntVar.maxValue*-1}deg ${slntVar.minValue*-1}deg`;
                }
            }

            props.item.files.push({
                uid: nanoid(10),
                attachment_id: attachment.id,
                attachment_url: attachment.url,
                extension: attachment.subtype,
                mime: attachment.mime,
                filesize: attachment.filesizeInBytes,
                name: attachment.name,
            });
        });
    });

    mediaFrame.open();
    mediaFrame.uploader.uploader.param('yabe_webfont_font_upload', true);
}

const isShowBody = ref(false);
const isShowConfirmDeleteBtn = ref(false);

function deleteFontFace() {
    if (isShowConfirmDeleteBtn.value === false) {
        isShowConfirmDeleteBtn.value = true;

        setTimeout(() => {
            isShowConfirmDeleteBtn.value = false;
        }, 3000);
        return;
    }

    if (props.item.files.length > 0 && !confirm('Are you sure you want to delete this font file?')) {
        isShowConfirmDeleteBtn.value = false;
        return;
    }

    store.delete(props.item.id);
}

function deleteFontFile(uid) {
    props.item.files = props.item.files.filter(file => file.uid !== uid);
}

const weightTooltip = ref(null);
const styleTooltip = ref(null);
const previewTextTooltip = ref(null);

const editTooltip = ref(null);
const deleteTooltip = ref(null);

function previewInlineStyle() {
    let stretch = props.item.width;

    if (props.item.width) {
        if (props.item.width.indexOf(' ') > -1) {
            stretch = `${props.preview.width.current}%`;
        }
    } else {
        stretch = '100%';
    }

    return {
        fontFamily: `'${props.preview.fontFamily}'`,
        fontSize: `${props.preview.fontSize}px`,
        fontWeight: typeof props.item.weight === 'string' && props.item.weight.indexOf(' ') > -1 ? props.preview.weight.current : props.item.weight,
        fontStyle: props.item.style,
        fontStretch: `${stretch}`,
    };
}

const weightOptions = [
    { label: '100 (Thin)', value: 100, },
    { label: '200 (Extra Light)', value: 200, },
    { label: '300 (Light)', value: 300, },
    { label: '400 (Regular)', value: 400, },
    { label: '500 (Medium)', value: 500, },
    { label: '600 (Semi Bold)', value: 600, },
    { label: '700 (Bold)', value: 700, },
    { label: '800 (Extra Bold)', value: 800, },
    { label: '900 (Black)', value: 900, },
];
</script>

<template>
    <Transition>
        <div class="font-item rel bg:white b:1|solid|gray-30/.5 {bt:1;bb:0}>*+* {bx:0}>*+* {b:solid}>*+* {b:gray-30/.5}>*+*">
            <!-- Header -->

            <div class="font-item__header flex align-items:stretch {bl:1;br:0}>*+* {by:0}>*+* {b:dashed}>*+* {b:gray-30/.5}>*+*">
                <div ref="weightTooltip" class="font-item__weight flex-grow:0 flex-shrink:0 flex p:12 align-items:center">
                    <VueSelect taggable v-model="item.weight" :reduce="weight => weight.value || weight.label" :create-option="val => weightOptions.find((option) => option.value == val) || { label: val, value: val }" :options="weightOptions" class="w:144">
                        <template #search="{ attributes, events }">
                            <input class="vs__search" :required="!item.weight" v-bind="attributes" v-on="events" />
                        </template>
                    </VueSelect>
                    <TheTooltip :target-ref="weightTooltip" :content="__('Font weight', 'yabe-webfont')" />
                </div>
                <div ref="styleTooltip" class="flex p:12 align-items:center">
                    <VueSelect taggable push-tags v-model="item.style" :options="['normal', 'italic', 'oblique']" :class="{ '{italic}_span': item.style === 'italic' }" class="w:96 {capitalize}_li {capitalize;white-space:nowrap;max-w:90;text:clip;overflow:hidden}_span" />
                    <TheTooltip :target-ref="styleTooltip" content="Font Style" />
                </div>
                <div ref="previewTextTooltip" class="flex:1|1|0% flex p:12 align-items:center">
                    <ContentEditable tag="div" v-model="preview.text" :style="previewInlineStyle()" class="preview-text lh:1.25" />
                    <TheTooltip :target-ref="previewTextTooltip" :content="__('Editable preview text', 'yabe-webfont')" />
                </div>
                <div class="flex p:12 gap:8 align-items:stretch rel">
                    <button type="button" ref="editTooltip" @click="isShowBody = !isShowBody" v-ripple class="button flex align-items:center px:6 py:0.5 focus:shadow-none cursor:pointer bg:inherit b:none">
                        <Icon icon="fa6-solid:pen-to-square" class="h:20 w:20 fg:blue-80 fg:blue-60:hover fill:current" />
                        <TheTooltip :target-ref="editTooltip" :content="__('Edit', 'yabe-webfont')" />
                    </button>
                    <button type="button" ref="deleteTooltip" @click="deleteFontFace" v-ripple class="button flex align-items:center px:6 py:0.5 focus:shadow-none cursor:pointer bg:inherit b:none">
                        <Icon v-if="!isShowConfirmDeleteBtn" icon="fa6-solid:triangle-exclamation" class="h:20 w:20 fg:red-80 fg:red-60:hover fill:current hover:animation:skeleton|2s|infinite" />
                        <Icon v-else icon="fa6-solid:trash" class="h:20 w:20 fg:red-80 fg:red-60:hover fill:current" />
                        <TheTooltip v-if="!isShowConfirmDeleteBtn" :target-ref="deleteTooltip" :content="__('Delete', 'yabe-webfont')" />
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div v-if="isShowBody" class="font-item__body {bx:0}>*+* {bt:1;bb:0}>*+* {b:solid}>*+* {b:gray-30/.5}>*+*">
                <div class="font-item__body-meta grid grid-cols:12 align-items:stretch {bl:1;br:0}>*+* {by:0}>*+* {b:dashed}>*+* {b:gray-30/.5}>*+*">
                    <div class="flex grid-col-span:2 flex:col gap:6 p:12">
                        <label :for="`width-${item.id}`" class="font:14 lh:20px font:semibold">Width</label>
                        <input type="text" name="width" :id="`width-${item.id}`" v-model="item.width" placeholder="25% 200%">
                    </div>
                    <div class="flex grid-col-span:2 flex:col gap:6 p:12">
                        <label :for="`display-${item.id}`" class="font:14 lh:20px font:semibold">Font Display</label>
                        <select name="display" :id="`display-${item.id}`" v-model="item.display" class="capitalize {capitalize}_option">
                            <option></option>
                            <option value="auto">auto</option>
                            <option value="block">block</option>
                            <option value="swap">swap</option>
                            <option value="fallback">fallback</option>
                            <option value="optional">optional</option>
                        </select>
                    </div>
                    <div class="flex grid-col-span:2 flex:col gap:6 p:12">
                        <label :for="`unicode-${item.id}`" class="font:14 lh:20px font:semibold">Unicode Range</label>
                        <input type="text" name="unicode" :id="`unicode-${item.id}`" v-model="item.unicodeRange" placeholder="U+0000-00FF">
                    </div>
                    <div class="flex grid-col-span:2 flex:col gap:6 p:12">
                        <label :for="`selector-${item.id}`" class="font:14 lh:20px font:semibold">CSS Selector</label>
                        <input type="text" name="selector" :id="`selector-${item.id}`" v-model="item.selector" placeholder="h1, h2, .poetry, .haiku, p, span, #lyric, #description" autocomplete="off">
                    </div>
                    <div class="flex grid-col-span:2 flex:col gap:6 p:12">
                        <label :for="`comment-${item.id}`" class="font:14 lh:20px font:semibold">Comment</label>
                        <input type="text" name="comment" :id="`comment-${item.id}`" v-model="item.comment" placeholder="latin">
                    </div>
                    <div class="flex grid-col-span:2 flex:col gap:6 p:12">
                        <label :for="`preload-${item.id}`" class="font:14 lh:20px font:semibold">Preload</label>
                        <label :for="`preload-${item.id}`" class="font:14 lh:20px">
                            <input type="checkbox" name="preload" :id="`preload-${item.id}`" v-model="item.preload">
                            Preload files
                        </label>
                    </div>
                </div>

                <div class="font-item__body-files grid grid-cols:12 align-items:stretch {bl:1;br:0}>*+* {bt:1;bb:0}>*+* {b:dashed}>*+* {b:gray-30/.5}>*+*">
                    <div class="flex grid-col-span:12 flex:col gap:6 p:12">
                        <button type="button" @click="uploadFont" v-ripple class="button">Upload Fonts</button>
                    </div>
                    <Draggable v-model="item.files" tag="transition-group" item-key="attachment_id" :component-data="{
                        name: 'font-file'
                    }" ghost-class="dragged-placeholder" animation="200">
                        <template #item="{ element }">
                            <div class="flex grid-col-span:6 gap:6 p:12">
                                <div class="flex w:0 flex:1|1|0% align-items:center">
                                    <Icon icon="fa6-solid:font" class="h:20 w:20 flex-shrink:0 fg:gray-40 fill:current" />
                                    <span class="ml:8 w:0 flex:1|1|0% overflow:hidden text:ellipsis white-space:nowrap" :title="`${element.name} (${prettyBytes(element.filesize)})`">[<b>{{ element.extension }}</b>] {{ element.name }} ({{ prettyBytes(element.filesize) }})</span>
                                </div>
                                <button type="button" @click="deleteFontFile(element.uid)" v-ripple class="button flex ml:16 flex-shrink:0 align-items:center focus:shadow-none cursor:pointer bg:inherit b:none fg:gray-50 fg:red-60:hover font:medium">
                                    <Icon icon="fa6-solid:delete-left" class="h:20 w:20 fill:current hover:animation:skeleton|2s|infinite pr:6" />
                                    Delete
                                </button>
                            </div>
                        </template>
                    </Draggable>
                </div>
            </div>
        </div>
    </Transition>
</template>

<style scoped>
/* Transition for font file list */
.font-file-list-move,
.font-file-move,

/* apply transition to moving elements */
.font-file-enter-active,
.font-file-leave-active {
    transition: all 0.5s ease;
}

.font-file-enter-from,
.font-file-leave-to {
    opacity: 0;
    transform: translateX(30px);
}

/* ensure leaving items are taken out of layout flow so that moving
   animations can be calculated correctly. */
/* .font-file-leave-active {
    position: absolute;
} */

.dragged-placeholder {
    opacity: 0.3;
}
</style>