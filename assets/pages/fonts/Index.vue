<template>
    <router-link :to="{ name: 'fonts.create.custom' }" class="page-title-action">{{ __('Add New', 'yabe-webfont') }}</router-link>
    <button @click="showImportModal = !showImportModal" v-ripple class="page-title-action">Import</button>
    <router-link :to="{ name: 'fonts.create.google-fonts' }" class="page-title-action">{{ __('Import Google Fonts', 'yabe-webfont') }}</router-link>

    <span v-if="route.query.search" class="subtitle"> {{ __('Search results for', 'yabe-webfont') }}: <strong> {{ route.query.search }} </strong> </span>

    <button type="button" :disabled="busy.isBusy" @click="doRefreshItems" v-ripple class="button float:right"> refresh 🔄️</button>

    <hr class="invisible m:0 mt:-2" />


    <div class="upload-plugin-wrap">
        <div :class="{ 'block': showImportModal }" class="upload-plugin">
            <p class="install-help">Import <code>ywf-exported-{timestamp}.json</code> file by locating the file and clicking "Import json" button.</p>
            <div class="wp-upload-form">
                <input type="file" ref="importfile" id="importfile" name="importfile" accept=".json" />
                <div @click="doImportJsonFile" class="button">Import json</div>
            </div>
        </div>
    </div>

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
        <li class="trash pl:4">
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

    <p class="search-box rel">
        <input type="search" id="searchInput" name="s" v-model.trim.lazy="query.search" @keyup.enter="$refs.searchBtn.click()" :disabled="busy.isBusy" />
        <button type="button" class="button" :disabled="busy.isBusy" ref="searchBtn" @click="doSearch()" v-ripple>{{ __('Search', 'yabe-webfont') }} 🔍</button>
    </p>

    <div class="tablenav top">
        <TheBulkAction :actions="bulkActions" @bulk-actions="doBulkActions" />
        <div class="tablenav-pages pb:12">
            <span class="displaying-num"> {{ `${meta.total_filtered} ${__('items', 'yabe-webfont')}` }} </span>
            <ThePagination v-if="meta.last_page > 1" :is-enable-goto="true" :current-page="meta.current_page" :first-page="meta.current_page - 1 > 1" :previous-page="meta.current_page > 1" :next-page="meta.current_page < meta.last_page" :last-page="meta.current_page + 1 < meta.last_page" :total-page="meta.last_page" @change-page="doChangePage" />
        </div>
        <br class="clear" />
    </div>

    <table class="wp-list-table widefat table-auto min-w:full plugins">
        <thead>
            <tr>
                <td class="manage-column column-cb ywf-check-column px:2 vertical:middle">
                    <input v-model="selectAll" class="ml:12" type="checkbox" />
                </td>
                <td v-if="!Boolean(query.soft_deleted)" class="manage-column">
                </td>
                <th scope="col">
                    {{ __('Title', 'yabe-webfont') }}
                </th>
                <th scope="col">
                    {{ __('Font Family', 'yabe-webfont') }}
                </th>
                <th scope="col">
                    {{ __('Modified', 'yabe-webfont') }}
                </th>
                <th scope="col" class="flex align-items:center">
                    {{ __('Preview', 'yabe-webfont') }}
                    <template v-if="items.length > 0">
                        <div class="px:16 h:fit flex r:6 shadow-sm">
                            <span class="inline-flex align-items:center rl:6 b:1|solid|gray-30/.5 br:0 bg:gray-5 px:12 fg:gray-40 font:12 lh:16px">size</span>
                            <input type="number" v-model="preview.fontSize" class="block min-w:0 w:64 min-h:0 h:24 mx:0 py:0 px:8 b:1|solid|gray-30/.5 r:0 font:12 lh:16px" />
                            <span class="inline-flex align-items:center rr:6 b:1|solid|gray-30/.5 bl:0 bg:gray-5 px:12 fg:gray-40 font:12 lh:16px">px</span>
                        </div>
                        <div class="h:fit flex r:6 shadow-sm">
                            <span class="inline-flex align-items:center rl:6 b:1|solid|gray-30/.5 br:0 bg:gray-5 px:12 fg:gray-40 font:12 lh:16px">weight</span>
                            <input type="number" min="1" max="1000" v-model="preview.fontWeight" class="block min-w:0 w:64 min-h:0 h:24 mx:0 py:0 px:8 r:0 rr:6 b:1|solid|gray-30/.5 font:12 lh:16px" />
                        </div>
                    </template>
                </th>
            </tr>
        </thead>
        <tbody v-if="items.length > 0 && !getBusyHasTask('fonts.index:fetch-items')">
            <TheFontIndexRow v-for="item in items" :key="item.id" :item="item" :preview="preview" @delete="doDelete(item)" @restore="doRestore(item)" @update-status="doUpdateStatus(item, null)" />
        </tbody>
        <tbody v-else-if="getBusyHasTask('fonts.index:fetch-items')">
            <tr v-for="skeleton in meta.skeleton" class="inactive animation:skeleton|2s|infinite">
                <th scope="row" class="vertical:middle py:8 ywf-check-column">
                    <input type="checkbox" value="0" disabled />
                </th>
                <td v-if="!Boolean(query.soft_deleted)" width="1%" class="manage-column vertical:middle">
                    <Switch :checked="false" class="opacity:.5 bg:gray-85 rel inline-flex p:0 h:24 w:44 flex-shrink:0 cursor:pointer rounded b:2 b:transparent">
                        <span class="translateX(0) pointer-events:none rel inline-block h:20 w:20 rounded bg:white box-shadow:0|0">
                            <span aria-hidden="true" class="abs inset:0 flex h:full w:full align-items:center justify-content:center opacity:1">
                                <font-awesome-icon :icon="['fas', 'spinner']" class="animation:rotate|linear|1s|infinite font:12 fg:gray-60"/>
                            </span>
                        </span>
                    </Switch>
                </td>
                <td width="20%">
                    <div class="h:12 bg:slate-30 r:4 w:1/2"></div>
                    <div class="row-actions visible mt-1 align-items:center flex">
                        <template v-if="!Boolean(query.soft_deleted)">
                            <a class="px:4 cursor:pointer"> Edit </a>
                            |
                            <a class="px:4 fg:red-80 cursor:wait fg:red-90:hover">
                                Trash
                            </a>
                        </template>
                        <template v-else="!Boolean(query.soft_deleted)">
                            <a class="px:4 cursor:pointer"> Restore </a>
                            |
                            <a class="px:4 fg:red-80 cursor:wait fg:red-90:hover">
                                Delete Permanently
                            </a>
                        </template>
                    </div>
                </td>
                <td width="20%" class="align-items:center vertical:middle">
                    <div class="h:12 bg:slate-30 r:4 w:1/2"></div>
                </td>
                <td width="10%" class="align-items:center vertical:middle">
                    <div class="h:12 bg:slate-30 r:4 w:full"></div>
                </td>
                <td class="vertical:middle">
                    <div class="h:12 bg:slate-30 r:4 w:11/12"></div>
                </td>
            </tr>
        </tbody>
        <tbody v-else>
            <tr>
                <td colspan="5">
                    {{ __(`It looks like you don't have any fonts.`, 'yabe-webfont') }}
                    <router-link :to="{ name: 'fonts.create.custom' }">{{ __('Perhaps you would like to add a new one?', 'yabe-webfont') }}</router-link>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td class="manage-column column-cb ywf-check-column px:2 vertical:middle">
                    <input v-model="selectAll" class="ml:12" type="checkbox" />
                </td>
                <td v-if="!Boolean(query.soft_deleted)" class="manage-column">
                </td>
                <th scope="col">
                    {{ __('Title', 'yabe-webfont') }}
                </th>
                <th scope="col">
                    {{ __('Font Family', 'yabe-webfont') }}
                </th>
                <th scope="col">
                    {{ __('Modified', 'yabe-webfont') }}
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
import cloneDeep from 'lodash-es/cloneDeep';

