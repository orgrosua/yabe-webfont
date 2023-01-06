<template>
    <router-link :to="{ name: 'fonts.create.custom' }" class="page-title-action">{{ __('Add New', 'yabe-webfont') }}</router-link>
    <router-link :to="{ name: 'fonts.create.google-fonts' }" class="page-title-action">{{ __('Import Google Fonts', 'yabe-webfont') }}</router-link>

    <span v-if="route.query.search" class="subtitle"> {{ __('Search results for', 'yabe-webfont') }}: <strong> {{ route.query.search }} </strong> </span>

    <button type="button" :disabled="busy.isBusy" @click="doRefreshItems" v-ripple class=" button tw-float-right"> refresh 🔄️</button>

    <hr class="tw-invisible tw-m-0 -tw-mt-0.5" />

    <ul class="subsubsub">
        <li class="all">
            <router-link :to="{
                name: 'fonts.index',
                query: {
                    ...route.query,
                    soft_deleted: 0,
                    page: 1,
                },
            }" :class="{ current: !Boolean(query.soft_deleted) }"> {{ __('All', 'yabe-webfont') }} <span class="count"> ({{ meta.total_exists }}) </span> </router-link>
            |
        </li>
        <li class="trash tw-pl-1">
            <router-link :to="{
                name: 'fonts.index',
                query: {
                    ...route.query,
                    soft_deleted: 1,
                    page: 1,
                },
            }" :class="{ current: Boolean(query.soft_deleted) }"> {{ __('Trash', 'yabe-webfont') }} <span class="count"> ({{ meta.total_deleted }}) </span> </router-link>
        </li>
    </ul>

    <p class="search-box tw-relative">
        <input type="search" id="searchInput" name="s" v-model.trim.lazy="query.search" @keyup.enter="$refs.searchBtn.click()" :disabled="busy.isBusy" />
        <button type="button" class="button" :disabled="busy.isBusy" ref="searchBtn" @click="doSearch()" v-ripple>{{ __('Search', 'yabe-webfont') }} 🔍</button>
    </p>

    <div class="tablenav top">
        <TheBulkAction :actions="bulkActions" @bulk-actions="doBulkActions" />
        <div class="tablenav-pages tw-pb-3">
            <span class="displaying-num"> {{ `${meta.total_filtered} ${__('items', 'yabe-webfont')}` }} </span>
            <ThePagination v-if="meta.last_page > 1" :is-enable-goto="true" :current-page="meta.current_page" :first-page="meta.current_page - 1 > 1" :previous-page="meta.current_page > 1" :next-page="meta.current_page < meta.last_page" :last-page="meta.current_page + 1 < meta.last_page" :total-page="meta.last_page" @change-page="doChangePage" />
        </div>
        <br class="clear" />
    </div>

    <table class="wp-list-table widefat table-auto tw-min-w-full plugins">
        <thead>
            <tr>
                <td class="manage-column column-cb ywf-check-column tw-px-0.5 tw-align-middle">
                    <input v-model="selectAll" class="tw-ml-3" type="checkbox" />
                </td>
                <th scope="col">
                    {{ __('Title', 'yabe-webfont') }}
                </th>
                <th scope="col">
                    {{ __('Font Family', 'yabe-webfont') }}
                </th>
                <th scope="col" class="tw-flex tw-items-center">
                    {{ __('Preview', 'yabe-webfont') }}
                    <div v-if="items.length > 0" class="tw-px-4 tw-h-fit tw-flex tw-rounded-md tw-shadow-sm">
                        <span class="tw-inline-flex tw-items-center tw-rounded-l-md tw-border tw-border-solid !tw-border-r-0 !tw-border-gray-300 tw-bg-gray-50 tw-px-3 tw-text-gray-500 !tw-text-xs">size</span>
                        <input type="number" v-model="preview.fontSize" class="!tw-block !tw-min-w-0 tw-w-16 !tw-min-h-0 !tw-h-6 tw-mx-0 !tw-py-0 !tw-px-2 !tw-border  !tw-border-solid !tw-rounded-none !tw-border-gray-300 !tw-text-xs" />
                        <span class="tw-inline-flex tw-items-center tw-rounded-r-md tw-border tw-border-solid !tw-border-l-0 !tw-border-gray-300 tw-bg-gray-50 tw-px-3 tw-text-gray-500 !tw-text-xs">px</span>
                    </div>
                </th>
            </tr>
        </thead>
        <tbody v-if="items.length > 0 && !getBusyHasTask('fonts.index:fetch-items')">
            <TheFontIndexRow v-for="item in items" :key="item.id" :item="item" :preview="preview" @delete="doDelete(item)" @restore="doRestore(item)" @update-status="doUpdateStatus(item, null)" />
        </tbody>
        <tbody v-else-if="getBusyHasTask('fonts.index:fetch-items')">
            <tr v-for="skeleton in meta.skeleton" class="inactive tw-animate-pulse">
                <th scope="row" class="tw-align-middle tw-py-2 ywf-check-column">
                    <input type="checkbox" value="0" disabled />
                </th>
                <td width="25%">
                    <div class="tw-h-3 tw-bg-slate-400 tw-rounded tw-w-1/2"></div>
                    <div class="row-actions visible tw-mt-1 tw-items-center tw-flex tw-divide-x-2 tw-divide-solid tw-divide-y-0 tw-divide-gray-400">
                        <span class="tw-pr-1 tw-text-gray-400 tw-flex tw-items-center">ID: <div class="tw-ml-1 tw-h-3 tw-bg-slate-400 tw-rounded tw-w-6"></div></span>
                        <a class="tw-px-1 tw-text-red-700 tw-cursor-wait hover:tw-text-red-800">
                            (De)activate
                        </a>
                        <a class="tw-px-1 tw-cursor-pointer"> Edit </a>
                        <a class="tw-px-1 tw-text-red-700 tw-cursor-wait hover:tw-text-red-800">
                            Delete
                        </a>
                    </div>
                </td>
                <td width="20%" class="tw-items-center tw-align-middle">
                    <div class="tw-h-3 tw-bg-slate-400 tw-rounded tw-w-1/2"></div>
                </td>
                <td class="tw-align-middle">
                    <div class="tw-h-3 tw-bg-slate-400 tw-rounded tw-w-11/12"></div>
                </td>
            </tr>
        </tbody>
        <tbody v-else>
            <tr>
                <td colspan="5">
                    {{ __('No fonts found.', 'yabe-webfont') }}
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td class="manage-column column-cb ywf-check-column tw-px-0.5 tw-align-middle">
                    <input v-model="selectAll" class="tw-ml-3" type="checkbox" />
                </td>
                <th scope="col">
                    {{ __('Title', 'yabe-webfont') }}
                </th>
                <th scope="col">
                    {{ __('Font Family', 'yabe-webfont') }}
                </th>
                <th scope="col">
                    {{ __('Preview', 'yabe-webfont') }}
                </th>
            </tr>
        </tfoot>
    </table>

    <div class="tablenav bottom">
        <TheBulkAction :actions="bulkActions" @bulk-actions="doBulkActions" />
        <div class="tablenav-pages">
            <span class="displaying-num"> {{ `${meta.total_filtered} ${__('items', 'yabe-webfont')}` }} </span>
            <ThePagination v-if="meta.last_page > 1" :current-page="meta.current_page" :first-page="meta.current_page - 1 > 1" :previous-page="meta.current_page > 1" :next-page="meta.current_page < meta.last_page" :last-page="meta.current_page + 1 < meta.last_page" :total-page="meta.last_page" @change-page="doChangePage" />
        </div>
        <br class="clear" />
    </div>
