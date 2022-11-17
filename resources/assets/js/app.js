require('./bootstrap');
require('select2');

import { library } from '@fortawesome/fontawesome-svg-core';
import { faArrowRight, faBookReader, faCheck, faUserSecret, faFutbol, faTrain, faListAlt, faPlane, faHome } from '@fortawesome/free-solid-svg-icons';
import { faFacebook, faFacebookSquare, faInstagramSquare, faTwitterSquare } from '@fortawesome/free-brands-svg-icons';
import Chart from 'chart.js/auto';

window.Chart = Chart;


library.add(
    faArrowRight,
    faBookReader,
    faCheck,
    faUserSecret,
    faFutbol,
    faTrain,
    faListAlt,
    faPlane,
    faHome,
    faFacebook,
    faFacebookSquare,
    faTwitterSquare,
    faInstagramSquare,
);
