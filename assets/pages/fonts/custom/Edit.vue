<template>
    <span class="mr:8 font:24 lh:32px">» {{ __('Edit', 'yabe-webfont') }} </span>

    <div id="poststuff">
        <form id="post-body" @submit="sendForm" class="metabox-holder columns-2 cols:auto">
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

                    <div id="wp-content-wrap" class="wp-core-ui mt:32">
                        <h3>Meta</h3>
                        <div class="grid grid-cols:12 gap:16">
                            <div class="grid-col-span:4 flex flex:col gap:6">
                                <label for="family" class="font:14 lh:20px font:semibold">Font Family</label>
                                <input type="text" name="family" id="family" v-model="family" required placeholder="Font Family" autocomplete="off">
                            </div>

                            <div class="grid-col-span:2 flex flex:col gap:6">
                                <label for="display" class="font:14 lh:20px font:semibold">Font Display</label>
                                <select name="display" id="display" v-model="display" class="capitalize {capitalize}_option">
                                    <option value="auto">auto</option>
                                    <option value="block">block</option>
                                    <option value="swap">swap</option>
                                    <option value="fallback">fallback</option>
                                    <option value="optional">optional</option>
                                </select>
                            </div>
                            <div class="grid-col-span:4 flex flex:col gap:6">
                                <label for="selector" class="font:14 lh:20px font:semibold">CSS Selector | Fallback Font</label>
                                <input type="text" name="selector" id="selector" v-model="selector" placeholder="h1, .title, #lyric | Arial, Helvetica, sans-serif" autocomplete="off">
                            </div>
                            <div class="grid-col-span:2 flex flex:col gap:6">
                                <span class="font:14 lh:20px font:semibold">Preload</span>
                                <div class="flex:1|1|0% flex align-items:center">
                                    <label for="preload" class="font:14 lh:20px">
                                        <input type="checkbox" name="preload" id="preload" v-model="preload">
                                        Preload files
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="flex align-items:center {ml:8}>*+* mt:32 mb:12">
                            <h3 class="flex:1|1|0%">
                                Variants
                                <Icon icon="fa6-solid:arrow-up-1-9" @click="sortFontFaces" title="Sort variants" class="w:16 h:16 ml:4 cursor:pointer" />
                            </h3>

                            <TheBulkUpload :font-faces="fontFaces" :family="family" />
                            <button type="button" @click="createNewFontFace" v-ripple class="button my:16">Add variant</button>
                        </div>

                        <div class="font-files">
                            <div class="grid gap:16">
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
                                            <div class="py:6 flex align-items:center">
                                                <Icon v-if="status" icon="fa6-solid:rocket" class="h:16 w:16 flex-shrink:0 fg:gray-90" />
                                                <Icon v-else icon="fa6-solid:rocket" class="h:16 w:16 flex-shrink:0 fg:gray-40" />
                                                <span class="pl:10 pr:8">Status:</span>
                                                <SwitchGroup as="div" class="flex align-items:center">
                                                    <Switch v-model="status" :class="[status ? 'bg:sky-60' : 'opacity:.5 bg:gray-85']" class="rel inline-flex p:0 h:24 w:44 flex-shrink:0 cursor:pointer rounded b:2 b:transparent transition-property:color,background-color,border-color,text-decoration-color,fill,stroke transition-duration:200 transition-timing-function:cubic-bezier(0.4,0,0.2,1) box-shadow:rgb(255,255,255)|0|0|0|2,rgb(14,165,233)|0|0|0|4,rgba(0,0,0,0)|0|0|0|0:focus outline:2|solid|transparent:focus">
                                                        <span :class="[status ? 'translateX(20)' : 'translateX(0)']" class="pointer-events:none rel inline-block font:12 h:20 w:20 rounded bg:white transition-property:color,background-color,border-color,text-decoration-color,fill,stroke,opacity,box-shadow,transform,filter,backdrop-filter transition-duration:200 transition-timing-function:cubic-bezier(0.4,0,0.2,1) box-shadow:rgb(255,255,255)|0|0|0|0,rgba(59,130,246,0.5)|0|0|0|0,rgba(0,0,0,0.1)|0|1|3|0,rgba(0,0,0,0.1)|0|1|2|-1">
                                                            <span aria-hidden="true" :class="[status ? 'opacity:0 transition-timing-function:ease-out transition-duration:100' : 'opacity:1 transition-timing-function:ease-in transition-duration:200']" class="abs inset:0 flex h:full w:full align-items:center justify-content:center tw-transition-opacity">
                                                                <Icon icon="fa6-solid:xmark" class="fg:gray-60" />
                                                            </span>
                                                            <span aria-hidden="true" :class="[status ? 'opacity:1 transition-timing-function:ease-in transition-duration:200' : 'opacity:0 transition-timing-function:ease-out transition-duration:100']" class="abs inset:0 flex h:full w:full align-items:center justify-content:center tw-transition-opacity">
                                                                <Icon icon="fa6-solid:check" class="fg:sky-60" />
                                                            </span>
                                                        </span>
                                                    </Switch>
                                                    <SwitchLabel as="span" :class="[status ? 'fg:black' : 'fg:gray-60']" class="ml:8 font:medium tw-cursor-pointer">
                                                        {{ status ? 'published' : 'draft' }}
                                                    </SwitchLabel>
                                                </SwitchGroup>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                </div>

                                <div id="major-publishing-actions">
                                    <div id="delete-action">
                                        <a :class="{ 'cursor:wait': busy.isBusy }" class="fg:red-80 text:underline cursor:pointer fg:red-90:hover" @click="doDelete">
                                            {{ isDeleting ? 'Deleting...' : 'Trash' }}
                                        </a>
                                    </div>

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
                    <div class="flex flex:col {mt:8}>*+* py:8 px:8 b:1|solid|gray-30/.5">
                        <div class="font:semibold font:16 lh:24px">Preview :</div>

                        <div class="h:fit flex r:6 shadow-sm">
                            <span class="inline-flex align-items:center rl:6 b:1|solid|gray-30/.5 br:0 bg:gray-5 px:12 fg:gray-60 font:12 lh:16px">width</span>
                            <input type="number" v-model="preview.width.current" :min="preview.width.min" :max="preview.width.max" class="block min-w:0 w:64 min-h:0 h:24 mx:0 py:0 px:8 r:0 rr:6 b:1|solid|gray-30/.5 font:12 lh:16px" />
                            <input type="range" v-model="preview.width.current" :min="preview.width.min" :max="preview.width.max" class="flex:1|1|0% appearance:none accent:#0050FF h:4 align-self:center" />
                        </div>

                        <div class="h:fit flex r:6 shadow-sm">
                            <span class="inline-flex align-items:center rl:6 b:1|solid|gray-30/.5 br:0 bg:gray-5 px:12 fg:gray-60 font:12 lh:16px">weight</span>
                            <input type="number" v-model="preview.weight.current" :min="preview.weight.min" :max="preview.weight.max" class="block min-w:0 w:64 min-h:0 h:24 mx:0 py:0 px:8 r:0 rr:6 b:1|solid|gray-30/.5 font:12 lh:16px" />
                            <input type="range" v-model="preview.weight.current" :min="preview.weight.min" :max="preview.weight.max" class="flex:1|1|0% appearance:none accent:#0050FF h:4 align-self:center" />
                        </div>

                        <div class="h:fit flex r:6 shadow-sm">
                            <span class="inline-flex align-items:center rl:6 b:1|solid|gray-30/.5 br:0 bg:gray-5 px:12 fg:gray-60 font:12 lh:16px">size</span>
                            <div class="h:fit flex align-items:stretch rel r:6 shadow-sm">
                                <input type="number" v-model="preview.fontSize" class="block min-w:0 w:64 min-h:0 h:24 mx:0 py:0 px:8 r:0 rr:6 b:1|solid|gray-30/.5 font:12 lh:16px" />
                                <div class="abs top:0 bottom:0 right:0 flex py:4 pr:6">
                                    <kbd class="inline-flex align-items:center r:4 b:1|solid|gray-20 px:4 fg:gray-60">px</kbd>
                                </div>
                            </div>
                            <input type="range" v-model="preview.fontSize" max="200" class="flex:1|1|0% appearance:none accent:#0050FF h:4 align-self:center" />
                        </div>
                    </div>
                </div>
            </div>
            <div id="postbox-container-2" class="postbox-container mt:12">
                <Transition name="css-preview">
                    <div v-if="cssPreviewStylesheet" class="mt:16">
                        <h3 class="mt:20">CSS Preview</h3>
                        <highlightjs language="css" :code="cssPreviewStylesheet" />
                    </div>
                </Transition>

                <div>
                    <table id="post-status-info" class="b:1|solid|#c3c4c7">
                        <tbody>
                            <tr>
                                <td class="pl:8">
                                    ID: <span :title="item.slug">{{ item.id }}</span>
                                </td>
                                <td class="autosave-info">
                                    Last edited on <span :title="new Date(item.updated_at * 1000)" class="text:underline decoration-dotted text-gray-700">{{ ago(new Date(item.updated_at * 1000)) }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, reactive, watch, computed, provide, onBeforeMount, onBeforeUnmount } from 'vue';
