// Video y materiales de la lección (F5-d, bloque 4b; specs/leccion.md → video, temario y pestanas).
//
//   node cdp-leccion-video.mjs video              arma en %TEMP% un MP4 real de 20 s (canvas + MediaRecorder de
//                                                 este mismo Chrome) y su portada PNG; después: datos-f5.php crear
//   node cdp-leccion-video.mjs [carpeta-capturas] la prueba (necesita datos-f5.php crear con ese video)
//
// Comprueba: al abrir solo se pide la portada; play reproduce por pedazos (206) y se puede adelantar; sin descarga
// ni menú del clic derecho; la marca de agua con el correo cambia de esquina a los 30 s y sigue a la vista en la
// pantalla completa del marco; si un pedazo falla (403), el JS pide una dirección nueva y sigue en la misma posición;
// la dirección copiada no sirve sin la sesión ni después de cerrarla; íconos por tipo y <audio> solo en el MP3; la
// vista previa sin sesión tiene video sin marca. Puerto 9351.
import { writeFileSync, existsSync, mkdirSync } from 'node:fs';
import { tmpdir } from 'node:os';
import { join } from 'node:path';
import { abrir, sitio, sleep } from './lib/cdp.mjs';

const VIDEO = join(tmpdir(), 'angelitozzz-video-prueba.mp4');
const PORTADA = join(tmpdir(), 'angelitozzz-video-prueba.png');
const ALUMNA = 'prueba-f5@angelitozzz.local';
const LECCION = '/cursos/curso-de-prueba-f5/video-1-bienvenida/';

const c = await abrir({ puerto: 9351 });

if (process.argv[2] === 'video') {
    await c.ir('/');
    const r = await c.ev(`
        const cv = document.createElement('canvas');
        cv.width = 960; cv.height = 540;
        const g = cv.getContext('2d');
        const t0 = performance.now();
        let cuadros = 0, raf = 0, portada = '';
        const pintar = () => {
            const s = (performance.now() - t0) / 1000;
            g.fillStyle = 'hsl(' + Math.round(s * 18) + ' 35% 40%)';
            g.fillRect(0, 0, 960, 540);
            // Ruido: sube el peso del video para que el servidor lo entregue en varios pedazos de 4 MB.
            for (let i = 0; i < 1500; i++) {
                g.fillStyle = 'rgba(255,255,255,' + Math.random() * 0.25 + ')';
                g.fillRect(Math.random() * 960, Math.random() * 540, 20, 20);
            }
            g.fillStyle = '#fff';
            g.font = 'bold 96px sans-serif';
            g.fillText(s.toFixed(1) + ' s', 340, 300);
            if (++cuadros === 2) portada = cv.toDataURL('image/png');
            raf = requestAnimationFrame(pintar);
        };
        pintar();
        const mr = new MediaRecorder(cv.captureStream(30), { mimeType: 'video/mp4;codecs=avc1', videoBitsPerSecond: 8000000 });
        const partes = [];
        mr.ondataavailable = (e) => partes.push(e.data);
        mr.start(1000);
        await new Promise((r) => setTimeout(r, 20000));
        mr.stop();
        await new Promise((r) => (mr.onstop = r));
        cancelAnimationFrame(raf);
        // Los bytes quedan en la página y se traen por trozos: un solo valor de varios MB cuelga CDP.
        window.__video = new Uint8Array(await new Blob(partes).arrayBuffer());
        return { tam: window.__video.length, portada: portada.split(',')[1] || cv.toDataURL('image/png').split(',')[1] };
    `);
    if (!r?.tam) {
        console.log(JSON.stringify({ ok: false, error: r?.error || 'MediaRecorder no devolvió el video' }));
        c.cerrar();
        process.exit(1);
    }
    const trozos = [];
    for (let i = 0; i < r.tam; i += 512 * 1024) {
        const b64 = await c.ev(`const t = window.__video.subarray(${i}, ${i + 512 * 1024}); let s = ''; for (let j = 0; j < t.length; j += 32768) s += String.fromCharCode(...t.subarray(j, j + 32768)); return btoa(s);`);
        trozos.push(Buffer.from(b64, 'base64'));
    }
    writeFileSync(VIDEO, Buffer.concat(trozos));
    writeFileSync(PORTADA, Buffer.from(r.portada, 'base64'));
    console.log(JSON.stringify({ ok: true, video: VIDEO, bytes: r.tam, portada: PORTADA, siguiente: 'php zz-datos-f5.php borrar && php zz-datos-f5.php crear' }));
    c.cerrar();
    process.exit(0);
}

