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

if (document.querySelector('[data-query="resultados"]')) {
    import('./modules/query.js').then(({ initQuery }) => initQuery()).catch(console.error);
}

if (document.querySelector('[data-accordion]')) {
    import('./modules/accordion.js').then(({ initAccordions }) => initAccordions()).catch(console.error);
}

if (document.querySelector('[data-pastillas]')) {
    import('./modules/pastillas.js').then(({ initPastillas }) => initPastillas()).catch(console.error);
}

if (document.querySelector('[data-ver-mas]')) {
    import('./modules/ver-mas.js').then(({ initVerMas }) => initVerMas()).catch(console.error);
}

if (document.querySelector('[data-testimonio-destacado-slider]')) {
    import('./modules/testimonio-destacado.js').then(({ initTestimonioDestacado }) => initTestimonioDestacado()).catch(console.error);
}

if (document.querySelector('[data-solucion-galeria]')) {
    import('./modules/solucion-galeria.js').then(({ initSolucionGaleria }) => initSolucionGaleria()).catch(console.error);
}

if (document.querySelector('[data-video-play]')) {
    import('./modules/video-portada.js').then(({ initVideoPortada }) => initVideoPortada()).catch(console.error);
}

if (document.querySelector('[data-solucion-agregar]')) {
    import('./modules/solucion-compra.js').then(({ initSolucionCompra }) => initSolucionCompra()).catch(console.error);
}

if (document.querySelector('[data-reglas-contrasena]')) {
    import('./modules/reglas-contrasena.js').then(({ initReglasContrasena }) => initReglasContrasena()).catch(console.error);
}

if (document.querySelector('[data-copiar-codigo]')) {
    import('./modules/copiar-codigo.js').then(({ initCopiarCodigo }) => initCopiarCodigo()).catch(console.error);
}

if (document.querySelector('form[data-enviar-una-vez]')) {
    import('./modules/enviar-una-vez.js').then(({ initEnviarUnaVez }) => initEnviarUnaVez()).catch(console.error);
}

if (document.querySelector('[data-ver-contrasena]')) {
    import('./modules/ver-contrasena.js').then(({ initVerContrasena }) => initVerContrasena()).catch(console.error);
}

if (document.querySelector('[data-pestanas]')) {
    import('./modules/pestanas.js').then(({ initPestanas }) => initPestanas()).catch(console.error);
}

if (document.querySelector('[data-perfil]')) {
    import('./modules/perfil.js').then(({ initPerfil }) => initPerfil()).catch(console.error);
}

if (document.querySelector('[data-leccion-completar]')) {
    import('./modules/leccion-completar.js').then(({ initLeccionCompletar }) => initLeccionCompletar()).catch(console.error);
}

if (document.querySelector('[data-video-leccion]')) {
    import('./modules/video-leccion.js').then(({ initVideoLeccion }) => initVideoLeccion()).catch(console.error);
}
