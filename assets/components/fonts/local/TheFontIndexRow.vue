<template>
    <transition mode="out-in">
        <tr v-if="item.isDeleted" class="plugin-deleted-tr inactive deleted">
            <td colspan="5" class="plugin-update colspanchange">
                <strong>{{ item.title }}</strong> was successfully deleted.
            </td>
        </tr>
        <tr v-else-if="item.isRestored" class="plugin-deleted-tr inactive deleted">
            <td colspan="5" class="plugin-update colspanchange">
                <strong>{{ item.title }}</strong> was successfully restored.
            </td>
        </tr>
        <tr v-else :class="{ 'active': item.status && item.deleted_at == null, 'inactive': !item.status }">
            <th scope="row" :class="{ 'tw-pl-1.5': !item.status }" class="align-middle tw-py-2 check-column">
                <input v-model="selectedItems" type="checkbox" :value="item.id" :disabled="busy.isBusy" />
            </th>
            <td width="25%" class="align-middle">
                <strong>
                    {{ item.title }}
                </strong>
                <div class="row-actions visible">
                    <span class="tw-text-gray-400">ID: {{ item.id }}</span>
                    |
                    <template v-if="item.deleted_at == null">
                        <a :class="{ 'tw-cursor-wait': busy.isBusy }" class="tw-text-red-700 tw-cursor-pointer hover:tw-text-red-800" @click="doUpdateStatus">
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
                    </template>
                    <template v-else>
                        <a :class="{ 'tw-cursor-wait': busy.isBusy }" class="tw-cursor-pointer " @click="doRestore">
                            {{ item.isRestoring ? 'Restoring...' : 'Restore' }}
                        </a>
                        |
                    </template>
                    <a :class="{ 'tw-cursor-wait': busy.isBusy }" class="tw-text-red-700 tw-cursor-pointer hover:tw-text-red-800" @click="doDelete">
                        {{ item.isDeleting ? 'Deleting...' : (item.deleted_at ? 'Delete Permanently' : 'Delete') }}
                    </a>
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
import { inject } from 'vue';
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

const selectedItems = inject('selectedItems');

function previewInlineStyle() {
    return {
        fontFamily: props.item.family,
        fontSize: `${props.preview.fontSize}px`,
        fontWeight: props.item.weight,
        fontStyle: props.item.style,
    };
}

function doUpdateStatus() {
    busy.add('fonts.updateStatus');
    props.item.isUpdatingStatus = true;

    api
        .request({
            method: 'PATCH',
            url: `/fonts/update-status/${props.item.id}`,
            data: {
                status: !props.item.status,
            },
        })
        .then((response) => {
            return response.data;
        })
        .then(data => {
            props.item.status = data.status;
        })
        .catch(function (error) {
            notifier.alert(error.message);
        })
        .finally(() => {
            props.item.isUpdatingStatus = false;
            busy.remove('fonts.updateStatus');
        });
}

function doDelete() {
    props.item.isDeleting = true;
    busy.add('fonts.delete');

    api
        .request({
            method: 'DELETE',
            url: `/fonts/delete/${props.item.id}`,
        })
        .then((response) => {
            props.item.isDeleted = true;
        })
        .catch(function (error) {
            notifier.alert(error.message);
        })
        .finally(() => {
            props.item.isDeleting = false;
            busy.remove('fonts.delete');
        });
}


function doRestore() {
    busy.add('fonts.restore');
    props.item.isRestoring = true;

    api
        .request({
            method: 'POST',
            url: `/fonts/restore/${props.item.id}`,
        })
        .then((response) => {
            props.item.isRestored = true;
        })
        .catch(function (error) {
            notifier.alert(error.message);
        })
        .finally(() => {
            props.item.isRestoring = false;
            busy.remove('fonts.restore');
        });
}
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