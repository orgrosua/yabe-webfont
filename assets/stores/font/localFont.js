import { defineStore } from 'pinia';
import { nanoid } from 'nanoid';

export const useLocalFontStore = defineStore('localFont', {
    state: () => ({
        /**
         * @type {{
         *   id: string, // nanoid
         *   weight: number, // 100, 200, 300, 400, 500, 600, 700, 800, 900, range<number number>
         *   width: number|string, // number, range<number number>
         *   style: string, // normal, italic, oblique
         *   display: ?string, // null, auto, block, swap, fallback, optional
         *   selector: ?string, // css selector
         *   comment: ?string,
         *   unicodeRange: ?string,
         *   files: {
         *     uid: string, // nanoid
         *     attachment_id: ?number, // attachment id
         *     attachment_url: string, // for preview only
         *     extension: string, // woff, woff2, ttf, otf, eot
         *     mime: string, // font/woff, font/woff2, font/ttf, font/otf, font/eot
         *     filesize: number, // bytes
         *     name: ?string, // filename without extension
         *   }[]
         * }[]} 
         */
        fontFaces: [],
    }),
    actions: {
        add() {
            this.fontFaces.unshift({
                id: nanoid(10),
                weight: 400,
                width: '',
                style: 'normal',
                display: '',
                selector: '',
                comment: '',
                unicodeRange: '',
                files: [],
            });
        },
        delete(id) {
            this.fontFaces = this.fontFaces.filter((fontFace) => fontFace.id !== id);
        },
        reset() {
            this.fontFaces = [];
        }
    },
});