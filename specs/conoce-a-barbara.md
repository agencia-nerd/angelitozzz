# Página: Conoce a Bárbara

- **Resumen:** Cuenta la historia de Bárbara, fundadora de Angelitozzz y coach de sueño pediátrico, para generar confianza; desde aquí el visitante va a las soluciones de sueño, lee artículos del blog o llega al CTA final.
- **Fuente:** `figma:conoce-a-barbara`
- **Plantilla:** `pages/template-conoce-a-barbara.php`
- **Tipo:** estática
- **Contenido:** ninguno
- **Secciones (en orden):** hero, reflexion, seccion-blog, cta-final
- **Skills usadas:** wp-helper
- **Grupo ACF:** Conoce a Bárbara
- **Ubicación:** plantilla pages/template-conoce-a-barbara.php
- **Padre:** ninguno
- **Notas de página:**
  - `h1` en el hero ("Hola, soy Bárbara.")
  - título SEO "Conoce a Bárbara | Angelitozzz"
  - sin meta descripción en el diseño
  - fondo de la página `--color-fondo` (el hero y la reflexión no tienen fondo propio: es el del frame de la página)
  - las decoraciones sueltas del hero y de la reflexión (estrellas, destello, luna, corazón) son capas del frame de la página (4017:20868), no de las secciones: no se descargaron y quedan por traer en la etapa C (ver el `Comportamiento` de cada sección)
  - sin frame móvil en Figma: el móvil sigue lo propuesto en el `Comportamiento` de cada sección
- **Estado:** pendiente

## Sección: hero

- **Origen:** `specs/figma/lectura/conoce-a-barbara/hero.ref.txt` (nodo 4017:21076)
- **Destino:** `template-parts/pages/conoce-a-barbara/hero.php`
- **Grupo:** `conoce_a_barbara_hero`
- **Skill:** wp-helper
- **Comportamiento:** contenedor `--container-sitio` con `py-14`. Encabezado centrado: antetítulo (`--text-eyebrow`, `--color-marca-suave`), título `h1` (`--text-display`, `--color-marca`) con el corazón naranja (`hero-corazon.svg`, 46 × 42, decorativo, fijo del tema) a su derecha, y bajada (`--text-body-xl`, `--color-marca`). Debajo, a 191 px del borde superior del bloque, dos columnas con `gap-20` centradas en vertical: texto a la izquierda (flexible) y foto a la derecha (460 × 560, `aspect-[23/28]`, `object-cover`, radio 32 px como valor suelto), que es la imagen principal de la página (`'loading' => 'eager'`, `'alt_fallback'` con el título). Los párrafos son Regular 20 / 1.5 sin estilo de Figma, `--color-tinta`, separados por 18 px (`mb-4.5`); su valor es igual al token `--text-body-l` y lo usan. El texto del campo se separa en párrafos por línea en blanco (`wpautop`). **Decoraciones por traer del frame de la página (4017:20868)**, no están en el código de la sección: estrella rosa arriba a la izquierda (≈ x 330–395, y 0–60 de la sección, asomando sobre el borde superior), destello amarillo chico sobre la bajada (≈ x 995–1015, y 170–190, entre "propósito" y el guion), luna teal a la derecha de la foto (≈ x 1575–1620, y 595–640) y un trazo rosa cortado en el borde superior derecho (≈ x 1530–1545, y 0–10); van como SVG con `alt=""` y `aria-hidden`, en posición absoluta respecto a la sección. Móvil (propuesto): `px-4 py-10`, encabezado centrado con el título en `--text-h3` y el corazón a 32 px, bajada en `--text-body-l`; las columnas pasan a una sola: texto arriba y foto debajo a todo el ancho (máximo 460 px, centrada, misma proporción); las decoraciones se reducen y la luna se oculta.
- **Estado:** pendiente

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
|---|---|---|---|---|
| `conoce_a_barbara_hero_antetitulo` | text | Texto chico sobre el título de la página. Recomendado: ~25 caracteres | Conoce a Bárbara | no |
| `conoce_a_barbara_hero_titulo` | text | Título principal de la página (h1). Se muestra en grande con un corazón a la derecha. Recomendado: ~30 caracteres | Hola, soy Bárbara. | sí |
| `conoce_a_barbara_hero_bajada` | text | Frase bajo el título. Recomendado: ~65 caracteres | Del agotamiento al propósito — mi historia. | no |
| `conoce_a_barbara_hero_texto` | textarea | Historia de Bárbara, a la izquierda de la foto. Deja una línea en blanco entre párrafos. Recomendado: ~920 caracteres | Hola, soy Bárbara — fundadora de Angelitozzz y mamá de dos angelitos que transformaron mi vida de la manera más intensa... y dolorosa.<br><br>Cuando nació mi hija Emma, la maternidad me golpeó con una fuerza que no esperaba. A pesar de estar informada y “lista”, terminé envuelta en noches infinitas, llanto constante, dolor físico y una angustia que no podía nombrar. Me enfrenté a una Depresión Post Parto que me hizo sentir aislada, agotada, perdida. Me sentía una extraña en mi propia historia.<br><br>Y lo más duro: sentía culpa por no estar disfrutando la experiencia que se suponía debía ser la más hermosa de mi vida. | sí |
| `conoce_a_barbara_hero_imagen` | image | Foto de Bárbara a la derecha del texto. Se recorta para llenar el marco vertical. Recomendado: 920 × 1120 px (23:28), mínimo 460 px de ancho | — | sí |

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] El título es el único `h1` de la página
- [ ] La foto carga con `loading="eager"` y mantiene la proporción 23:28 en escritorio y móvil
- [ ] Las cuatro decoraciones del frame de la página están descargadas y ubicadas como en la captura, sin tapar el texto
- [ ] Los párrafos del texto salen separados como en el diseño (18 px entre ellos)

