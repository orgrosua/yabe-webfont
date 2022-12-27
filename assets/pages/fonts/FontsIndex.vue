<template>
    <router-link :to="{ name: 'fonts.create.custom' }" class="page-title-action">{{ __('Add New', 'yabe-webfont') }}</router-link>
    <router-link :to="{ name: 'fonts.create.google-fonts' }" class="page-title-action">{{ __('Import Google Fonts', 'yabe-webfont') }}</router-link>

    <span v-if="searchQuery" class="subtitle"> {{ __('Search results for', 'yabe-webfont') }}: <strong> {{ searchQuery }} </strong> </span>
    <hr class="tw-invisible tw-m-0 -tw-mt-0.5" />

    <ul class="subsubsub">
        <li class="all">
            <a class="current" aria-current="page">
                {{ __('All', 'yabe-webfont') }} <span class="count"> ({{ meta.total }}) </span>
            </a>
        </li>
    </ul>

    <p class="search-box tw-relative">
        <input id="searchInput" v-model.trim.lazy="searchQuery" type="search" name="s" :disabled="isDisableAction" />
        <button type="submit" class="button" :disabled="isDisableAction" @click="doSearch" v-ripple>{{ __('Search', 'yabe-webfont') }} 🔍</button>
    </p>

    <div class="tablenav top">
        <TheBulkAction :actions="bulkActions" :is-disable-action="isDisableAction" @do-bulk-actions="doBulkActions" />
        <div class="tablenav-pages tw-pb-3">
            <span class="displaying-num"> {{ `${meta.total} ${__('items', 'yabe-webfont')}` }} </span>
            <ThePagination v-if="meta.last_page > 1" :is-enable-goto="true" :is-disable-action="isDisableAction" :current-page="meta.current_page" :first-page="meta.current_page - 1 > 1" :previous-page="meta.current_page > 1" :next-page="meta.current_page < meta.last_page" :last-page="meta.current_page + 1 < meta.last_page" :total-page="meta.last_page" @change-page="doChangePage" />
        </div>
        <br class="clear" />
    </div>

    <!-- placeholder: start: table here -->


    <table class="wp-list-table widefat table-auto plugins tw-min-w-full">
        <thead>
            <tr>
                <td class="tw-px-0.5 tw-align-middle">
                    <input v-model="selectAll" type="checkbox" />
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
                <th scope="col">
                    {{ __('Files', 'yabe-webfont') }}
                </th>
                <th scope="col">
                    {{ __('Type', 'yabe-webfont') }}
                </th>
            </tr>
        </thead>

        <!-- <tbody v-if="items.length > 0">
            <IndexTheTableItem v-for="(item, index) in items" :key="item.id" :item-id="item.id" :item-index="index" :endpoint="item.endpoint" :site-name="item.site_title" :api-key="item.key" :api-secret="item.secret" :is-deleted="item.isDeleted ?? false" :is-deleting="item.isDeleting ?? false" :is-disable-action="isDisableAction" @edit="doEdit(item.id)" @delete="doDestroy([index])" />
        </tbody>
        <tbody v-else>
            <tr>
                <td colspan="5">
                    No remotes found.
                </td>
            </tr>
        </tbody> -->
        <tfoot>
            <tr>
                <td class="tw-px-0.5 tw-align-middle">
                    <input v-model="selectAll" type="checkbox" />
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
                <th scope="col">
                    {{ __('Files', 'yabe-webfont') }}
                </th>
                <th scope="col">
                    {{ __('Type', 'yabe-webfont') }}
                </th>
            </tr>
        </tfoot>
    </table>

    <!-- placeholder: end: table here -->

    <div class="tablenav bottom">
        <TheBulkAction :actions="bulkActions" :is-disable-action="isDisableAction" @do-bulk-actions="doBulkActions" />
        <div class="tablenav-pages">
            <span class="displaying-num"> {{ `${meta.total} ${__('items', 'yabe-webfont')}` }} </span>
            <ThePagination v-if="meta.last_page > 1" :is-disable-action="isDisableAction" :current-page="meta.current_page" :first-page="meta.current_page - 1 > 1" :previous-page="meta.current_page > 1" :next-page="meta.current_page < meta.last_page" :last-page="meta.current_page + 1 < meta.last_page" :total-page="meta.last_page" @change-page="doChangePage" />
        </div>
        <br class="clear" />
    </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue';
import TheBulkAction from '../../components/TheBulkAction.vue';
import ThePagination from '../../components/ThePagination.vue';

const isDisableAction = computed(() => false);

const searchQuery = ref('');

// TODO
function doSearch(reset = false) {
    if (reset) {
        searchQuery.value = "";
    }
    // store.dispatch('remotes/updateQuery', {
    //     page: 1,
    //     search: search.value
    // }).then(() => {
    //     doRefreshRemote();
    // });
}

const meta = reactive({
    current_page: 0,
    from: 0,
    last_page: 0,
    to: 0,
    total: 0
});

// TODO
const items = reactive([]);

// TODO
const selectAll = computed({
    get() {
        if (items.value && items.value.length > 0) {
            let allChecked = true;
            for (const [index, value] of items.value.entries()) {
                if (!selectedItems.value.includes(index)) {
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
            items.value.forEach((item, index) => {
                if (
                    !(
                        items.value[index].isDeleted !== null &&
                        items.value[index].isDeleted !== undefined &&
                        items.value[index].isDeleting !== null &&
                        items.value[index].isDeleting !== undefined
                    )
                ) {
                    checked.push(index);
                }
            });
        }
        selectedItems.value = checked;
    },
});

function resetBulkSelection() {
    selectAll.value = false;
}

const bulkActions = [{ key: "delete", label: "Delete" }];

// TODO
function doBulkActions(action) {
    if (action === "-1") {
        return;
    }
    // switch (action) {
    //     case "delete":
    //         if (
    //             confirm("Are you sure you want to delete the selected remote(s)?")
    //         ) {
    //             // doDestroy(selectedItems.value, true);
    //         }
    //         break;
    //     default:
    //         break;
    // }
}

// TODO
function doChangePage(page) {
    // store.dispatch('remotes/updateQuery', {
    //     page: page,
    // }).then(() => {
    //     doRefreshRemote();
    // });
}


</script>