import { useApi } from '../../library/api';
import { useBusy } from '../../stores/busy';

import TheBulkAction from '../../components/TheBulkAction.vue';
import ThePagination from '../../components/ThePagination.vue';
import TheFontIndexRow from '../../components/fonts/TheFontIndexRow.vue';
import { useNotifier } from '../../library/notifier';
import { useWordpressNotice } from '../../stores/wordpressNotice';
import { Switch } from '@headlessui/vue';

const route = useRoute();
const router = useRouter();
const api = useApi();
const busy = useBusy();
const wordpressNotice = useWordpressNotice();

const searchBtn = ref(null);
const notifier = useNotifier();

const importfile = ref(null);
const showImportModal = ref(false);

const preview = reactive({
    text: `The quick brown fox jumps over a lazy dog`,
    fontSize: 18,
    fontWeight: 400,
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

const options = ref({});

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

            loadTypekitCss();
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

    busy.add('fonts.index:update-status');
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
            busy.remove('fonts.index:update-status');
        });
}

function doDelete(item) {
    item.isDeleting = true;
    busy.add('fonts.index:delete');

    api
        .request({
            // method: 'DELETE', // not working on IIS server without further configuration
            method: 'POST',
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
            busy.remove('fonts.index:delete');
        });
}

function doRestore(item) {
    busy.add('fonts.index:restore');
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
            busy.remove('fonts.index:restore');
        });
}

