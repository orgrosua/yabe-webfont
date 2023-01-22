import { createRouter, createWebHistory } from 'vue-router';

import NotFound from './pages/NotFound.vue';

import FontsBase from './pages/fonts/Base.vue';
import FontsIndex from './pages/fonts/Index.vue';
import FontsCustomCreate from './pages/fonts/custom/Create.vue';
import FontsCustomEdit from './pages/fonts/custom/Edit.vue';
import FontsGoogleFontsCreate from './pages/fonts/google-fonts/Create.vue';
import FonstGoogleFontsEdit from './pages/fonts/google-fonts/Edit.vue';

import SettingsIndex from './pages/settings/Index.vue';

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
                        { path: 'custom', name: 'fonts.create.custom', component: FontsCustomCreate },
                        { path: 'google-fonts', name: 'fonts.create.google-fonts', component: FontsGoogleFontsCreate },
                    ]
                },
                {
                    path: 'edit/:id(\\d+)',
                    name: 'fonts.edit',
                    // component: FontsEdit,
                    children: [
                        { path: 'custom', name: 'fonts.edit.custom', component: FontsCustomEdit },
                        { path: 'google-fonts', name: 'fonts.edit.google-fonts', component: FonstGoogleFontsEdit },
                    ]
                }
            ],
        },
        {
            path: '/settings',
            name: 'settings',
            component: SettingsIndex,
        },
        { path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFound },
    ]
});

export default router;