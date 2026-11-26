// Videos de las landings (F5-d, bloque 5; specs/_shared.md → video-portada, solucion.md → secciones y
// modal-etapa): MP4 público de Medios con el reproductor del navegador, sin Vimeo.
// Necesita "php zz-datos-f5.php video-landing" (al terminar, "video-landing-borrar").
// Comprueba, en la galería de Angelita Pro y en el modal de la primera etapa de Horarios Ideales:
// - antes de play no hay <video> ni se pide el MP4;
// - play crea el <video> con controles, en el mismo lugar, con el foco, y empieza a sonar;
// - cambiar de diapositiva (galería) o cerrar el modal (etapa) lo quita y vuelve la portada;
// - no hay iframe de Vimeo ni avisos de PHP; a 390 px el video entra sin desborde.
// Uso: node cdp-landing-video.mjs. Puerto 9353.
import { abrir, sleep } from './lib/cdp.mjs';

const c = await abrir({ puerto: 9353 });
const pedidosMp4 = [];
c.escuchar('Network.requestWillBeSent', ({ request }) => {
    if (/\.mp4(\?|$)/i.test(request.url)) pedidosMp4.push(request.url);
});
await c.send('Network.enable');
const out = {};
// Clic real del mouse (con activación de usuario): un .click() por JS no deja que el video suene solo.
const clicReal = async (selector) => {
    const caja = await c.ev(`const b = document.querySelector(${JSON.stringify(selector)}); b.scrollIntoView({ block: 'center' }); const r = b.getBoundingClientRect(); return { x: r.x + r.width / 2, y: r.y + r.height / 2 };`);
    await c.clic(caja.x, caja.y);
};

const estado = (raiz) => c.ev(`const r = ${raiz};
    const v = r.querySelector('[data-video] video');
    return {
        video: !!v,
        controles: v?.controls ?? null,
        sonando: v ? !v.paused && v.currentTime > 0 : false,
        foco: v ? document.activeElement === v : false,
        portadaOculta: !!r.querySelector('[data-video] > img[hidden]'),
        boton: !!r.querySelector('[data-video-play]:not([hidden])'),
        vimeo: !!document.querySelector('iframe[src*="vimeo"]'),
    };`);

// ---------- Galería de Angelita Pro ----------
await c.ir('/soluciones-de-sueno/angelita-pro/', 1500);
out.galeriaAntes = { ...(await estado(`document.querySelector('[data-solucion-galeria]')`)), pedidos: pedidosMp4.length };
c.comprobar('galería: antes de play no hay <video> ni se pide el MP4', !out.galeriaAntes.video && out.galeriaAntes.pedidos === 0 && out.galeriaAntes.boton, out.galeriaAntes);
c.comprobar('no hay iframe de Vimeo', !out.galeriaAntes.vimeo, out.galeriaAntes);
out.botonNombre = await c.ev(`return document.querySelector('[data-solucion-galeria] [data-video-play]')?.innerText.trim();`);
c.comprobar('el play tiene nombre accesible', /^Reproducir video: /.test(out.botonNombre || ''), out.botonNombre);

await clicReal('[data-solucion-galeria] [data-video-play]');
for (let i = 0; i < 20; i++) {
    out.galeriaPlay = await estado(`document.querySelector('[data-solucion-galeria]')`);
    if (out.galeriaPlay.sonando) break;
    await sleep(300);
}
c.comprobar('galería: play crea el <video> con controles, con el foco, y suena', out.galeriaPlay.video && out.galeriaPlay.controles && out.galeriaPlay.foco && out.galeriaPlay.sonando && out.galeriaPlay.portadaOculta, out.galeriaPlay);
c.comprobar('recién al pulsar play se pide el MP4', pedidosMp4.length > 0, pedidosMp4);

await c.ev(`document.querySelector('[data-solucion-galeria] [data-slider-siguiente]').click(); return 1;`);
await sleep(800);
out.galeriaCambio = await estado(`document.querySelector('[data-solucion-galeria]')`);
c.comprobar('galería: al cambiar de diapositiva el video se quita y vuelve la portada', !out.galeriaCambio.video && !out.galeriaCambio.portadaOculta && out.galeriaCambio.boton, out.galeriaCambio);
c.comprobar('galería: sin avisos de PHP', !(await c.avisosPhp()).length);

// ---------- Modal de la primera etapa de Horarios Ideales ----------
pedidosMp4.length = 0;
await c.ir('/soluciones-de-sueno/horarios-ideales/', 1500);
await c.ev(`document.querySelector('[data-modal-open="etapa-1"], [aria-controls="etapa-1"]')?.click() ?? document.querySelector('#etapa-1').showModal(); return 1;`);
await sleep(500);
out.etapaAntes = { ...(await estado(`document.getElementById('etapa-1')`)), abierto: await c.ev(`return document.getElementById('etapa-1').open;`), pedidos: pedidosMp4.length };
c.comprobar('etapa: el modal abre sin <video> ni pedir el MP4', out.etapaAntes.abierto && !out.etapaAntes.video && out.etapaAntes.pedidos === 0 && out.etapaAntes.boton, out.etapaAntes);
await clicReal('#etapa-1 [data-video-play]');
for (let i = 0; i < 20; i++) {
    out.etapaPlay = await estado(`document.getElementById('etapa-1')`);
    if (out.etapaPlay.sonando) break;
    await sleep(300);
}
c.comprobar('etapa: play crea el <video> con controles y suena', out.etapaPlay.video && out.etapaPlay.controles && out.etapaPlay.sonando, out.etapaPlay);
await c.ev(`document.getElementById('etapa-1').close(); return 1;`);
await sleep(300);
out.etapaCerrada = await estado(`document.getElementById('etapa-1')`);
c.comprobar('etapa: al cerrar el modal el video se quita y vuelve la portada', !out.etapaCerrada.video && !out.etapaCerrada.portadaOculta, out.etapaCerrada);
c.comprobar('etapa: sin avisos de PHP', !(await c.avisosPhp()).length);

// ---------- Móvil ----------
await c.send('Emulation.setDeviceMetricsOverride', { width: 390, height: 844, deviceScaleFactor: 1, mobile: true });
await c.ir('/soluciones-de-sueno/angelita-pro/', 1500);
await clicReal('[data-solucion-galeria] [data-video-play]');
await sleep(1200);
out.movil = await c.ev(`const v = document.querySelector('[data-solucion-galeria] video'); const r = v?.getBoundingClientRect(); return { video: !!v, ancho: r ? Math.round(r.width) : 0, desborde: document.documentElement.scrollWidth - innerWidth };`);
c.comprobar('móvil: el video entra en 390 px sin desborde', out.movil.video && out.movil.ancho <= 390 && out.movil.desborde <= 0, out.movil);

await c.terminar(out);
