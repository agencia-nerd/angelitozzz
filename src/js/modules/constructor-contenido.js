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
 * Cada cambio se guarda al momento por la API del plugin.
 */

import { openModal, closeModal } from './modal.js';
import { crearApi, formatear, pedirConfirmacion, textosEliminar, avisarCambiosSinGuardar } from './admin-comun.js';
import { crearEditor } from './editor-texto.js';

const PDF_MAXIMO = 20 * 1024 * 1024;

const esPdf = (archivo) => archivo && (archivo.type === 'application/pdf' || /\.pdf$/i.test(archivo.name)) && archivo.size <= PDF_MAXIMO;

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
            linea = `${t.descargable} · ${String(datos.tamano ?? '').replace(/^PDF · /u, '')}`;
        } else {
            li.dataset.leccion = JSON.stringify(datos);
            linea = datos.video ? t.videoVimeo + (datos.duracion_texto ? ` · ${datos.duracion_texto}` : '') : t.sinVideo;
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
    const vistaVideo = formLeccion?.querySelector('[data-video-vista]');
    const listaPdf = formLeccion?.querySelector('[data-materiales]');
    let editor = null;
    let actual = null; // { modulo, li, materiales, nuevos: File[], quitar: Set<number> }
    let firmaInicial = '';
    let lecturaVideo = 0;

    const firma = () =>
        actual
            ? JSON.stringify([
                  formLeccion.titulo.value,
                  formLeccion.video.value.trim(),
                  editor?.getHTML() ?? '',
                  formLeccion.vista_previa.checked,
                  actual.nuevos.length,
                  [...actual.quitar],
              ])
            : '';
    const hayCambios = () => Boolean(dialogLeccion?.open && actual && firma() !== firmaInicial);

    avisarCambiosSinGuardar(hayCambios);

    const pintarVideo = (datos) => {
        if (!datos) {
            vistaVideo.hidden = true;
            return;
        }
        const miniatura = vistaVideo.querySelector('[data-video-miniatura]');
        if (datos.miniatura) miniatura.src = datos.miniatura;
        else miniatura.removeAttribute('src');
        miniatura.hidden = !datos.miniatura;
        vistaVideo.querySelector('[data-video-estado]').textContent = formLeccion.dataset.videoConectado;
        vistaVideo.querySelector('[data-video-duracion]').textContent = datos.duracion_texto ? formatear(formLeccion.dataset.videoDuracion, datos.duracion_texto) : '';
        vistaVideo.hidden = false;
    };

    const leerVideo = async () => {
        const url = formLeccion.video.value.trim();
        const zona = formLeccion.querySelector('[data-error-de="video"]');
        const turno = ++lecturaVideo;
        zona.textContent = '';
        formLeccion.video.removeAttribute('aria-invalid');
        if (!url) {
            pintarVideo(null);
            return;
        }
        vistaVideo.hidden = false;
        vistaVideo.querySelector('[data-video-estado]').textContent = formLeccion.dataset.videoLeyendo;
        vistaVideo.querySelector('[data-video-duracion]').textContent = '';
        try {
            const datos = await pedir('GET', '/video', null, { url });
            if (turno === lecturaVideo) pintarVideo(datos);
        } catch (error) {
            if (turno !== lecturaVideo) return;
            pintarVideo(null);
            marcarError(formLeccion, 'video', error?.message || formLeccion.dataset.videoError);
        }
    };

    const pintarMateriales = () => {
        listaPdf.replaceChildren();
        const chip = (nombre, pendiente, alQuitar) => {
            const li = document.createElement('li');
            li.className = 'inline-flex items-center gap-2 rounded-full border border-neutro bg-fondo py-2 pl-4 pr-2 text-cuerpo-s text-marca';
            const texto = document.createElement('span');
            texto.textContent = pendiente ? `${nombre} (${formLeccion.dataset.pendiente})` : nombre;
            const quitar = document.createElement('button');
            quitar.type = 'button';
            quitar.className = 'flex size-7 cursor-pointer items-center justify-center rounded-full hover:bg-neutro focus-visible:outline-2 focus-visible:outline-offset-1 focus-visible:outline-marca';
            quitar.setAttribute('aria-label', formatear(formLeccion.dataset.quitar, nombre));
            quitar.innerHTML = '<svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true" focusable="false"><path d="M6 6l12 12M18 6L6 18"/></svg>';
            quitar.addEventListener('click', () => {
                const indice = [...listaPdf.children].indexOf(li);
                alQuitar();
                pintarMateriales();
                const botones = listaPdf.querySelectorAll('button');
                (botones[Math.min(indice, botones.length - 1)] || formLeccion.querySelector('[data-leccion-pdf]')).focus();
            });
            const icono = document.createElement('span');
            icono.innerHTML = '<svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8l-5-5Z"/><path d="M14 3v5h5"/></svg>';
            li.append(icono.firstElementChild, texto, quitar);
            listaPdf.append(li);
        };
        actual.materiales
            .filter((material) => !actual.quitar.has(material.id))
            .forEach((material) => chip(material.nombre, false, () => actual.quitar.add(material.id)));
        actual.nuevos.forEach((archivo) => chip(archivo.name, true, () => actual.nuevos.splice(actual.nuevos.indexOf(archivo), 1)));
    };

    const abrirLeccion = (modulo, li, opener) => {
        if (!formLeccion) return;
        let datos = { titulo: '', video: '', contenido: '', vista_previa: false, materiales: [], miniatura: '', duracion_texto: '' };
        if (li) {
            try {
                datos = { ...datos, ...JSON.parse(li.dataset.leccion || '{}') };
            } catch {
                /* Se abre con lo que haya en la fila. */
            }
        }
        actual = { modulo, li, materiales: [...(datos.materiales || [])], nuevos: [], quitar: new Set() };

        const lecciones = leccionesDe(modulo);
        const numero = li ? lecciones.indexOf(li) + 1 : lecciones.length + 1;
        document.getElementById('editar-leccion-title').textContent = li ? formLeccion.dataset.tituloEditar : formLeccion.dataset.tituloNueva;
        const ante = document.getElementById('editar-leccion-description');
        if (ante) ante.textContent = formatear(formLeccion.dataset.antetitulo, modulos().indexOf(modulo) + 1, numero);

        limpiarErrores(formLeccion);
        errorLeccion.textContent = '';
        formLeccion.titulo.value = datos.titulo || '';
        formLeccion.video.value = datos.video || '';
        formLeccion.vista_previa.checked = Boolean(datos.vista_previa);
        lecturaVideo++;
        pintarVideo(datos.video ? datos : null);

        if (!editor) editor = crearEditor(formLeccion.querySelector('[data-editor]'), { html: datos.contenido || '' });
        else editor.setContent(datos.contenido || '');

        pintarMateriales();
        firmaInicial = firma();
        openModal(dialogLeccion, opener);
        formLeccion.titulo.focus();
    };

    const intentarCerrarLeccion = () => {
        // eslint-disable-next-line no-alert -- Confirmación simple antes de descartar lo escrito.
        if (hayCambios() && !window.confirm(formLeccion.dataset.descartar)) return false;
        actual = null;
        closeModal(dialogLeccion);
        return true;
    };

    formLeccion?.querySelector('[data-leccion-cancelar]')?.addEventListener('click', intentarCerrarLeccion);
    // Esc y la ✕ del motor también preguntan si hay cambios.
    dialogLeccion?.addEventListener('cancel', (event) => {
        if (!hayCambios()) return;
        event.preventDefault();
        intentarCerrarLeccion();
    });
    dialogLeccion?.querySelector('.modal__close')?.addEventListener('click', (event) => {
        if (!hayCambios()) return;
        event.preventDefault();
        intentarCerrarLeccion();
    });

    formLeccion?.video.addEventListener('change', leerVideo);
    formLeccion?.video.addEventListener('paste', () => setTimeout(leerVideo, 0));

    formLeccion?.querySelector('[data-leccion-pdf]')?.addEventListener('change', (event) => {
        const entrada = event.currentTarget;
        const zona = formLeccion.querySelector('[data-error-de="materiales"]');
        zona.textContent = '';
        [...entrada.files].forEach((archivo) => {
            if (esPdf(archivo)) actual?.nuevos.push(archivo);
            else zona.textContent = formLeccion.dataset.soloPdf;
        });
        entrada.value = '';
        if (actual) pintarMateriales();
    });

    formLeccion?.addEventListener('submit', async (event) => {
        event.preventDefault();
        if (!actual) return;
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

        const { modulo } = actual;
        let { li } = actual;
        const cuerpo = {
            titulo,
            video: formLeccion.video.value.trim(),
            contenido: editor?.getHTML() ?? '',
            vista_previa: formLeccion.vista_previa.checked,
        };
        if (!li) cuerpo.modulo = modulo.dataset.id;

        let item;
        try {
            item = await pedir('POST', li ? `/lecciones/${li.dataset.id}` : `/cursos/${cursoId}/lecciones`, cuerpo);
        } catch (error) {
            mostrarErrores(formLeccion, error, errorLeccion);
            guardar.disabled = false;
            guardar.removeAttribute('aria-busy');
            return;
        }

        // Los PDF se quitan y se suben después de guardar la lección (en una nueva, recién ahí existe).
        const fallos = [];
        const quitados = new Set();
        for (const id of actual.quitar) {
            try {
                await pedir('DELETE', `/lecciones/${item.id}/materiales/${id}`);
                quitados.add(id);
            } catch (error) {
                fallos.push(error?.message || t.error);
            }
        }
        const subidos = [];
        const pendientes = [];
        for (const archivo of actual.nuevos) {
            anunciar(formatear(t.subiendo, archivo.name));
            const datos = new FormData();
            datos.append('archivo', archivo);
            try {
                subidos.push(await pedir('POST', `/lecciones/${item.id}/materiales`, datos));
            } catch (error) {
                pendientes.push(archivo);
                fallos.push(`${archivo.name}: ${error?.message || t.error}`);
            }
        }
        item.materiales = [...(item.materiales || []).filter((m) => !quitados.has(m.id)), ...subidos];

        if (!li) {
            li = plantilla('leccion');
            listaDe(modulo).append(li);
            abrirModulo(modulo);
        }
        rellenarItem(li, item);
        renumerar();

        guardar.disabled = false;
        guardar.removeAttribute('aria-busy');

        if (fallos.length) {
            // La lección quedó guardada; solo falló algún PDF: el modal sigue abierto con lo pendiente.
            actual = { modulo, li, materiales: item.materiales, nuevos: pendientes, quitar: new Set([...actual.quitar].filter((id) => !quitados.has(id))) };
            document.getElementById('editar-leccion-title').textContent = formLeccion.dataset.tituloEditar;
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

    // "+ Agregar descargable" de cada módulo: PDF del curso en ese módulo.
    raiz.addEventListener('change', async (event) => {
        const entrada = event.target.closest('[data-agregar-descargable]');
        if (!entrada) return;
        const archivo = entrada.files?.[0];
        entrada.value = '';
        const modulo = entrada.closest('[data-modulo]');
        if (!archivo || !modulo) return;
        if (!esPdf(archivo)) {
            mostrarError({ message: formLeccion?.dataset.soloPdf });
            return;
        }
        anunciar(formatear(t.subiendo, archivo.name));
        const datos = new FormData();
        datos.append('archivo', archivo);
        datos.append('modulo', modulo.dataset.id);
        try {
            const material = await pedir('POST', `/cursos/${cursoId}/materiales`, datos);
            const fila = plantilla('material');
            rellenarItem(fila, material);
            listaDe(modulo).append(fila);
            renumerar();
            limpiarError();
            anunciar(t.descargableAgregado);
        } catch (error) {
            mostrarError(error);
        }
    });

    renumerar();
}
