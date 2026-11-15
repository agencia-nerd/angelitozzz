/*
 * Lista de cursos del administrador (specs/admin-cursos.md): menú "Más opciones" de cada tarjeta,
 * publicar / pasar a borrador / eliminar por la API REST del plugin (angelitozzz/v1/cursos/<id>) y el
 * aviso "¡Curso publicado!".
 *
 * pedirConfirmacion() maneja el modal `eliminar` (template-parts/modals/eliminar.php) y la reutiliza
 * el constructor para lecciones, módulos y descargables.
 */

import { openModal, closeModal } from './modal.js';

const DURACION_AVISO = 6000;

/** Reemplaza %s, %d, %1$s, %2$d… en orden, como sprintf. */
export function formatear(plantilla, ...valores) {
    let siguiente = 0;
    return String(plantilla).replace(/%(?:(\d+)\$)?[sd]/g, (_, posicion) => {
        const indice = posicion ? Number(posicion) - 1 : siguiente++;
        return String(valores[indice] ?? '');
    });
}

/** Textos del modal `eliminar` (data-textos), o null si el modal no está en la página. */
export function textosEliminar() {
    const fuente = document.querySelector('#eliminar [data-textos]');
    if (!fuente) return null;
    try {
        return JSON.parse(fuente.dataset.textos);
    } catch {
        return null;
    }
}

/**
 * Abre el modal `eliminar` con un título y un texto. Resuelve 'confirmar', 'alternativa' o null
 * (Cancelar, Esc o cierre). El foco empieza en "Cancelar" y vuelve a `opener` al cerrar.
 *
 * @param {{titulo: string, texto: string, confirmar?: boolean, alternativa?: string, opener?: HTMLElement}} opciones
 * @returns {Promise<'confirmar'|'alternativa'|null>}
 */
export function pedirConfirmacion({ titulo, texto, confirmar = true, alternativa = '', opener = document.activeElement }) {
    const dialog = document.getElementById('eliminar');
    if (!(dialog instanceof HTMLDialogElement)) return Promise.resolve(null);

    const botonConfirmar = dialog.querySelector('[data-eliminar-confirmar]');
    const botonAlternativa = dialog.querySelector('[data-eliminar-alternativa]');
    const botonCancelar = dialog.querySelector('[data-eliminar-cancelar]');

    dialog.querySelector('#eliminar-title').textContent = titulo;
    const descripcion = dialog.querySelector('#eliminar-description');
    if (descripcion) descripcion.textContent = texto;

    botonConfirmar.hidden = !confirmar;
    botonAlternativa.hidden = !alternativa;
    if (alternativa) botonAlternativa.querySelector('span').textContent = alternativa;

    return new Promise((resolver) => {
        let resultado = null;

        const elegir = (valor) => (event) => {
            event.preventDefault(); // Son submit de un form method="dialog": se cierra a mano.
            resultado = valor;
            closeModal(dialog);
        };
        const alConfirmar = elegir('confirmar');
        const alAlternativa = elegir('alternativa');

        const alCerrar = () => {
            botonConfirmar.removeEventListener('click', alConfirmar);
            botonAlternativa.removeEventListener('click', alAlternativa);
            resolver(resultado);
        };

        botonConfirmar.addEventListener('click', alConfirmar);
        botonAlternativa.addEventListener('click', alAlternativa);
        dialog.addEventListener('close', alCerrar, { once: true });

        openModal(dialog, opener);
        botonCancelar?.focus();
    });
}

