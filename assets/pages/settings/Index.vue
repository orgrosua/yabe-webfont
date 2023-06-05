<template>
    <div v-if="!yabeWebfont.lite_edition" class="tw-mb-6">
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row"><label>License Key</label></th>
                    <td>
                        <input name="license_key" type="password" v-model.lazy="license.key" class="tw-min-w-[25rem]">
                        <div v-if="license.is_activated" class="tw-flex tw-my-2.5 tw-items-center tw-font-medium">
                            Status: <span class="tw-font-normal tw-text-white tw-bg-green-700 tw-px-1.5 tw-py-1 tw-rounded tw-ml-2.5">active</span>
                        </div>
                        <p v-else class="description">Enter your <a href="https://webfont.yabe.land/#pricing-plans" target="_blank">license key</a> receive the update of the latest version.</p>
                    </td>
                </tr>
                <!-- <tr>
                    <th scope="row"><label>Pre-release version</label></th>
                    <td>
                        <input id="pre-release" name="opt_in_pre_release" type="checkbox" v-model="license.opt_in_pre_release">
                        <label for="pre-release"> Enable</label>
                        <p class="description">Opt-in to get the pre-release version update. <span class="tw-text-red-700">Pre-release version may unstable.</span></p>
                    </td>
                </tr> -->
            </tbody>
        </table>
    </div>

    <div class="tw-mb-6">
        <h2 class="title">Performance</h2>

        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row"> Cached CSS </th>
                    <td>
                        <p class="tw-flex tw-gap-x-1">
                            <span class="tw-font-medium"> Last generated: </span>
                            <template v-if="css_cache.last_generated">
                                {{ new dayjs(css_cache.last_generated * 1000).format('YYYY-MM-DD HH:mm:ss') }}
                                <a :href="`${css_cache.file_url}?ver=${css_cache.last_generated}`" target="_blank">
                                    <svg class="tw-w-[15px] tw-h-[15px]" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" viewBox="0 0 100 100">
                                        <path fill="currentColor" d="M18.8,85.1h56l0,0c2.2,0,4-1.8,4-4v-32h-8v28h-48v-48h28v-8h-32l0,0c-2.2,0-4,1.8-4,4v56C14.8,83.3,16.6,85.1,18.8,85.1z"></path>
                                        <polygon fill="currentColor" points="45.7,48.7 51.3,54.3 77.2,28.5 77.2,37.2 85.2,37.2 85.2,14.9 62.8,14.9 62.8,22.9 71.5,22.9"></polygon>
                                    </svg>
                                </a>
                            </template>
                            <span :class="busy.isBusy && (busy.hasTask('settings:generate-cache') || busy.hasTask('settings:fetch-cache')) ? 'tw-visible' : 'tw-hidden'" class="spinner"></span>
                        </p>
                        <p class="description">
                            Serve the CSS file from the cache instead of generating it on the fly.
                        </p>
                        <div class="tw-flex tw-items-center tw-my-2">
                            <button type="button" @click="doGenerateCache" class="button button-secondary"> Re-generate cache </button>

                            <template v-if="css_cache.pending_task">
                                <svg class="tw-ml-3 tw-h-5 tw-w-5 tw-text-gray-400 tw-fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                    <path d="M32 0C14.3 0 0 14.3 0 32S14.3 64 32 64V75c0 42.4 16.9 83.1 46.9 113.1L146.7 256 78.9 323.9C48.9 353.9 32 394.6 32 437v11c-17.7 0-32 14.3-32 32s14.3 32 32 32H64 320h10.8C285.6 480.1 256 427.5 256 368c0-27.2 6.2-53 17.2-76l-36-36 67.9-67.9c30-30 46.9-70.7 46.9-113.1V64c17.7 0 32-14.3 32-32s-14.3-32-32-32H320 64 32zM96 75V64H288V75c0 19-5.6 37.4-16 53H112c-10.3-15.6-16-34-16-53zM576 368c0-79.5-64.5-144-144-144s-144 64.5-144 144s64.5 144 144 144s144-64.5 144-144zM432 288c8.8 0 16 7.2 16 16v48h32c8.8 0 16 7.2 16 16s-7.2 16-16 16H432c-8.8 0-16-7.2-16-16V304c0-8.8 7.2-16 16-16z" />
                                </svg>
                                <span class="tw-text-gray-400 tw-pl-1">
                                    There is a scheduled task to generate the cache.
                                </span>
                            </template>

                        </div>

                    </td>
                </tr>
                <tr>
                    <th scope="row"><label>Cache loading method</label></th>
                    <td>
                        <input id="inline-print" name="inline_print" type="checkbox" :checked="get(options, 'cache.inline_print', false)" :value="get(options, 'cache.inline_print', false)" @input="set(options, 'cache.inline_print', !options?.cache?.inline_print)">
                        <label for="inline-print"> Inline style</label>
                        <p class="description">Load font style via inline styles instead of external file.</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="tw-mb-6">
        <h2 class="title">Misc.</h2>

        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row"><label>Media Library</label></th>
                    <td>
                        <input id="media-library" name="hide_media_library" type="checkbox" :checked="get(options, 'misc.hide_media_library', false)" :value="get(options, 'misc.hide_media_library', false)" @input="set(options, 'misc.hide_media_library', !options?.misc?.hide_media_library)">
                        <label for="media-library"> Hide font files from Media Library </label>
                        <p class="description">
                            Only show items in the Media Library that satisfy the following conditions: original allowed mime types.
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label>Bundled export</label></th>
                    <td>
                        <input id="export-bundle-binary" name="export_bundle_binary" type="checkbox" :checked="get(options, 'misc.export_bundle_binary', false)" :value="get(options, 'misc.export_bundle_binary', false)" @input="set(options, 'misc.export_bundle_binary', !options?.misc?.export_bundle_binary)">
                        <label for="export-bundle-binary"> Bundle file on the exported file </label>
                        <p class="description">
                            Include font binary file on the exported file. This will increase the file size.
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="tw-mb-6">
        <h2 class="title">Adobe Fonts</h2>

        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row"><label>Project ID</label></th>
                    <td>
                        <div class="tw-flex tw-items-center">
                            <input type="text" id="adobe-font-project-id" name="adobe_font_project_id" :value="get(options, 'adobe_fonts.project_id', null)" @change="$event => set(options, 'adobe_fonts.project_id', $event.target.value === '' ? null : $event.target.value)" class="">
                            <button v-if="adobe_font_kit" type="button" @click="doSyncAdobeFonts" class="button button-secondary"> Sync fonts </button>
                            <span :class="busy.isBusy && (busy.hasTask('settings:fetch-adobe-fonts-kits') || busy.hasTask('settings:sync-adobe-fonts-kits')) ? 'tw-visible' : 'tw-hidden'" class="spinner"></span>
                        </div>
                        <p class="description">
                            Get your Project ID from <a href="https://fonts.adobe.com/my_fonts#web_projects-section" target="_blank">https://fonts.adobe.com/my_fonts#web_projects-section</a>
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <button type="button" @click="doStore" v-ripple class="button button-primary">Save Changes</button>
</template>

