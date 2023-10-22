<template>
    <div class="">
        <main class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-xl py-16 sm:py-24">
                <div class="text-center">
                    <p class="text-base font-semibold text-sky-600">404</p>
                    <h1 class="mt-2 text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">{{ __('This page does not exist.', 'yabe-webfont') }}</h1>
                    <p class="mt-2 text-lg text-gray-500">{{ __('The page you are looking for could not be found.', 'yabe-webfont') }}</p>
                </div>
                <div class="mt-12">
                    <h2 class="text-base font-semibold text-gray-500">{{ __('Popular pages', 'yabe-webfont') }}</h2>
                    <ul role="list" class="mt-4 border-solid border-0 divide-solid divide-x-0 divide-y divide-gray-300 border-t border-b border-gray-300">
                        <li class="relative flex items-start space-x-4 py-6">
                            <div class="shrink-0">
                                <span class="flex h-12 w-12 items-center justify-center rounded-lg bg-gray-200">
                                    <svg class="h-6 w-6 text-sky-700 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                        <path d="M96 0C43 0 0 43 0 96V416c0 53 43 96 96 96H384h32c17.7 0 32-14.3 32-32s-14.3-32-32-32V384c17.7 0 32-14.3 32-32V32c0-17.7-14.3-32-32-32H384 96zm0 384H352v64H96c-17.7 0-32-14.3-32-32s14.3-32 32-32zm32-240c0-8.8 7.2-16 16-16H336c8.8 0 16 7.2 16 16s-7.2 16-16 16H144c-8.8 0-16-7.2-16-16zm16 48H336c8.8 0 16 7.2 16 16s-7.2 16-16 16H144c-8.8 0-16-7.2-16-16s7.2-16 16-16z" />
                                    </svg>
                                </span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="my-0 text-base font-medium text-gray-900">
                                    <span class="rounded-sm focus-within:ring-2 focus-within:ring-sky-500 focus-within:ring-offset-2">
                                        <a href="#" class="no-underline focus:shadow-none focus:outline-none text-inherit">
                                            <span class="absolute inset-0" aria-hidden="true"></span>
                                            {{ __('Documentation', 'yabe-webfont') }}
                                        </a>
                                    </span>
                                </h3>
                                <p class="my-0 text-base text-gray-500">{{ __('A complete knowledge-base of the product','yabe-webfont') }}</p>
                            </div>
                            <div class="shrink-0 self-center">
                                <!-- Heroicon name: mini/chevron-right -->
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </li>

                        <li class="relative flex items-start space-x-4 py-6">
                            <div class="shrink-0">
                                <span class="flex h-12 w-12 items-center justify-center rounded-lg bg-gray-200">
                                    <svg class="h-6 w-6 text-sky-700 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                        <path d="M256 48C141.1 48 48 141.1 48 256v40c0 13.3-10.7 24-24 24s-24-10.7-24-24V256C0 114.6 114.6 0 256 0S512 114.6 512 256V400.1c0 48.6-39.4 88-88.1 88L313.6 488c-8.3 14.3-23.8 24-41.6 24H240c-26.5 0-48-21.5-48-48s21.5-48 48-48h32c17.8 0 33.3 9.7 41.6 24l110.4 .1c22.1 0 40-17.9 40-40V256c0-114.9-93.1-208-208-208zM144 208h16c17.7 0 32 14.3 32 32V352c0 17.7-14.3 32-32 32H144c-35.3 0-64-28.7-64-64V272c0-35.3 28.7-64 64-64zm224 0c35.3 0 64 28.7 64 64v48c0 35.3-28.7 64-64 64H352c-17.7 0-32-14.3-32-32V240c0-17.7 14.3-32 32-32h16z" />
                                    </svg>
                                </span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="my-0 text-base font-medium text-gray-900">
                                    <span class="rounded-sm focus-within:ring-2 focus-within:ring-sky-500 focus-within:ring-offset-2">
                                        <a href="https://l.suabahasa.dev/EyJym" class="no-underline focus:shadow-none focus:outline-none text-inherit">
                                            <span class="absolute inset-0" aria-hidden="true"></span>
                                            {{ __('Support', 'yabe-webfont') }}
                                        </a>
                                    </span>
                                </h3>
                                <p class="my-0 text-base text-gray-500">{{ __('How can we help you?', 'yabe-webfont') }} </p>
                            </div>
                            <div class="shrink-0 self-center">
                                <!-- Heroicon name: mini/chevron-right -->
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </li>

                        <li class="relative flex items-start space-x-4 py-6">
                            <div class="shrink-0">
                                <span class="flex h-12 w-12 items-center justify-center rounded-lg bg-gray-200">
                                    <!-- rss -->
                                    <svg class="h-6 w-6 text-sky-700 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                        <path d="M0 64C0 46.3 14.3 32 32 32c229.8 0 416 186.2 416 416c0 17.7-14.3 32-32 32s-32-14.3-32-32C384 253.6 226.4 96 32 96C14.3 96 0 81.7 0 64zM128 416c0 35.3-28.7 64-64 64s-64-28.7-64-64s28.7-64 64-64s64 28.7 64 64zM32 160c159.1 0 288 128.9 288 288c0 17.7-14.3 32-32 32s-32-14.3-32-32c0-123.7-100.3-224-224-224c-17.7 0-32-14.3-32-32s14.3-32 32-32z" />
                                    </svg>
                                </span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="my-0 text-base font-medium text-gray-900">
                                    <span class="rounded-sm focus-within:ring-2 focus-within:ring-sky-500 focus-within:ring-offset-2">
                                        <a href="https://rosua.org/blog/" class="no-underline focus:shadow-none focus:outline-none text-inherit">
                                            <span class="absolute inset-0" aria-hidden="true"></span>
                                            {{ __('Blog', 'yabe-webfont') }}
                                        </a>
                                    </span>
                                </h3>
                                <p class="my-0 text-base text-gray-500">{{ __('Read our latest news and articles', 'yabe-webfont') }}</p>
                            </div>
                            <div class="shrink-0 self-center">
                                <!-- Heroicon name: mini/chevron-right -->
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </li>
                    </ul>
                    <div class="mt-8">
                        <router-link :to="{ name: 'home' }" class="no-underline text-base font-medium text-sky-600 hover:text-sky-500">
                            {{ __('Or go back home', 'yabe-webfont') }}
                            <span aria-hidden="true"> &rarr;</span>
                        </router-link>
                    </div>
                </div>
            </div>
        </main>
    </div>


</template>

<script setup></script>
