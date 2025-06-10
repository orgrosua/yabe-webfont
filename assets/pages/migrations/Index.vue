<template>
    <hr class="invisible m:0 mt:-2" />

    <div class="mt:16">
        <div class="{bx:1|solid|gray-10/.5}>*+* overflow:hidden r:8 grid grid-cols:2 gap-x:1">
            <div v-for="(legacy, legacyIdx) in legacies" :key="legacy.title" :class="[
                legacyIdx === 0 ? 'rtl:8 rtr:0' : '',
                legacyIdx === 1 ? 'rtr:8' : '',
                legacyIdx === legacies.length - 2 ? 'rbl:8' : '',
                legacyIdx === legacies.length - 1 ? 'rbr:8 rbl:0' : '',
                'group rel bg:white p:24 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500'
            ]">
                <div>
                    <span :class="[legacy.iconBackground, legacy.iconForeground, 'inline-flex r:8 p:12 ring-4 ring-white']">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h:24 w:24 fill:current" aria-hidden="true" viewBox="0 0 576 512"><!--! Font Awesome Pro 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                            <path d="M80 80c-17.7 0-32 14.3-32 32v48H80v48H48c-26.5 0-48-21.5-48-48V112C0 67.8 35.8 32 80 32H400c44.2 0 80 35.8 80 80V272H448 432 400c-8.8 0-16-7.2-16-16s7.2-16 16-16h32V160H400c-8.8 0-16-7.2-16-16s7.2-16 16-16h32V112c0-17.7-14.3-32-32-32H153.3c4.3 9.8 6.7 20.6 6.7 32V224h48c8.8 0 16 7.2 16 16s-7.2 16-16 16H160V400c0 17.7 14.3 32 32 32s32-14.3 32-32V352c0-26.5 21.5-48 48-48h.6 .6 .6 .6 .6 .6 .6 .6 .6 .6 .6 .6 .6 .6 .6 .6 .6 .6 .6 .6 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .5 .6 .5 .6 .5 .6 .6 .6 .6 .6 .6 .6 .6 .6 .6 .6 .6 .6 .6 .6 .6 .6 .6 .6 .6c26.5 0 48 21.5 48 48v48c0 44.2-35.8 80-80 80H192c-44.2 0-80-35.8-80-80V112c0-17.7-14.3-32-32-32zM265.3 432H496c17.7 0 32-14.3 32-32V352h0-.6-.6-.6-.6-.6-.6H524h-.6-.6-.6-.6-.6-.6-.6-.6H519h-.6-.6-.6-.6-.5-.6-.5-.6-.5-.5H513h-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5H507h-.5H506h-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5H499h-.5H498h-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5H487h-.5H486h-.5H485h-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5H456h-.5H455h-.5H454h-.5H453h-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5H441h-.5H440h-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5H431h-.5H430h-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5H423h-.5H422h-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5H415h-.5H414h-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5H407h-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5H400v32c0 8.8-7.2 16-16 16s-16-7.2-16-16V352h-.4-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5H360h-.5H359h-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5H347h-.5H346h-.5H345h-.5H344h-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5H315h-.5H314h-.5H313h-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5H302h-.5H301h-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5H294h-.5H293h-.5-.5-.5-.5-.5-.5-.5-.5-.5-.5H287h-.5-.5-.5-.5-.5-.5-.5-.6-.6-.6H281h-.6-.6-.6-.6-.6-.6-.6-.6H276h-.6-.6-.6-.6-.6-.6H272h0v48c0 11.4-2.4 22.2-6.7 32z" />
                        </svg>
                    </span>
                </div>
                <div class="mt:32">
                    <h3 class="font:16 lh:24px font:semibold leading-6 fg:gray-90">
                        <router-link :to="{ name: legacy.route }" class="text-decoration:none fg:black focus:outline-none">
                            <!-- Extend touch target to entire panel -->
                            <!-- <span class="abs inset-0" aria-hidden="true" /> -->
                            {{ legacy.title }}
                            
                        </router-link>
                    </h3>
                    <p class="mt:8 font:14 lh:20px fg:gray-50">{{ legacy.description }}</p>
                </div>
                <span class="pointer-events:none abs right:24 top:24 fg:gray-30 group-hover:fg:gray-40" aria-hidden="true">
                    <svg class="h:24 w:24" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20 4h1a1 1 0 00-1-1v1zm-1 12a1 1 0 102 0h-2zM8 3a1 1 0 000 2V3zM3.293 19.293a1 1 0 101.414 1.414l-1.414-1.414zM19 4v12h2V4h-2zm1-1H8v2h12V3zm-.707.293l-16 16 1.414 1.414 16-16-1.414-1.414z" />
                    </svg>
                </span>
            </div>
        </div>
    </div>
</template>

<script setup>

const legacies = [
    {
        title: 'Custom Fonts [Bricks]',
        iconForeground: 'fg:yellow-70',
        iconBackground: 'bg:yellow-5',
        route: 'migrations.custom-fonts-bricks',
        description: 'Import data from the Custom Fonts [Bricks] feature',
    },
    // {
    //     title: 'Use Any Font [Dnesscarkey]',
    //     iconForeground: 'fg:teal-70',
    //     iconBackground: 'bg:teal-5',
    //     route: 'migrations.custom-fonts-bricks',
    //     description: 'Import data from the Use Any Font [Dnesscarkey] plugin',
    // },
    // {
    //     title: 'Custom Fonts [Brainstorm Force]',
    //     iconForeground: 'fg:indigo-70',
    //     iconBackground: 'bg:indigo-5',
    //     route: 'migrations.custom-fonts-bricks',
    //     description: 'Import data from the Custom Fonts [Brainstorm Force] plugin',
    // },
    // {
    //     title: 'Font Hero [Dplugins]',
    //     iconForeground: 'fg:purple-70',
    //     iconBackground: 'bg:purple-5',
    //     route: 'migrations.font-hero-dplugins',
    //     description: 'Import data from the Font Hero [Dplugins] plugin',
    // },
]
</script>

<style></style>