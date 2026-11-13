# Compartido

Se define una sola vez y vale para todas las páginas.

- **Estado:** aprobado

## Alcance

Qué es cada página del diseño. Lo responde el usuario una sola vez; `analyze.mjs` solo propone candidatos (`dynamicCandidates`).

| Diseño                                              | Qué es      | Contenido  |
| --------------------------------------------------- | ----------- | ---------- |
| Inicio (`figma:home`)                               | estática    | ninguno    |
| Conoce a Bárbara (`figma:conoce-a-barbara`)         | estática    | ninguno    |
| Contacto (`figma:contacto`)                         | estática    | ninguno    |
| Mundo Angelitozzz (`figma:mundo-angelitozzz`)       | estática    | ninguno    |
| Preguntas frecuentes (`figma:preguntas-frecuentes`) | estática    | ninguno    |
| Blog (`figma:blog`)                                 | blog        | post       |
| Artículo del blog (`figma:articulo-blog`)           | blog        | post       |
| Soluciones de sueño (`figma:soluciones-de-sueno`)   | catálogo    | solucion   |
| Testimonios (`figma:testimonios`)                   | catálogo    | testimonio |

`Qué es`: `estática`, `blog`, `catálogo` (tipo de contenido propio), `woocommerce` u `otro`. `Contenido`: `ninguno` o la fila de abajo que lo describe.

Fuera de esta etapa (el usuario las agrega después): la ficha de cada curso (interna de producto), la página de cursos a la que lleva el hero de Inicio y las demás páginas aún sin diseño.

**Cursos (2026-11-10, plan en `specs/plan/checklist-cursos.md`):** los cursos se dan con Tutor LMS y se cobran con WooCommerce (Tutor → Monetización: WooCommerce). La página Soluciones de sueño deja de ser la tienda de Woo: lista el tipo de contenido `solucion` (la landing de venta de cada curso), que se une a uno o más cursos de Tutor. Las landings, la lección, el carrito, el checkout, el regalo y la cuenta tienen diseño (`specs/figma/enlaces/`) y entran a esta tabla en la F1.

## Contenido dinámico

Una fila por tipo de contenido que se repite o se filtra (entradas, productos, proyectos). Solo se documenta: no se registra el tipo de contenido ni se escribe la consulta.

| Contenido  | Origen | Taxonomías                                                                                                            | Campos por elemento                                                                                                                                                                                      | Filtros                                      | Plantillas                                                                                                                                                              | Acción                                                           |
| ---------- | ------ | --------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------- |
| post       | post   | `category` jerárquica: Especiales, Bebés 0–4 meses, Bebés 4–24 meses, Bebés 24 meses–4 años, Bases del sueño, Siestas | Nativos de WordPress: título, extracto, contenido (editor), fecha, autor (avatar e Información biográfica del usuario), imagen destacada. Tiempo de lectura calculado del contenido, sin campo           | categoría (pastillas del hero), búsqueda `q` | `home.php` (listado: una sección por categoría elegida por el admin), `category.php` ("Ver todos" de cada categoría, reutiliza hero y tarjeta), `single.php` (artículo) | "Leer artículo": enlace al artículo                              |
| solucion   | cpt    | ninguna                                                                                                               | Tipo de contenido nuevo: la landing de venta de un curso. Nombre (título), foto de la tarjeta (imagen destacada), etiqueta de esquina, ícono, edad, beneficios, cursos de Tutor que vende (1 o más), tipo de compra (directa, selector de etapas, suscripción), precios por cantidad y secciones (contenido flexible). El precio no es campo: sale del producto de Woo de su curso | ninguno                                      | `archive-solucion.php` (página Soluciones de sueño, `/soluciones-de-sueno/`), `single-solucion.php` (landing, `/soluciones-de-sueno/<slug>/`)                            | Botón de la tarjeta: enlace a la landing de la solución          |
| curso      | cpt    | `course-category` de Tutor (edad)                                                                                     | Tipo de contenido de Tutor LMS (`courses`, ya existe): temario, lecciones con video de Vimeo, materiales, progreso, inscripción y duración del acceso. Se edita en el constructor de Tutor, no con ACF  | ninguno                                      | Las de Tutor, sobrescritas en `tutor/` solo donde el diseño lo pide (lección, cuenta, login). URL `/cursos/<slug>/`: sin comprar redirige a su solución             | Comprar: lo hace la landing de la solución                       |
| producto   | woo    | ninguna                                                                                                               | Solo para cobrar: un producto Simple + Virtual + "For Tutor" por curso, unido al curso en Tutor. Nativos de Woo: precio, precio rebajado (precio de lanzamiento), cupones. Oculto del catálogo         | ninguno                                      | No tiene listado ni ficha: `/tienda/` redirige a `/soluciones-de-sueno/`                                                                                                 | Agregar al carrito de Woo (desde la landing)                     |
| testimonio | cpt    | `categoria_testimonio` no jerárquica: Soluciones de Sueño, Mundo Angelitozzz                                          | Tipo de contenido nuevo. Nombre (título), foto (imagen destacada), testimonio (contenido), estrellas (1–5), edad del bebé (texto: "Bebé de 5 meses")                                                     | categoría (pastillas)                        | `archive-testimonio.php` (página Testimonios, `/testimonios/`)                                                                                                          | ninguna                                                          |

