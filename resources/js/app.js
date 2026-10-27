import './bootstrap';
import * as bootstrap from 'bootstrap';
import { Chart, registerables } from 'chart.js';
import Alpine from 'alpinejs';

Chart.register(...registerables);

window.bootstrap = bootstrap;
window.Chart = Chart;
window.Alpine = Alpine;

Alpine.start();
