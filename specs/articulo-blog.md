# Página: Artículo del blog

- **Resumen:** Muestra un artículo del blog completo (título, autora, imagen destacada y contenido del editor) y lleva a la lectora a conocer a Bárbara, a otros artículos de la misma categoría o a las soluciones de sueño.
- **Fuente:** `figma:articulo-blog`
- **Plantilla:** `single.php`
- **Tipo:** detalle
- **Contenido:** post
- **Secciones (en orden):** cabecera-del-articulo, cuerpo-del-articulo, escrito-por, te-puede-interesar, cta-final
- **Skills usadas:** wp-helper (enlaces de los botones)
- **Grupo ACF:** ninguno
- **Ubicación:** tipo de contenido = post (sin campos propios por ahora)
- **Padre:** ninguno
- **Notas de página:**
  - `h1`: el título de la entrada (`the_title()`), en la cabecera. Los títulos del contenido del editor empiezan en `h2`.
  - Título SEO: "<título de la entrada> | Angelitozzz" (lo arma WordPress o el plugin de SEO); meta descripción: el extracto de la entrada. El diseño no trae ninguna de las dos.
  - Todo lo que cambia por artículo es nativo de WordPress: título, categoría, fecha, autor (nombre, avatar e Información biográfica), imagen destacada, contenido del editor y extracto. El tiempo de lectura se calcula del contenido. Por eso la entrada no lleva campos ACF propios (`Grupo ACF: ninguno`).
  - Los textos que son iguales en todos los artículos ("Escrito por", "Conoce a Bárbara", "Ver soluciones de sueño", "También te puede interesar", "Ver todo →" y los del CTA final) quedan fijos en la plantilla en esta etapa. Propuesta pendiente de decisión: pasarlos a la página de opciones (`specs/opciones.md`), no a cada entrada.
  - Fondo crema (`--color-fondo`) en cabecera, cuerpo y escrito-por; blanco en te-puede-interesar.
  - Sin frame móvil en Figma: el móvil propuesto está en el `Comportamiento` de cada sección.
  - `single.php` llama una vez a `get_header()` y `get_footer()` y pone las cinco secciones dentro de `<main id="main-content">` y de un `<article>` con `post_class()` (las tres primeras) dentro del loop de la entrada.
- **Estado:** pendiente

## Sección: cabecera-del-articulo

