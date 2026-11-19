/*
 * Constructor · paso 1 "Básico" (specs/admin-constructor.md → paso-basico): guarda la información del
 * curso por la API del plugin (POST cursos o cursos/<id>) y la portada aparte (POST|DELETE
 * cursos/<id>/portada). En un curso nuevo la portada elegida se sube justo después de crearlo.
 *
 * Errores del servidor (400, data.campos): junto a cada campo (aria-invalid + aria-describedby) y en
 * un resumen role="alert"; lo escrito no se pierde. Mientras haya cambios sin guardar, el navegador
 * pregunta antes de salir.
 */

import { crearApi, avisarCambiosSinGuardar } from './admin-comun.js';

const TIPOS_PORTADA = ['image/jpeg', 'image/png', 'image/webp'];

/** Agrega parámetros a una URL. */
const conParametros = (url, parametros) => {
    const destino = new URL(url, location.href);
    Object.entries(parametros).forEach(([clave, valor]) => destino.searchParams.set(clave, valor));
    return destino.toString();
};

export function initConstructorBasico() {
    const form = document.querySelector('[data-constructor-basico]');
    const raiz = form?.closest('[data-constructor]');
    if (!form || !raiz) return;

    const pedir = crearApi(raiz.dataset.api, raiz.dataset.nonce);
    let cursoId = Number(raiz.dataset.curso) || 0;

    const resumen = form.querySelector('[data-errores]');
    const estado = form.querySelector('[data-estado]');
    const botones = [...form.querySelectorAll('button')];

    // ---------- Mes gratis en Mundo: aviso si el curso es de suscripción (no se bloquea) ----------

    const mundo = form.elements.mes_mundo;
    const avisoMundo = form.querySelector('[data-mundo-aviso-texto]');
    const tipoCurso = form.elements.tipo;
    const sincronizarMundo = () => {
        if (!mundo || !tipoCurso || !avisoMundo) return;
        avisoMundo.classList.toggle('hidden', !(tipoCurso.value === 'suscripcion' && mundo.checked));
    };
    tipoCurso?.addEventListener('change', sincronizarMundo);
    mundo?.addEventListener('change', sincronizarMundo);
    sincronizarMundo();

    // ---------- Cambios sin guardar ----------

    let sucio = false;
    let portadaPendiente = null; // Archivo elegido en un curso nuevo (se sube al crear el curso).
    const quitarAviso = avisarCambiosSinGuardar(() => sucio || portadaPendiente !== null);

    form.addEventListener('input', (event) => {
        if (!event.target.matches('[data-portada-input]')) sucio = true;
    });
    form.addEventListener('change', (event) => {
        if (!event.target.matches('[data-portada-input]')) sucio = true;
    });

    // ---------- Mensajes ----------

    const anunciar = (texto) => {
        estado.textContent = '';
        requestAnimationFrame(() => {
            estado.textContent = texto;
        });
    };

    const limpiarErrores = () => {
        resumen.replaceChildren();
        form.querySelectorAll('[aria-invalid="true"]').forEach((control) => control.removeAttribute('aria-invalid'));
        form.querySelectorAll('[data-error-de]').forEach((zona) => {
            zona.textContent = '';
        });
    };

    const errorDeCampo = (nombre, texto) => {
        const zona = form.querySelector(`[data-error-de="${nombre}"]`);
        if (zona) zona.textContent = texto;
        const control = form.elements.namedItem(nombre);
        if (!(control instanceof HTMLElement)) return;
        if (texto) {
            control.setAttribute('aria-invalid', 'true');
        } else {
            control.removeAttribute('aria-invalid');
        }
    };

    const mostrarErrores = (error) => {
        const parrafo = document.createElement('p');
        parrafo.textContent = error?.message || raiz.dataset.errorGenerico;
        const nodos = [parrafo];

        const campos = error?.data?.campos;
        if (campos && typeof campos === 'object') {
            const lista = document.createElement('ul');
            lista.className = 'mt-2 list-disc pl-5';
            Object.entries(campos).forEach(([nombre, texto]) => {
                errorDeCampo(nombre, texto);
                const item = document.createElement('li');
                item.textContent = texto;
                lista.append(item);
            });
            nodos.push(lista);
        }

        resumen.replaceChildren(...nodos);
        resumen.focus({ preventScroll: false });
    };

    const ocupado = (si) => {
        botones.forEach((boton) => {
            boton.disabled = si;
        });
        form.setAttribute('aria-busy', si ? 'true' : 'false');
    };

    // ---------- Portada ----------

    const zonaPortada = form.querySelector('[data-portada-zona]');
    const vistaPortada = form.querySelector('[data-portada-vista]');
    const imagenPortada = form.querySelector('[data-portada-imagen]');
    const inputPortada = form.querySelector('[data-portada-input]');
    const soltar = form.querySelector('[data-portada-soltar]');
    let urlLocal = '';

    const mostrarVista = (url) => {
        imagenPortada.src = url;
        zonaPortada.classList.add('hidden');
        vistaPortada.classList.remove('hidden');
    };

    const mostrarZona = () => {
        if (urlLocal) URL.revokeObjectURL(urlLocal);
        urlLocal = '';
        imagenPortada.removeAttribute('src');
        inputPortada.value = '';
        vistaPortada.classList.add('hidden');
        zonaPortada.classList.remove('hidden');
    };

    const subirPortada = async (id, archivo) => {
        const datos = new FormData();
        datos.append('portada', archivo);
        return pedir('POST', `/cursos/${id}/portada`, datos);
    };

    const elegirPortada = async (archivo) => {
        if (!archivo) return;
        errorDeCampo('portada', '');
        if (!TIPOS_PORTADA.includes(archivo.type)) {
            errorDeCampo('portada', form.dataset.errorPortada);
            inputPortada.value = '';
            return;
        }

        if (!cursoId) {
            // Curso nuevo: se sube al guardar, después de crear el curso.
            portadaPendiente = archivo;
            if (urlLocal) URL.revokeObjectURL(urlLocal);
            urlLocal = URL.createObjectURL(archivo);
            mostrarVista(urlLocal);
            form.querySelector('[data-portada-cambiar]').focus();
            return;
        }

        ocupado(true);
        try {
            const respuesta = await subirPortada(cursoId, archivo);
            mostrarVista(respuesta.url);
            anunciar(form.dataset.guardado);
            form.querySelector('[data-portada-cambiar]').focus();
        } catch (error) {
            errorDeCampo('portada', error?.message || form.dataset.errorPortada);
            inputPortada.value = '';
        } finally {
            ocupado(false);
        }
    };

    inputPortada.addEventListener('change', () => elegirPortada(inputPortada.files?.[0]));

    form.querySelector('[data-portada-cambiar]').addEventListener('click', () => inputPortada.click());

    form.querySelector('[data-portada-quitar]').addEventListener('click', async () => {
        if (portadaPendiente || !cursoId) {
            portadaPendiente = null;
            mostrarZona();
            inputPortada.focus();
            return;
        }
        ocupado(true);
        try {
            await pedir('DELETE', `/cursos/${cursoId}/portada`);
            mostrarZona();
            anunciar(form.dataset.guardado);
            inputPortada.focus();
        } catch (error) {
            errorDeCampo('portada', error?.message || raiz.dataset.errorGenerico);
        } finally {
            ocupado(false);
        }
    });

    // Arrastrar y soltar sobre la zona (la etiqueta del input).
    ['dragenter', 'dragover'].forEach((tipo) => {
        soltar.addEventListener(tipo, (event) => {
            event.preventDefault();
            soltar.dataset.arrastrando = '';
        });
    });
    ['dragleave', 'dragend'].forEach((tipo) => {
        soltar.addEventListener(tipo, () => delete soltar.dataset.arrastrando);
    });
    soltar.addEventListener('drop', (event) => {
        event.preventDefault();
        delete soltar.dataset.arrastrando;
        elegirPortada(event.dataTransfer?.files?.[0]);
    });

    // ---------- Guardar ----------

    const datosDelFormulario = () => ({
        titulo: form.elements.titulo.value,
        descripcion: form.elements.descripcion.value,
        edad: form.elements.edad.value,
        tipo: form.elements.tipo.value,
        precio: form.elements.precio.value,
        precio_lanzamiento: form.elements.precio_lanzamiento.value,
        duracion: form.elements.duracion.value,
        mes_mundo: form.elements.mes_mundo.checked,
    });

    const irA = (url) => {
        sucio = false;
        portadaPendiente = null;
        quitarAviso();
        location.assign(url);
    };

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const accion = event.submitter?.dataset.accion === 'continuar' ? 'continuar' : 'borrador';

        limpiarErrores();
        ocupado(true);

        let respuesta;
        try {
            respuesta = await pedir('POST', cursoId ? `/cursos/${cursoId}` : '/cursos', datosDelFormulario());
        } catch (error) {
            ocupado(false);
            mostrarErrores(error);
            return;
        }

        const nuevo = !cursoId;
        cursoId = Number(respuesta.id) || cursoId;
        sucio = false;

        // Curso nuevo con portada elegida: se sube ahora que el curso existe.
        let errorPortada = false;
        if (portadaPendiente) {
            try {
                await subirPortada(cursoId, portadaPendiente);
            } catch {
                errorPortada = true;
            }
            portadaPendiente = null;
        }

        if (accion === 'continuar' && !errorPortada) {
            irA(respuesta.url_paso_2);
            return;
        }
        if (nuevo) {
            // Con la URL de edición se habilitan los pasos 2 y 3.
            irA(conParametros(respuesta.url_editar, errorPortada ? { guardado: '1', portada: 'error' } : { guardado: '1' }));
            return;
        }

        ocupado(false);
        if (errorPortada) {
            errorDeCampo('portada', form.dataset.errorPortada);
        }
        anunciar(form.dataset.guardado);
    });

    // ---------- Al cargar ----------

    if (vistaPortada && !vistaPortada.classList.contains('hidden') && !imagenPortada.getAttribute('src')) {
        mostrarZona();
    }

    const url = new URL(location.href);
    if (url.searchParams.has('guardado') || url.searchParams.has('portada')) {
        if (url.searchParams.get('portada') === 'error') errorDeCampo('portada', form.dataset.errorPortada);
        if (url.searchParams.has('guardado')) anunciar(form.dataset.guardado);
        url.searchParams.delete('guardado');
        url.searchParams.delete('portada');
        history.replaceState(history.state, '', url);
    }
}
