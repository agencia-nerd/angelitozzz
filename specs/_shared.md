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

**Cursos (plan en `specs/plan/checklist-cursos.md`):** desde 2026-11-13 los cursos son a medida, sin Tutor LMS: el plugin propio `angelitozzz-cursos` registra `curso` y `leccion`, guarda inscripciones y avance, controla el acceso y hace el puente con WooCommerce, que cobra (con Openpay; antes Niubiz). El tema solo pinta. La página Soluciones de sueño deja de ser la tienda de Woo: lista el tipo de contenido `solucion` (la landing de venta de cada curso), que se une a uno o más cursos. Las landings, la lección, el carrito, el checkout, el regalo y la cuenta tienen diseño (`specs/figma/enlaces/`) y entran a esta tabla en la F1.

## Contenido dinámico

Una fila por tipo de contenido que se repite o se filtra (entradas, productos, proyectos). Solo se documenta: no se registra el tipo de contenido ni se escribe la consulta.

| Contenido  | Origen | Taxonomías                                                                                                            | Campos por elemento                                                                                                                                                                                      | Filtros                                      | Plantillas                                                                                                                                                              | Acción                                                           |
| ---------- | ------ | --------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------- |
| post       | post   | `category` jerárquica: Especiales, Bebés 0–4 meses, Bebés 4–24 meses, Bebés 24 meses–4 años, Bases del sueño, Siestas | Nativos de WordPress: título, extracto, contenido (editor), fecha, autor (avatar e Información biográfica del usuario), imagen destacada. Tiempo de lectura calculado del contenido, sin campo           | categoría (pastillas del hero), búsqueda `q` | `home.php` (listado: una sección por categoría elegida por el admin), `category.php` ("Ver todos" de cada categoría, reutiliza hero y tarjeta), `single.php` (artículo) | "Leer artículo": enlace al artículo                              |
| solucion   | cpt    | ninguna                                                                                                               | Tipo de contenido nuevo: la landing de venta de un curso. Nombre (título), foto de la tarjeta (imagen destacada), etiqueta de esquina, ícono, edad, beneficios, cursos que vende (1 o más), tipo de compra (directa, selector de etapas, suscripción), precios por cantidad y secciones (contenido flexible). El precio no es campo: sale del producto de Woo de su curso | ninguno                                      | `archive-solucion.php` (página Soluciones de sueño, `/soluciones-de-sueno/`), `single-solucion.php` (landing, `/soluciones-de-sueno/<slug>/`)                            | Botón de la tarjeta: enlace a la landing de la solución          |
| curso      | plugin | ninguna | Tipo de contenido del plugin `angelitozzz-cursos` (no se registra con ACF). Título, extracto, portada (imagen destacada), duración del acceso (4, 6, 12 meses o ilimitado) y su producto de Woo. La información adicional (qué aprenderás, requisitos, para quién es) no se guarda en el curso: la muestra la landing de la solución (decisión del 2026-11-13, `cursos-datos.md`). Se edita en el constructor de Bárbara (`admin-constructor.md`), no en el admin de WordPress | ninguno | No tiene listado ni ficha pública: la URL del curso sin comprar redirige a su solución; con inscripción vigente, a su última lección vista | Comprar: lo hace la landing de la solución |
| leccion    | plugin | ninguna | Tipo de contenido del plugin, hijo de un `curso`: módulo, orden, video (enlace de Vimeo; la duración se lee sola), texto (con la cita "PD"), materiales (PDF protegidos) y vista previa gratuita sí/no. Se edita en el constructor. El acceso y el avance son de las tablas del plugin | ninguno | `single-leccion.php` (`/cursos/<curso>/<leccion>/`, solo con inscripción vigente) | Marcar como completa (avance del plugin) |
| producto   | woo    | ninguna                                                                                                               | Solo para cobrar: un producto Simple + Virtual por curso, que el constructor crea al guardar el paso 1 del curso y actualiza en cada guardado (el plugin guarda la relación). Nativos de Woo: precio, precio rebajado (precio de lanzamiento), cupones. Oculto del catálogo         | ninguno                                      | No tiene listado ni ficha: `/tienda/` redirige a `/soluciones-de-sueno/`                                                                                                 | Agregar al carrito de Woo (desde la landing)                     |
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

