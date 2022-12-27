import { defineStore } from 'pinia';
import { nanoid } from 'nanoid';

export const useLocalFontStore = defineStore('localFont', {
    state: () => ({
        fontFaces: []
    }),
    actions: {
        addFontFace() {
            this.fontFaces.unshift({
                id: nanoid(10),
                weight: 400,
                style: 'normal',
                display: 'auto',
                selector: '',
                comment: '',
                unicodeRange: '',
                files: [
                    // {
                    //     format: string (woff, woff2, ttf, otf, svg, eot),
                    //     attachment_id: ?int,
                    //     attachment_url: string, // backup, url to file of attachment — to check if the input:text is external url or coming from current attachment
                    //     url: ?string, // font-face src:url(),
                    // }
                ],
            });
        },
        deleteFontFace(id) {
            this.fontFaces = this.fontFaces.filter((fontFace) => fontFace.id !== id);
        },
    }
});


/*

font = {
    weight: int (100, 200, 300, 400, 500, 600, 700, 800, 900, range<int, int>),
    style: string (normal, italic, oblique),
        display: string (auto, block, swap, fallback, optional),
        selector: string (css selector),
        comment: ?string,
        unicodeRange: ?string,
        file: {
            {
                format: string (woff, woff2, ttf, otf, svg, eot),
                attachment_id: ?int,
                attachment_url: string, // backup, url to file of attachment — to check if the input:text is external url or coming from current attachment
                url: ?string, // font-face src:url(),
            },

        },
}

*/