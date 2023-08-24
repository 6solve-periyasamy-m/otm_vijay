import {library} from '@fortawesome/fontawesome-svg-core';
import {
    faArrowRight,
    faBookReader,
    faCheck,
    faFutbol,
    faHome,
    faListAlt,
    faPlane,
    faTrain,
    faUserSecret
} from '@fortawesome/free-solid-svg-icons';
import {faFacebook, faFacebookSquare, faInstagramSquare, faTwitterSquare} from '@fortawesome/free-brands-svg-icons';
import Chart from 'chart.js/auto';
import {format} from 'date-fns';

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

window.formatDate = function (date, dateFormat, preformatted = false) {
    if (!preformatted) {
        dateFormat = phpToFormat(dateFormat);
    }
    return format(date, dateFormat);
}

function phpToFormat(format) {
    // Not a complete replacement, but replaces all the ones used in settings
    return format
        .replaceAll('d', 'dd') // 'd' in php has a leading 0
        .replaceAll('M', 'MMM') // 'M' is 3 letter month
        .replaceAll('F', 'MMMM') // Full month name
        .replaceAll('m', 'MM') // Again, trailing 0, and capitalized
        .replaceAll('Y', 'y') // PHP 'Y' shows all 4 digits
        .replaceAll('jS', 'do') // Day of the month with st/nd/rd
        .replaceAll('i', 'mm') // Minute of the hour, 2 digits
        .replaceAll('s', 'ss') // Second of the minute, 2 digits
        .replaceAll('A', 'b'); // AM or PM
}
