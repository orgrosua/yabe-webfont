<template>
    <span class="tw-mr-2 tw-text-2xl">» {{ __('New', 'yabe-webfont') }} </span>

    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-2">


            <div id="post-body-content">
                <div id="titlediv">
                    <div id="titlewrap">
                        <label class="screen-reader-text" for="title">Add title</label>
                        <input type="text" name="title" id="title" size="30" v-model="title" placeholder="Title" autocomplete="off">
                    </div>
                    <div class="inside">

                    </div>
                </div><!-- /titlediv -->
                <div class="postarea wp-editor-expand">

                    <div id="wp-content-wrap" class="wp-core-ui tw-mt-8">
                        <h3>Meta</h3>
                        <div class="tw-grid tw-grid-cols-12 tw-gap-4">
                            <div class="tw-col-span-4 tw-flex tw-flex-col tw-gap-1.5">
                                <label for="family" class="tw-text-sm tw-font-semibold">Font Family</label>
                                <input type="text" name="family" id="family" v-model="family" placeholder="Font Family" autocomplete="off">
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
                        </div>

                        <div class="tw-flex tw-items-center tw-space-x-4 tw-mt-8">
                            <h3 class="tw-flex-1">Font Files</h3>

                            <div class="tw-flex tw-items-center tw-space-x-4 ">
                                <div class="tw-h-fit tw-flex tw-rounded-md tw-shadow-sm">
                                    <span class="tw-inline-flex tw-items-center tw-rounded-l-md tw-border tw-border-solid !tw-border-r-0 !tw-border-gray-300 tw-bg-gray-50 tw-px-3 tw-text-gray-500 !tw-text-xs">Preview size</span>
                                    <input type="number" v-model="preview.fontSize" class="!tw-block !tw-min-w-0 tw-w-16 !tw-min-h-0 !tw-h-6 !tw-py-0 !tw-px-2 !tw-border-1  !tw-border-solid !tw-rounded-none !tw-border-gray-300 !tw-text-xs" />
                                    <span class="tw-inline-flex tw-items-center tw-rounded-r-md tw-border tw-border-solid !tw-border-l-0 !tw-border-gray-300 tw-bg-gray-50 tw-px-3 tw-text-gray-500 !tw-text-xs">px</span>
                                </div>

                                <button @click="createNewFontFace" v-ripple class="button tw-my-4">Add a font file</button>
                            </div>

                        </div>

                        <div class="font-files">
                            <div class="tw-grid tw-gap-4">


                                <draggable v-model="fontFaces" tag="transition-group" item-key="id" :component-data="{
                                    // tag: 'div',
                                    // type: 'TransitionGroup',
                                    name: 'font-face'
                                }" ghost-class="dragged-placeholder" animation="200">
                                    <template #item="{ element }">
                                        <div>
                                            <TheFontFace :item="element" :preview="preview" :font-family="family" />
                                        </div>
                                    </template>
                                </draggable>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- /post-body-content -->
            <div id="postbox-container-1" class="postbox-container">
                <div id="side-sortables" class="meta-box-sortables ui-sortable" style="">
                    <div id="submitdiv" class="postbox ">
                        <div class="postbox-header">
                            <h2 class="hndle ui-sortable-handle">Publish</h2>
                            <!-- <div class="handle-actions hide-if-no-js">
                                <button type="button" class="handlediv" aria-expanded="true">
                                    <span class="screen-reader-text">Toggle panel: Publish</span>
                                    <span class="toggle-indicator" aria-hidden="true"></span>
                                </button>
                            </div> -->
                        </div>
                        <div class="inside">
                            <div class="submitbox" id="submitpost">

                                <div id="minor-publishing">

                                    <!-- <div style="display:none;">
                                        <p class="submit"><input type="submit" name="save" id="save" class="button" value="Save"></p>
                                    </div> -->

                                    <!-- <div id="minor-publishing-actions">
                                        <div id="save-action">
                                            <input type="submit" name="save" id="save-post" value="Save Draft" class="button">
                                            <span class="spinner"></span>
                                        </div>

                                        <div id="preview-action">
                                            <a class="preview button" href="http://127.0.0.1:8080/?p=1&amp;preview=true" target="wp-preview-1" id="post-preview">Preview<span class="screen-reader-text"> (opens in a new tab)</span></a>
                                            <input type="hidden" name="wp-preview" id="wp-preview" value="">
                                        </div>
                                        <div class="clear"></div>
                                    </div> -->

                                    <div id="misc-publishing-actions">
                                        <div class="misc-pub-section misc-pub-post-status">
                                            Status: <span id="post-status-display">
                                                Draft </span>

                                            <a href="#post_status" class="edit-post-status hide-if-no-js" role="button"><span aria-hidden="true">Edit</span> <span class="screen-reader-text">Edit status</span></a>

                                            <div id="post-status-select" class="hide-if-js">
                                                <input type="hidden" name="hidden_post_status" id="hidden_post_status" value="draft">
                                                <label for="post_status" class="screen-reader-text">Set status</label>
                                                <select name="post_status" id="post_status">
                                                    <option value="pending">Pending Review</option>
                                                    <option selected="selected" value="draft">Draft</option>
                                                </select>
                                                <a href="#post_status" class="save-post-status hide-if-no-js button">OK</a>
                                                <a href="#post_status" class="cancel-post-status hide-if-no-js button-cancel">Cancel</a>
                                            </div>
                                        </div>

                                        <!-- <div class="misc-pub-section misc-pub-visibility" id="visibility">
                                            Visibility: <span id="post-visibility-display">
                                                Public </span>

                                            <a href="#visibility" class="edit-visibility hide-if-no-js" role="button"><span aria-hidden="true">Edit</span> <span class="screen-reader-text">Edit visibility</span></a>

                                            <div id="post-visibility-select" class="hide-if-js">
                                                <input type="hidden" name="hidden_post_password" id="hidden-post-password" value="">
                                                <input type="checkbox" style="display:none" name="hidden_post_sticky" id="hidden-post-sticky" value="sticky">

                                                <input type="hidden" name="hidden_post_visibility" id="hidden-post-visibility" value="public">
                                                <input type="radio" name="visibility" id="visibility-radio-public" value="public" checked="checked"> <label for="visibility-radio-public" class="selectit">Public</label><br>

                                                <span id="sticky-span"><input id="sticky" name="sticky" type="checkbox" value="sticky"> <label for="sticky" class="selectit">Stick this post to the front page</label><br></span>

                                                <input type="radio" name="visibility" id="visibility-radio-password" value="password"> <label for="visibility-radio-password" class="selectit">Password protected</label><br>
                                                <span id="password-span"><label for="post_password">Password:</label> <input type="text" name="post_password" id="post_password" value="" maxlength="255"><br></span>

                                                <input type="radio" name="visibility" id="visibility-radio-private" value="private"> <label for="visibility-radio-private" class="selectit">Private</label><br>

                                                <p>
                                                    <a href="#visibility" class="save-post-visibility hide-if-no-js button">OK</a>
                                                    <a href="#visibility" class="cancel-post-visibility hide-if-no-js button-cancel">Cancel</a>
                                                </p>
                                            </div>
                                        </div> -->

                                        <!-- <div class="misc-pub-section curtime misc-pub-curtime">
                                            <span id="timestamp">
                                                Publish on: <b>Aug 24, 2022 at 03:11</b> </span>
                                            <a href="#edit_timestamp" class="edit-timestamp hide-if-no-js" role="button">
                                                <span aria-hidden="true">Edit</span>
                                                <span class="screen-reader-text">Edit date and time</span>
                                            </a>
                                            <fieldset id="timestampdiv" class="hide-if-js">
                                                <legend class="screen-reader-text">Date and time</legend>
                                                <div class="timestamp-wrap"><label><span class="screen-reader-text">Month</span><select class="form-required" id="mm" name="mm">
                                                            <option value="01" data-text="Jan">01-Jan</option>
                                                            <option value="12" data-text="Dec">12-Dec</option>
                                                        </select></label> <label><span class="screen-reader-text">Day</span><input type="text" id="jj" name="jj" value="24" size="2" maxlength="2" autocomplete="off" class="form-required"></label>, <label><span class="screen-reader-text">Year</span><input type="text" id="aa" name="aa" value="2022" size="4" maxlength="4" autocomplete="off" class="form-required"></label> at <label><span class="screen-reader-text">Hour</span><input type="text" id="hh" name="hh" value="03" size="2" maxlength="2" autocomplete="off" class="form-required"></label>:<label><span class="screen-reader-text">Minute</span><input type="text" id="mn" name="mn" value="11" size="2" maxlength="2" autocomplete="off" class="form-required"></label></div><input type="hidden" id="ss" name="ss" value="27">

                                                <p>
                                                    <a href="#edit_timestamp" class="save-timestamp hide-if-no-js button">OK</a>
                                                    <a href="#edit_timestamp" class="cancel-timestamp hide-if-no-js button-cancel">Cancel</a>
                                                </p>
                                            </fieldset>
                                        </div> -->
                                    </div>
                                    <div class="clear"></div>
                                </div>

                                <div id="major-publishing-actions">
                                    <div id="delete-action">
                                        <a class="submitdelete deletion" href="http://127.0.0.1:8080/wp-admin/post.php?post=1&amp;action=trash&amp;_wpnonce=0693899314">Move to Trash</a>
                                    </div>

                                    <div id="publishing-action">
                                        <span class="spinner"></span>
                                        <input type="submit" name="publish" id="publish" class="button button-primary button-large" value="Publish">
                                    </div>
                                    <div class="clear"></div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- <div id="categorydiv" class="postbox ">
                        <div class="postbox-header">
                            <h2 class="hndle ui-sortable-handle">Categories</h2>
                            <div class="handle-actions hide-if-no-js"><button type="button" class="handle-order-higher" aria-disabled="false" aria-describedby="categorydiv-handle-order-higher-description"><span class="screen-reader-text">Move up</span><span class="order-higher-indicator" aria-hidden="true"></span></button><span class="hidden" id="categorydiv-handle-order-higher-description">Move Categories box up</span><button type="button" class="handle-order-lower" aria-disabled="false" aria-describedby="categorydiv-handle-order-lower-description"><span class="screen-reader-text">Move down</span><span class="order-lower-indicator" aria-hidden="true"></span></button><span class="hidden" id="categorydiv-handle-order-lower-description">Move Categories box down</span><button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Categories</span><span class="toggle-indicator" aria-hidden="true"></span></button></div>
                        </div>
                        <div class="inside">
                            <div id="taxonomy-category" class="categorydiv">
                                <ul id="category-tabs" class="category-tabs">
                                    <li class="tabs"><a href="#category-all">All Categories</a></li>
                                    <li class="hide-if-no-js"><a href="#category-pop">Most Used</a></li>
                                </ul>

                                <div id="category-pop" class="tabs-panel" style="display: none;">
                                    <ul id="categorychecklist-pop" class="categorychecklist form-no-clear">
                                    </ul>
                                </div>

                                <div id="category-all" class="tabs-panel">
                                    <ul id="categorychecklist" data-wp-lists="list:category" class="categorychecklist form-no-clear">

                                        <li id="category-1"><label class="selectit"><input value="1" type="checkbox" name="post_category[]" id="in-category-1" checked="checked"> Uncategorized</label></li>
                                    </ul>
                                </div>
                                <div id="category-adder" class="wp-hidden-children">
                                    <a id="category-add-toggle" href="#category-add" class="hide-if-no-js taxonomy-add-new">
                                        + Add New Category </a>
                                    <p id="category-add" class="category-add wp-hidden-child">
                                        <label class="screen-reader-text" for="newcategory">Add New Category</label>
                                        <input type="text" name="newcategory" id="newcategory" class="form-required form-input-tip" value="New Category Name" aria-required="true">
                                        <label class="screen-reader-text" for="newcategory_parent">
                                            Parent Category: </label>
                                        <select name="newcategory_parent" id="newcategory_parent" class="postform">
                                            <option value="-1">— Parent Category —</option>
                                            <option class="level-0" value="1">Uncategorized</option>
                                        </select>
                                        <input type="button" id="category-add-submit" data-wp-lists="add:categorychecklist:category-add" class="button category-add-submit" value="Add New Category">
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tagsdiv-post_tag" class="postbox ">
                        <div class="postbox-header">
                            <h2 class="hndle ui-sortable-handle">Tags</h2>
                            <div class="handle-actions hide-if-no-js"><button type="button" class="handle-order-higher" aria-disabled="false" aria-describedby="tagsdiv-post_tag-handle-order-higher-description"><span class="screen-reader-text">Move up</span><span class="order-higher-indicator" aria-hidden="true"></span></button><span class="hidden" id="tagsdiv-post_tag-handle-order-higher-description">Move Tags box up</span><button type="button" class="handle-order-lower" aria-disabled="false" aria-describedby="tagsdiv-post_tag-handle-order-lower-description"><span class="screen-reader-text">Move down</span><span class="order-lower-indicator" aria-hidden="true"></span></button><span class="hidden" id="tagsdiv-post_tag-handle-order-lower-description">Move Tags box down</span><button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Tags</span><span class="toggle-indicator" aria-hidden="true"></span></button></div>
                        </div>
                        <div class="inside">
                            <div class="tagsdiv" id="post_tag">
                                <div class="jaxtag">
                                    <div class="nojs-tags hide-if-js">
                                        <label for="tax-input-post_tag">Add or remove tags</label>
                                        <p><textarea name="tax_input[post_tag]" rows="3" cols="20" class="the-tags" id="tax-input-post_tag" aria-describedby="new-tag-post_tag-desc"></textarea></p>
                                    </div>
                                    <div class="ajaxtag hide-if-no-js">
                                        <label class="screen-reader-text" for="new-tag-post_tag">Add New Tag</label>
                                        <input data-wp-taxonomy="post_tag" type="text" id="new-tag-post_tag" name="newtag[post_tag]" class="newtag form-input-tip ui-autocomplete-input" size="16" autocomplete="off" aria-describedby="new-tag-post_tag-desc" value="" role="combobox" aria-autocomplete="list" aria-expanded="false" aria-owns="ui-id-2">
                                        <input type="button" class="button tagadd" value="Add">
                                    </div>
                                    <p class="howto" id="new-tag-post_tag-desc">Separate tags with commas</p>
                                </div>
                                <ul class="tagchecklist" role="list"></ul>
                            </div>
                            <p class="hide-if-no-js"><button type="button" class="button-link tagcloud-link" id="link-post_tag" aria-expanded="false">Choose from the most used tags</button></p>
                        </div>
                    </div>
                    <div id="postimagediv" class="postbox ">
                        <div class="postbox-header">
                            <h2 class="hndle ui-sortable-handle">Featured image</h2>
                            <div class="handle-actions hide-if-no-js"><button type="button" class="handle-order-higher" aria-disabled="false" aria-describedby="postimagediv-handle-order-higher-description"><span class="screen-reader-text">Move up</span><span class="order-higher-indicator" aria-hidden="true"></span></button><span class="hidden" id="postimagediv-handle-order-higher-description">Move Featured image box up</span><button type="button" class="handle-order-lower" aria-disabled="false" aria-describedby="postimagediv-handle-order-lower-description"><span class="screen-reader-text">Move down</span><span class="order-lower-indicator" aria-hidden="true"></span></button><span class="hidden" id="postimagediv-handle-order-lower-description">Move Featured image box down</span><button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Featured image</span><span class="toggle-indicator" aria-hidden="true"></span></button></div>
                        </div>
                        <div class="inside">
                            <p class="hide-if-no-js"><a href="http://127.0.0.1:8080/wp-admin/media-upload.php?post_id=1&amp;type=image&amp;TB_iframe=1&amp;width=753&amp;height=437" id="set-post-thumbnail" class="thickbox">Set featured image</a></p><input type="hidden" id="_thumbnail_id" name="_thumbnail_id" value="-1">
                        </div>
                    </div> -->
                </div>
            </div>
            <div id="postbox-container-2" class="postbox-container tw-mt-3">
                <Transition name="css-preview">
                    <div v-if="cssPreview" class="tw-mt-4">
                        <h3 class="tw-mt-5">CSS Preview</h3>
                        <highlightjs language="css" :code="cssPreview" />
                    </div>
                </Transition>

                <!-- <div>
                    <table id="post-status-info" class="tw-border tw-border-solid tw-border-[#c3c4c7]">
                        <tbody>
                            <tr>
                                <td id="wp-word-count">
                                    Word count: <span class="word-count">16</span> </td>
                                <td class="autosave-info">
                                    <span class="autosave-message">&nbsp;</span>
                                    <span id="last-edit">Last edited on November 11, 2022 at 4:40 pm</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div> -->

            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, watch, onBeforeMount, computed } from 'vue';
