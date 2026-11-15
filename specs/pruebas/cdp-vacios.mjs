// Estados vacíos de la cuenta con una usuaria sin cursos ni pedidos (vacia-f5@angelitozzz.local / Prueba123).
import { spawn } from 'node:child_process';

const sitio = process.env.SITIO || 'http://angelitozzz.local'; // Otra URL: SITIO=http://... node <script>
const proc = spawn(process.env.CHROME || 'C:/Program Files/Google/Chrome/Application/chrome.exe', ['--headless=new', '--disable-gpu', '--remote-debugging-port=9339', '--window-size=1440,1000', 'about:blank'], { stdio: 'ignore' });
const sleep = (ms) => new Promise((r) => setTimeout(r, ms));
let ws, id = 0, cargo = null;
const pending = new Map();
const send = (method, params = {}) => new Promise((r) => { const n = ++id; pending.set(n, r); ws.send(JSON.stringify({ id: n, method, params })); });
const ev = async (expr) => (await send('Runtime.evaluate', { expression: `(async () => { ${expr} })()`, awaitPromise: true, returnByValue: true })).result?.result?.value;
const esperar = () => new Promise((r) => { cargo = r; setTimeout(r, 8000); });
const ir = async (ruta) => { const p = esperar(); await send('Page.navigate', { url: sitio + ruta }); await p; await sleep(600); };

const out = {};
try {
    for (let i = 0; i < 40 && !ws; i++) {
        try {
            const page = (await (await fetch('http://127.0.0.1:9339/json')).json()).find((t) => t.type === 'page');
            if (page) {
                ws = new WebSocket(page.webSocketDebuggerUrl);
                await new Promise((r) => ws.addEventListener('open', r));
                ws.addEventListener('message', (e) => {
                    const m = JSON.parse(e.data);
                    if (m.id && pending.has(m.id)) { pending.get(m.id)(m); pending.delete(m.id); }
                    if (m.method === 'Page.loadEventFired' && cargo) { const c = cargo; cargo = null; c(); }
                });
            }
        } catch {}
        if (!ws) await sleep(250);
    }
    await send('Page.enable');
    await send('Network.enable');
    await send('Network.clearBrowserCookies');
    await ir('/iniciar-sesion/');
    const p = esperar();
    await ev(`const f = document.querySelector('main form'); f.correo.value = 'vacia-f5@angelitozzz.local'; f.clave.value = 'Prueba123'; f.requestSubmit(); return 1;`);
    await p; await sleep(600);
    for (const r of ['/cuenta/', '/cuenta/cursos/?pestana=inscritos', '/cuenta/cursos/?pestana=activos', '/cuenta/cursos/?pestana=completados', '/cuenta/pedidos/']) {
        await ir(r);
        out[r] = await ev(`const m = document.querySelector('main'); return { texto: [...m.querySelectorAll('p, h2')].map(n => n.textContent.trim()).filter(t => /aún no/i.test(t)), boton: [...m.querySelectorAll('a')].map(a => a.textContent.trim()).filter(t => /soluciones/i.test(t)), tarjetas: m.querySelectorAll('article').length, tabla: !!m.querySelector('table') };`);
    }
} catch (e) {
    out.fallo = String(e);
} finally {
    console.log(JSON.stringify(out, null, 1));
    proc.kill();
    process.exit(0);
}
