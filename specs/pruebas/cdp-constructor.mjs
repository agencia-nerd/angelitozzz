// Recorrido del constructor (bloque 6b) por CDP con la instructora de prueba (datos-f5.php crear):
// crea un curso de cero (paso 1 con error del servidor y portada), agrega módulo, lecciones con video y
// descargables de "Archivos privados" (F5-d, 4c: subir por partes y elegir de mis archivos), texto, ordena
// con teclado, elimina, y lo publica (paso 3). Contraseña siempre correcta.
// 4c: sube el MP4 real de %TEMP%/angelitozzz-video-prueba.mp4 (si falta: node cdp-leccion-video.mjs video),
// un PDF de 21 MB (advertencia), un SVG (error), cancela una subida y prueba "+ Agregar descargable" con su
// modal. Lo subido queda en "Archivos privados" de la instructora: se borra aparte, junto con el curso.
// Termina con código 1 si alguna comprobación falla (out.fallos).
// F5-c: el enlace del editor y "¿Descartar los cambios?" son paneles del mismo modal. Si aparece una ventana
// nativa del navegador (alert, confirm o prompt), se cierra sin aceptar y queda en out.dialogosNativos (falla).
// Uso: node cdp-constructor.mjs <carpeta temporal para el PNG y el PDF>
import { spawn } from 'node:child_process';
import fs from 'node:fs';
import os from 'node:os';
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
// 4c: PDF de 21 MB (pasa el tope de 20 MB: advertencia y se sube igual), un SVG (no permitido) y el MP4 real.
const rutaPdfGrande = path.join(temporal, 'Guía grande.pdf');
const rutaSvg = path.join(temporal, 'dibujo.svg');
fs.writeFileSync(rutaPdfGrande, Buffer.concat([Buffer.from('%PDF-1.4\n'), Buffer.alloc(21 * 1024 * 1024, 32), Buffer.from('\n%%EOF\n')]));
fs.writeFileSync(rutaSvg, '<svg xmlns="http://www.w3.org/2000/svg"/>');
const rutaMp4 = path.join(os.tmpdir(), 'angelitozzz-video-prueba.mp4');
if (!fs.existsSync(rutaMp4)) { console.error(`Falta ${rutaMp4}: corre antes "node cdp-leccion-video.mjs video".`); process.exit(1); }

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
    await send('DOM.setFileInputFiles', { files: [].concat(ruta), objectId: r.result.result.objectId });
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
const fallos = [];
const comprobar = (nombre, ok, detalle) => { if (!ok) fallos.push(detalle === undefined ? nombre : `${nombre}: ${JSON.stringify(detalle)}`); };
// Espera hasta que `expr` (cuerpo de función) devuelva algo verdadero, o null.
const hasta = async (expr, intentos = 150, pausa = 400) => { for (let i = 0; i < intentos; i++) { const v = await ev(expr); if (v) return v; await sleep(pausa); } return null; };
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
            // Video: un PDF en "Subir video" da error; el MP4 sube por partes con su barra y "Guardar" desactivado.
            await subir('[data-video-subir]', rutaPdf);
            await sleep(300);
            out.videoNoVideo = await texto('[data-leccion-form] [data-error-de=video]');
            comprobar('un archivo que no es video da error', /MP4 o WebM/.test(out.videoNoVideo || ''), out.videoNoVideo);

            await subir('[data-video-subir]', rutaMp4);
            const estadoVideo = `const f = document.querySelector('[data-leccion-form]'); return { subiendo: !f.querySelector('[data-video-subiendo]').hidden, texto: f.querySelector('[data-video-progreso-texto]').textContent, valor: f.querySelector('[data-video-progreso]').value, guardar: f.querySelector('[data-leccion-guardar]').disabled, espera: !f.querySelector('[data-leccion-espera]').hidden, foco: document.activeElement?.textContent.trim() };`;
            out.videoSubiendo = await hasta(`const e = (() => { ${estadoVideo} })(); return e.subiendo ? e : null;`, 20, 100);
            comprobar('mientras sube hay barra con el nombre y el porcentaje', /Subiendo angelitozzz-video-prueba\.mp4… \d+ %/.test(out.videoSubiendo?.texto || ''), out.videoSubiendo);
            comprobar('"Guardar lección" queda desactivado mientras sube, con su ayuda', out.videoSubiendo?.guardar && out.videoSubiendo?.espera, out.videoSubiendo);
            comprobar('el foco pasa a "Cancelar" de la subida', out.videoSubiendo?.foco === 'Cancelar', out.videoSubiendo);
            await hasta(`return document.querySelector('[data-video-subiendo]').hidden;`, 300);
            out.video = await ev(`const f = document.querySelector('[data-leccion-form]'); return { listo: !f.querySelector('[data-video-listo]').hidden, detalle: f.querySelector('[data-video-detalle]').textContent, miniatura: !!f.querySelector('[data-video-miniatura]').getAttribute('src'), id: f.video.value, guardar: f.querySelector('[data-leccion-guardar]').disabled, foco: document.activeElement?.textContent.trim(), recomendacion: f.querySelector('[data-video-recomendacion]').textContent };`);
            comprobar('al terminar: "Video listo", portada, nombre e ID', out.video.listo && out.video.miniatura && /angelitozzz-video-prueba/.test(out.video.detalle) && Number(out.video.id) > 0, out.video);
            comprobar('al terminar se puede guardar y el foco va a "Cambiar video"', !out.video.guardar && out.video.foco === 'Cambiar video', out.video);

            // Elegir de mis archivos: vista del mismo modal; volver no pierde lo escrito; Escape también vuelve.
            await ev(`document.querySelector('[data-video-cambiar]').click(); document.querySelector('[data-video-elegir]').click(); return 1;`);
            out.elegirVideo = await hasta(`const v = document.querySelector('[data-leccion-archivos]'); const filas = [...v.querySelectorAll('[data-lista-elegir]')]; return filas.length ? { vista: !v.hidden, contenidoOculto: document.querySelector('[data-leccion-contenido]').hidden, modales: document.querySelectorAll('dialog[open]').length, filas: filas.map((b) => b.getAttribute('aria-label')), clase: v.querySelector('[data-lista-archivos]').dataset.clase } : null;`, 40);
            comprobar('"Elegir de mis archivos" es una vista del mismo modal, solo con videos', out.elegirVideo?.vista && out.elegirVideo?.contenidoOculto && out.elegirVideo?.modales === 1 && out.elegirVideo?.clase === 'video', out.elegirVideo);
            await tecla('Escape', 'Escape', 27);
            await sleep(300);
            out.elegirEscape = await ev(`return { modal: document.getElementById('editar-leccion').open, vistaOculta: document.querySelector('[data-leccion-archivos]').hidden, titulo: document.querySelector('[data-leccion-form]').titulo.value, foco: document.activeElement?.textContent.trim() };`);
            comprobar('Escape vuelve a la lección sin perder lo escrito', out.elegirEscape.modal && out.elegirEscape.vistaOculta && out.elegirEscape.titulo === titulo, out.elegirEscape);
            await ev(`document.querySelector('[data-video-elegir]').click(); return 1;`);
            await hasta(`return document.querySelectorAll('[data-leccion-archivos] [data-lista-elegir]').length;`, 40);
            await ev(`const b = [...document.querySelectorAll('[data-leccion-archivos] [data-lista-elegir]')].find((x) => /angelitozzz-video-prueba/.test(x.getAttribute('aria-label'))); b.click(); return 1;`);
            await sleep(300);
            out.videoElegido = await ev(`const f = document.querySelector('[data-leccion-form]'); return { id: f.video.value, foco: document.activeElement?.textContent.trim(), vista: !f.querySelector('[data-leccion-contenido]').hidden };`);
            comprobar('elegir un video lo deja en la lección con el foco en "Cambiar video"', out.videoElegido.vista && Number(out.videoElegido.id) > 0 && out.videoElegido.foco === 'Cambiar video', out.videoElegido);
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
            // Descargables: el SVG da error; el PDF chico y el de 21 MB (advertencia) suben y quedan como chips.
            await subir('[data-leccion-material]', [rutaSvg, rutaMp4, rutaPdf, rutaPdfGrande]);
            await sleep(300);
            out.materialSvg = await texto('[data-leccion-form] [data-error-de=materiales]');
            comprobar('un SVG y un video en descargables dan error', /dibujo\.svg: .*no está permitido/.test(out.materialSvg || '') && /angelitozzz-video-prueba\.mp4: .*no está permitido/.test(out.materialSvg || ''), out.materialSvg);
            out.materialEnEspera = await ev(`return [...document.querySelectorAll('[data-materiales] li')].map((li) => li.textContent.trim());`);
            comprobar('los descargables suben de a uno (el segundo, en espera)', out.materialEnEspera.some((t) => /^En espera: Guía grande\.pdf/.test(t)), out.materialEnEspera);
            await hasta(`return !document.querySelector('[data-materiales] li.opacity-80');`, 300);
            out.chips = await ev(`const f = document.querySelector('[data-leccion-form]'); return { chips: [...f.querySelectorAll('[data-materiales] li')].map((li) => li.textContent.trim() + ' · ' + (li.querySelector('img')?.getAttribute('src') || '').split('/').pop()), aviso: f.querySelector('[data-materiales-aviso]').textContent };`);
            comprobar('los dos PDF quedan como chips con su ícono', out.chips.chips.length === 2 && out.chips.chips.every((c) => /pdf\.svg$/.test(c)), out.chips);
            comprobar('el PDF de 21 MB muestra la advertencia de peso y se sube igual', /Guía grande\.pdf: .*Se subirá igual/.test(out.chips.aviso), out.chips.aviso);

            // Cancelar una subida: el chip desaparece y no queda nada pendiente.
            await subir('[data-leccion-material]', rutaPdfGrande);
            await hasta(`return document.querySelector('[data-materiales] li.opacity-80');`, 20, 100);
            await ev(`document.querySelector('[data-materiales] li.opacity-80 button').click(); return 1;`);
            out.cancelada = await hasta(`const f = document.querySelector('[data-leccion-form]'); return !f.querySelector('[data-materiales] li.opacity-80') ? { chips: f.querySelectorAll('[data-materiales] li').length, guardar: f.querySelector('[data-leccion-guardar]').disabled } : null;`, 30, 200);
            comprobar('cancelar una subida la quita y "Guardar" vuelve a estar activo', out.cancelada?.chips === 2 && out.cancelada?.guardar === false, out.cancelada);
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

    out.leccionGuardada = await ev(`const li = [...document.querySelectorAll('li[data-item]')].find((i) => i.dataset.titulo === 'Conoce a Bárbara'); const d = JSON.parse(li.dataset.leccion); return { linea: li.querySelector('[data-item-linea]').textContent, video: d.video, materiales: d.materiales.map((m) => m.nombre) };`);
    comprobar('la lección guardada tiene su video y sus 2 descargables', /^Video/.test(out.leccionGuardada.linea) && out.leccionGuardada.video > 0 && out.leccionGuardada.materiales.length === 2, out.leccionGuardada);

    // Descargable del módulo: "+ Agregar descargable" abre dos opciones.
    out.menuDescargable = await ev(`const b = document.querySelector('li[data-modulo] [data-agregar-descargable]'); b.click(); await new Promise((r) => setTimeout(r, 200)); return { expandido: b.getAttribute('aria-expanded'), visible: !b.closest('[data-descargable-menu]').querySelector('[data-descargable-opciones]').hidden };`);
    comprobar('"+ Agregar descargable" abre sus dos opciones', out.menuDescargable.expandido === 'true' && out.menuDescargable.visible, out.menuDescargable);
    await tecla('Escape', 'Escape', 27);
    out.menuEscape = await ev(`return { expandido: document.querySelector('li[data-modulo] [data-agregar-descargable]').getAttribute('aria-expanded'), foco: document.activeElement?.matches('[data-agregar-descargable]') };`);
    comprobar('Escape cierra el menú y devuelve el foco', out.menuEscape.expandido === 'false' && out.menuEscape.foco, out.menuEscape);

    // Subir archivo (PDF chico) desde el módulo.
    await ev(`document.querySelector('li[data-modulo] [data-agregar-descargable]').click(); return 1;`);
    await subir('li[data-modulo] [data-descargable-subir]', rutaPdf);
    await hasta(`return [...document.querySelectorAll('li[data-modulo] li[data-item][data-tipo=material]')].length >= 1;`, 60);
    // Elegir de mis archivos (modal de 720 px, solo materiales, con buscador): el PDF grande.
    await ev(`document.querySelector('li[data-modulo] [data-agregar-descargable]').click(); document.querySelector('li[data-modulo] [data-descargable-elegir]').click(); return 1;`);
    out.modalElegir = await hasta(`const d = document.getElementById('elegir-archivo'); const filas = d.querySelectorAll('[data-lista-elegir]'); return filas.length ? { abierto: d.open, ancho: Math.round(d.querySelector('.modal__panel')?.getBoundingClientRect().width || 0), clase: d.querySelector('[data-lista-archivos]').dataset.clase, filas: [...filas].map((b) => b.getAttribute('aria-label')) } : null;`, 40);
    comprobar('"Elegir de mis archivos" del módulo abre su modal de 720 px, solo con materiales y sin portadas', out.modalElegir?.abierto && out.modalElegir?.ancho === 720 && out.modalElegir?.clase === 'material' && !out.modalElegir.filas.some((f) => /portada|\.mp4|video-prueba/i.test(f)), out.modalElegir);
    await ev(`const i = document.querySelector('#elegir-archivo [data-lista-buscar]'); i.value = 'grande'; i.dispatchEvent(new Event('input', { bubbles: true })); return 1;`);
    await sleep(1200);
    out.buscador = await ev(`return [...document.querySelectorAll('#elegir-archivo [data-lista-elegir]')].map((b) => b.getAttribute('aria-label'));`);
    comprobar('el buscador filtra por nombre', out.buscador.length >= 1 && out.buscador.every((f) => /grande/i.test(f)), out.buscador);
    await ev(`document.querySelector('#elegir-archivo [data-lista-elegir]').click(); return 1;`);
    await hasta(`return !document.getElementById('elegir-archivo').open;`, 30);
    out.itemsConPdf = await ev(`return [...document.querySelectorAll('li[data-modulo] li[data-item]')].map(i => i.dataset.tipo + ': ' + i.dataset.titulo + ' · ' + i.querySelector('[data-item-linea]').textContent);`);
    comprobar('los dos descargables del módulo quedan con su tipo y tamaño', out.itemsConPdf.filter((i) => /^material: .* · PDF · /.test(i)).length === 2, out.itemsConPdf);

    // Quitar un descargable del curso no lo borra de "Archivos privados".
    out.quitarDescargable = await ev(`
        const item = [...document.querySelectorAll('li[data-item][data-tipo=material]')].find((i) => i.dataset.titulo === 'Guía grande');
        const id = item.dataset.id;
        item.querySelector('[data-item-eliminar]').click();
        await new Promise((r) => setTimeout(r, 400));
        const d = document.getElementById('eliminar');
        const r = { titulo: d.querySelector('#eliminar-title').innerText.trim(), texto: d.querySelector('[data-eliminar-texto], #eliminar-description')?.innerText.trim() };
        d.querySelector('[data-eliminar-confirmar]').click();
        await new Promise((r) => setTimeout(r, 2000));
        const base = document.querySelector('[data-constructor]');
        const url = new URL(base.dataset.api, location.href);
        const ruta = '/archivos';
        if (url.searchParams.has('rest_route')) url.searchParams.set('rest_route', url.searchParams.get('rest_route').replace(/\\/$/, '') + ruta); else url.pathname = url.pathname.replace(/\\/$/, '') + ruta;
        url.searchParams.set('buscar', 'Guía grande');
        const lista = await (await fetch(url, { headers: { 'X-WP-Nonce': base.dataset.nonce }, credentials: 'same-origin' })).json();
        r.sigue = lista.archivos.some((a) => String(a.id) === id);
        r.queda = !!document.querySelector('li[data-item][data-tipo=material][data-id="' + id + '"]');
        return r;
    `);
    comprobar('quitar un descargable dice "¿Quitar este descargable?" y no lo borra de Archivos privados', out.quitarDescargable.titulo === '¿Quitar este descargable?' && out.quitarDescargable.sigue && !out.quitarDescargable.queda, out.quitarDescargable);

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
    // El modal de la lección en el móvil: sin desborde, con el recuadro del video a todo el ancho.
    await ir('/administrador/cursos/' + cursoId + '/editar/?paso=2');
    await ev(`document.querySelector('li[data-modulo] [data-item-editar]').click(); return 1;`);
    await sleep(800);
    out.movil.modalLeccion = await ev(`const p = document.querySelector('#editar-leccion .modal__panel'); const c = document.querySelector('[data-leccion-contenido]'); return { ancho: Math.round(p.getBoundingClientRect().width), desborde: c.scrollWidth - c.clientWidth };`);
    comprobar('el modal de la lección entra en 390 px sin desborde', out.movil.modalLeccion.ancho <= 390 && out.movil.modalLeccion.desborde <= 0, out.movil.modalLeccion);
    const foto = await send('Page.captureScreenshot', { format: 'png' });
    fs.writeFileSync(path.join(temporal, 'constructor-leccion-390.png'), Buffer.from(foto.result.data, 'base64'));
    await ir('/administrador/cursos/nuevo/');
    out.movil.nuevo = (await revisar()).desborde;
    for (const [paso, desborde] of Object.entries(out.movil)) if (typeof desborde === 'number') comprobar('sin scroll horizontal en el móvil (' + paso + ')', desborde <= 0, desborde);
} catch (e) {
    out.fallo = String(e?.stack || e);
} finally {
    out.erroresJs = errores;
    out.dialogosNativos = dialogosNativos;
    if (out.fallo) fallos.push(out.fallo);
    if (errores.length) fallos.push('errores de JS: ' + JSON.stringify(errores));
    if (dialogosNativos.length) fallos.push('ventanas nativas: ' + JSON.stringify(dialogosNativos));
    out.fallos = fallos;
    out.ok = fallos.length === 0;
    console.log(JSON.stringify(out, null, 1));
    proc.kill();
    process.exit(fallos.length ? 1 : 0);
}
