// Modales (wp-modal) y acordeón (wp-accordion) del sitio, contra el Local: abrir, foco dentro, cerrar con Esc
// y con clic en el fondo, y el foco de vuelta al botón que lo abrió.
// - Coach (cintillo), drawer del carrito vacío y el acordeón de Preguntas frecuentes (también sin JS).
// - Con `datos-f5.php tienda`: el modal de una etapa y el selector de edad de Horarios Ideales. Sin esos datos
//   esa parte se salta y lo dice.
// Uso: node cdp-modales.mjs   (puerto 9346)
import { abrir } from './lib/cdp.mjs';

const c = await abrir({ puerto: 9346 });
const out = {};

// Abre un modal con su botón (clic del mouse) y prueba Esc, el foco y, si se pide, el clic en el fondo.
const probarModal = async (nombre, id, selectorBoton, { fondo = true } = {}) => {
    const r = {};
    const centro = await c.ev(`
        const b = document.querySelector(${JSON.stringify(selectorBoton)});
        if (!b) return null;
        b.scrollIntoView({ block: 'center' });
        const q = b.getBoundingClientRect();
        return { x: q.x + q.width / 2, y: q.y + q.height / 2 };`);
    if (!c.comprobar(`${nombre}: está el botón que lo abre`, !!centro, selectorBoton)) return r;
    const estado = () => c.ev(`
        const d = document.getElementById(${JSON.stringify(id)});
        const a = document.activeElement;
        return { abierto: !!d?.open, focoDentro: !!(d && d.contains(a)), focoEnBoton: a === document.querySelector(${JSON.stringify(selectorBoton)}), modal: d?.matches(':modal') || false };`);

    await c.clic(centro.x, centro.y);
    r.abierto = await estado();
    c.comprobar(`${nombre}: se abre como modal`, r.abierto.abierto && r.abierto.modal, r.abierto);
    c.comprobar(`${nombre}: el foco entra al modal`, r.abierto.focoDentro, r.abierto);

    await c.tecla('Escape');
    await c.ev(`await new Promise((r) => setTimeout(r, 500)); return 1;`);
    r.esc = await estado();
    c.comprobar(`${nombre}: Esc lo cierra`, !r.esc.abierto, r.esc);
    c.comprobar(`${nombre}: el foco vuelve al botón`, r.esc.focoEnBoton, r.esc);

    if (fondo) {
        await c.clic(centro.x, centro.y);
        await c.clic(4, 4); // Esquina de la ventana: el fondo del <dialog>.
        await c.ev(`await new Promise((r) => setTimeout(r, 500)); return 1;`);
        r.fondo = await estado();
        c.comprobar(`${nombre}: clic en el fondo lo cierra`, !r.fondo.abierto, r.fondo);
    }
    return r;
};

// 1. Coach, desde el cintillo.
await c.borrarCookies();
await c.ir('/');
out.coach = await probarModal('coach', 'modal-coach', '[data-modal-open="modal-coach"]');

// 2. Drawer del carrito vacío (el carrito del header).
out.drawer = await probarModal('drawer del carrito', 'carrito', 'header [data-modal-open="carrito"]');
await c.ev(`document.querySelector('header [data-modal-open="carrito"]').click(); await new Promise((r) => setTimeout(r, 500)); return 1;`);
out.drawer.contenido = await c.ev(`return (document.getElementById('carrito')?.innerText || '').replace(/\\s+/g, ' ').trim().slice(0, 200);`);
c.comprobar('drawer vacío: mensaje y "Seguir comprando"', out.drawer.contenido.includes('Tu carrito está vacío') && out.drawer.contenido.includes('Seguir comprando'), out.drawer.contenido);

// 3. Acordeón de Preguntas frecuentes: la primera abierta, se abre y cierra con teclado; sin JS sigue andando.
await c.ir('/preguntas-frecuentes/');
const acordeon = () => c.ev(`return [...document.querySelectorAll('main details')].map((d) => d.open);`);
out.acordeon = { inicial: await acordeon() };
c.comprobar('acordeón: hay preguntas', out.acordeon.inicial.length > 0, out.acordeon.inicial);
c.comprobar('acordeón: solo la primera abierta', out.acordeon.inicial[0] === true && out.acordeon.inicial.slice(1).every((v) => !v), out.acordeon.inicial);
await c.ev(`document.querySelectorAll('main details summary')[1].focus(); return 1;`);
await c.tecla('Enter');
await c.ev(`await new Promise((r) => setTimeout(r, 500)); return 1;`);
out.acordeon.conEnter = await acordeon();
c.comprobar('acordeón: Enter abre la segunda', out.acordeon.conEnter[1] === true, out.acordeon.conEnter);
await c.tecla(' ', 'Space');
await c.ev(`await new Promise((r) => setTimeout(r, 500)); return 1;`);
out.acordeon.conEspacio = await acordeon();
c.comprobar('acordeón: Espacio la vuelve a cerrar', out.acordeon.conEspacio[1] === false, out.acordeon.conEspacio);
await c.sinJs(true);
await c.ir('/preguntas-frecuentes/');
await c.ev(`document.querySelectorAll('main details summary')[2].click(); return 1;`);
out.acordeon.sinJs = await acordeon();
c.comprobar('acordeón sin JS: abre la tercera', out.acordeon.sinJs[2] === true, out.acordeon.sinJs);
await c.sinJs(false);

// 4. Horarios Ideales: modal de una etapa y selector de edad (solo con `datos-f5.php tienda`).
await c.ir('/soluciones-de-sueno/horarios-ideales/');
const conCursos = await c.ev(`return !!document.getElementById('selecciona-edad');`);
if (conCursos) {
    out.etapa = await probarModal('modal de la etapa 1', 'etapa-1', '[data-modal-open="etapa-1"]', { fondo: false });
    out.selector = await probarModal('selector de edad', 'selecciona-edad', 'main [data-modal-open="selecciona-edad"]', { fondo: false });
} else {
    out.etapas = 'saltado: Horarios Ideales sin cursos a la venta (corre `datos-f5.php tienda` para probarlo)';
}

for (const ruta of ['/', '/preguntas-frecuentes/', '/soluciones-de-sueno/horarios-ideales/']) {
    await c.ir(ruta);
    const php = await c.avisosPhp();
    c.comprobar(`${ruta}: sin avisos de PHP`, php.length === 0, php);
}

await c.terminar(out);
