# Página: Blog

- **Resumen:** Portada del blog: presenta los consejos de Bárbara y muestra las últimas entradas agrupadas por categoría. El visitante busca un tema, elige una categoría o entra a un artículo.
- **Fuente:** `figma:blog`
- **Plantilla:** `home.php`
- **Tipo:** listado
- **Contenido:** post
- **Secciones (en orden):** hero-blog, categoria-especiales, categoria-bebes-0-4-meses, categoria-bebes-4-24-meses, categoria-bebes-24-meses-4-anos
- **Skills usadas:** ninguna en el maquetado. Después: wp-acf (campos de la página), wp-query (las 3 entradas de cada categoría, el conteo, la búsqueda y la paginación del archivo de categoría)
- **Grupo ACF:** Blog
- **Ubicación:** página de entradas (posts page)
- **Padre:** ninguno
- **Notas de página:**
    - cada seccion es una categoria nueva.
    - cada seccion tiene la opcion de ver todos.
    - Al precionar ver todos debe de filtrar automaticamente solo los post de esa categoria.
    - usar las entradas que viene por defecto de wordpress e implementarlo con las skills recomendadas.
    - `h1`: el título de la página Blog (el nombre que se le pone en Páginas, hoy "Blog"), en el hero; no es un campo. Los títulos de cada categoría son `h2` y los de las tarjetas `h3`.
    - Título SEO: "Blog | Angelitozzz" (lo arma WordPress con el título de la página). El diseño no trae meta descripción: se propone usar la bajada del hero.
    - Las entradas son las nativas de WordPress (`post`): título, extracto, fecha, autor e imagen destacada no son campos. El tiempo de lectura se calcula del contenido (palabras / 200, redondeado hacia arriba, mínimo 1), sin campo.
    - Las 4 secciones de categoría del diseño son la misma estructura repetida: una sola plantilla (`template-parts/pages/blog/categoria.php`) que `home.php` llama una vez por cada categoría elegida en `blog_categorias_secciones`, en ese orden. El admin decide qué categorías aparecen y en qué orden; el diseño muestra 4 de ejemplo.
    - `category.php` ("Ver todos" de cada categoría) reutiliza el hero (con el `h1` = nombre de la categoría y su pastilla activa) y el componente `blog-listado` (grilla de `post-card` de la consulta principal, con paginación); no necesita spec propio.
    - Con búsqueda (`?q=`), `home.php` cambia las secciones por `blog-listado` con los resultados. Sin búsqueda imprime las mismas zonas (`total`, `resultados`, `paginacion`) para que el JS de wp-query pase de una vista a otra sin recargar.
    - Las 3 entradas de cada sección son una lista secundaria (`get_posts`, 3 más recientes de la categoría), no la consulta principal. Los datos de cada tarjeta salen de `angelitozzz_post_card_args()` (`inc/helpers/entradas.php`).
    - La etiqueta de esquina de cada tarjeta muestra la categoría real de la entrada (el diseño pone "Especiales" en todas como relleno).
    - Corrección de texto: "Del cuna a la cama" se escribe "De la cuna a la cama".
- **Al filtrar:** botón aplicar
- **Paginación:** números
- **Por página:** 12
- **Filtros en móvil:** igual
- **Estado:** aplicado

`Al filtrar`: las pastillas son enlaces (cada una abre el archivo de su categoría) y la búsqueda se envía con el botón "Buscar": nada cambia sin una acción. `Paginación` y `Por página` valen para `category.php` y los resultados de búsqueda (grilla de 3 columnas, 4 filas); la portada del blog no pagina: cada sección muestra 3 entradas y "Ver todos".

**Filtros**

| Filtro      | Tipo      | Fuente                                     | Varios | Conteo  | Vista                                                                            |
| ----------- | --------- | ------------------------------------------ | ------ | ------- | -------------------------------------------------------------------------------- |
| `categoria` | taxonomía | `category`                                 | no     | ninguno | pastillas (enlaces al archivo de cada categoría; "Todos" lleva a la página Blog) |
| `q`         | búsqueda  | título y contenido (búsqueda de WordPress) | —      | —       | campo con lupa y botón "Buscar"                                                  |

## Sección: hero-blog

