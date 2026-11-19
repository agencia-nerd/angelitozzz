// Pago de punta a punta con Openpay en sandbox (F5-b), contra el Local: agrega Recién Nacidos Go desde su landing,
// llena el checkout sin sesión (crea la cuenta prueba-pago-<hora>@angelitozzz.local / Prueba123) y paga con una
// tarjeta de prueba de Openpay. Necesita `datos-f5.php tienda` y las llaves de sandbox cargadas en Woo.
// Uso: node cdp-pago.mjs <carpeta para capturas> [aprobada|rechazada]   (SITIO y CHROME como variables de entorno)
// Deja un pedido y una usuaria de prueba: `datos-f5.php tienda-borrar` borra el pedido; la usuaria, aparte.
// LÍMITE (2026-11-18): el sandbox de Openpay responde 403 sin CORS a este Chrome sin ventana, así que el token no se
// crea y sale "ERROR undefined". Sirve para revisar que el checkout se llena y lo que pide Openpay (lista `red`);
// el pago en sí se prueba a mano en un navegador normal (pasos en LEEME.md).
import { spawn } from 'node:child_process';
import { writeFileSync, mkdirSync } from 'node:fs';

const sitio = process.env.SITIO || 'http://angelitozzz.local';
const carpeta = process.argv[2] || '.';
const caso = process.argv[3] || 'aprobada';
const tarjetas = { aprobada: '4111111111111111', rechazada: '4222222222222220' };
mkdirSync(carpeta, { recursive: true });
const proc = spawn(process.env.CHROME || 'C:/Program Files/Google/Chrome/Application/chrome.exe', ['--headless=new', '--disable-gpu', '--remote-debugging-port=9343', '--window-size=1440,1000', 'about:blank'], { stdio: 'ignore' });
const sleep = (ms) => new Promise((r) => setTimeout(r, ms));
let ws, id = 0, cargo = null;
const pending = new Map();
const errores = [];
const red = []; // Peticiones a Openpay (sin cuerpos: solo URL sin la ruta del comercio, estado y error).
const limpiar = (u) => u.replace(/\/v1\/[^/]+\//, '/v1/<comercio>/').split('?')[0];
const send = (method, params = {}) => new Promise((r) => { const n = ++id; pending.set(n, r); ws.send(JSON.stringify({ id: n, method, params })); });
const ev = async (expr) => {
    const r = await send('Runtime.evaluate', { expression: `(async () => { const sleep = (ms) => new Promise(r => setTimeout(r, ms)); ${expr} })()`, awaitPromise: true, returnByValue: true });
    return r.result?.result?.value ?? r.result?.exceptionDetails?.exception?.description;
};
const esperarCarga = (ms = 15000) => new Promise((r) => { cargo = r; setTimeout(r, ms); });
const ir = async (ruta) => { const p = esperarCarga(); await send('Page.navigate', { url: sitio + ruta }); await p; await sleep(1500); };
const captura = async (nombre) => {
    const r = await send('Page.captureScreenshot', { format: 'png' });
    writeFileSync(`${carpeta}/${nombre}.png`, Buffer.from(r.result.data, 'base64'));
};

const out = { caso };
try {
    for (let i = 0; i < 40 && !ws; i++) {
        try {
            const page = (await (await fetch('http://127.0.0.1:9343/json')).json()).find((t) => t.type === 'page');
            if (page) {
                ws = new WebSocket(page.webSocketDebuggerUrl);
                await new Promise((r) => ws.addEventListener('open', r));
                ws.addEventListener('message', (e) => {
                    const m = JSON.parse(e.data);
                    if (m.id && pending.has(m.id)) { pending.get(m.id)(m); pending.delete(m.id); }
                    if (m.method === 'Page.loadEventFired' && cargo) { const c = cargo; cargo = null; c(); }
                    if (m.method === 'Network.responseReceived' && /openpay/i.test(m.params.response.url)) red.push({ url: limpiar(m.params.response.url), estado: m.params.response.status });
                    if (m.method === 'Network.loadingFailed') red.push({ id: m.params.requestId, error: m.params.errorText, bloqueo: m.params.blockedReason || m.params.corsErrorStatus?.corsError });
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

    await ir('/soluciones-de-sueno/recien-nacidos-go/');
    out.agregado = await ev(`
        document.querySelector('[data-solucion-agregar][data-productos]:not([data-regalo])').click();
        for (let i = 0; i < 40; i++) { if (document.querySelector('[data-carrito-contenido]')?.dataset.enCarrito) return document.querySelector('[data-carrito-contenido]').dataset.enCarrito; await sleep(250); }
        return null;`);

    await ir('/finalizar-compra/');
    await sleep(2500);
    const correo = `prueba-pago-${Date.now()}@angelitozzz.local`;
    out.correo = correo;
    out.formulario = await ev(`
        const poner = (sel, valor) => {
            const c = document.querySelector(sel);
            if (!c) return sel;
            c.focus(); c.value = valor;
            ['input', 'keyup', 'change', 'blur'].forEach(t => c.dispatchEvent(new Event(t, { bubbles: true })));
            return null;
        };
        const faltan = [
            poner('#billing_first_name', 'Prueba Pago'),
            poner('#billing_phone_prefijo', '+51'),
            poner('#billing_phone', '987654321'),
            poner('#billing_tipo_documento', 'dni'),
            poner('#billing_numero_documento', '12345678'),
            poner('#billing_email', ${JSON.stringify(correo)}),
            poner('#account_password', 'Prueba123'),
            poner('#openpay-holder-name', 'PRUEBA PAGO'),
            poner('#openpay-card-number', '${tarjetas[caso]}'),
            poner('#openpay-card-expiry', '12 / 30'),
            poner('#openpay-card-cvc', '123'),
        ].filter(Boolean);
        const terminos = document.getElementById('terms');
        if (terminos && !terminos.checked) terminos.click();
        await sleep(500);
        return { faltan, pasarela: document.querySelector('input[name=payment_method]:checked')?.value ?? document.querySelector('input[name=payment_method]')?.value };`);
    await captura(`pago-${caso}-1-checkout`);

    const p = esperarCarga(45000);
    await ev(`document.getElementById('place_order').click(); return true;`);
    // Si el pago se rechaza, Woo no navega: muestra el error en el checkout.
    const fin = await Promise.race([p.then(() => 'navego'), sleep(45000).then(() => 'tiempo')]);
    await sleep(2500);
    out.resultado = await ev(`return {
        fin: ${JSON.stringify(fin)},
        url: location.pathname + location.search,
        h1: document.querySelector('main h1, h1')?.textContent.trim(),
        estado: document.querySelector('main')?.innerText.replace(/\\s+/g, ' ').trim().slice(0, 400),
        errores: [...document.querySelectorAll('.woocommerce-error li, .woocommerce-error, .woocommerce-NoticeGroup [role=alert]')].map(e => e.textContent.replace(/\\s+/g, ' ').trim()).slice(0, 5),
        avisosPhp: (document.documentElement.outerHTML.match(/<b>(warning|notice|deprecated|fatal error)<\\/b>|Undefined (array key|variable)/gi) || []).length,
    };`);
    await captura(`pago-${caso}-2-resultado`);
} catch (e) {
    out.error = String(e);
} finally {
    out.erroresJs = errores;
    out.red = red;
    console.log(JSON.stringify(out, null, 2));
    ws?.close();
    proc.kill();
}