Decidido en la F0 (2026-11-10): carrito, checkout y mini-carrito clásicos, porque el diseño es propio y los plugins de pasarela peruanos no siempre soportan el checkout en bloques. La pasarela es Openpay desde el 2026-11-18 (antes Niubiz); su plugin dibuja el pago con `payment_fields()` del checkout clásico. El mini-carrito es un drawer que se abre al agregar un curso. La cuenta no es la de Woo: es propia (página `/cuenta/` del tema, con datos del plugin `angelitozzz-cursos`: escritorio, mi perfil, cursos inscritos e historial de pedidos leído de `WC_Order`), y "Mi cuenta" de Woo redirige ahí. Hasta 2026-11-13 era el panel de Tutor LMS, que se dejó. Las etapas de Horarios Ideales no son variaciones: son 3 cursos con su propio producto.

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
| `pie-ayuda`        | Footer, columna 4                                                 | Preguntas frecuentes (Soporte quitado el 2026-11-17, por ahora)                      |

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
| `--color-terracota`   | `#c4704a`                                                                                                | Íconos y bordes de aviso: "!" de eliminar, zona de subida y botones "+ Agregar" del constructor, caja del código de regalo, nota de Mundo, ícono de regalo del hero | Figma `color/terracota` (F4, bloque 6) |
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
- `#9ec0c0` (`green 20`, solo Inicio) y `#8a8a8a` (placeholder del newsletter) se usan una vez: quedan como valor arbitrario y se listan en el `Comportamiento` de su sección.
- Los valores sueltos iguales a un token (`#000000`, `#d7d0b5`, `#492343`, `#234549`, `#ffffff`) usan el token.
- Anchos de contenido del artículo (1360 y 1040) y radios de 28, 30 y 32 aparecen en una sola página: quedan como valor arbitrario.
- La sombra `shadow header` llegó escrita de dos formas distintas en Figma, pero tiene el mismo valor.
- "Made with" del pie usa Gothic A1: es parte del crédito de la agencia y se resuelve con el SVG del logo NERD; no se carga esa fuente.

## Componentes compartidos

Cada componente vive en `template-parts/components/<nombre>.php` y recibe sus datos por `$args`.

### boton

- **Origen:** componente Figma "Botón" (29 usos en 8 páginas; variantes Frambuesa, Verde, Secundario)
- **Destino:** `template-parts/components/boton.php`
- **Argumentos:** `enlace` (array de campo link o string URL, requerido), `texto` (string, opcional: reemplaza el texto del enlace), `variante` (`frambuesa` | `verde` | `secundario` | `claro`, por defecto `frambuesa`; `claro`: fondo crema y texto verde, sobre fondos oscuros como el banner de Mundo), `flecha` (bool, por defecto `true`), `desactivado` (bool, por defecto `false`: también se activa solo cuando el enlace es `#` o está vacío), `clase` (string, opcional), `modal` (string, opcional: id de un modal de wp-modal; en vez del `<a>` imprime un `<button>` con `angelitozzz_modal_trigger_attrs()` que lo abre, con el texto de `texto` o del `title` de `enlace`), `datos` (array, opcional: atributos `data-*` extra de ese `<button>`), `enviar` (bool, por defecto `false`: imprime un `<button type="submit">` que envía su formulario, con el texto de `texto`)
- **Se usa en:** casi todas las secciones con botón; el `<a>` pasa por `angelitozzz_link_attrs()`. Con `modal`: los botones de compra de las landings (abren el drawer del carrito o el selector de edad) y "Más información" de las etapas. Con `enviar`: los formularios de acceso y el canje de regalo

### etiqueta

