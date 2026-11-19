// Prueba de la compra (F5-b) por CDP, contra el Local: landing → drawer → etapas → regalo → quitar → carrito →
// cupón → checkout, sin pagar (el pago con Openpay en sandbox se prueba a mano). Necesita los datos de
// `datos-f5.php tienda`. Sin sesión: crea solo un carrito en la sesión de Woo de este Chrome headless.
// Uso: node cdp-compra.mjs <carpeta para capturas>   (SITIO y CHROME como variables de entorno)
import { spawn } from 'node:child_process';
import { writeFileSync, mkdirSync } from 'node:fs';

const sitio = process.env.SITIO || 'http://angelitozzz.local';
const carpeta = process.argv[2] || '.';
mkdirSync(carpeta, { recursive: true });
const proc = spawn(process.env.CHROME || 'C:/Program Files/Google/Chrome/Application/chrome.exe', ['--headless=new', '--disable-gpu', '--remote-debugging-port=9341', '--window-size=1440,1000', 'about:blank'], { stdio: 'ignore' });
const sleep = (ms) => new Promise((r) => setTimeout(r, ms));
let ws, id = 0, cargo = null;
const pending = new Map();
const errores = [];
const send = (method, params = {}) => new Promise((r) => { const n = ++id; pending.set(n, r); ws.send(JSON.stringify({ id: n, method, params })); });
const ev = async (expr) => {
    const r = await send('Runtime.evaluate', { expression: `(async () => { const sleep = (ms) => new Promise(r => setTimeout(r, ms)); ${expr} })()`, awaitPromise: true, returnByValue: true });
    return r.result?.result?.value ?? r.result?.exceptionDetails?.exception?.description;
};
const esperarCarga = () => new Promise((r) => { cargo = r; setTimeout(r, 10000); });
const ir = async (ruta) => { const p = esperarCarga(); await send('Page.navigate', { url: sitio + ruta }); await p; await sleep(1200); };
const captura = async (nombre) => {
    const r = await send('Page.captureScreenshot', { format: 'png', captureBeyondViewport: false });
    writeFileSync(`${carpeta}/${nombre}.png`, Buffer.from(r.result.data, 'base64'));
};
// Avisos de PHP en el HTML (WordPress los imprime como <b>Warning</b>:).
const avisosPhp = () => ev(`return (document.documentElement.outerHTML.match(/<b>(warning|notice|deprecated|fatal error)<\\/b>|Undefined (array key|variable)/gi) || []).length;`);
const drawer = () => ev(`
    const d = document.getElementById('carrito');
    const c = document.querySelector('[data-carrito-contenido]');
    return {
        abierto: d?.open ?? null,
        items: [...(c?.querySelectorAll('li') || [])].map(li => li.innerText.replace(/\\s+/g, ' ').trim()),
        subtotal: c?.querySelector('.text-h4')?.textContent.trim() ?? null,
        sugerida: c?.querySelector('#carrito-sugerida-titulo')?.nextElementSibling?.innerText.replace(/\\s+/g, ' ').trim() ?? null,
        enCarrito: c?.dataset.enCarrito,
        cuenta: document.querySelector('[data-carrito-cuenta]')?.innerText.replace(/\\s+/g, ' ').trim(),
    };`);
const esperarDrawer = (antes) => ev(`
    for (let i = 0; i < 40; i++) {
        const c = document.querySelector('[data-carrito-contenido]');
        if (c && c.getAttribute('aria-busy') !== 'true' && c.dataset.enCarrito !== ${JSON.stringify(antes)}) return true;
        await sleep(250);
    }
    return false;`);