- **Origen:** `specs/figma/lectura/articulo-blog/cabecera-del-articulo.ref.txt` (nodo 3751:4579)
- **Destino:** `template-parts/pages/articulo-blog/cabecera-del-articulo.php`, `inc/helpers/tiempo-lectura.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** todo sale de la entrada, sin campos. Migas de pan en `<nav aria-label="Migas de pan">`: "Blog" enlaza a la página de entradas (`page_for_posts`) y la categoría a su archivo (`get_category_link()`), separadas por "/" con `aria-hidden`; texto Caption `--color-tinta-suave`. Debajo, la categoría principal (la primera de la entrada) con el componente `etiqueta` (`estilo: tarjeta`). Título `h1` con `--text-display` `--color-marca`, ancho máximo 1100 px. Fila de autora (el avatar sale de Gravatar o de un plugin de avatar local, ver escrito-por): avatar de WordPress (`get_avatar()`, 44 px, circular, `alt=""` porque el nombre va al lado), "Por <nombre del autor>" (Label `--color-marca`) y "<fecha> · <N> min de lectura" (Caption `--color-tinta-suave`); fecha con `get_the_date('j \d\e F \d\e Y')` en español ("1 de marzo de 2024"). El tiempo de lectura lo calcula el helper propio `angelitozzz_tiempo_lectura()` (palabras del contenido sin etiquetas ÷ 200, redondeado hacia arriba, mínimo 1, devuelve "5 min de lectura"); el mismo helper alimenta el argumento `lectura` de `post-card` en el blog y en te-puede-interesar. Imagen grande: la imagen destacada (`the_post_thumbnail('full')`, `loading="eager"` y `fetchpriority="high"`, alt del adjunto o, si está vacío, el título de la entrada), relación 17:8 (1360 × 640) con `object-cover` y `--radius-tarjeta`; recomendación para la imagen destacada: 2720 × 1280 px (17:8), mínimo 1360 px de ancho. Sin imagen destacada, el bloque de la imagen no se imprime. Contenedor de 1360 px (valor suelto, una sola página) con `pt-18 pb-12` y `gap` 18 px entre filas. Móvil (propuesto): margen lateral de 16 px, título a 36 px (interlineado 1.1), la fila de autora se mantiene en una fila (la línea de fecha puede partirse), imagen en 4:3 con `--radius-media`, `pt-10 pb-8`.
- **Indicaciones del diseño:**
  - La imagen que aparece de quien lo publico (Por Bárbara...) se usara lo que es del avatar del propio wordpress.
  - sobre los minutos de lecturas que sea automatico, y no un campo a parte.
  - Que la imagen grande venga de la imagen destacada.
- **Estado:** pendiente

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
|---|---|---|---|---|

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] El `h1` es el título de la entrada y es el único de la página
- [ ] El avatar es el de WordPress (`get_avatar()`), sin imagen fija ni campo ACF
- [ ] El tiempo de lectura cambia al alargar o acortar el contenido de la entrada (no hay campo para escribirlo)
- [ ] La imagen grande es la imagen destacada, carga con `eager` y no se imprime nada si la entrada no tiene una
- [ ] "Blog" y la categoría de las migas de pan llevan a la página del blog y al archivo de la categoría

## Sección: cuerpo-del-articulo

- **Origen:** `specs/figma/lectura/articulo-blog/cuerpo-del-articulo.ref.txt` (nodo 3751:4590)
- **Destino:** `template-parts/pages/articulo-blog/cuerpo-del-articulo.php`, `src/css/articulo-contenido.css`, `inc/editor-estilos.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** imprime `the_content()` dentro de `<div class="articulo-contenido">` (columna de 1040 px, valor suelto de una sola página, `pt-6 pb-18`). El texto del diseño es contenido de ejemplo de la entrada, no texto fijo de la plantilla. Los estilos por defecto de los bloques de WordPress se sobrescriben con los del diseño en `src/css/articulo-contenido.css`, todos limitados a `.articulo-contenido` (no afectan al resto del sitio) y cargados también en el editor con `add_editor_style()` para que la autora vea lo mismo al escribir. El tema no declara `wp-block-styles`. Estilos por bloque: separación vertical de 24 px entre bloques y 16 px entre párrafos seguidos; párrafo `--text-body-l` `--color-tinta`; `h2` del editor con el aspecto de `--text-h3` `--color-marca` (y `h3` con `--text-h4`); lista (`ul`) `--text-body-l` con viñeta "•" y 10 px entre ítems (`ol` con números, mismo ritmo); imagen (`core/image`) a todo el ancho de la columna con `--radius-media`, alto automático (respeta la proporción que sube la autora), y su pie (`figcaption`) centrado en Caption `--color-tinta-suave`; enlaces del texto en `--color-acento` subrayados. Bloques del diseño como estilos de bloque registrados en `inc/editor-estilos.php` (`register_block_style`): "Entradilla" (párrafo, `--text-body-xl` `--color-marca`); "Cita destacada" = estilo del bloque Cita (`core/quote`): fondo `--color-fondo-rosa`, borde izquierdo de 6 px `--color-acento`, `--radius-media`, relleno 28 × 32 px, texto Barlow Condensed Medium Italic 26 px / 1.25 `--color-marca` (valor suelto: no hay token de 26 itálica medium) y la cita (`cite`) en Label S `--color-tinta-suave` con "— " antepuesto por CSS; "Consejo" = estilo del bloque Grupo (`core/group`): fondo `--color-neutro`, `--radius-media`, relleno 24 × 28 px, ícono bombilla de 32 px (`cuerpo-icono-bombilla.svg` copiado a `src/images/`, puesto por CSS, decorativo) a la izquierda con 16 px de separación, primer párrafo como etiqueta (Label `--color-marca`) y el resto en `--text-body`. Además, un patrón de bloques "Consejo de Bárbara" (grupo con estilo Consejo, etiqueta "Consejo de Bárbara" y un párrafo) para insertarlo con un clic. Móvil (propuesto): columna a todo el ancho con margen de 16 px; párrafos y listas a 18 px, entradilla a 20 px, `h2` a 26 px, cita a 22 px con relleno 20 × 20 px, consejo con el ícono arriba del texto.
- **Indicaciones del diseño:**
  - Usar la entrada de la interna por defecto de wordpres donde se puede ingresar texto imagen, y etc de cosas para el articulo.
  - ya que wordpress tiene estilos ya definidos por defecto, sobre escribe eso y usa los estilos que se tiene.
