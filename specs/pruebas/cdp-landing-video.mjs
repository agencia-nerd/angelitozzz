// Videos de las landings (F5-e; specs/_shared.md → video-portada, solucion.md → secciones y modal-etapa): enlace
// de Vimeo (campo oembed de ACF) que video-portada reproduce en el iframe de player.vimeo.com.
// Necesita "php zz-datos-f5.php video-landing" (al terminar, "video-landing-borrar").
// Comprueba, en la galería de Angelita Pro y en el modal de la primera etapa de Horarios Ideales:
// - antes de play no hay iframe ni se pide nada a player.vimeo.com;
// - play crea el iframe de Vimeo (dnt=1, autoplay=1) en el mismo lugar, con el foco y el título;
// - cambiar de diapositiva (galería) o cerrar el modal (etapa) lo quita y vuelve la portada;
// - sin avisos de PHP; a 390 px el video entra sin desborde.
// Uso: node cdp-landing-video.mjs. Puerto 9353.
import { abrir, sleep } from './lib/cdp.mjs';

const c = await abrir({ puerto: 9353 });
const pedidosVimeo = [];
c.escuchar('Network.requestWillBeSent', ({ request }) => {
    if (/player\.vimeo\.com/i.test(request.url)) pedidosVimeo.push(request.url);
});
await c.send('Network.enable');
const out = {};
// Clic real del mouse (con activación de usuario), como el de una visitante.
const clicReal = async (selector) => {
    const caja = await c.ev(`const b = document.querySelector(${JSON.stringify(selector)}); b.scrollIntoView({ block: 'center' }); const r = b.getBoundingClientRect(); return { x: r.x + r.width / 2, y: r.y + r.height / 2 };`);
    await c.clic(caja.x, caja.y);
};

const estado = (raiz) => c.ev(`const r = ${raiz};
    const f = r.querySelector('[data-video] iframe');
    return {
        iframe: !!f,
        src: f?.getAttribute('src') || '',
        titulo: f?.title || '',
        foco: f ? document.activeElement === f : false,
        portadaOculta: !!r.querySelector('[data-video] > img[hidden]'),
        boton: !!r.querySelector('[data-video-play]:not([hidden])'),
        videoNativo: !!r.querySelector('[data-video] video'),
    };`);
const esVimeo = (src) => /^https:\/\/player\.vimeo\.com\/video\/76979871\?(?=.*dnt=1)(?=.*autoplay=1)/.test(src);

// ---------- Galería de Angelita Pro ----------
await c.ir('/soluciones-de-sueno/angelita-pro/', 1500);
out.galeriaAntes = { ...(await estado(`document.querySelector('[data-solucion-galeria]')`)), pedidos: pedidosVimeo.length };
c.comprobar('galería: antes de play no hay iframe ni se pide nada a Vimeo', !out.galeriaAntes.iframe && out.galeriaAntes.pedidos === 0 && out.galeriaAntes.boton, out.galeriaAntes);
out.botonNombre = await c.ev(`return document.querySelector('[data-solucion-galeria] [data-video-play]')?.innerText.trim();`);
c.comprobar('el play tiene nombre accesible', /^Reproducir video: /.test(out.botonNombre || ''), out.botonNombre);

await clicReal('[data-solucion-galeria] [data-video-play]');
await sleep(1500);
out.galeriaPlay = { ...(await estado(`document.querySelector('[data-solucion-galeria]')`)), pedidos: pedidosVimeo.length };
c.comprobar('galería: play crea el iframe de Vimeo (dnt y autoplay) con el foco y su título', out.galeriaPlay.iframe && esVimeo(out.galeriaPlay.src) && out.galeriaPlay.foco && out.galeriaPlay.titulo !== '' && out.galeriaPlay.portadaOculta && !out.galeriaPlay.videoNativo, out.galeriaPlay);
c.comprobar('recién al pulsar play se pide a Vimeo', out.galeriaPlay.pedidos > 0, pedidosVimeo);

await c.ev(`document.querySelector('[data-solucion-galeria] [data-slider-siguiente]').click(); return 1;`);
await sleep(800);
out.galeriaCambio = await estado(`document.querySelector('[data-solucion-galeria]')`);
c.comprobar('galería: al cambiar de diapositiva el iframe se quita y vuelve la portada', !out.galeriaCambio.iframe && !out.galeriaCambio.portadaOculta && out.galeriaCambio.boton, out.galeriaCambio);
c.comprobar('galería: sin avisos de PHP', !(await c.avisosPhp()).length);

// ---------- Modal de la primera etapa de Horarios Ideales ----------
pedidosVimeo.length = 0;
await c.ir('/soluciones-de-sueno/horarios-ideales/', 1500);
await c.ev(`document.querySelector('[data-modal-open="etapa-1"], [aria-controls="etapa-1"]')?.click() ?? document.querySelector('#etapa-1').showModal(); return 1;`);
await sleep(500);
out.etapaAntes = { ...(await estado(`document.getElementById('etapa-1')`)), abierto: await c.ev(`return document.getElementById('etapa-1').open;`), pedidos: pedidosVimeo.length };
c.comprobar('etapa: el modal abre sin iframe ni pedir nada a Vimeo', out.etapaAntes.abierto && !out.etapaAntes.iframe && out.etapaAntes.pedidos === 0 && out.etapaAntes.boton, out.etapaAntes);
await clicReal('#etapa-1 [data-video-play]');
await sleep(1500);
out.etapaPlay = await estado(`document.getElementById('etapa-1')`);
c.comprobar('etapa: play crea el iframe de Vimeo', out.etapaPlay.iframe && esVimeo(out.etapaPlay.src), out.etapaPlay);
await c.ev(`document.getElementById('etapa-1').close(); return 1;`);
await sleep(300);
out.etapaCerrada = await estado(`document.getElementById('etapa-1')`);
c.comprobar('etapa: al cerrar el modal el iframe se quita y vuelve la portada', !out.etapaCerrada.iframe && !out.etapaCerrada.portadaOculta, out.etapaCerrada);
c.comprobar('etapa: sin avisos de PHP', !(await c.avisosPhp()).length);

// ---------- Móvil ----------
await c.send('Emulation.setDeviceMetricsOverride', { width: 390, height: 844, deviceScaleFactor: 1, mobile: true });
await c.ir('/soluciones-de-sueno/angelita-pro/', 1500);
await clicReal('[data-solucion-galeria] [data-video-play]');
await sleep(1200);
out.movil = await c.ev(`const f = document.querySelector('[data-solucion-galeria] iframe'); const r = f?.getBoundingClientRect(); return { iframe: !!f, ancho: r ? Math.round(r.width) : 0, desborde: document.documentElement.scrollWidth - innerWidth };`);
c.comprobar('móvil: el video entra en 390 px sin desborde', out.movil.iframe && out.movil.ancho <= 390 && out.movil.desborde <= 0, out.movil);

await c.terminar(out);
