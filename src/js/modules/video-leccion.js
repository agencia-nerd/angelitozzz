/*
 * Video de la lección (template-parts/pages/leccion/video.php, specs/leccion.md → video). El reproductor
 * del plugin (angelitozzz_cursos_video_html(): [data-angelitozzz-video] con el iframe de Vimeo o el
 * <video> firmado, la marca de agua y el botón "Pantalla completa") viene en un <template>: hasta pulsar
 * play solo se pidió la portada. Al pulsar:
 * - se pone el reproductor en el marco, empieza a reproducir y recibe el foco (Vimeo, con autoplay=1);
 * - con Vimeo (F5-e): el iframe no trae allowfullscreen, así la pantalla completa es la del marco con la
 *   marca. Si el navegador no puede poner el marco en pantalla completa (iPhone), se le da
 *   allowfullscreen para que funcione la de Vimeo (ahí la marca no se ve, como con el <video>) y se
 *   quita el botón propio;
 * - la marca de agua cambia de esquina cada 30 s (los dos tipos).
 * Solo con el <video> (archivo privado):
 * - "Pantalla completa" (y el doble clic) pone en pantalla completa el marco entero, para que la marca
 *   siga a la vista; en iPhone, que solo deja hacerlo con el <video>, se usa la del <video> (ahí la marca
 *   no se ve: límite aceptado en el spec). Si el navegador pone el <video> solo en pantalla completa con
 *   su propio botón (Firefox no respeta controlslist="nofullscreen"), se pasa al marco entero;
 * - el clic derecho no abre el menú;
 * - si la dirección firmada venció (4 h) o falla, se pide una nueva a la ruta REST del plugin
 *   (GET angelitozzz/v1/lecciones/<id>/video) y se sigue en la misma posición; como mucho un intento
 *   por minuto, y si no se puede, un aviso.
 * Se activa con [data-video-leccion].
 */

const ESQUINAS = ['arriba-izquierda', 'arriba-derecha', 'abajo-derecha', 'abajo-izquierda'];
const CADA_ESQUINA = 30_000;
const ENTRE_INTENTOS = 60_000;

/** Milisegundos en que vence una dirección firmada (su parámetro `e`), o 0. */
const venceEn = (src) => {
    try {
        return Number(new URL(src, location.href).searchParams.get('e') || 0) * 1000;
    } catch {
        return 0;
    }
};

const pantallaCompleta = () => document.fullscreenElement || document.webkitFullscreenElement || null;

export function initVideoLeccion() {
    for (const marco of document.querySelectorAll('[data-video-leccion]')) {
        const plantilla = marco.querySelector('template[data-video-leccion-plantilla]');
        const boton = marco.querySelector('[data-video-leccion-play]');
        if (!plantilla || !boton) continue;

        boton.addEventListener('click', () => iniciar(marco, plantilla, boton), { once: true });
    }
}

/** Si el navegador puede poner un elemento (el marco) en pantalla completa. */
const puedePantallaDelMarco = (marco) =>
    Boolean((marco.requestFullscreen || marco.webkitRequestFullscreen) && (document.fullscreenEnabled ?? document.webkitFullscreenEnabled ?? true));

