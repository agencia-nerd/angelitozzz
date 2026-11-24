// Recorrido del constructor (bloque 6b) por CDP con la instructora de prueba (datos-f5.php crear):
// crea un curso de cero (paso 1 con error del servidor y portada), agrega módulo, lecciones con video de
// Vimeo, texto y PDF, ordena con teclado, elimina, y lo publica (paso 3). Contraseña siempre correcta.
// F5-c: el enlace del editor y "¿Descartar los cambios?" son paneles del mismo modal. Si aparece una ventana
// nativa del navegador (alert, confirm o prompt), se cierra sin aceptar y queda en out.dialogosNativos (falla).
// Uso: node cdp-constructor.mjs <carpeta temporal para el PNG y el PDF>
import { spawn } from 'node:child_process';
import fs from 'node:fs';
import path from 'node:path';
import zlib from 'node:zlib';

const sitio = process.env.SITIO || 'http://angelitozzz.local'; // Otra URL: SITIO=http://... node <script>
const temporal = process.argv[2];
if (!temporal) { console.error('Falta la carpeta temporal'); process.exit(1); }

// PNG de 4 × 4 y PDF mínimo para las subidas.
const crc = (buf) => { let c, t = []; for (let n = 0; n < 256; n++) { c = n; for (let k = 0; k < 8; k++) c = c & 1 ? 0xedb88320 ^ (c >>> 1) : c >>> 1; t[n] = c >>> 0; } let r = 0xffffffff; for (const b of buf) r = t[(r ^ b) & 0xff] ^ (r >>> 8); return (r ^ 0xffffffff) >>> 0; };
const trozo = (tipo, datos) => { const l = Buffer.alloc(4); l.writeUInt32BE(datos.length); const td = Buffer.concat([Buffer.from(tipo), datos]); const c = Buffer.alloc(4); c.writeUInt32BE(crc(td)); return Buffer.concat([l, td, c]); };
const ihdr = Buffer.alloc(13); ihdr.writeUInt32BE(4, 0); ihdr.writeUInt32BE(4, 4); ihdr[8] = 8; ihdr[9] = 2;
const filas = Buffer.alloc(4 * (1 + 4 * 3), 120);
for (let i = 0; i < 4; i++) filas[i * 13] = 0;
const png = Buffer.concat([Buffer.from([137, 80, 78, 71, 13, 10, 26, 10]), trozo('IHDR', ihdr), trozo('IDAT', zlib.deflateSync(filas)), trozo('IEND', Buffer.alloc(0))]);
const rutaPng = path.join(temporal, 'portada-prueba.png');
const rutaPdf = path.join(temporal, 'Guía del curso.pdf');
fs.writeFileSync(rutaPng, png);
fs.writeFileSync(rutaPdf, '%PDF-1.4\n1 0 obj<<>>endobj\ntrailer<<>>\n%%EOF\n');

const proc = spawn(process.env.CHROME || 'C:/Program Files/Google/Chrome/Application/chrome.exe', ['--headless=new', '--disable-gpu', '--remote-debugging-port=9342', '--window-size=1440,1000', 'about:blank'], { stdio: 'ignore' });
const sleep = (ms) => new Promise((r) => setTimeout(r, ms));
let ws, id = 0, cargo = null;
const pending = new Map();
const errores = [];
const dialogosNativos = [];
const tecla = async (key, code, keyCode) => { // Tecla real (Enter, Escape) en el elemento con foco.
    for (const type of ['keyDown', 'keyUp']) await send('Input.dispatchKeyEvent', { type, key, code, windowsVirtualKeyCode: keyCode, nativeVirtualKeyCode: keyCode });
};
const send = (method, params = {}) => new Promise((r) => { const n = ++id; pending.set(n, r); ws.send(JSON.stringify({ id: n, method, params })); });
const ev = async (expr) => {
    const r = await send('Runtime.evaluate', { expression: `(async () => { ${expr} })()`, awaitPromise: true, returnByValue: true });
    return r.result?.result?.value ?? r.result?.exceptionDetails?.exception?.description;
};
const esperar = () => new Promise((r) => { cargo = r; setTimeout(r, 10000); });
const ir = async (ruta) => { const p = esperar(); await send('Page.navigate', { url: sitio + ruta }); await p; await sleep(900); };
const subir = async (selector, ruta) => {
    const r = await send('Runtime.evaluate', { expression: `document.querySelector(${JSON.stringify(selector)})` });
    await send('DOM.setFileInputFiles', { files: [ruta], objectId: r.result.result.objectId });
};
const revisar = () => ev(`return {
    url: location.pathname + location.search,
    titulo: document.title,
    h1: [...document.querySelectorAll('h1')].map(h => h.textContent.trim()),
    php: /<b>(warning|notice|deprecated|fatal error)<\\/b>|Undefined (array key|variable)/i.test(document.documentElement.innerHTML),
    desborde: document.documentElement.scrollWidth - window.innerWidth,
};`);
const texto = (sel) => ev(`return document.querySelector(${JSON.stringify(sel)})?.innerText.replace(/\\s+/g, ' ').trim() ?? null;`);

