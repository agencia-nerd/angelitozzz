import { initMenu } from './modules/menu.js';
import { initTestimoniosSlider } from './modules/testimonios-slider.js';
import { initCarruselTarjetas } from './modules/carrusel-tarjetas.js';

initMenu();
initTestimoniosSlider();
initCarruselTarjetas();

if (document.querySelector('form[data-form]')) {
    import('./modules/form.js').then(({ initForms }) => initForms()).catch(console.error);
}

if (document.querySelector('dialog[data-modal]')) {
    import('./modules/modal.js').then(({ initModals }) => initModals()).catch(console.error);
    import('./modules/modal-coach.js').then(({ initModalCoach }) => initModalCoach()).catch(console.error);
}