import { storeToRefs } from 'pinia';

import draggable from 'zhyswan-vuedraggable';

import { useLocalFontStore } from '../../stores/font/localFont.js';

import TheFontFace from '../../components/fonts/local/TheFontFace.vue';

const title = ref('');
const family = ref('');
const display = ref('auto');
const selector = ref('');
const preload = ref(false);

watch(family, (newFamily, oldFamily) => {
    if (title.value === '' || title.value === oldFamily) {
        title.value = newFamily;
    }
});

const store = useLocalFontStore();

const { fontFaces } = storeToRefs(store);

const createNewFontFace = () => {
    store.addFontFace();
};

const preview = reactive({
    text: `I can do all things through Christ which strengtheneth me. [Philippians 4:13]`,
    fontSize: 18,
    lineHeight: 1.5,
    fontFamily: family,
});

const cssPreview = computed(() => {
    let css = ``;

    if (!family.value) {
        return css;
    }

    fontFaces.value.forEach(fontFace => {

        if (fontFace.comment) {
            css += `/* ${fontFace.comment} */\n`;
        }

        css += `@font-face {\n`;

        css += `\tfont-family: '${family.value}';\n`;

        css += `\tfont-style: ${fontFace.style};\n`;

        if (fontFace.weight !== '') {
            css += `\tfont-weight: ${fontFace.weight};\n`;
        }

        if (typeof fontFace.weight !== 'number' && fontFace.weight.split(' ').length > 1) {
            css += `\tfont-stretch: 100%;\n`;
        }

        css += `\tfont-display: ${fontFace.display || display.value};\n`;

        // font :src here
        css += `\t/* src: ; */\n`;

        if (fontFace.unicodeRange) {
            css += `\tunicode-range: ${fontFace.unicodeRange};\n`;
        }

        css += `}\n\n`;

        console.log(fontFace);
    });

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

    // replace <family> placeholder
    css = css.replace(/<family>/g, family.value);

    return css;
});