## Sección: reflexion

- **Origen:** `specs/figma/lectura/conoce-a-barbara/reflexion.ref.txt` (nodo 4017:21055)
- **Destino:** `template-parts/pages/conoce-a-barbara/reflexion.php`
- **Grupo:** `conoce_a_barbara_reflexion`
- **Skill:** wp-helper
- **Comportamiento:** contenedor `--container-sitio` con `py-14` y `gap-14` entre bloques. Arriba la cita como `h2` de la sección, centrada, ancho máximo 900 px, `--color-marca`, Medium Italic 40 / 1.15 (valor suelto: `text-[40px] leading-[1.15] font-medium italic`, no hay token). En el diseño la caja de 900 px queda alineada a la izquierda del contenedor; se centra (`mx-auto`) para alinearla con el bloque de misión. Debajo, dos columnas con `gap-20` centradas en vertical: foto a la izquierda (440 × 520, `aspect-[11/13]`, `object-cover`, radio 32 px suelto, `loading="lazy"`, `'alt_fallback'` con la cita) y texto a la derecha (párrafos Regular 20 / 1.5 sin estilo de Figma = `--text-body-l`, `--color-tinta`, separados por 18 px). Al final, bloque centrado de 820 px con `gap-3.5`: misión (`--text-h4`, `--color-marca`), cierre (Regular 20 / 1.5 = `--text-body-l`, líneas separadas 8 px), firma (`--text-h5`, `--color-marca`) con el corazón frambuesa (`reflexion-firma-corazon.svg`, 22 px, decorativo, fijo del tema), cargo (`--text-body-s`, `--color-tinta-suave`) y, 8 px más abajo, el botón compartido `boton` variante `frambuesa` con flecha. El botón lleva a la tienda (Soluciones de sueño), que aún está pendiente: queda con `#` y se anota como pendiente. **Decoraciones por traer del frame de la página (4017:20868)**, no están en el código de la sección: estrella teal de cuatro puntas a la izquierda de la cita (≈ x 298–326, y 97–127 de la sección) y corazón teal entre la foto y el texto (≈ x 885–920, y 565–598); van como SVG con `alt=""` y `aria-hidden`, en posición absoluta respecto a la sección. Móvil (propuesto): `px-4 py-10`; la cita baja a 28 px (valor suelto) con ancho completo; las columnas pasan a una sola, con la foto arriba (máximo 440 px, centrada, misma proporción) y el texto debajo; el bloque de misión a todo el ancho con la misión en `--text-h5`; el botón mantiene su ancho y se centra; la estrella se reduce y el corazón teal se oculta.
- **Indicaciones del diseño:**
  - el boton Conoce mis soluciones de sueño deberia de redirigir al shop que aun esta pendiente.
