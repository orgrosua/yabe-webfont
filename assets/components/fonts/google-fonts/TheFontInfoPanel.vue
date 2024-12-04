<template>
    <div>
        <div id="submitdiv" class="postbox">
            <div class="postbox-header">
                <h2 class="">
                    Font Information
                    <a v-if="fontData" :href="`https://fonts.google.com/specimen/${fontData.slug.replaceAll('-', '+')}`" target="_blank">
                        <Icon icon="fa6-solid:arrow-up-right-from-square" class="w:15 h:15"/>
                    </a>
                </h2>
            </div>
            <div class="inside">
                <div class="submitbox" id="submitpost">

                    <div id="minor-publishing">
                        <div id="misc-publishing-actions" class="py:6">
                            <div class="misc-pub-section">
                                <!-- Font Info: Rank -->
                                <div class="py:6 flex align-items:center">
                                    <Icon icon="fa6-solid:star" class="h:16 w:16 flex-shrink:0 fg:#8c8f94 fill:current" />
                                    <span class="pl:10 pr:8">Rank:</span>
                                    <span class="font:medium">{{ fontData?.popularity }}</span>
                                </div>

                                <!-- Font Info: Category -->
                                <div class="py:6 flex align-items:center">
                                    <Icon icon="fa6-solid:font" class="h:16 w:16 flex-shrink:0 fg:#8c8f94 fill:current" />
                                    <span class="pl:10 pr:8">Category:</span>
                                    <span class="font:medium">{{ fontData?.category }}</span>
                                </div>

                                <!-- Font Info: Version -->
                                <div class="py:6 flex align-items:center">
                                    <Icon icon="fa6-solid:code-pull-request" class="h:16 w:16 flex-shrink:0 fg:#8c8f94 fill:current" />
                                    <span class="pl:10 pr:8">Version:</span>
                                    <span class="font:medium">{{ fontData?.version }}</span>
                                </div>

                                <!-- Font Info: Last Modified -->
                                <div class="py:6 flex align-items:center">
                                    <Icon icon="fa6-solid:calendar-check" class="h:16 w:16 flex-shrink:0 fg:#8c8f94 fill:current" />
                                    <span class="pl:10 pr:8">Last modified:</span>
                                    <span v-if="fontData" :title="fontData?.modifiedAt" class="font:medium">{{ ago(new Date(fontData?.modifiedAt)) }}</span>
                                </div>

                                <!-- Font Info: Style -->
                                <div class="py:6 flex align-items:center">
                                    <Icon icon="fa6-solid:paintbrush" class="h:16 w:16 flex-shrink:0 fg:#8c8f94 fill:current" />
                                    <span class="pl:10 pr:8">Style:</span>
                                    <span v-if="fontData" class="font:medium">
                                        {{ sprintf(_n('%d subset', '%d subsets', fontData?.subsets.length, 'yabe-webfont'), fontData?.subsets.length) }}
                                        & {{ sprintf(_n('%d variant', '%d variants', fontData?.variants.length, 'yabe-webfont'), fontData?.variants.length) }}
                                    </span>
                                </div>

                                <!-- Font Info: Designers -->
                                <div class="py:6 flex align-items:center">
                                    <Icon icon="fa6-solid:user" class="h:16 w:16 flex-shrink:0 fg:#8c8f94 fill:current" />
                                    <span class="pl:10 pr:8">Designers:</span>
                                    <span class="font:medium">{{ fontData?.designers }}</span>
                                </div>

                                <!-- Font Info: Axes -->
                                <div v-if="fontData?.axes.length" class="py:6 flex flex:col">
                                    <div class="flex align-items:center">
                                        <Icon icon="fa6-solid:gear" class="h:16 w:16 flex-shrink:0 fg:#8c8f94 fill:current" />
                                        <span class="pl:10 pr:8">Axis:</span>
                                    </div>
                                    <table class="table-auto border-collapse mt:6 ml:36 b:1|solid|gray-20/.4">
                                        <thead>

                                            <tr class="text:left bg:gray-5">
                                                <th class="p:6 b:1|solid|gray-20/.4">
                                                    <a href="https://fonts.google.com/variablefonts#axis-definitions" target="_blank">
                                                        Axes
                                                    </a>
                                                </th>
                                                <th class="p:6 b:1|solid|gray-20/.4 text:right">Min</th>
                                                <th class="p:6 b:1|solid|gray-20/.4 text:right">Max</th>
                                                <th class="p:6 b:1|solid|gray-20/.4 text:right">Default</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="axis in fontData?.axes" :key="axis.tag" class="b:1|solid|gray-20/.4">
                                                <td class="p:6">{{ axis.tag }}</td>
                                                <td class="p:6 text:right">{{ axis.min }}</td>
                                                <td class="p:6 text:right">{{ axis.max }}</td>
                                                <td class="p:6 text:right">{{ axis.defaultValue }}</td>
                                            </tr>
                                        </tbody>
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