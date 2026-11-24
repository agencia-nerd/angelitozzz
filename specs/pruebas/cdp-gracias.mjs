// Gracias por tu compra y canje del regalo (gracias.md, canjear-regalo.md), contra el Local y el plugin real.
// Necesita `datos-f5.php crear`: pedidos de la alumna (pagado, pendiente, regalo y cancelado), el código del regalo
// y la usuaria canje-f5@angelitozzz.local. El canje gasta el código: para repetir, `borrar` y `crear` de nuevo.
// Uso: node cdp-gracias.mjs   (puerto 9344)
import { abrir, datosF5 } from './lib/cdp.mjs';

const datos = datosF5();
if (!datos?.gracias || !datos.codigos?.length) {
    console.error('Faltan los datos de prueba: corre `php zz-datos-f5.php crear` primero.');
    process.exit(1);
}
const codigo = datos.codigos[0];
const c = await abrir({ puerto: 9344 });
const out = { gracias: {}, canje: {} };

// Lo visible de la página: títulos, pastillas, botones y textos clave.
const leer = () => c.ev(`
    const m = document.querySelector('main');
    const texto = (m?.innerText || '').replace(/\\s+/g, ' ');
    return {
        h1: [...m.querySelectorAll('h1')].map((h) => h.innerText.trim()),
        botones: [...m.querySelectorAll('a.rounded-full, button.rounded-full')].map((b) => b.innerText.trim()).filter(Boolean),
        mundo: texto.includes('hay algo más esperándote'),
        codigo: texto.toUpperCase().includes(${JSON.stringify(codigo.toUpperCase())}),
        copiar: !!m.querySelector('[data-copiar-raiz] button'),
        alertas: [...m.querySelectorAll('[role="alert"], [role="status"]')].map((a) => a.innerText.trim()).filter(Boolean),
        url: location.pathname + location.search,
    };`);

// 1. Gracias, con la alumna que compró.
c.comprobar('entra la alumna', await c.entrar('prueba-f5@angelitozzz.local'));
for (const [clave, url] of Object.entries(datos.gracias)) {
    await c.ir(url);
    out.gracias[clave] = { ...(await leer()), php: await c.avisosPhp() };
    c.comprobar(`gracias ${clave}: sin avisos de PHP`, out.gracias[clave].php.length === 0, out.gracias[clave].php);
}
const g = out.gracias;
c.comprobar('pagado: título de compra exitosa', g.pagado.h1.some((t) => t.startsWith('¡Gracias por tu compra')), g.pagado.h1);
// El curso A incluye el mes gratis; el banner sale solo si Ajustes del sitio tiene su enlace.
if (datos.mundo_con_enlace) c.comprobar('pagado: bloque de Mundo (el curso lo incluye)', g.pagado.mundo, g.pagado);
else {
    c.comprobar('pagado: sin enlace de Mundo en Ajustes, no hay banner', !g.pagado.mundo, g.pagado);
    out.aviso = 'Ajustes del sitio sin enlace del mes gratis en Mundo: no se probó el banner (pendiente de la F7).';
}
c.comprobar('pendiente: confirmando el pago', g.pendiente.h1.some((t) => t.includes('confirmando tu pago')), g.pendiente.h1);
c.comprobar('pendiente: sin Mundo', !g.pendiente.mundo, g.pendiente);
c.comprobar('regalo: muestra el código', g.regalo.codigo, g.regalo);
c.comprobar('regalo: botón "Copiar código"', g.regalo.copiar, g.regalo);
c.comprobar('regalo: sin Mundo', !g.regalo.mundo, g.regalo);
c.comprobar('cancelado: "Este pedido fue cancelado"', g.cancelado.h1.some((t) => t.includes('fue cancelado')), g.cancelado.h1);
c.comprobar('cancelado: sin Mundo ni código', !g.cancelado.mundo && !g.cancelado.codigo, g.cancelado);

// "Copiar código": en el Local (sin https) usa el método clásico del navegador y avisa por aria-live.
// Clic real del mouse: copiar necesita un gesto de la usuaria (un .click() por JS no cuenta).
await c.ir(datos.gracias.regalo);
const boton = await c.ev(`const b = document.querySelector('[data-copiar-raiz] button'); b.scrollIntoView({ block: 'center' }); const r = b.getBoundingClientRect(); return { x: r.x + r.width / 2, y: r.y + r.height / 2 };`);
await c.clic(boton.x, boton.y);
out.gracias.copiado = await c.ev(`return (document.querySelector('[data-copiar-aviso]')?.textContent || '') + ' | ' + (document.querySelector('[data-copiar-texto]')?.textContent || '');`);
c.comprobar('regalo: "Copiar código" avisa que copió', /copiad/i.test(out.gracias.copiado), out.gracias.copiado);

// 2. Canje sin sesión: un código válido lleva a crear la cuenta (no se completa: el código queda sin usar).
const canjear = async (valor) => {
    await c.ir('/canjear-regalo/');
    await c.conNavegacion(`const f = document.querySelector('main form'); f.codigo.value = ${JSON.stringify(valor)}; f.requestSubmit(); return 1;`);
    return { ...(await leer()), php: await c.avisosPhp() };
};
await c.borrarCookies();
await c.ir('/canjear-regalo/');
out.canje.nota = await c.ev(`return (document.querySelector('main').innerText || '').includes('la creas en el siguiente paso');`);
c.comprobar('canje sin sesión: nota de crear la cuenta', out.canje.nota);
out.canje.sinSesion = await canjear(codigo);
c.comprobar('canje sin sesión: lleva a crear la cuenta', out.canje.sinSesion.url.startsWith('/crear-cuenta/') && out.canje.sinSesion.alertas.some((a) => a.includes('Crea tu cuenta')), out.canje.sinSesion);

// 3. Canje con canje-f5 (sin cursos): inválido, válido y el mismo otra vez.
c.comprobar('entra la usuaria del canje', await c.entrar('canje-f5@angelitozzz.local'));
out.canje.invalido = await canjear('ANGZ-0000');
c.comprobar('código inválido: error y sigue en el canje', out.canje.invalido.url.startsWith('/canjear-regalo/') && out.canje.invalido.alertas.length > 0, out.canje.invalido);
out.canje.valido = await canjear(codigo);
c.comprobar('código válido: "Regalo canjeado"', out.canje.valido.h1.some((t) => t.includes('ya tienes tu curso')), out.canje.valido);
await c.ir('/cuenta/cursos/');
out.canje.misCursos = await c.ev(`return [...document.querySelectorAll('main article h3, main article h2')].map((h) => h.innerText.trim());`);
c.comprobar('el curso canjeado está en Mis cursos', out.canje.misCursos.some((t) => t.includes('Curso de prueba F5')), out.canje.misCursos);
out.canje.repetido = await canjear(codigo);
c.comprobar('el mismo código otra vez: error', out.canje.repetido.alertas.length > 0 && !out.canje.repetido.h1.some((t) => t.includes('ya tienes tu curso')), out.canje.repetido);
for (const [paso, r] of Object.entries(out.canje)) if (r?.php) c.comprobar(`canje ${paso}: sin avisos de PHP`, r.php.length === 0, r.php);

await c.terminar(out);
