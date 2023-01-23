<template>
    <div class="tw-mb-6">
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row"><label>License Key</label></th>
                    <td>
                        <input name="license_key" type="text" v-model="license.key" class="tw-min-w-[25rem]">
                        <div v-if="license.is_activated" class="tw-flex tw-my-2.5 tw-items-center tw-font-medium">
                            Status: <span class="tw-font-normal tw-text-white tw-bg-green-700 tw-px-1.5 tw-py-1 tw-rounded tw-ml-2.5">active</span>
                        </div>
                        <p class="description">Enter your <a href="https://rosua.org/downloads/yabe-webfont/" target="_blank">license key</a> receive the update of the latest version.</p>

                    </td>
                </tr>
                <tr>
                    <th scope="row"><label>Pre-release version</label></th>
                    <td>
                        <input id="pre-release" name="opt_in_pre_release" type="checkbox" v-model="license.opt_in_pre_release">
                        <label for="pre-release"> Enable</label>
                        <p class="description">Opt-in to get the pre-release version update. <span class="tw-text-red-700">Pre-release version may unstable.</span></p>
                    </td>
                </tr>
            </tbody>
        </table>

        <button type="button" @click="doStore" v-ripple class="button button-primary">Save Changes</button>
    </div>

    <h2 class="title">Performance</h2>

    <table class="form-table" role="presentation">
        <tbody>
            <tr>
                <th scope="row"> Cached CSS </th>
                <td>
                    <p>
                        <span class="tw-font-medium"> Last generated: </span>
                        <template v-if="css_cache.last_generated">
                            {{ new dayjs(css_cache.last_generated * 1000).format('YYYY-MM-DD HH:mm:ss')  }}
                            <a :href="`${css_cache.file_url}?ver=${css_cache.last_generated}`" target="_blank">
                                <svg class="tw-w-[15px] tw-h-[15px]" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" viewBox="0 0 100 100">
                                    <path fill="currentColor" d="M18.8,85.1h56l0,0c2.2,0,4-1.8,4-4v-32h-8v28h-48v-48h28v-8h-32l0,0c-2.2,0-4,1.8-4,4v56C14.8,83.3,16.6,85.1,18.8,85.1z"></path>
                                    <polygon fill="currentColor" points="45.7,48.7 51.3,54.3 77.2,28.5 77.2,37.2 85.2,37.2 85.2,14.9 62.8,14.9 62.8,22.9 71.5,22.9"></polygon>
                                </svg>
                            </a>
                        </template>
                    </p>
                    <p class="description">
                        Cached CSS file for the active font files. 
                    </p>
                    <p class="description">
                        <button type="button" @click="doGenerateCache" class="button button-secondary"> Re-generate cache </button>
                    </p>

                </td>
            </tr>
        </tbody>
    </table>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import dayjs from 'dayjs';

import { useApi } from '../../library/api';
import { useNotifier } from '../../library/notifier';
import { useBusy } from '../../stores/busy';


const api = useApi();
const busy = useBusy();
const notifier = useNotifier();

const license = ref({
    key: '',
    is_activated: false,
    opt_in_pre_release: false,
});

const css_cache = ref({
    last_generated: '',
    pending_task: false,
    file_url: '',
});

onMounted(() => {
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
});

function doStore() {
    busy.add('settings:store-license');
    api
        .request({
            method: 'POST',
            url: '/setting/license/store',
            data: {
                license: license.value,
            },
        })
        .then(response => response.data)
        .then(data => {
            license.value = data.license;
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
</script>