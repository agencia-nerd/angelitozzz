// Pantalla "Archivos privados" de wp-admin (specs/archivos-privados.md, F5-d bloque 3), contra el Local.
// - Subir un PDF de 21 MB (pasa el tope de 20: advertencia en rojo, sube igual, en 3 trozos) y un MP4 chico.
// - Barra de avance, lista recargada, "Ver" con dirección firmada, que no aparezca en Medios.
// - Renombrar y eliminar con filas en la tabla (sin ventanas nativas del navegador).
// Antes: php zz-datos-f5.php archivos (administradora de prueba). Después: php zz-datos-f5.php archivos-borrar.
// Uso: node cdp-archivos-admin.mjs   (puerto 9350)
import { mkdtempSync, writeFileSync, rmSync } from 'node:fs';
import { tmpdir } from 'node:os';
import { join } from 'node:path';
import { randomBytes } from 'node:crypto';
import { abrir, sitio, sleep } from './lib/cdp.mjs';

const carpeta = mkdtempSync(join(tmpdir(), 'angz-archivos-'));
const sufijo = Date.now().toString(36);
const pdf = join(carpeta, `Guia grande ${sufijo}.pdf`);
const mp4 = join(carpeta, `Clase ${sufijo}.mp4`);
writeFileSync(pdf, Buffer.concat([Buffer.from('%PDF-1.4\n'), Buffer.alloc(21 * 1024 * 1024, 0x20), Buffer.from('\n%%EOF\n')]));
writeFileSync(mp4, Buffer.concat([Buffer.from([0, 0, 0, 0x18]), Buffer.from('ftypmp42'), Buffer.alloc(4), Buffer.from('mp42isom'), randomBytes(200 * 1024)]));

const c = await abrir({ puerto: 9350 });
const out = {};

