<template>
    <div class="alignleft actions bulkactions">
        <select id="bulk-action-selector-top" v-model="chosen" name="action" :disabled="busy.isBusy">
            <option value="-1">
                {{ __('Bulk actions', 'yabe-webfont') }}
            </option>
            <option v-for="action in actions" :key="action.key" :value="action.key">
                {{ action.label }}
            </option>
        </select>
        <button type="submit" class="button action" :disabled="busy.isBusy" @click="$emit('bulkActions', chosen)" v-ripple >
            {{ __('Apply', 'yabe-webfont') }}
        </button>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useBusy } from '../stores/busy';

const props = defineProps({
    actions: {
        type: Array,
        default: () => []
    },
});

const busy = useBusy();

const emit = defineEmits(['bulkActions']);

const chosen = ref('-1');
</script>