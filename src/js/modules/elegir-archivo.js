/*
 * Archivos privados en el constructor (specs/admin-constructor.md → modal-leccion y modal-archivos):
 *
 * - crearLista(raiz, opciones): "Elegir de mis archivos" sobre la vista
 *   template-parts/pages/administrador/lista-archivos.php ([data-lista-archivos]), con GET /archivos del
 *   plugin (clase fija, buscador y "Cargar más", 24 por vez). Cada fila es un <button> "Elegir <nombre>".
 * - iconoMaterial(datos): el ícono de un material, con la misma regla que angelitozzz_icono_material()
 *   (inc/leccion.php).
 * - crearCola(): sube de a un archivo por vez con window.AngelitozzzSubida (script del plugin, handle
 *   angelitozzz-cursos-subida). Una sola subida a la vez cuida los procesos del hosting.
 */

import { formatear } from './admin-comun.js';

/** documento, hoja, presentacion, imagen o audio (como angelitozzz_icono_material()). */
export function iconoMaterial(datos = {}) {
    const extension = String(datos.extension || '').toLowerCase();
    if (extension === 'xlsx') return 'hoja';
    if (extension === 'pptx') return 'presentacion';
    const clase = String(datos.clase || '');
    if (clase === 'imagen' || clase === 'audio') return clase;
    return 'documento';
}

/**
 * Cola de subidas: agregar() devuelve { promesa, cancelar }. La promesa resuelve los datos del archivo
 * guardado (con portada_url en un video) o se rechaza (con error.message, o 'cancelada').
 *
 * @param {{ sinSubida: string }} textos
 */
export function crearCola(textos = {}) {
    let cadena = Promise.resolve();
    let pendientes = 0;

    const agregar = (archivo, opciones = {}, { alEmpezar } = {}) => {
        const tarea = { cancelada: false, subida: null };
        pendientes++;
        const trabajo = cadena.then(() => {
            if (tarea.cancelada) throw new Error('cancelada');
            const motor = window.AngelitozzzSubida;
            if (!motor?.subir) throw new Error(textos.sinSubida || 'Error');
            alEmpezar?.();
            tarea.subida = motor.subir(archivo, opciones);
            return tarea.subida.promesa;
        });
        // Cancelar una subida en espera la rechaza enseguida (no espera su turno).
        let rechazar;
        const cancelacion = new Promise((_, r) => {
            rechazar = r;
        });
        cancelacion.catch(() => {});
        const promesa = Promise.race([trabajo, cancelacion]).finally(() => {
            pendientes--;
        });
        cadena = trabajo.catch(() => {});
        return {
            promesa,
            cancelar: () => {
                if (tarea.cancelada) return;
                tarea.cancelada = true;
                tarea.subida?.cancelar();
                rechazar(new Error('cancelada'));
            },
        };
    };

    return { agregar, ocupada: () => pendientes > 0 };
}

/**
 * Lista de "Elegir de mis archivos".
 *
 * @param {HTMLElement} raiz [data-lista-archivos]
 * @param {{ pedir: Function, iconos: Object<string, string>, alElegir: (archivo: object, boton: HTMLElement) => void, alSubir?: () => void }} opciones
 * @returns {{ abrir: (clase?: string) => void }}
 */
