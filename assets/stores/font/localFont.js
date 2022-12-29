import { defineStore } from 'pinia';
import { nanoid } from 'nanoid';

export const useLocalFontStore = defineStore('localFont', {
    state: () => ({
        fontFaces: [
            /* 
            {
                id: string (nanoid),
                weight: int (100, 200, 300, 400, 500, 600, 700, 800, 900, range<int int>),
                style: string (normal, italic, oblique),
                display: ?string (null, auto, block, swap, fallback, optional),
                selector: ?string (css selector),
                comment: ?string,
                unicodeRange: ?string,
                files: {
                    {
                        uid: string (nanoid),
                        attachment_id: ?int,
                        attachment_url: string, // for preview only
                        extension: string (woff, woff2, ttf, otf, eot),
                        mime: string (font/woff, font/woff2, font/ttf, font/otf, font/eot),
                        filesize: int (bytes),
                        name: ?string (filename without extension),
                    },

                },
            }
            */
        ]
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
                files: [],
            });
        },
        deleteFontFace(id) {
            this.fontFaces = this.fontFaces.filter((fontFace) => fontFace.id !== id);
        },
    },
});