- `Contenido`: nombre en singular, snake_case, sin tildes (`producto`, `proyecto`, `post`).
- `Origen`: `post` (entradas del blog), `cpt` (tipo de contenido propio, nuevo o ya existente) o `woo` (WooCommerce: solo se maqueta).
- `Taxonomías`: nombre, si es jerárquica y los términos de ejemplo del diseño (`categoria_producto` jerárquica: Sillas, Mesas).
- `Campos por elemento`: lo que cada elemento guarda (precio, galería, ficha). El detalle va en el spec del detalle.
- `Filtros`: los que muestra el diseño (categoría, marca, orden, buscador) o `ninguno`.
- `Plantillas`: archivo de listado, de detalle y de taxonomía (`archive-producto.php`, `single-producto.php`).
- `Acción`: qué hace el botón de cada elemento (WhatsApp con mensaje, formulario de consulta, contacto, carrito de Woo, ninguna) y de dónde sale el dato (campo global, campo del elemento).

Los sliders de testimonios de Inicio y Mundo Angelitozzz **no** usan el tipo `testimonio`: cada página tiene su propio repetidor (nota del usuario: "cada pagina que tiene testimonios es independiente").

## WooCommerce

Solo si algún contenido tiene `Origen: woo`; si no, se deja vacía o se borra. Con `woo`: una fila por pieza (las cuatro) y los dos valores. Lo responde el usuario una sola vez: no se adivina. Solo se documenta: no se escribe lógica de WooCommerce.

| Pieza        | Modo      |
| ------------ | --------- |
| carrito      | clásico   |
| checkout     | clásico   |
| mini-carrito | clásico   |
| cuenta       | no aplica |

- **Variaciones:** no
- **Filtros de tienda:** no

Decidido en la F0 (2026-11-10): carrito, checkout y mini-carrito clásicos, porque el diseño es propio y los plugins de pasarela peruanos (Niubiz) no siempre soportan el checkout en bloques. El mini-carrito es un drawer que se abre al agregar un curso. La cuenta no es la de Woo: la lleva el panel de Tutor LMS (escritorio, mi perfil, cursos inscritos e historial de pedidos), y "Mi cuenta" de Woo redirige ahí. Las etapas de Horarios Ideales no son variaciones: son 3 cursos con su propio producto.

