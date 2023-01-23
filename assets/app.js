/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import 'highlight.js/styles/stackoverflow-light.css'
import 'vue-select/dist/vue-select.css';
import './styles/app.css';
import './styles/custom.scss';

require('./bootstrap');

import { __, _n, sprintf } from '@wordpress/i18n';
import { createApp } from 'vue';
import { createPinia } from 'pinia';

import App from './App.vue';
import router from './router.js';

import hljs from 'highlight.js/lib/core';
import hljsCssLang from 'highlight.js/lib/languages/css';
import hljsVuePlugin from '@highlightjs/vue-plugin';
import vRipple from './directives/ripple/ripple.js';
import VueSelect from 'vue-select';
import draggable from 'zhyswan-vuedraggable';

hljs.registerLanguage('css', hljsCssLang);

const pinia = createPinia();
const app = createApp(App);

app.config.globalProperties.__ = __;
app.config.globalProperties._n = _n;
app.config.globalProperties.sprintf = sprintf;
app.config.globalProperties.yabeWebfont = window.yabeWebfont;

app
    .use(pinia)
    .use(router)
    .use(hljsVuePlugin);

app
    .component('VueSelect', VueSelect)
    .component('Draggable', draggable);

app.directive('ripple', vRipple);

app.mount('#yabe-webfont-app');