import { storeToRefs } from 'pinia';
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router';
import debounce from 'lodash-es/debounce';
import isEqual from 'lodash-es/isEqual';
import cloneDeep from 'lodash-es/cloneDeep';
import ago from 's-ago';
import { __ } from '@wordpress/i18n';
import { useApi } from '../../../library/api';
import { useNotifier } from '../../../library/notifier';
import { useBusy } from '../../../stores/busy';
import { useLocalFontStore } from '../../../stores/font/localFont';
import { useWordpressNotice } from '../../../stores/wordpressNotice';

import TheFontFace from '../../../components/fonts/local/TheFontFace.vue';
import TheBulkUpload from '../../../components/fonts/local/TheBulkUpload.vue';
import { Switch, SwitchGroup, SwitchLabel } from '@headlessui/vue';

const route = useRoute();
const router = useRouter();
const api = useApi();
const busy = useBusy();
const notifier = useNotifier();
const wordpressNotice = useWordpressNotice();
const store = useLocalFontStore();

const { fontFaces } = storeToRefs(store);

const title = ref('');
const family = ref('');
const display = ref('auto');
const selector = ref('');
const preload = ref(false);
const status = ref(true);

const item = ref({});

const isDeleting = ref(false);

watch(family, (newFamily, oldFamily) => {
    if (title.value === '' || title.value === oldFamily) {
        title.value = newFamily;
    }
});