- **Origen:** `figma:blog` nodo 3751:4324 (`specs/figma/lectura/blog/hero-blog.ref.txt`)
- **Destino:** `template-parts/components/hero-blog.php`, `home.php`, `category.php`
- **Grupo:** `blog_hero`
- **Skill:** ninguna
- **Comportamiento:** Fondo `--color-fondo-rosa`, contenido centrado en `--container-sitio`, `py-24` (96) y `gap-4` (16). Antetítulo `--text-eyebrow` en `--color-marca-suave`; `h1` "Blog" en `--text-display` y `--color-marca`; bajada `--text-body-xl` en `--color-marca`, centrada. Buscador y pastillas son un solo `<form method="get" role="search" data-query="filtros">` con `action` = URL de la página Blog: `<label>` "Buscar en el blog" (`sr-only`) unido al `<input type="search" name="q">` con el placeholder fijo "Busca un tema: siestas, regresión, horarios…", la lupa (`hero-buscador-icono.svg`, `alt=""`, `aria-hidden`) y un `<button type="submit">` "Buscar" frambuesa (`--color-acento`, texto `--color-blanco`, `--text-label-s`, `rounded-full`, `px-5 py-2.5`). La caja del buscador: fondo `--color-blanco`, borde `--color-neutro`, `rounded-full`, `pl-5.5 pr-2 py-2`, `gap-3`, ancho `w-full max-w-[620px]`. Bajo el buscador (`pt-3`), un `<nav aria-label="Categorías del blog">` con una lista de enlaces-pastilla (`--text-label-s`, `rounded-full`, `px-4.5 py-2`, `gap-2.5`): "Todos" (texto fijo) lleva a la página Blog y cada categoría a su archivo (`get_category_link`), que filtra solo las entradas de esa categoría. La pastilla de la página actual va con fondo `--color-marca`, texto `--color-fondo` y `aria-current="page"` ("Todos" en `home.php`, la categoría en `category.php`); las demás fondo `--color-blanco` y texto `--color-marca`. Las pastillas listan todas las categorías con al menos una entrada (sin "Sin categoría"), en orden alfabético, desde las opciones del filtro `categoria` de wp-query; son navegación normal (`data-query-ignorar`) porque abren otra página. Antetítulo, `h1` y bajada van en una zona `data-query="cabecera"`: al buscar sin recargar desde una categoría, el título pasa al del Blog como en una carga normal. Con `q` en la URL, el input muestra el texto buscado. Es componente porque lo usan `home.php` y `category.php`: recibe `antetitulo`, `titulo`, `bajada` y `activa` (slug de la categoría actual o vacío); el placeholder, el botón "Buscar" y la pastilla "Todos" son textos fijos del componente (traducibles con el text domain). En `home.php` el `titulo` es el título de la página Blog; en `category.php` el `titulo` es el nombre de la categoría, la `bajada` su descripción (o la del Blog si está vacía) y el resto se lee de los campos de la página Blog. Decoración (SVG, `alt=""`, `aria-hidden`, posición absoluta sin tapar el contenido): estrella blanca grande arriba a la izquierda (`hero-estrella-grande.svg`, 60 × 61) y destello dorado abajo a la derecha (`hero-destello.svg`, 21 × 21 dentro de una caja de 40); la estrella pequeña (`hero-estrella-pequena.svg`) es del mismo color que el fondo y no se ve en el diseño: no se incluye. Valores sueltos: sombra del buscador `shadow-[0_6px_20px_0_rgba(36,69,74,0.08)]`, ancho del buscador `max-w-[620px]`. Móvil (sin frame): `px-4 py-16`; la bajada se parte en varias líneas (sin `whitespace-nowrap`); el buscador ocupa todo el ancho con el botón dentro; las pastillas quedan en una fila con desplazamiento horizontal (`overflow-x-auto`, sin salto de línea, la activa visible al cargar) y desde `md` se centran y saltan de línea si no caben; la estrella grande se reduce a la mitad y el destello se oculta bajo `md`. Antetítulo y bajada son opcionales: si el campo está vacío la plantilla no imprime esa línea.
- **Indicaciones del diseño:**
    - usar las skills recomendadas para este formato.
- **Estado:** aplicado
- **ACF:** conectado
- **Query:** conectado

La indicación se aplica así: el buscador y las pastillas son filtros de listado (marcado con `<form method="get">` y `data-query`, nunca `wp-form`), los textos pasan a ACF con wp-acf y la búsqueda, el listado de categorías y la pastilla activa los conecta wp-query.

**Campos ACF**