</template>

<script setup>
import { ref, reactive, computed, watch, onMounted, provide } from 'vue';
import { useRouter, useRoute, onBeforeRouteUpdate } from 'vue-router';
import { __ } from '@wordpress/i18n';

import { useApi } from '../../library/api';
import { useBusy } from '../../stores/busy';

import TheBulkAction from '../../components/TheBulkAction.vue';
import ThePagination from '../../components/ThePagination.vue';
import TheFontIndexRow from '../../components/fonts/local/TheFontIndexRow.vue';
import { useNotifier } from '../../library/notifier';

const route = useRoute();
const router = useRouter();
const api = useApi();
const busy = useBusy();

const searchBtn = ref(null);
const notifier = useNotifier();

const preview = reactive({
    text: `I can do all things through Christ which strengtheneth me. [Philippians 4:13]`,
    fontSize: 16,
    lineHeight: 1.5,
});

const query = reactive({
    page: route.query.page ? Number(route.query.page) : 1,
    search: route.query.search || '',
    per_page: route.query.per_page ? Number(route.query.per_page) : 20,
    soft_deleted: route.query.soft_deleted ? Number(route.query.soft_deleted) : 0,
});

const meta = reactive({
    current_page: 0,
    from: 0,
    last_page: 0,
    to: 0,
    total_filtered: 0,
    total_exists: 0,
    total_deleted: 0,
    skeleton: 5,
});

