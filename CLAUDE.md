# Angelitozzz — tema WordPress

Stack: WordPress (Local WP) + Vite + Tailwind + pnpm.

## Paquetes (pnpm)
- Usa solo `pnpm`. Nunca `npm`, `yarn` ni `npx`; para ejecutar un paquete usa `pnpm dlx`.
- No bajes `minimumReleaseAge` en `pnpm-workspace.yaml`.
- No apruebes `allowBuilds` sin revisar antes el paquete y avisarme.

## PHP
- Empieza el nombre de toda función, hook y constante con el prefijo `angelitozzz_`. El text domain es `angelitozzz`.
- En `functions.php` solo escribe `require_once` de archivos de `inc/`.
- Pon el código nuevo en un archivo nuevo de `inc/`, y agrégalo a `functions.php`.
- Empieza todo archivo de `inc/` con `defined('ABSPATH') || exit;`.
- Escapa toda salida con `esc_html`, `esc_url` o `esc_attr`.
- No crees `acf.php`, `acf-json/` ni field groups por iniciativa propia. Cómo se crean (admin, ACF JSON o código) lo define el pedido o la skill que lo haga; si no está definido, pregúntame.

## Páginas
- La portada es `front-page.php`: WordPress la usa sola para la página "Inicio" (fijada en Ajustes → Lectura). No tiene `Template Name` ni se elige en el admin. El tema crea "Inicio" al activarse (`inc/crear-inicio.php`).
- Crea cada otra página con diseño propio como `pages/template-<nombre>.php`, con `Template Name:` en la cabecera.
- Llama una sola vez a `get_header()` y a `get_footer()` en cada plantilla, y pon el contenido dentro de `<main id="main-content">`.
- Pon cada sección de una sola página en `template-parts/pages/<página>/<sección>.php` (la portada usa `home/`).
- Pon las piezas que se repiten en 2 o más páginas, o los bloques chicos (card, botón), en `template-parts/components/<nombre>.php`. Si una sección de página pasa a usarse en una segunda, muévela a `components/`.
- Incluye cada pieza con `get_template_part('template-parts/...', null, $args)` y lee `$args` dentro, escapando la salida. Los forms y modals tienen sus propias carpetas (`forms/`, `modals/`).

## Tailwind
- No agregues `@source`: Tailwind detecta las clases solo. Agrégalo únicamente si una ruta no se detecta.

## Comandos
- `pnpm dev`: inicia Vite con HMR (crea `hot` y lo borra al detenerse).
- `pnpm build`: genera `dist/` y `manifest.json`.

## Deploy
- No commitees `dist/` (está en `.gitignore`).
- Para publicar, detén el dev server, corre `pnpm build` y sube `dist/` más los PHP modificados.
- No subas al servidor `hot`, `node_modules/`, `src/` ni archivos de config (`package.json`, `pnpm-*`, `vite.config.ts`, `CLAUDE.md`).

<!-- wp-helper:start -->
## Helpers (wp-helper 1.3.1)
- Los helpers viven en `inc/helpers/` y se cargan con `require_once` desde `functions.php`. No edites los archivos del motor: se actualizan desde la skill wp-helper.
- Un helper propio de este proyecto va en `inc/helpers/<nombre>.php` con el prefijo `angelitozzz_`, y también se carga desde `functions.php`.
- Toda imagen de un campo ACF pasa por `angelitozzz_image($campo, [...])` (devuelve el `<img>` o `''`) o por `angelitozzz_image_data($campo)` (`url`, `alt`, `width`, `height` o `null`). No uses `$campo['url']` ni `wp_get_attachment_image_url()` directo en las vistas. Usa `'loading' => 'eager'` en la imagen principal de la página. Si el cliente no escribe el alt, pasa `'alt_fallback'` con el título de la sección (o el de su tarjeta); `'alt' => ''` solo para imágenes decorativas.
- Un texto con partes de otro color, y todo campo textarea, se imprime con `angelitozzz_highlight($texto)`; ya sale escapado y respeta los saltos de línea. Los marcadores y sus clases están en `inc/helpers/text-styles.php`: para sumar un estilo corre `install.mjs --add-style` de la skill wp-helper, no lo edites a mano.
- Todo enlace de un campo ACF (Link o URL) pasa por `angelitozzz_link($campo, [...])` (el `<a>` completo) o por `<a<?= angelitozzz_link_attrs($campo, [...]) ?>>` cuando el diseño lleva íconos u otros hijos. No escribas `target="_blank"`, `rel` ni `$campo['url']` a mano: el helper pone `rel`, descarta enlaces vacíos o inseguros y avisa de la pestaña nueva (clase `sr-only`).
<!-- wp-helper:end -->

