// Base de las pruebas en Chrome (CDP) de specs/pruebas: Chrome sin ventana, conexión y ayudas comunes.
// La usan los cdp-*.mjs nuevos; los anteriores llevan su propia copia de este código.
//
//   import { abrir } from './lib/cdp.mjs';
//   const c = await abrir({ puerto: 9344 });
//   await c.ir('/carrito/');
//   const titulo = await c.ev(`return document.title;`);
//   c.comprobar('tiene título', titulo !== '', titulo);
//   await c.terminar({ titulo });   // imprime el JSON y sale con 1 si alguna comprobación falló
//
// Variables: SITIO (por defecto http://angelitozzz.local) y CHROME (ruta de chrome.exe).
import { spawn } from 'node:child_process';
import { readFileSync, writeFileSync } from 'node:fs';
import { tmpdir } from 'node:os';

export const sitio = process.env.SITIO || 'http://angelitozzz.local';
export const sleep = (ms) => new Promise((r) => setTimeout(r, ms));

/** Lo que deja `datos-f5.php crear` para las pruebas (URL de gracias y códigos de regalo), o null. */
export function datosF5() {
    try {
        return JSON.parse(readFileSync(`${tmpdir()}/angelitozzz-datos-f5.json`, 'utf8'));
    } catch {
        return null;
    }
}

/** Puerto de un servicio del sitio en Local (sites.json), p. ej. ('mailpit', 'WEB'). */
export function puertoLocal(servicio, nombre) {
    try {
        const sitios = JSON.parse(readFileSync(`${process.env.APPDATA}/Local/sites.json`, 'utf8'));
        const host = new URL(sitio).hostname;
        const local = Object.values(sitios).find((s) => (s.domain || '') === host) || Object.values(sitios).find((s) => (s.name || '') === host.split('.')[0]);
        return local?.services?.[servicio]?.ports?.[nombre]?.[0] ?? null;
    } catch {
        return null;
    }
}

