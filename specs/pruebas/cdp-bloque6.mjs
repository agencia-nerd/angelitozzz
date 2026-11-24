// Verificación del bloque 6 (administrador) por CDP: /administrador/ sin sesión, con la alumna (va a
// /cuenta/) y con la instructora de prueba. Entra siempre con la contraseña correcta: nunca intentos
// fallidos (el límite del plugin bloquea la IP 15 min). Uso: node cdp-bloque6.mjs [lista]
import { spawn } from 'node:child_process';

const sitio = process.env.SITIO || 'http://angelitozzz.local'; // Otra URL: SITIO=http://... node <script>
const proc = spawn(process.env.CHROME || 'C:/Program Files/Google/Chrome/Application/chrome.exe', ['--headless=new', '--disable-gpu', '--remote-debugging-port=9339', '--window-size=1440,1000', 'about:blank'], { stdio: 'ignore' });
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
    titulo: document.title,
    h1: document.querySelectorAll('h1').length,
    noindex: !!document.querySelector('meta[name=robots][content*=noindex]'),
    php: /<b>(warning|notice|deprecated|fatal error)<\\/b>|Undefined (array key|variable)/i.test(document.documentElement.innerHTML),
    desborde: document.documentElement.scrollWidth - window.innerWidth,
};`);
const entrar = async (correo, destino) => {
    await send('Network.clearBrowserCookies');
    await ir('/iniciar-sesion/?redirect_to=' + encodeURIComponent(sitio + destino));
    const p = esperar();
    await ev(`const f = document.querySelector('main form'); f.correo.value = ${JSON.stringify(correo)}; f.clave.value = 'Prueba123'; f.requestSubmit(); return 1;`);
    await p; await sleep(700);
};

const out = { vistas: {}, movil: {} };
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

    await ir('/administrador/cursos/');
    out.sinSesion = (await revisar()).url;

    await entrar('prueba-f5@angelitozzz.local', '/administrador/');
    out.alumna = (await revisar()).url;
    // Con su nonce y un cuerpo válido: sin ellos WordPress responde 400 por los parámetros antes de mirar el
    // permiso, y eso no prueba nada. Lo esperado es 403 (rest_forbidden).
    out.alumnaApi = await ev(`const nonce = await (await fetch('/wp-admin/admin-ajax.php?action=rest-nonce')).text(); const r = await fetch('/wp-json/angelitozzz/v1/cursos/1/estado', { method: 'POST', headers: { 'X-WP-Nonce': nonce, 'Content-Type': 'application/json' }, body: JSON.stringify({ estado: 'borrador' }) }); return r.status;`);

    await entrar('prueba-instructora@angelitozzz.local', '/administrador/');
    out.instructora = (await revisar()).url;

    const rutas = ['/administrador/', '/administrador/cursos/', '/administrador/cursos/nuevo/', '/administrador/cursos/999999/editar/'];
    for (const r of rutas) { await ir(r); out.vistas[r] = await revisar(); }

    await ir('/administrador/');
    out.resumen = await ev(`return [...document.querySelectorAll('#admin-resumen-titulo + ul li')].map(li => li.innerText.replace(/\\s+/g, ' ').trim());`);
    out.tabla = await ev(`return { caption: document.querySelector('table caption')?.textContent.trim(), filas: [...document.querySelectorAll('tbody tr')].map(tr => tr.innerText.replace(/\\s+/g, ' ').trim()) };`);
    out.menu = await ev(`return [...document.querySelectorAll('nav[aria-label="Administrador de cursos"] a')].map(a => a.textContent.trim() + (a.getAttribute('aria-current') ? ' (actual)' : ''));`);

    if (process.argv[2] === 'lista') {
        // Lista: tarjetas y menú "Más opciones" con teclado (Esc cierra y devuelve el foco).
        await ir('/administrador/cursos/');
        out.tarjetas = await ev(`return [...document.querySelectorAll('[data-curso-card]')].map(t => t.innerText.replace(/\\s+/g, ' ').trim().slice(0, 140));`);
        out.menuOpciones = await ev(`
            const b = document.querySelector('[data-curso-card] [aria-expanded]');
            if (!b) return 'sin botón';
            b.focus(); b.click(); await new Promise(r => setTimeout(r, 200));
            const abierto = b.getAttribute('aria-expanded');
            const items = [...(document.getElementById(b.getAttribute('aria-controls'))?.querySelectorAll('a, button') ?? [])].map(i => i.textContent.trim());
            document.activeElement.dispatchEvent(new KeyboardEvent('keydown', { key: 'Escape', bubbles: true }));
            await new Promise(r => setTimeout(r, 200));
            return { abierto, items, cerrado: b.getAttribute('aria-expanded'), foco: document.activeElement === b };
        `);

        // Acciones reales por la API (cambian los datos de prueba; se borran con datos-f5.php borrar).
        const espera = (ms) => `await new Promise(r => setTimeout(r, ${ms}));`;
        const tarjeta = (titulo) => `[...document.querySelectorAll('[data-curso-card]')].find(t => t.dataset.titulo === ${JSON.stringify(titulo)})`;
        const accion = (titulo, cual) => `const ta = ${tarjeta(titulo)}; ta.querySelector('[data-curso-menu-boton]').click(); ${espera(100)} ta.querySelector('[data-accion="${cual}"]').click(); ${espera(1500)}`;
        out.publicarIncompleto = await ev(`${accion('Curso borrador F5', 'publicar')} return document.querySelector('[data-admin-error]').innerText.replace(/\\s+/g, ' ').trim();`);
        out.aBorrador = await ev(`${accion('Curso de prueba F5', 'borrador')} const t = ${tarjeta('Curso de prueba F5')}; return { estado: t.dataset.estado, etiqueta: !t.querySelector('[data-curso-etiqueta]').classList.contains('hidden') };`);
        out.publicar = await ev(`${accion('Curso de prueba F5', 'publicar')} const t = ${tarjeta('Curso de prueba F5')}; return { estado: t.dataset.estado, aviso: document.querySelector('[data-admin-aviso]').innerText.trim() };`);
        out.eliminarConEstudiantes = await ev(`${accion('Curso de prueba F5', 'eliminar')} const d = document.querySelector('dialog#eliminar'); const r = { abierto: d.open, titulo: d.querySelector('#eliminar-title').innerText, texto: d.querySelector('#eliminar-description').innerText, foco: document.activeElement.innerText.trim(), botones: [...d.querySelectorAll('button')].filter(b => !b.hidden && b.offsetParent).map(b => b.innerText.trim()) }; d.querySelector('[data-eliminar-cancelar]').click(); ${espera(300)} r.cerrado = !d.open; return r;`);
        out.eliminarSinEstudiantes = await ev(`${accion('Curso borrador F5', 'eliminar')} const d = document.querySelector('dialog#eliminar'); const r = { titulo: d.querySelector('#eliminar-title').innerText, foco: document.activeElement.innerText.trim() }; d.querySelector('[data-eliminar-confirmar]').click(); ${espera(1500)} r.quedaTarjeta = !!${tarjeta('Curso borrador F5')}; return r;`);
        await ir('/administrador/cursos/?publicado=' + (await ev(`return ${tarjeta('Curso de prueba F5')}.dataset.id;`)));
        out.avisoAlVolver = await ev(`return { aviso: document.querySelector('[data-admin-aviso]').innerText.trim(), url: location.search };`);
    }

    await send('Emulation.setDeviceMetricsOverride', { width: 390, height: 844, deviceScaleFactor: 1, mobile: true });
    for (const r of rutas.slice(0, 3)) { await ir(r); out.movil[r] = (await revisar()).desborde; }
} catch (e) {
    out.fallo = String(e);
} finally {
    out.erroresJs = errores;
    console.log(JSON.stringify(out, null, 1));
    proc.kill();
    process.exit(0);
}
