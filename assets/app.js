/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// import './public-path.js';

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
import './master.css.js';

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
import InlineSvg from 'vue-inline-svg';
import FloatingVue from 'floating-vue';
import { FontAwesomeIcon } from './font-awesome.js';

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
    .use(hljsVuePlugin)
    .use(FloatingVue, {
        container: '#webfont-app',
    });

app
    .component('font-awesome-icon', FontAwesomeIcon)
    .component('inline-svg', InlineSvg)
    .component('VueSelect', VueSelect)
    .component('Draggable', draggable);

app.directive('ripple', vRipple);

app.mount('#webfont-app');