const createNewFontFace = () => {
    store.add();
};

const preview = reactive({
    text: `The quick brown fox jumps over a lazy dog`,
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

function doDelete() {
    if (!confirm(__(`Are you sure you want to delete the font?`, 'yabe-webfont'))) {
        return;
    }

    isDeleting.value = true;
    busy.add('fonts.edit.custom:delete');

    api
        .request({
            // method: 'DELETE', // not working on IIS server without further configuration
            method: 'POST',
            url: `/fonts/delete/${item.value.id}`,
        })
        .then((response) => {
            router.push({ name: 'fonts.index' });
        })
        .catch(function (error) {
            notifier.alert(error.message);
        })
        .finally(() => {
            isDeleting.value = false;
            busy.remove('fonts.edit.custom:delete');
        });
}

async function fetchItem() {
    busy.add('fonts.edit.custom:fetch-item');
    return api
        .request({
            method: 'GET',
            url: `/fonts/detail/${route.params.id}`,
        })
        .then((response) => {
            return response.data;
        })
        .then(data => {
            family.value = data.family;
            title.value = data.title;
            display.value = data.metadata.display;
            selector.value = data.metadata.selector;
            preload.value = data.metadata.preload;
            status.value = data.status;
            fontFaces.value = cloneDeep(data.font_faces);

            item.value = data;
        })
        .finally(() => {
            busy.remove('fonts.edit.custom:fetch-item');
        });
};

function sendForm(e) {
    e.preventDefault();

    busy.add('fonts.edit.custom:send-form');

    let promise = api
        .request({
            // method: 'PUT', // not working on IIS server without further configuration
            method: 'POST',
            url: `/fonts/custom/update/${item.value.id}`,
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
            wordpressNotice.add({
                type: 'success',
                message: `<p>Font updated successfully.</p>`,
            });

            fetchItem();
        })
        .finally(() => {
            busy.remove('fonts.edit.custom:send-form');
        })

    notifier.async(
        promise,
        'Font updated successfully.',
        undefined,
        'Updating the font...'
    );
}

onBeforeMount(async () => {
    let promise = fetchItem();

    notifier.async(
        promise,
        'Font details loaded.',
        err => {
            notifier.alert(
                err.response && err.response.status === 404
                    ? 'Font not found.'
                    : 'Failed to load font details.'
            );
            router.go(-1);
        },
        'Fetching font details...'
    );

    fontPreviewStylesheetEl = document.querySelector('#yabe-webfont-preview');
    if (!fontPreviewStylesheetEl) {
        fontPreviewStylesheetEl = document.createElement('style');
        fontPreviewStylesheetEl.setAttribute('id', 'yabe-webfont-preview');
        document.head.appendChild(fontPreviewStylesheetEl);
    }
});

const isHaveUnsavedChanges = computed(() => {
    if (!item.value) {
        return false;
    }

    return (
        title.value !== item.value.title
        || family.value !== item.value.family
        || status.value !== item.value.status
        || display.value !== item.value.metadata.display
        || selector.value !== item.value.metadata.selector
        || preload.value !== item.value.metadata.preload
        || !isEqual(fontFaces.value, item.value.font_faces)
    );
});

const unsavedNoticeId = ref(null);

watch(isHaveUnsavedChanges, (newVal, oldVal) => {
    if (newVal && !unsavedNoticeId.value) {
        unsavedNoticeId.value = wordpressNotice.add({
            type: 'warning',
            message: `<p>You have unsaved changes.</p>`,
        });
    } else {
        wordpressNotice.remove(unsavedNoticeId.value);
        unsavedNoticeId.value = null;
    }
});

onBeforeRouteLeave((to, from, next) => {
    if (isHaveUnsavedChanges.value) {
        if (!confirm(__(`You have unsaved changes. Are you sure you want to leave?`, 'yabe-webfont'))) {
            return;
        }
    }

    next();
});

window.onbeforeunload = function (e) {
    if (isHaveUnsavedChanges.value) {
        return __(`You have unsaved changes. Are you sure you want to leave?`, 'yabe-webfont');
    }
};

onBeforeUnmount(() => {
    window.onbeforeunload = null;
    if (unsavedNoticeId.value) {
        wordpressNotice.remove(unsavedNoticeId.value);
    }
    if (fontPreviewStylesheetEl) {
        document.head.removeChild(fontPreviewStylesheetEl);
    }
});
</script>