/*
 * Constructor · paso 3 "Publicar" (specs/admin-constructor.md → paso-publicar): la visibilidad cambia el
 * texto del botón principal ("Publicar curso" / "Guardar como borrador") y se guarda por la API del
 * plugin (POST cursos/<id>/estado). Al publicar, vuelve a la lista con el aviso "¡Curso publicado!"
 * (?publicado=<id>). Si falta algo, el servidor responde 422 con data.pendientes.
 */

import { crearApi } from './admin-comun.js';

export function initConstructorPublicar() {
    const form = document.querySelector('[data-constructor-publicar]');
    const raiz = form?.closest('[data-constructor]');
    if (!form || !raiz) return;

    const pedir = crearApi(raiz.dataset.api, raiz.dataset.nonce);
    const cursoId = Number(raiz.dataset.curso) || 0;
    const principal = form.querySelector('[data-principal]');
    const textoPrincipal = principal?.querySelector('span');
    const resumen = form.querySelector('[data-errores]');
    const estado = form.querySelector('[data-estado]');

    const elegida = () => form.querySelector('input[name="visibilidad"]:checked')?.value === 'publicado' ? 'publicado' : 'borrador';

    const actualizarBoton = () => {
        if (textoPrincipal) {
            textoPrincipal.textContent = elegida() === 'publicado' ? form.dataset.textoPublicar : form.dataset.textoBorrador;
        }
    };

    form.addEventListener('change', (event) => {
        if (event.target.matches('input[name="visibilidad"]')) actualizarBoton();
    });
    actualizarBoton();

    const anunciar = (texto) => {
        estado.textContent = '';
        requestAnimationFrame(() => {
            estado.textContent = texto;
        });
    };

    const mostrarError = (error) => {
        const parrafo = document.createElement('p');
        parrafo.textContent = error?.message || raiz.dataset.errorGenerico;
        const nodos = [parrafo];

        const pendientes = error?.data?.pendientes;
        if (Array.isArray(pendientes) && pendientes.length) {
            const lista = document.createElement('ul');
            lista.className = 'mt-2 list-disc pl-5';
            pendientes.forEach((texto) => {
                const item = document.createElement('li');
                item.textContent = texto;
                lista.append(item);
            });
            nodos.push(lista);
        }
        resumen.replaceChildren(...nodos);
        resumen.focus();
    };

    const ocupado = (si) => {
        form.querySelectorAll('button, input').forEach((control) => {
            control.disabled = si;
        });
        form.setAttribute('aria-busy', si ? 'true' : 'false');
    };

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        if (!cursoId) return;

        const visibilidad = elegida();
        resumen.replaceChildren();
        estado.textContent = '';
        ocupado(true);

        try {
            await pedir('POST', `/cursos/${cursoId}/estado`, { estado: visibilidad });
        } catch (error) {
            ocupado(false);
            mostrarError(error);
            return;
        }

        if (visibilidad === 'publicado') {
            const destino = new URL(raiz.dataset.urlCursos, location.href);
            destino.searchParams.set('publicado', String(cursoId));
            location.assign(destino.toString());
            return;
        }

        ocupado(false);
        anunciar(form.dataset.guardadoBorrador);
    });
}