export function initAdminCursos() {
    const raiz = document.querySelector('[data-admin-cursos]');
    if (!raiz) return;

    const api = raiz.dataset.api;
    const nonce = raiz.dataset.nonce;
    const zonaError = raiz.querySelector('[data-admin-error]');
    const aviso = raiz.querySelector('[data-admin-aviso-tarjeta]');
    const avisoTexto = raiz.querySelector('[data-admin-aviso-texto]');
    let temporizador = 0;

    // ---------- Aviso ----------

    const ocultarAviso = () => {
        clearTimeout(temporizador);
        const teniaFoco = aviso.contains(document.activeElement);
        aviso.classList.add('hidden');
        aviso.classList.remove('flex');
        avisoTexto.textContent = '';
        if (teniaFoco) {
            const titulo = raiz.querySelector('h2');
            titulo.tabIndex = -1;
            titulo.focus({ preventScroll: true });
        }
    };

    const mostrarAviso = (texto) => {
        clearTimeout(temporizador);
        avisoTexto.textContent = '';
        aviso.classList.remove('hidden');
        aviso.classList.add('flex');
        // Se vacía y se vuelve a llenar para que el lector anuncie también un texto repetido.
        requestAnimationFrame(() => {
            avisoTexto.textContent = texto;
        });
        temporizador = setTimeout(ocultarAviso, DURACION_AVISO);
    };

    raiz.querySelector('[data-admin-aviso-cerrar]')?.addEventListener('click', ocultarAviso);

    if (!aviso.classList.contains('hidden') && avisoTexto.textContent.trim() !== '') {
        temporizador = setTimeout(ocultarAviso, DURACION_AVISO);
        // Al recargar no vuelve a salir.
        const url = new URL(location.href);
        if (url.searchParams.has('publicado')) {
            url.searchParams.delete('publicado');
            history.replaceState(history.state, '', url);
        }
    }

    // ---------- Errores ----------

    const limpiarError = () => zonaError.replaceChildren();

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
        zonaError.replaceChildren(...nodos);
    };

    // ---------- API ----------

    const pedir = async (metodo, ruta, cuerpo) => {
        let respuesta;
        try {
            respuesta = await fetch(api + ruta, {
                method: metodo,
                credentials: 'same-origin',
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': nonce,
                },
                body: cuerpo ? JSON.stringify(cuerpo) : undefined,
            });
        } catch {
            throw null;
        }
        let datos = null;
        try {
            datos = await respuesta.json();
        } catch {
            datos = null;
        }
        if (!respuesta.ok) throw datos;
        return datos;
    };

    const ocupada = (tarjeta, si) => {
        tarjeta.setAttribute('aria-busy', si ? 'true' : 'false');
        tarjeta.querySelectorAll('[data-accion], [data-curso-menu-boton]').forEach((boton) => {
            boton.disabled = si;
        });
    };

    // ---------- Menú "Más opciones" ----------

    const menuDe = (boton) => document.getElementById(boton.getAttribute('aria-controls'));

    const cerrarMenu = (boton, devolverFoco = false) => {
        const menu = menuDe(boton);
        if (!menu || menu.hidden) return;
        menu.hidden = true;
        boton.setAttribute('aria-expanded', 'false');
        if (devolverFoco) boton.focus();
    };

    const cerrarTodos = (salvo = null) => {
        raiz.querySelectorAll('[data-curso-menu-boton][aria-expanded="true"]').forEach((boton) => {
            if (boton !== salvo) cerrarMenu(boton);
        });
    };

    const abrirMenu = (boton) => {
        cerrarTodos(boton);
        menuDe(boton).hidden = false;
        boton.setAttribute('aria-expanded', 'true');
    };

    raiz.addEventListener('click', (event) => {
        const boton = event.target.closest('[data-curso-menu-boton]');
        if (!boton) return;
        if (boton.getAttribute('aria-expanded') === 'true') {
            cerrarMenu(boton);
        } else {
            abrirMenu(boton);
        }
    });

    document.addEventListener('click', (event) => {
        if (!event.target.closest('[data-curso-menu-contenedor]')) cerrarTodos();
    });

    raiz.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape') return;
        const contenedor = event.target.closest('[data-curso-menu-contenedor]');
        const boton = contenedor?.querySelector('[data-curso-menu-boton][aria-expanded="true"]');
        if (!boton) return;
        event.preventDefault();
        cerrarMenu(boton, true);
    });

    // Si el foco sale del menú (Tab), se cierra.
    raiz.addEventListener('focusout', (event) => {
        const contenedor = event.target.closest('[data-curso-menu-contenedor]');
        if (!contenedor || contenedor.contains(event.relatedTarget)) return;
        const boton = contenedor.querySelector('[data-curso-menu-boton]');
        if (event.relatedTarget) cerrarMenu(boton);
    });

    // ---------- Acciones ----------

    const actualizarEstado = (tarjeta, estado) => {
        tarjeta.dataset.estado = estado;
        tarjeta.querySelector('[data-curso-etiqueta]')?.classList.toggle('hidden', estado === 'publicado');
        const boton = tarjeta.querySelector('[data-accion="publicar"], [data-accion="borrador"]');
        if (boton) {
            boton.dataset.accion = estado === 'publicado' ? 'borrador' : 'publicar';
            boton.textContent = estado === 'publicado' ? boton.dataset.textoBorrador : boton.dataset.textoPublicar;
        }
    };

    const cambiarEstado = async (tarjeta, estado) => {
        limpiarError();
        ocupada(tarjeta, true);
        try {
            const datos = await pedir('POST', `${tarjeta.dataset.id}/estado`, { estado });
            actualizarEstado(tarjeta, datos?.estado || estado);
            if (datos?.aviso) mostrarAviso(datos.aviso);
        } catch (error) {
            mostrarError(error);
        } finally {
            ocupada(tarjeta, false);
        }
    };

    const eliminar = async (tarjeta, opener) => {
        const textos = textosEliminar();
        if (!textos) return;

        const titulo = tarjeta.dataset.titulo;
        const estudiantes = Number(tarjeta.dataset.estudiantes) || 0;

        if (estudiantes > 0) {
            const t = textos.curso_con_estudiantes;
            const cuantas = estudiantes === 1 ? t.una : formatear(t.varias, estudiantes);
            const eleccion = await pedirConfirmacion({
                titulo: t.titulo,
                texto: formatear(t.texto, titulo, cuantas),
                confirmar: false,
                alternativa: tarjeta.dataset.estado === 'publicado' ? t.alternativa : '',
                opener,
            });
            if (eleccion === 'alternativa') await cambiarEstado(tarjeta, 'borrador');
            return;
        }

        const eleccion = await pedirConfirmacion({
            titulo: textos.curso.titulo,
            texto: formatear(textos.curso.texto, titulo),
            opener,
        });
        if (eleccion !== 'confirmar') return;

        limpiarError();
        ocupada(tarjeta, true);
        try {
            const datos = await pedir('DELETE', tarjeta.dataset.id);
            const item = tarjeta.closest('li');
            const lista = item?.parentElement;
            item?.remove();
            if (lista && !lista.children.length) {
                lista.hidden = true;
                raiz.querySelector('[data-admin-cursos-vacio]')?.classList.remove('hidden');
            }
            // La tarjeta (y su botón, que tenía el foco) ya no existe: el foco va al título de la lista.
            const encabezado = raiz.querySelector('h2');
            encabezado.tabIndex = -1;
            encabezado.focus({ preventScroll: true });
            if (datos?.aviso) mostrarAviso(datos.aviso);
        } catch (error) {
            ocupada(tarjeta, false);
            mostrarError(error);
        }
    };

    raiz.addEventListener('click', (event) => {
        const accion = event.target.closest('[data-accion]');
        const tarjeta = accion?.closest('[data-curso-card]');
        if (!accion || !tarjeta || accion.disabled) return;

        const boton = tarjeta.querySelector('[data-curso-menu-boton]');
        cerrarMenu(boton);

        if (accion.dataset.accion === 'eliminar') {
            eliminar(tarjeta, boton);
        } else {
            cambiarEstado(tarjeta, accion.dataset.accion === 'publicar' ? 'publicado' : 'borrador').then(() => boton.focus());
        }
    });
}
