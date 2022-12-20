import { createRouter, createWebHistory } from "vue-router";

import NotFound from "./pages/NotFound.vue";

import FontsBase from './pages/fonts/FontsBase.vue';
import FontsIndex from './pages/fonts/FontsIndex.vue';
import FontsCreate from './pages/fonts/FontsCreate.vue';

const router = createRouter({
    history: createWebHistory(`${yabeWebfont.web_history}#/`),
    scrollBehavior(_, _2, savedPosition) {
        return savedPosition || { left: 0, top: 0 };
    },
    routes: [
        { path: '/', redirect: { name: 'fonts' } },
        {
            path: '/fonts',
            name: 'fonts',
            component: FontsBase,
            redirect: { name: 'fonts.index' },
            children: [
                { path: 'index', name: 'fonts.index', component: FontsIndex },
                {
                    path: 'create',
                    name: 'fonts.create',
                    // component: FontsIndex,
                    children: [
                        { path: 'custom', name: 'fonts.create.custom', component: FontsCreate },
                        { path: 'google-fonts', name: 'fonts.create.google-fonts', component: FontsIndex },
                    ]
                },
            ],
        },
        {
            path: '/settings',
            name: 'settings',
        },
        { path: '/:notFound(.*)', component: NotFound },
    ]
});

export default router;