- `Pieza`: las que WooCommerce puede dibujar como plantilla PHP o como bloque: `carrito`, `checkout`, `mini-carrito` y `cuenta`. Tienda, tarjeta de producto y ficha son siempre plantillas PHP y no se preguntan.
- `Modo`: `clásico` (plantilla PHP de `woocommerce/`: el HTML sale del diseño), `bloques` (bloque de WooCommerce: no hay plantilla, el diseño se aproxima con CSS) o `no aplica` (el diseño no tiene esa pieza).
- `Variaciones`: `sí` si hay productos con talla, color u otro atributo que se elige antes de comprar.
- `Filtros de tienda`: `sí` si la tienda del diseño filtra (categoría, precio, orden).

## Sitio

Lo que se repite en todas las páginas (cabecera, menú, pie). Aquí solo se decide dónde vive cada cosa; los campos van en `specs/opciones.md`. Nada se crea en esta etapa.

- **Identidad del sitio** (Personalizar > Identidad del sitio, nativo de WordPress): logo principal, ícono del sitio, nombre y descripción. No son campos ACF.
- **Menús** (Apariencia > Menús): cada menú es una ubicación del tema, no un campo.
- **Todo lo demás que se repite** (logo alternativo, contacto, redes, copyright, textos fijos del pie, aviso superior): campos de la página de opciones "Ajustes del sitio", en `specs/opciones.md`.

En este sitio:

- **Personalizar:** el logo del header (`header-logo.png`) es el logo principal (`custom-logo`).
- **Ajustes del sitio** (`specs/opciones.md`): el cintillo de promociones y el modal que abre (con su formulario y la pantalla de gracias), los números de WhatsApp, las redes sociales y el texto del newsletter del pie.
- **Fijo en `footer.php`:** el crédito "Made with ♥ NERD" (firma de la agencia, no lo edita el cliente).
- **Obligatorio en todas las páginas** (nota de Inicio): cintillo, header y footer. Viven en `header.php` y `footer.php`, nunca dentro de una plantilla de página.
- **Fondo de nubes:** lo usan Inicio y Soluciones de sueño (`pagina-fondo-nubes.webp`), no todas las páginas. Se queda quieta al hacer scroll (`background-attachment: fixed`; en móvil, donde iOS no lo respeta, una capa `position: fixed` detrás del contenido). Es de cada página y se documenta en su spec.

### Menús

| Ubicación          | Dónde se muestra                                                  | Enlaces del diseño                                                                    |
| ------------------ | ----------------------------------------------------------------- | ------------------------------------------------------------------------------------- |
| `principal`        | Header, centrado                                                  | Soluciones de Sueño, Blog, Mundo Angelitozzz, Conoce a Bárbara, Testimonios           |
| `cabecera-derecha` | Header, a la derecha, junto a "Iniciar sesión" (texto más grande) | Contacto                                                                              |
| `pie-navegacion`   | Footer, columnas 1 y 2                                            | Soluciones de Sueño, Mundo Angelitozzz, Conoce a Bárbara, Testimonios, Blog, Contacto |
| `pie-legal`        | Footer, columna 3                                                 | Políticas de privacidad, Términos y condiciones                                       |
| `pie-ayuda`        | Footer, columna 4                                                 | Preguntas frecuentes, Soporte                                                         |

## Tokens

Van en `@theme` del CSS principal. Todo valor repetido del diseño se reemplaza por su token.

