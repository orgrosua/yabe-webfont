<template>
    <div class="alignleft actions bulkactions">
        <select id="bulk-action-selector-top" v-model="chosen" name="action" :disabled="isDisableAction">
            <option value="-1">
                {{ __('Bulk actions', 'yabe-webfont') }}
            </option>
            <option v-for="action in actions" :key="action.key" :value="action.key">
                {{ action.label }}
            </option>
        </select>
        <button type="submit" class="button action" :disabled="isDisableAction" @click="$emit('doBulkActions', chosen)" v-ripple >
            {{ __('Apply', 'yabe-webfont') }}
        </button>
    </div>
</template>

<script setup>
import { ref } from "vue";

const props = defineProps({
    actions: {
        type: Array,
        default: () => []
    },
    isDisableAction: {
        type: Boolean,
        default: false,
        required: false
    }
});

const emit = defineEmits(["doBulkActions"]);

const chosen = ref("-1");
</script>