- **Estado:** pendiente

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
|---|---|---|---|---|

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] El cuerpo es `the_content()`: la plantilla no tiene texto fijo del artículo
- [ ] Una entrada con párrafos, `h2`, lista, imagen con pie, cita y consejo se ve igual que el diseño, sin los estilos por defecto de WordPress (márgenes, bordes, tipografía)
- [ ] Los estilos solo afectan a `.articulo-contenido`: el resto del sitio no cambia
- [ ] En el editor aparecen los estilos "Entradilla", "Cita destacada" y "Consejo", y el patrón "Consejo de Bárbara", y el editor los muestra con el aspecto del diseño
- [ ] Las imágenes del contenido no se deforman ni se salen de la columna en móvil

## Sección: escrito-por

- **Origen:** `specs/figma/lectura/articulo-blog/escrito-por.ref.txt` (nodo 4038:22779)
- **Destino:** `template-parts/pages/articulo-blog/escrito-por.php`
- **Grupo:** ninguno
- **Skill:** wp-helper
- **Comportamiento:** `<aside aria-label="Sobre la autora">` sobre fondo `--color-fondo` (`py-5`), con una tarjeta blanca de 1040 px (`--radius-tarjeta`, relleno 32 px, 28 px entre avatar y texto, sin alto fijo). Avatar: el de WordPress (`get_avatar()` del autor de la entrada, 120 px, circular, `alt=""`). La foto sale de Gravatar o de un plugin de avatar local (los dos se enganchan a `get_avatar()`): la imagen tiene que verse, así que el autor debe tener una configurada. "Escrito por" fijo (Caption `--color-tinta-suave`); nombre del autor (`get_the_author()`) como `h2` con el aspecto de `--text-h4` `--color-marca`; descripción: la "Información biográfica" del usuario (`get_the_author_meta('description')`, `--text-body-s`), que se oculta si está vacía. Dos botones con el componente `boton` (12 px entre ellos): "Conoce a Bárbara" (`variante: secundario`, `flecha: false`) a la página Conoce a Bárbara (`/conoce-a-barbara/`, enlace a la página por su slug, no URL escrita a mano) y "Ver soluciones de sueño" (`variante: frambuesa`, con flecha) a la tienda, pendiente: `#` hasta que exista la página de WooCommerce. Textos fijos en la plantilla: "Escrito por", "Conoce a Bárbara", "Ver soluciones de sueño". Móvil (propuesto): la tarjeta pasa a una columna con relleno 24 px, avatar de 88 px arriba a la izquierda, y los botones uno debajo del otro a todo el ancho.
- **Indicaciones del diseño:**
  - Usar la misma imagen que usar el avatar de wordpress.
  - Sobre la descripcion usar Información biográfica que viene por defecto en wordpress.
  - Conce a barbara que lleve a la pagina conoce a barbara
  - ver soluciones de sueño que lleve a shop (como se menciono aun quedara pendiente de implementar)
