<template>
    <span class="pagination-links">
        <button :disabled="isDisableAction || !firstPage" class="tablenav-pages-navspan button mx-0.5 " @click="$emit('changePage', 1)">
            «
        </button>
        <button :disabled="isDisableAction || !previousPage" class="tablenav-pages-navspan button mx-0.5" @click="$emit('changePage', currentPage - 1)">
            ‹
        </button>
        <span class="paging-input mx-0.5">
            <input v-if="isEnableGoto" v-model.number="thePage" class="current-page w-14" type="number" :disabled="isDisableAction" min="1" :max="totalPage" />
            <span class="tablenav-paging-text mx-0.5">
                {{ isEnableGoto === false ? currentPage : '' }} of <span class="total-pages"> {{ totalPage }} </span>
            </span>
        </span>
        <button :disabled="isDisableAction || !nextPage" class="next-page button mx-0.5" @click="$emit('changePage', currentPage + 1)">
            ›
        </button>
        <button :disabled="isDisableAction || !lastPage" class="tablenav-pages-navspan button mx-0.5" @click="$emit('changePage', totalPage)">
            »
        </button>
    </span>
</template>
  
<script setup>
import { ref, watch } from "vue";

const props = defineProps({
    isEnableGoto: {
        type: Boolean,
        default: false,
        required: false
    },
    isDisableAction: {
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

const emit = defineEmits(["changePage"]);

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