const out = {};
try {
    for (let i = 0; i < 40 && !ws; i++) {
        try {
            const page = (await (await fetch('http://127.0.0.1:9341/json')).json()).find((t) => t.type === 'page');
            if (page) {
                ws = new WebSocket(page.webSocketDebuggerUrl);
                await new Promise((r) => ws.addEventListener('open', r));
                ws.addEventListener('message', (e) => {
                    const m = JSON.parse(e.data);
                    if (m.id && pending.has(m.id)) { pending.get(m.id)(m); pending.delete(m.id); }
                    if (m.method === 'Page.loadEventFired' && cargo) { const c = cargo; cargo = null; c(); }
                    if (m.method === 'Runtime.exceptionThrown') errores.push(m.params.exceptionDetails.exception?.description?.slice(0, 200));
                });
            }
        } catch {}
        if (!ws) await sleep(250);
    }
    await send('Page.enable');
    await send('Runtime.enable');
    await send('Network.enable');
    await send('Network.clearBrowserCookies');

    // 1. Landing de venta directa: precio de Woo, "antes" y botón que agrega y abre el drawer.
    await ir('/soluciones-de-sueno/recien-nacidos-go/');
    out.landing = await ev(`
        const tarjeta = document.querySelector('[data-cupon-hero]')?.closest('div');
        return {
            precio: tarjeta?.querySelector('.text-h3')?.textContent.trim(),
            nota: [...(tarjeta?.querySelectorAll('p') || [])].map(p => p.textContent.trim()).find(t => t.includes('lanzamiento')),
            relacionados: [...document.querySelectorAll('main section:last-of-type li, main section:last-of-type article')].map(e => e.innerText.match(/\\$\\d+/)?.[0]).filter(Boolean),
        };`);
    out.landing.avisosPhp = await avisosPhp();
    let antes = (await drawer()).enCarrito;
    await ev(`document.querySelector('[data-solucion-agregar][data-productos]:not([data-regalo])').click(); return true;`);
    out.agregarDirecta = { listo: await esperarDrawer(antes), ...(await drawer()) };
    await captura('1-drawer');

    // 2. Etapas: el selector suma con el descuento y agrega dos cursos.
    await ir('/soluciones-de-sueno/horarios-ideales/');
    out.etapasHero = await ev(`return document.querySelector('[data-cupon-hero]')?.closest('div')?.querySelector('.text-h3')?.textContent.trim();`);
    out.selector = await ev(`
        document.querySelector('[data-modal-open="selecciona-edad"]').click(); await sleep(400);
        const cajas = [...document.querySelectorAll('[data-solucion-edades] input[type=checkbox]')];
        cajas[0].click(); cajas[1].click(); await sleep(100);
        const dos = { total: document.querySelector('[data-solucion-total]').textContent, ahorro: document.querySelector('[data-solucion-ahorro]').textContent };
        cajas[2].click(); await sleep(100);
        const tres = { total: document.querySelector('[data-solucion-total]').textContent, ahorro: document.querySelector('[data-solucion-ahorro]').textContent };
        cajas[2].click(); await sleep(100);
        return { valores: cajas.map(c => c.value), dos, tres };`);
    antes = (await drawer()).enCarrito;
    await ev(`document.querySelector('[data-solucion-edades] [data-solucion-agregar]').click(); return true;`);
    out.agregarEtapas = { listo: await esperarDrawer(antes), ...(await drawer()) };

    // 3. Regalo: el mismo curso de otra landing, marcado como regalo; luego se quita desde el drawer.
    await ir('/soluciones-de-sueno/a-dormir-por-fin/');
    antes = (await drawer()).enCarrito;
    await ev(`document.querySelector('[data-solucion-agregar][data-regalo="1"]').click(); return true;`);
    out.regalo = { listo: await esperarDrawer(antes), ...(await drawer()) };
    antes = (await drawer()).enCarrito;
    await ev(`[...document.querySelectorAll('[data-carrito-contenido] li')].find(li => li.innerText.includes('regalo')).querySelector('[data-carrito-eliminar]').click(); return true;`);
    out.quitar = { listo: await esperarDrawer(antes), ...(await drawer()), foco: await ev(`return document.activeElement?.outerHTML.slice(0, 80);`) };

    // 4. Cupón del hero con un código que no existe: aviso de Woo sin salir de la landing.
    out.cuponHero = await ev(`
        const f = document.querySelector('[data-cupon-hero]');
        f.coupon_code.value = 'NOEXISTE'; f.requestSubmit();
        for (let i = 0; i < 30; i++) { const t = f.querySelector('[data-cupon-mensaje]').textContent; if (t) return { url: location.pathname, mensaje: t }; await sleep(250); }
        return { url: location.pathname, mensaje: null };`);

    // 5. Carrito: líneas reales, descuento por etapas y totales.
    await ir('/carrito/');
    out.carrito = await ev(`return {
        h1: document.querySelector('h1')?.textContent.trim(),
        lineas: document.querySelectorAll('.woocommerce-cart-form__cart-item, .woocommerce-cart-form li').length,
        resumen: document.querySelector('main aside, main [aria-labelledby^="resumen-compra"]')?.innerText.replace(/\\s+/g, ' ').trim(),
        nonce: !!document.querySelector('[name="woocommerce-cart-nonce"]'),
    };`);
    out.carrito.avisosPhp = await avisosPhp();
    await captura('2-carrito');

    // 6. Checkout sin sesión: campos del plugin, prefijo, flecha del botón, menú del header visible.
    await ir('/finalizar-compra/');
    await sleep(2500); // update_order_review de Woo
    out.checkout = await ev(`
        const prefijo = document.getElementById('billing_phone_prefijo');
        const nav = document.querySelector('header nav');
        return {
            campos: [...document.querySelectorAll('form.checkout [name]')].map(c => c.name).filter(n => /^(billing|account)_/.test(n)),
            prefijo: prefijo ? { valor: prefijo.value, ancho: Math.round(prefijo.getBoundingClientRect().width), opciones: prefijo.options.length } : null,
            flecha: !!document.querySelector('#place_order svg'),
            textoBoton: document.querySelector('#place_order')?.textContent.trim(),
            pasarelas: [...document.querySelectorAll('.wc_payment_methods li')].map(li => li.className),
            sinPasarela: document.querySelector('.woocommerce-notice, .woocommerce-info')?.textContent.trim() ?? null,
            login: !!document.querySelector('form.login [name="woocommerce-login-nonce"]'),
            cuponVisible: getComputedStyle(document.querySelector('form.checkout_coupon')).display !== 'none',
            navVisible: nav ? getComputedStyle(nav).visibility : null,
            resumen: document.querySelector('.woocommerce-checkout-review-order-table')?.innerText.replace(/\\s+/g, ' ').trim(),
            nonce: !!document.querySelector('[name="woocommerce-process-checkout-nonce"]'),
        };`);
    out.checkout.avisosPhp = await avisosPhp();
    await captura('3-checkout');
} catch (e) {
    out.error = String(e);
} finally {
    out.erroresJs = errores;
    console.log(JSON.stringify(out, null, 2));
    ws?.close();
    proc.kill();
}