onBeforeMount(() => {
    fontFaces.value = [];
    createNewFontFace();
});
</script>

<style>
.v-select {
    box-shadow: 0 0 0 transparent;
    border-radius: 4px;
    border: 1px solid #8c8f94;
    background-color: #fff;
    color: #2c3338;
}

.v-select.vs--open {
    box-shadow: var(--tw-ring-inset) 0 0 0 calc(1px + var(--tw-ring-offset-width)) var(--tw-ring-color);
    ;
    --tw-ring-color: theme('colors.sky.600');
}

input[type=search].vs__search {
    background-color: transparent;
    border: none;
    box-shadow: none;
    line-height: normal;
    margin: 0;
    padding-top: 2px;
    padding-bottom: 2px;
    width: theme('width.full');
    font-size: theme('fontSize.sm');
}

input[type=search].vs__search::placeholder {
    color: #646970;
}

span.vs__selected {
    font-size: theme('fontSize.sm');
    margin: 0 2px;
    width: theme('width.full');
}

.vs__dropdown-toggle {
    border: none;
    padding: 0;
}

.vs__dropdown-menu {
    padding: 0;
}

.vs__selected-options {
    flex-grow: 0;
    flex-wrap: nowrap;
}

.vs__selected-options:has(>span.vs__selected) input[type=search].vs__search {
    width: 1px;
}

