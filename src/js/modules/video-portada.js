/*
 * Portada de video (template-parts/components/video-portada.php): el iframe de Vimeo no está en el HTML.
 * Al pulsar play se crea en el mismo lugar (con autoplay) y recibe el foco; detenerVideos() lo quita y
 * vuelve a la portada (al cerrar un modal o al cambiar de diapositiva).
 */

export function detenerVideos(raiz) {
    raiz.querySelectorAll('[data-video] iframe').forEach((iframe) => {
        const contenedor = iframe.closest('[data-video]');
        iframe.remove();
        contenedor?.querySelectorAll('[data-video-oculto]').forEach((elemento) => {
            elemento.hidden = false;
            elemento.removeAttribute('data-video-oculto');
        });
    });
}

function reproducir(boton) {
    const contenedor = boton.closest('[data-video]');
    const src = boton.dataset.videoSrc;
    if (!contenedor || !src) return;

    const iframe = document.createElement('iframe');
    iframe.src = src;
    iframe.title = boton.dataset.videoTitulo || 'Video';
    iframe.allow = 'autoplay; fullscreen; picture-in-picture';
    iframe.allowFullscreen = true;
    iframe.referrerPolicy = 'strict-origin-when-cross-origin';
    iframe.className = 'absolute inset-0 size-full';

    // La portada y el botón se ocultan mientras suena el video.
    contenedor.querySelectorAll(':scope > img, :scope > [data-video-play]').forEach((elemento) => {
        elemento.hidden = true;
        elemento.setAttribute('data-video-oculto', '');
    });
    contenedor.append(iframe);
    iframe.focus();
}

export function initVideoPortada() {
    document.addEventListener('click', (evento) => {
        const boton = evento.target.closest('[data-video-play]');
        if (boton) reproducir(boton);
    });

    // Al cerrar un modal con video, el video se detiene.
    document.querySelectorAll('dialog[data-modal]').forEach((dialogo) => {
        if (dialogo.querySelector('[data-video]')) {
            dialogo.addEventListener('close', () => detenerVideos(dialogo));
        }
    });
}
