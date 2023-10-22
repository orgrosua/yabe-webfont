import { createRouter, createWebHistory } from 'vue-router';

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
            component: () => import('./pages/fonts/Base.vue'),
            redirect: { name: 'fonts.index' },
            children: [
                {
                    path: 'index',
                    name: 'fonts.index',
                    component: () => import('./pages/fonts/Index.vue'),
                },
                {
                    path: 'create',
                    name: 'fonts.create',
                    children: [
                        {
                            path: 'custom',
                            name: 'fonts.create.custom',
                            component: () => import('./pages/fonts/custom/Create.vue'),
                        },
                        {
                            path: 'google-fonts',
                            name: 'fonts.create.google-fonts',
                            component: () => import('./pages/fonts/google-fonts/Create.vue'),
                        },
                    ]
                },
                {
                    path: 'edit/:id(\\d+)',
                    name: 'fonts.edit',
                    children: [
                        {
                            path: 'custom',
                            name: 'fonts.edit.custom',
                            component: () => import('./pages/fonts/custom/Edit.vue'),
                        },
                        {
                            path: 'google-fonts',
                            name: 'fonts.edit.google-fonts',
                            component: () => import('./pages/fonts/google-fonts/Edit.vue'),
                        },
                    ]
                }
            ],
        },
        {
            path: '/settings',
            name: 'settings',
            component: () => import('./pages/settings/Index.vue'),
        },
        {
            path: '/migrations',
            name: 'migrations',
            component: () => import('./pages/migrations/Base.vue'),
            redirect: { name: 'migrations.index' },
            children: [
                {
                    path: 'index',
                    name: 'migrations.index',
                    component: () => import('./pages/migrations/Index.vue'),
                },
                {
                    path: 'custom-fonts-bricks',
                    name: 'migrations.custom-fonts-bricks',
                    component: () => import('./pages/migrations/CustomFontsBricks.vue'),
                },
                {
                    path: 'font-hero-dplugins',
                    name: 'migrations.font-hero-dplugins',
                    component: () => import('./pages/migrations/FontHeroDplugins.vue'),
                },
            ],
        },
        {
            path: '/:pathMatch(.*)*',
            name: 'NotFound',
            component: () => import('./pages/NotFound.vue'),
        },
    ]
});

export default router;