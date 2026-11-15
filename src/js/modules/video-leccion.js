/*
 * Video de la lección (template-parts/pages/leccion/video.php): el reproductor del plugin viene en un
 * <template>; al pulsar play se pone en el marco con autoplay y recibe el foco. Hasta entonces no hay
 * ninguna petición al proveedor del video. Se activa con [data-video-leccion].
 */

export function initVideoLeccion() {
    for (const marco of document.querySelectorAll('[data-video-leccion]')) {
        const plantilla = marco.querySelector('template[data-video-leccion-plantilla]');
        const boton = marco.querySelector('[data-video-leccion-play]');
        if (!plantilla || !boton) continue;

        boton.addEventListener('click', () => {
            const iframe = plantilla.content.querySelector('iframe')?.cloneNode(true);
            if (!iframe) return;

            const url = new URL(iframe.getAttribute('src'), location.href);
            url.searchParams.set('autoplay', '1');
            iframe.src = url.toString();
            iframe.removeAttribute('loading');

            boton.replaceWith(iframe);
            iframe.focus();
        });
    }
}
