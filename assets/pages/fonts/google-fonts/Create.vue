<template>
    <span class="mr:8 font:24 lh:32px">» {{ __('Import Google Fonts', 'yabe-webfont') }} </span>

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
                                <label class="font:14 lh:20px font:semibold">Font Family</label>
                                <TheSearchFamily :catalog="catalog" v-model="fontData"></TheSearchFamily>
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

                            <!-- TODO: 2nd row -->
                            <div class="grid-col-span:7 flex flex:col gap:6">
                                <span class="font:14 lh:20px font:semibold">Subsets</span>
                                <VueSelect multiple :options="fontData?.subsets.filter((item) => !subsets.includes(item))" v-model="subsets" :searchable="false" placeholder="Choose subsets">
                                    <template v-slot:no-options="{ search, searching }">
                                        <template v-if="!subsets.length">
                                            Please choose a font family first.
                                        </template>
                                    </template>
                                </VueSelect>
                            </div>
                            <div class="grid-col-span:3 flex flex:col gap:6">
                                <span class="font:14 lh:20px font:semibold">File Format</span>
                                <VueSelect multiple :options="variable ? ['woff2'] : ['woff2', 'woff', 'ttf']" v-model="format" :searchable="false" placeholder="Choose formats"></VueSelect>
                            </div>

                            <div class="grid-col-span:2 flex flex:col gap:6">
                                <span class="font:14 lh:20px font:semibold">Variable Fonts</span>
                                <div class="flex:1|1|0% flex align-items:center">
                                    <label for="variable" class="font:14 lh:20px">
                                        <input :disabled="!fontData?.isSupportVariable" type="checkbox" name="variable" id="variable" v-model="variable">
                                        Yes
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="flex align-items:center {ml:8}>*+* mt:32 mb:12">
                            <h3 class="flex:1|1|0%">Variants <input v-if="fontFaces?.length > 0" v-model="selectAll" class="" type="checkbox" title="(De)select All" /></h3>

                            <div class="flex align-items:center {ml:8}>*+* py:8 px:8 b:1|solid|gray-30/.5">

                                <div class="font:semibold font:16 lh:24px">Preview :</div>

                                <template v-if="variable">
                                    <div v-if="preview.width.current !== 0" class="h:fit flex r:6 shadow-sm">
                                        <span class="inline-flex align-items:center rl:6 br:0 b:1|solid|gray-30/.5 bg:gray-5 px:12 fg:gray-60 font:12 lh:16px">width</span>
                                        <input type="number" v-model="preview.width.current" :min="preview.width.min" :max="preview.width.max" class="block min-w:0 w:64 min-h:0 h:24 mx:0 py:0 px:8 r:0 rr:6 b:1|solid|gray-30/.5 font:12 lh:16px" />
                                        <input type="range" v-model="preview.width.current" :min="preview.width.min" :max="preview.width.max" class="w:64 appearance:none accent:#0050FF h:4 align-self:center" />
                                    </div>

                                    <div v-if="preview.weight.current !== 0" class="h:fit flex r:6 shadow-sm">
                                        <span class="inline-flex align-items:center rl:6 br:0 b:1|solid|gray-30/.5 bg:gray-5 px:12 fg:gray-60 font:12 lh:16px">weight</span>
                                        <input type="number" v-model="preview.weight.current" :min="preview.weight.min" :max="preview.weight.max" class="block min-w:0 w:64 min-h:0 h:24 mx:0 py:0 px:8 r:0 rr:6 b:1|solid|gray-30/.5 font:12 lh:16px" />
                                        <input type="range" v-model="preview.weight.current" :min="preview.weight.min" :max="preview.weight.max" class="w:64 appearance:none accent:#0050FF h:4 align-self:center" />
                                    </div>
                                </template>

                                <div class="h:fit flex r:6 shadow-sm">
                                    <span class="inline-flex align-items:center rl:6 br:0 b:1|solid|gray-30/.5 bg:gray-5 px:12 fg:gray-60 font:12 lh:16px">size</span>
                                    <div class="h:fit flex align-items:stretch rel r:6 shadow-sm">
                                        <input type="number" v-model="preview.fontSize" class="block min-w:0 w:64 min-h:0 h:24 mx:0 py:0 px:8 r:0 rr:6 b:1|solid|gray-30/.5 font:12 lh:16px" />
                                        <div class="abs top:0 bottom:0 right:0 flex py:4 pr:6">
                                            <kbd class="inline-flex align-items:center r:4 b:1|solid|gray-20 px:4 fg:gray-60">px</kbd>
                                        </div>
                                    </div>
                                    <input type="range" v-model="preview.fontSize" max="200" class="w:64 appearance:none accent:#0050FF h:4 align-self:center" />

                                </div>

                            </div>
                        </div>

                        <div class="font-files">
                            <div class="grid gap:16">
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
            <div id="postbox-container-2" class="postbox-container mt:12">
                <Transition name="css-preview">
                    <div v-if="cssPreviewStylesheet" class="mt:16">
                        <h3 class="mt:20">CSS Preview</h3>
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

            let files = [];

            subsets.value.forEach(subset => {
                let sfiles = fontFiles.value.filter(
                    f => f.weight == fontFace.weight
                        && f.style === fontFace.style
                        && format.value.includes(f.format)
                        && f.subsets.includes(subset)
                );

                // add a subset property to sfles,
                // so we can use it in the next step
                sfiles.forEach(f => {
                    f.subset = subset;
                });

                files.push(...sfiles);
            });

            // if subset contain number/digit, then add it
            let sfiles = fontFiles.value.filter(
                f => f.weight == fontFace.weight
                    && f.style === fontFace.style
                    && format.value.includes(f.format)
                    && f.subsets.some(s => s.match(/\d+/))
            );

            sfiles.forEach(f => {
                f.subset = f.subsets.join('');
            });

            files.push(...sfiles);

            let filteredFiles = [];

            // ensure that files array are unique by checking for all properties except the uid property
            files.forEach(file => {
                let isUnique = true;
                filteredFiles.forEach(f => {
                    if (f.weight === file.weight && f.style === file.style && isEqual(f.subsets, file.subsets) && f.format === file.format) {
                        isUnique = false;
                    }
                });

                if (isUnique) {
                    filteredFiles.push(file);
                }
            });

            filteredFiles.forEach(file => {
                if (fontFace.comment) {
                    css += `/* ${fontFace.comment} */\n`;
                }

                css += `/* ${file.subset} */\n`;

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

            let files = [];

            subsets.value.forEach(subset => {
                let sfiles = fontFiles.value.filter(
                    f => f.weight == fontFace.weight
                        && f.style === fontFace.style
                        && f.subsets.includes(subset)
                        && format.value.includes(f.format)
                );

                // add a subset property to sfles,
                // so we can use it in the next step
                sfiles.forEach(f => {
                    f.subset = subset;
                });

                files.push(...sfiles);
            });

            // if subset contain number/digit, then add it
            let sfiles = fontFiles.value.filter(
                f => f.weight == fontFace.weight
                    && f.style === fontFace.style
                    && f.subsets.some(s => s.match(/\d+/))
                    && format.value.includes(f.format)
            );

            sfiles.forEach(f => {
                f.subset = f.subsets.join('');
            });

            files.push(...sfiles);

            let filteredFiles = [];

            // ensure that files array are unique by checking for all properties except the uid property
            files.forEach(file => {
                let isUnique = true;
                filteredFiles.forEach(f => {
                    if (f.weight === file.weight && f.style === file.style && isEqual(f.subsets, file.subsets) && f.format === file.format) {
                        isUnique = false;
                    }
                });

                if (isUnique) {
                    filteredFiles.push(file);
                }
            });

            filteredFiles.forEach(file => {
                if (fontFace.comment) {
                    css += `/* ${fontFace.comment} */\n`;
                }

                css += `/* ${file.subset} */\n`;

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

    let filteredFontFiles = [];

    // ensure that files array are unique by checking for all properties except the uid property
    fontFiles.value.forEach(file => {
        let isUnique = true;

        filteredFontFiles.forEach(f => {
            if (f.weight === file.weight && f.style === file.style && isEqual(f.subsets, file.subsets) && f.format === file.format) {
                isUnique = false;
            }
        });

        if (isUnique) {
            filteredFontFiles.push(file);
        }
    });

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
                        // font_files: fontFiles.value,
                        font_files: filteredFontFiles,
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