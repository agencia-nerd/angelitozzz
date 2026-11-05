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
## Helpers (wp-helper 1.3.0)
- Los helpers viven en `inc/helpers/` y se cargan con `require_once` desde `functions.php`. No edites los archivos del motor: se actualizan desde la skill wp-helper.
- Un helper propio de este proyecto va en `inc/helpers/<nombre>.php` con el prefijo `angelitozzz_`, y también se carga desde `functions.php`.
- Toda imagen de un campo ACF pasa por `angelitozzz_image($campo, [...])` (devuelve el `<img>` o `''`) o por `angelitozzz_image_data($campo)` (`url`, `alt`, `width`, `height` o `null`). No uses `$campo['url']` ni `wp_get_attachment_image_url()` directo en las vistas. Usa `'loading' => 'eager'` en la imagen principal de la página. Si el cliente no escribe el alt, pasa `'alt_fallback'` con el título de la sección (o el de su tarjeta); `'alt' => ''` solo para imágenes decorativas.
- Todo enlace de un campo ACF (Link o URL) pasa por `angelitozzz_link($campo, [...])` (el `<a>` completo) o por `<a<?= angelitozzz_link_attrs($campo, [...]) ?>>` cuando el diseño lleva íconos u otros hijos. No escribas `target="_blank"`, `rel` ni `$campo['url']` a mano: el helper pone `rel`, descarta enlaces vacíos o inseguros y avisa de la pestaña nueva (clase `sr-only`).
<!-- wp-helper:end -->
