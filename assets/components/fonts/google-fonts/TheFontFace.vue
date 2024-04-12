<template>
    <div :class="{ 'b:green-60': item.isEnabled }" class="font-item rel bg:white b:1|solid|gray-30/.5 {bt:1;bb:0}>*+* {bx:0}>*+* {b:solid}>*+* {b:gray-30/.5}>*+*">
        <!-- Header -->

        <div class="font-item__header flex align-items:stretch {bl:1;br:0}>*+* {by:0}>*+* {b:dashed}>*+* {b:gray-30/.5}>*+*">
            <div class="flex p:12 align-items:center">
                <Switch v-model="item.isEnabled" :class="[item.isEnabled ? 'bg:sky-60' : 'opacity:.5 bg:gray-20']" class="rel inline-flex p:0 h:24 w:44 flex-shrink:0 cursor:pointer rounded b:2 b:transparent transition-property:color,background-color,border-color,text-decoration-color,fill,stroke transition-duration:200 transition-timing-function:cubic-bezier(0.4,0,0.2,1) box-shadow:rgb(255,255,255)|0|0|0|2,rgb(14,165,233)|0|0|0|4,rgba(0,0,0,0)|0|0|0|0:focus outline:2|solid|transparent:focus">
                    <span :class="[item.isEnabled ? 'translateX(20)' : 'translateX(0)']" class="pointer-events:none rel inline-block font:12 h:20 w:20 rounded bg:white transition-property:color,background-color,border-color,text-decoration-color,fill,stroke,opacity,box-shadow,transform,filter,backdrop-filter transition-duration:200 transition-timing-function:cubic-bezier(0.4,0,0.2,1) box-shadow:rgb(255,255,255)|0|0|0|0,rgba(59,130,246,0.5)|0|0|0|0,rgba(0,0,0,0.1)|0|1|3|0,rgba(0,0,0,0.1)|0|1|2|-1">
                        <span aria-hidden="true" :class="[item.isEnabled ? 'opacity:0 transition-timing-function:ease-out transition-duration:100' : 'opacity:1 transition-timing-function:ease-in transition-duration:200']" class="abs inset:0 flex h:full w:full align-items:center justify-content:center tw-transition-opacity">
                            <font-awesome-icon :icon="['fas', 'xmark']" class="fg:gray-60" />
                        </span>
                        <span aria-hidden="true" :class="[item.isEnabled ? 'opacity:1 transition-timing-function:ease-in transition-duration:200' : 'opacity:0 transition-timing-function:ease-out transition-duration:100']" class="abs inset:0 flex h:full w:full align-items:center justify-content:center tw-transition-opacity">
                            <font-awesome-icon :icon="['fas', 'check']" class="fg:sky-60" />
                        </span>
                    </span>
                </Switch>
            </div>
            <div ref="weightStyleTooltip" class="font-item__weight flex-grow:0 flex-shrink:0 flex p:12 align-items:center {ml:8}>*+*">
                <span v-if="item.weight === 0" :title="weightName" class="opacity:.7 font:14 lh:20px fg:#a7aaad px:20 py:4 r:2 b:1|solid|#dcdcde bg:#f6f7f7">
                    {{ preview.weight.current }}
                </span>
                <span v-else :title="weightName" class="opacity:.7 font:14 lh:20px fg:#a7aaad px:20 py:4 r:2 b:1|solid|#dcdcde bg:#f6f7f7">
                    {{ item.weight }}
                </span>
                <span :class="{ 'italic': styleName === 'italic' }" :title="styleName" class="opacity:.7 font:14 lh:20px capitalize text:left fg:#a7aaad white-space:nowrap text:clip overflow:hidden px:8 py:4 w-12 r:2 b:1|solid|#dcdcde bg:#f6f7f7">
                    {{ styleName }}
                </span>
                <TheTooltip :target-ref="weightStyleTooltip" content="Font weight & style" />
            </div>
            <div ref="previewTextTooltip" class="flex:1|1|0% flex p:12 align-items:center">
                <ContentEditable tag="div" v-model="preview.text" :style="previewInlineStyle()" class="preview-text lh:1.25" />
                <TheTooltip :target-ref="previewTextTooltip" :content="__('Editable preview text', 'yabe-webfont')" />
            </div>
            <button type="button" ref="editTooltip" @click="isShowBody = !isShowBody" v-ripple class="button flex align-items:center px:16 py:0.5 focus:shadow-none cursor:pointer bg:inherit b:none">
                <font-awesome-icon :icon="['fas', 'pen-to-square']" class="h:20 w:20 fg:blue-80 fg:blue-60:hover fill:current" />
                <TheTooltip :target-ref="editTooltip" :content="__('Edit', 'yabe-webfont')" />
            </button>
        </div>

        <!-- Body -->
        <div v-if="isShowBody" class="font-item__body {bx:0}>*+* {bt:1;bb:0}>*+* {b:solid}>*+* {b:gray-30/.5}>*+*">
            <div class="font-item__body-meta grid grid-cols:12 align-items:stretch {bl:1;br:0}>*+* {by:0}>*+* {b:dashed}>*+* {b:gray-30/.5}>*+*">
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
                <div class="flex grid-col-span:4 flex:col gap:6 p:12">
                    <label :for="`comment-${item.id}`" class="font:14 lh:20px font:semibold">Comment</label>
                    <input type="text" name="comment" :id="`comment-${item.id}`" v-model="item.comment" placeholder="">
                </div>
                <div class="flex grid-col-span:4 flex:col gap:6 p:12">
                    <label :for="`selector-${item.id}`" class="font:14 lh:20px font:semibold">CSS Selector</label>
                    <input type="text" name="selector" :id="`selector-${item.id}`" v-model="item.selector" placeholder="h1, h2, .poetry, .haiku, p, span, #lyric, #description" autocomplete="off">
                </div>
                <div class="flex grid-col-span:2 flex:col gap:6 p:12">
                    <label :for="`preload-${item.id}`" class="font:14 lh:20px font:semibold">Preload</label>
                    <label :for="`preload-${item.id}`" class="font:14 lh:20px">
                        <input type="checkbox" name="preload" :id="`preload-${item.id}`" v-model="item.preload">
                        Preload files
                    </label>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, ref } from 'vue';