const capturas = process.argv[2] || '';
if (capturas && !existsSync(capturas)) mkdirSync(capturas, { recursive: true });
const foto = (nombre) => (capturas ? c.foto(join(capturas, nombre)) : null);

// Todo lo que el navegador pide a /cursos/archivo/<id>/.
const pedidos = [];
c.escuchar('Network.responseReceived', ({ response }) => {
    const m = response.url.match(/\/cursos\/archivo\/(\d+)\//);
    if (m) pedidos.push({ id: Number(m[1]), estado: response.status, rango: response.headers['Content-Range'] || response.headers['content-range'] || '' });
});
const delVideo = (id) => pedidos.filter((p) => p.id === id);
const out = {};

const entro = await c.entrar(ALUMNA);
c.comprobar('entra la alumna de prueba', entro === true, entro);

// 1. Al abrir: portada sí, video no.
await c.ir(LECCION, 1500);
const inicio = await c.ev(`
    const marco = document.querySelector('[data-video-leccion]');
    const plantilla = marco?.querySelector('template')?.content;
    const video = plantilla?.querySelector('video');
    return {
        marco: !!marco,
        portada: !!marco?.querySelector('[data-video-leccion-portada]'),
        videoEnPagina: !!document.querySelector('[data-video-leccion] > [data-angelitozzz-video]'),
        id: Number((video?.getAttribute('src') || '').match(/archivo\\/(\\d+)/)?.[1] || 0),
        controles: video?.getAttribute('controlslist') || '',
        marca: plantilla?.querySelector('[data-angelitozzz-video-marca]')?.textContent || '',
    };
`);
out.inicio = inicio;
c.comprobar('hay marco con portada y el video sigue en la plantilla', inicio.marco && inicio.portada && !inicio.videoEnPagina && inicio.id > 0, inicio);
c.comprobar('al abrir no se pide el video', delVideo(inicio.id).length === 0, delVideo(inicio.id));
c.comprobar('se pide la portada', pedidos.some((p) => p.id !== inicio.id && p.estado === 200), pedidos);
c.comprobar('sin descarga ni pantalla completa del <video>', /nodownload/.test(inicio.controles) && /nofullscreen/.test(inicio.controles), inicio.controles);
c.comprobar('la marca de agua es el correo de la alumna', inicio.marca === ALUMNA, inicio.marca);
c.comprobar('sin avisos de PHP', (await c.avisosPhp()).length === 0);
await foto('1-portada-1440.png');

// 2. Play con un clic real (el navegador solo reproduce con un gesto de la usuaria).
const play = await c.ev(`const r = document.querySelector('[data-video-leccion-play]').getBoundingClientRect(); window.scrollTo(0, scrollY + r.top - 100); await new Promise((x) => setTimeout(x, 300)); const b = document.querySelector('[data-video-leccion-play]').getBoundingClientRect(); return { x: b.left + b.width / 2, y: b.top + b.height / 2 };`);
await c.clic(play.x, play.y);
await sleep(2500);
const reproduce = await c.ev(`
    const v = document.querySelector('[data-video-leccion] video');
    return { hay: !!v, foco: document.activeElement === v, t: v?.currentTime || 0, pausa: v?.paused, duracion: v?.duration || 0, portada: !!document.querySelector('[data-video-leccion-portada]') };
`);
out.reproduce = reproduce;
c.comprobar('play pone el video, lo reproduce y le da el foco', reproduce.hay && reproduce.foco && !reproduce.pausa && reproduce.t > 0 && !reproduce.portada, reproduce);
c.comprobar('el video llega por pedazos (206)', delVideo(inicio.id).some((p) => p.estado === 206), delVideo(inicio.id));
await foto('2-reproduciendo-1440.png');

// 3. Adelantar a la mitad.
const antes = delVideo(inicio.id).length;
const adelanto = await c.ev(`
    const v = document.querySelector('[data-video-leccion] video');
    const destino = v.duration * 0.6;
    v.currentTime = destino;
    await new Promise((r) => setTimeout(r, 2500));
    return { destino, t: v.currentTime, pausa: v.paused };
`);
out.adelanto = adelanto;
c.comprobar('se puede adelantar y sigue reproduciendo', adelanto.t >= adelanto.destino && !adelanto.pausa, adelanto);
// En el Local, Chrome suele bajar todo el video en los primeros segundos: lo que se comprueba es que llegó en
// varios pedazos de hasta 4 MB (206 con inicios distintos), no que adelantar pida uno nuevo.
const pedazos = delVideo(inicio.id).filter((p) => p.estado === 206);
out.pedazos = { total: pedazos.length, alAdelantar: delVideo(inicio.id).length - antes, rangos: pedazos.map((p) => p.rango) };
const tamano = (r) => {
    const m = r.match(/bytes (\d+)-(\d+)\//);
    return m ? Number(m[2]) - Number(m[1]) + 1 : 0;
};
c.comprobar('el video llega en varios pedazos de hasta 4 MB', new Set(pedazos.map((p) => p.rango.split('-')[0])).size >= 2 && pedazos.every((p) => tamano(p.rango) <= 4 * 1024 * 1024), out.pedazos);

// 4. Clic derecho cancelado.
const menu = await c.ev(`const e = new MouseEvent('contextmenu', { bubbles: true, cancelable: true }); document.querySelector('[data-video-leccion] video').dispatchEvent(e); return e.defaultPrevented;`);
c.comprobar('el clic derecho no abre el menú', menu === true, menu);

// 5. La marca de agua cambia de esquina a los 30 s.
const esquina = () => c.ev(`return document.querySelector('[data-angelitozzz-video-marca]')?.dataset.esquina || '';`);
const primera = await esquina();
await sleep(31000);
const segunda = await esquina();
out.esquinas = [primera, segunda];
c.comprobar('la marca cambia de esquina cada 30 s', primera === 'arriba-izquierda' && segunda === 'arriba-derecha', out.esquinas);

// 6. Pantalla completa: Chrome sin ventana la niega ("not granted"), así que se comprueba que el botón y el doble
// clic la piden para el marco entero (no para el <video>), con requestFullscreen reemplazado, y que el texto
// cambia en fullscreenchange. El resultado visual (marca a la vista) se revisa en un navegador con ventana.
const boton = await c.ev(`
    const marco = document.querySelector('[data-video-leccion]');
    window.__pidio = [];
    marco.requestFullscreen = () => { window.__pidio.push('marco'); return Promise.resolve(); };
    document.querySelector('[data-video-leccion] video').requestFullscreen = () => { window.__pidio.push('video'); return Promise.resolve(); };
    const b = marco.querySelector('[data-angelitozzz-video-pantalla]').getBoundingClientRect();
    return { x: b.left + b.width / 2, y: b.top + b.height / 2 };
`);
await c.clic(boton.x, boton.y);
const completa = await c.ev(`
    const marco = document.querySelector('[data-video-leccion]');
    const v = marco.querySelector('video');
    v.dispatchEvent(new MouseEvent('dblclick', { bubbles: true, cancelable: true }));
    // Lo que haría el navegador al entrar: fullscreenElement y el evento.
    Object.defineProperty(document, 'fullscreenElement', { configurable: true, get: () => marco });
    document.dispatchEvent(new Event('fullscreenchange'));
    const texto = marco.querySelector('[data-angelitozzz-video-pantalla]').textContent;
    delete document.fullscreenElement;
    document.dispatchEvent(new Event('fullscreenchange'));
    return { pidio: window.__pidio, texto, despues: marco.querySelector('[data-angelitozzz-video-pantalla]').textContent };
`);
out.pantallaCompleta = completa;
c.comprobar('el botón y el doble clic piden pantalla completa del marco, no del <video>', completa.pidio.length === 2 && completa.pidio.every((p) => p === 'marco'), completa);
c.comprobar('en pantalla completa el botón dice "Salir de pantalla completa" y al salir vuelve', completa.texto === 'Salir de pantalla completa' && completa.despues === 'Pantalla completa', completa);

// 7. Si la dirección deja de servir (vencida o rechazada), el JS pide una nueva y sigue en la misma posición.
const renovacion = await c.ev(`
    const v = document.querySelector('[data-video-leccion] video');
    v.currentTime = 8;
    await new Promise((r) => setTimeout(r, 1500));
    const posicion = v.currentTime;
    const original = v.currentSrc;
    const mala = new URL(original);
    mala.searchParams.set('f', '0'.repeat(64));
    v.src = mala.toString();
    v.play().catch(() => {});
    await new Promise((r) => setTimeout(r, 5000));
    return { posicion, t: v.currentTime, pausa: v.paused, error: !!v.error, original, mala: mala.toString(), src: v.currentSrc, aviso: !document.querySelector('[data-video-leccion-error]').hidden };
`);
out.renovacion = renovacion;
c.comprobar('la dirección rechazada da 403', pedidos.some((p) => p.estado === 403), pedidos.filter((p) => p.estado === 403));
c.comprobar('se pidió una dirección nueva', renovacion.src !== renovacion.mala && /f=[a-f0-9]{64}/.test(renovacion.src) && !renovacion.src.includes('0'.repeat(64)), renovacion);
c.comprobar('tras renovar sigue en la misma posición y reproduciendo', renovacion.t >= renovacion.posicion && !renovacion.pausa && !renovacion.error && !renovacion.aviso, renovacion);

// 8. Materiales: ícono por tipo y <audio> solo en el MP3.
const materiales = await c.ev(`
    const filas = [...document.querySelectorAll('#leccion-materiales-panel li')];
    return {
        filas: filas.length,
        iconos: new Set(filas.map((f) => f.querySelector('svg')?.innerHTML || '')).size,
        audios: filas.filter((f) => f.querySelector('audio')).map((f) => f.querySelector('.text-rotulo')?.textContent),
        audioFirmado: /\\/cursos\\/archivo\\/\\d+\\/?\\?l=/.test(document.querySelector('#leccion-materiales-panel audio')?.getAttribute('src') || ''),
        temario: [...document.querySelectorAll('aside a[href*="/cursos/archivo/"] img')].map((i) => i.getAttribute('src').split('/').pop()),
    };
`);
out.materiales = materiales;
c.comprobar('6 materiales con 5 íconos distintos (PDF y Word comparten el de documento)', materiales.filas === 6 && materiales.iconos === 5, materiales);
c.comprobar('el <audio> solo en el MP3, con su dirección firmada', materiales.audios.length === 1 && materiales.audios[0] === 'Ruido blanco' && materiales.audioFirmado, materiales);
c.comprobar('el PDF del curso en el temario lleva el ícono de documento', materiales.temario.includes('pdf.svg'), materiales.temario);
const descargas = await c.ev(`
    const filas = [...document.querySelectorAll('#leccion-materiales-panel li')];
    const nombres = [];
    for (const f of filas) {
        const r = await fetch(f.querySelector('a').href, { credentials: 'same-origin' });
        nombres.push(r.status + ' ' + (r.headers.get('content-disposition') || ''));
    }
    const audio = document.querySelector('#leccion-materiales-panel audio');
    audio.load();
    await new Promise((r) => { audio.addEventListener('loadedmetadata', r, { once: true }); audio.addEventListener('error', r, { once: true }); setTimeout(r, 5000); });
    return { nombres, audio: { error: audio.error?.code || 0, duracion: audio.duration || 0 } };
`);
out.descargas = descargas;
c.comprobar('cada material se descarga con su nombre', descargas.nombres.length === 6 && descargas.nombres.every((n) => /^200 attachment; filename="[^"]+\.(pdf|docx|xlsx|pptx|png|mp3)"/.test(n)) && descargas.nombres[1].includes('Plan-de-siestas.docx'), descargas.nombres);
c.comprobar('el audio carga en la tarjeta', descargas.audio.error === 0 && descargas.audio.duracion > 0, descargas.audio);
await c.ev(`document.querySelector('#leccion-materiales-pestana')?.click(); document.querySelector('#leccion-materiales-panel')?.scrollIntoView(); return 1;`);
await sleep(400);
await foto('4-materiales-1440.png');

// 9. La dirección copiada no sirve sin la sesión, ni en este navegador después de cerrarla.
const src = await c.ev(`return document.querySelector('[data-video-leccion] video').currentSrc;`);
const sinCookie = await fetch(src, { headers: { Range: 'bytes=0-99' } });
out.sinCookie = sinCookie.status;
c.comprobar('la dirección copiada, sin la sesión, da 403', sinCookie.status === 403, sinCookie.status);
// "Cerrar sesión" está en el menú de la cuenta.
await c.ir('/cuenta/');
const salir = await c.ev(`return document.querySelector('a[href*="action=logout"]')?.href || '';`);
if (salir) {
    await c.ir(salir, 1200);
    const trasSalir = await c.ev(`const r = await fetch(${JSON.stringify(src)}, { headers: { Range: 'bytes=0-99' }, credentials: 'same-origin' }); return r.status;`);
    out.trasCerrarSesion = trasSalir;
    c.comprobar('después de cerrar sesión la dirección da 403', trasSalir === 403, trasSalir);
} else {
    c.comprobar('hay un enlace para cerrar sesión', false, 'no encontré a[href*="action=logout"]');
}

// 10. Vista previa sin sesión: video sin marca. Y la lección en móvil.
await c.borrarCookies();
await c.ir(LECCION.replace('video-1-bienvenida', 'video-2-el-sueno-del-recien-nacido'), 1200);
const previa = await c.ev(`
    const t = document.querySelector('[data-video-leccion] template')?.content;
    return { video: !!t?.querySelector('video'), marca: !!t?.querySelector('[data-angelitozzz-video-marca]'), materiales: !!document.querySelector('#leccion-materiales-panel') };
`);
out.vistaPrevia = previa;
c.comprobar('vista previa sin sesión: video sin marca y sin materiales', previa.video && !previa.marca && !previa.materiales, previa);
c.cerrar();

if (capturas) {
    await sleep(1500);
    const m = await abrir({ puerto: 9351, ancho: 390, alto: 844 });
    await m.entrar(ALUMNA);
    await m.ir(LECCION, 1500);
    await m.ev(`document.querySelector('[data-video-leccion]').scrollIntoView(); return 1;`);
    await sleep(300);
    await m.foto(join(capturas, '5-portada-390.png'));
    await m.ev(`document.querySelector('#leccion-materiales-pestana')?.click(); document.querySelector('#leccion-materiales-panel')?.scrollIntoView(); return 1;`);
    await sleep(400);
    await m.foto(join(capturas, '6-materiales-390.png'));
    out.desborde390 = await m.ev(`return document.documentElement.scrollWidth - innerWidth;`);
    m.comprobar('sin desborde a 390 px', out.desborde390 <= 0, out.desborde390);
    out.pedidos = pedidos.length;
    m.fallos.push(...c.fallos);
    m.erroresJs.push(...c.erroresJs);
    await m.terminar({ ...out, sitio });
}
out.pedidos = pedidos.length;
await c.terminar(out);