- **Estado:** pendiente

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
|---|---|---|---|---|
| `conoce_a_barbara_reflexion_cita` | textarea | Cita destacada en itálica al inicio de la sección. Recomendado: ~220 caracteres | “Dormía mal. Mi bebé dormía peor. Y sin descanso no podía pensar, ni decidir, ni vivir... y mucho menos amar mi maternidad como merecía hacerlo.” | sí |
| `conoce_a_barbara_reflexion_imagen` | image | Foto de Bárbara a la izquierda del texto. Se recorta para llenar el marco vertical. Recomendado: 880 × 1040 px (11:13), mínimo 440 px de ancho | — | sí |
| `conoce_a_barbara_reflexion_texto` | textarea | Texto a la derecha de la foto. Deja una línea en blanco entre párrafos. Recomendado: ~1160 caracteres | Pero algo cambió cuando entendí que necesitaba ayuda. Que mi bienestar también importa. Que para cuidar de mi bebé, primero tenía que cuidarme a mí.<br><br>Fue entonces cuando empecé a investigar, a formarme, a estudiar el sueño infantil — no solo como técnica, sino como una herramienta para sanar y empoderar. Y encontré mi vocación.<br><br>Desde 2016 he acompañado a más de 8.000 familias en 12 países. Porque cuando un bebé duerme bien, la madre renace. Y esa transformación es lo que me mueve.<br><br>Hoy soy Coach de Sueño Pediátrico — pero más que eso: soy una mujer comprometida con ayudarte a soltar la culpa, tomar decisiones conscientes y vivir tu maternidad desde el goce, la salud y la conexión real. Mi enfoque es personalizado, respetuoso, basado en ciencia y lleno de humanidad. | sí |
| `conoce_a_barbara_reflexion_mision` | textarea | Frase de la misión, centrada bajo la foto y el texto. Recomendado: ~175 caracteres | Mi misión no es solo enseñarte a mejorar el sueño de tu bebé. Mi misión es devolverte el poder sobre tu maternidad. | sí |
| `conoce_a_barbara_reflexion_cierre` | textarea | Texto centrado bajo la misión. Usa Enter para pasar a otra línea. Recomendado: ~225 caracteres | Tu derecho a descansar. Tu derecho a disfrutar. Tu derecho a ser tú, entera.<br>Aquí empieza tu transformación. Y yo estoy aquí para caminarla contigo. | no |
| `conoce_a_barbara_reflexion_firma` | text | Firma de Bárbara, con un corazón a la derecha. Recomendado: ~40 caracteres | Con cariño infinito, Balli | no |
| `conoce_a_barbara_reflexion_cargo` | text | Texto chico bajo la firma. Recomendado: ~45 caracteres | Tu Coach de Sueño Pediátrico | no |
| `conoce_a_barbara_reflexion_boton` | link | Botón al final de la sección: texto y destino (la tienda de soluciones de sueño) | Conoce mis soluciones de sueño | sí |

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] La cita es el `h2` de la sección y la misión no salta niveles de encabezado
- [ ] El botón usa el componente `boton` (variante `frambuesa`) y apunta a `#`, anotado como pendiente hasta que exista la tienda
- [ ] La foto mantiene la proporción 11:13 sin deformarse en escritorio y móvil
- [ ] Las dos decoraciones del frame de la página están descargadas y ubicadas como en la captura, sin tapar el texto

## Sección: seccion-blog

