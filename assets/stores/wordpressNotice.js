import { defineStore } from 'pinia';
import { nanoid } from 'nanoid';

export const useWordpressNotice = defineStore('wordpressNotice', {
    state: () => ({
        notices: []
    }),
    actions: {
        /**
         * @param {string} type success, error, warning, info
         */
        add(type, message, options = {}) {
            this.notices.unshift({
                id: nanoid(10),
                type,
                message,
                options,
            });
        },
        remove(id) {
            this.notices = this.notices.filter((notice) => notice.id !== id);
        }
    },
});