/*
 * Constructor · paso 2 "Contenido" (specs/admin-constructor.md → paso-contenido, modal-leccion,
 * modal-eliminar). Raíz [data-constructor-contenido] dentro de [data-constructor] (data-api,
 * data-nonce, data-curso).
 *
 * - Módulos plegables (botón con aria-expanded que muestra u oculta su panel).
 * - Orden: arrastre nativo (asa de cada ítem o módulo) y botones Subir/Bajar para teclado y móvil.
 *   Después de cada movimiento se manda el orden completo (POST cursos/<id>/orden); si falla, todo
 *   vuelve a como estaba y se avisa.
 * - Agregar y editar módulos (modal editar-modulo), lecciones (modal editar-leccion, con el editor de
 *   editor-texto.js) y descargables del curso; eliminar con el modal `eliminar`.
 * - El video y los descargables son archivos privados (specs/admin-constructor.md → modal-leccion y
 *   modal-archivos): se suben por partes con el script del plugin (crearCola() de elegir-archivo.js, de a
 *   uno por vez) o se eligen de "Archivos privados" (crearLista()). Lo subido queda enseguida en
 *   "Archivos privados"; la lección guarda el ID del video y vincula los descargables por archivo_id.
 * Cada cambio se guarda al momento por la API del plugin.
 */

import { openModal, closeModal } from './modal.js';
import { crearApi, formatear, pedirConfirmacion, textosEliminar, avisarCambiosSinGuardar } from './admin-comun.js';
import { crearEditor } from './editor-texto.js';
import { crearCola, crearLista, iconoMaterial } from './elegir-archivo.js';

const VIDEOS = ['mp4', 'webm'];
const MATERIALES = ['pdf', 'docx', 'xlsx', 'pptx', 'jpg', 'jpeg', 'png', 'webp', 'mp3'];

const extension = (nombre) => {
    const partes = String(nombre).toLowerCase().split('.');
    return partes.length > 1 ? partes.pop() : '';
};

/** Datos de un archivo privado (de la subida o de GET /archivos) con la forma de un material del constructor. */
const comoMaterial = (archivo) => ({
    id: Number(archivo.id),
    nombre: String(archivo.nombre ?? ''),
    clase: String(archivo.clase ?? ''),
    extension: String(archivo.extension ?? ''),
    tamano: [archivo.tipo_texto, archivo.tamano_texto].filter(Boolean).join(' · '),
});

/** El texto del corte de la subida del plugin, para cambiarlo por el del video. */
const textoCorte = () => window.AngelitozzzSubidaConfig?.textos?.corte || '';

