<template>
    <span class="pagination-links">
        <button type="button" :disabled="busy.isBusy || !firstPage" class="tablenav-pages-navspan button tw-mx-0.5" @click="$emit('changePage', 1)" v-ripple>
            «
        </button>
        <button type="button" :disabled="busy.isBusy || !previousPage" class="tablenav-pages-navspan button tw-mx-0.5" @click="$emit('changePage', currentPage - 1)" v-ripple>
            ‹
        </button>
        <span class="paging-input tw-mx-0.5">
            <input v-if="isEnableGoto" v-model.number="thePage" class="current-page tw-w-14" type="number" :disabled="busy.isBusy" min="1" :max="totalPage" />
            <span class="tablenav-paging-text tw-mx-0.5">
                {{ isEnableGoto === false ? currentPage : '' }} of <span class="total-pages"> {{ totalPage }} </span>
            </span>
        </span>
        <button type="button" :disabled="busy.isBusy || !nextPage" class="next-page button tw-mx-0.5" @click="$emit('changePage', currentPage + 1)" v-ripple>
            ›
        </button>
        <button type="button" :disabled="busy.isBusy || !lastPage" class="tablenav-pages-navspan button tw-mx-0.5" @click="$emit('changePage', totalPage)" v-ripple>
            »
        </button>
    </span>
</template>
  
<script setup>
import { ref, watch } from 'vue';

import { useBusy } from '../stores/busy';

const props = defineProps({
    isEnableGoto: {
        type: Boolean,
        default: false,
        required: false
    },
    firstPage: {
        type: Boolean,
        default: false,
        required: false
    },
    previousPage: {
        type: Boolean,
        default: false,
        required: false
    },
    nextPage: {
        type: Boolean,
        default: false,
        required: false
    },
    lastPage: {
        type: Boolean,
        default: false,
        required: false
    },
    totalPage: {
        type: Number,
        default: 0,
        required: false
    },
    currentPage: {
        type: Number,
        default: 0,
        required: false
    }
});

const busy = useBusy();

const emit = defineEmits(['changePage']);

const thePage = ref(props.currentPage);

watch(thePage, (value, oldValue) => {
    if (
        value > 0
        && value <= props.totalPage
        && value !== oldValue
        && value !== props.currentPage
    ) {
        emit('changePage', value);
    }
});

watch(() => props.currentPage, (value, oldValue) => {
    if (value !== oldValue) {
        thePage.value = props.currentPage;
    }
});
</script>
