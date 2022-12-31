import { defineStore } from 'pinia';
import { nanoid } from 'nanoid';

export const useWordpressNotice = defineStore('wordpressNotice', {
    state: () => ({
        notices: []
    }),
    actions: {
        /**
         * @param {object} notice
         * @param {string} notice.message
         * @param {string} notice.type - 'success', 'info', 'warning', 'error'
         * @param {object} notice.options
         */
        add(notice) {
            this.notices.unshift({
                id: nanoid(10),
                ...notice,
            });
        },
        remove(id) {
            this.notices = this.notices.filter((notice) => notice.id !== id);
        }
    },
});