- **Estado:** pendiente

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
|---|---|---|---|---|

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] El avatar y la biografía cambian al editar el perfil del usuario en WordPress; sin biografía, el párrafo no se imprime
- [ ] "Conoce a Bárbara" lleva a `/conoce-a-barbara/`; "Ver soluciones de sueño" queda en `#` (pendiente)
- [ ] La tarjeta no tiene alto fijo: una biografía larga la hace crecer sin cortar el texto

## Sección: te-puede-interesar

- **Origen:** `specs/figma/lectura/articulo-blog/te-puede-interesar.ref.txt` (nodo 3751:4628)
- **Destino:** `template-parts/pages/articulo-blog/te-puede-interesar.php`, `template-parts/components/post-card.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** fondo `--color-blanco`, `py-22`, contenedor `--container-sitio` (1110 px). Encabezado en una fila: "También te puede interesar" como `h2` con el aspecto de `--text-h3` `--color-marca`, y a la derecha "Ver todo →" (`--text-body-s` `--color-marca`) que lleva al archivo de la categoría de la entrada (`get_category_link()`; si no tiene categoría, a la página del blog). Debajo, 3 tarjetas en fila (`gap` 30 px, 28 px bajo el encabezado) con el componente `post-card`, cada una con su título como `h3`. Contenido (lo conecta wp-query, no esta etapa): las 3 entradas más recientes de la misma categoría, sin la entrada actual; si hay menos de 3, se completan con las entradas más recientes del blog en general (tampoco la actual), y si no hay ninguna otra entrada la sección no se imprime. En esta etapa se maqueta con las 3 tarjetas del diseño como arrays fijos que se pasan a `post-card` (imagen, categoría "Especiales", fecha, lectura, título, resumen, URL `#`), así conectar la consulta es cambiar el array por el loop. Textos fijos de la sección: "También te puede interesar" y "Ver todo →". Tarjetas de ejemplo: "5 señales de que tu bebé está en transición de siestas" (12 de febrero de 2024, 4 min), "Cómo crear el ambiente ideal para dormir" (28 de enero de 2024, 6 min), "Rutinas de noche que sí funcionan" (1 de marzo de 2024, 5 min), las tres con el resumen "Qué esperar cuando tu bebé protesta y cómo acompañarlo con calma, sin culpa." y las fotos `interesar-foto-tarjeta-1/2/3`. Móvil (propuesto): una tarjeta por fila (dos en tablet), "Ver todo →" baja debajo del título, `py-14`.
- **Indicaciones del diseño:**
  - Que muestre los 3 articulos mas recientes de la categoria relacionada
  - Si no hay mas articulos relacionados a la categoria, mostrar los articulos en general.
- **Estado:** pendiente

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
|---|---|---|---|---|

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] Las 3 tarjetas usan el componente `post-card` con `$args`; no hay `WP_Query` en esta etapa
- [ ] "Ver todo →" lleva al archivo de la categoría de la entrada
- [ ] Al conectar la consulta (wp-query): misma categoría, sin la entrada actual, completando con las más recientes del blog hasta 3

## Sección: cta-final

- **Origen:** `specs/figma/lectura/articulo-blog/cta-final.ref.txt` (nodo 3751:4670, instancia del componente "CTA final")
- **Destino:** `single.php`, `template-parts/components/cta-final.php`
- **Grupo:** ninguno
- **Skill:** wp-helper
- **Comportamiento:** `single.php` llama al componente compartido `cta-final` con `titulo` "¿Quieres un plan para tu bebé?", `descripcion` "Encuentra la solución de sueño ideal según su edad." y `boton` "Encuentra tu solución" con destino pendiente `#` (nota de Inicio: el enlace de los CTA finales de las demás páginas queda pendiente). Los textos son iguales en todos los artículos: quedan fijos en `single.php` en esta etapa (propuesta: pasarlos a la página de opciones, ver Notas de página). Fondo, destellos y móvil son los del componente.
- **Estado:** pendiente

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
|---|---|---|---|---|

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] Usa `template-parts/components/cta-final.php` con `$args`, sin copiar su HTML en la página
- [ ] El botón queda en `#` y está anotado como pendiente