const items = ref([]);

const selectedItems = ref([]);
provide('selectedItems', selectedItems);

const getBusyHasTask = busy.hasTask;

function pushWithQuery() {
    router.push({
        name: 'fonts.index',
        query: {
            ...route.query,
            ...query,
        },
    });
};

onMounted(() => {
    busy.reset();
    doRefreshItems();
});

// do search when query.search cleared
watch(() => query.search, (value, oldValue) => {
    if ('' === value && route.query.search.trim() !== value) {
        doSearch(true);
    }
});

function doChangePage(page) {
    query.page = page;
    pushWithQuery();
}

onBeforeRouteUpdate((to, from) => {
    if (to.query.page !== from.query.page) {
        query.page = to.query.page ? Number(to.query.page) : 1;
    }

    if (to.query.per_page !== from.query.per_page) {
        query.per_page = to.query.per_page ? Number(to.query.per_page) : 20;
    }

    if (to.query.soft_deleted !== from.query.soft_deleted) {
        query.soft_deleted = to.query.soft_deleted ? Number(to.query.soft_deleted) : 0;
    }

    doRefreshItems();
});

function doSearch(reset = false) {
    query.page = 1;

    if (reset) {
        query.search = '';
    }

    pushWithQuery();
}

function doRefreshItems() {
    busy.add('fonts.index:fetch-items');
    api
        .request({
            method: 'GET',
            url: '/fonts/index',
            params: {
                page: query.page,
                search: query.search,
                per_page: query.per_page,
                soft_deleted: query.soft_deleted,
            },
        })
        .then(response => response.data)
        .then(data => {
            items.value = data.data.map(item => {
                item.isDeleted = false;
                item.isDeleting = false;
                item.isUpdatingStatus = false;
                item.isRestored = false;
                item.isRestoring = false;
                return item;
            });

            meta.total_exists = data.meta.total_exists;
            meta.total_deleted = data.meta.total_deleted;
            meta.total_filtered = data.meta.total_filtered;
            meta.current_page = data.meta.page;
            meta.from = data.meta.from;
            meta.to = data.meta.to;
            meta.last_page = data.meta.total_pages;

            meta.skeleton = data.data.length > 0 ? data.data.length : 5;

            resetBulkSelection();
        })
        .catch(function (error) {
            notifier.alert(error.message);
        })
        .finally(() => {
            busy.remove('fonts.index:fetch-items');
            resetBulkSelection();
        });
}

function doUpdateStatus(item, status = null) {
    if (status === item.status) {
        return;
    }

    busy.add('fonts.updateStatus');
    item.isUpdatingStatus = true;

    api
        .request({
            method: 'PATCH',
            url: `/fonts/update-status/${item.id}`,
            data: {
                status: status !== null ? status : !item.status,
            },
        })
        .then((response) => {
            return response.data;
        })
        .then(data => {
            item.status = data.status;
        })
        .catch(function (error) {
            notifier.alert(error.message);
        })
        .finally(() => {
            item.isUpdatingStatus = false;
            busy.remove('fonts.updateStatus');
        });
}

function doDelete(item) {
    item.isDeleting = true;
    busy.add('fonts.delete');

    api
        .request({
            method: 'DELETE',
            url: `/fonts/delete/${item.id}`,
        })
        .then((response) => {
            item.isDeleted = true;
        })
        .catch(function (error) {
            notifier.alert(error.message);
        })
        .finally(() => {
            item.isDeleting = false;
            busy.remove('fonts.delete');
        });
}

function doRestore(item) {
    busy.add('fonts.restore');
    item.isRestoring = true;

    api
        .request({
            method: 'POST',
            url: `/fonts/restore/${item.id}`,
        })
        .then((response) => {
            item.isRestored = true;
        })
        .catch(function (error) {
            notifier.alert(error.message);
        })
        .finally(() => {
            item.isRestoring = false;
            busy.remove('fonts.restore');
        });
}

const selectAll = computed({
    get() {
        if (items.value.length > 0) {
            let allChecked = true;
            for (const [index, item] of items.value.entries()) {
                if (!selectedItems.value.includes(item.id)) {
                    allChecked = false;
                }
                if (!allChecked) break;
            }
            return allChecked;
        }
        return false;
    },
    set(value) {
        const checked = [];
        if (value) {
            items.value.forEach((item) => {
                checked.push(item.id);
            });
        }
        selectedItems.value = checked;
    },
});

function resetBulkSelection() {
    selectedItems.value = [];
}

const bulkActions = ref([]);

