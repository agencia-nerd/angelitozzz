/*
 * Piezas comunes del administrador de cursos (lista y constructor): textos con marcadores, el modal de
 * confirmación `eliminar` (template-parts/modals/eliminar.php), las peticiones a la API REST del plugin
 * (angelitozzz/v1, con el nonce wp_rest) y el aviso de cambios sin guardar.
 */

import { openModal, closeModal } from './modal.js';

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

/**
 * URL de una ruta de la API a partir de la base que arma PHP con rest_url() (data-api). No supone
 * enlaces permanentes: con los "bonitos" la base es …/wp-json/angelitozzz/v1/ y la ruta va en la
 * ruta de la URL; con los simples es …/?rest_route=/angelitozzz/v1/ y la ruta va en ese parámetro.
 * Los parámetros de `consulta` se agregan siempre con searchParams (nunca pegando "?" a mano).
 *
 * @param {string} base     rest_url('angelitozzz/v1/').
 * @param {string} ruta     Ruta dentro del espacio de nombres, empezando con "/" (p. ej. "/cursos/5").
 * @param {Object<string, string|number>} [consulta] Parámetros de la URL.
 * @returns {string}
 */
export function urlApi(base, ruta, consulta = {}) {
    const url = new URL(base, window.location.href);
    const tramo = '/' + String(ruta).replace(/^\/+/, '');
    const enParametro = url.searchParams.get('rest_route');

    if (enParametro !== null) {
        url.searchParams.set('rest_route', enParametro.replace(/\/+$/, '') + tramo);
    } else {
        url.pathname = url.pathname.replace(/\/+$/, '') + tramo;
    }
    Object.entries(consulta || {}).forEach(([clave, valor]) => url.searchParams.set(clave, String(valor)));
    return url.toString();
}

/**
 * Cliente de la API REST del plugin. `base` es rest_url('angelitozzz/v1/') (lo pasa PHP en data-api)
 * y `nonce` el de wp_rest. Devuelve pedir(metodo, ruta, cuerpo, consulta): el cuerpo puede ser un
 * objeto (JSON) o un FormData (archivos) y la consulta, un objeto con los parámetros de la URL. Si la
 * respuesta no es 2xx, lanza el JSON del error ({code, message, data}) o null si no hubo conexión.
 */
export function crearApi(base, nonce) {
    return async function pedir(metodo, ruta, cuerpo, consulta) {
        const esFormulario = cuerpo instanceof FormData;
        const cabeceras = { Accept: 'application/json', 'X-WP-Nonce': nonce };
        if (cuerpo && !esFormulario) cabeceras['Content-Type'] = 'application/json';

        let respuesta;
        try {
            respuesta = await fetch(urlApi(base, ruta, consulta), {
                method: metodo,
                credentials: 'same-origin',
                headers: cabeceras,
                body: cuerpo ? (esFormulario ? cuerpo : JSON.stringify(cuerpo)) : undefined,
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
}

/**
 * Pregunta antes de salir de la página mientras `hayCambios()` sea verdadero (beforeunload).
 * Devuelve una función que quita el aviso (por ejemplo, justo antes de navegar después de guardar).
 */
export function avisarCambiosSinGuardar(hayCambios) {
    const alSalir = (event) => {
        if (!hayCambios()) return;
        event.preventDefault();
        event.returnValue = '';
    };
    window.addEventListener('beforeunload', alSalir);
    return () => window.removeEventListener('beforeunload', alSalir);
}