<script setup>
import { ref, onMounted, watch, watchEffect, onBeforeMount, reactive } from 'vue';
import dayjs from 'dayjs';
import cloneDeep from 'lodash-es/cloneDeep';
import set from 'lodash-es/set';
import get from 'lodash-es/get';

import { useApi } from '../../library/api';
import { useNotifier } from '../../library/notifier';
import { useBusy } from '../../stores/busy';

const api = useApi();
const busy = useBusy();
const notifier = useNotifier();

const license = ref({
    key: null,
    is_activated: false,
    opt_in_pre_release: false,
});

const css_cache = ref({
    last_generated: '',
    pending_task: false,
    file_url: '',
});

const options = ref({});

const adobe_font_kit = ref(null);

onBeforeMount(() => {
    busy.add('settings:fetch-license');
    api
        .request({
            method: 'GET',
            url: '/setting/license/index'
        })
        .then(response => response.data)
        .then(data => {
            license.value = data.license;
        })
        .catch(function (error) {
            notifier.alert(error.message);
        })
        .finally(() => {
            busy.remove('settings:fetch-license');
        });

    busy.add('settings:fetch-cache');
    api
        .request({
            method: 'GET',
            url: '/setting/cache/index'
        })
        .then(response => response.data)
        .then(data => {
            css_cache.value = data.cache;
        })
        .catch(function (error) {
            notifier.alert(error.message);
        })
        .finally(() => {
            busy.remove('settings:fetch-cache');
        });

    busy.add('settings:fetch-options');
    api
        .request({
            method: 'GET',
            url: '/setting/option/index'
        })
        .then(response => response.data)
        .then(data => {
            options.value = cloneDeep(data.options);
        })
        .then(() => {
            adobe_font_kit.value = options.value.adobe_fonts?.kit;
        })
        .catch(function (error) {
            notifier.alert(error.message);
        })
        .finally(() => {
            busy.remove('settings:fetch-options');
        });
});

