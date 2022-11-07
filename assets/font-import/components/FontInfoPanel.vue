<template>
    <div class="meta-box-sortables ui-sortable">
        <div id="submitdiv" class="postbox ">
            <div class="postbox-header">
                <h2 class="hndle ui-sortable-handle">Font Information</h2>
            </div>
            <div class="inside">
                <div>
                    <div class="tw-px-2.5 tw-py-3">
                        <!-- Font Info: Popularity -->
                        <div class="tw-py-1.5 tw-flex tw-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="tw-h-4 tw-w-4 tw-text-[#8c8f94] tw-fill-current">
                                <!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                <path d="M424 136l-74.01-.0254l13.63-75.76c2.344-13.03-6.312-25.53-19.38-27.88c-13-2.188-25.5 6.344-27.88 19.38l-15.16 84.26h-111.2l13.63-75.76c2.344-13.03-6.312-25.53-19.38-27.88C171.2 30.15 158.7 38.69 156.4 51.72l-15.16 84.26H56c-13.25 0-24 10.78-24 24.03c0 13.25 10.75 23.97 24 23.97h76.57l-25.92 144H24c-13.25 0-24 10.76-24 24.01C0 365.3 10.75 376 24 376l74.01-.0078l-13.63 75.76c-2.344 13.03 6.312 25.53 19.38 27.88C105.2 479.9 106.6 480 108 480c11.38 0 21.5-8.158 23.59-19.75l15.16-84.26h111.2l-13.63 75.76c-2.344 13.03 6.312 25.53 19.38 27.88C265.2 479.9 266.6 480 268 480c11.38 0 21.5-8.158 23.59-19.75l15.16-84.26L392 376c13.25 0 24-10.75 24-23.1c0-13.25-10.75-24.01-24-24.01h-76.57l25.92-144L424 184c13.25 0 24-10.75 24-23.1C448 146.8 437.3 136 424 136zM266.7 327.1h-111.2l25.92-144h111.2L266.7 327.1z" />
                            </svg>
                            <span class="tw-pl-2.5 tw-pr-2">Rank:</span>
                            <span v-if="font" class="tw-font-medium"> {{ font.popularity }} </span>
                        </div>

                        <!-- Font Info: Category -->
                        <div class="tw-py-1.5 tw-flex tw-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="tw-h-4 tw-w-4 tw-text-[#8c8f94] tw-fill-current">
                                <!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                <path d="M432 448h-21.37L238.7 40.9c-5-11.87-24.47-11.87-29.47 0L37.37 448H16C7.164 448 0 455.2 0 464S7.164 480 16 480H96c8.838 0 16-7.164 16-16S104.8 448 96 448H72.12l40.64-96.25h222.5L375.9 448H352c-8.836 0-16 7.164-16 16S343.2 480 352 480h80c8.838 0 16-7.164 16-16S440.8 448 432 448zM126.3 319.7L224 88.29l97.71 231.4H126.3z" />
                            </svg>
                            <span class="tw-pl-2.5 tw-pr-2">Category:</span>
                            <span v-if="font" class="tw-font-medium"> {{ font.category }} </span>
                        </div>

                        <!-- Font Info: Version -->
                        <div class="tw-py-1.5 tw-flex tw-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="tw-h-4 tw-w-4 tw-text-[#8c8f94] tw-fill-current">
                                <!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                <path d="M112 0C120.8 0 128 7.164 128 16V64H320V16C320 7.164 327.2 0 336 0C344.8 0 352 7.164 352 16V64H384C419.3 64 448 92.65 448 128V448C448 483.3 419.3 512 384 512H64C28.65 512 0 483.3 0 448V128C0 92.65 28.65 64 64 64H96V16C96 7.164 103.2 0 112 0zM416 192H32V448C32 465.7 46.33 480 64 480H384C401.7 480 416 465.7 416 448V192zM384 96H64C46.33 96 32 110.3 32 128V160H416V128C416 110.3 401.7 96 384 96z" />
                            </svg>
                            <span class="tw-pl-2.5 tw-pr-2">Last modified:</span>
                            <span v-if="font" class="tw-font-medium"> {{ new Date(font.modifiedAt).toLocaleDateString() }} </span>
                        </div>

                        <!-- Font Info: Subsets and variants -->
                        <div class="tw-py-1.5 tw-flex tw-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="tw-h-4 tw-w-4 tw-text-[#8c8f94] tw-fill-current">
                                <!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                <path d="M557.2 109.3L286.3 380.2C287.4 386.6 288 393.2 288 400C288 461.9 237.9 512 176 512H32C14.33 512 0 497.7 0 480C0 462.3 14.33 448 32 448H36.81C54.44 448 66.4 429.1 64.59 411.6C64.2 407.8 64 403.9 64 400C64 338.1 114.1 288 176 288C182.7 288 189.3 288.6 195.7 289.7L466.7 18.75C491.7-6.248 532.2-6.248 557.2 18.75C582.2 43.74 582.2 84.26 557.2 109.3L557.2 109.3zM489.3 41.37L324.6 206L369.9 251.3L534.5 86.63C547 74.13 547 53.87 534.5 41.37C522 28.88 501.8 28.88 489.3 41.37V41.37zM224.6 306L269.9 351.3L347.3 273.9L302 228.6L224.6 306zM195.9 322.5C189.5 320.9 182.9 320 176 320C131.8 320 96 355.8 96 400C96 402.8 96.14 405.6 96.43 408.3C98.15 425 93.42 441.9 83.96 455.1C74.31 468.5 58 480 36.81 480H176C220.2 480 256 444.2 256 400C256 393.1 255.1 386.5 253.5 380.1L195.9 322.5z" />
                            </svg>
                            <span class="tw-pl-2.5 tw-pr-2">Style:</span>
                            <span v-if="font" class="tw-font-medium"> {{ font.subsets.length }} subsets & {{ font.variants.length }} variants </span>
                        </div>

                        <!-- Font Info: Designers -->
                        <div class="tw-py-1.5 tw-flex tw-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" class="tw-h-4 tw-w-4 tw-text-[#8c8f94] tw-fill-current">
                                <!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                <path d="M625.9 234.2l-28.13-28.14c-9.373-9.373-21.66-14.06-33.94-14.06s-24.57 4.688-33.94 14.06l-175.5 175.5c-8.936 8.936-15.03 20.32-17.5 32.71l-16.66 83.35C318.7 505.2 324.6 512 331.1 512c.7852 0 1.586-.0781 2.398-.2402l83.35-16.67c12.39-2.479 23.77-8.566 32.7-17.5l175.5-175.5C644.7 283.3 644.7 252.9 625.9 234.2zM427.8 454.1c-4.48 4.48-10.13 7.506-16.35 8.748L357.5 474.5l10.78-53.93c1.244-6.219 4.27-11.88 8.754-16.36l114.3-114.3l50.76 50.76L427.8 454.1zM603.3 279.5l-38.62 38.62l-50.76-50.76l38.62-38.62c4.076-4.076 8.838-4.686 11.31-4.686s7.236 .6094 11.31 4.686l28.13 28.14C607.4 260.9 608 265.7 608 268.1C608 270.6 607.4 275.4 603.3 279.5zM223.1 256c70.7 0 128-57.31 128-128s-57.3-128-128-128C153.3 0 96 57.31 96 128S153.3 256 223.1 256zM223.1 32c52.94 0 96 43.06 96 96c0 52.93-43.06 96-96 96s-96-43.07-96-96C127.1 75.06 171.1 32 223.1 32zM272.5 480H34.66C33.21 480 32 478.8 32 477.3C31.99 399.4 95.4 336 173.3 336h101.3c19.17 0 37.23 4.199 53.84 11.27c6.475 2.758 13.1 .9727 18.97-4.002c7.65-7.652 5.398-20.84-4.533-25.13C321.1 309.1 298.9 304 274.7 304H173.3C77.54 304-.1152 381.6 0 477.4C.0234 496.4 15.63 512 34.66 512h237.9c8.545 0 15.47-6.928 15.47-15.47v-1.059C287.1 486.9 281.1 480 272.5 480z" />
                            </svg>
                            <span class="tw-pl-2.5 tw-pr-2">Designers:</span>
                            <span v-if="font" class="tw-font-medium"> {{ font.designers.join(', ') }} </span>
                        </div>

                        <!-- Font Info: Variable support checkbox -->
                        <div class="tw-py-1.5 tw-flex tw-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="tw-h-4 tw-w-4 tw-text-[#8c8f94] tw-fill-current">
                                <!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                <path d="M0 416C0 407.2 7.164 400 16 400H81.6C89.01 363.5 121.3 336 160 336C198.7 336 230.1 363.5 238.4 400H496C504.8 400 512 407.2 512 416C512 424.8 504.8 432 496 432H238.4C230.1 468.5 198.7 496 160 496C121.3 496 89.01 468.5 81.6 432H16C7.164 432 0 424.8 0 416V416zM208 416C208 389.5 186.5 368 160 368C133.5 368 112 389.5 112 416C112 442.5 133.5 464 160 464C186.5 464 208 442.5 208 416zM352 176C390.7 176 422.1 203.5 430.4 240H496C504.8 240 512 247.2 512 256C512 264.8 504.8 272 496 272H430.4C422.1 308.5 390.7 336 352 336C313.3 336 281 308.5 273.6 272H16C7.164 272 0 264.8 0 256C0 247.2 7.164 240 16 240H273.6C281 203.5 313.3 176 352 176zM400 256C400 229.5 378.5 208 352 208C325.5 208 304 229.5 304 256C304 282.5 325.5 304 352 304C378.5 304 400 282.5 400 256zM496 80C504.8 80 512 87.16 512 96C512 104.8 504.8 112 496 112H270.4C262.1 148.5 230.7 176 192 176C153.3 176 121 148.5 113.6 112H16C7.164 112 0 104.8 0 96C0 87.16 7.164 80 16 80H113.6C121 43.48 153.3 16 192 16C230.7 16 262.1 43.48 270.4 80H496zM144 96C144 122.5 165.5 144 192 144C218.5 144 240 122.5 240 96C240 69.49 218.5 48 192 48C165.5 48 144 69.49 144 96z" />
                            </svg>
                            <span class="tw-pl-2.5 tw-pr-2">Variable support:</span>
                            <span v-if="font && !font.isSupportVariable" class="tw-font-medium tw-pr-2"> Not available </span>
                            <div v-else-if="font && font.isSupportVariable">
                                <!-- Font Info: Variable -->
                                <input type="checkbox" v-model="variableEnabled" id="variable-support" :class="[{ '!tw-border-blue-600': variableEnabled }, '!tw-m-0']" />
                                <label for="variable-support" class="tw-text-sm tw-text-gray-700 tw-font-medium tw-m-0 tw-ml-2 tw-inline-block tw-cursor-pointer"> {{ variableEnabled ? 'Enabled' : 'Disabled' }}</label>
                            </div>

                        </div>
                    </div>

                    <div id="major-publishing-actions">
                        <div class="tw-float-right tw-text-right">
                            <span class="spinner tw-float-none"></span>
                            <button @click="$emit('importFont')" :disabled="!font" class="button button-primary button-large">Import</button>
                        </div>
                        <div class="clear"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { inject } from 'vue';

const props = defineProps({
    font: {
        type: Object,
        required: false
    },
    importSelectedFont: {
        type: Function,
        required: false
    }
});

const variableEnabled = inject('variableEnabled');
</script>