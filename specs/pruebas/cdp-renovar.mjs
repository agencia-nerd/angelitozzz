// Renovación de Angelita Pro (F6, cursos-datos.md → renovacion; usuario-cursos.md y usuario-historial-pedido.md).
// Necesita `datos-f5.php tienda` y `datos-f5.php renovar` (alumna renovar-f5 con el acceso por vencer en 5 días).
// Comprueba que la tarjeta de "Mis cursos" muestra "Tu acceso vence el …" y "Renovar", que el historial tiene
// "Renovar" en esa compra, y que el botón lleva al checkout sin los parámetros con el producto en el carrito,
// también si ya estaba (sin el error de "Vender individualmente"). Con carpeta, toma capturas a 1440 y 390 px.
// Uso: node cdp-renovar.mjs [carpeta para capturas]   (puerto 9354)
import { abrir } from './lib/cdp.mjs';

const carpeta = process.argv[2] || '';
const out = {};
const fallosPrevios = []; // Cada ancho abre su Chrome: los fallos del primero pasan al último.

for (const ancho of [1440, 390]) {
    const c = await abrir({ puerto: 9354, ancho });
    c.fallos.push(...fallosPrevios);
    const r = (out[ancho] = {});
    c.comprobar(`${ancho}: entra la alumna`, await c.entrar('renovar-f5@angelitozzz.local'));

    // Mis cursos: la tarjeta de Angelita Pro.
    await c.ir('/cuenta/cursos/?pestana=inscritos');
    r.cursos = await c.ev(`
        const tarjeta = [...document.querySelectorAll('main article')].find((a) => a.innerText.includes('Angelita Pro'));
        const renovar = tarjeta && [...tarjeta.querySelectorAll('a')].find((a) => a.innerText.trim() === 'Renovar');
        return {
            vence: tarjeta ? (tarjeta.innerText.match(/Tu acceso vence el [^\\n]+/) || [''])[0] : '',
            renovar: renovar ? renovar.getAttribute('href') : '',
            desborde: document.documentElement.scrollWidth > innerWidth,
        };`);
    r.cursos.php = await c.avisosPhp();
    c.comprobar(`${ancho}: la tarjeta dice cuándo vence`, /Tu acceso vence el \d+ de \w+, \d{4}/.test(r.cursos.vence), r.cursos);
    c.comprobar(`${ancho}: la tarjeta tiene Renovar al checkout`, /finalizar-compra\/\?add-to-cart=\d+&angelitozzz_renovar=1/.test(r.cursos.renovar), r.cursos);
    c.comprobar(`${ancho}: cursos sin desborde ni avisos de PHP`, !r.cursos.desborde && r.cursos.php.length === 0, r.cursos);
    if (carpeta) await c.foto(`${carpeta}/renovar-cursos-${ancho}.png`);

    // Historial: el enlace en la fila del pedido.
    await c.ir('/cuenta/pedidos/');
    r.pedidos = await c.ev(`
        const fila = [...document.querySelectorAll('main tbody tr')].find((t) => t.innerText.includes('Angelita Pro'));
        const a = fila && [...fila.querySelectorAll('a')].find((x) => x.innerText.trim().startsWith('Renovar'));
        return {
            estado: fila ? fila.innerText.includes('Por expirar') : false,
            renovar: a ? a.getAttribute('href') : '',
            nombre: a ? a.textContent.replace(/\\s+/g, ' ').trim() : '',
            desborde: document.documentElement.scrollWidth > innerWidth,
        };`);
    r.pedidos.php = await c.avisosPhp();
    c.comprobar(`${ancho}: historial "Por expirar" con Renovar`, r.pedidos.estado && /angelitozzz_renovar=1/.test(r.pedidos.renovar), r.pedidos);
    c.comprobar(`${ancho}: el enlace dice el curso a los lectores`, r.pedidos.nombre.includes('Angelita Pro'), r.pedidos);
    c.comprobar(`${ancho}: historial sin desborde ni avisos de PHP`, !r.pedidos.desborde && r.pedidos.php.length === 0, r.pedidos);
    if (carpeta) await c.foto(`${carpeta}/renovar-pedidos-${ancho}.png`);

    if (ancho === 1440) {
        // Renovar: checkout limpio con el producto; la segunda vez, sin error.
        for (const vez of ['primera', 'segunda']) {
            await c.ir(r.cursos.renovar);
            r[vez] = await c.ev(`
                const texto = document.querySelector('main')?.innerText || '';
                return {
                    url: location.pathname + location.search,
                    curso: texto.includes('Angelita Pro'),
                    avisos: [...document.querySelectorAll('.woocommerce-error, .woocommerce-message, .woocommerce-info, [role="alert"]')].map((e) => e.innerText.trim()).filter(Boolean),
                };`);
            c.comprobar(`renovar (${vez}): checkout sin parámetros`, r[vez].url === '/finalizar-compra/', r[vez]);
            // Ni "se ha añadido a tu carrito" ni el error de "Vender individualmente".
            c.comprobar(`renovar (${vez}): con Angelita Pro y sin avisos de Woo`, r[vez].curso && r[vez].avisos.length === 0, r[vez]);
        }
        if (carpeta) await c.foto(`${carpeta}/renovar-checkout-1440.png`);
    }

    if (ancho === 390) await c.terminar(out);
    fallosPrevios.push(...c.fallos, ...c.erroresJs.map((e) => ({ nombre: `${ancho}: error de JS`, detalle: e })));
    c.cerrar();
}