.vs--single.vs--open .vs__selected,
.vs--single.vs--loading .vs__selected {
    position: initial;
    opacity: .4
}

.vs__actions {
    --vs-actions-padding: 0px 6px 0 3px;
    padding-top: 0;
}

.vs__actions svg {
    transform: scale(0.7);
    fill: #7a7a7a;
}

.vs--open .vs__actions svg {
    transform: rotate(180deg) scale(0.7);
}

.vs__clear {
    display: none;
}

.vs__open-indicator {
    position: absolute;
    right: 4px;
}


/* Transition for <TheFontFace/> list */
.font-face-list-move,
.font-face-move,

/* apply transition to moving elements */
.font-face-enter-active,
.font-face-leave-active {
    transition: all 0.5s ease;
}

.font-face-enter-from,
.font-face-leave-to {
    opacity: 0;
    transform: translateX(30px);
}

/* ensure leaving items are taken out of layout flow so that moving
   animations can be calculated correctly. */
/* .font-face-leave-active {
    position: absolute;
} */

/* Transition for the CSS Preview syntax highlight */
.css-preview-enter-active,
.css-preview-leave-active {
    transition: opacity 0.5s ease;
}

.css-preview-enter-from,
.css-preview-leave-to {
    opacity: 0;
}

.dragged-placeholder {
    opacity: 0.3;
}
</style>