- **Origen:** componente Figma "Etiqueta" (16 usos; estilos Tarjeta, Hero, Esquina; íconos Ninguno, Destellos, Estrella)
- **Destino:** `template-parts/components/etiqueta.php`
- **Argumentos:** `texto` (string, requerido), `estilo` (`tarjeta` | `hero` | `esquina`, por defecto `tarjeta`), `icono` (`ninguno` | `destellos` | `estrella`, por defecto `ninguno`), `color` (`salvia` | `estrella` | `suave` | `neutro`, por defecto `salvia`: fondo del estilo `tarjeta`; lo usan los estados del historial de pedidos)
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
- **Argumentos:** `imagen` (array imagen/ID o vacío: sin imagen usa la imagen por defecto "not found", `assets/img/ayudarte-imagen-not-found.webp`), `etiqueta` (string, opcional), `etiqueta_icono` (`ninguno` | `destellos` | `estrella`), `titulo` (string, requerido), `edad` (string, opcional), `descripcion` (string, opcional: se oculta si viene vacía; los relacionados de las landings no la llevan), `beneficios` (array de strings, opcional), `boton` (array link, requerido), `loading` (`lazy` | `eager`, por defecto `lazy`: `eager` para las tarjetas de la primera pantalla)
- **Se usa en:** home/como-ayudarte, soluciones-de-sueno/shop (`archive-solucion.php`), solucion/relacionados

### resumen-compra

- **Origen:** caja "Resumen de compra" de carrito (4151:10157) y checkout (4156:9961): borde `#bcb28a`, radio 12, 345 px de ancho
- **Destino:** `template-parts/components/resumen-compra.php`
- **Argumentos:** `items` (array de `tipo` ("Curso"), `nombre` y `precio` (string ya formateado por Woo); requerido), `subtotal` (string, requerido), `descuento` (string, opcional: sin valor no se imprime la fila), `impuesto` (array `etiqueta` ("IGV") y `monto`, opcional: sin valor no se imprime la fila), `total` (string, requerido)
- **Se usa en:** carrito/carrito-de-compras (`woocommerce/cart/cart.php`), checkout/hero (`woocommerce/checkout/review-order.php`). Título "Resumen de compra" fijo; cada fila con su etiqueta a la izquierda y el monto a la derecha, separadas por líneas `#bcb28a`. Los datos los arma la plantilla de Woo desde el carrito; el componente no llama a Woo.

### carrito-contenido

- **Origen:** contenido del drawer "Resumen de compra" de las landings (`solucion.md` → `drawer-carrito`): lista con scroll, sugerida, pie fijo con el subtotal y "Ir a pagar"; o el estado vacío
- **Destino:** `template-parts/components/carrito-contenido.php`
- **Argumentos:** ninguno. Lee `angelitozzz_carrito_datos()` (`inc/woocommerce/carrito.php`, desde `WC()->cart`). La raíz `div[data-carrito-contenido]` lleva `data-wc-ajax` y `data-en-carrito` para `src/js/modules/carrito.js`
- **Se usa en:** el modal `carrito` (`template-parts/modals/carrito.php`) y como fragmento de Woo (`woocommerce_add_to_cart_fragments`), que lo reemplaza entero al agregar o quitar sin recargar (F5-b)

### carrito-cuenta

- **Origen:** globo con la cantidad sobre el ícono del carrito del header (`header.php`)
- **Destino:** `template-parts/components/carrito-cuenta.php`
- **Argumentos:** ninguno. Lee `angelitozzz_carrito_cantidad()`. Texto `sr-only` "Carrito (N productos)" y el globo solo con 1 curso o más
- **Se usa en:** `header.php` (dentro del enlace que abre el drawer) y como fragmento de Woo (raíz `span[data-carrito-cuenta]`) (F5-b)

### acceso-tarjeta

