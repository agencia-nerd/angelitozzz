// Verificación conjunta del bloque 5 por CDP: entra con la alumna de prueba (contraseña correcta) y revisa
// cada vista de la cuenta y las lecciones; también /mi-cuenta/ y /cuenta/ sin sesión.
import { spawn } from 'node:child_process';

const sitio = process.env.SITIO || 'http://angelitozzz.local'; // Otra URL: SITIO=http://... node <script>
const proc = spawn(process.env.CHROME || 'C:/Program Files/Google/Chrome/Application/chrome.exe', ['--headless=new', '--disable-gpu', '--remote-debugging-port=9338', '--window-size=1440,1000', 'about:blank'], { stdio: 'ignore' });
const sleep = (ms) => new Promise((r) => setTimeout(r, ms));
let ws, id = 0, cargo = null;
const pending = new Map();
const errores = [];
const send = (method, params = {}) => new Promise((r) => { const n = ++id; pending.set(n, r); ws.send(JSON.stringify({ id: n, method, params })); });
const ev = async (expr) => {
    const r = await send('Runtime.evaluate', { expression: `(async () => { ${expr} })()`, awaitPromise: true, returnByValue: true });
    return r.result?.result?.value ?? r.result?.exceptionDetails?.exception?.description;
};
const esperar = () => new Promise((r) => { cargo = r; setTimeout(r, 8000); });
const ir = async (ruta) => { const p = esperar(); await send('Page.navigate', { url: sitio + ruta }); await p; await sleep(700); };
const revisar = () => ev(`return {
    url: location.pathname + location.search,
    h1: document.querySelectorAll('h1').length,
    noindex: !!document.querySelector('meta[name=robots][content*=noindex]'),
    php: /<b>(warning|notice|deprecated|fatal error)<\\/b>|Undefined (array key|variable)/i.test(document.documentElement.innerHTML),
    desborde: document.documentElement.scrollWidth - window.innerWidth,
};`);

const out = { escritorio: {}, movil: {} };
try {
    for (let i = 0; i < 40 && !ws; i++) {
        try {
            const page = (await (await fetch('http://127.0.0.1:9338/json')).json()).find((t) => t.type === 'page');
            if (page) {
                ws = new WebSocket(page.webSocketDebuggerUrl);
                await new Promise((r) => ws.addEventListener('open', r));
                ws.addEventListener('message', (e) => {
                    const m = JSON.parse(e.data);
                    if (m.id && pending.has(m.id)) { pending.get(m.id)(m); pending.delete(m.id); }
                    if (m.method === 'Page.loadEventFired' && cargo) { const c = cargo; cargo = null; c(); }
                    if (m.method === 'Runtime.exceptionThrown') errores.push(m.params.exceptionDetails.exception?.description?.slice(0, 160));
                });
            }
        } catch {}
        if (!ws) await sleep(250);
    }
    await send('Page.enable');
    await send('Runtime.enable');
    await send('Network.enable');
    await send('Network.clearBrowserCookies');

    await ir('/cuenta/cursos/');
    out.sinSesion = (await revisar()).url;
    await ir('/mi-cuenta/');
    out.miCuentaSinSesion = (await revisar()).url;

    // Entrar (contraseña correcta): vuelve a /cuenta/cursos/.
    await ir('/iniciar-sesion/?redirect_to=' + encodeURIComponent(sitio + '/cuenta/cursos/'));
    const p = esperar();
    await ev(`const f = document.querySelector('main form'); f.correo.value = 'prueba-f5@angelitozzz.local'; f.clave.value = 'Prueba123'; f.requestSubmit(); return 1;`);
    await p; await sleep(700);
    out.trasEntrar = (await revisar()).url;
    await ir('/mi-cuenta/');
    out.miCuentaConSesion = (await revisar()).url;

    const rutas = ['/cuenta/', '/cuenta/perfil/', '/cuenta/cursos/?pestana=inscritos', '/cuenta/cursos/?pestana=completados', '/cuenta/pedidos/',
        '/cursos/curso-de-prueba-f5/video-1-bienvenida/', '/cursos/curso-de-prueba-f5/video-3-la-rutina-de-la-noche/', '/cursos/angelita-pro-de-prueba-f5/conoce-a-angelita/'];
    for (const r of rutas) { await ir(r); out.escritorio[r] = await revisar(); }
    await ir('/cuenta/');
    out.menu = await ev(`return [...document.querySelectorAll('nav[aria-label="Mi cuenta"] a')].map(a => a.textContent.trim() + (a.getAttribute('aria-current') ? ' (actual)' : ''));`);
    await ir('/cuenta/pedidos/');
    out.pedidos = await ev(`return [...document.querySelectorAll('tbody tr')].map(tr => tr.innerText.replace(/\\s+/g, ' ').trim());`);

    await send('Emulation.setDeviceMetricsOverride', { width: 390, height: 844, deviceScaleFactor: 1, mobile: true });
    for (const r of rutas) { await ir(r); out.movil[r] = (await revisar()).desborde; }
} catch (e) {
    out.fallo = String(e);
} finally {
    out.erroresJs = errores;
    console.log(JSON.stringify(out, null, 1));
    proc.kill();
    process.exit(0);
}
