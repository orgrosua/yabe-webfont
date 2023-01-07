import { createRouter, createWebHistory } from 'vue-router';

import NotFound from './pages/NotFound.vue';

import FontsBase from './pages/fonts/FontsBase.vue';
import FontsIndex from './pages/fonts/FontsIndex.vue';
import FontsCreate from './pages/fonts/FontsCreate.vue';
import FontsEdit from './pages/fonts/FontsEdit.vue';

const router = createRouter({
    history: createWebHistory(`${yabeWebfont.web_history}#/`),
    scrollBehavior(_, _2, savedPosition) {
        return savedPosition || { left: 0, top: 0 };
    },
    routes: [
        { path: '/', name: 'home', redirect: { name: 'fonts' } },
        {
            path: '/fonts',
            name: 'fonts',
            component: FontsBase,
            redirect: { name: 'fonts.index' },
            children: [
                {
                    path: 'index',
                    name: 'fonts.index',
                    component: FontsIndex,
                },
                {
                    path: 'create',
                    name: 'fonts.create',
                    // component: FontsIndex,
                    children: [
                        { path: 'custom', name: 'fonts.create.custom', component: FontsCreate },
                        { path: 'google-fonts', name: 'fonts.create.google-fonts', component: NotFound },
                    ]
                },
                {
                    path: 'edit/:id(\\d+)',
                    name: 'fonts.edit',
                    // component: FontsEdit,
                    children: [
                        { path: 'custom', name: 'fonts.edit.custom', component: FontsEdit },
                        { path: 'google-fonts', name: 'fonts.edit.google-fonts', component: NotFound },
                    ]
                }
            ],
        },
        {
            path: '/settings',
            name: 'settings',
            component: NotFound,
        },
        { path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFound },
    ]
});

export default router;