- **Origen:** "Tarjeta" y "Decoración acceso" de iniciar-sesion (4156:23796), crear-cuenta (4156:23743) y recuperar-contrasena (4156:24625)
- **Destino:** `template-parts/components/acceso-tarjeta.php`
- **Argumentos:** `titulo` (string, requerido: el `h1`), `bajada` (string, opcional). Imprime el fondo crema con la decoración (nube dormida, destellos, luna; `aria-hidden`), la tarjeta blanca (560 px, padding 56, radio 28, sombra `0 12px 40px #24454a14`, separación 32 px) con el logo de Mundo Angelitozzz (asset fijo del tema, `assets/img/compra/banner-logo-mundo.webp`, el mismo del banner de Mundo; `alt` "Mundo Angelitozzz"), el título y la bajada. El formulario de cada pantalla va debajo, dentro de la tarjeta: el componente se abre con `acceso-tarjeta` y se cierra con `acceso-tarjeta-fin`.
- **Se usa en:** iniciar-sesion, crear-cuenta, recuperar-contrasena (`template-parts/pages/<pantalla>/formulario.php`)

### acceso-tarjeta-fin

- **Origen:** el mismo de `acceso-tarjeta`: es su cierre
- **Destino:** `template-parts/components/acceso-tarjeta-fin.php`
- **Argumentos:** ninguno. Cierra la tarjeta y la sección que abre `acceso-tarjeta`.
- **Se usa en:** las mismas pantallas que `acceso-tarjeta`, al final del formulario

### campo-acceso

- **Origen:** campos de las pantallas de acceso: caja crema con borde `#d7d0b5` (`--color-neutro`), radio 12, padding 14/16, ícono de 18 px a la izquierda, etiqueta verde encima (`--text-rotulo`), placeholder en `--color-tinta-suave`
- **Destino:** `template-parts/components/campo-acceso.php`
- **JS:** `src/js/modules/ver-contrasena.js`
- **Argumentos:** `id` (string, requerido), `name` (string, requerido), `etiqueta` (string, requerido), `tipo` (`text` | `email` | `password`, por defecto `text`), `placeholder` (string), `icono` (`correo` | `candado` | `ninguno`, por defecto `ninguno`), `autocomplete` (string), `requerido` (bool, por defecto `true`), `valor` (string: lo que ya escribió, al volver con error; nunca en contraseñas), `error` (bool, por defecto `false`: borde frambuesa y `aria-invalid`), `describe` (string, opcional: id del mensaje de error, para `aria-describedby`), `asterisco` (bool, por defecto `false`: " *" en la etiqueta de un campo requerido; lo usa crear cuenta). Con `tipo` `password` agrega el botón ojo (ver u ocultar, `aria-pressed`, nombre accesible "Mostrar contraseña"); el botón aparece solo con JS.
- **Se usa en:** iniciar-sesion, crear-cuenta, recuperar-contrasena. No en el checkout: allí los campos son subrayados, como su diseño (decisión del usuario, 2026-11-13).

### aviso-formulario

- **Origen:** propuesta de los specs de acceso (el diseño no dibuja errores): recuadro arriba del formulario
- **Destino:** `template-parts/components/aviso-formulario.php`
- **Argumentos:** `texto` (string, requerido), `tipo` (`error` | `aviso`, por defecto `error`: el error lleva `role="alert"`, borde frambuesa y fondo rosa; el aviso, `role="status"`, borde salvia y fondo crema), `id` (string, opcional: para que los campos apunten al mensaje con `aria-describedby`). Sin texto no imprime nada.
- **Se usa en:** iniciar-sesion, crear-cuenta, recuperar-contrasena (el mensaje de un solo uso del plugin, `angelitozzz_cursos_mensaje()`)

### cuenta-menu