- **Origen:** `specs/figma/lectura/conoce-a-barbara/seccion-blog.ref.txt` (nodo 4017:21124)
- **Destino:** `template-parts/pages/conoce-a-barbara/seccion-blog.php`
- **Grupo:** `conoce_a_barbara_seccion_blog`
- **Skill:** wp-helper
- **Comportamiento:** fondo `--color-fondo`, contenedor `--container-sitio`, `py-26` y `gap-10`. Título `h2` alineado a la izquierda (`--text-h2`, `--color-marca`). Fila de 3 tarjetas con `gap-7.5` (30 px), cada una con el componente compartido `post-card` (título de tarjeta como `h3`, radio `--radius-media`, borde `--color-neutro`, etiqueta de esquina `etiqueta` estilo `esquina`). Debajo, centrado, el botón compartido `boton` variante `frambuesa` con flecha "Ver más artículos", que lleva a la página del blog (la página de entradas de Ajustes → Lectura, `home.php`). Contenido: el admin elige y ordena los artículos en el campo de relación `conoce_a_barbara_seccion_blog_articulos` (entradas del blog, tipo `post`); si no elige ninguno, la sección muestra los 3 últimos artículos del blog. En esta etapa no se escribe la consulta: las 3 tarjetas son ejemplos fijos del diseño pasados a `post-card` por `$args` (imágenes `blog-tarjeta-1/2/3-imagen.jpg`, categoría "Especiales", fechas "Publicado el 1 de marzo de 2024" / "12 de febrero de 2024" / "28 de enero de 2024", lectura 5 / 4 / 6 min, títulos "Aprendizaje del sueño y las lágrimas", "5 señales de que tu bebé está en transición de siestas", "Cómo crear el ambiente ideal para dormir", el mismo resumen y `url` `#`); la lectura de la relación y la alternativa de los 3 últimos se conectan en el paso de ACF/consultas. Si el título está vacío, se oculta. Móvil (propuesto): `px-4 py-16`, título en `--text-h3`, tarjetas en una columna (dos desde `md`, tres desde `lg`) y botón centrado.
- **Indicaciones del diseño:**
  - que tenga un campo relationalshilp creo que es, que se puede seleccionar y ordenar los articulos que el admi guste, si no pone niguno que salga los 3 ultimos articulos del blog.
- **Estado:** pendiente

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
|---|---|---|---|---|
| `conoce_a_barbara_seccion_blog_titulo` | text | Título de la sección de artículos. Recomendado: ~50 caracteres | Últimos artículos de nuestro blog | no |
| `conoce_a_barbara_seccion_blog_articulos` | relationship | Artículos del blog que se muestran, en el orden elegido (arrástralos para ordenarlos). Si no eliges ninguno, se muestran los 3 últimos artículos. Recomendado: 3 artículos | — | no |
| `conoce_a_barbara_seccion_blog_boton` | link | Botón bajo las tarjetas: texto y destino (la página del blog) | Ver más artículos | no |

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] Las 3 tarjetas usan el componente `post-card` con `$args`, sin markup propio de tarjeta
- [ ] No hay `WP_Query` ni lectura de la relación en esta etapa: las tarjetas son los ejemplos fijos del diseño
- [ ] "Ver más artículos" usa el componente `boton` y lleva a la página del blog
- [ ] En móvil las tarjetas pasan a una columna sin scroll horizontal

## Sección: cta-final

- **Origen:** `specs/figma/lectura/conoce-a-barbara/cta-final.ref.txt` (nodo 4017:21176, instancia del componente Figma "CTA final" 3713:17003)
- **Destino:** `pages/template-conoce-a-barbara.php`, `template-parts/components/cta-final.php`
- **Grupo:** `conoce_a_barbara_cta_final`
- **Skill:** wp-helper
- **Comportamiento:** usa el componente compartido `cta-final` (`_shared.md`), llamado desde la plantilla con `get_template_part('template-parts/components/cta-final', null, $args)` y los argumentos `titulo`, `descripcion` (vacía en esta página: el componente no la muestra) y `boton`. Fondo `--color-fondo-rosa`, título `h2` centrado (`--text-h2`, `--color-marca`), botón `boton` variante `frambuesa` con flecha, y los destellos y trazos blancos decorativos del componente (`cta-final-*.svg`, el destello grande sobresale por debajo de la sección). El botón "Encuentra tu solución" aún no tiene destino: queda con `#` y se anota como pendiente. Móvil: el del componente compartido (título en `--text-h3`, `px-4`, decoraciones reducidas u ocultas).
- **Estado:** pendiente

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
|---|---|---|---|---|
| `conoce_a_barbara_cta_final_titulo` | textarea | Título del bloque de cierre de la página. Recomendado: ~135 caracteres | La maternidad ya tiene suficientes sacrificios. El sueño no tiene que ser uno de ellos. | sí |
| `conoce_a_barbara_cta_final_descripcion` | textarea | Texto opcional bajo el título. Si se deja vacío no se muestra. Recomendado: ~150 caracteres | — | no |
| `conoce_a_barbara_cta_final_boton` | link | Botón del bloque de cierre: texto y destino | Encuentra tu solución | sí |

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] La sección es una llamada al componente `cta-final` con `$args`, sin markup propio en la plantilla
- [ ] Sin descripción no queda un párrafo vacío
- [ ] El botón apunta a `#`, anotado como pendiente
