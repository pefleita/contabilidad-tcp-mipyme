import './bootstrap';

import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';
import $ from 'jquery';
import select2Factory from 'select2';
import 'select2/dist/css/select2.min.css';

window.Alpine = Alpine;
window.Chart = Chart;
window.$ = window.jQuery = $;

select2Factory(window, $);

Alpine.start();