import { Switch } from '@headlessui/vue';
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
    isVariable: {
        type: Boolean,
    },
    fontData: {
        type: Object,
    },
});

const isShowBody = ref(false);

const weightStyleTooltip = ref(null);
const previewTextTooltip = ref(null);

const editTooltip = ref(null);

function previewInlineStyle() {
    return {
        fontFamily: `'${props.preview.fontFamily}'`,
        fontSize: `${props.preview.fontSize}px`,
        fontWeight: props.item.weight !== 0 ? props.item.weight : props.preview.weight.current,
        fontStyle: props.item.style,
        fontStretch: props.preview.width.current !== 0 ? `${props.preview.width.current}%` : '100%',
    };
}

const weightName = computed(() => {
    let w = props.item.weight !== 0 ? props.item.weight : Math.round(props.preview.weight.current / 100) * 100;
    switch (w) {
        case 100: return 'Thin';
        case 200: return 'Extra Light';
        case 300: return 'Light';
        case 500: return 'Medium';
        case 600: return 'Semi Bold';
        case 700: return 'Bold';
        case 800: return 'Extra Bold';
        case 900: return 'Black';
        case 400:
        default: return 'Regular';
    }
});

const styleName = computed(() => {
    if (props.isVariable) {
        let slnt = props.fontData?.axes.find(axis => axis.tag === 'slnt');
        if (slnt !== undefined) {
            return `oblique ${slnt.max*-1}deg ${slnt.min*-1}deg`;
        }
    }

    return props.item.style;
});
</script>
