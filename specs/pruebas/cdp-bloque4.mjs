// Prueba de punta a punta del bloque 4 (acceso) por CDP, contra el Local y el plugin real.
// Crea usuarias de prueba (@angelitozzz.local); el limpiado lo hace otro script por CLI.
import { spawn } from 'node:child_process';

const sitio = process.env.SITIO || 'http://angelitozzz.local'; // Otra URL: SITIO=http://... node <script>
const reset = process.argv[2] || '';
const solo3 = process.argv[3] === 'solo3'; // URL del paso 3 generada por CLI (opcional)
const proc = spawn(process.env.CHROME || 'C:/Program Files/Google/Chrome/Application/chrome.exe', ['--headless=new', '--disable-gpu', '--remote-debugging-port=9337', '--window-size=1440,1000', 'about:blank'], { stdio: 'ignore' });
const sleep = (ms) => new Promise((r) => setTimeout(r, ms));
let ws, id = 0;
const pending = new Map();
const errores = [];
let cargo = null;
const send = (method, params = {}) => new Promise((r) => { const n = ++id; pending.set(n, r); ws.send(JSON.stringify({ id: n, method, params })); });
const ev = async (expr) => {
    const r = await send('Runtime.evaluate', { expression: `(async () => { ${expr} })()`, awaitPromise: true, returnByValue: true });
    return r.result?.result?.value ?? r.result?.exceptionDetails?.exception?.description;
};
const esperarCarga = () => new Promise((r) => { cargo = r; setTimeout(r, 8000); });
const ir = async (ruta) => { const p = esperarCarga(); await send('Page.navigate', { url: sitio + ruta }); await p; await sleep(600); };
const enviar = async (rellenar) => {
    const p = esperarCarga();
    await ev(`${rellenar}; document.querySelector('main form').requestSubmit(); return true;`);
    await p; await sleep(600);
};
const estado = () => ev(`return {
    url: location.pathname + location.search,
    h1: document.querySelector('h1')?.textContent.trim(),
    alerta: document.querySelector('[role=alert]')?.textContent.trim() || null,
    aviso: document.querySelector('[role=status]')?.textContent.trim() || null,
    campos: Object.fromEntries([...document.querySelectorAll('main form input:not([type=hidden]):not([type=checkbox])')].map(i => [i.name, i.value])),
};`);

