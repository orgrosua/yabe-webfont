/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

require('./bootstrap');

import { __ } from '@wordpress/i18n';
import { createApp } from 'vue';
import { createPinia } from 'pinia';

import App from './App.vue';
import router from './router.js';

import 'highlight.js/styles/stackoverflow-light.css'
import hljs from 'highlight.js/lib/core';
import hljsCssLang from 'highlight.js/lib/languages/css';
import hljsVuePlugin from "@highlightjs/vue-plugin";
import vRipple from './directives/ripple/ripple.js';

const pinia = createPinia();
const app = createApp(App);
app.config.globalProperties.__ = __;
app.config.globalProperties.yabeWebfont = window.yabeWebfont;

app.use(pinia);
app.use(router);

hljs.registerLanguage('css', hljsCssLang);
app.use(hljsVuePlugin);

app.directive('ripple', vRipple);

app.mount('#yabe-webfont-app');