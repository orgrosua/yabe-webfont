<template>
    <VueSelect ref="vueSelectEl" :options="paginatedCatalog" v-model="fontData" label="family" :filterable="false" :autoscroll="true" @search="onSearch" @keyup.ctrl.left="offset -= limit * hasPrevPage" @keyup.ctrl.right="offset += limit * hasNextPage" placeholder="Choose Font Family" class="ywf-google-search-family">
        <template #list-footer>
            <li class="tw-flex tw-my-0 tw-w-full">
                <button type="button" :disabled="!hasPrevPage" @click="offset -= limit" class="button tw-flex-1" v-ripple>
                    Prev
                </button>
                <button type="button" :disabled="!hasNextPage" @click="offset += limit" class="button tw-flex-1" v-ripple>
                    Next
                </button>
            </li>
        </template>
        <template #option="{family, category}">
            <div class="tw-flex tw-justify-between">
                <span class="">{{ family }}</span>

                <span class="tw-text-xs tw-text-gray-500 tw-bg-gray-100 tw-px-1 tw-py-0.5 tw-rounded">{{ category.replace(/[^a-zA-Z0-9\-_]+/g, '-').toLowerCase() }}</span>
            </div>
        </template>
    </VueSelect>
</template>

<script setup>
import Fuse from 'fuse.js';
import { ref, computed, onMounted } from 'vue';

const props = defineProps({
    catalog: {
        type: Object,
        required: true
    },
    modelValue: {
        type: Object,
    }
});

const emit = defineEmits(['update:modelValue']);

const fontData = computed({
    get() {
        return props.modelValue
    },
    set(value) {
        emit('update:modelValue', value);
    }
})

// Font search
const searchFontFamily = ref('');
const offset = ref(0);
const limit = ref(50);

const vueSelectEl = ref(null);

const categories = computed(() => {
    return [...new Set(props.catalog.map(({ category }) => category.trim().replace(/[^a-zA-Z0-9\-_]+/g, '-').toLowerCase()))];
});

const filteredCatalog = computed(() => {
    if (searchFontFamily.value) {

        if (searchFontFamily.value.toLowerCase().startsWith(':')) {
            const queryCategory = searchFontFamily.value.slice(1).trim().replace(/[^a-zA-Z0-9\-_]+/g, '-').toLowerCase();
            if (categories.value.includes(queryCategory)) {
                return props.catalog.filter(({ category }) =>  category.trim().replace(/[^a-zA-Z0-9\-_]+/g, '-').toLowerCase() === queryCategory);
            }
        }

        const fuse = new Fuse(props.catalog, {
            keys: ['family']
        });

        return fuse.search(searchFontFamily.value).map(({ item }) => item);
    }
    return props.catalog;
});

const paginatedCatalog = computed(() => {
    return filteredCatalog.value.slice(offset.value, limit.value + offset.value);
});

const hasNextPage = computed(() => {
    const nextOffset = offset.value + limit.value;
    return Boolean(filteredCatalog.value.slice(nextOffset, limit.value + nextOffset).length);
});

const hasPrevPage = computed(() => {
    const prevOffset = offset.value - limit.value;
    return Boolean(filteredCatalog.value.slice(prevOffset, limit.value + prevOffset).length);
});

function onSearch(query) {
    searchFontFamily.value = query;
    offset.value = 0;
}

onMounted(() => {
    vueSelectEl.value.searchEl.focus();
});
</script>

<style>
.ywf-google-search-family li.vs__dropdown-option {
    --vs-dropdown-option-padding: 3px 8px;
}
</style>