| Token                 | Valor                                                                                                    | Uso                                                                               | Origen                                                |
| --------------------- | -------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------- | ----------------------------------------------------- |
| `--color-tinta`       | `#000000`                                                                                                | Texto general                                                                     | Figma `color/texto`                                   |
| `--color-tinta-suave` | `#6b6b6b`                                                                                                | Texto secundario (fechas, notas)                                                  | Figma `color/texto-suave`                             |
| `--color-marca`       | `#234549`                                                                                                | Títulos, menú activo, botón verde, pastilla activa                                | Figma `color/verde`                                   |
| `--color-marca-suave` | `#56706f`                                                                                                | Subtítulos y textos de apoyo                                                      | Figma `color/verde-suave`                             |
| `--color-acento`      | `#a8566a`                                                                                                | Botón principal, contador del carrito, enlaces "Leer artículo"                    | Figma `color/frambuesa`                               |
| `--color-etiqueta`    | `#9cb0b0`                                                                                                | Fondo de etiquetas y pastillas de categoría                                       | Figma `color/teal`                                    |
| `--color-contorno`    | `#492343`                                                                                                | Borde de flechas de slider, íconos de redes, flecha "Ver más"                     | Figma `green 80`                                      |
| `--color-fondo`       | `#f8f6f1`                                                                                                | Fondo de secciones                                                                | Figma `color/crema`                                   |
| `--color-fondo-rosa`  | `#f1dbd5`                                                                                                | Fondo de hero del blog y bloques de cita                                          | Figma `color/blush`                                   |
| `--color-neutro`      | `#d7d0b5`                                                                                                | Bordes, separadores, tarjetas beige, degradados del slider                        | Figma `color/beige` (y la variable duplicada `Beige`) |
| `--color-blanco`      | `#ffffff`                                                                                                | Tarjetas, texto sobre botones                                                     | Figma `color/blanco`                                  |
| `--color-estrella`    | `#e9b44c`                                                                                                | Estrellas de los testimonios (25 usos)                                            | Valor suelto sin variable                             |
| `--font-sans`         | `"Barlow Condensed", sans-serif`                                                                         | Toda la tipografía (400, 500, 600 e itálica 400/500), autoalojada en `src/fonts/` | Figma estilos `2026/*`                                |
| `--text-display`      | `52px` / interlineado `1` / peso 600                                                                     | Título de página (h1 del hero)                                                    | `2026/Display`                                        |
| `--text-h1`           | `50px` / `1.1` / 600                                                                                     | h1 de listados                                                                    | `2026/H1`                                             |
| `--text-h2`           | `46px` / `1.15` / 500                                                                                    | Títulos de sección                                                                | `2026/H2`                                             |
| `--text-h3`           | `32px` / `1.2` / 500                                                                                     | Subtítulos                                                                        | `2026/H3`                                             |
| `--text-h4`           | `26px` / `1.25` / 500                                                                                    | Títulos de tarjeta                                                                | `2026/H4`                                             |
| `--text-h5`           | `22px` / `1.3` / 500                                                                                     | Títulos menores                                                                   | `2026/H5`                                             |
| `--text-cuerpo-xl`    | `24px` / `1.5` / 400                                                                                     | Bajadas grandes                                                                   | `2026/Body XL`                                        |
| `--text-cuerpo-l`     | `20px` / `1.5` / 400                                                                                     | Bajadas                                                                           | `2026/Body L`                                         |
| `--text-cuerpo`       | `18px` / `1.5` / 400                                                                                     | Párrafos                                                                          | `2026/Body`                                           |
| `--text-cuerpo-s`     | `16px` / `1.5` / 400                                                                                     | Menú, textos chicos                                                               | `2026/Body S`                                         |
| `--text-rotulo`       | `18px` / `1.3` / 500                                                                                     | Botones                                                                           | `2026/Label`                                          |
| `--text-rotulo-s`     | `15px` / `1.3` / 500                                                                                     | Etiquetas, pastillas                                                              | `2026/Label S`                                        |
| `--text-caption`      | `14px` / `1.4` / 400                                                                                     | Fecha y lectura de tarjetas                                                       | `2026/Caption`                                        |
| `--text-eyebrow`      | `18px` / `1.3` / 500 / espaciado `0.04em`                                                                | Antetítulos                                                                       | `2026/Eyebrow`                                        |
| `--text-nota`         | `16px` / `1.4` / 400 itálica                                                                             | Notas en itálica                                                                  | `2026/Note`                                           |
| `--radius-tarjeta`    | `24px`                                                                                                   | Tarjetas, cajas y imágenes de producto                                            | 22 usos                                               |
| `--radius-media`      | `20px`                                                                                                   | Imágenes de blog, testimonios e Instagram                                         | 41 usos                                               |
| `--radius-etiqueta`   | `16px`                                                                                                   | Etiquetas de esquina                                                              | 22 usos                                               |
| `--shadow-header`     | `-2px 0 12px #cac29b26, -10px 10px 22px #cac29b21, -22px 40px 30px #cac29b14, -39px 80px 36px #cac29b05` | Sombra del header                                                                 | Figma `shadow header`                                 |
| `--container-sitio`   | `1110px`                                                                                                 | Ancho del contenido (márgenes de 405 px a 1920)                                   | 19 usos                                               |

