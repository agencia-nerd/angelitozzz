/*
 * Editor liviano de la descripción de una lección (specs/admin-constructor.md → modal-leccion): Tiptap
 * con solo Negrita, Cursiva, Lista y Enlace, para que lo que se escribe quepa en la lista permitida
 * del plugin (que además filtra el HTML con wp_kses al guardar).
 *
 * Marcado que espera (template-parts/modals/editar-leccion.php):
 *   [data-editor] (data-etiqueta-id, data-ayuda-id, data-enlace-invalido)
 *     [role="toolbar"] con botones [data-editor-accion="negrita|cursiva|lista|enlace"] y aria-pressed
 *     [data-editor-enlace] fila del enlace (sin ventanas nativas): [data-editor-enlace-campo],
 *       [data-editor-enlace-aplicar], [data-editor-enlace-quitar], [data-editor-enlace-cancelar] y
 *       [data-editor-enlace-error] (role="alert"). Enter aplica; Escape cierra solo la fila, no el modal.
 *     [data-editor-area] donde se monta el editor
 */

import { Editor } from '@tiptap/core';
import Document from '@tiptap/extension-document';
import Paragraph from '@tiptap/extension-paragraph';
import Text from '@tiptap/extension-text';
import Bold from '@tiptap/extension-bold';
import Italic from '@tiptap/extension-italic';
import { BulletList, ListItem } from '@tiptap/extension-list';
import Link from '@tiptap/extension-link';

const PROTOCOLOS = /^(https?:\/\/|mailto:)/i;

/**
 * Monta el editor en `elemento` ([data-editor]).
 *
 * @param {HTMLElement} elemento
 * @param {{html?: string, etiqueta?: string}} opciones `etiqueta`: nombre accesible si no hay data-etiqueta-id.
 * @returns {{getHTML: () => string, setContent: (html: string) => void, estaVacio: () => boolean, destroy: () => void, alCambiar: (fn: () => void) => void}}
 */