- **Origen:** "drawer" de las 4 vistas de la cuenta (usuario-escritorio 4164:25044, usuario-mi-perfil 4164:26839, usuario-cursos 4164:25448, usuario-historial-pedido 4164:25910)
- **Destino:** `template-parts/components/cuenta-menu.php`
- **Argumentos:** `menu` (`alumna` | `instructora`, por defecto `alumna`), `activo` (con `alumna`: `escritorio` | `perfil` | `cursos` | `pedidos`; con `instructora`: `escritorio` | `cursos`; requerido), `saludo` (`h1` | `p`, por defecto `h1`; con `p` el nombre no es el título de la página: lo usa el constructor de cursos, cuyo `h1` es el título del paso; F4, bloque 6b). Con `instructora` (`admin-escritorio.md`, `admin-cursos.md`) los ítems son "Escritorio" y "Cursos" del administrador (`/administrador/`, `/administrador/cursos/`) y la columna mide 458 px. Imprime "Hola," y el nombre de la alumna (`h1` o `p` según `saludo`, `--text-h1`, verde), un `<nav aria-label="Mi cuenta">` (con `instructora`, "Administrador de cursos") con los 4 ítems (321 × 50, radio 15, ícono en círculo de 36 px; activo con fondo verde, texto blanco `--text-h5` y `aria-current="page"`; inactivo con texto `--text-cuerpo-l` y círculo salvia) y al pie el enlace "Cerrar sesión" con flecha (`wp_logout_url`; un enlace de texto, como en el diseño; en móvil queda bajo los ítems). Columna de 377 px con borde derecho negro de 1 px. Los enlaces son las vistas de `/cuenta/` (`/cuenta/`, `/cuenta/perfil/`, `/cuenta/cursos/`, `/cuenta/pedidos/`).
- **Se usa en:** `pages/template-cuenta.php` (todas las vistas de la cuenta) y `pages/template-administrador.php` (con `menu` `instructora`). En móvil (propuesta): saludo arriba y los ítems como fila de pestañas con scroll propio.

### cuenta-pestanas

- **Origen:** tarjetas de pestaña de usuario-mi-perfil, usuario-cursos y usuario-historial-pedido (173 × 94, radio 15, ícono de 35 px sobre el texto)
- **Destino:** `template-parts/components/cuenta-pestanas.php`
- **JS:** `src/js/modules/pestanas.js` (modo `tabs`; genérico, también lo usan las pestañas de la lección)
- **Argumentos:** `pestanas` (array de `id`, `texto`, `icono`, `url` o `panel`, `numero` opcional; requerido), `activa` (string, requerido), `modo` (`enlaces` | `tabs`, por defecto `enlaces`: con `tabs` usa `role="tablist"` y cambia de panel sin recargar, con flechas, Inicio y Fin; cada panel lo imprime la vista con `id="<id>-panel"`), `etiqueta` (string, opcional: nombre accesible del grupo). Los íconos son SVG del tema en `assets/img/cuenta/` con `currentColor`, impresos con `angelitozzz_svg()` (`inc/iconos.php`). Activa: fondo `#fafeff`, borde de 2 px `#492343`, texto verde; inactiva: fondo blanco, sin borde, texto negro.
- **Se usa en:** usuario-mi-perfil (`tabs`), usuario-cursos (`enlaces`, con número), usuario-historial-pedido (una sola, como título)

### curso-progreso-card

- **Origen:** tarjetas de curso de usuario-escritorio (horizontal, 1212 × 238) y usuario-cursos (vertical, 355 × 467)
- **Destino:** `template-parts/components/curso-progreso-card.php`
- **Argumentos:** `datos` (array, requerido: una tarjeta de `angelitozzz_cursos_de_usuaria()` del plugin, ya calculada para la usuaria de la sesión: título, extracto, imagen, URL de continuar o empezar, porcentaje, lecciones completadas y total; cambio de la F4, bloque 5: antes era `curso_id`, pero así la tarjeta no repite la lógica del avance ni del vencimiento), `variante` (`horizontal` | `vertical`, por defecto `vertical`), `lecciones` (bool: muestra "N de M lecciones", por defecto `true` en vertical), `nivel` (`h2` | `h3`, por defecto `h3`). El pie cambia con el avance: 0 % "Empezar curso", en progreso "Continuar curso", 100 % etiqueta "Completado" y `boton` secundario "Volver a ver". Barra de 8 px (fondo crema con borde beige, relleno frambuesa, radio 100): un `<progress>` nativo con la clase `.barra-progreso` de `input.css` (rol progressbar implícito, sin estilos inline).
- **Se usa en:** usuario-escritorio (horizontal), usuario-cursos (vertical)

### reglas-contrasena