const out = {};
try {
    for (let i = 0; i < 40 && !ws; i++) {
        try {
            const page = (await (await fetch('http://127.0.0.1:9342/json')).json()).find((t) => t.type === 'page');
            if (page) {
                ws = new WebSocket(page.webSocketDebuggerUrl);
                await new Promise((r) => ws.addEventListener('open', r));
                ws.addEventListener('message', (e) => {
                    const m = JSON.parse(e.data);
                    if (m.id && pending.has(m.id)) { pending.get(m.id)(m); pending.delete(m.id); }
                    if (m.method === 'Page.loadEventFired' && cargo) { const c = cargo; cargo = null; c(); }
                    if (m.method === 'Runtime.exceptionThrown') errores.push(m.params.exceptionDetails.exception?.description?.slice(0, 200));
                    if (m.method === 'Page.javascriptDialogOpening') { dialogosNativos.push(`${m.params.type}: ${m.params.message}`); send('Page.handleJavaScriptDialog', { accept: false }); }
                });
            }
        } catch {}
        if (!ws) await sleep(250);
    }
    await send('Page.enable');
    await send('Runtime.enable');
    await send('Network.enable');
    await send('DOM.enable');
    await send('Network.clearBrowserCookies');

    // Entrar con la instructora y probar wp-admin cerrado.
    await ir('/iniciar-sesion/?redirect_to=' + encodeURIComponent(sitio + '/administrador/cursos/nuevo/'));
    let p = esperar();
    await ev(`const f = document.querySelector('main form'); f.correo.value = 'prueba-instructora@angelitozzz.local'; f.clave.value = 'Prueba123'; f.requestSubmit(); return 1;`);
    await p; await sleep(900);
    out.nuevo = await revisar();
    out.pastillas = await ev(`return [...document.querySelectorAll('[aria-label="Pasos del curso"] li')].map(li => li.innerText.trim() + (li.querySelector('a') ? ' (enlace)' : '') + (li.querySelector('[aria-current=step]') ? ' (actual)' : ''));`);

    // Paso 1: error del servidor (lanzamiento >= precio) y luego datos válidos con portada.
    await ev(`const f = document.querySelector('[data-constructor-basico]'); f.noValidate = true;
        f.titulo.value = 'Curso del constructor'; f.descripcion.value = 'Creado por la prueba.'; f.edad.value = '0 a 12 semanas';
        f.tipo.value = 'curso_online'; f.precio.value = '97'; f.precio_lanzamiento.value = '120'; f.duracion.value = '6'; f.mes_mundo.checked = true;
        f.titulo.dispatchEvent(new Event('input', { bubbles: true }));
        f.querySelector('[data-accion="borrador"]').click(); return 1;`);
    await sleep(2000);
    out.errorServidor = await ev(`const c = document.querySelector('[name=precio_lanzamiento]'); return { resumen: document.querySelector('[data-errores]')?.innerText.trim(), invalido: c.getAttribute('aria-invalid'), describe: c.getAttribute('aria-describedby') };`);

    await subir('[data-portada-input]', rutaPng);
    await sleep(500);
    out.vistaPortada = await ev(`return !document.querySelector('[data-portada-vista]')?.hidden;`);
    p = esperar();
    await ev(`const f = document.querySelector('[data-constructor-basico]'); f.precio_lanzamiento.value = '67'; f.querySelector('[data-accion="continuar"]').click(); return 1;`);
    await p; await sleep(1500);
    out.paso2 = await revisar();
    const cursoId = await ev(`return document.querySelector('[data-constructor]').dataset.curso;`);
    out.cursoId = cursoId;

    // Paso 2: módulo nuevo con título.
    await ev(`document.querySelector('[data-agregar-modulo]')?.click(); return 1;`);
    await sleep(1500);
    out.modalModulo = await ev(`const d = document.getElementById('editar-modulo'); return { abierto: d?.open, foco: document.activeElement?.name };`);
    await ev(`const f = document.querySelector('[data-modulo-form]'); f.titulo.value = 'Bienvenida'; f.querySelector('[data-modulo-guardar]').click(); return 1;`);
    await sleep(1500);
    out.modulos = await ev(`return [...document.querySelectorAll('li[data-modulo]')].map(m => m.querySelector('[data-modulo-plegar]').innerText.replace(/\\s+/g, ' ').trim());`);

    // Lección 1 con video, texto y PDF.
    const nuevaLeccion = async (titulo, conVideo) => {
        await ev(`const m = document.querySelector('li[data-modulo]'); if (m.querySelector('[data-modulo-plegar]').getAttribute('aria-expanded') !== 'true') m.querySelector('[data-modulo-plegar]').click(); m.querySelector('[data-agregar-leccion]').click(); return 1;`);
        await sleep(800);
        await ev(`const f = document.querySelector('[data-leccion-form]'); f.titulo.value = ${JSON.stringify(titulo)}; f.titulo.dispatchEvent(new Event('input', { bubbles: true })); return 1;`);
        if (conVideo) {
            await ev(`const v = document.querySelector('[data-leccion-form] [name=video]'); v.focus(); v.value = 'https://vimeo.com/76979871'; v.dispatchEvent(new Event('input', { bubbles: true })); v.dispatchEvent(new Event('change', { bubbles: true })); v.blur(); return 1;`);
            await sleep(4000);
            out.video = await ev(`return { vista: !document.querySelector('[data-video-vista]')?.hidden, estado: document.querySelector('[data-video-estado]')?.innerText.replace(/\\s+/g, ' ').trim(), miniatura: !!document.querySelector('[data-video-miniatura]')?.getAttribute('src') };`);
            await ev(`document.querySelector('[data-editor-area] [contenteditable]')?.focus() ?? document.querySelector('[data-editor-area]').focus(); return 1;`);
            await send('Input.insertText', { text: 'Bienvenida al curso. PD: escríbeme si tienes dudas.' });
            await ev(`document.querySelector('[data-editor-accion="negrita"], [data-editor-accion="bold"]')?.click(); return 1;`);

            // Enlace: fila del editor (sin prompt). Enlace inválido → error; válido → enlace; Escape cierra solo la fila.
            // Seleccionar todo el texto con el teclado (como una persona), y abrir la fila del enlace.
            await ev(`document.querySelector('[data-editor-area] [contenteditable]').focus(); return 1;`);
            for (const type of ['keyDown', 'keyUp']) await send('Input.dispatchKeyEvent', { type, key: 'a', code: 'KeyA', windowsVirtualKeyCode: 65, modifiers: 2, commands: type === 'keyDown' ? ['selectAll'] : [] });
            await sleep(300);
            await ev(`document.querySelector('[data-editor-accion="enlace"]').click(); return 1;`);
            await sleep(300);
            out.enlaceAbierto = await ev(`const p = document.querySelector('[data-editor-enlace]'); return { visible: !p.hidden, foco: document.activeElement === p.querySelector('[data-editor-enlace-campo]'), expandido: document.querySelector('[data-editor-accion="enlace"]').getAttribute('aria-expanded') };`);
            await send('Input.insertText', { text: 'javascript:alert(1)' });
            await tecla('Enter', 'Enter', 13);
            await sleep(300);
            out.enlaceInvalido = await ev(`const p = document.querySelector('[data-editor-enlace]'); return { error: p.querySelector('[data-editor-enlace-error]').textContent.trim(), invalido: p.querySelector('[data-editor-enlace-campo]').getAttribute('aria-invalid'), modalAbierto: document.getElementById('editar-leccion').open, enlaces: document.querySelectorAll('[data-editor-area] a').length };`);
            await ev(`const c = document.querySelector('[data-editor-enlace-campo]'); c.focus(); c.select(); return 1;`);
            await send('Input.insertText', { text: 'https://angelitozzz.com/' });
            await tecla('Enter', 'Enter', 13);
            await sleep(300);
            out.enlaceValido = await ev(`return { href: document.querySelector('[data-editor-area] a')?.getAttribute('href'), filaOculta: document.querySelector('[data-editor-enlace]').hidden, modalAbierto: document.getElementById('editar-leccion').open };`);
            await ev(`document.querySelector('[data-editor-area] a').click(); document.querySelector('[data-editor-accion="enlace"]').click(); return 1;`);
            await sleep(300);
            out.enlaceQuitarVisible = await ev(`return !document.querySelector('[data-editor-enlace-quitar]').hidden;`);
            await tecla('Escape', 'Escape', 27);
            await sleep(300);
            out.enlaceEscape = await ev(`return { filaOculta: document.querySelector('[data-editor-enlace]').hidden, modalAbierto: document.getElementById('editar-leccion').open };`);
            await subir('[data-leccion-form] input[type=file]', rutaPdf);
            await sleep(500);
            out.chips = await texto('[data-materiales]');
        }
        await ev(`document.querySelector('[data-leccion-guardar]').click(); return 1;`);
        await sleep(5000);
    };
    await nuevaLeccion('Conoce a Bárbara', true);
    out.modalCerrado = await ev(`return !document.getElementById('editar-leccion').open;`);
    await nuevaLeccion('Objetivos del curso', false);

    // ¿Descartar los cambios? (sin confirm): Cancelar con cambios muestra la vista; Escape = seguir editando.
    await ev(`const m = document.querySelector('li[data-modulo]'); m.querySelector('[data-agregar-leccion]').click(); return 1;`);
    await sleep(800);
    await ev(`const f = document.querySelector('[data-leccion-form]'); f.titulo.value = 'Lección que se descarta'; f.titulo.dispatchEvent(new Event('input', { bubbles: true })); f.querySelector('[data-leccion-cancelar]').click(); return 1;`);
    await sleep(300);
    out.descartarVista = await ev(`const v = document.querySelector('[data-leccion-descartar]'); return { visible: !v.hidden, contenidoOculto: document.querySelector('[data-leccion-contenido]').hidden, foco: document.activeElement?.innerText.trim(), modalAbierto: document.getElementById('editar-leccion').open };`);
    await tecla('Escape', 'Escape', 27);
    await sleep(300);
    out.descartarEscape = await ev(`return { vistaOculta: document.querySelector('[data-leccion-descartar]').hidden, modalAbierto: document.getElementById('editar-leccion').open, titulo: document.querySelector('[data-leccion-form]').titulo.value };`);
    await ev(`document.querySelector('[data-leccion-cancelar]').click(); return 1;`);
    await sleep(300);
    await ev(`document.querySelector('[data-leccion-descartar-confirmar]').click(); return 1;`);
    await sleep(500);
    out.descartarCerrado = await ev(`return { modalAbierto: document.getElementById('editar-leccion').open, creada: !![...document.querySelectorAll('li[data-item]')].find(i => i.dataset.titulo === 'Lección que se descarta') };`);
    out.items = await ev(`return [...document.querySelectorAll('li[data-modulo] li[data-item]')].map(i => i.innerText.replace(/\\s+/g, ' ').trim().slice(0, 120));`);

    // Descargable del módulo.
    await subir('li[data-modulo] input[data-agregar-descargable]', rutaPdf);
    await ev(`document.querySelector('li[data-modulo] input[data-agregar-descargable]').dispatchEvent(new Event('change', { bubbles: true })); return 1;`);
    await sleep(3000);
    out.itemsConPdf = await ev(`return [...document.querySelectorAll('li[data-modulo] li[data-item]')].map(i => i.dataset.tipo + ': ' + i.dataset.titulo);`);

    // Ordenar con teclado: subir la segunda lección.
    out.orden = await ev(`
        const items = [...document.querySelectorAll('li[data-modulo] li[data-item]')];
        const b = items[1].querySelector('[data-mover="arriba"]'); b.focus(); b.click();
        await new Promise(r => setTimeout(r, 2500));
        return { titulos: [...document.querySelectorAll('li[data-modulo] li[data-item]')].map(i => i.dataset.titulo), anuncio: document.querySelector('[data-contenido-estado]')?.textContent.trim(), foco: document.activeElement === b };
    `);

    // Eliminar la lección "Objetivos del curso" con el modal (foco en Cancelar).
    out.eliminar = await ev(`
        const item = [...document.querySelectorAll('li[data-item]')].find(i => i.dataset.titulo === 'Objetivos del curso');
        item.querySelector('[data-item-eliminar]').click();
        await new Promise(r => setTimeout(r, 400));
        const d = document.getElementById('eliminar');
        const r = { abierto: d.open, titulo: d.querySelector('#eliminar-title').innerText, foco: document.activeElement.innerText.trim() };
        d.querySelector('[data-eliminar-confirmar]').click();
        await new Promise(r => setTimeout(r, 2500));
        r.queda = !![...document.querySelectorAll('li[data-item]')].find(i => i.dataset.titulo === 'Objetivos del curso');
        return r;
    `);

    // Recargar: el orden y el contenido quedaron guardados.
    await ir('/administrador/cursos/' + cursoId + '/editar/?paso=2');
    out.trasRecargar = await ev(`return [...document.querySelectorAll('li[data-item]')].map(i => i.innerText.replace(/\\s+/g, ' ').trim().slice(0, 100));`);
    out.paso2Revisar = await revisar();

    // Paso 3: resumen, pendientes y publicar.
    await ir('/administrador/cursos/' + cursoId + '/editar/?paso=3');
    out.paso3 = await revisar();
    out.resumen = await texto('[data-constructor-publicar]');
    await ev(`const f = document.querySelector('[data-constructor-publicar]'); f.querySelector('[name=visibilidad][value=publicado], [name=visibilidad][value=publish]').click(); return document.querySelector('[data-principal]').innerText.trim();`);
    out.botonPublicar = await texto('[data-principal]');
    p = esperar();
    await ev(`document.querySelector('[data-principal]').click(); return 1;`);
    await p; await sleep(1500);
    out.trasPublicar = { url: (await revisar()).url, aviso: await texto('[data-admin-aviso]') };

    // wp-admin cerrado para la instructora.
    await ir('/wp-admin/');
    out.wpAdmin = (await revisar()).url;

    // Móvil sin scroll horizontal en los 3 pasos.
    await send('Emulation.setDeviceMetricsOverride', { width: 390, height: 844, deviceScaleFactor: 1, mobile: true });
    out.movil = {};
    for (const paso of [1, 2, 3]) { await ir('/administrador/cursos/' + cursoId + '/editar/?paso=' + paso); out.movil[paso] = (await revisar()).desborde; }
    await ir('/administrador/cursos/nuevo/');
    out.movil.nuevo = (await revisar()).desborde;
} catch (e) {
    out.fallo = String(e?.stack || e);
} finally {
    out.erroresJs = errores;
    out.dialogosNativos = dialogosNativos;
    console.log(JSON.stringify(out, null, 1));
    proc.kill();
    process.exit(0);
}
