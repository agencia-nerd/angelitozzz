// Formularios de wp-form (contacto, newsletter del pie y coach del modal), contra el Local.
// Por cada uno: enviado vacío marca los errores y no envía; bien lleno muestra el éxito (el coach pasa a
// "gracias") y el correo llega a Mailpit. Usa prueba-formularios@angelitozzz.local: después,
// `php zz-datos-f5.php limpiar-formularios` borra los envíos guardados (los correos quedan en Mailpit).
// No necesita datos de prueba. Límite de wp-form: 10 envíos por hora por IP y formulario.
// Uso: node cdp-formularios.mjs   (puerto 9345)
import { abrir, puertoLocal, sleep } from './lib/cdp.mjs';

const CORREO = 'prueba-formularios@angelitozzz.local';
const mailpit = puertoLocal('mailpit', 'WEB');
const c = await abrir({ puerto: 9345 });
const out = { mailpit: mailpit ? `http://localhost:${mailpit}` : 'sin puerto en sites.json' };

const mensajesMailpit = async () => {
    if (!mailpit) return null;
    try {
        return (await (await fetch(`http://localhost:${mailpit}/api/v1/messages?limit=50`)).json()).messages || [];
    } catch {
        return null;
    }
};

// Llena cada campo visible según su tipo (sin tocar el honeypot ni los ocultos).
const llenar = (selector) => c.ev(`
    const f = document.querySelector(${JSON.stringify(selector)});
    for (const el of f.elements) {
        if (!el.name || el.type === 'hidden' || el.name === 'angelitozzz_hp' || el.closest('[aria-hidden="true"]')) continue;
        if (el.type === 'checkbox') el.checked = true;
        else if (el.tagName === 'SELECT') { if (!el.value) el.value = [...el.options].find((o) => o.value)?.value || ''; }
        else if (el.type === 'email') el.value = ${JSON.stringify(CORREO)};
        else if (el.type === 'tel' || /celular|whatsapp|telefono/.test(el.name)) el.value = '987654321';
        else if (el.tagName === 'TEXTAREA') el.value = 'Mensaje de prueba automática (cdp-formularios).';
        else el.value = 'Prueba Formularios';
        el.dispatchEvent(new Event('input', { bubbles: true }));
        el.dispatchEvent(new Event('change', { bubbles: true }));
    }
    return [...f.elements].filter((el) => el.name && el.type !== 'hidden').map((el) => el.name);`);

// Envía vacío y luego lleno; devuelve lo que se vio.
const probar = async (nombre, selector, exito) => {
    const r = {};
    const listo = await c.ev(`
        const f = document.querySelector(${JSON.stringify(selector)});
        if (!f) return 'no está el formulario';
        // form.js pide el nonce y el token (en memoria, no en el HTML) cuando el form aparece en pantalla.
        f.scrollIntoView({ block: 'center' });
        await new Promise((r) => setTimeout(r, 800));
        return 'ok';`);
    r.listo = listo;
    if (!c.comprobar(`${nombre}: el formulario está listo`, listo === 'ok', listo)) return r;

    r.vacio = await c.ev(`
        const f = document.querySelector(${JSON.stringify(selector)});
        f.requestSubmit();
        await new Promise((r) => setTimeout(r, 600));
        const invalidos = [...f.querySelectorAll('[aria-invalid="true"]')];
        return { invalidos: invalidos.map((el) => el.name), foco: document.activeElement?.name || document.activeElement?.tagName, estado: f.querySelector('[data-form-status]')?.innerText.trim() || '' };`);
    c.comprobar(`${nombre}: vacío marca los campos obligatorios`, r.vacio.invalidos.length > 0, r.vacio);
    c.comprobar(`${nombre}: vacío pone el foco en el primer error`, r.vacio.invalidos[0] === r.vacio.foco, r.vacio);

    r.campos = await llenar(selector);
    await sleep(3500); // Tiempo mínimo del anti-spam (ANGELITOZZZ_FORM_MIN_SECONDS = 3).
    const antes = (await mensajesMailpit())?.length ?? null;
    r.enviado = await c.ev(`
        const f = document.querySelector(${JSON.stringify(selector)});
        f.requestSubmit();
        for (let i = 0; i < 50; i++) {
            const t = f.querySelector('[data-form-status]')?.innerText.trim() || '';
            if (t && !/enviando/i.test(t)) break;
            await new Promise((r) => setTimeout(r, 200));
        }
        return { estado: f.querySelector('[data-form-status]')?.innerText.trim() || '', invalidos: [...f.querySelectorAll('[aria-invalid="true"]')].map((el) => el.name) };`);
    c.comprobar(`${nombre}: lleno muestra el éxito`, r.enviado.estado.includes(exito), r.enviado);
    await sleep(1500);
    const despues = await mensajesMailpit();
    if (despues && antes !== null) {
        r.correo = despues.length > antes ? despues[0].Subject : 'no llegó';
        c.comprobar(`${nombre}: el correo llega a Mailpit`, despues.length > antes, r.correo);
    } else {
        r.correo = 'no se pudo leer Mailpit';
    }
    r.php = await c.avisosPhp();
    c.comprobar(`${nombre}: sin avisos de PHP`, r.php.length === 0, r.php);
    return r;
};

// Contacto.
await c.ir('/contacto/');
out.contacto = await probar('contacto', 'main form[data-form="contacto"]', '¡Gracias! Te responderemos pronto.');

// Newsletter (pie de página).
await c.ir('/');
out.newsletter = await probar('newsletter', 'footer form[data-form="newsletter"]', '¡Gracias! Ya estás suscrita');

// Coach (modal que abre el cintillo); al terminar, el modal pasa a "gracias".
await c.ir('/');
await c.ev(`document.querySelector('[data-modal-open="modal-coach"]').click(); await new Promise((r) => setTimeout(r, 600)); return 1;`);
out.coach = await probar('coach', '#modal-coach form[data-form="coach"]', 'Listo');
out.coach.gracias = await c.ev(`const d = document.getElementById('modal-coach'); return { estado: d.dataset.estado || '', titulo: d.querySelector('[data-coach-titulo]')?.textContent || '' };`);
c.comprobar('coach: pasa a la pantalla de gracias con el nombre', out.coach.gracias.estado === 'gracias' && out.coach.gracias.titulo.includes('Prueba'), out.coach.gracias);

await c.terminar(out);