export function crearLista(raiz, { pedir, iconos = {}, alElegir, alSubir }) {
    let t = {};
    try {
        t = JSON.parse(raiz.dataset.textos || '{}');
    } catch {
        t = {};
    }
    const buscar = raiz.querySelector('[data-lista-buscar]');
    const filas = raiz.querySelector('[data-lista-filas]');
    const estado = raiz.querySelector('[data-lista-estado]');
    const zonaError = raiz.querySelector('[data-lista-error]');
    const vacia = raiz.querySelector('[data-lista-vacia]');
    const sinResultados = raiz.querySelector('[data-lista-sin-resultados]');
    const cargando = raiz.querySelector('[data-lista-cargando]');
    const mas = raiz.querySelector('[data-lista-mas]');
    const plantilla = raiz.querySelector('template[data-lista-plantilla]');

    let pagina = 0;
    let paginas = 0;
    let turno = 0;
    let espera = 0;
    let contador = 0;

    const anunciar = (texto) => {
        estado.textContent = '';
        requestAnimationFrame(() => {
            estado.textContent = texto;
        });
    };

    const fila = (archivo) => {
        const li = plantilla.content.firstElementChild.cloneNode(true);
        const boton = li.querySelector('[data-lista-elegir]');
        const nombre = String(archivo.nombre ?? '');
        const esVideo = archivo.clase === 'video';
        const detalle = [archivo.tipo_texto, archivo.tamano_texto, esVideo ? archivo.duracion_texto : ''].filter(Boolean).join(' · ');
        const usos = Array.isArray(archivo.en_uso) ? archivo.en_uso.length : 0;

        const imagen = li.querySelector('[data-lista-imagen]');
        const foto = esVideo ? archivo.portada_url : archivo.clase === 'imagen' ? archivo.url_ver : '';
        if (foto) {
            imagen.src = foto;
        } else {
            imagen.src = iconos[esVideo ? 'video' : iconoMaterial(archivo)] || '';
            imagen.classList.remove('bg-neutro', 'object-cover', 'rounded-[8px]');
            imagen.classList.add('p-2');
        }

        li.querySelector('[data-lista-nombre]').textContent = nombre;
        const zonaDetalle = li.querySelector('[data-lista-detalle]');
        zonaDetalle.textContent = detalle;
        zonaDetalle.id = `${raiz.dataset.listaId || 'lista'}-detalle-${++contador}`;
        li.querySelector('[data-lista-uso]').textContent = usos ? (usos === 1 ? t.usadoEnUna : formatear(t.usadoEnVarias, usos)) : '';

        boton.setAttribute('aria-label', formatear(t.elegir, nombre));
        boton.setAttribute('aria-describedby', zonaDetalle.id);
        boton.addEventListener('click', () => alElegir?.(archivo, boton));
        return li;
    };

    const cargar = async (siguiente) => {
        const mio = ++turno;
        const numero = siguiente ? pagina + 1 : 1;
        const texto = buscar.value.trim();
        zonaError.hidden = true;
        cargando.hidden = false;
        mas.hidden = true;
        if (!siguiente) {
            vacia.hidden = true;
            sinResultados.hidden = true;
        }
        let datos;
        try {
            datos = await pedir('GET', '/archivos', null, { clase: raiz.dataset.clase || '', buscar: texto, pagina: numero });
        } catch (error) {
            if (mio !== turno) return;
            cargando.hidden = true;
            zonaError.querySelector('[data-lista-error-texto]').textContent = t.error || error?.message || '';
            zonaError.hidden = false;
            return;
        }
        if (mio !== turno) return;
        cargando.hidden = true;
        if (!siguiente) filas.replaceChildren();
        const archivos = Array.isArray(datos?.archivos) ? datos.archivos : [];
        archivos.forEach((archivo) => filas.append(fila(archivo)));
        pagina = Number(datos?.pagina || numero);
        paginas = Number(datos?.paginas || 0);
        mas.hidden = pagina >= paginas;

        const total = Number(datos?.total || 0);
        if (!siguiente) {
            vacia.hidden = total > 0 || texto !== '';
            sinResultados.hidden = total > 0 || texto === '';
            anunciar(total === 1 ? t.totalUno : formatear(t.total, total));
        }
        if (siguiente) {
            // El foco pasa a la primera fila nueva (el botón "Cargar más" puede desaparecer).
            filas.children[filas.children.length - archivos.length]?.querySelector('button')?.focus();
        }
    };

    buscar.addEventListener('input', () => {
        clearTimeout(espera);
        espera = setTimeout(() => cargar(false), 300);
    });
    buscar.addEventListener('keydown', (event) => {
        if (event.key !== 'Enter') return;
        event.preventDefault(); // No envía el formulario de la lección.
        clearTimeout(espera);
        cargar(false);
    });
    raiz.querySelector('[data-lista-reintentar]')?.addEventListener('click', () => cargar(pagina > 0 && filas.children.length > 0));
    mas.addEventListener('click', () => cargar(true));
    raiz.querySelector('[data-lista-subir]')?.addEventListener('click', () => alSubir?.());

    return {
        abrir(clase) {
            if (clase) raiz.dataset.clase = clase;
            clearTimeout(espera);
            buscar.value = '';
            filas.replaceChildren();
            pagina = 0;
            paginas = 0;
            cargar(false);
            requestAnimationFrame(() => buscar.focus());
        },
    };
}
