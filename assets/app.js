import 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';

/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.scss';

document.addEventListener('DOMContentLoaded', ()=> {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl){
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });    
});