const out = {};
try {
    for (let i = 0; i < 40 && !ws; i++) {
        try {
            const page = (await (await fetch('http://127.0.0.1:9337/json')).json()).find((t) => t.type === 'page');
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

    const llenar = (clave) => `
        const f = document.querySelector('main form');
        f.nombre.value = 'Prueba'; f.apellidos.value = 'Cuatro'; f.correo.value = 'prueba-f4@angelitozzz.local';
        f.clave.value = '${clave}'; f.terminos.checked = true;`;

    if (!solo3) {
    // 1. Crear cuenta: interfaz, contraseña débil, luego válida.
    await ir('/crear-cuenta/');
    out.crearUi = await ev(`
        const sleep = (ms) => new Promise(r => setTimeout(r, ms));
        const ojo = document.querySelector('[data-ver-contrasena-boton]');
        const campo = document.getElementById('crear-cuenta-clave');
        const antes = { visible: !ojo.hidden, pressed: ojo.getAttribute('aria-pressed'), tipo: campo.type, nombre: ojo.textContent.trim() };
        ojo.click(); await sleep(100);
        const despues = { pressed: ojo.getAttribute('aria-pressed'), tipo: campo.type };
        ojo.click();
        campo.value = 'Abcdefg1'; campo.dispatchEvent(new Event('input', { bubbles: true })); await sleep(150);
        const reglas = document.querySelector('[data-reglas-contrasena]')?.innerText.replace(/\\s+/g, ' ');
        const terminos = document.getElementById('crear-cuenta-terminos');
        return { antes, despues, reglas, terminosMarcada: terminos.checked, terminosRequerida: terminos.required, labels: [...document.querySelectorAll('main input:not([type=hidden])')].every(i => i.labels?.length) };
    `);
    await enviar(llenar('abc'));
    out.crearDebil = await estado();
    await enviar(llenar('Prueba123'));
    out.crearBien = { ...(await estado()), sesion: (await send('Network.getCookies', { urls: [sitio] })).result.cookies.some((c) => c.name.startsWith('wordpress_logged_in')) };
    await ir('/');
    out.headerConSesion = await ev(`return [...document.querySelectorAll('header a')].map(a => a.textContent.trim()).find(t => /cuenta|sesi/i.test(t));`);

    // 2. Correo repetido (sin sesión).
    await send('Network.clearBrowserCookies');
    await ir('/crear-cuenta/');
    await enviar(llenar('Prueba123'));
    out.crearRepetido = await estado();

    // 3. Iniciar sesión: errada y bien con vuelta a la página de origen.
    await ir('/iniciar-sesion/?redirect_to=' + encodeURIComponent(sitio + '/soluciones-de-sueno/'));
    await enviar(`const f = document.querySelector('main form'); f.correo.value = 'prueba-f4@angelitozzz.local'; f.clave.value = 'Errada999'`);
    out.loginErrado = await estado();
    await ir('/iniciar-sesion/?redirect_to=' + encodeURIComponent(sitio + '/soluciones-de-sueno/'));
    await enviar(`const f = document.querySelector('main form'); f.correo.value = 'prueba-f4@angelitozzz.local'; f.clave.value = 'Prueba123'`);
    out.loginBien = await estado();
    await ir('/iniciar-sesion/?redirect_to=' + encodeURIComponent('https://example.com/robo'));
    out.redirectExterno = await ev(`return document.querySelector('input[name=redirect_to]')?.value ?? '(sin campo)';`);

    // 4. Recuperar: correo que existe y que no (mismo mensaje).
    await send('Network.clearBrowserCookies');
    await ir('/recuperar-contrasena/');
    await enviar(`document.querySelector('main form').correo.value = 'prueba-f4@angelitozzz.local'`);
    out.recuperarExiste = await estado();
    await ir('/recuperar-contrasena/');
    await enviar(`document.querySelector('main form').correo.value = 'nadie-f4@angelitozzz.local'`);
    out.recuperarNoExiste = await estado();
    await ir('/recuperar-contrasena/?key=malo&login=prueba-f4');
    out.enlaceVencido = await estado();

    }
    // 5. Paso 3 con una clave real: distintas, luego bien; después entrar con la nueva.
    if (reset) {
        await ir(reset.replace(sitio, ''));
        out.paso3 = await estado();
        await enviar(`const f = document.querySelector('main form'); f.clave.value = 'Nueva1234'; f.clave2.value = 'Otra12345'`);
        out.paso3Distintas = await estado();
        await enviar(`const f = document.querySelector('main form'); f.clave.value = 'Nueva1234'; f.clave2.value = 'Nueva1234'`);
        out.paso3Bien = await estado();
        await enviar(`const f = document.querySelector('main form'); f.correo.value = 'prueba-f4@angelitozzz.local'; f.clave.value = 'Nueva1234'`);
        out.loginNueva = await estado();
    }

    if (!solo3) {
    // 6. Móvil: sin scroll horizontal.
    await send('Network.clearBrowserCookies');
    await send('Emulation.setDeviceMetricsOverride', { width: 390, height: 844, deviceScaleFactor: 1, mobile: true });
    out.desborde = {};
    for (const r of ['/iniciar-sesion/', '/crear-cuenta/', '/recuperar-contrasena/']) {
        await ir(r);
        out.desborde[r] = await ev(`return document.documentElement.scrollWidth - window.innerWidth;`);
    }
    await send('Emulation.clearDeviceMetricsOverride');

    // 7. Bloqueo: 5 fallos con otra cuenta (al final: también suma a la IP).
    out.bloqueo = [];
    for (let i = 0; i < 6; i++) {
        await ir('/iniciar-sesion/');
        await enviar(`const f = document.querySelector('main form'); f.correo.value = 'bloqueo-f4@angelitozzz.local'; f.clave.value = 'Errada999'`);
        out.bloqueo.push((await estado()).alerta);
    }
    }
} catch (e) {
    out.fallo = String(e);
} finally {
    out.erroresJs = errores;
    console.log(JSON.stringify(out, null, 1));
    proc.kill();
    process.exit(0);
}
