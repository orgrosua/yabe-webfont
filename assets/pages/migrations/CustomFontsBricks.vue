<script setup>
import { ref } from 'vue';
import { useApi } from '../../library/api';
import { useNotifier } from '../../library/notifier';
import { useBusy } from '../../stores/busy.js';

const busyStore = useBusy();
const api = useApi();
const notifier = useNotifier();

const completedActions = ref({
    import: false,
    cleanup: false,
});

function doImport() {
    if (!confirm(
        completedActions.value.import
            ? 'You have already imported the fonts data. Are you sure you want to import it again?'
            : 'Are you sure you want to import the fonts data?'
    )) {
        return;
    }

    busyStore.add('migrations.index:import-fonts');

    let promise = api
        .request({
            method: 'POST',
            url: '/migrations/bricks-custom-fonts/import-fonts',
        })
        .then((response) => {
            completedActions.value.import = true;
        })
        .finally(() => {
            busyStore.remove('migrations.index:import-fonts');
        });

    notifier.async(
        promise,
        'Fonts data imported successfully.',
        'Failed to import fonts data.',
        'Importing fonts data...'
    );
}

function doCleanUp() {
    if (!confirm(
        !completedActions.value.import
            ? 'You have not imported the fonts data. Are you sure you want to clean up the Custom Fonts [Bricks] data?'
            : (
                completedActions.value.cleanup
                    ? 'You have already cleaned up the Custom Fonts [Bricks] data. Are you sure you want to clean it up again?'
                    : 'Are you sure you want to clean up the Custom Fonts [Bricks] data?'
            )
    )) {
        return;
    }

    busyStore.add('migrations.index:cleanup-fonts');

    let promise = api
        .request({
            method: 'POST',
            url: '/migrations/bricks-custom-fonts/clean-up',
        })
        .then((response) => {
            completedActions.value.cleanup = true;
        })
        .finally(() => {
            busyStore.remove('migrations.index:cleanup-fonts');
        });

    notifier.async(
        promise,
        'Custom Fonts [Bricks] data cleaned up successfully.',
        'Failed to clean up Custom Fonts [Bricks] data.',
        'Cleaning up Custom Fonts [Bricks] data...'
    );
}
</script>

<template>
    <span class="mr:8 font:24">» {{ __('Custom Fonts [Bricks]', 'yabe-webfont') }} </span>

    <div class="mt:16 r:4 rel bg:white p:24">
        <div class="mx:auto max-w:512">
            <h2 class="m:0 font:semibold lh:1.5 fg:gray-80">
                Custom Fonts [Bricks] → Yabe Webfont
            </h2>
            <p class="m:0 mt:4 font:14 fg:gray-50">
                Migrate your custom fonts from Bricks to Yabe Webfont. Manage your fonts in one place, and use them across visual builders.
            </p>
            <ul role="list" class="m:0 mt:24 bt:1|solid|gray-20 {by:1|solid|gray-20}>*+* ">
                <li class="m:0">
                    <div class="rel flex items-start gap-x:12 py:16">
                        <div class="flex-shrink:0">
                            <span :class="['bg:blue-50', 'inline-flex h:40 w:40 align-items:center justify-content:center r:8']">
                                <font-awesome-icon :icon="['far', 'file-import']" class="fg:white font:24" />
                            </span>
                        </div>
                        <div class="min-w:0 flex-grow:1">
                            <p class="m:0 font:14 font:medium text-gray-900">
                                Import fonts
                            </p>
                            <p class="m:0 font:14 fg:gray-50">
                                Import the fonts data from the Custom Fonts [Bricks] feature.
                            </p>
                        </div>
                        <div class="flex-shrink:0 align-self:center">
                            <button type="button" :disabled="busyStore.isBusy" @click="doImport" v-ripple class="button flex align-items:center gap-x:8">
                                <font-awesome-icon :icon="['fas', 'spinner']" :class="{ hidden: !busyStore.hasTask('migrations.index:import-fonts') }" class="animation:rotate|1.2s|infinite|linear fill:current" />
                                {{ __('Run', 'yabe-webfont') }}
                            </button>
                        </div>
                    </div>
                </li>
                <li class="m:0">
                    <div class="rel flex items-start gap-x:12 py:16">
                        <div class="flex-shrink:0">
                            <span :class="['bg:pink-50', 'inline-flex h:40 w:40 align-items:center justify-content:center r:8']">
                                <font-awesome-icon :icon="['far', 'broom']" class="font:24 fg:white fill:current" />
                            </span>
                        </div>
                        <div class="min-w:0 flex-grow:1">
                            <p class="m:0 font:14 font:medium fg:yellow-80">
                                <font-awesome-icon :icon="['fas', 'triangle-exclamation']" />
                                Clean up
                            </p>
                            <p class="m:0 font:14 fg:gray-50">
                                Remove the fonts data from the Custom Fonts [Bricks] feature.
                            </p>
                        </div>
                        <div class="flex-shrink:0 align-self:center">
                            <button type="button" :disabled="busyStore.isBusy" @click="doCleanUp" v-ripple class="button flex align-items:center gap-x:8">
                                <font-awesome-icon :icon="['fas', 'spinner']" :class="{ hidden: !busyStore.hasTask('migrations.index:cleanup-fonts') }" class="animation:rotate|1.2s|infinite|linear fill:current" />
                                {{ __('Run', 'yabe-webfont') }}
                            </button>
                        </div>
                    </div>
                </li>
            </ul>
            <div class="mt:24 r:8 bg:sky-60/.10 p:16">
                <div class="flex">
                    <div class="flex-shrink:0">
                        <font-awesome-icon :icon="['fas', 'circle-info']" class="font:20 fg:blue-40" />
                    </div>
                    <div class="ml:12 flex-grow:1 flex justify-content:between">
                        <p class="m:0 font:14 fg:blue-70">
                            Please make a backup of your database before running the migration.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>