export function initConstructorContenido() {
    const raiz = document.querySelector('[data-constructor-contenido]');
    const base = raiz?.closest('[data-constructor]');
    if (!raiz || !base || raiz.dataset.listo) return;
    raiz.dataset.listo = '1';

    const cursoId = base.dataset.curso;
    const pedir = crearApi(base.dataset.api, base.dataset.nonce);
    const lista = raiz.querySelector('[data-modulos]');
    const zonaError = raiz.querySelector('[data-contenido-error]');
    const zonaEstado = raiz.querySelector('[data-contenido-estado]');
    const estudiantes = Number(raiz.dataset.estudiantes || 0);
    let t = {};
    try {
        t = JSON.parse(raiz.dataset.textos || '{}');
    } catch {
        t = {};
    }

    // ---------- Avisos ----------

    const anunciar = (texto) => {
        zonaEstado.textContent = '';
        requestAnimationFrame(() => {
            zonaEstado.textContent = texto;
        });
    };
    const limpiarError = () => {
        zonaError.textContent = '';
    };
    const mostrarError = (error) => {
        zonaError.textContent = error?.message || t.error || base.dataset.errorGenerico || '';
    };

    // ---------- Lectura del DOM ----------

    const plantilla = (nombre) => raiz.querySelector(`template[data-plantilla="${nombre}"]`).content.firstElementChild.cloneNode(true);
    const modulos = () => [...lista.children].filter((el) => el.matches('[data-modulo]'));
    const listaDe = (modulo) => modulo.querySelector('[data-items]');
    const itemsDe = (modulo) => [...listaDe(modulo).children].filter((el) => el.matches('[data-item]'));
    const leccionesDe = (modulo) => itemsDe(modulo).filter((li) => li.dataset.tipo === 'leccion');

    const abrirModulo = (modulo) => {
        modulo.querySelector('[data-modulo-plegar]').setAttribute('aria-expanded', 'true');
        modulo.querySelector('[data-modulo-panel]').hidden = false;
    };

    // ---------- Pintar ----------

    const rellenarItem = (li, datos) => {
        const material = li.dataset.tipo === 'material';
        const titulo = String((material ? datos.nombre : datos.titulo) ?? '');
        li.dataset.id = String(datos.id);
        li.dataset.titulo = titulo;
        li.querySelector('[data-item-titulo]').textContent = titulo;

        let linea;
        if (material) {
            linea = String(datos.tamano ?? ''); // "PDF · 2,4 MB", "Word · 120 KB".
        } else {
            li.dataset.leccion = JSON.stringify(datos);
            linea = Number(datos.video) > 0 ? t.video + (datos.duracion_texto ? ` · ${datos.duracion_texto}` : '') : t.sinVideo;
        }
        li.querySelector('[data-item-linea]').textContent = linea;

        const desc = li.querySelector('[data-item-desc]');
        const extracto = material ? '' : String(datos.extracto ?? '');
        desc.textContent = extracto ? formatear(t.descripcion, extracto) : '';
        desc.classList.toggle('hidden', !extracto);
    };

    const etiquetar = (boton, plantillaTexto, titulo) => boton?.setAttribute('aria-label', formatear(plantillaTexto, titulo));

    /** Números, conteos, nombres accesibles y botones Subir/Bajar desactivados en los extremos. */
    const renumerar = () => {
        const todos = modulos();
        todos.forEach((modulo, indice) => {
            const titulo = modulo.dataset.titulo || '';
            const panel = modulo.querySelector('[data-modulo-panel]');
            const plegar = modulo.querySelector('[data-modulo-plegar]');
            const cuantas = leccionesDe(modulo).length;

            panel.id = `modulo-${modulo.dataset.id || indice}-panel`;
            plegar.setAttribute('aria-controls', panel.id);
            modulo.querySelector('[data-modulo-numero]').textContent = formatear(t.moduloNumero, indice + 1);
            modulo.querySelector('[data-modulo-titulo]').textContent = titulo;
            modulo.querySelector('[data-modulo-conteo]').textContent = ` · ${cuantas === 1 ? t.unaLeccion : formatear(t.lecciones, cuantas)}`;
            modulo.querySelector('[data-modulo-vacio]').classList.toggle('hidden', itemsDe(modulo).length > 0);

            etiquetar(modulo.querySelector('[data-modulo-editar]'), t.editarModulo, titulo);
            etiquetar(modulo.querySelector('[data-modulo-eliminar]'), t.eliminarModulo, titulo);
            const subirModulo = modulo.querySelector('[data-mover-modulo="arriba"]');
            const bajarModulo = modulo.querySelector('[data-mover-modulo="abajo"]');
            etiquetar(subirModulo, t.subirModulo, titulo);
            etiquetar(bajarModulo, t.bajarModulo, titulo);
            subirModulo.disabled = indice === 0;
            bajarModulo.disabled = indice === todos.length - 1;

            const items = itemsDe(modulo);
            items.forEach((li, posicion) => {
                const nombre = li.dataset.titulo || '';
                const material = li.dataset.tipo === 'material';
                const subir = li.querySelector('[data-mover="arriba"]');
                const bajar = li.querySelector('[data-mover="abajo"]');
                etiquetar(subir, t.subir, nombre);
                etiquetar(bajar, t.bajar, nombre);
                etiquetar(li.querySelector('[data-item-editar]'), t.editarLeccion, nombre);
                etiquetar(li.querySelector('[data-item-eliminar]'), material ? t.eliminarDescargable : t.eliminarLeccion, nombre);
                subir.disabled = indice === 0 && posicion === 0;
                bajar.disabled = indice === todos.length - 1 && posicion === items.length - 1;
            });
        });
    };

    // ---------- Orden ----------

    const capturar = () => modulos().map((modulo) => ({ modulo, items: itemsDe(modulo) }));

    const restaurar = (foto) => {
        foto.forEach(({ modulo, items }) => {
            lista.append(modulo);
            const ul = listaDe(modulo);
            items.forEach((li) => ul.append(li));
        });
        renumerar();
    };

    const cargaOrden = () => {
        const items = {};
        const ids = modulos().map((modulo) => {
            items[modulo.dataset.id] = itemsDe(modulo).map((li) => ({ tipo: li.dataset.tipo, id: Number(li.dataset.id) }));
            return modulo.dataset.id;
        });
        return { modulos: ids, items };
    };

    // Las peticiones de orden van en fila: cada una manda el estado completo del momento.
    let cola = Promise.resolve();
    const guardarOrden = (foto) => {
        const carga = cargaOrden();
        cola = cola.then(async () => {
            try {
                await pedir('POST', `/cursos/${cursoId}/orden`, carga);
                limpiarError();
                anunciar(t.ordenGuardado);
            } catch (error) {
                restaurar(foto);
                mostrarError({ message: t.ordenError + (error?.message ? ` ${error.message}` : '') });
            }
        });
        return cola;
    };

    /** Mueve un ítem un lugar; al llegar al borde pasa al módulo vecino. Devuelve si se movió. */
    const moverItem = (li, direccion) => {
        const ul = li.parentElement;
        const modulo = li.closest('[data-modulo]');
        if (direccion === 'arriba') {
            const anterior = li.previousElementSibling;
            if (anterior) {
                ul.insertBefore(li, anterior);
                return true;
            }
            const moduloAnterior = modulo.previousElementSibling;
            if (!moduloAnterior) return false;
            listaDe(moduloAnterior).append(li);
            abrirModulo(moduloAnterior);
            return true;
        }
        const siguiente = li.nextElementSibling;
        if (siguiente) {
            ul.insertBefore(siguiente, li);
            return true;
        }
        const moduloSiguiente = modulo.nextElementSibling;
        if (!moduloSiguiente) return false;
        listaDe(moduloSiguiente).prepend(li);
        abrirModulo(moduloSiguiente);
        return true;
    };

    const moverModulo = (modulo, direccion) => {
        const vecino = direccion === 'arriba' ? modulo.previousElementSibling : modulo.nextElementSibling;
        if (!vecino) return false;
        if (direccion === 'arriba') lista.insertBefore(modulo, vecino);
        else lista.insertBefore(vecino, modulo);
        return true;
    };

    /** Tras mover con teclado el foco sigue en el mismo botón (o en su pareja si quedó desactivado). */
    const reenfocar = (boton, pareja) => {
        requestAnimationFrame(() => {
            (boton.disabled ? pareja : boton)?.focus();
        });
    };

    // ---------- Arrastre nativo ----------

    let arrastrado = null;
    let fotoArrastre = null;
    let ordenAntes = '';

    lista.addEventListener('pointerdown', (event) => {
        const asa = event.target.closest('[data-asa], [data-asa-modulo]');
        if (!asa) return;
        const elemento = asa.matches('[data-asa]') ? asa.closest('[data-item]') : asa.closest('[data-modulo]');
        elemento.draggable = true;
    });

    document.addEventListener('pointerup', () => {
        if (arrastrado) return;
        lista.querySelectorAll('[draggable="true"]').forEach((el) => {
            el.draggable = false;
        });
    });

    lista.addEventListener('dragstart', (event) => {
        const elemento = event.target.closest?.('[draggable="true"]');
        if (!elemento) return;
        arrastrado = elemento;
        fotoArrastre = capturar();
        ordenAntes = JSON.stringify(cargaOrden());
        elemento.dataset.arrastrando = '';
        event.dataTransfer.effectAllowed = 'move';
        event.dataTransfer.setData('text/plain', elemento.dataset.titulo || '');
    });

    lista.addEventListener('dragover', (event) => {
        if (!arrastrado) return;
        event.preventDefault();
        event.dataTransfer.dropEffect = 'move';

        if (arrastrado.matches('[data-modulo]')) {
            const destino = event.target.closest('[data-modulo]');
            if (!destino || destino === arrastrado) return;
            const caja = destino.getBoundingClientRect();
            lista.insertBefore(arrastrado, event.clientY < caja.top + caja.height / 2 ? destino : destino.nextElementSibling);
            return;
        }

        const item = event.target.closest('[data-item]');
        if (item && item !== arrastrado) {
            const caja = item.getBoundingClientRect();
            item.parentElement.insertBefore(arrastrado, event.clientY < caja.top + caja.height / 2 ? item : item.nextElementSibling);
            return;
        }
        // Sobre la cabecera o un módulo vacío: al final de ese módulo (y se abre).
        const modulo = event.target.closest('[data-modulo]');
        if (modulo && !item && arrastrado.closest('[data-modulo]') !== modulo) {
            listaDe(modulo).append(arrastrado);
            abrirModulo(modulo);
        }
    });

    lista.addEventListener('drop', (event) => {
        if (arrastrado) event.preventDefault();
    });

    lista.addEventListener('dragend', () => {
        if (!arrastrado) return;
        delete arrastrado.dataset.arrastrando;
        arrastrado.draggable = false;
        arrastrado = null;
        renumerar();
        if (JSON.stringify(cargaOrden()) !== ordenAntes) guardarOrden(fotoArrastre);
    });

    // ---------- Eliminar ----------

    const textoEliminar = (clave, ...valores) => {
        const textos = textosEliminar();
        if (!textos?.[clave]) return null;
        const extra = estudiantes > 0 && textos.inscritas ? ` ${textos.inscritas}` : '';
        return { titulo: textos[clave].titulo, texto: formatear(textos[clave].texto, ...valores) + extra };
    };

    const eliminarItem = async (li, boton) => {
        const material = li.dataset.tipo === 'material';
        const textos = textoEliminar(material ? 'descargable' : 'leccion', li.dataset.titulo);
        if (!textos || (await pedirConfirmacion({ ...textos, opener: boton })) !== 'confirmar') return;

        const modulo = li.closest('[data-modulo]');
        try {
            await pedir('DELETE', material ? `/cursos/${cursoId}/materiales/${li.dataset.id}` : `/lecciones/${li.dataset.id}`);
        } catch (error) {
            mostrarError(error);
            return;
        }
        const siguiente = li.nextElementSibling || li.previousElementSibling;
        li.remove();
        renumerar();
        limpiarError();
        anunciar(t.eliminado);
        requestAnimationFrame(() => {
            (siguiente?.querySelector('[data-item-eliminar]') || modulo.querySelector('[data-agregar-leccion]')).focus();
        });
    };

    const eliminarModulo = async (modulo, boton) => {
        const textos = textoEliminar('modulo', modulo.dataset.titulo, leccionesDe(modulo).length);
        if (!textos || (await pedirConfirmacion({ ...textos, opener: boton })) !== 'confirmar') return;
        try {
            await pedir('DELETE', `/cursos/${cursoId}/modulos/${modulo.dataset.id}`);
        } catch (error) {
            mostrarError(error);
            return;
        }
        const siguiente = modulo.nextElementSibling || modulo.previousElementSibling;
        modulo.remove();
        renumerar();
        limpiarError();
        anunciar(t.eliminado);
        requestAnimationFrame(() => {
            (siguiente?.querySelector('[data-modulo-plegar]') || raiz.querySelector('[data-agregar-modulo]')).focus();
        });
    };

    // ---------- Errores por campo (modales) ----------

    const marcarError = (form, campo, texto) => {
        const zona = form.querySelector(`[data-error-de="${campo}"]`);
        if (!zona) return false;
        zona.textContent = texto;
        const control = form.querySelector(`[name="${campo}"]`);
        if (control) {
            control.setAttribute('aria-invalid', 'true');
            const ids = new Set((control.getAttribute('aria-describedby') || '').split(' ').filter(Boolean));
            ids.add(zona.id);
            control.setAttribute('aria-describedby', [...ids].join(' '));
        }
        return true;
    };

    const limpiarErrores = (form) => {
        form.querySelectorAll('[data-error-de]').forEach((zona) => {
            zona.textContent = '';
        });
        form.querySelectorAll('[aria-invalid]').forEach((control) => {
            control.removeAttribute('aria-invalid');
            const zona = form.querySelector(`[data-error-de="${control.name}"]`);
            if (zona?.id) {
                const ids = (control.getAttribute('aria-describedby') || '').split(' ').filter((id) => id && id !== zona.id);
                if (ids.length) control.setAttribute('aria-describedby', ids.join(' '));
                else control.removeAttribute('aria-describedby');
            }
        });
    };

    /** Reparte data.campos de un 400 en sus campos; lo que no tiene campo va a `general`. */
    const mostrarErrores = (form, error, general) => {
        const campos = error?.data?.campos;
        let primero = null;
        if (campos && typeof campos === 'object') {
            Object.entries(campos).forEach(([campo, texto]) => {
                if (marcarError(form, campo, texto)) primero ??= form.querySelector(`[name="${campo}"]`);
                else if (general) general.textContent = texto;
            });
        } else if (general) {
            general.textContent = error?.message || t.error;
        }
        primero?.focus();
    };

    // ---------- Modal "Editar módulo" ----------

    const dialogModulo = document.getElementById('editar-modulo');
    const formModulo = dialogModulo?.querySelector('[data-modulo-form]');
    let moduloEditado = null;

    const abrirEditarModulo = (modulo, opener) => {
        if (!formModulo) return;
        moduloEditado = modulo;
        limpiarErrores(formModulo);
        formModulo.titulo.value = modulo.dataset.titulo || '';
        openModal(dialogModulo, opener);
        formModulo.titulo.focus();
        formModulo.titulo.select();
    };

    formModulo?.querySelector('[data-modulo-cancelar]')?.addEventListener('click', () => closeModal(dialogModulo));

    formModulo?.addEventListener('submit', async (event) => {
        event.preventDefault();
        if (!moduloEditado) return;
        limpiarErrores(formModulo);
        const titulo = formModulo.titulo.value.trim();
        if (!titulo) {
            marcarError(formModulo, 'titulo', formModulo.titulo.validationMessage || t.error);
            formModulo.titulo.focus();
            return;
        }
        const guardar = formModulo.querySelector('[data-modulo-guardar]');
        guardar.disabled = true;
        try {
            const respuesta = await pedir('POST', `/cursos/${cursoId}/modulos/${moduloEditado.dataset.id}`, { titulo });
            moduloEditado.dataset.titulo = respuesta.titulo;
            renumerar();
            closeModal(dialogModulo);
            anunciar(t.moduloGuardado);
        } catch (error) {
            mostrarErrores(formModulo, error, formModulo.querySelector('[data-error-de="titulo"]'));
        } finally {
            guardar.disabled = false;
        }
    });

    // ---------- Modal "Editar lección" ----------

    const dialogLeccion = document.getElementById('editar-leccion');
    const formLeccion = dialogLeccion?.querySelector('[data-leccion-form]');
    const errorLeccion = formLeccion?.querySelector('[data-leccion-error]');
    const zonaVideo = formLeccion?.querySelector('[data-video]');
    const listaMateriales = formLeccion?.querySelector('[data-materiales]');
    const vistaArchivos = formLeccion?.querySelector('[data-leccion-archivos]');
    const d = formLeccion?.dataset ?? {};
    let iconos = {};
    try {
        iconos = JSON.parse(d.iconos || '{}');
    } catch {
        iconos = {};
    }
    // Una sola fila de subidas para toda la página (lección y descargables del paso 2).
    const filaSubidas = crearCola({ sinSubida: d.sinSubida || t.sinSubida });

    const enVideo = (selector) => zonaVideo?.querySelector(selector);
    const entradaVideo = enVideo('[data-video-subir]');
    const entradaMaterial = formLeccion?.querySelector('[data-leccion-material]');

    let editor = null;
    /**
     * La lección abierta: { modulo, li, video: {id, nombre, duracion_texto, miniatura}|null, cambiandoVideo,
     * materiales (los vinculados al abrir), quitar: Set<id>, nuevos (subidos o elegidos, sin vincular aún),
     * subidas: [{uso, nombre, porcentaje, esperando, cancelada, cancelar, texto}] }.
     */
    let actual = null;
    let firmaInicial = '';

    const subiendo = () => Boolean(actual?.subidas.length);

    const firma = () =>
        actual
            ? JSON.stringify([
                  formLeccion.titulo.value,
                  actual.video?.id ?? 0,
                  editor?.getHTML() ?? '',
                  formLeccion.vista_previa.checked,
                  actual.nuevos.map((material) => material.id),
                  [...actual.quitar],
                  actual.subidas.length,
              ])
            : '';
    const hayCambios = () => Boolean(dialogLeccion?.open && actual && firma() !== firmaInicial);

    avisarCambiosSinGuardar(() => hayCambios() || filaSubidas.ocupada());

    /** "Guardar lección" desactivado (con su ayuda) mientras sube algo. */
    const actualizarGuardar = () => {
        const guardar = formLeccion?.querySelector('[data-leccion-guardar]');
        const espera = formLeccion?.querySelector('[data-leccion-espera]');
        if (!guardar || guardar.hasAttribute('aria-busy')) return;
        const bloqueado = subiendo();
        guardar.disabled = bloqueado;
        if (espera) espera.hidden = !bloqueado;
        if (bloqueado) guardar.setAttribute('aria-describedby', 'leccion-espera');
        else guardar.removeAttribute('aria-describedby');
    };

    const textoSubida = (subida) => (subida.esperando ? formatear(d.enEspera, subida.nombre) : formatear(d.subiendo, subida.nombre, subida.porcentaje));

    // ----- Video -----

    const pintarVideo = () => {
        if (!actual || !zonaVideo) return;
        const subida = actual.subidas.find((s) => s.uso === 'video');
        const { video } = actual;
        formLeccion.video.value = video ? String(video.id) : '';

        const miniatura = enVideo('[data-video-miniatura]');
        if (video?.miniatura) {
            miniatura.src = video.miniatura;
            miniatura.hidden = false;
        } else {
            miniatura.removeAttribute('src');
            miniatura.hidden = true;
        }

        enVideo('[data-video-listo]').hidden = !video || Boolean(subida);
        enVideo('[data-video-detalle]').textContent = video ? (video.duracion_texto ? formatear(d.videoDuracion, video.nombre, video.duracion_texto) : video.nombre) : '';

        enVideo('[data-video-subiendo]').hidden = !subida;
        if (subida) {
            enVideo('[data-video-progreso-texto]').textContent = textoSubida(subida);
            const barra = enVideo('[data-video-progreso]');
            barra.value = subida.porcentaje;
            barra.textContent = `${subida.porcentaje} %`;
        }
        enVideo('[data-video-botones]').hidden = Boolean(subida) || (Boolean(video) && !actual.cambiandoVideo);
        enVideo('[data-video-acciones]').hidden = Boolean(subida) || !video || actual.cambiandoVideo;
        actualizarGuardar();
    };

    const limpiarAvisosVideo = () => {
        enVideo('[data-video-aviso]').textContent = '';
        enVideo('[data-video-recomendacion]').textContent = '';
        formLeccion.querySelector('[data-error-de="video"]').textContent = '';
    };

    /** Si el foco quedó en algo que se ocultó, lo pasa a `destino`. */
    const reenfocarSiOculto = (destino) => {
        requestAnimationFrame(() => {
            const activo = document.activeElement;
            if (!activo || activo === document.body || !activo.checkVisibility?.() || activo.closest('[hidden]')) destino?.focus();
        });
    };

    const quitarSubida = (sesion, subida) => {
        sesion.subidas = sesion.subidas.filter((s) => s !== subida);
    };

    const subirVideo = (archivo) => {
        if (!actual) return;
        limpiarAvisosVideo();
        if (!VIDEOS.includes(extension(archivo.name))) {
            formLeccion.querySelector('[data-error-de="video"]').textContent = d.errorVideo;
            return;
        }
        const sesion = actual;
        sesion.subidas.filter((s) => s.uso === 'video').forEach((s) => s.cancelar());
        const subida = { uso: 'video', nombre: archivo.name, porcentaje: 0, esperando: filaSubidas.ocupada(), cancelada: false };
        const tarea = filaSubidas.agregar(
            archivo,
            {
                uso: 'video',
                alProgreso: (n) => {
                    subida.porcentaje = n;
                    if (actual === sesion) pintarVideo();
                },
                alAviso: (texto) => {
                    if (actual === sesion) enVideo('[data-video-aviso]').textContent = texto;
                },
                alRecomendacion: (texto) => {
                    if (actual === sesion) enVideo('[data-video-recomendacion]').textContent = texto;
                },
            },
            {
                alEmpezar: () => {
                    subida.esperando = false;
                    if (actual === sesion) pintarVideo();
                },
            },
        );
        subida.cancelar = () => {
            subida.cancelada = true;
            tarea.cancelar();
        };
        sesion.subidas.push(subida);
        pintarVideo();
        requestAnimationFrame(() => enVideo('[data-video-cancelar]')?.focus());

        tarea.promesa
            .then((datos) => {
                sesion.video = { id: Number(datos.id), nombre: String(datos.nombre ?? archivo.name), duracion_texto: datos.duracion_texto || '', miniatura: datos.portada_url || '' };
                sesion.cambiandoVideo = false;
            })
            .catch((error) => {
                if (subida.cancelada || actual !== sesion) return;
                const texto = error?.message && error.message !== textoCorte() ? error.message : d.errorCorte;
                formLeccion.querySelector('[data-error-de="video"]').textContent = texto;
            })
            .finally(() => {
                quitarSubida(sesion, subida);
                if (actual !== sesion) return;
                pintarVideo();
                reenfocarSiOculto(enVideo(sesion.video ? '[data-video-cambiar]' : '[data-video-elegir]'));
            });
    };

    entradaVideo?.addEventListener('change', () => {
        const archivo = entradaVideo.files?.[0];
        entradaVideo.value = '';
        if (archivo) subirVideo(archivo);
    });

    enVideo('[data-video-cancelar]')?.addEventListener('click', () => {
        actual?.subidas.filter((s) => s.uso === 'video').forEach((s) => s.cancelar());
    });

    enVideo('[data-video-cambiar]')?.addEventListener('click', () => {
        if (!actual) return;
        actual.cambiandoVideo = true;
        pintarVideo();
        enVideo('[data-video-elegir]')?.focus();
    });

    enVideo('[data-video-quitar]')?.addEventListener('click', () => {
        if (!actual) return;
        actual.video = null; // Solo se desvincula: sigue en "Archivos privados".
        actual.cambiandoVideo = false;
        limpiarAvisosVideo();
        pintarVideo();
        enVideo('[data-video-elegir]')?.focus();
    });

    // ----- Descargables -----

    const icono = (datos) => {
        const img = document.createElement('img');
        img.className = 'size-5 shrink-0';
        img.alt = '';
        img.width = 20;
        img.height = 20;
        img.src = iconos[iconoMaterial(datos)] || '';
        return img;
    };

    const botonChip = (etiqueta, alClic) => {
        const boton = document.createElement('button');
        boton.type = 'button';
        boton.className = 'flex size-7 cursor-pointer items-center justify-center rounded-full hover:bg-neutro focus-visible:outline-2 focus-visible:outline-offset-1 focus-visible:outline-marca';
        boton.setAttribute('aria-label', etiqueta);
        boton.innerHTML = '<svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true" focusable="false"><path d="M6 6l12 12M18 6L6 18"/></svg>';
        boton.addEventListener('click', alClic);
        return boton;
    };

    const claseChip = 'inline-flex max-w-full items-center gap-2 rounded-full border border-neutro bg-fondo py-2 pl-3 pr-2 text-cuerpo-s text-marca';

    /** Tras quitar un chip, el foco va al chip que quedó en su lugar o a "Subir archivo". */
    const enfocarChip = (indice) => {
        const botones = listaMateriales.querySelectorAll('button');
        (botones[Math.min(indice, botones.length - 1)] || entradaMaterial)?.focus();
    };

    const pintarMateriales = () => {
        if (!actual || !listaMateriales) return;
        listaMateriales.replaceChildren();
        const visibles = [...actual.materiales.filter((material) => !actual.quitar.has(material.id)), ...actual.nuevos];
        visibles.forEach((material) => {
            const li = document.createElement('li');
            li.className = claseChip;
            li.dataset.materialId = String(material.id);
            const texto = document.createElement('span');
            texto.className = 'truncate';
            texto.textContent = material.nombre;
            const quitar = botonChip(formatear(d.quitar, material.nombre), () => {
                const indice = [...listaMateriales.children].indexOf(li);
                if (actual.nuevos.includes(material)) actual.nuevos = actual.nuevos.filter((m) => m !== material);
                else actual.quitar.add(material.id); // Solo se desvincula al guardar: sigue en "Archivos privados".
                pintarMateriales();
                enfocarChip(indice);
            });
            li.append(icono(material), texto, quitar);
            listaMateriales.append(li);
        });
        actual.subidas
            .filter((subida) => subida.uso === 'material')
            .forEach((subida) => {
                const li = document.createElement('li');
                li.className = `${claseChip} opacity-80`;
                const texto = document.createElement('span');
                texto.className = 'truncate';
                texto.textContent = textoSubida(subida);
                subida.texto = texto;
                const cancelar = botonChip(formatear(d.cancelarSubida, subida.nombre), () => {
                    const indice = [...listaMateriales.children].indexOf(li);
                    subida.cancelar();
                    requestAnimationFrame(() => enfocarChip(indice));
                });
                li.append(icono({ extension: extension(subida.nombre) }), texto, cancelar);
                listaMateriales.append(li);
            });
        actualizarGuardar();
    };

    const yaEsta = (id) => actual.nuevos.some((m) => m.id === id) || actual.materiales.some((m) => m.id === id && !actual.quitar.has(id));

    /** Suma un material a la lección (se vincula al guardar). Devuelve false si ya estaba. */
    const sumarMaterial = (material) => {
        if (actual.quitar.has(material.id)) {
            actual.quitar.delete(material.id);
            return true;
        }
        if (yaEsta(material.id)) return false;
        actual.nuevos.push(material);
        return true;
    };

    const subirMaterial = (archivo) => {
        const sesion = actual;
        const zonaError = formLeccion.querySelector('[data-error-de="materiales"]');
        if (!MATERIALES.includes(extension(archivo.name))) {
            zonaError.textContent = [zonaError.textContent, `${archivo.name}: ${d.errorMaterial}`].filter(Boolean).join(' ');
            return;
        }
        const subida = { uso: 'material', nombre: archivo.name, porcentaje: 0, esperando: filaSubidas.ocupada(), cancelada: false, texto: null };
        const refrescar = () => {
            if (subida.texto) subida.texto.textContent = textoSubida(subida);
        };
        const tarea = filaSubidas.agregar(
            archivo,
            {
                uso: 'material',
                alProgreso: (n) => {
                    subida.porcentaje = n;
                    refrescar();
                },
                alAviso: (texto) => {
                    if (actual !== sesion) return;
                    const aviso = formLeccion.querySelector('[data-materiales-aviso]');
                    aviso.textContent = [aviso.textContent, `${archivo.name}: ${texto}`].filter(Boolean).join(' ');
                },
            },
            {
                alEmpezar: () => {
                    subida.esperando = false;
                    refrescar();
                },
            },
        );
        subida.cancelar = () => {
            subida.cancelada = true;
            tarea.cancelar();
        };
        sesion.subidas.push(subida);

        tarea.promesa
            .then((datos) => {
                sumarMaterial(comoMaterial(datos));
            })
            .catch((error) => {
                if (subida.cancelada || actual !== sesion) return;
                zonaError.textContent = [zonaError.textContent, `${archivo.name}: ${error?.message || d.errorCorte}`].filter(Boolean).join(' ');
            })
            .finally(() => {
                quitarSubida(sesion, subida);
                if (actual !== sesion) return;
                pintarMateriales();
                reenfocarSiOculto(entradaMaterial);
            });
    };

    entradaMaterial?.addEventListener('change', () => {
        const archivos = [...(entradaMaterial.files || [])];
        entradaMaterial.value = '';
        if (!actual) return;
        formLeccion.querySelector('[data-error-de="materiales"]').textContent = '';
        formLeccion.querySelector('[data-materiales-aviso]').textContent = '';
        archivos.forEach(subirMaterial);
        pintarMateriales();
    });

    // ----- Vistas del mismo modal: "Elegir de mis archivos" y "¿Descartar los cambios?" -----

    let modoElegir = 'video';
    let volverA = null;

    const mostrarVista = (vista) => {
        // vista: 'leccion' | 'archivos' | 'descartar'
        formLeccion.querySelector('[data-leccion-contenido]')?.toggleAttribute('hidden', vista !== 'leccion');
        formLeccion.querySelector('[data-leccion-pie]')?.toggleAttribute('hidden', vista !== 'leccion');
        if (vistaArchivos) vistaArchivos.hidden = vista !== 'archivos';
        const descartar = formLeccion.querySelector('[data-leccion-descartar]');
        if (descartar) descartar.hidden = vista !== 'descartar';
    };
    const descartando = () => Boolean(formLeccion?.querySelector('[data-leccion-descartar]:not([hidden])'));
    const eligiendo = () => Boolean(vistaArchivos && !vistaArchivos.hidden);

    const listaLeccion = vistaArchivos
        ? crearLista(vistaArchivos.querySelector('[data-lista-archivos]'), {
              pedir,
              iconos,
              alElegir: (archivo) => elegirArchivo(archivo),
              alSubir: () => {
                  mostrarVista('leccion');
                  (modoElegir === 'video' ? entradaVideo : entradaMaterial)?.click();
              },
          })
        : null;

    const abrirArchivos = (modo, opener) => {
        if (!actual || !listaLeccion) return;
        modoElegir = modo;
        volverA = opener;
        mostrarVista('archivos');
        listaLeccion.abrir(modo === 'video' ? 'video' : 'material');
    };

    const volverDeArchivos = () => {
        mostrarVista('leccion');
        volverA?.focus();
    };

    function elegirArchivo(archivo) {
        if (!actual) return;
        if (modoElegir === 'video') {
            limpiarAvisosVideo();
            actual.video = { id: Number(archivo.id), nombre: String(archivo.nombre ?? ''), duracion_texto: archivo.duracion_texto || '', miniatura: archivo.portada_url || '' };
            actual.cambiandoVideo = false;
            mostrarVista('leccion');
            pintarVideo();
            requestAnimationFrame(() => enVideo('[data-video-cambiar]')?.focus());
            return;
        }
        const material = comoMaterial(archivo);
        const zonaError = formLeccion.querySelector('[data-error-de="materiales"]');
        zonaError.textContent = sumarMaterial(material) ? '' : d.yaEsta;
        mostrarVista('leccion');
        pintarMateriales();
        requestAnimationFrame(() => {
            (listaMateriales.querySelector(`[data-material-id="${material.id}"] button`) || formLeccion.querySelector('[data-materiales-elegir]'))?.focus();
        });
    }

    // Escape en "Elegir de mis archivos" vuelve a la lección. Se toma en keydown: si se deja al `cancel` del
    // <dialog>, el navegador puede cerrarlo igual (sin un clic de por medio, ese cancel no se puede frenar).
    vistaArchivos?.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape') return;
        event.preventDefault();
        event.stopPropagation();
        volverDeArchivos();
    });

    enVideo('[data-video-elegir]')?.addEventListener('click', (event) => abrirArchivos('video', event.currentTarget));
    formLeccion?.querySelector('[data-materiales-elegir]')?.addEventListener('click', (event) => abrirArchivos('material', event.currentTarget));
    formLeccion?.querySelector('[data-leccion-volver]')?.addEventListener('click', volverDeArchivos);

    // ----- Abrir y cerrar -----

    const abrirLeccion = (modulo, li, opener) => {
        if (!formLeccion) return;
        let datos = { titulo: '', video: 0, video_nombre: '', contenido: '', vista_previa: false, materiales: [], miniatura: '', duracion_texto: '' };
        if (li) {
            try {
                datos = { ...datos, ...JSON.parse(li.dataset.leccion || '{}') };
            } catch {
                /* Se abre con lo que haya en la fila. */
            }
        }
        const video = Number(datos.video) > 0 ? { id: Number(datos.video), nombre: String(datos.video_nombre || ''), duracion_texto: datos.duracion_texto || '', miniatura: datos.miniatura || '' } : null;
        actual = { modulo, li, video, cambiandoVideo: false, materiales: [...(datos.materiales || [])], quitar: new Set(), nuevos: [], subidas: [] };

        const lecciones = leccionesDe(modulo);
        const numero = li ? lecciones.indexOf(li) + 1 : lecciones.length + 1;
        document.getElementById('editar-leccion-title').textContent = li ? d.tituloEditar : d.tituloNueva;
        const ante = document.getElementById('editar-leccion-description');
        if (ante) ante.textContent = formatear(d.antetitulo, modulos().indexOf(modulo) + 1, numero);

        limpiarErrores(formLeccion);
        errorLeccion.textContent = '';
        limpiarAvisosVideo();
        formLeccion.querySelector('[data-materiales-aviso]').textContent = '';
        formLeccion.titulo.value = datos.titulo || '';
        formLeccion.vista_previa.checked = Boolean(datos.vista_previa);
        pintarVideo();

        if (!editor) editor = crearEditor(formLeccion.querySelector('[data-editor]'), { html: datos.contenido || '' });
        else editor.setContent(datos.contenido || '');

        pintarMateriales();
        firmaInicial = firma();
        mostrarVista('leccion');
        openModal(dialogLeccion, opener);
        formLeccion.titulo.focus();
    };

    const cerrarLeccion = () => {
        // Lo que se estaba subiendo se cancela; lo ya subido queda sin usar en "Archivos privados".
        actual?.subidas.forEach((subida) => subida.cancelar());
        actual = null;
        mostrarVista('leccion');
        closeModal(dialogLeccion);
    };

    const seguirEditando = () => {
        mostrarVista('leccion');
        formLeccion?.querySelector('[data-leccion-cancelar]')?.focus();
    };

    const intentarCerrarLeccion = () => {
        if (hayCambios()) {
            mostrarVista('descartar');
            formLeccion?.querySelector('[data-leccion-seguir]')?.focus();
            return false;
        }
        cerrarLeccion();
        return true;
    };

    formLeccion?.querySelector('[data-leccion-cancelar]')?.addEventListener('click', intentarCerrarLeccion);
    formLeccion?.querySelector('[data-leccion-seguir]')?.addEventListener('click', seguirEditando);
    formLeccion?.querySelector('[data-leccion-descartar-confirmar]')?.addEventListener('click', cerrarLeccion);
    // Esc y la ✕ del motor: desde una vista vuelven a la lección; desde la lección preguntan si hay cambios.
    const alCerrarMotor = (event) => {
        if (eligiendo()) {
            event.preventDefault();
            volverDeArchivos();
            return;
        }
        if (descartando()) {
            event.preventDefault();
            seguirEditando();
            return;
        }
        if (!hayCambios()) {
            actual = null;
            return;
        }
        event.preventDefault();
        intentarCerrarLeccion();
    };
    dialogLeccion?.addEventListener('cancel', alCerrarMotor);
    dialogLeccion?.querySelector('.modal__close')?.addEventListener('click', alCerrarMotor);

    // ----- Guardar -----

    formLeccion?.addEventListener('submit', async (event) => {
        event.preventDefault();
        if (!actual || subiendo()) return;
        limpiarErrores(formLeccion);
        errorLeccion.textContent = '';

        const titulo = formLeccion.titulo.value.trim();
        if (!titulo) {
            marcarError(formLeccion, 'titulo', formLeccion.titulo.validationMessage || t.error);
            formLeccion.titulo.focus();
            return;
        }

        const guardar = formLeccion.querySelector('[data-leccion-guardar]');
        guardar.disabled = true;
        guardar.setAttribute('aria-busy', 'true');
        const terminar = () => {
            guardar.removeAttribute('aria-busy');
            guardar.disabled = false;
            actualizarGuardar();
        };

        const sesion = actual;
        const { modulo } = sesion;
        let { li } = sesion;
        const cuerpo = {
            titulo,
            video: sesion.video ? String(sesion.video.id) : '',
            contenido: editor?.getHTML() ?? '',
            vista_previa: formLeccion.vista_previa.checked,
        };
        if (!li) cuerpo.modulo = modulo.dataset.id;

        let item;
        try {
            item = await pedir('POST', li ? `/lecciones/${li.dataset.id}` : `/cursos/${cursoId}/lecciones`, cuerpo);
        } catch (error) {
            mostrarErrores(formLeccion, error, errorLeccion);
            terminar();
            return;
        }

        // Los descargables se desvinculan y se vinculan después de guardar (en una nueva, recién ahí existe).
        const fallos = [];
        const quitados = new Set();
        for (const id of sesion.quitar) {
            try {
                await pedir('DELETE', `/lecciones/${item.id}/materiales/${id}`);
                quitados.add(id);
            } catch (error) {
                fallos.push(error?.message || t.error);
            }
        }
        const vinculados = [];
        const pendientes = [];
        for (const material of sesion.nuevos) {
            try {
                vinculados.push(await pedir('POST', `/lecciones/${item.id}/materiales`, { archivo_id: material.id }));
            } catch (error) {
                pendientes.push(material);
                fallos.push(`${material.nombre}: ${error?.message || t.error}`);
            }
        }
        item.materiales = [...(item.materiales || []).filter((m) => !quitados.has(m.id)), ...vinculados];

        if (!li) {
            li = plantilla('leccion');
            listaDe(modulo).append(li);
            abrirModulo(modulo);
        }
        rellenarItem(li, item);
        renumerar();
        terminar();

        if (fallos.length) {
            // La lección quedó guardada; solo falló algún descargable: el modal sigue abierto con lo pendiente.
            actual = { ...sesion, li, materiales: item.materiales, nuevos: pendientes, quitar: new Set([...sesion.quitar].filter((id) => !quitados.has(id))), subidas: [] };
            document.getElementById('editar-leccion-title').textContent = d.tituloEditar;
            pintarMateriales();
            errorLeccion.textContent = fallos.join(' ');
            firmaInicial = '';
            return;
        }

        actual = null;
        closeModal(dialogLeccion);
        anunciar(t.leccionGuardada);
        requestAnimationFrame(() => li.querySelector('[data-item-editar]')?.focus());
    });

    // ---------- Clics ----------

    raiz.addEventListener('click', async (event) => {
        const boton = event.target.closest('button');
        if (!boton || !raiz.contains(boton) || boton.disabled) return;
        const modulo = boton.closest('[data-modulo]');
        const li = boton.closest('[data-item]');

        if (boton.matches('[data-modulo-plegar]')) {
            const abierto = boton.getAttribute('aria-expanded') === 'true';
            boton.setAttribute('aria-expanded', abierto ? 'false' : 'true');
            modulo.querySelector('[data-modulo-panel]').hidden = abierto;
            return;
        }

        if (boton.matches('[data-mover]')) {
            const foto = capturar();
            if (moverItem(li, boton.dataset.mover)) {
                renumerar();
                reenfocar(boton, li.querySelector(`[data-mover="${boton.dataset.mover === 'arriba' ? 'abajo' : 'arriba'}"]`));
                guardarOrden(foto);
            }
            return;
        }

        if (boton.matches('[data-mover-modulo]')) {
            const foto = capturar();
            if (moverModulo(modulo, boton.dataset.moverModulo)) {
                renumerar();
                reenfocar(boton, modulo.querySelector(`[data-mover-modulo="${boton.dataset.moverModulo === 'arriba' ? 'abajo' : 'arriba'}"]`));
                guardarOrden(foto);
            }
            return;
        }

        if (boton.matches('[data-modulo-editar]')) {
            abrirEditarModulo(modulo, boton);
            return;
        }
        if (boton.matches('[data-modulo-eliminar]')) {
            eliminarModulo(modulo, boton);
            return;
        }
        if (boton.matches('[data-item-editar]')) {
            abrirLeccion(modulo, li, boton);
            return;
        }
        if (boton.matches('[data-item-eliminar]')) {
            eliminarItem(li, boton);
            return;
        }
        if (boton.matches('[data-agregar-leccion]')) {
            abrirLeccion(modulo, null, boton);
            return;
        }
        if (boton.matches('[data-agregar-descargable]')) {
            const menu = boton.closest('[data-descargable-menu]');
            const abierto = boton.getAttribute('aria-expanded') === 'true';
            cerrarMenus(menu);
            boton.setAttribute('aria-expanded', abierto ? 'false' : 'true');
            menu.querySelector('[data-descargable-opciones]').hidden = abierto;
            if (!abierto) menu.querySelector('[data-descargable-subir]')?.focus();
            return;
        }
        if (boton.matches('[data-descargable-elegir]')) {
            cerrarMenus();
            abrirElegirDescargable(modulo, modulo.querySelector('[data-agregar-descargable]'));
            return;
        }

        if (boton.matches('[data-agregar-modulo]')) {
            boton.disabled = true;
            try {
                const nuevo = await pedir('POST', `/cursos/${cursoId}/modulos`);
                const fila = plantilla('modulo');
                fila.dataset.id = nuevo.id;
                fila.dataset.titulo = nuevo.titulo;
                lista.append(fila);
                renumerar();
                limpiarError();
                abrirEditarModulo(fila, fila.querySelector('[data-modulo-editar]'));
            } catch (error) {
                mostrarError(error);
            } finally {
                boton.disabled = false;
            }
        }
    });

    // ---------- "+ Agregar descargable" de cada módulo ----------
    // Un botón con dos opciones: subir un archivo (a "Archivos privados") o elegir uno ya subido (modal
    // elegir-archivo). En los dos casos se vincula al módulo con POST cursos/<id>/materiales {archivo_id, modulo}.

    const dialogElegir = document.getElementById('elegir-archivo');
    const errorElegir = dialogElegir?.querySelector('[data-elegir-error]');
    let moduloElegir = null;
    let openerElegir = null;

    const cerrarMenus = (excepto = null) => {
        raiz.querySelectorAll('[data-descargable-menu]').forEach((menu) => {
            if (menu === excepto) return;
            menu.querySelector('[data-agregar-descargable]')?.setAttribute('aria-expanded', 'false');
            const opciones = menu.querySelector('[data-descargable-opciones]');
            if (opciones) opciones.hidden = true;
        });
    };

    const vincularDescargable = async (modulo, archivoId) => {
        const material = await pedir('POST', `/cursos/${cursoId}/materiales`, { archivo_id: archivoId, modulo: modulo.dataset.id });
        const fila = plantilla('material');
        rellenarItem(fila, { tipo: 'material', ...material });
        listaDe(modulo).append(fila);
        abrirModulo(modulo);
        renumerar();
        limpiarError();
        anunciar(t.descargableAgregado);
        return fila;
    };

    const listaElegir = dialogElegir
        ? crearLista(dialogElegir.querySelector('[data-lista-archivos]'), {
              pedir,
              iconos,
              alElegir: async (archivo, boton) => {
                  if (!moduloElegir) return;
                  errorElegir.textContent = '';
                  boton.disabled = true;
                  try {
                      const fila = await vincularDescargable(moduloElegir, Number(archivo.id));
                      closeModal(dialogElegir);
                      requestAnimationFrame(() => fila.querySelector('[data-item-eliminar]')?.focus());
                  } catch (error) {
                      errorElegir.textContent = error?.message || t.error;
                      boton.disabled = false;
                  }
              },
              alSubir: () => {
                  const modulo = moduloElegir;
                  closeModal(dialogElegir);
                  modulo?.querySelector('[data-descargable-subir]')?.click();
              },
          })
        : null;

    const abrirElegirDescargable = (modulo, opener) => {
        if (!dialogElegir || !listaElegir) return;
        moduloElegir = modulo;
        openerElegir = opener;
        errorElegir.textContent = '';
        openModal(dialogElegir, openerElegir);
        listaElegir.abrir('material');
    };

    const subirDescargable = (modulo, archivo) => {
        const zona = modulo.querySelector('[data-descargable-subiendo]');
        const texto = modulo.querySelector('[data-descargable-texto]');
        const barra = modulo.querySelector('[data-descargable-progreso]');
        const aviso = modulo.querySelector('[data-descargable-aviso]');
        const boton = modulo.querySelector('[data-agregar-descargable]');
        aviso.textContent = '';
        if (!MATERIALES.includes(extension(archivo.name))) {
            aviso.textContent = `${archivo.name}: ${t.errorMaterial}`;
            return;
        }
        if (modulo.dataset.subiendo) return; // Uno por módulo a la vez (y la cola, uno en toda la página).

        const subida = { nombre: archivo.name, porcentaje: 0, esperando: filaSubidas.ocupada(), cancelada: false };
        const pintar = () => {
            texto.textContent = subida.esperando ? formatear(t.enEspera, subida.nombre) : formatear(t.subiendo, subida.nombre, subida.porcentaje);
            barra.value = subida.porcentaje;
            barra.textContent = `${subida.porcentaje} %`;
        };
        const tarea = filaSubidas.agregar(
            archivo,
            {
                uso: 'material',
                alProgreso: (n) => {
                    subida.porcentaje = n;
                    pintar();
                },
                alAviso: (txt) => {
                    aviso.textContent = `${archivo.name}: ${txt}`;
                },
            },
            {
                alEmpezar: () => {
                    subida.esperando = false;
                    pintar();
                },
            },
        );
        const cancelar = zona.querySelector('[data-descargable-cancelar]');
        const alCancelar = () => {
            subida.cancelada = true;
            tarea.cancelar();
        };
        cancelar.addEventListener('click', alCancelar, { once: true });

        modulo.dataset.subiendo = '1';
        boton.disabled = true;
        zona.hidden = false;
        pintar();
        cancelar.focus();

        tarea.promesa
            .then((datos) => vincularDescargable(modulo, Number(datos.id)))
            .then((fila) => {
                requestAnimationFrame(() => fila.querySelector('[data-item-eliminar]')?.focus());
            })
            .catch((error) => {
                if (subida.cancelada) {
                    requestAnimationFrame(() => boton.focus());
                    return;
                }
                aviso.textContent = `${archivo.name}: ${error?.message || t.error}`;
                requestAnimationFrame(() => boton.focus());
            })
            .finally(() => {
                cancelar.removeEventListener('click', alCancelar);
                delete modulo.dataset.subiendo;
                boton.disabled = false;
                zona.hidden = true;
            });
    };

    raiz.addEventListener('change', (event) => {
        const entrada = event.target.closest('[data-descargable-subir]');
        if (!entrada) return;
        const archivo = entrada.files?.[0];
        entrada.value = '';
        const modulo = entrada.closest('[data-modulo]');
        cerrarMenus();
        if (archivo && modulo) subirDescargable(modulo, archivo);
    });

    // Escape cierra el menú abierto y devuelve el foco a su botón; un clic afuera lo cierra.
    raiz.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape') return;
        const menu = event.target.closest?.('[data-descargable-menu]');
        if (!menu || menu.querySelector('[data-descargable-opciones]').hidden) return;
        event.preventDefault();
        cerrarMenus();
        menu.querySelector('[data-agregar-descargable]').focus();
    });
    document.addEventListener('click', (event) => {
        if (!event.target.closest?.('[data-descargable-menu]')) cerrarMenus();
    });

    renumerar();
}