| Campo                            | Tipo     | Descripción                                                                                                            | Valor actual                                                      | Requerido |
| -------------------------------- | -------- | ---------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------- | --------- |
| `blog_hero_antetitulo`           | text     | Texto chico sobre el título del blog.                                                      | Recursos gratuitos                                                | no        |
| `blog_hero_bajada`               | textarea | Frase bajo el título. También se usa en el archivo de una categoría sin descripción.      | Consejos de Bárbara para que tu bebé duerma mejor, en cada etapa. | no        |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] Buscador y pastillas están en un `<form method="get" role="search" data-query="filtros">`; el campo es `name="q"` con su `<label>` (`sr-only`) y no se usa `wp-form` ni `WP_Query`
- [x] Las pastillas son `<a href>` dentro de `<nav aria-label="Categorías del blog">`: "Todos" lleva a la página Blog y cada categoría a su archivo; la actual lleva `aria-current="page"` y el estilo activo
- [x] El componente vive en `template-parts/components/hero-blog.php` y lo usan `home.php` y `category.php` con `$args`
- [x] En móvil (375 px) el buscador ocupa el ancho sin desbordar y las pastillas se desplazan en horizontal sin crear scroll en la página
- [x] La estrella pequeña invisible no está en el HTML

## Sección: categoria-especiales

- **Origen:** `figma:blog` nodo 3751:4348 (`specs/figma/lectura/blog/categoria-especiales.ref.txt`)
- **Destino:** `template-parts/pages/blog/categoria.php`, `home.php`
- **Grupo:** `blog_categorias`
- **Skill:** ninguna
- **Comportamiento:** Es la primera de las secciones de categoría y documenta la estructura que comparten las cuatro: una sola plantilla, `categoria.php`, que `home.php` llama una vez por cada fila de `blog_categorias_secciones`, en el orden del repetidor (por eso este bloque lleva el único campo de las cuatro y los demás tienen `Grupo: ninguno`). Recibe por `$args`: `nombre` (nombre de la categoría), `slug`, `total` (cantidad de entradas publicadas), `url` (archivo de la categoría) y `entradas` (hasta 3 arrays con los argumentos de `post-card`). Cada sección es un `<section aria-labelledby="categoria-<slug>">`: cabecera en fila (`justify-between`, `items-center`) con el `h2` del nombre (`--text-h3`, `--color-marca`), la pastilla de conteo (`--color-neutro`, texto `--color-marca`, `--text-label-s`, `rounded-full`, `px-3 py-1`, `gap-3` con el título; "1 artículo" en singular, "N artículos" en plural) y el enlace "Ver todos" (`--text-label`, `--color-acento`, flecha `flecha-ver-todos.svg` de 18 px con `alt=""`, `gap-2`) al archivo de la categoría, que muestra solo sus entradas; el enlace lleva el nombre completo para lectores de pantalla ("Ver todos" + `sr-only` "los artículos de Especiales"). Debajo (`gap-7`, 28 px), una grilla de 3 `post-card` (`gap-7.5`, 30 px) con las 3 entradas más recientes de la categoría; la etiqueta de esquina de cada tarjeta es la categoría real de la entrada. Si la categoría tiene menos de 3 entradas se muestran las que hay; si no tiene ninguna, la sección no se imprime. Las secciones van dentro de un contenedor `data-query="resultados"` con fondo `--color-fondo`, `pt-12 pb-16`, y cada sección `py-10` en `--container-sitio`: así la primera queda a 88 px del hero, entre secciones 80 px y la última a 104 px del pie, como en el diseño. Maquetado: `home.php` llama a `categoria.php` con las 4 categorías del diseño y sus tarjetas como arrays fijos (títulos, fechas e imágenes de `specs/figma/lectura/blog/assets/`). Móvil (sin frame): `px-4`; la cabecera salta de línea si no cabe (título y conteo arriba, "Ver todos" debajo alineado a la izquierda); la grilla es de 1 columna, 2 desde `md` y 3 desde `lg`. No hay valores sueltos.
- **Estado:** aplicado
- **ACF:** conectado
- **Query:** conectado

**Campos ACF**

