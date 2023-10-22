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
    <span class="mr-2 text-2xl">» {{ __('Custom Fonts [Bricks]', 'yabe-webfont') }} </span>

    <div class="mt-4 rounded group relative bg-white p-6">
        <div class="mx-auto max-w-lg">
            <h2 class="m-0 text-base font-semibold leading-6 text-gray-900">
                Custom Fonts [Bricks] → Yabe Webfont
            </h2>
            <p class="m-0 mt-1 text-sm text-gray-500">
                Migrate your custom fonts from Bricks to Yabe Webfont. Manage your fonts in one place, and use them across visual builders.
            </p>
            <ul role="list" class="m-0 mt-6 [&>li]:border-solid [&>li]:border-0 divide-y divide-gray-200 border-0 border-b border-t border-solid border-gray-200">
                <li class="m-0">
                    <div class="group relative flex items-start space-x-3 py-4">
                        <div class="flex-shrink-0">
                            <span :class="['bg-blue-500', 'inline-flex h-10 w-10 items-center justify-center rounded-lg']">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white fill-current" aria-hidden="true" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                    <path d="M448 464H192c-8.8 0-16-7.2-16-16V368H128v80c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V154.5c0-17-6.7-33.3-18.7-45.3L402.7 18.7C390.7 6.7 374.5 0 357.5 0H192c-35.3 0-64 28.7-64 64V256h48V64c0-8.8 7.2-16 16-16H352v80c0 17.7 14.3 32 32 32h80V448c0 8.8-7.2 16-16 16zM297 215c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9l39 39H24c-13.3 0-24 10.7-24 24s10.7 24 24 24H302.1l-39 39c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l80-80c9.4-9.4 9.4-24.6 0-33.9l-80-80z" />
                                </svg>
                            </span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="m-0 text-sm font-medium text-gray-900">
                                Import fonts
                            </p>
                            <p class="m-0 text-sm text-gray-500">
                                Import the fonts data from the Custom Fonts [Bricks] feature.
                            </p>
                        </div>
                        <div class="flex-shrink-0 self-center">
                            <button type="button" :disabled="busyStore.isBusy" @click="doImport" v-ripple class="button flex items-center gap-x-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" :class="{ hidden: !busyStore.hasTask('migrations.index:import-fonts') }" class="animate-spin fill-current" height="1em" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                    <path d="M288 32a32 32 0 1 0 -64 0 32 32 0 1 0 64 0zm0 448a32 32 0 1 0 -64 0 32 32 0 1 0 64 0zM448 256a32 32 0 1 0 64 0 32 32 0 1 0 -64 0zM32 288a32 32 0 1 0 0-64 32 32 0 1 0 0 64zM75 437a32 32 0 1 0 45.3-45.3A32 32 0 1 0 75 437zm316.8 0A32 32 0 1 0 437 391.8 32 32 0 1 0 391.8 437zM75 75a32 32 0 1 0 45.3 45.3A32 32 0 1 0 75 75z" />
                                </svg>
                                {{ __('Run', 'yabe-ukiyo') }}
                            </button>
                        </div>
                    </div>
                </li>
                <li class="m-0">
                    <div class="group relative flex items-start space-x-3 py-4">
                        <div class="flex-shrink-0">
                            <span :class="['bg-pink-500', 'inline-flex h-10 w-10 items-center justify-center rounded-lg']">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white fill-current" aria-hidden="true" viewBox="0 0 576 512"><!--! Font Awesome Pro 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                    <path d="M569 41c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0L335 207l-39-39c-5.1-5.1-12.1-8-19.4-8c-12.2 0-23 8.1-26.3 19.9l-15.9 55.6-.6-.1c-46.4-7.1-93.5 8.3-126.7 41.5L97 287c-18.4 18.4-29.7 42.4-32.4 68c-.4 3.8-.6 7.7-.6 11.6c0 9.6 7.8 17.3 17.3 17.3H104L81.3 404.9 57.1 427.3 7.2 473.4C2.6 477.6 0 483.5 0 489.7C0 502 10 512 22.3 512l172.7 0c39.1 0 76.6-15.5 104.2-43.2c33.2-33.2 48.6-80.2 41.5-126.7l-.1-.6 55.6-15.9c11.8-3.4 19.9-14.1 19.9-26.3c0-7.3-2.9-14.2-8-19.4l-39-39L569 41zM278.8 254.8l8-28.1 62.4 62.4-28.1 8-42.3-42.3zm-52.3 28l9.8 1.5 55.4 55.4 1.5 9.8c4.8 31.3-5.6 63-28 85.4c-18.6 18.6-43.9 29.1-70.3 29.1L88.1 464l48.5-44.7c14.5-13.4 19.3-34.3 12.1-52.7c-5.4-13.7-16.6-23.9-30.1-28.3c3.1-6.4 7.3-12.2 12.4-17.3l10.2-10.2c22.4-22.4 54.1-32.8 85.4-28z" />
                                </svg>
                            </span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="m-0 text-sm font-medium text-yellow-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="fill-current" height="1em" viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                    <path d="M256 32c14.2 0 27.3 7.5 34.5 19.8l216 368c7.3 12.4 7.3 27.7 .2 40.1S486.3 480 472 480H40c-14.3 0-27.6-7.7-34.7-20.1s-7-27.8 .2-40.1l216-368C228.7 39.5 241.8 32 256 32zm0 128c-13.3 0-24 10.7-24 24V296c0 13.3 10.7 24 24 24s24-10.7 24-24V184c0-13.3-10.7-24-24-24zm32 224a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z" />
                                </svg>
                                Clean up
                            </p>
                            <p class="m-0 text-sm text-gray-500">
                                Remove the fonts data from the Custom Fonts [Bricks] feature.
                            </p>
                        </div>
                        <div class="flex-shrink-0 self-center">
                            <button type="button" :disabled="busyStore.isBusy" @click="doCleanUp" v-ripple class="button flex items-center gap-x-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" :class="{ hidden: !busyStore.hasTask('migrations.index:cleanup-fonts') }" class="animate-spin fill-current" height="1em" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                    <path d="M288 32a32 32 0 1 0 -64 0 32 32 0 1 0 64 0zm0 448a32 32 0 1 0 -64 0 32 32 0 1 0 64 0zM448 256a32 32 0 1 0 64 0 32 32 0 1 0 -64 0zM32 288a32 32 0 1 0 0-64 32 32 0 1 0 0 64zM75 437a32 32 0 1 0 45.3-45.3A32 32 0 1 0 75 437zm316.8 0A32 32 0 1 0 437 391.8 32 32 0 1 0 391.8 437zM75 75a32 32 0 1 0 45.3 45.3A32 32 0 1 0 75 75z" />
                                </svg>
                                {{ __('Run', 'yabe-ukiyo') }}
                            </button>
                        </div>
                    </div>
                </li>
            </ul>
            <div class="mt-6 rounded-md bg-blue-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400 fill-current" aria-hidden="true" viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                            <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM216 336h24V272H216c-13.3 0-24-10.7-24-24s10.7-24 24-24h48c13.3 0 24 10.7 24 24v88h8c13.3 0 24 10.7 24 24s-10.7 24-24 24H216c-13.3 0-24-10.7-24-24s10.7-24 24-24zm40-208a32 32 0 1 1 0 64 32 32 0 1 1 0-64z" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1 md:flex md:justify-between">
                        <p class="m-0 text-sm text-blue-700">
                            Please make a backup of your database before running the migration.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>