<template>
    <button type="button" @click="uploadFonts" v-ripple class="button tw-my-4">Bulk upload</button>
</template>

<script setup>
import { inject } from 'vue';
import { nanoid } from 'nanoid';
import sortBy from 'lodash-es/sortBy';

import { useLocalFontStore } from '../../../stores/font/localFont';
const store = useLocalFontStore();

const props = defineProps({
    fontFaces: {
        type: Array,
        required: true,
    },
});

const family = inject('fontFamily');

let mediaFrame = null;

const formatPrecedence = {
    'woff2': 1,
    'woff': 2,
    'ttf': 3,
    'otf': 4,
    'eot': 5,
};

const weightPatterns = [
    // more specific
    { 'key': 200, 'value': /[ \-]?(200|((extra|ultra)\-?light))/i },
    { 'key': 800, 'value': /[ \-]?(800|((extra|ultra)\-?bold))/i },
    { 'key': 600, 'value': /[ \-]?(600|([ds]emi(\-?bold)?))/i },
    // less specific
    { 'key': 100, 'value': /[ \-]?(100|thin)/i },
    { 'key': 300, 'value': /[ \-]?(300|light)/i },
    { 'key': 400, 'value': /[ \-]?(400|normal|regular|book)/i },
    { 'key': 500, 'value': /[ \-]?(500|medium)/i },
    { 'key': 700, 'value': /[ \-]?(700|bold)/i },
    { 'key': 900, 'value': /[ \-]?(900|black|heavy)/i },
    { 'key': '100 900', 'value': /[ \-]?(VariableFont|\[wght\])/i },
];

const stylePatterns = [
    { 'key': 'italic', 'value': /[ \-]?(italic)/i },
    { 'key': 'oblique', 'value': /[ \-]?(oblique)/i },
    { 'key': 'normal', 'value': /[ \-]?(normal)/i },
];

function uploadFonts(e) {
    e.preventDefault();

    if (mediaFrame) {
        mediaFrame.open();
        return;
    }

    mediaFrame = wp.media.frames.file_frame = wp.media({
        title: 'Upload Fonts',
        multiple: true,
        library: {
            type: 'font',
            uploadedTo: null, // Attached to a specific post (ID).
        }
    });

    mediaFrame.on('ready', () => {
        _wpPluploadSettings.defaults.filters.mime_types[0].extensions = 'woff2,woff,ttf,otf,eot';
    });

    mediaFrame.on('insert select', () => {
        let attachments = mediaFrame.state().get('selection').toJSON();

        let selectedMediaFiles = [];

        attachments.forEach(attachment => {
            selectedMediaFiles.push({
                uid: nanoid(10),
                attachment_id: attachment.id,
                attachment_url: attachment.url,
                extension: attachment.subtype,
                mime: attachment.mime,
                filesize: attachment.filesizeInBytes,
                name: attachment.filename.replace(/\.[^/.]+$/, ''),
            });
        });

        selectedMediaFiles = sortBy(selectedMediaFiles, (f) => formatPrecedence[f.extension]);

        let fontFamily = null;

        selectedMediaFiles.forEach(element => {
            let weight = 400;
            let style = 'normal';
            fontFamily = element.name;

            for (let i = 0; i < weightPatterns.length; i++) {
                if (fontFamily.match(weightPatterns[i].value)) {
                    weight = weightPatterns[i].key;
                    fontFamily = fontFamily.replace(weightPatterns[i].value, '');
                    break;
                }
            }

            for (let i = 0; i < stylePatterns.length; i++) {
                if (fontFamily.match(stylePatterns[i].value)) {
                    style = stylePatterns[i].key;
                    fontFamily = fontFamily.replace(stylePatterns[i].value, '');
                    break;
                }
            }

            let fontFace = props.fontFaces.find((f) => {
                return f.weight === weight && f.style === style;
            });

            if (!fontFace) {
                fontFace = store.add({
                    weight: weight,
                    style: style,
                });
            }

            fontFace.files.push(element);
        });


        if (family.value === '' && fontFamily !== null) {
            family.value = fontFamily;
        }
    });

    mediaFrame.open();
    mediaFrame.uploader.uploader.param('yabe_webfont_font_upload', true);
}
</script>