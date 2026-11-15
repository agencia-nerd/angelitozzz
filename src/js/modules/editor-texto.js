/*
 * Editor liviano de la descripción de una lección (specs/admin-constructor.md → modal-leccion): Tiptap
 * con solo Negrita, Cursiva, Lista y Enlace, para que lo que se escribe quepa en la lista permitida
 * del plugin (que además filtra el HTML con wp_kses al guardar).
 *
 * Marcado que espera (template-parts/modals/editar-leccion.php):
 *   [data-editor] (data-etiqueta-id, data-ayuda-id, data-pedir-enlace, data-enlace-invalido)
 *     [role="toolbar"] con botones [data-editor-accion="negrita|cursiva|lista|enlace"] y aria-pressed
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

    const pedirEnlace = () => {
        const actual = editor.getAttributes('link').href || '';
        // eslint-disable-next-line no-alert -- Pedido simple y accesible; el spec deja elegir campo propio o prompt.
        const respuesta = window.prompt(elemento.dataset.pedirEnlace || 'URL', actual);
        if (respuesta === null) return;
        const url = respuesta.trim();
        const cadena = editor.chain().focus().extendMarkRange('link');
        if (url === '') {
            cadena.unsetLink().run();
            return;
        }
        if (!PROTOCOLOS.test(url)) {
            // eslint-disable-next-line no-alert
            window.alert(elemento.dataset.enlaceInvalido || '');
            return;
        }
        cadena.setLink({ href: url }).run();
    };

    const acciones = {
        negrita: () => editor.chain().focus().toggleBold().run(),
        cursiva: () => editor.chain().focus().toggleItalic().run(),
        lista: () => editor.chain().focus().toggleBulletList().run(),
        enlace: pedirEnlace,
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