- **Origen:** lista "Su contraseña debe tener al menos:" de usuario-mi-perfil / Seguridad (4164:27124)
- **Destino:** `template-parts/components/reglas-contrasena.php`
- **JS:** `src/js/modules/reglas-contrasena.js`
- **Argumentos:** `campo` (string, requerido: id del campo de contraseña que vigila). Lista de 4 reglas en 2 × 2 (8 caracteres, 1 letra minúscula, 1 número, 1 letra mayúscula) con ícono de 30 px: cumplida check `#9cb09d`, pendiente ✕ `#f5a8a9`; cada regla dice su estado en texto para lectores ("cumplida" / "pendiente") y la lista se anuncia con `aria-live="polite"`. Las reglas que se muestran las da el plugin con `angelitozzz_cursos_reglas_contrasena()` (las mismas que valida el servidor, F3); el tema pone el texto de cada una y su comprobación en vivo. Una regla nueva del plugin sin texto en el tema se muestra con su clave y queda pendiente (2026-11-24). Sin el plugin, la lista no se muestra.
- **Se usa en:** usuario-mi-perfil (Seguridad), crear-cuenta, recuperar-contrasena (nueva contraseña), checkout (contraseña de la cuenta nueva). Regla de todo el sitio (decisión del usuario, 2026-11-13).

### banner-mundo

- **Origen:** componente Figma "Banner Mundo" (3586:1518), en la primera lección (`leccion.md`)
- **Destino:** `template-parts/components/banner-mundo.php`
- **Argumentos:** `titulo` (string, opcional: si no viene, el de Ajustes del sitio), `texto` (string, opcional, ídem), `variante` (`leccion` | `confirmacion`, por defecto `leccion`). El enlace, el pie y el texto del botón salen siempre de `get_field('opciones_mes_gratis_mundo', 'option')`; sin enlace no imprime nada. Degradado verde, logo de Mundo de 96 px con borde dorado, `boton` claro sin flecha en pestaña nueva (`angelitozzz_link`).
- **Se usa en:** leccion/banner-mundo (primera lección de los cursos con mes gratis); gracias/mes-gratis-mundo (`variante` `confirmacion`, título "Espera — hay algo más esperándote")

### confirmacion-tarjeta

- **Origen:** tarjetas de regalo de Figma: "Regalo listo" (3649:1702), "Canje de regalo" (3640:1593) y "Canje exitoso" (3677:1971). Blanca, 720 px, padding 56/64, radio 24 (`--radius-tarjeta`), bloques centrados con 24 px entre ellos
- **Destino:** `template-parts/components/confirmacion-tarjeta.php`
- **Argumentos:** `titulo` (string, requerido), `nivel` (`h1` | `h2`, por defecto `h1`), `icono` (`regalo` | `check` | `ninguno`, por defecto `ninguno`: SVG de 56 px, fijo del tema y decorativo), `pastilla` (string, opcional: "✓ Compra exitosa", "✓ Regalo canjeado"), `pastilla_estilo` (`verde` | `neutro`, por defecto `verde`: verde = fondo `--color-marca` y texto `--color-fondo`; neutro = fondo `--color-neutro` y texto `--color-marca`; radio 100, padding 6/14, `--text-rotulo`), `texto` (string, opcional: `--text-cuerpo-l`, `--color-tinta`, centrado). Título en `--text-h2` `--color-marca`, centrado. El contenido de cada pantalla (campo, caja del código, caja de acceso, botones) va entre `confirmacion-tarjeta` y `confirmacion-tarjeta-fin`.
- **Se usa en:** canjear-regalo/canje, canjear-regalo/canjeado, gracias/confirmacion, gracias/regalo-listo

### confirmacion-tarjeta-fin

- **Origen:** el mismo de `confirmacion-tarjeta`: es su cierre
- **Destino:** `template-parts/components/confirmacion-tarjeta-fin.php`
- **Argumentos:** `nota` (string, opcional: `--text-nota`, `--color-tinta-suave`, en itálica como en Figma). Imprime la nota, si la hay, y cierra la tarjeta.
- **Se usa en:** las mismas pantallas que `confirmacion-tarjeta`, al final de su contenido