watch(
    () => query.soft_deleted,
    () => {
        if (Boolean(query.soft_deleted)) {
            bulkActions.value = [
                { key: 'restore', label: 'Restore' },
                { key: 'delete', label: 'Delete permanently' },
            ];
        } else {
            bulkActions.value = [
                { key: 'activate', label: 'Activate' },
                { key: 'deactivate', label: 'Deactivate' },
                { key: 'delete', label: 'Delete' },
            ];
        }
    },
    { immediate: true }
);

function doBulkActions(action) {
    if (action === '-1') {
        return;
    }
    switch (action) {
        case 'delete':
            if (
                confirm(__(`Are you sure you want to delete the selected font(s)?`, 'yabe-webfont'))
            ) {
                selectedItems.value.forEach(async (id) => {
                    const item = items.value.find((item) => item.id === id);
                    doDelete(item);
                });
                resetBulkSelection();
            }
            break;
        case 'deactivate':
            if (
                confirm(__(`Are you sure you want to deactivate the selected font(s)?`, 'yabe-webfont'))
            ) {
                selectedItems.value.forEach(async (id) => {
                    const item = items.value.find((item) => item.id === id);
                    doUpdateStatus(item, false);
                });
                resetBulkSelection();
            }
            break;
        case 'activate':
            if (
                confirm(__(`Are you sure you want to activate the selected font(s)?`, 'yabe-webfont'))
            ) {
                selectedItems.value.forEach(async (id) => {
                    const item = items.value.find((item) => item.id === id);
                    doUpdateStatus(item, true);
                });
                resetBulkSelection();
            }
            break;
        case 'restore':
            if (
                confirm(__(`Are you sure you want to restore the selected font(s)?`, 'yabe-webfont'))
            ) {
                selectedItems.value.forEach(async (id) => {
                    const item = items.value.find((item) => item.id === id);
                    doRestore(item);
                });
                resetBulkSelection();
            }
            break;
        default:
            break;
    }
}
</script>

<style>
.widefat .ywf-check-column {
    width: 2.2em;
    padding: 6px 0 25px;
    vertical-align: top
}

.widefat tbody th.ywf-check-column {
    padding: 9px 0 22px
}

.updates-table tbody td.ywf-check-column,
.widefat tbody th.ywf-check-column,
.widefat tfoot td.ywf-check-column,
.widefat thead td.ywf-check-column {
    padding: 11px 0 0 3px
}

.widefat tfoot td.ywf-check-column,
.widefat thead td.ywf-check-column {
    padding-top: 4px;
    vertical-align: middle
}

.plugins tbody,
.plugins tbody th.ywf-check-column {
    padding: 8px 0 0 2px
}

.plugins tbody th.ywf-check-column input[type=checkbox] {
    margin-top: 4px
}

.plugins .inactive th.ywf-check-column,
.plugins tfoot td.ywf-check-column,
.plugins thead td.ywf-check-column {
    padding-left: 6px
}

.plugin-update-tr.active td,
.plugins .active th.ywf-check-column {
    border-left: 4px solid #72aee6
}

.plugins tr.paused th.ywf-check-column {
    border-left: 4px solid #b32d2e
}

@media screen and (max-width: 782px) {
    .wp-list-table tr th.ywf-check-column {
        display: table-cell
    }

    .wp-list-table .ywf-check-column {
        width: 2.5em
    }

    .wp-list-table tr:not(.inline-edit-row):not(.no-items) td:not(.ywf-check-column) {
        position: relative;
        clear: both;
        width: auto !important
    }

    .wp-list-table tr:not(.inline-edit-row):not(.no-items) td.column-primary~td:not(.ywf-check-column) {
        padding: 3px 8px 3px 35%
    }

    .widefat tfoot td.ywf-check-column,
    .widefat thead td.ywf-check-column {
        padding-top: 10px
    }

    .plugins .plugin-update-tr:before,
    .plugins tr.active+tr.inactive td.column-description,
    .plugins tr.active+tr.inactive th.ywf-check-column {
        box-shadow: inset 0 -1px 0 rgba(0, 0, 0, .1)
    }

    .plugins tr.active+tr.inactive td,
    .plugins tr.active+tr.inactive th.ywf-check-column {
        border-top: none
    }

    .plugins tbody th.ywf-check-column {
        padding: 8px 0 0 5px
    }

    .plugins .inactive th.ywf-check-column,
    .plugins tfoot td.ywf-check-column,
    .plugins thead td.ywf-check-column {
        padding-left: 9px
    }
}
</style>