watch(
    () => license.value.key,
    async (newKey, oldKey) => {
        if (busy.isBusy) {
            return;
        }

        if (newKey === oldKey) {
            return;
        }

        busy.add('settings:store-license');
        api
            .request({
                method: 'POST',
                url: '/setting/license/store',
                data: {
                    license: newKey
                },
            })
            .then(response => response.data)
            .then(data => {
                license.value = cloneDeep(data.license);
                if (Object.keys(data.notice).length > 0) {
                    if (data.notice?.success) {
                        notifier.success(data.notice?.success);
                    } else if (data.notice?.error) {
                        notifier.alert(data.notice?.error);
                    }
                } else {
                    notifier.success('License setting saved.');
                }
            })
            .catch(function (error) {
                notifier.alert(error.message);
            })
            .finally(() => {
                busy.remove('settings:store-license');
            });
    }
);

watch(
    () => options.value.adobe_fonts?.project_id,
    async (newProjectId, oldProjectId) => {
        if (busy.isBusy) {
            return;
        }

        if (newProjectId === oldProjectId) {
            return;
        }

        adobe_font_kit.value = null;
 
        if (newProjectId === null) {
            doDestroyAdobeFonts();
            return;
        }
        
        busy.add('settings:fetch-adobe-fonts-kits');
        api
            .request({
                method: 'POST',
                url: '/setting/adobe-fonts/get-kits',
                data: {
                    project_id: newProjectId
                },
            })
            .then(response => response.data)
            .then(data => {
                adobe_font_kit.value = data.data.kit;
                doStore();
                doSyncAdobeFonts();
            })
            .catch(function (error) {
                notifier.alert(error.response.data.message);
            })
            .finally(() => {
                busy.remove('settings:fetch-adobe-fonts-kits');
            });
    }
);

function doStore() {
    busy.add('settings:store-options');
    api
        .request({
            method: 'POST',
            url: '/setting/option/store',
            data: {
                options: options.value,
            },
        })
        .then(response => response.data)
        .then(data => {
            options.value = cloneDeep(data.options);
            notifier.success('Settings saved.');
        })
        .catch(function (error) {
            notifier.alert(error.message);
        })
        .finally(() => {
            busy.remove('settings:store-options');
        });
}

function doGenerateCache() {
    busy.add('settings:generate-cache');
    api
        .request({
            method: 'POST',
            url: '/setting/cache/generate',
        })
        .then(response => response.data)
        .then(data => {
            css_cache.value = data.cache;
        })
        .catch(function (error) {
            notifier.alert(error.message);
        })
        .finally(() => {
            busy.remove('settings:generate-cache');
        });
}

function doSyncAdobeFonts() {
    busy.add('settings:sync-adobe-fonts');
    api
        .request({
            method: 'POST',
            url: '/setting/adobe-fonts/sync',
            data: {
                project_id: options.value.adobe_fonts.project_id,
                kit: adobe_font_kit.value
            },
        })
        .then(response => response.data)
        .then(data => {
            notifier.success('Adobe Fonts synced.');
        })
        .catch(function (error) {
            notifier.alert(error.message);
        })
        .finally(() => {
            busy.remove('settings:sync-adobe-fonts');
        });
}

function doDestroyAdobeFonts() {
    busy.add('settings:destroy-adobe-fonts');
    api
        .request({
            method: 'POST',
            url: '/setting/adobe-fonts/destroy',
        })
        .then(response => response.data)
        .then(data => {
            notifier.success('Adobe Fonts cleared.');
        })
        .catch(function (error) {
            notifier.alert(error.message);
        })
        .finally(() => {
            busy.remove('settings:destroy-adobe-fonts');
        });
}
</script>