try {
    const entro = await c.entrar('prueba-admin-f5@angelitozzz.local');
    c.comprobar('la administradora de prueba entra (php zz-datos-f5.php archivos)', entro, entro);
    await c.ir('/wp-admin/admin.php?page=angelitozzz-archivos');
    out.pantalla = await c.ev(`return {
        h1: document.querySelector('.wrap h1')?.textContent.trim(),
        menu: !!document.querySelector('#adminmenu a[href*="page=angelitozzz-archivos"] .dashicons-lock, #adminmenu .toplevel_page_angelitozzz-archivos .dashicons-lock'),
        zonaOculta: document.querySelector('[data-angz-zona]').hidden,
    };`);
    c.comprobar('la pantalla se abre', out.pantalla.h1 === 'Archivos privados', out.pantalla);
    c.comprobar('la zona de subida empieza oculta', out.pantalla.zonaOculta, out.pantalla);
    c.comprobar('no hay avisos de PHP', !(await c.avisosPhp()).length);

    // Subir: abrir la zona y poner los dos archivos en el input.
    await c.ev(`document.querySelector('[data-angz-abrir]').click(); return 1;`);
    const doc = await c.send('DOM.getDocument', { depth: -1 });
    const nodo = await c.send('DOM.querySelector', { nodeId: doc.result.root.nodeId, selector: '[data-angz-input]' });
    await c.send('DOM.setFileInputFiles', { nodeId: nodo.result.nodeId, files: [pdf, mp4] });

    // Mientras sube: barra con avance y la advertencia del PDF.
    let vistaBarra = false;
    let fin = null;
    for (let i = 0; i < 120; i++) {
        const estado = await c.ev(`return {
            barras: [...document.querySelectorAll('[data-angz-subidas] progress')].map((p) => p.value),
            rojo: [...document.querySelectorAll('[data-angz-subidas] .notice-error')].map((n) => n.textContent),
            textos: [...document.querySelectorAll('[data-angz-subidas] li')].map((li) => li.firstChild?.textContent),
            tabla: !!document.querySelector('.angz-tabla'),
        };`).catch(() => null);
        if (estado?.barras?.some((v) => v > 0 && v < 100)) vistaBarra = true;
        if (estado?.rojo?.length) out.advertencia = estado.rojo[0];
        if (estado?.tabla && !estado.barras?.length) { fin = estado; break; }
        await sleep(500);
    }
    c.comprobar('la barra de avance se mueve', vistaBarra);
    c.comprobar('el PDF de 21 MB muestra la advertencia de peso en rojo', /Se subirá igual/.test(out.advertencia || ''), out.advertencia);
    c.comprobar('al terminar, la lista se recarga', !!fin);
    await sleep(800);

    const filas = await c.ev(`return [...document.querySelectorAll('[data-angz-fila]')].map((tr) => ({
        id: tr.dataset.angzFila, nombre: tr.dataset.angzNombre,
        tipo: tr.children[2].textContent.trim(), tamano: tr.children[3].textContent.trim(),
        ver: tr.querySelector('a[target=_blank]')?.href || '',
        eliminar: !!tr.querySelector('[data-angz-eliminar]'),
    }));`);
    const filaPdf = filas.find((f) => f.nombre === `Guia grande ${sufijo}`);
    const filaMp4 = filas.find((f) => f.nombre === `Clase ${sufijo}`);
    out.filas = { pdf: filaPdf, mp4: filaMp4 };
    c.comprobar('el PDF está en la lista, con su tipo', filaPdf?.tipo === 'PDF', filaPdf);
    c.comprobar('el MP4 está en la lista, como video', filaMp4?.tipo === 'Video', filaMp4);

    // "Ver": dirección firmada; el PDF se entrega; la URL sin firma no.
    out.ver = await c.ev(`
        const r = await fetch(${JSON.stringify(filaPdf?.ver || '')}, { headers: { Range: 'bytes=0-4' } });
        const sin = await fetch(${JSON.stringify((filaPdf?.ver || '').split('?')[0])});
        return { estado: r.status, inicio: await r.text(), sinFirma: sin.status };`);
    c.comprobar('"Ver" entrega el archivo con la firma', [200, 206].includes(out.ver.estado) && out.ver.inicio.startsWith('%PDF'), out.ver);
    c.comprobar('sin firma, 403', out.ver.sinFirma === 403, out.ver);

    // No aparece en Medios.
    out.medios = await c.ev(`const r = await fetch('/wp-admin/upload.php?mode=list&s=' + encodeURIComponent(${JSON.stringify(sufijo)})); const t = await r.text(); return t.includes(${JSON.stringify(sufijo)} + '</a>') || t.includes('Clase ${sufijo}</a>');`);
    c.comprobar('no aparece en la Biblioteca de medios', out.medios === false, out.medios);

    // Renombrar con Enter.
    await c.ev(`document.querySelector('[data-angz-fila="${filaMp4.id}"] [data-angz-renombrar]').click(); return 1;`);
    out.renombrar = await c.ev(`const i = document.activeElement; return { tag: i.tagName, valor: i.value };`);
    c.comprobar('"Renombrar" pone el foco en el campo con el nombre', out.renombrar.tag === 'INPUT' && out.renombrar.valor === `Clase ${sufijo}`, out.renombrar);
    await c.conNavegacion(`const i = document.activeElement; i.value = 'Clase renombrada ${sufijo}'; i.dispatchEvent(new KeyboardEvent('keydown', { key: 'Enter', bubbles: true })); return 1;`, 1000);
    out.renombrado = await c.ev(`return document.querySelector('[data-angz-fila="${filaMp4.id}"]')?.dataset.angzNombre;`);
    c.comprobar('el nombre nuevo se guarda', out.renombrado === `Clase renombrada ${sufijo}`, out.renombrado);

    // Eliminar: fila de confirmación, foco en Cancelar, Escape la cierra; "Sí, eliminar" borra.
    await c.ev(`document.querySelector('[data-angz-fila="${filaPdf.id}"] [data-angz-eliminar]').click(); return 1;`);
    out.confirmar = await c.ev(`return { fila: !!document.querySelector('.angz-confirmar'), foco: document.activeElement.textContent.trim(), texto: document.querySelector('.angz-confirmar p')?.textContent };`);
    c.comprobar('"Eliminar" abre la confirmación en la tabla, con el foco en Cancelar', out.confirmar.fila && out.confirmar.foco === 'Cancelar', out.confirmar);
    // "Sí, eliminar". Una marca en la página dice si hubo recarga. La lista se recarga al llegar la
    // respuesta o, si no llega (pasa a veces en el Local de Windows al borrar un archivo grande), a los 20 s
    // con el aviso "No recibimos respuesta…": se espera hasta 40 s.
    await c.ev(`window.__angzMarca = 1; [...document.querySelectorAll('.angz-confirmar button')].find((b) => b.textContent.includes('eliminar')).click(); return 1;`);
    for (let i = 0; i < 80 && !out.eliminado; i++) {
        await sleep(500);
        out.eliminado = await c.ev(`return !window.__angzMarca && document.readyState === 'complete' && !!document.querySelector('.angz-tabla, .angz-vacio') && !document.querySelector('[data-angz-fila="${filaPdf.id}"]');`);
    }
    c.comprobar('el PDF se eliminó y la lista se recargó', out.eliminado, out.eliminado ? '' : await c.ev(`
        const e = document.querySelector('[data-angz-error]');
        const s = [...document.scripts].map((x) => x.src).filter((x) => x.includes('angelitozzz-cursos'));
        return { marca: !!window.__angzMarca, estado: document.readyState, error: e && !e.hidden ? e.textContent.trim() : '', scripts: s,
            nuevo: await fetch(s.find((x) => x.includes('subida'))).then((r) => r.text()).then((t) => t.includes('sin-respuesta')) };`));

    // Sin ventanas nativas: el script de la pantalla no usa prompt ni confirm.
    out.nativas = await c.ev(`const t = await (await fetch(document.querySelector('script[src*="archivos-admin.js"]').src)).text(); return /\\b(prompt|confirm|alert)\\(/.test(t);`);
    c.comprobar('sin ventanas nativas del navegador', out.nativas === false);
} finally {
    rmSync(carpeta, { recursive: true, force: true });
}

await c.terminar(out);
