<template>
    <h1 class="wp-heading-inline">{{ __('Import Google Fonts', 'yabe-webfont') }}</h1>

    <div class="tw-inline-flex tw-items-center tw-float-right">
        <div class="tw-mr-2 tw-font-medium tw-text-sm">Yabe Webfont <span class="tw-text-xs tw-text-gray-500 tw-font-normal">v{{ yabeWebfontImport._version }}</span></div>
        <a href="https://l.suabahasa.dev/s7XEu" target="_blank" class="tw-group page-title-action tw-flex tw-space-x-1 !tw-top-auto !tw-p-1 !tw-px-3 tw-items-center !tw-rounded-md tw-transition-[color,background-color,box-shadow,border-color] tw-ease-[cubic-bezier(0.33,1,0.68,1)] tw-duration-75">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="group-hover:tw-animate-none tw-w-4 tw-h-4 group-hover:tw-scale-[1.15] group-hover:group-hover:tw-ease-[cubic-bezier(0.2,0,0.13,2)] tw-text-pink-700 tw-fill-current">
                <!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                <path d="M244 84L255.1 96L267.1 84.02C300.6 51.37 347 36.51 392.6 44.1C461.5 55.58 512 115.2 512 185.1V190.9C512 232.4 494.8 272.1 464.4 300.4L283.7 469.1C276.2 476.1 266.3 480 256 480C245.7 480 235.8 476.1 228.3 469.1L47.59 300.4C17.23 272.1 0 232.4 0 190.9V185.1C0 115.2 50.52 55.58 119.4 44.1C164.1 36.51 211.4 51.37 244 84C243.1 84 244 84.01 244 84L244 84zM255.1 163.9L210.1 117.1C188.4 96.28 157.6 86.4 127.3 91.44C81.55 99.07 48 138.7 48 185.1V190.9C48 219.1 59.71 246.1 80.34 265.3L256 429.3L431.7 265.3C452.3 246.1 464 219.1 464 190.9V185.1C464 138.7 430.4 99.07 384.7 91.44C354.4 86.4 323.6 96.28 301.9 117.1L255.1 163.9z" />
            </svg>
            <span>Sponsor</span>
        </a>
    </div>

    <hr class="wp-header-end">

    <div v-for="(notice, index) in wpNoticeState" :key="index" :class="`notice notice-${notice.type} is-dismissible`">
        <p v-html="notice.message"></p>
        <button type="button" @click="wpNotice.remove(index)" class="notice-dismiss">
            <span class="screen-reader-text">Dismiss this notice.</span>
        </button>
    </div>

    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-2">
            <div id="post-body-content">
                <div id="titlediv">
                    <div id="titlewrap">
                        <SearchComponenet :fontsIndex="fontsIndex" v-model="selectedFont"></SearchComponenet>
                    </div>
                    <div class="inside">
                    </div>
                </div>
            </div>

            <div id="postbox-container-1" class="postbox-container">
                <FontInfoPanel :font="selectedFont" @import-font="importSelectedFont" />
            </div>

            <div id="postbox-container-2" class="postbox-container">
                <div v-if="selectedFont" class="meta-box-sortables ui-sortable">
                    <div class="ywf__font-metabox postbox tw-shadow-none">
                        <div class="inside">
                            <h2 class="title">Subsets</h2>
                            <div class="tw-flex">
                                <div class="tw-flex tw-w-full tw-space-x-6">
                                    <div v-for="subset in subsets" :key="subset.key" class="tw-flex tw-items-center">
                                        <input type="checkbox" :id="subset.key" v-model="subset.isEnabled" :disabled="subset.isEnabled && subsets.reduce((acc, curr) => curr.isEnabled ? ++acc : acc, 0) === 1" :class="[{ '!tw-border-blue-600': subset.isEnabled }, '!tw-m-0']" />
                                        <label :for="subset.key" :class="[subset.isEnabled ? 'tw-text-black tw-font-medium' : 'tw-text-gray-500', 'tw-ml-2 tw-text-sm']">{{ subset.key }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ywf__font-metabox postbox tw-shadow-none">
                        <div class="inside">
                            <h2 class="title">
                                Font Format
                                <a href="https://academy.bricksbuilder.io/article/custom-fonts/#:~:text=the%20following%20font%20formats%20are%20enabled%20by%20default" target="_blank"><i class="dashicons dashicons-editor-help"></i></a>
                            </h2>
                            <div class="tw-flex">
                                <div class="tw-flex tw-w-full tw-space-x-6">
                                    <div v-for="mime in mimes" :key="mime.key" class="tw-flex tw-items-center">
                                        <input type="checkbox" :id="mime.key" v-model="mime.isEnabled" :disabled="mime.isEnabled && mimes.reduce((acc, curr) => curr.isEnabled ? ++acc : acc, 0) === 1" :class="[{ '!tw-border-blue-600': mime.isEnabled }, '!tw-m-0']" />
                                        <label :for="mime.key" :class="[mime.isEnabled ? 'tw-text-black tw-font-medium' : 'tw-text-gray-500', 'tw-ml-2 tw-text-sm']">{{ mime.key }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <FontVariantPreview />
                </div>
            </div>
        </div>
        <br class="clear">
    </div>
</template>

<script setup>
import { reactive, ref, watch, provide } from 'vue';
import { __ } from '@wordpress/i18n';

import AWN from 'awesome-notifications';
import 'awesome-notifications/dist/style.css';

import SearchComponenet from './components/SearchComponent.vue';
import FontInfoPanel from './components/FontInfoPanel.vue';
import FontVariantPreview from './components/FontVariantPreview.vue';

const yabeWebfontImport = window.yabeWebfontImport;

let notifier = new AWN();
provide('notifier', notifier);

let wpNoticeState = ref([]);
let wpNotice = {
    add: (message, type = 'success') => {
        wpNoticeState.value.push({ message: message, type: type });
    },
    remove: (_index) => {
        wpNoticeState.value = wpNoticeState.value.filter((_, index) => index !== _index);
    }
};

// Google Fonts index
const fontsIndex = reactive({
    fonts: [],
});

const fontsIndexFormData = new FormData();
fontsIndexFormData.append('action', 'yabe_webfont_fonts_index');
fontsIndexFormData.append('_wpnonce', yabeWebfontImport._wpnonce);

fetch(`${yabeWebfontImport.hostedWakufont}/api/fonts`, {
    method: 'GET',
    headers: {
        Accept: 'application/json',
    },
})
    .then(response => response.json())
    .then(data => {
        fontsIndex.fonts = data.fonts;
    })
    .catch(error => {
        notifier.alert('Failed to load Google Fonts index');
    });


// Selected font
const selectedFont = ref(null);
provide('font', selectedFont);

const variants = ref([]);
provide('variants', variants);

const subsets = ref([]);
provide('subsets', subsets);

const mimes = ref([]);
provide('mimes', mimes);

const variableEnabled = ref(false);
provide('variableEnabled', variableEnabled);

mimes.value = yabeWebfontImport.mimeTypes.map(mime => {
    return {
        key: mime,
        isEnabled: mime === 'woff2',
    }
});

watch(selectedFont, (newValue, oldValue) => {
    if (null === newValue || newValue === oldValue) {
        return;
    }

    variants.value = newValue.variants.map(variant => {
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

    subsets.value = newValue.subsets.map(subset => {
        return {
            key: subset,
            isEnabled: subset === 'latin',
        };
    });
});

// Import font
async function importSelectedFont() {
    const formData = new FormData();
    formData.append('action', 'yabe_webfont_import_selected_font');
    formData.append('font_slug', selectedFont.value.slug);
    formData.append('subsets', subsets.value.filter(subset => subset.isEnabled).map(subset => subset.key));
    formData.append('variants', variants.value.filter(variant => variant.isEnabled).map(variant => variant.key));
    formData.append('mimes', mimes.value.filter(mime => mime.isEnabled).map(mime => mime.key));
    formData.append('_wpnonce', yabeWebfontImport._wpnonce);

    let importPromise = fetch(ajaxurl, {
        method: 'POST',
        headers: {
            Accept: 'application/json',
        },
        body: formData,
    })
        .then(response => response.json())
        .then(data => {
            let postIds = data.data;
            const customFontPosts =  postIds.map(postId => {
                let postUrl = new URL(yabeWebfontImport.postUrl);
                postUrl.searchParams.append('action', 'edit');
                postUrl.searchParams.append('post', postId);

                return `<a href="${postUrl.href}" target="_blank">#${postId}</a>`;
            });

            wpNotice.add(`Font imported successfully: ${customFontPosts.join(', ')}.`, 'success');
        })
        .catch(error => {
            reject('Error importing font');
            return;
        });

    notifier.asyncBlock(
        importPromise,
        __('Font imported', 'yabe-webfont'),
        __('Font failed to imported', 'yabe-webfont'),
        __('Importing font', 'yabe-webfont'),
        {
            labels: {
                success: '',
            }
        }
    );
}
</script>