### curso-admin-card

- **Origen:** tarjetas de la lista de cursos del administrador (`admin-cursos`, nodo 4184:29718; 355 × 467, radio 20)
- **Destino:** `template-parts/components/curso-admin-card.php`
- **Argumentos:** `datos` (array, requerido: un curso de `angelitozzz_cursos_admin_lista()` del plugin, con portada, título, estado, fecha, estudiantes, precio de su producto de Woo y URL de editar y de su solución; cambio de la F4, bloque 6: antes recibía el ID del curso, como pasó con curso-progreso-card), `nivel` (`h2` | `h3`, por defecto `h3`). Sin portada usa la imagen "not found"; sin precio muestra "—". Muestra la `etiqueta` "Borrador" si no está publicado y el botón "Más opciones" (⋯) con su menú (Editar, Ver en el sitio si está en una solución, Publicar / Pasar a borrador, Eliminar). El menú y las acciones son de `src/js/modules/admin-cursos.js`; sin JS el título enlaza a editar.
- **Se usa en:** admin-cursos/seccion

### testimonio-destacado

- **Origen:** componente Figma "Testimonio · Destacado" (testimonios de las 4 landings de soluciones: curso-angelita-pro 4013:14078, curso-a-dormir-por-fin 4013:15586, curso-horarios-ideales 4013:19383, curso-recien-nacidos-go 4013:18105)
- **Destino:** `template-parts/components/testimonio-destacado.php`
- **JS:** `src/js/modules/testimonio-destacado.js` (Swiper, un testimonio a la vez, no automático)
- **Argumentos:** `titulo` (string, requerido), `testimonios` (array de `texto` y `autora`; requerido), `fondo` (`crema` | `rosa` | `beige` | `salvia` | `blanco`, por defecto `beige`)
- **Se usa en:** solucion/secciones (layout `testimonios`). Distinto de `testimonios-slider` (tarjetas con estrellas de Inicio y Mundo): aquí es una cita grande en itálica con la autora, flechas blancas a los lados y puntos debajo; con un solo testimonio no hay flechas ni puntos. Decoración fija: fila de "Z" y nubes.

### video-portada

- **Origen:** portada con botón play de la galería de las landings (curso-angelita-pro/conoce-angelito-pro-por-dentro 4013:14037, curso-recien-nacidos-go/resumen-breve 4013:18035) y del modal de etapa (curso-horarios-ideales/modal-etapas 3752:5964)
- **Destino:** `template-parts/components/video-portada.php`
- **JS:** `src/js/modules/video-portada.js`
- **Argumentos:** `imagen` (array imagen/ID/URL, requerido: la portada), `video` (string, opcional: enlace de Vimeo), `titulo` (string, requerido: `alt` de la portada y nombre del botón play "Reproducir video: …"), `clase` (string, opcional: tamaño y radio del contenedor), `loading` (`lazy` | `eager`, por defecto `lazy`). Sin video (o con un enlace que no es de Vimeo) es solo la imagen. El iframe no está en el HTML: el JS lo crea al pulsar play y lo quita al cerrar el modal o al cambiar de diapositiva. La dirección del reproductor la da el plugin con `angelitozzz_cursos_video_url( $video, ['autoplay' => true] )` (con `function_exists`; F5-c): el tema no sabe del proveedor de video.
- **Se usa en:** solucion/secciones (layout `galeria`), `template-parts/modals/etapa.php`

### testimonios-slider

- **Origen:** componente Figma "Testimonios · Tarjetas" (home/testimonios y mundo-angelitozzz/testimonios)
- **Destino:** `template-parts/components/testimonios-slider.php`
- **Argumentos:** `titulo` (string, requerido), `testimonios` (array de `autora`, `detalle`, `estrellas`, `texto`; requerido), `boton` (array link, lleva a la página Testimonios). El movimiento es de `src/js/modules/testimonios-slider.js` (Swiper, no automático)
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
