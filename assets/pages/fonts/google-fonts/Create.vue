<template>
    <span class="tw-mr-2 tw-text-2xl">» {{ __('Import Google Fonts', 'yabe-webfont') }} </span>

    <div id="poststuff">
        <form id="post-body" @submit="sendForm" class="metabox-holder columns-2">
            <div id="post-body-content">
                <div id="titlediv">
                    <div id="titlewrap">
                        <label class="screen-reader-text" for="title">Add title</label>
                        <input type="text" name="title" id="title" size="30" required v-model="title" :placeholder="__('Title', 'yabe-webfont')" autocomplete="off">
                    </div>
                    <div class="inside">
                    </div>
                </div><!-- /titlediv -->
                <div class="postarea wp-editor-expand">
                    <div id="wp-content-wrap" class="wp-core-ui tw-mt-8">
                        <h3>Meta</h3>
                        <div class="tw-grid tw-grid-cols-12 tw-gap-4">
                            <div class="tw-col-span-4 tw-flex tw-flex-col tw-gap-1.5">
                                <label class="tw-text-sm tw-font-semibold">Font Family</label>
                                <TheSearchFamily :catalog="catalog" v-model="fontData"></TheSearchFamily>
                            </div>

                            <div class="tw-col-span-2 tw-flex tw-flex-col tw-gap-1.5">
                                <label for="display" class="tw-text-sm tw-font-semibold">Font Display</label>
                                <select name="display" id="display" v-model="display" class="tw-capitalize [&_option]:tw-capitalize">
                                    <option value="auto">auto</option>
                                    <option value="block">block</option>
                                    <option value="swap">swap</option>
                                    <option value="fallback">fallback</option>
                                    <option value="optional">optional</option>
                                </select>
                            </div>
                            <div class="tw-col-span-4 tw-flex tw-flex-col tw-gap-1.5">
                                <label for="selector" class="tw-text-sm tw-font-semibold">CSS Selector</label>
                                <input type="text" name="selector" id="selector" v-model="selector" placeholder="h1, h2, .poetry, .haiku, p, span, #lyric, #description" autocomplete="off">
                            </div>
                            <div class="tw-col-span-2 tw-flex tw-flex-col tw-gap-1.5">
                                <span class="tw-text-sm tw-font-semibold">Preload</span>
                                <div class="tw-flex-1 tw-flex tw-items-center">
                                    <label for="preload" class="tw-text-sm">
                                        <input type="checkbox" name="preload" id="preload" v-model="preload">
                                        Preload files
                                    </label>
                                </div>
                            </div>

                            <!-- TODO: 2nd row -->
                            <div class="tw-col-span-7 tw-flex tw-flex-col tw-gap-1.5">
                                <span class="tw-text-sm tw-font-semibold">Subsets</span>
                                <VueSelect multiple :options="fontData?.subsets.filter((item) => !subsets.includes(item))" v-model="subsets" :searchable="false" placeholder="Choose subsets">
                                    <template v-slot:no-options="{ search, searching }">
                                        <template v-if="!subsets.length">
                                            Please choose a font family first.
                                        </template>
                                    </template>
                                </VueSelect>
                            </div>
                            <div class="tw-col-span-3 tw-flex tw-flex-col tw-gap-1.5">
                                <span class="tw-text-sm tw-font-semibold">File Format</span>
                                <VueSelect multiple :options="variable ? ['woff2'] : ['woff2', 'woff', 'ttf']" v-model="format" :searchable="false" placeholder="Choose formats"></VueSelect>
                            </div>

                            <div class="tw-col-span-2 tw-flex tw-flex-col tw-gap-1.5">
                                <span class="tw-text-sm tw-font-semibold">Variable Fonts</span>
                                <div class="tw-flex-1 tw-flex tw-items-center">
                                    <label for="variable" class="tw-text-sm">
                                        <input :disabled="!fontData?.isSupportVariable" type="checkbox" name="variable" id="variable" v-model="variable">
                                        Yes
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="tw-flex tw-items-center tw-space-x-2 tw-mt-8 tw-mb-3">
                            <h3 class="tw-flex-1">Variants</h3>

                            <div class="tw-flex tw-items-center tw-space-x-2 tw-border tw-border-solid tw-py-2 tw-px-2 !tw-border-gray-300">

                                <div class="tw-font-semibold tw-text-base">Preview :</div>

                                <template v-if="variable">
                                    <div v-if="preview.width.current !== 0" class="tw-h-fit tw-flex tw-rounded-md tw-shadow-sm">
                                        <span class="tw-inline-flex tw-items-center tw-rounded-l-md tw-border tw-border-solid !tw-border-r-0 !tw-border-gray-300 tw-bg-gray-50 tw-px-3 tw-text-gray-500 !tw-text-xs">width</span>
                                        <input type="number" v-model="preview.width.current" :min="preview.width.min" :max="preview.width.max" class="!tw-block !tw-min-w-0 tw-w-16 !tw-min-h-0 !tw-h-6 !tw-mx-0 !tw-py-0 !tw-px-2 !tw-border !tw-border-solid !tw-rounded-none !tw-rounded-r-md !tw-border-gray-300 !tw-text-xs" />
                                        <input type="range" v-model="preview.width.current" :min="preview.width.min" :max="preview.width.max" class="tw-w-16 !appearance-none !tw-accent-[#0050FF] !tw-h-1 tw-self-center" />
                                    </div>

                                    <div v-if="preview.weight.current !== 0" class="tw-h-fit tw-flex tw-rounded-md tw-shadow-sm">
                                        <span class="tw-inline-flex tw-items-center tw-rounded-l-md tw-border tw-border-solid !tw-border-r-0 !tw-border-gray-300 tw-bg-gray-50 tw-px-3 tw-text-gray-500 !tw-text-xs">weight</span>
                                        <input type="number" v-model="preview.weight.current" :min="preview.weight.min" :max="preview.weight.max" class="!tw-block !tw-min-w-0 tw-w-16 !tw-min-h-0 !tw-h-6 !tw-mx-0 !tw-py-0 !tw-px-2 !tw-border !tw-border-solid !tw-rounded-none !tw-rounded-r-md !tw-border-gray-300 !tw-text-xs" />
                                        <input type="range" v-model="preview.weight.current" :min="preview.weight.min" :max="preview.weight.max" class="tw-w-16 !appearance-none !tw-accent-[#0050FF] !tw-h-1 tw-self-center" />
                                    </div>
                                </template>

                                <div class="tw-h-fit tw-flex tw-rounded-md tw-shadow-sm">
                                    <span class="tw-inline-flex tw-items-center tw-rounded-l-md tw-border tw-border-solid !tw-border-r-0 !tw-border-gray-300 tw-bg-gray-50 tw-px-3 tw-text-gray-500 !tw-text-xs">size</span>
                                    <div class="tw-h-fit tw-flex tw-items-stretch tw-relative tw-rounded-md tw-shadow-sm">
                                        <input type="number" v-model="preview.fontSize" class="!tw-block !tw-min-w-0 tw-w-16 !tw-min-h-0 !tw-h-6 !tw-mx-0 !tw-py-0 !tw-px-2 !tw-border !tw-border-solid !tw-rounded-none !tw-rounded-r-md !tw-border-gray-300 !tw-text-xs" />
                                        <div class="tw-absolute tw-inset-y-0 tw-right-0 tw-flex tw-py-1 tw-pr-1.5">
                                            <kbd class="tw-inline-flex tw-items-center tw-rounded tw-border tw-border-gray-200 tw-px-1 tw-text-gray-500">px</kbd>
                                        </div>
                                    </div>
                                    <input type="range" v-model="preview.fontSize" max="200" class="tw-w-16 !appearance-none !tw-accent-[#0050FF] !tw-h-1 tw-self-center" />

                                </div>

                            </div>
                        </div>

                        <div class="font-files">
                            <div class="tw-grid tw-gap-4">
                                <template v-for="fontFace in fontFaces">
                                    <TheFontFace :item="fontFace" :preview="preview" />
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- /post-body-content -->
            <div id="postbox-container-1" class="postbox-container">
                <div>
                    <div id="submitdiv" class="postbox ">
                        <div class="postbox-header">
                            <h2 class="">Publish</h2>
                        </div>
                        <div class="inside">
                            <div class="submitbox" id="submitpost">

                                <div id="minor-publishing">
                                    <div id="misc-publishing-actions">
                                        <div class="misc-pub-section">
                                            <!-- Font Info: Publish Status -->
                                            <div class="tw-py-1.5 tw-flex tw-items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="tw-h-4 tw-w-4 shrink-0 tw-text-[#8c8f94] tw-fill-current"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                                    <path v-if="status" d="M117.8 128H207C286.9-3.7 409.5-8.5 483.9 5.3c11.6 2.2 20.7 11.2 22.8 22.8c13.8 74.4 9 197-122.7 276.9v89.3c0 25.4-13.4 49-35.3 61.9l-88.5 52.5c-7.4 4.4-16.6 4.5-24.1 .2s-12.1-12.2-12.1-20.9l0-114.7c0-22.6-9-44.3-25-60.3s-37.7-25-60.3-25H24c-8.6 0-16.6-4.6-20.9-12.1s-4.2-16.7 .2-24.1l52.5-88.5c13-21.9 36.5-35.3 61.9-35.3zM424 128c0-22.1-17.9-40-40-40s-40 17.9-40 40s17.9 40 40 40s40-17.9 40-40zM166.5 470C117 519.5 .5 511.5 .5 511.5s-8-116.5 41.5-166c34.4-34.4 90.1-34.4 124.5 0s34.4 90.1 0 124.5zm-46.7-36.4c11.4-11.4 11.4-30 0-41.4s-30-11.4-41.4 0c-16.5 16.5-13.8 55.2-13.8 55.2s38.7 2.7 55.2-13.8z" />
                                                    <path v-else d="M156.6 384.9L125.7 354c-8.5-8.5-11.5-20.8-7.7-32.2c3-8.9 7-20.5 11.8-33.8L24 288c-8.6 0-16.6-4.6-20.9-12.1s-4.2-16.7 .2-24.1l52.5-88.5c13-21.9 36.5-35.3 61.9-35.3l82.3 0c2.4-4 4.8-7.7 7.2-11.3C289.1-4.1 411.1-8.1 483.9 5.3c11.6 2.1 20.6 11.2 22.8 22.8c13.4 72.9 9.3 194.8-111.4 276.7c-3.5 2.4-7.3 4.8-11.3 7.2v82.3c0 25.4-13.4 49-35.3 61.9l-88.5 52.5c-7.4 4.4-16.6 4.5-24.1 .2s-12.1-12.2-12.1-20.9V380.8c-14.1 4.9-26.4 8.9-35.7 11.9c-11.2 3.6-23.4 .5-31.8-7.8zM384 168c22.1 0 40-17.9 40-40s-17.9-40-40-40s-40 17.9-40 40s17.9 40 40 40z" />
                                                </svg>

                                                <span class="tw-pl-2.5 tw-pr-2">Status:</span>
                                                <SwitchGroup as="div" :class="{ 'tw-opacity-50': !status }" class="tw-flex tw-items-center">
                                                    <!-- Font Info: Publish Status -->
                                                    <Switch v-model="status" :class="[status ? 'tw-bg-sky-600' : 'tw-bg-gray-200']" class="tw-relative tw-inline-flex tw-p-0 tw-h-6 tw-w-11 tw-flex-shrink-0 tw-cursor-pointer tw-rounded-full tw-border-2 tw-border-transparent tw-transition-colors tw-duration-200 tw-ease-in-out focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-sky-500 focus:tw-ring-offset-2">
                                                        <span :class="[status ? 'tw-translate-x-5' : 'tw-translate-x-0']" class="tw-pointer-events-none tw-relative tw-inline-block tw-h-5 tw-w-5 tw-transform tw-rounded-full tw-bg-white tw-shadow tw-ring-0 tw-transition tw-duration-200 tw-ease-in-out">
                                                            <span aria-hidden="true" :class="[status ? 'tw-opacity-0 tw-ease-out tw-duration-100' : 'tw-opacity-100 tw-ease-in tw-duration-200']" class="tw-absolute tw-inset-0 tw-flex tw-h-full tw-w-full tw-items-center tw-justify-center tw-transition-opacity">
                                                                <svg class="tw-h-3 tw-w-3 tw-text-gray-400" fill="none" viewBox="0 0 12 12">
                                                                    <path d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                                </svg>
                                                            </span>
                                                            <span aria-hidden="true" :class="[status ? 'tw-opacity-100 tw-ease-in tw-duration-200' : 'tw-opacity-0 tw-ease-out tw-duration-100']" class="tw-absolute tw-inset-0 tw-flex tw-h-full tw-w-full tw-items-center tw-justify-center tw-transition-opacity">
                                                                <svg class="tw-h-3 tw-w-3 tw-text-sky-600" fill="currentColor" viewBox="0 0 12 12">
                                                                    <path d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z" />
                                                                </svg>
                                                            </span>
                                                        </span>
                                                    </Switch>
                                                    <SwitchLabel as="span" :class="[status ? 'tw-text-black' : 'tw-text-gray-500']" class="tw-ml-2 tw-font-medium tw-cursor-pointer">
                                                        {{ status? 'published': 'draft' }}
                                                    </SwitchLabel>
                                                </SwitchGroup>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                </div>

                                <div id="major-publishing-actions">
                                    <div id="publishing-action">
                                        <button type="submit" name="save" id="save" :disabled="busy.isBusy" class="button button-primary button-large" value="save">Save</button>
                                    </div>
                                    <div class="clear"></div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <div id="submitdiv" class="postbox ">
                        <div class="postbox-header">
                            <h2 class="">Font Information</h2>
                        </div>
                        <div class="inside">
                            <div class="submitbox" id="submitpost">

                                <div id="minor-publishing">
                                    <div id="misc-publishing-actions" class="tw-py-1.5">
                                        <div class="misc-pub-section">
                                            <!-- Font Info: Rank -->
                                            <div class="tw-py-1.5 tw-flex tw-items-center">
                                                <svg class="tw-h-4 tw-w-4 shrink-0 tw-text-[#8c8f94] tw-fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                                    <path d="M353.8 54.1L330.2 6.3c-3.9-8.3-16.1-8.6-20.4 0L286.2 54.1l-52.3 7.5c-9.3 1.4-13.3 12.9-6.4 19.8l38 37-9 52.1c-1.4 9.3 8.2 16.5 16.8 12.2l46.9-24.8 46.6 24.4c8.6 4.3 18.3-2.9 16.8-12.2l-9-52.1 38-36.6c6.8-6.8 2.9-18.3-6.4-19.8l-52.3-7.5zM256 256c-17.7 0-32 14.3-32 32V480c0 17.7 14.3 32 32 32H384c17.7 0 32-14.3 32-32V288c0-17.7-14.3-32-32-32H256zM32 320c-17.7 0-32 14.3-32 32V480c0 17.7 14.3 32 32 32H160c17.7 0 32-14.3 32-32V352c0-17.7-14.3-32-32-32H32zm416 96v64c0 17.7 14.3 32 32 32H608c17.7 0 32-14.3 32-32V416c0-17.7-14.3-32-32-32H480c-17.7 0-32 14.3-32 32z" />
                                                </svg>
                                                <span class="tw-pl-2.5 tw-pr-2">Rank:</span>
                                                <span class="tw-font-medium">{{ fontData?.popularity }}</span>
                                            </div>

                                            <!-- Font Info: Category -->
                                            <div class="tw-py-1.5 tw-flex tw-items-center">
                                                <svg class="tw-h-4 tw-w-4 shrink-0 tw-text-[#8c8f94] tw-fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                                    <path d="M254 52.8C249.3 40.3 237.3 32 224 32s-25.3 8.3-30 20.8L57.8 416H32c-17.7 0-32 14.3-32 32s14.3 32 32 32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32h-1.8l18-48H303.8l18 48H320c-17.7 0-32 14.3-32 32s14.3 32 32 32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H390.2L254 52.8zM279.8 304H168.2L224 155.1 279.8 304z" />
                                                </svg>
                                                <span class="tw-pl-2.5 tw-pr-2">Category:</span>
                                                <span class="tw-font-medium">{{ fontData?.category }}</span>
                                            </div>

                                            <!-- Font Info: Version -->
                                            <div class="tw-py-1.5 tw-flex tw-items-center">
                                                <svg class="tw-h-4 tw-w-4 shrink-0 tw-text-[#8c8f94] tw-fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                                    <path d="M305.8 2.1C314.4 5.9 320 14.5 320 24V64h16c70.7 0 128 57.3 128 128V358.7c28.3 12.3 48 40.5 48 73.3c0 44.2-35.8 80-80 80s-80-35.8-80-80c0-32.8 19.7-61 48-73.3V192c0-35.3-28.7-64-64-64H320v40c0 9.5-5.6 18.1-14.2 21.9s-18.8 2.3-25.8-4.1l-80-72c-5.1-4.6-7.9-11-7.9-17.8s2.9-13.3 7.9-17.8l80-72c7-6.3 17.2-7.9 25.8-4.1zM104 80c0-13.3-10.7-24-24-24S56 66.7 56 80s10.7 24 24 24s24-10.7 24-24zm8 73.3V358.7c28.3 12.3 48 40.5 48 73.3c0 44.2-35.8 80-80 80s-80-35.8-80-80c0-32.8 19.7-61 48-73.3V153.3C19.7 141 0 112.8 0 80C0 35.8 35.8 0 80 0s80 35.8 80 80c0 32.8-19.7 61-48 73.3zM104 432c0-13.3-10.7-24-24-24s-24 10.7-24 24s10.7 24 24 24s24-10.7 24-24zm328 24c13.3 0 24-10.7 24-24s-10.7-24-24-24s-24 10.7-24 24s10.7 24 24 24z" />
                                                </svg>
                                                <span class="tw-pl-2.5 tw-pr-2">Version:</span>
                                                <span class="tw-font-medium">{{ fontData?.version }}</span>
                                            </div>

                                            <!-- Font Info: Last Modified -->
                                            <div class="tw-py-1.5 tw-flex tw-items-center">
                                                <svg class="tw-h-4 tw-w-4 shrink-0 tw-text-[#8c8f94] tw-fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                                    <path d="M128 0c17.7 0 32 14.3 32 32V64H288V32c0-17.7 14.3-32 32-32s32 14.3 32 32V64h48c26.5 0 48 21.5 48 48v48H0V112C0 85.5 21.5 64 48 64H96V32c0-17.7 14.3-32 32-32zM0 192H448V464c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V192zm308.8 75c-14.7-14.7-38.6-14.7-53.3 0L240.6 282l53.3 53.3 14.9-14.9c14.7-14.7 14.7-38.6 0-53.3zM146.1 376.5c-4.1 4.1-7 9.2-8.4 14.9L128.5 428c-1.4 5.5 .2 11.2 4.2 15.2s9.7 5.6 15.2 4.2l36.6-9.2c5.6-1.4 10.8-4.3 14.9-8.4l71.9-71.9L218 304.6l-71.9 71.9z" />
                                                </svg>
                                                <span class="tw-pl-2.5 tw-pr-2">Last modified:</span>
                                                <span v-if="fontData" :title="fontData?.modifiedAt" class="tw-font-medium">{{ ago(new Date(fontData?.modifiedAt)) }}</span>
                                            </div>

                                            <!-- Font Info: Style -->
                                            <div class="tw-py-1.5 tw-flex tw-items-center">
                                                <svg class="tw-h-4 tw-w-4 shrink-0 tw-text-[#8c8f94] tw-fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                                    <path d="M371.3 367.1c27.3-3.9 51.9-19.4 67.2-42.9L600.2 74.1c12.6-19.5 9.4-45.3-7.6-61.2S549.7-4.4 531.1 9.6L294.4 187.2c-24 18-38.2 46.1-38.4 76.1L371.3 367.1zm-19.6 25.4l-116-104.4C175.9 290.3 128 339.6 128 400c0 3.9 .2 7.8 .6 11.6c1.8 17.5-10.2 36.4-27.8 36.4H96c-17.7 0-32 14.3-32 32s14.3 32 32 32H240c61.9 0 112-50.1 112-112c0-2.5-.1-5-.2-7.5z" />
                                                </svg>
                                                <span class="tw-pl-2.5 tw-pr-2">Style:</span>
                                                <span v-if="fontData" class="tw-font-medium">
                                                    {{ sprintf(_n('%d subset', '%d subsets', fontData?.subsets.length, 'yabe-webfont'), fontData?.subsets.length) }}
                                                    & {{ sprintf(_n('%d variant', '%d variants', fontData?.variants.length, 'yabe-webfont'), fontData?.variants.length) }}
                                                </span>
                                            </div>

                                            <!-- Font Info: Designers -->
                                            <div class="tw-py-1.5 tw-flex tw-items-center">
                                                <svg class="tw-h-4 tw-w-4 shrink-0 tw-text-[#8c8f94] tw-fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                                    <path d="M192 80c0 8.8 7.2 16 16 16h80c17.7 0 32 14.3 32 32s-14.3 32-32 32H208c-44.2 0-80-35.8-80-80s35.8-80 80-80H320c70.7 0 128 57.3 128 128s-57.3 128-128 128H288c0 17.7-14.3 32-32 32s-32-14.3-32-32V224c0-17.7 14.3-32 32-32h64c35.3 0 64-28.7 64-64s-28.7-64-64-64H208c-8.8 0-16 7.2-16 16zM568.2 336.3c13.1 17.8 9.3 42.8-8.5 55.9L433.1 485.5c-23.4 17.2-51.6 26.5-80.7 26.5H192 32c-17.7 0-32-14.3-32-32V416c0-17.7 14.3-32 32-32H68.8l44.9-36c22.7-18.2 50.9-28 80-28H272h16 64c17.7 0 32 14.3 32 32s-14.3 32-32 32H288 272c-8.8 0-16 7.2-16 16s7.2 16 16 16H392.6l119.7-88.2c17.8-13.1 42.8-9.3 55.9 8.5zM193.6 384l0 0-.9 0c.3 0 .6 0 .9 0z" />
                                                </svg>
                                                <span class="tw-pl-2.5 tw-pr-2">Designers:</span>
                                                <span class="tw-font-medium">{{ fontData?.designers }}</span>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div id="postbox-container-2" class="postbox-container tw-mt-3">
                <Transition name="css-preview">
                    <div v-if="cssPreviewStylesheet" class="tw-mt-4">
                        <h3 class="tw-mt-5">CSS Preview</h3>
                        <highlightjs language="css" :code="cssPreviewStylesheet" />
                    </div>
                </Transition>
            </div>
        </form>
    </div>
</template>

<script setup>
import ago from 's-ago';
import { nanoid } from 'nanoid';
import axios from 'axios';
import { ref, reactive, watch, computed, onBeforeMount, onBeforeUnmount } from 'vue';
import { useRouter } from 'vue-router';
import debounce from 'lodash-es/debounce';
import isEqual from 'lodash-es/isEqual';
import uniq from 'lodash-es/uniq';
import sortBy from 'lodash-es/sortBy';
import { useApi } from '../../../library/api';
import { useBusy } from '../../../stores/busy';
import { useNotifier } from '../../../library/notifier';
import { useWordpressNotice } from '../../../stores/wordpressNotice';

import TheFontFace from '../../../components/fonts/google-fonts/TheFontFace.vue';
import { Switch, SwitchGroup, SwitchLabel } from '@headlessui/vue';
import TheSearchFamily from '../../../components/fonts/google-fonts/TheSearchFamily.vue';

const api = useApi();
const router = useRouter();
const notifier = useNotifier();
const busy = useBusy();
const wordpressNotice = useWordpressNotice();

const catalog = ref([]);
const fontData = ref(null);
const title = ref('');
const display = ref('auto');
const selector = ref('');
const preload = ref(false);
const family = computed(() => fontData.value?.family || '');
const status = ref(true);
const subsets = ref([]);
const format = ref(['woff2']);
const variable = ref(false);
const fontFiles = ref([]);
const fontFaces = ref([]);

const preview = reactive({
    text: ``,
    fontSize: 18,
    lineHeight: 1.5,
    fontFamily: family,
    weight: {
        current: 0,
        min: 0,
        max: 0,
    },
    width: {
        current: 0,
        min: 0,
        max: 0,
    }
});

watch(family, (newFamily, oldFamily) => {
    if (title.value === '' || title.value === oldFamily) {
        title.value = newFamily;
    }
});

watch(fontData, (newValue, oldValue) => {
    if (newValue?.isSupportVariable !== oldValue?.isSupportVariable) {
        variable.value = false;
    }

    if (newValue && Array.isArray(newValue.subsets) && newValue.subsets.length) {
        subsets.value = newValue.subsets.includes('latin') ? ['latin'] : [newValue.subsets[0]];
    } else {
        subsets.value = [];
    }
});

watch(variable, (newValue, oldValue) => {
    format.value = ['woff2'];
    reComputeFontFiles();
});

watch([variable, family], ([newVariable, newFamily], [oldVariable, oldFamily]) => {
    if (newVariable) {
        preview.weight = {
            current: 0,
            min: 0,
            max: 0,
        };
        preview.width = {
            current: 0,
            min: 0,
            max: 0,
        };

        fontData.value?.axes.forEach(axis => {
            if (axis.tag === 'wght') {
                preview.weight.current = axis.defaultValue;
                preview.weight.min = axis.min;
                preview.weight.max = axis.max;
            }

            if (axis.tag === 'wdth') {
                preview.width.current = axis.defaultValue;
                preview.width.min = axis.min;
                preview.width.max = axis.max;
            }
        });
    }
});

watch(subsets, (newValue, oldValue) => {
    if (newValue.length === 0 && Array.isArray(fontData.value?.subsets) && fontData.value?.subsets?.length) {
        subsets.value = fontData.value.subsets.includes('latin') ? ['latin'] : [fontData.value.subsets[0]];
    }
});

watch(format, (newValue, oldValue) => {
    if (newValue.length === 0) {
        format.value = ['woff2'];
    }
});

watch([subsets, family], ([newSubsets, newFamily, newVariable], [oldSubsets, oldFamily, oldVariable]) => {
    if (isEqual(newSubsets.sort(), oldSubsets.sort()) && newFamily === oldFamily && newVariable === oldVariable) {
        return;
    }

    if (newFamily !== '') {
        fetchFontFiles();
    }
});

function reComputeFontFiles() {
    if (variable.value) {
        fontFaces.value = [];

        let wdth = fontData.value.axes.find(a => a.tag === 'wdth');

        if (fontFiles.value.some(file => file.weight === 0 && file.style === 'normal')) {
            fontFaces.value.push({
                id: nanoid(10),
                key: '0',
                weight: 0,
                width: wdth ? `${wdth.min}% ${wdth.max}%` : '',
                style: 'normal',
                isEnabled: true,
                display: '',
                selector: '',
                comment: '',
            });
        }
        if (fontFiles.value.some(file => file.weight === 0 && file.style === 'italic')) {
            fontFaces.value.push({
                id: nanoid(10),
                key: '0i',
                weight: 0,
                width: wdth ? `${wdth.min}% ${wdth.max}%` : '',
                style: 'italic',
                isEnabled: true,
                display: '',
                selector: '',
                comment: '',
            });
        }
    } else {
        fontFaces.value = fontData.value.variants.map(v => {
            let fWeight = Number.parseInt(v);
            let fStyle = 'normal';
            switch (v.replace('regular', '').replace(/[0-9]/g, '').trim()) {
                case 'i':
                    fStyle = 'italic';
                    break;
                case 'o':
                    fStyle = 'oblique';
                    break;
                default:
                    break;
            }

            return {
                id: nanoid(10),
                key: v,
                weight: !isNaN(fWeight) ? fWeight : 400,
                width: '',
                style: fStyle,
                isEnabled: fWeight === 400 || fWeight === 0,
                display: '',
                selector: '',
                comment: '',
            };
        });
    }
}

function fetchFontFiles() {
    busy.add('fonts.create.google-fonts:fetch-font-files');

    axios
        .request({
            method: 'GET',
            url: `${yabeWebfont.hostedWakufont}/api/fonts/${fontData.value.slug}`,
            params: {
                subsets: subsets.value.join(','),
            },
        })
        .then(response => {
            const files = Object.values(response.data.files);

            fontFiles.value = files.map(file => {
                file.uid = nanoid(10);
                return file;
            });

            reComputeFontFiles();
        })
        .catch(error => {
            notifier.alert(error.message);
        })
        .finally(() => {
            busy.remove('fonts.create.google-fonts:fetch-font-files');
        });
}
function fontFormatMap(ext) {
    // https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/src#font_formats
    switch (ext) {
        case 'woff2':
        case 'font/woff2':
            return 'woff2';
        case 'woff':
        case 'font/woff':
            return 'woff';
        case 'ttf':
        case 'font/ttf':
            return 'truetype';
        case 'otf':
        case 'font/otf':
            return 'opentype';
        case 'eot':
        case 'font/eot':
            return 'embedded-opentype';
        default:
            return 'woff2';
    }
};

const cssFontFaceRule = computed(() => {
    let css = ``;

    if (!family.value) {
        return css;
    }

    if (variable.value) {
        fontFaces.value.forEach(fontFace => {
            if (fontFace.weight !== 0) {
                return;
            }

            subsets.value.forEach(subset => {
                let files = fontFiles.value.filter(
                    f => f.weight == fontFace.weight
                        && f.style === fontFace.style
                        && f.subsets.includes(subset)
                        && format.value.includes(f.format)
                );

                files.forEach(file => {
                    if (fontFace.comment) {
                        css += `/* ${fontFace.comment} */\n`;
                    }

                    css += `/* ${subset} */\n`;

                    css += `@font-face {\n`;

                    css += `\tfont-family: '${family.value}';\n`;

                    css += `\tfont-style: ${fontFace.style};\n`;

                    if (fontFace.weight !== 0) {
                        css += `\tfont-weight: ${fontFace.weight};\n`;
                    } else if (preview.weight.min !== 0 || preview.weight.max !== 0) {
                        css += `\tfont-weight: ${preview.weight.min} ${preview.weight.max};\n`;
                    } else {
                        css += `\tfont-weight: 400;\n`;
                    }

                    let wdth = fontData.value.axes.find(a => a.tag === 'wdth');

                    if (wdth) {
                        css += `\tfont-stretch: ${wdth.min}% ${wdth.max}%;\n`;
                    }

                    css += `\tfont-display: ${fontFace.display || display.value};\n`;

                    css += `\tsrc: url('${file.url}') format(${fontFormatMap(file.format)});\n`;

                    if (file.unicodeRange) {
                        css += `\tunicode-range: ${file.unicodeRange};\n`;
                    }

                    css += `}\n\n`;
                });
            });
        });
    } else {
        fontFaces.value.forEach(fontFace => {
            if (fontFace.comment) {
                css += `/* ${fontFace.comment} */\n`;
            }

            css += `@font-face {\n`;

            css += `\tfont-family: '${family.value}';\n`;

            css += `\tfont-style: ${fontFace.style};\n`;

            if (fontFace.weight !== 0) {
                css += `\tfont-weight: ${fontFace.weight};\n`;
            } else {
                css += `\tfont-weight: ${preview.weight.min} ${preview.weight.max};\n`;
            }

            css += `\tfont-display: ${fontFace.display || display.value};\n`;

            const formatPrecedence = {
                'woff2': 1,
                'woff': 2,
                'ttf': 3,
                'otf': 4,
                'eot': 5,
            };

            let files = fontFiles.value.filter(
                f => f.weight == fontFace.weight
                    && f.style === fontFace.style
                    && isEqual(uniq(f.subsets).sort(), uniq(subsets.value).sort())
                    && format.value.includes(f.format)
            );

            if (files.length) {
                files = sortBy(files, (f) => formatPrecedence[f.format]);

                css += `\tsrc: `;

                let fileSrc = files.map(f => {
                    return `url('${f.url}') format(${fontFormatMap(f.format)})`;
                });

                css += fileSrc.join(',\n\t\t');

                css += `;\n`;
            }

            if (fontFace.unicodeRange) {
                css += `\tunicode-range: ${fontFace.unicodeRange};\n`;
            }

            css += `}\n\n`;
        });
    }

    return css;
});

const cssFontFaceRuleFiltered = computed(() => {
    let css = ``;

    if (!family.value) {
        return css;
    }

    if (variable.value) {
        fontFaces.value.forEach(fontFace => {
            if (!fontFace.isEnabled || fontFace.weight !== 0) {
                return;
            }

            subsets.value.forEach(subset => {
                let files = fontFiles.value.filter(
                    f => f.weight == fontFace.weight
                        && f.style === fontFace.style
                        && f.subsets.includes(subset)
                        && format.value.includes(f.format)
                );

                files.forEach(file => {
                    if (fontFace.comment) {
                        css += `/* ${fontFace.comment} */\n`;
                    }

                    css += `/* ${subset} */\n`;

                    css += `@font-face {\n`;

                    css += `\tfont-family: '${family.value}';\n`;

                    css += `\tfont-style: ${fontFace.style};\n`;

                    if (fontFace.weight !== 0) {
                        css += `\tfont-weight: ${fontFace.weight};\n`;
                    } else if (preview.weight.min !== 0 || preview.weight.max !== 0) {
                        css += `\tfont-weight: ${preview.weight.min} ${preview.weight.max};\n`;
                    } else {
                        css += `\tfont-weight: 400;\n`;
                    }

                    let wdth = fontData.value.axes.find(a => a.tag === 'wdth');

                    if (wdth) {
                        css += `\tfont-stretch: ${wdth.min}% ${wdth.max}%;\n`;
                    }

                    css += `\tfont-display: ${fontFace.display || display.value};\n`;

                    css += `\tsrc: url('${file.url}') format(${fontFormatMap(file.format)});\n`;

                    if (file.unicodeRange) {
                        css += `\tunicode-range: ${file.unicodeRange};\n`;
                    }

                    css += `}\n\n`;
                });
            });
        });
    } else {
        fontFaces.value.forEach(fontFace => {
            if (!fontFace.isEnabled) {
                return;
            }

            if (fontFace.comment) {
                css += `/* ${fontFace.comment} */\n`;
            }

            css += `@font-face {\n`;

            css += `\tfont-family: '${family.value}';\n`;

            css += `\tfont-style: ${fontFace.style};\n`;

            if (fontFace.weight !== 0) {
                css += `\tfont-weight: ${fontFace.weight};\n`;
            } else {
                css += `\tfont-weight: ${preview.weight.min} ${preview.weight.max};\n`;
            }

            css += `\tfont-display: ${fontFace.display || display.value};\n`;

            const formatPrecedence = {
                'woff2': 1,
                'woff': 2,
                'ttf': 3,
                'otf': 4,
                'eot': 5,
            };

            let files = fontFiles.value.filter(
                f => f.weight == fontFace.weight
                    && f.style === fontFace.style
                    && isEqual(uniq(f.subsets).sort(), uniq(subsets.value).sort())
                    && format.value.includes(f.format)
            );

            if (files.length) {
                files = sortBy(files, (f) => formatPrecedence[f.format]);

                css += `\tsrc: `;

                let fileSrc = files.map(f => {
                    return `url('${f.url}') format(${fontFormatMap(f.format)})`;
                });

                css += fileSrc.join(',\n\t\t');

                css += `;\n`;
            }

            if (fontFace.unicodeRange) {
                css += `\tunicode-range: ${fontFace.unicodeRange};\n`;
            }

            css += `}\n\n`;
        });
    }

    return css;
});

const cssPreviewStylesheet = computed(() => {
    let css = ``;

    if (!family.value) {
        return css;
    }

    css += `\n`;

    css += cssFontFaceRuleFiltered.value;

    if (selector.value) {
        css += `${selector.value} {\n\tfont-family: '${family.value}';\n}\n\n`;
    }

    fontFaces.value.forEach(fontFace => {
        if (fontFace.selector) {
            css += `${fontFace.selector} {\n`;
            css += `\tfont-family: '${family.value}';\n`;
            css += `\tfont-style: ${fontFace.style};\n`;
            css += `\tfont-weight: ${fontFace.weight};\n`;
            css += `}\n\n`;
        }
    });

    // replace tabs with 2 spaces
    css = css.replace(/\t/g, '  ');

    // replace url with placeholder
    css = css.replace(/url\(\'.*\'\)/g, `url('FONT_FILE_URL')`);

    // replace <family> placeholder
    css = css.replace(/<family>/g, family.value);

    return css;
});

let fontPreviewStylesheetEl;

watch(cssFontFaceRule, debounce((newCss, oldCss) => {
    if (fontPreviewStylesheetEl) {
        // replace tabs with 2 spaces and assign
        fontPreviewStylesheetEl.innerHTML = newCss.replace(/\t/g, '  ');
    }
}, 1000));

function resetForm() {
    fontData.value = null;
    title.value = '';
    display.value = 'auto';
    selector.value = '';
    preload.value = false;
    subsets.value = [];
    format.value = ['woff2'];
    variable.value = false;
    fontFiles.value = [];
    fontFaces.value = [];

    preview.text = `The quick brown fox jumps over a lazy dog`;
    preview.fontSize = 18;
    preview.weight = {
        current: 0,
        min: 0,
        max: 0,
    };
    preview.width = {
        current: 0,
        min: 0,
        max: 0,
    };
}

onBeforeMount(() => {
    busy.add('fonts.create.google-fonts:on-before-mount');

    axios
        .request({
            method: 'GET',
            url: `${yabeWebfont.hostedWakufont}/api/fonts`,
        })
        .then(response => {
            catalog.value = response.data.fonts;
            catalog.value.forEach(font => {
                font.subsets = font.subsets.filter(subset => subset !== 'menu');
            });
        })
        .catch(error => {
            notifier.alert(error.message);
        })
        .finally(() => {
            busy.remove('fonts.create.google-fonts:on-before-mount');
        });


    resetForm();

    fontPreviewStylesheetEl = document.querySelector('#yabe-webfont-preview');
    if (!fontPreviewStylesheetEl) {
        fontPreviewStylesheetEl = document.createElement('style');
        fontPreviewStylesheetEl.setAttribute('id', 'yabe-webfont-preview');
        document.head.appendChild(fontPreviewStylesheetEl);
    }
});

onBeforeUnmount(() => {
    if (fontPreviewStylesheetEl) {
        document.head.removeChild(fontPreviewStylesheetEl);
    }
});

function sendForm(e) {
    e.preventDefault();

    busy.add('fonts.create.google-fonts:send-form');

    let promise = api
        .request({
            method: 'POST',
            url: '/fonts/google-fonts/store',
            data: {
                title: title.value,
                status: status.value,
                metadata: {
                    preload: preload.value,
                    selector: selector.value,
                    display: display.value,
                    google_fonts: {
                        variable: variable.value,
                        formats: format.value,
                        font_data: fontData.value,
                        subsets: subsets.value,
                        font_files: fontFiles.value,
                        font_faces: fontFaces.value,
                    }
                }
            },
        })
        .then(response => {
            const editUrl = router.resolve({
                name: 'fonts.edit.google-fonts',
                params: {
                    id: response.data.id,
                },
            }).href;

            wordpressNotice.add({
                type: 'success',
                message: `<p>Google Fonts imported successfully. <a href="${editUrl}">Edit Font</a></p>`,
            });

            resetForm();
        })
        .finally(() => {
            busy.remove('fonts.create.google-fonts:send-form');
        });

    notifier.async(
        promise,
        'Google Fonts imported successfully.',
        undefined,
        'Importing Google Fonts...'
    );
}
</script>

<style scoped>
/* Transition for the CSS Preview syntax highlight */
.css-preview-enter-active,
.css-preview-leave-active {
    transition: opacity 0.5s ease;
}

.css-preview-enter-from,
.css-preview-leave-to {
    opacity: 0;
}
</style>