| Campo                                 | Tipo     | Descripción                                                                                                                                                                                                                                                                                          | Valor actual | Requerido |
| ------------------------------------- | -------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------ | --------- |
| `blog_categorias_secciones`           | repeater | Categorías que se muestran como secciones en la página Blog, en este orden. Cada una muestra sus 3 artículos más recientes, cuántos artículos tiene y "Ver todos". El diseño trae 4: Especiales, Bebés 0 – 4 meses, Bebés 4 – 24 meses y Bebés 24 meses – 4 años. Si queda vacío, solo se ve el hero | Especiales   | no        |
| `blog_categorias_secciones.categoria` | taxonomy | Categoría de entradas de esta sección. Su nombre es el título de la sección. Taxonomía: category                                                                                                                                                                                                     | Especiales   | sí        |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] Las 4 secciones del diseño salen de la misma plantilla `template-parts/pages/blog/categoria.php` llamada con `$args`; no hay cuatro plantillas
- [x] Cada sección es un `<section>` con `aria-labelledby` apuntando a su `h2`
- [x] Las tarjetas son `template-parts/components/post-card.php` con `$args`, sin `WP_Query` en el maquetado
- [x] "Ver todos" es un `<a href>` al archivo de la categoría con nombre accesible completo
- [x] El contenedor de las secciones lleva `data-query="resultados"`
- [x] Separaciones como el diseño: 88 px tras el hero, 80 px entre secciones y 104 px antes del pie
- [x] En móvil (375 px) las tarjetas van en 1 columna y la cabecera no desborda

## Sección: categoria-bebes-0-4-meses

- **Origen:** `figma:blog` nodo 3751:4399 (`specs/figma/lectura/blog/categoria-bebes-0-4-meses.ref.txt`)
- **Destino:** `template-parts/pages/blog/categoria.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** Misma plantilla y comportamiento que `categoria-especiales` (ver ese bloque); no tiene campos propios: es la segunda fila de `blog_categorias_secciones` (categoría "Bebés 0 – 4 meses"). Datos de ejemplo del maquetado: conteo "7 artículos"; tarjetas "Los primeros días: qué esperar del sueño" (1 de marzo de 2024, 5 min), "Día y noche: cómo ayudarle a diferenciarlos" (12 de febrero de 2024, 4 min) y "Envolver o no envolver: guía práctica" (28 de enero de 2024, 6 min), con las imágenes `bebes-0-4-tarjeta-1..3-foto`.
- **Estado:** aplicado
- **ACF:** conectado
- **Query:** conectado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] Se imprime con la misma llamada a `categoria.php` que las demás categorías, cambiando solo los `$args`

## Sección: categoria-bebes-4-24-meses

- **Origen:** `figma:blog` nodo 3751:4450 (`specs/figma/lectura/blog/categoria-bebes-4-24-meses.ref.txt`)
- **Destino:** `template-parts/pages/blog/categoria.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** Misma plantilla y comportamiento que `categoria-especiales` (ver ese bloque); no tiene campos propios: es la tercera fila de `blog_categorias_secciones` (categoría "Bebés 4 – 24 meses"). Datos de ejemplo del maquetado: conteo "7 artículos"; tarjetas "Regresión de los 4 meses: qué está pasando" (1 de marzo de 2024, 5 min), "Ventanas de vigilia según la edad" (12 de febrero de 2024, 4 min) y "Despertares nocturnos: causas comunes" (28 de enero de 2024, 6 min), con las imágenes `bebes-4-24-tarjeta-1..3-foto`.
- **Estado:** aplicado
- **ACF:** conectado
- **Query:** conectado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] Se imprime con la misma llamada a `categoria.php` que las demás categorías, cambiando solo los `$args`

## Sección: categoria-bebes-24-meses-4-anos

- **Origen:** `figma:blog` nodo 3751:4501 (`specs/figma/lectura/blog/categoria-bebes-24-meses-4-anos.ref.txt`)
- **Destino:** `template-parts/pages/blog/categoria.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** Misma plantilla y comportamiento que `categoria-especiales` (ver ese bloque); no tiene campos propios: es la cuarta fila de `blog_categorias_secciones` (categoría "Bebés 24 meses – 4 años") y la última del diseño (104 px hasta el pie, que da el contenedor). Datos de ejemplo del maquetado: conteo "7 artículos"; tarjetas "De la cuna a la cama: cuándo y cómo" (corregido; el diseño dice "Del cuna a la cama"; 1 de marzo de 2024, 5 min), "Pesadillas y terrores nocturnos" (12 de febrero de 2024, 4 min) y "Rutinas de noche que sí funcionan" (28 de enero de 2024, 6 min), con las imágenes `bebes-24m-4a-tarjeta-1..3-foto`.
- **Estado:** aplicado
- **ACF:** conectado
- **Query:** conectado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] Se imprime con la misma llamada a `categoria.php` que las demás categorías, cambiando solo los `$args`
- [x] El título de ejemplo dice "De la cuna a la cama: cuándo y cómo"