Decisiones y valores sueltos:

- Las pastillas y botones usan `rounded-full` (radio 100 del diseño): no necesitan token.
- Los espaciados de sección (104, 88, 72, 40, 24…) son múltiplos de 4 y existen en la escala de Tailwind (`py-26`, `py-22`…): no llevan token.
- `#9ec0c0` (`green 20`, solo Inicio), `#c4704a` (`color/terracota`, solo Mundo Angelitozzz) y `#8a8a8a` (placeholder del newsletter) se usan una vez: quedan como valor arbitrario y se listan en el `Comportamiento` de su sección.
- Los valores sueltos iguales a un token (`#000000`, `#d7d0b5`, `#492343`, `#234549`, `#ffffff`) usan el token.
- Anchos de contenido del artículo (1360 y 1040) y radios de 28, 30 y 32 aparecen en una sola página: quedan como valor arbitrario.
- La sombra `shadow header` llegó escrita de dos formas distintas en Figma, pero tiene el mismo valor.
- "Made with" del pie usa Gothic A1: es parte del crédito de la agencia y se resuelve con el SVG del logo NERD; no se carga esa fuente.

## Componentes compartidos

Cada componente vive en `template-parts/components/<nombre>.php` y recibe sus datos por `$args`.

### boton

- **Origen:** componente Figma "Botón" (29 usos en 8 páginas; variantes Frambuesa, Verde, Secundario)
- **Destino:** `template-parts/components/boton.php`
- **Argumentos:** `enlace` (array de campo link o string URL, requerido), `texto` (string, opcional: reemplaza el texto del enlace), `variante` (`frambuesa` | `verde` | `secundario`, por defecto `frambuesa`), `flecha` (bool, por defecto `true`), `desactivado` (bool, por defecto `false`: también se activa solo cuando el enlace es `#` o está vacío), `clase` (string, opcional)
- **Se usa en:** casi todas las secciones con botón; el `<a>` pasa por `angelitozzz_link_attrs()`

### etiqueta

- **Origen:** componente Figma "Etiqueta" (16 usos; estilos Tarjeta, Hero, Esquina; íconos Ninguno, Destellos, Estrella)
- **Destino:** `template-parts/components/etiqueta.php`
- **Argumentos:** `texto` (string, requerido), `estilo` (`tarjeta` | `hero` | `esquina`, por defecto `tarjeta`), `icono` (`ninguno` | `destellos` | `estrella`, por defecto `ninguno`)
- **Se usa en:** post-card, producto-card, testimonio-card, articulo-blog/cabecera-del-articulo

### post-card

- **Origen:** componente Figma "Tarjeta de blog" (18 usos: blog, articulo-blog, conoce-a-barbara)
- **Destino:** `template-parts/components/post-card.php`
- **Argumentos:** `imagen` (array imagen/ID, requerido), `categoria` (string, requerido), `fecha` (string, "1 de marzo de 2024", requerido; la tarjeta antepone "Publicado el"), `lectura` (string, "5 min de lectura", requerido), `titulo` (string, requerido), `resumen` (string, requerido), `url` (string, requerido)
- **Se usa en:** blog (las 4 secciones de categoría), articulo-blog/te-puede-interesar, conoce-a-barbara/seccion-blog, `category.php`

### hero-blog

