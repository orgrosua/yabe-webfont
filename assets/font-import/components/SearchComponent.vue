<template>
    <VueSelect :options="paginatedFontsIndex" :filterable="false" @search="onSearch" v-model="selectedFont" label="family" placeholder="Choose Font Family">
        <template #list-footer>
            <li class="tw-flex tw-my-0 tw-w-full">
                <button :disabled="!hasPrevPage" @click="offset -= limit" class="button tw-flex-1">
                    Prev
                </button>
                <button :disabled="!hasNextPage" @click="offset += limit" class="button tw-flex-1">
                    Next
                </button>
            </li>
        </template>
    </VueSelect>
</template>

<script setup>
import Fuse from 'fuse.js';
import { ref, computed } from 'vue';

import VueSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

const props = defineProps({
    fontsIndex: {
        type: Object,
        required: true
    }
});

// Font search
const searchFontFamily = ref('');
const offset = ref(0);
const limit = ref(100);

const filteredFontsIndex = computed(() => {
    if (searchFontFamily.value) {
        const fuse = new Fuse(props.fontsIndex.fonts, {
            keys: ['family']
        });

        return fuse.search(searchFontFamily.value).map(({ item }) => item);
    }
    return props.fontsIndex.fonts;
});

const paginatedFontsIndex = computed(() => {
    return filteredFontsIndex.value.slice(offset.value, limit.value + offset.value);
});

const hasNextPage = computed(() => {
    const nextOffset = offset.value + limit.value;
    return Boolean(filteredFontsIndex.value.slice(nextOffset, limit.value + nextOffset).length);
});

const hasPrevPage = computed(() => {
    const prevOffset = offset.value - limit.value;
    return Boolean(filteredFontsIndex.value.slice(prevOffset, limit.value + prevOffset).length);
});

function onSearch(query) {
    searchFontFamily.value = query;
    offset.value = 0;
}
</script>

<style>
.v-select {
    --vs-font-size: 1.7em;
    box-shadow: 0 0 0 transparent;
    border-radius: 4px;
    border: 1px solid #8c8f94;
    background-color: #fff;
    color: #2c3338;
}

input[type=search].vs__search {
    background-color: transparent;
    border: none;
    box-shadow: none;
    line-height: normal;
    margin: 0;
    padding-top: 2px;
    padding-bottom: 2px;
}

input[type=search].vs__search::placeholder {
    color: #646970;
}

span.vs__selected {
    font-size: var(--vs-font-size);
    margin: 0 2px;
}

.vs__actions {
    --vs-actions-padding: 0px 6px 0 3px;
    padding-top: 0;
}

.vs__dropdown-toggle {
    padding: 0;
    border: none;
}

.vs__dropdown-menu {
    padding: 0;
}
</style>