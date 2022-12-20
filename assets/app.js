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

import App from './App.vue';
import router from './router.js';

import vRipple from './components/ripple/ripple.js';

const app = createApp(App);
app.config.globalProperties.__ = __;
app.config.globalProperties.yabeWebfont = window.yabeWebfont;
app.use(router);

app.directive('ripple', vRipple);

app.mount('#yabe-webfont-app');