- **Origen:** blog/hero-blog (nodo 3751:4324): antetítulo, título, bajada, buscador y pastillas de categoría
- **Destino:** `template-parts/components/hero-blog.php`
- **Argumentos:** `antetitulo` (string), `titulo` (string, requerido), `bajada` (string), `activa` (string, slug de la categoría activa o vacío). El placeholder, "Buscar" y "Todos" son textos fijos del componente.
- **Se usa en:** `home.php` (Blog), `category.php` (Ver todos)

### blog-listado

- **Origen:** grilla de `post-card` del blog (las tarjetas de las secciones de categoría, en 3 columnas): archivo de categoría y resultados de búsqueda
- **Destino:** `template-parts/components/blog-listado.php`
- **Argumentos:** `titulo` (string, opcional: título h2 solo para lectores de pantalla)
- **Se usa en:** `category.php` (Ver todos), `home.php` (resultados de búsqueda)

### producto-card

- **Origen:** tarjetas de soluciones-de-sueno/shop y home/como-ayudarte (imagen 346 × 374, etiqueta de esquina, nombre y edad, descripción, viñetas, botón)
- **Destino:** `template-parts/components/producto-card.php`
- **Argumentos:** `imagen` (array imagen/ID o vacío: sin imagen usa la imagen por defecto "not found", `assets/img/ayudarte-imagen-not-found.webp`), `etiqueta` (string, opcional), `etiqueta_icono` (`ninguno` | `destellos` | `estrella`), `titulo` (string, requerido), `edad` (string, opcional), `descripcion` (string, requerido), `beneficios` (array de strings, opcional), `boton` (array link, requerido), `loading` (`lazy` | `eager`, por defecto `lazy`: `eager` para las tarjetas de la primera pantalla)
- **Se usa en:** home/como-ayudarte, soluciones-de-sueno/shop (`woocommerce/content-product.php` la llama)

### testimonios-slider

- **Origen:** componente Figma "Testimonios · Tarjetas" (home/testimonios y mundo-angelitozzz/testimonios)
- **Destino:** `template-parts/components/testimonios-slider.php` y `src/js/modules/testimonios-slider.js` (Swiper, no automático)
- **Argumentos:** `titulo` (string, requerido), `testimonios` (array de `autora`, `detalle`, `estrellas`, `texto`; requerido), `boton` (array link, lleva a la página Testimonios)
- **Se usa en:** home/testimonios, mundo-angelitozzz/testimonios (cada página con su propio repetidor)

### testimonio-card

- **Origen:** tarjetas de testimonios/seccion-testimonios (foto 160 × 160, nombre, estrellas, categoría, edad del bebé, texto)
- **Destino:** `template-parts/components/testimonio-card.php`
- **Argumentos:** `post_id` (int, opcional: el testimonio; de él salen los demás datos y lo que se pase aparte tiene prioridad), `foto` (array imagen/ID), `nombre` (string, requerido), `estrellas` (int 1–5, por defecto 5), `categoria` (string), `edad` (string, opcional), `texto` (string, requerido)
- **Se usa en:** `archive-testimonio.php` (página Testimonios)

### cta-final

- **Origen:** componente Figma "CTA final" (6 usos: home, conoce-a-barbara, articulo-blog, preguntas-frecuentes, soluciones-de-sueno, testimonios), fondo con destellos blancos
- **Destino:** `template-parts/components/cta-final.php`
- **Argumentos:** `titulo` (string, requerido), `descripcion` (string, opcional: se oculta si viene vacía), `boton` (array link, requerido)
- **Se usa en:** home, conoce-a-barbara, articulo-blog, preguntas-frecuentes, soluciones-de-sueno, testimonios

### cintillo-promociones

- **Origen:** home/cintillo-promociones (nodo 4011:9019), en todas las páginas
- **Destino:** `template-parts/components/cintillo-promociones.php`
- **Argumentos:** `texto` (string; vacío = no se muestra), `modal` (string, id del modal que abre; por defecto `modal-coach`)
- **Se usa en:** `header.php`