export function crearEditor(elemento, { html = '', etiqueta = '' } = {}) {
    const area = elemento.querySelector('[data-editor-area]');
    const botones = [...elemento.querySelectorAll('[data-editor-accion]')];
    const oyentes = new Set();

    const atributos = {
        role: 'textbox',
        'aria-multiline': 'true',
        class: 'min-h-[140px] max-h-[320px] overflow-y-auto px-[18px] py-4 text-cuerpo text-tinta outline-none [&_p]:mb-3 [&_p:last-child]:mb-0 [&_ul]:mb-3 [&_ul]:list-disc [&_ul]:pl-6 [&_li_p]:mb-1 [&_a]:text-acento [&_a]:underline [&_strong]:font-semibold',
    };
    if (elemento.dataset.etiquetaId) atributos['aria-labelledby'] = elemento.dataset.etiquetaId;
    else if (etiqueta) atributos['aria-label'] = etiqueta;
    if (elemento.dataset.ayudaId) atributos['aria-describedby'] = elemento.dataset.ayudaId;
    if (area.id) {
        // El id queda en el contenedor; el textbox real es el contenteditable.
        atributos.id = area.id + '-texto';
    }

    const editor = new Editor({
        element: area,
        extensions: [
            Document,
            Paragraph,
            Text,
            Bold,
            Italic,
            BulletList,
            ListItem,
            Link.configure({
                openOnClick: false,
                autolink: true,
                defaultProtocol: 'https',
                protocols: ['http', 'https', 'mailto'],
                HTMLAttributes: { target: '_blank', rel: 'noopener noreferrer' },
            }),
        ],
        content: html || '',
        editorProps: { attributes: atributos },
    });

    const estado = {
        negrita: () => editor.isActive('bold'),
        cursiva: () => editor.isActive('italic'),
        lista: () => editor.isActive('bulletList'),
        enlace: () => editor.isActive('link'),
    };

    const pintarBarra = () => {
        botones.forEach((boton) => {
            const activo = estado[boton.dataset.editorAccion]?.() ?? false;
            boton.setAttribute('aria-pressed', activo ? 'true' : 'false');
        });
    };

    // ---------- Enlace: una fila bajo la barra, en vez de window.prompt / window.alert ----------

    const panel = elemento.querySelector('[data-editor-enlace]');
    const campoEnlace = panel?.querySelector('[data-editor-enlace-campo]');
    const errorEnlace = panel?.querySelector('[data-editor-enlace-error]');
    const quitarEnlace = panel?.querySelector('[data-editor-enlace-quitar]');
    const botonEnlace = elemento.querySelector('[data-editor-accion="enlace"]');

    const marcarError = (texto) => {
        if (errorEnlace) errorEnlace.textContent = texto;
        if (texto) campoEnlace?.setAttribute('aria-invalid', 'true');
        else campoEnlace?.removeAttribute('aria-invalid');
    };

    const cerrarEnlace = ({ alEditor = true } = {}) => {
        if (!panel || panel.hidden) return;
        panel.hidden = true;
        marcarError('');
        botonEnlace?.setAttribute('aria-expanded', 'false');
        if (alEditor) editor.commands.focus();
    };

    const abrirEnlace = () => {
        if (!panel || !campoEnlace) return;
        if (!panel.hidden) {
            cerrarEnlace();
            return;
        }
        // El enlace del texto donde está el cursor; la selección se conserva en el editor mientras se escribe.
        const actual = editor.getAttributes('link').href || '';
        campoEnlace.value = actual;
        if (quitarEnlace) quitarEnlace.hidden = actual === '';
        marcarError('');
        panel.hidden = false;
        botonEnlace?.setAttribute('aria-expanded', 'true');
        campoEnlace.focus();
        campoEnlace.select();
    };

    const aplicarEnlace = () => {
        const url = (campoEnlace?.value || '').trim();
        if (url === '') {
            editor.chain().focus().extendMarkRange('link').unsetLink().run();
            cerrarEnlace({ alEditor: false });
            return;
        }
        if (!PROTOCOLOS.test(url)) {
            marcarError(elemento.dataset.enlaceInvalido || '');
            campoEnlace?.focus();
            return;
        }
        editor.chain().focus().extendMarkRange('link').setLink({ href: url }).run();
        cerrarEnlace({ alEditor: false });
    };

    panel?.querySelector('[data-editor-enlace-aplicar]')?.addEventListener('click', aplicarEnlace);
    panel?.querySelector('[data-editor-enlace-cancelar]')?.addEventListener('click', () => cerrarEnlace());
    quitarEnlace?.addEventListener('click', () => {
        editor.chain().focus().extendMarkRange('link').unsetLink().run();
        cerrarEnlace({ alEditor: false });
    });
    campoEnlace?.addEventListener('input', () => marcarError(''));
    campoEnlace?.addEventListener('keydown', (event) => {
        if (event.key === 'Enter') {
            // No envía el formulario de la lección.
            event.preventDefault();
            aplicarEnlace();
        } else if (event.key === 'Escape') {
            // Cierra solo la fila: sin esto, el <dialog> también se cerraría.
            event.preventDefault();
            event.stopPropagation();
            cerrarEnlace();
        }
    });

    const acciones = {
        negrita: () => editor.chain().focus().toggleBold().run(),
        cursiva: () => editor.chain().focus().toggleItalic().run(),
        lista: () => editor.chain().focus().toggleBulletList().run(),
        enlace: abrirEnlace,
    };

    const alClic = (event) => {
        const boton = event.target.closest('[data-editor-accion]');
        if (!boton || !elemento.contains(boton)) return;
        event.preventDefault();
        acciones[boton.dataset.editorAccion]?.();
        pintarBarra();
    };
    elemento.addEventListener('click', alClic);

    editor.on('selectionUpdate', pintarBarra);
    editor.on('transaction', pintarBarra);
    editor.on('update', () => oyentes.forEach((fn) => fn()));
    pintarBarra();

    return {
        getHTML: () => (editor.isEmpty ? '' : editor.getHTML()),
        setContent: (nuevo) => {
            cerrarEnlace({ alEditor: false });
            editor.commands.setContent(nuevo || '', { emitUpdate: false });
            pintarBarra();
        },
        estaVacio: () => editor.isEmpty,
        destroy: () => {
            elemento.removeEventListener('click', alClic);
            oyentes.clear();
            editor.destroy();
        },
        alCambiar: (fn) => {
            oyentes.add(fn);
        },
        enfocar: () => editor.commands.focus('end'),
    };
}
