<template>
    <div v-if="!yabeWebfont.lite_edition" class="mb:24">
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row"><label>License Key</label></th>
                    <td>
                        <input name="license_key" type="password" v-model.lazy="license.key" class="min-w:25rem">
                        <div v-if="license.is_activated" class="flex my:10 align-items:center font:medium">
                            Status:
                            <span class="bg:green-80 fg:white font:regular ml:10 px:6 py:4 r:4 user-select:none">
                                active
                            </span>
                        </div>
                        <p v-else class="description">Enter your <a href="https://webfont.yabe.land/#pricing-plans" target="_blank">license key</a> receive the update of the latest version.</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="mb:24">
        <h2 class="title">Performance</h2>

        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row"> Cached CSS </th>
                    <td>
                        <p class="flex gap-x:4">
                            <span class="font:medium"> Last generated: </span>
                            <template v-if="css_cache.last_generated">
                                {{ new dayjs(css_cache.last_generated * 1000).format('YYYY-MM-DD HH:mm:ss') }}
                                <a :href="`${css_cache.file_url}?ver=${css_cache.last_generated}`" target="_blank">
                                    <font-awesome-icon :icon="['far', 'arrow-up-right-from-square']" class="font:13 translateY(-6)" />
                                </a>
                            </template>
                            <span :class="busy.isBusy && (busy.hasTask('settings:generate-cache') || busy.hasTask('settings:fetch-cache')) ? 'visible' : 'hidden'" class="spinner"></span>
                        </p>
                        <p class="description">
                            Serve the CSS file from the cache instead of generating it on the fly.
                        </p>
                        <div class="flex align-items:center my:8">
                            <button type="button" @click="doGenerateCache" class="button button-secondary"> Re-generate cache </button>
                            <template v-if="css_cache.pending_task">
                                <font-awesome-icon :icon="['fas', 'hourglass-clock']" class="ml:12 fg:gray-50"/>
                                <span class="fg:gray-50 pl:4">
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

    <div class="mb:24">
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

                <tr>
                    <th scope="row"><label>Disable user's Google Fonts</label></th>
                    <td>
                        <input id="disable-user-google-fonts" name="disable_user_google_fonts" type="checkbox" :checked="get(options, 'misc.disable_user_google_fonts', false)" :value="get(options, 'misc.disable_user_google_fonts', false)" @input="set(options, 'misc.disable_user_google_fonts', !options?.misc?.disable_user_google_fonts)">
                        <label for="disable-user-google-fonts"> Disable Google Fonts API that loaded manually </label>
                        <p class="description">
                            Scan and disable Google Fonts API that loaded manually by the theme or plugin through <code>wp_enqueue_style</code> function.
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="mb:24">
        <h2 class="title">Adobe Fonts</h2>

        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row"><label>Project ID</label></th>
                    <td>
                        <div class="flex align-items:center">
                            <input type="text" id="adobe-font-project-id" name="adobe_font_project_id" :value="get(options, 'adobe_fonts.project_id', null)" @change="$event => set(options, 'adobe_fonts.project_id', $event.target.value === '' ? null : $event.target.value)" class="">
                            <button v-if="adobe_font_kit" type="button" @click="doSyncAdobeFonts" class="button button-secondary"> Sync fonts </button>
                            <span :class="busy.isBusy && (busy.hasTask('settings:fetch-adobe-fonts-kits') || busy.hasTask('settings:sync-adobe-fonts-kits')) ? 'visible' : 'hidden'" class="spinner"></span>
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