## Criterios comunes

Valen para todas las secciones y páginas. Cada sección lo declara con una sola línea ("Cumple los criterios comunes") y solo lista los suyos. Se marcan `[x]` en la sección cuando se comprueban.

### Todas las secciones

- [ ] Un solo `h1` por página y los encabezados no saltan de nivel
- [ ] Sin estilos inline, `!important` ni manejadores `onclick=` en el HTML
- [ ] Controles correctos: acciones con `<button>`, navegación con `<a href>`, campos con `<label>`
- [ ] Imágenes con `alt` (vacío si son decorativas) y proporción fija (`aspect-ratio` + `object-cover`)
- [ ] Colores, tipografías, radios y sombras salen de los tokens de este archivo; los valores sueltos están listados en "Comportamiento"
- [ ] Mismo aspecto que el original en escritorio y en móvil (espaciados, tamaños y colores iguales)
- [ ] Toda la salida PHP escapada (`esc_html`, `esc_url`, `esc_attr`) y el archivo empieza con `defined('ABSPATH') || exit;`
- [ ] `php -l` sin errores en cada archivo creado y `pnpm build` sin errores

### Según lo que tenga la sección

- Imágenes: `width`/`height` o `aspect-ratio` para no mover el layout; `loading="lazy"` salvo las de la primera pantalla.
- Enlaces: destinos reales; los externos con `rel` seguro (helper de enlaces de `wp-helper`).
- Comportamiento con JS: el JS vive en `src/js/modules/`, se activa por `data-` o clase, funciona con teclado y respeta `prefers-reduced-motion`.
- Con skill (`wp-form`, `wp-modal`, `wp-accordion`, `wp-helper`): pasa los criterios de esa skill.
- Bloque repetido: un solo componente con `$args`, usado en todos los lugares donde se repetía.
- Texto con partes de otro color o con saltos de línea (también dentro de un componente): llega como un solo texto con los marcadores de `wp-helper` y se imprime con `<prefijo>_highlight()`, sin reglas propias (no se parte por líneas, no se cambian los marcadores a mano, no hay un argumento aparte para la parte de color).
- Decoración (destellos, estrellas, nubes, lunas): SVG con `alt=""` y `aria-hidden`, posicionada sin tapar el contenido; en móvil se reduce u oculta si no entra.
- Sin frame móvil en Figma: el móvil sigue lo aprobado en el `Comportamiento` de cada sección.

### Cada página (una vez, al final)

- [ ] `check.mjs` sale con `0`
- [ ] Los enlaces internos apuntan a las páginas del tema, no a los `.html` del diseño
- [ ] `header.php` y `footer.php` no se repiten dentro de la plantilla de la página

## Nomenclatura

- Un grupo de campos por página (`Grupo ACF`) con su regla de ubicación; cada sección es un campo de tipo `group` (`home_hero`) y sus subcampos se guardan como `<sección>_<campo>`.
- Campos ACF: `<página>_<sección>_<campo>` en snake_case y en español (`home_hero_titulo`), que es el nombre con el que ACF guarda el subcampo `titulo` del grupo `home_hero`.
- Repetidos (cards, ítems): un repeater `<página>_<sección>_<plural>` con subcampos sin prefijo (`home_servicios_items.titulo`).
- Página en español, sin tildes ni `ñ` en el nombre del campo.
- Sin límites impuestos: la descripción del campo trae una recomendación, no una validación.
- Enlaces a páginas que aún no existen (tienda de cursos, ficha de curso, enlace externo de Mundo Angelitozzz): quedan con `#`, se anotan como pendientes en el `Comportamiento` de su sección y **se muestran desactivados**: el componente `boton` con `desactivado: true` imprime el botón sin `href`, con `aria-disabled="true"`, opacidad reducida y sin cursor de enlace (no se ocultan).
