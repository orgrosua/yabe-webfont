<template>
    <Transition>
        <div class="font-item tw-relative tw-bg-white tw-border tw-border-solid tw-border-gray-300 tw-divide-y tw-divide-x-0 tw-divide-solid tw-divide-gray-300">
            <!-- Header -->

            <div class="font-item__header tw-flex tw-items-stretch tw-divide-x tw-divide-y-0 tw-divide-dashed tw-divide-gray-300/70">
                <div ref="weightTooltip" class="font-item__weight tw-grow-0 tw-shrink-0 tw-flex tw-p-3 tw-items-center">
                    <VueSelect taggable v-model="item.weight" :reduce="weight => weight.value || weight.label" :create-option="val => weightOptions.find((option) => option.value == val) || { label: val, value: val }" :options="weightOptions" class="tw-w-36">
                        <template #search="{ attributes, events }">
                            <input class="vs__search" :required="!item.weight" v-bind="attributes" v-on="events" />
                        </template>
                    </VueSelect>
                    <TheTooltip :target-ref="weightTooltip" :content="__('Font weight', 'yabe-webfont')" />
                </div>
                <div ref="styleTooltip" class="tw-flex tw-p-3 tw-items-center">
                    <VueSelect taggable push-tags v-model="item.style" :options="['normal', 'italic', 'oblique']" :class="{ '[&_span]:tw-italic': item.style === 'italic' }" class="tw-w-24 [&_span]:tw-capitalize [&_li]:tw-capitalize [&_span]:tw-whitespace-nowrap [&_span]:tw-max-w-[90px] [&_span]:tw-text-clip [&_span]:tw-overflow-hidden" />
                    <TheTooltip :target-ref="styleTooltip" content="Font Style" />
                </div>
                <div ref="previewTextTooltip" class="tw-flex-1 tw-flex tw-p-3 tw-items-center">
                    <ContentEditable tag="div" v-model="preview.text" :style="previewInlineStyle()" class="preview-text tw-leading-tight" />
                    <TheTooltip :target-ref="previewTextTooltip" :content="__('Editable preview text', 'yabe-webfont')" />
                </div>
                <div class="tw-flex tw-p-3 tw-gap-2 tw-items-stretch tw-relative">
                    <button type="button" ref="editTooltip" @click="isShowBody = !isShowBody" v-ripple class="button tw-flex tw-items-center tw-px-1.5 tw-py-0.5 focus:tw-shadow-none tw-cursor-pointer tw-bg-inherit tw-border-none">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="tw-h-5 tw-w-5 tw-text-blue-700 hover:tw-text-blue-500 tw-fill-current"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                            <path d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.8 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z" />
                        </svg>
                        <TheTooltip :target-ref="editTooltip" :content="__('Edit', 'yabe-webfont')" />
                    </button>
                    <button type="button" ref="deleteTooltip" @click="deleteFontFace" v-ripple class="button tw-flex tw-items-center tw-px-1.5 tw-py-0.5 focus:tw-shadow-none tw-cursor-pointer tw-bg-inherit tw-border-none">
                        <svg v-if="!isShowConfirmDeleteBtn" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="tw-h-5 tw-w-5 tw-text-red-700 hover:tw-text-red-500 tw-fill-current hover:tw-animate-pulse"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                            <path d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z" />
                        </svg>
                        <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="tw-h-5 tw-w-5 tw-text-red-700 hover:tw-text-red-500 tw-fill-current"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                            <path d="M256 32c14.2 0 27.3 7.5 34.5 19.8l216 368c7.3 12.4 7.3 27.7 .2 40.1S486.3 480 472 480H40c-14.3 0-27.6-7.7-34.7-20.1s-7-27.8 .2-40.1l216-368C228.7 39.5 241.8 32 256 32zm0 128c-13.3 0-24 10.7-24 24V296c0 13.3 10.7 24 24 24s24-10.7 24-24V184c0-13.3-10.7-24-24-24zm32 224c0-17.7-14.3-32-32-32s-32 14.3-32 32s14.3 32 32 32s32-14.3 32-32z" />
                        </svg>
                        <TheTooltip v-if="!isShowConfirmDeleteBtn" :target-ref="deleteTooltip" :content="__('Delete', 'yabe-webfont')" />
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div v-if="isShowBody" class="font-item__body tw-divide-x-0 tw-divide-y tw-divide-solid tw-divide-gray-300/70">
                <div class="font-item__body-meta tw-grid tw-grid-cols-12 tw-items-stretch tw-divide-x tw-divide-y-0 tw-divide-dashed tw-divide-gray-300/70">
                    <div class="tw-flex tw-col-span-2 tw-flex-col tw-gap-1.5 tw-p-3">
                        <label :for="`width-${item.id}`" class="tw-text-sm tw-font-semibold">Width</label>
                        <input type="text" name="width" :id="`width-${item.id}`" v-model="item.width" placeholder="25% 200%">
                    </div>
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
                    <div class="tw-flex tw-col-span-2 tw-flex-col tw-gap-1.5 tw-p-3">
                        <label :for="`unicode-${item.id}`" class="tw-text-sm tw-font-semibold">Unicode Range</label>
                        <input type="text" name="unicode" :id="`unicode-${item.id}`" v-model="item.unicodeRange" placeholder="U+0000-00FF">
                    </div>
                    <div class="tw-flex tw-col-span-2 tw-flex-col tw-gap-1.5 tw-p-3">
                        <label :for="`selector-${item.id}`" class="tw-text-sm tw-font-semibold">CSS Selector</label>
                        <input type="text" name="selector" :id="`selector-${item.id}`" v-model="item.selector" placeholder="h1, h2, .poetry, .haiku, p, span, #lyric, #description" autocomplete="off">
                    </div>
                    <div class="tw-flex tw-col-span-2 tw-flex-col tw-gap-1.5 tw-p-3">
                        <label :for="`comment-${item.id}`" class="tw-text-sm tw-font-semibold">Comment</label>
                        <input type="text" name="comment" :id="`comment-${item.id}`" v-model="item.comment" placeholder="latin">
                    </div>
                    <div class="tw-flex tw-col-span-2 tw-flex-col tw-gap-1.5 tw-p-3">
                        <label :for="`preload-${item.id}`" class="tw-text-sm tw-font-semibold">Preload</label>
                        <label :for="`preload-${item.id}`" class="tw-text-sm">
                            <input type="checkbox" name="preload" :id="`preload-${item.id}`" v-model="item.preload">
                            Preload files
                        </label>
                    </div>
                </div>

                <div class="font-item__body-files tw-grid tw-grid-cols-12 tw-items-stretch tw-divide-x tw-divide-y tw-divide-dashed tw-divide-gray-300/70">
                    <div class="tw-flex tw-col-span-12 tw-flex-col tw-gap-1.5 tw-p-3">
                        <button type="button" @click="uploadFont" v-ripple class="button">Upload Fonts</button>
                    </div>
                    <Draggable v-model="item.files" tag="transition-group" item-key="attachment_id" :component-data="{
                        name: 'font-file'
                    }" ghost-class="dragged-placeholder" animation="200">
                        <template #item="{ element }">
                            <div class="tw-flex tw-col-span-6 tw-gap-1.5 tw-p-3">
                                <div class="tw-flex tw-w-0 tw-flex-1 tw-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="tw-h-5 tw-w-5 tw-shrink-0 tw-text-gray-400 tw-fill-current"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                        <path d="M254 52.8C249.3 40.3 237.3 32 224 32s-25.3 8.3-30 20.8L57.8 416H32c-17.7 0-32 14.3-32 32s14.3 32 32 32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32h-1.8l18-48H303.8l18 48H320c-17.7 0-32 14.3-32 32s14.3 32 32 32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H390.2L254 52.8zM279.8 304H168.2L224 155.1 279.8 304z" />
                                    </svg>
                                    <span class="tw-ml-2 tw-w-0 tw-flex-1 tw-truncate" :title="`${element.name} (${prettyBytes(element.filesize)})`">[<b>{{ element.extension }}</b>] {{ element.name }} ({{ prettyBytes(element.filesize) }})</span>
                                </div>
                                <button type="button" @click="deleteFontFile(element.uid)" v-ripple class="button tw-flex tw-ml-4 tw-shrink-0 tw-items-center focus:tw-shadow-none tw-cursor-pointer tw-bg-inherit tw-border-none tw-text-gray-500 hover:tw-text-red-500 tw-font-medium">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="tw-h-5 tw-w-5 tw-fill-current hover:tw-animate-pulse tw-pr-1.5"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                        <path d="M576 128c0-35.3-28.7-64-64-64H205.3c-17 0-33.3 6.7-45.3 18.7L9.4 233.4c-6 6-9.4 14.1-9.4 22.6s3.4 16.6 9.4 22.6L160 429.3c12 12 28.3 18.7 45.3 18.7H512c35.3 0 64-28.7 64-64V128zM271 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z" />
                                    </svg>
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

<script setup>
import { reactive, ref } from 'vue';
import { nanoid } from 'nanoid';
import prettyBytes from 'pretty-bytes';

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

    mediaFrame.on('insert select', () => {
        let attachments = mediaFrame.state().get('selection').toJSON();

        attachments.forEach(attachment => {
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