async function iniciar(marco, plantilla, boton) {
    const reproductor = plantilla.content.querySelector('[data-angelitozzz-video]')?.cloneNode(true);
    if (!reproductor) return;

    const iframe = reproductor.querySelector('iframe');
    if (iframe) {
        iniciarVimeo(marco, reproductor, iframe, boton);
        return;
    }

    const video = reproductor.querySelector('video');
    if (!video) return;

    const aviso = marco.querySelector('[data-video-leccion-error]');
    const datos = marco.dataset;
    let ultimoIntento = 0;

    const mostrarError = () => {
        if (!aviso) return;
        aviso.textContent = datos.textoError || '';
        aviso.hidden = false;
    };

    /** Dirección nueva del video (o null si no se pudo). */
    const direccionNueva = async () => {
        ultimoIntento = Date.now();
        try {
            const respuesta = await fetch(datos.videoLeccionUrl, {
                credentials: 'same-origin',
                headers: datos.videoLeccionNonce ? { 'X-WP-Nonce': datos.videoLeccionNonce } : {},
                cache: 'no-store',
            });
            const json = await respuesta.json().catch(() => ({}));
            return respuesta.ok && json.url ? json.url : null;
        } catch {
            return null;
        }
    };

    // Si la dirección de la página ya venció (o está por vencer), se pide otra antes de empezar.
    if (venceEn(video.getAttribute('src')) < Date.now() + 60_000) {
        const url = await direccionNueva();
        if (url) video.src = url;
    }

    // Si se estaba reproduciendo y dónde (tras un error, `paused` y `currentTime` ya no lo dicen): se
    // recuerda la última posición con datos cargados.
    let reproduciendo = true;
    let ultimaPosicion = 0;
    video.addEventListener('play', () => (reproduciendo = true));
    video.addEventListener('pause', () => {
        if (!video.error) reproduciendo = false;
    });
    video.addEventListener('timeupdate', () => {
        if (video.readyState >= 2 && !video.error) ultimaPosicion = video.currentTime;
    });

    // Renovar si falla, en la misma posición y con el mismo estado.
    video.addEventListener('error', async () => {
        if (Date.now() - ultimoIntento < ENTRE_INTENTOS) {
            mostrarError();
            return;
        }
        const posicion = ultimaPosicion;
        const seguia = reproduciendo;
        const url = await direccionNueva();
        if (!url) {
            mostrarError();
            return;
        }
        video.addEventListener(
            'loadedmetadata',
            () => {
                if (posicion > 0) video.currentTime = posicion;
                if (seguia) video.play().catch(() => {});
            },
            { once: true },
        );
        video.src = url;
        video.load();
    });

    marco.addEventListener('contextmenu', (evento) => evento.preventDefault());

    moverMarca(reproductor);

    // Pantalla completa del marco entero.
    const { pantalla, alternarPantalla, textoEntrar } = pantallaDelMarco(marco, reproductor, () => video.webkitEnterFullscreen?.());
    const alCambiar = () => {
        // Firefox no respeta controlslist="nofullscreen": su botón pone en pantalla completa el <video>
        // solo, sin la marca de agua. Se pasa al marco entero; si el navegador no lo deja, se sale.
        if (pantallaCompleta() === video) {
            const salir = (document.exitFullscreen || document.webkitExitFullscreen)?.call(document);
            Promise.resolve(salir)
                .catch(() => {})
                .then(() => marco.requestFullscreen?.())
                .catch(() => {});
            return;
        }
        if (pantalla) pantalla.textContent = pantallaCompleta() === marco ? datos.textoSalir || textoEntrar : textoEntrar;
    };
    video.addEventListener('dblclick', (evento) => {
        evento.preventDefault();
        alternarPantalla();
    });
    document.addEventListener('fullscreenchange', alCambiar);
    document.addEventListener('webkitfullscreenchange', alCambiar);

    marco.querySelector('[data-video-leccion-portada]')?.remove();
    boton.replaceWith(reproductor);
    video.focus();
    video.play().catch(() => {});
}

/** Marca de agua: cambia de esquina cada 30 s. */
function moverMarca(reproductor) {
    const marca = reproductor.querySelector('[data-angelitozzz-video-marca]');
    if (!marca) return;
    let esquina = 0;
    marca.dataset.esquina = ESQUINAS[esquina];
    setInterval(() => {
        esquina = (esquina + 1) % ESQUINAS.length;
        marca.dataset.esquina = ESQUINAS[esquina];
    }, CADA_ESQUINA);
}

/**
 * El botón "Pantalla completa" pone el marco entero (con la marca); si el navegador no puede, llama a
 * `sinMarco` (el <video> en iPhone).
 */
function pantallaDelMarco(marco, reproductor, sinMarco) {
    const pantalla = reproductor.querySelector('[data-angelitozzz-video-pantalla]');
    const textoEntrar = pantalla?.textContent || '';
    const alternarPantalla = () => {
        if (pantallaCompleta() === marco) {
            (document.exitFullscreen || document.webkitExitFullscreen)?.call(document);
        } else if (marco.requestFullscreen) {
            marco.requestFullscreen().catch(() => {});
        } else if (marco.webkitRequestFullscreen) {
            marco.webkitRequestFullscreen();
        } else {
            sinMarco();
        }
    };
    pantalla?.addEventListener('click', alternarPantalla);
    return { pantalla, alternarPantalla, textoEntrar };
}

/** Reproductor de Vimeo (F5-e): el iframe del plugin con autoplay, la marca y la pantalla del marco. */
function iniciarVimeo(marco, reproductor, iframe, boton) {
    try {
        const url = new URL(iframe.getAttribute('src'), location.href);
        url.searchParams.set('autoplay', '1');
        iframe.src = url.toString();
    } catch {
        return;
    }
    iframe.removeAttribute('loading');

    if (puedePantallaDelMarco(marco)) {
        const { pantalla, textoEntrar } = pantallaDelMarco(marco, reproductor, () => {});
        const alCambiar = () => {
            if (pantalla) pantalla.textContent = pantallaCompleta() === marco ? marco.dataset.textoSalir || textoEntrar : textoEntrar;
        };
        document.addEventListener('fullscreenchange', alCambiar);
        document.addEventListener('webkitfullscreenchange', alCambiar);
    } else {
        // iPhone: solo la pantalla completa de Vimeo (sin la marca, límite aceptado en el spec).
        iframe.setAttribute('allow', 'autoplay; fullscreen; picture-in-picture');
        iframe.setAttribute('allowfullscreen', '');
        reproductor.querySelector('[data-angelitozzz-video-pantalla]')?.remove();
    }

    moverMarca(reproductor);

    marco.querySelector('[data-video-leccion-portada]')?.remove();
    boton.replaceWith(reproductor);
    iframe.focus();
}
