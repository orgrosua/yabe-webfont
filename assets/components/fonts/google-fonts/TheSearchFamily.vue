<template>
    <VueSelect :options="paginatedCatalog" v-model="fontData" label="family" :filterable="false" @search="onSearch" @keyup.ctrl.left="offset -= limit * hasPrevPage" @keyup.ctrl.right="offset += limit * hasNextPage" placeholder="Choose Font Family">
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
    </VueSelect>
</template>

<script setup>
import Fuse from 'fuse.js';
import { ref, computed } from 'vue';

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

const filteredCatalog = computed(() => {
    if (searchFontFamily.value) {
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
</script>