<!-- design-import:start -->
## Specs de diseño (design-import 1.14.0)
- Los specs viven en `specs/` (`_index.md`, `_shared.md` y un archivo por página). Son documentación del tema: no se suben al servidor.
- Aplica solo las páginas con estado `aprobado` en `specs/_index.md` y deja el estado en `aplicado` al terminar.
- Cada página es un grupo de campos ACF (`Grupo ACF` y `Ubicación` en su spec) y cada sección es un campo `group` (`home_hero`). Blog, catálogo y WooCommerce se describen en la tabla "Contenido dinámico" de `_shared.md`: no escribas consultas, filtros ni lógica de Woo hasta ese paso.
- Lo que se repite en todo el sitio (contacto, redes, textos del pie) está en `specs/opciones.md` y va en una página de opciones de ACF; el logo y el ícono son de Personalizar y los menús son menús de WordPress.
- Los campos ACF se nombran `<página>_<sección>_<campo>` en snake_case y en español. No pongas límites de caracteres ni de tamaño de imagen: la descripción del campo solo lo recomienda.
- El texto del diseño queda fijo en la plantilla y su campo está en el spec ("valor actual"): al pasarlo a ACF se reemplaza uno a uno.
- **El spec se corrige junto con el código.** Si una corrección o un cambio toca algo que el spec describe (campos y su tipo, "valor actual", `Destino` de una sección, componentes de `_shared.md` y sus argumentos, `Comportamiento`, tokens), actualiza el spec en el mismo paso; si es un campo, primero el spec y después se genera el JSON. Un ajuste visual o interno que el spec no describe no lo toca. Al terminar, di qué líneas del spec cambiaron. `check.mjs` de design-import avisa si un componente no coincide con `_shared.md`.
<!-- design-import:end -->

<!-- wp-form:start -->
## Formularios (wp-form 1.5.0)
- Cada form son dos archivos: la definición `inc/forms/<id>.php` y la vista `template-parts/forms/<id>.php`. Muéstralo con `get_template_part('template-parts/forms/<id>')`.
- No edites el motor (`inc/forms.php`, `inc/forms-entries.php`, `src/js/modules/form.js`): se actualiza desde la skill wp-form.
- Define los campos, las reglas y los mensajes solo en la definición.
- No escribas a mano `name`, `id`, `required`, `maxlength` ni `aria-*` en los campos: los imprimen los helpers `angelitozzz_*`.
- Instala Zod con `pnpm add zod@latest`.
<!-- wp-form:end -->

<!-- wp-modal:start -->
## Modales (wp-modal 1.0.3)
- Cada modal es una vista en `template-parts/modals/<id>.php` que se muestra con `get_template_part('template-parts/modals/<id>')`. Ábrelo con `angelitozzz_modal_start($id, [...])` y ciérralo con `angelitozzz_modal_end()`; el contenido entre las dos llamadas es libre.
- El diseño sale de Figma: `width` exacto, `mobile` según el frame móvil y las clases por parte (`class`, `header_class`, `title_class`, `close_class`, `body_class`). Lo que se repite en varios modales va una vez como variable `--modal-*` en el CSS del proyecto.
- Pon siempre `title` (es el nombre accesible; `title_hidden => true` si no debe verse). Abre con `<button>` o `<a href>` que lleve `<?php angelitozzz_modal_trigger_attrs($id); ?>`. Nunca agregues `open` al `<dialog>`.
- No edites el motor (`inc/modals.php`, `src/js/modules/modal.js`, `src/css/modal.css`): se actualiza desde la skill wp-modal.
- No abras un modal al cargar la página salvo que el proyecto lo pida (opción `auto`). Un form dentro de un modal usa wp-form o el que el proyecto haya elegido.
<!-- wp-modal:end -->
