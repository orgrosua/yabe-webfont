<template>
    <div>
        <div id="submitdiv" class="postbox">
            <div class="postbox-header">
                <h2 class="">
                    Font Information
                    <a v-if="fontData" :href="`https://fonts.google.com/specimen/${fontData.slug.replaceAll('-', '+')}`" target="_blank">
                        <svg class="w-[15px] h-[15px]" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" viewBox="0 0 100 100">
                            <path fill="currentColor" d="M18.8,85.1h56l0,0c2.2,0,4-1.8,4-4v-32h-8v28h-48v-48h28v-8h-32l0,0c-2.2,0-4,1.8-4,4v56C14.8,83.3,16.6,85.1,18.8,85.1z"></path>
                            <polygon fill="currentColor" points="45.7,48.7 51.3,54.3 77.2,28.5 77.2,37.2 85.2,37.2 85.2,14.9 62.8,14.9 62.8,22.9 71.5,22.9"></polygon>
                        </svg>
                    </a>
                </h2>
            </div>
            <div class="inside">
                <div class="submitbox" id="submitpost">

                    <div id="minor-publishing">
                        <div id="misc-publishing-actions" class="py-1.5">
                            <div class="misc-pub-section">
                                <!-- Font Info: Rank -->
                                <div class="py-1.5 flex items-center">
                                    <svg class="h-4 w-4 shrink-0 text-[#8c8f94] fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                        <path d="M353.8 54.1L330.2 6.3c-3.9-8.3-16.1-8.6-20.4 0L286.2 54.1l-52.3 7.5c-9.3 1.4-13.3 12.9-6.4 19.8l38 37-9 52.1c-1.4 9.3 8.2 16.5 16.8 12.2l46.9-24.8 46.6 24.4c8.6 4.3 18.3-2.9 16.8-12.2l-9-52.1 38-36.6c6.8-6.8 2.9-18.3-6.4-19.8l-52.3-7.5zM256 256c-17.7 0-32 14.3-32 32V480c0 17.7 14.3 32 32 32H384c17.7 0 32-14.3 32-32V288c0-17.7-14.3-32-32-32H256zM32 320c-17.7 0-32 14.3-32 32V480c0 17.7 14.3 32 32 32H160c17.7 0 32-14.3 32-32V352c0-17.7-14.3-32-32-32H32zm416 96v64c0 17.7 14.3 32 32 32H608c17.7 0 32-14.3 32-32V416c0-17.7-14.3-32-32-32H480c-17.7 0-32 14.3-32 32z" />
                                    </svg>
                                    <span class="pl-2.5 pr-2">Rank:</span>
                                    <span class="font-medium">{{ fontData?.popularity }}</span>
                                </div>

                                <!-- Font Info: Category -->
                                <div class="py-1.5 flex items-center">
                                    <svg class="h-4 w-4 shrink-0 text-[#8c8f94] fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                        <path d="M254 52.8C249.3 40.3 237.3 32 224 32s-25.3 8.3-30 20.8L57.8 416H32c-17.7 0-32 14.3-32 32s14.3 32 32 32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32h-1.8l18-48H303.8l18 48H320c-17.7 0-32 14.3-32 32s14.3 32 32 32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H390.2L254 52.8zM279.8 304H168.2L224 155.1 279.8 304z" />
                                    </svg>
                                    <span class="pl-2.5 pr-2">Category:</span>
                                    <span class="font-medium">{{ fontData?.category }}</span>
                                </div>

                                <!-- Font Info: Version -->
                                <div class="py-1.5 flex items-center">
                                    <svg class="h-4 w-4 shrink-0 text-[#8c8f94] fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                        <path d="M305.8 2.1C314.4 5.9 320 14.5 320 24V64h16c70.7 0 128 57.3 128 128V358.7c28.3 12.3 48 40.5 48 73.3c0 44.2-35.8 80-80 80s-80-35.8-80-80c0-32.8 19.7-61 48-73.3V192c0-35.3-28.7-64-64-64H320v40c0 9.5-5.6 18.1-14.2 21.9s-18.8 2.3-25.8-4.1l-80-72c-5.1-4.6-7.9-11-7.9-17.8s2.9-13.3 7.9-17.8l80-72c7-6.3 17.2-7.9 25.8-4.1zM104 80c0-13.3-10.7-24-24-24S56 66.7 56 80s10.7 24 24 24s24-10.7 24-24zm8 73.3V358.7c28.3 12.3 48 40.5 48 73.3c0 44.2-35.8 80-80 80s-80-35.8-80-80c0-32.8 19.7-61 48-73.3V153.3C19.7 141 0 112.8 0 80C0 35.8 35.8 0 80 0s80 35.8 80 80c0 32.8-19.7 61-48 73.3zM104 432c0-13.3-10.7-24-24-24s-24 10.7-24 24s10.7 24 24 24s24-10.7 24-24zm328 24c13.3 0 24-10.7 24-24s-10.7-24-24-24s-24 10.7-24 24s10.7 24 24 24z" />
                                    </svg>
                                    <span class="pl-2.5 pr-2">Version:</span>
                                    <span class="font-medium">{{ fontData?.version }}</span>
                                </div>

                                <!-- Font Info: Last Modified -->
                                <div class="py-1.5 flex items-center">
                                    <svg class="h-4 w-4 shrink-0 text-[#8c8f94] fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                        <path d="M128 0c17.7 0 32 14.3 32 32V64H288V32c0-17.7 14.3-32 32-32s32 14.3 32 32V64h48c26.5 0 48 21.5 48 48v48H0V112C0 85.5 21.5 64 48 64H96V32c0-17.7 14.3-32 32-32zM0 192H448V464c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V192zm308.8 75c-14.7-14.7-38.6-14.7-53.3 0L240.6 282l53.3 53.3 14.9-14.9c14.7-14.7 14.7-38.6 0-53.3zM146.1 376.5c-4.1 4.1-7 9.2-8.4 14.9L128.5 428c-1.4 5.5 .2 11.2 4.2 15.2s9.7 5.6 15.2 4.2l36.6-9.2c5.6-1.4 10.8-4.3 14.9-8.4l71.9-71.9L218 304.6l-71.9 71.9z" />
                                    </svg>
                                    <span class="pl-2.5 pr-2">Last modified:</span>
                                    <span v-if="fontData" :title="fontData?.modifiedAt" class="font-medium">{{ ago(new Date(fontData?.modifiedAt)) }}</span>
                                </div>

                                <!-- Font Info: Style -->
                                <div class="py-1.5 flex items-center">
                                    <svg class="h-4 w-4 shrink-0 text-[#8c8f94] fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                        <path d="M371.3 367.1c27.3-3.9 51.9-19.4 67.2-42.9L600.2 74.1c12.6-19.5 9.4-45.3-7.6-61.2S549.7-4.4 531.1 9.6L294.4 187.2c-24 18-38.2 46.1-38.4 76.1L371.3 367.1zm-19.6 25.4l-116-104.4C175.9 290.3 128 339.6 128 400c0 3.9 .2 7.8 .6 11.6c1.8 17.5-10.2 36.4-27.8 36.4H96c-17.7 0-32 14.3-32 32s14.3 32 32 32H240c61.9 0 112-50.1 112-112c0-2.5-.1-5-.2-7.5z" />
                                    </svg>
                                    <span class="pl-2.5 pr-2">Style:</span>
                                    <span v-if="fontData" class="font-medium">
                                        {{ sprintf(_n('%d subset', '%d subsets', fontData?.subsets.length, 'yabe-webfont'), fontData?.subsets.length) }}
                                        & {{ sprintf(_n('%d variant', '%d variants', fontData?.variants.length, 'yabe-webfont'), fontData?.variants.length) }}
                                    </span>
                                </div>

                                <!-- Font Info: Designers -->
                                <div class="py-1.5 flex items-center">
                                    <svg class="h-4 w-4 shrink-0 text-[#8c8f94] fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                        <path d="M192 80c0 8.8 7.2 16 16 16h80c17.7 0 32 14.3 32 32s-14.3 32-32 32H208c-44.2 0-80-35.8-80-80s35.8-80 80-80H320c70.7 0 128 57.3 128 128s-57.3 128-128 128H288c0 17.7-14.3 32-32 32s-32-14.3-32-32V224c0-17.7 14.3-32 32-32h64c35.3 0 64-28.7 64-64s-28.7-64-64-64H208c-8.8 0-16 7.2-16 16zM568.2 336.3c13.1 17.8 9.3 42.8-8.5 55.9L433.1 485.5c-23.4 17.2-51.6 26.5-80.7 26.5H192 32c-17.7 0-32-14.3-32-32V416c0-17.7 14.3-32 32-32H68.8l44.9-36c22.7-18.2 50.9-28 80-28H272h16 64c17.7 0 32 14.3 32 32s-14.3 32-32 32H288 272c-8.8 0-16 7.2-16 16s7.2 16 16 16H392.6l119.7-88.2c17.8-13.1 42.8-9.3 55.9 8.5zM193.6 384l0 0-.9 0c.3 0 .6 0 .9 0z" />
                                    </svg>
                                    <span class="pl-2.5 pr-2">Designers:</span>
                                    <span class="font-medium">{{ fontData?.designers }}</span>
                                </div>

                                <!-- Font Info: Axes -->
                                <div v-if="fontData?.axes.length" class="py-1.5 flex flex-col">
                                    <div class="flex items-center">
                                        <svg class="h-4 w-4 shrink-0 text-[#8c8f94] fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                            <path d="M495.9 166.6c3.2 8.7 .5 18.4-6.4 24.6l-43.3 39.4c1.1 8.3 1.7 16.8 1.7 25.4s-.6 17.1-1.7 25.4l43.3 39.4c6.9 6.2 9.6 15.9 6.4 24.6c-4.4 11.9-9.7 23.3-15.8 34.3l-4.7 8.1c-6.6 11-14 21.4-22.1 31.2c-5.9 7.2-15.7 9.6-24.5 6.8l-55.7-17.7c-13.4 10.3-28.2 18.9-44 25.4l-12.5 57.1c-2 9.1-9 16.3-18.2 17.8c-13.8 2.3-28 3.5-42.5 3.5s-28.7-1.2-42.5-3.5c-9.2-1.5-16.2-8.7-18.2-17.8l-12.5-57.1c-15.8-6.5-30.6-15.1-44-25.4L83.1 425.9c-8.8 2.8-18.6 .3-24.5-6.8c-8.1-9.8-15.5-20.2-22.1-31.2l-4.7-8.1c-6.1-11-11.4-22.4-15.8-34.3c-3.2-8.7-.5-18.4 6.4-24.6l43.3-39.4C64.6 273.1 64 264.6 64 256s.6-17.1 1.7-25.4L22.4 191.2c-6.9-6.2-9.6-15.9-6.4-24.6c4.4-11.9 9.7-23.3 15.8-34.3l4.7-8.1c6.6-11 14-21.4 22.1-31.2c5.9-7.2 15.7-9.6 24.5-6.8l55.7 17.7c13.4-10.3 28.2-18.9 44-25.4l12.5-57.1c2-9.1 9-16.3 18.2-17.8C227.3 1.2 241.5 0 256 0s28.7 1.2 42.5 3.5c9.2 1.5 16.2 8.7 18.2 17.8l12.5 57.1c15.8 6.5 30.6 15.1 44 25.4l55.7-17.7c8.8-2.8 18.6-.3 24.5 6.8c8.1 9.8 15.5 20.2 22.1 31.2l4.7 8.1c6.1 11 11.4 22.4 15.8 34.3zM256 336c44.2 0 80-35.8 80-80s-35.8-80-80-80s-80 35.8-80 80s35.8 80 80 80z" />
                                        </svg>
                                        <span class="pl-2.5 pr-2">Axis:</span>
                                    </div>
                                    <table class="table-auto border-collapse mt-1.5 ml-6 border border-solid">
                                        <tr class="text-left bg-gray-50">
                                            <th class="p-1.5 border border-solid border-gray-200">
                                                <a href="https://fonts.google.com/variablefonts#axis-definitions" target="_blank">
                                                    Axes
                                                </a>
                                            </th>
                                            <th class="p-1.5 border border-solid border-gray-200 text-right">Min</th>
                                            <th class="p-1.5 border border-solid border-gray-200 text-right">Max</th>
                                            <th class="p-1.5 border border-solid border-gray-200 text-right">Default</th>
                                        </tr>
                                        <tr v-for="axis in fontData?.axes" :key="axis.tag" class="border border-solid border-gray-200">
                                            <td class="p-1.5">{{ axis.tag }}</td>
                                            <td class="p-1.5 text-right">{{ axis.min }}</td>
                                            <td class="p-1.5 text-right">{{ axis.max }}</td>
                                            <td class="p-1.5 text-right">{{ axis.defaultValue }}</td>
                                        </tr>
                                    </table>


                                </div>

                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import ago from 's-ago';

const props = defineProps({
    fontData: {
        type: Object,
        required: true,
    },
    fontData: {
        type: Object,
    },
});
</script>