/*
 * Portada de video (template-parts/components/video-portada.php; specs/_shared.md → video-portada): el
 * <video> no está en el HTML, así el archivo no se descarga hasta pulsar play. Al pulsar se crea en el
 * mismo lugar (con autoplay y la portada como poster) y recibe el foco; detenerVideos() lo detiene, lo
 * quita y vuelve a la portada (al cerrar un modal o al cambiar de diapositiva).
 */

export function detenerVideos(raiz) {
    raiz.querySelectorAll('[data-video] video').forEach((video) => {
        const contenedor = video.closest('[data-video]');
        video.pause();
        video.removeAttribute('src');
        video.querySelectorAll('source').forEach((source) => source.remove());
        video.load(); // Corta la descarga que estuviera en curso.
        video.remove();
        contenedor?.querySelectorAll('[data-video-oculto]').forEach((elemento) => {
            elemento.hidden = false;
            elemento.removeAttribute('data-video-oculto');
        });
    });
}

function reproducir(boton) {
    const contenedor = boton.closest('[data-video]');
    const src = boton.dataset.videoSrc;
    if (!contenedor || !src || contenedor.querySelector('video')) return;

    const video = document.createElement('video');
    video.controls = true;
    video.playsInline = true;
    video.autoplay = true;
    video.preload = 'auto';
    video.setAttribute('aria-label', boton.dataset.videoTitulo || 'Video');
    video.className = 'absolute inset-0 size-full bg-tinta object-contain';
    const portada = contenedor.querySelector(':scope > img');
    if (portada?.currentSrc || portada?.src) video.poster = portada.currentSrc || portada.src;

    const source = document.createElement('source');
    source.src = src;
    if (boton.dataset.videoTipo) source.type = boton.dataset.videoTipo;
    video.append(source);

    // La portada y el botón se ocultan mientras suena el video.
    contenedor.querySelectorAll(':scope > img, :scope > [data-video-play]').forEach((elemento) => {
        elemento.hidden = true;
        elemento.setAttribute('data-video-oculto', '');
    });
    contenedor.append(video);
    video.focus();
    // Si el navegador no deja empezar solo, quedan los controles para darle play.
    video.play()?.catch(() => {});
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
