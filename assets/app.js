/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.scss in this case)
import './styles/scss/app.scss';
import './styles/scss/tablet.scss';
import './styles/scss/mobile.scss';

// start the Stimulus application
import './bootstrap';

const $ = require('jquery');
global.$ = global.jQuery = $;

import '../assets/js/registration';
import '../assets/js/iframe';
import '../assets/js/game-handler';
import '../assets/js/mobileHelper';