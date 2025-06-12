import * as bootstrap from 'bootstrap/dist/js/bootstrap.bundle.min.js';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-icons/font/bootstrap-icons.css';

window.bootstrap = bootstrap;



console.log('app.js charger');

import './styles/app.scss';

document.addEventListener('DOMContentLoaded', ()=> {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl){
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });    
});