export async function abrir({ puerto, ancho = 1440, alto = 1000 }) {
    if (!puerto) throw new Error('Falta el puerto de depuración.');
    // Chrome sin ventana no baja de 500 px de ancho: los anchos de móvil se emulan más abajo.
    const proc = spawn(process.env.CHROME || 'C:/Program Files/Google/Chrome/Application/chrome.exe', ['--headless=new', '--disable-gpu', `--remote-debugging-port=${puerto}`, `--window-size=${Math.max(ancho, 500)},${alto}`, 'about:blank'], { stdio: 'ignore' });

    let ws;
    let id = 0;
    let cargo = null;
    const pendientes = new Map();
    const erroresJs = [];
    const fallos = [];

    for (let i = 0; i < 40 && !ws; i++) {
        try {
            const pagina = (await (await fetch(`http://127.0.0.1:${puerto}/json`)).json()).find((t) => t.type === 'page');
            if (pagina) {
                ws = new WebSocket(pagina.webSocketDebuggerUrl);
                await new Promise((r) => ws.addEventListener('open', r));
            }
        } catch {}
        if (!ws) await sleep(250);
    }
    if (!ws) {
        proc.kill();
        throw new Error(`No pude conectar con Chrome en el puerto ${puerto} (¿otro script lo usa?).`);
    }
    ws.addEventListener('message', (e) => {
        const m = JSON.parse(e.data);
        if (m.id && pendientes.has(m.id)) {
            pendientes.get(m.id)(m);
            pendientes.delete(m.id);
        }
        if (m.method === 'Page.loadEventFired' && cargo) {
            const c = cargo;
            cargo = null;
            c();
        }
        if (m.method === 'Runtime.exceptionThrown') erroresJs.push(m.params.exceptionDetails.exception?.description || m.params.exceptionDetails.text);
    });

    const send = (method, params = {}) => new Promise((r) => {
        const n = ++id;
        pendientes.set(n, r);
        ws.send(JSON.stringify({ id: n, method, params }));
    });
    await send('Page.enable');
    await send('Runtime.enable');
    await send('Network.enable');
    if (ancho < 768) {
        // Móvil de verdad (390, 360, 320…): sin esto la ventana mide 500 px aunque se pida menos.
        await send('Emulation.setDeviceMetricsOverride', { width: ancho, height: alto, deviceScaleFactor: 1, mobile: true });
    }

    // Evalúa el cuerpo de una función async en la página y devuelve su valor (o { error } si lanza).
    const ev = async (cuerpo) => {
        const r = await send('Runtime.evaluate', { expression: `(async () => { ${cuerpo} })()`, awaitPromise: true, returnByValue: true });
        if (r.result?.exceptionDetails) return { error: r.result.exceptionDetails.exception?.description || r.result.exceptionDetails.text };
        return r.result?.result?.value;
    };
    const esperarCarga = (ms = 15000) => new Promise((r) => {
        cargo = r;
        setTimeout(r, ms);
    });
    // Navega a una ruta del sitio (o URL completa) y espera la carga.
    const ir = async (ruta, pausa = 700) => {
        const p = esperarCarga();
        await send('Page.navigate', { url: /^https?:/.test(ruta) ? ruta : sitio + ruta });
        await p;
        await sleep(pausa);
    };
    // Ejecuta algo que provoca una navegación (enviar un form, un clic) y espera la página nueva.
    const conNavegacion = async (cuerpo, pausa = 700) => {
        const p = esperarCarga();
        await ev(cuerpo);
        await p;
        await sleep(pausa);
    };
    const tecla = async (key, code = key) => {
        const vk = { Escape: 27, Enter: 13, Tab: 9, ' ': 32 }[key] ?? 0;
        // Enter y Espacio llevan su carácter: sin él, Chrome no "activa" el elemento (summary, botón).
        const text = { Enter: '\r', ' ': ' ' }[key];
        await send('Input.dispatchKeyEvent', { type: 'keyDown', key, code, windowsVirtualKeyCode: vk, ...(text ? { text } : {}) });
        await send('Input.dispatchKeyEvent', { type: 'keyUp', key, code, windowsVirtualKeyCode: vk });
        await sleep(300);
    };
    const clic = async (x, y) => {
        for (const type of ['mousePressed', 'mouseReleased']) await send('Input.dispatchMouseEvent', { type, x, y, button: 'left', clickCount: 1 });
        await sleep(400);
    };
    const foto = async (archivo) => {
        const r = await send('Page.captureScreenshot', { format: 'png' });
        writeFileSync(archivo, Buffer.from(r.result.data, 'base64'));
    };
    const sinJs = (apagado) => send('Emulation.setScriptExecutionDisabled', { value: apagado });
    const borrarCookies = () => send('Network.clearBrowserCookies');
    // ¿Hay avisos de PHP en la página actual?
    const avisosPhp = () => ev(`return ['Warning', 'Notice', 'Fatal error', 'Deprecated', 'Parse error'].filter((w) => document.body.innerHTML.includes(w + '</' + 'b>'));`);
    // Inicia sesión por /iniciar-sesion/ (formulario del plugin).
    const entrar = async (correo, clave = 'Prueba123') => {
        await borrarCookies();
        await ir('/iniciar-sesion/');
        await conNavegacion(`const f = document.querySelector('main form'); f.correo.value = ${JSON.stringify(correo)}; f.clave.value = ${JSON.stringify(clave)}; f.requestSubmit(); return 1;`);
        return ev(`return document.body.classList.contains('logged-in');`);
    };

    const comprobar = (nombre, ok, detalle) => {
        if (!ok) fallos.push({ nombre, detalle });
        return ok;
    };
    const terminar = async (salida) => {
        const resultado = { ...salida, erroresJs, fallos, ok: fallos.length === 0 && erroresJs.length === 0 };
        console.log(JSON.stringify(resultado, null, 2));
        try { ws.close(); } catch {}
        proc.kill();
        process.exit(resultado.ok ? 0 : 1);
    };
    const cerrar = () => {
        try { ws.close(); } catch {}
        proc.kill();
    };

    return { send, ev, ir, conNavegacion, tecla, clic, foto, sinJs, borrarCookies, avisosPhp, entrar, comprobar, terminar, cerrar, erroresJs, fallos };
}