function doExport(items) {
    busy.add('fonts.index:export');

    api
        .request({
            method: 'POST',
            url: `/fonts/export`,
            data: {
                items: items,
            },
        })
        .then((response) => {
            return response.data.data;
        })
        .then(data => {
            const blob = new Blob([JSON.stringify(data)], { type: 'application/json' });
            const a = document.createElement('a');
            a.href = window.URL.createObjectURL(blob);
            a.download = `ywf-exported-${data.export_time}.json`;
            a.click();
        })
        .catch(function (error) {
            notifier.alert(error.message);
        })
        .finally(() => {
            busy.remove('fonts.index:export');
        });
}

function doImportJsonFile() {

    if (importfile.value.files.length === 0) {
        notifier.alert('Please select a file to import.');
        return;
    }

    // first element
    const jsonfile = importfile.value.files[0];

    // read file type
    if (jsonfile.type !== 'application/json') {
        notifier.alert('Please select a valid JSON file.');
        return;
    }

    // read file
    const reader = new FileReader();

    reader.onload = function (e) {
        const data = JSON.parse(e.target.result);

        if (data === null || data === undefined) {
            notifier.alert('Please select a valid JSON file.');
            return;
        }

        if (!data.hasOwnProperty('module_id') || data.module_id !== yabeWebfont.option_namespace) {
            notifier.alert('Please select a valid JSON file.');
            return;
        }

        // loop send data.items import request to server one by one to avoid server timeout
        let i = 0;

        const importNext = () => {
            if (i < data.items.length) {
                const item = data.items[i];
                busy.add('fonts.index:import');

                const promise = api
                    .request({
                        method: 'POST',
                        url: `/fonts/import`,
                        data: {
                            site_url: data.site_url,
                            is_bundled: data.is_bundled,
                            version: data.version,
                            item
                        }
                    })
                    .then((response) => {
                        const editUrl = router.resolve({
                            name: `fonts.edit.${item.type}`,
                            params: {
                                id: response.data.id,
                            },
                        }).href;

                        wordpressNotice.add({
                            type: 'success',
                            message: `<p>Font "${item.title}" imported successfully. <a href="${editUrl}">Edit Font</a></p>`
                        });
                    })
                    .catch(function (error) {
                        wordpressNotice.add({
                            type: 'error',
                            message: `<p>Failed to import font "${item.title}".`
                            // message: `<p>Google Fonts imported successfully. <a href="${editUrl}">Edit Font</a></p>`,
                        });

                        // return error so that notifier.async can handle it
                        throw error;
                    })
                    .finally(() => {
                        busy.remove('fonts.index:import');
                        i++;
                        importNext();
                    });

                notifier.async(
                    promise,
                    `Successfully imported font: ${item.title}.`,
                    `Failed to import font: ${item.title}.`,
                    `Importing font: ${item.title}...`
                );
            } else {
                doRefreshItems();
            }
        };

        importNext();
    };

    reader.readAsText(jsonfile);

    // reset file input
    importfile.value.value = '';
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
                { key: 'export', label: 'Export' },
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
                    if (item.type === 'adobe-fonts') {
                        return;
                    }
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
        case 'export':
            if (selectedItems.value.length === 0) {
                notifier.alert(__(`Please select at least one font to export.`, 'yabe-webfont'));
                return;
            }

            doExport(selectedItems.value);

            resetBulkSelection();
            break;
        default:
            break;
    }
}

function loadTypekitCss() {
    // get stylesheet element with id="typekit-css" else create it
    let typekitCss = document.getElementById('typekit-css');
    if (!typekitCss) {
        typekitCss = document.createElement('link');
        typekitCss.setAttribute('id', 'typekit-css');
        typekitCss.setAttribute('rel', 'stylesheet');
        document.head.appendChild(typekitCss);
    }


    busy.add('settings:fetch-options');
    api
        .request({
            method: 'GET',
            url: '/setting/option/index'
        })
        .then(response => response.data)
        .then(data => {
            options.value = cloneDeep(data.options);

            const project_id = options.value.adobe_fonts?.project_id;
            if (project_id) {
                typekitCss.setAttribute('href', `https://use.typekit.net/${project_id}.css`);
            }
        })
        .catch(function (error) {
            notifier.alert(error.message);
        })
        .finally(() => {
            busy.remove('settings:fetch-options');
        });


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

.plugins tr:last-child.active td,
.plugins tr:last-child.active th,
.plugins tr:last-child.inactive td,
.plugins tr:last-child.inactive th {
    box-shadow: none;
}
</style>