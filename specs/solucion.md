# Página: Solución (landing)

- **Resumen:** Landing de venta de una solución de sueño (tipo de contenido `solucion`): presenta el curso o servicio, resuelve dudas y lleva a comprarlo, regalarlo o canjear un código. Una sola plantilla sirve a las 4 soluciones (Angelita Pro, A Dormir Por Fin, Horarios Ideales, Recién Nacidos Go); cada una arma su página con los bloques de la biblioteca de layouts.
- **Fuente:** `figma:curso-a-dormir-por-fin`
- **Plantilla:** `single-solucion.php`
- **Tipo:** detalle
- **Contenido:** solucion
- **Secciones (en orden):** hero, tarjeta, compra, secciones, relacionados, modal-selecciona-edad, modal-etapa, drawer-carrito
- **Skills usadas:** wp-helper (imágenes, enlaces y textos), wp-accordion (preguntas frecuentes), wp-modal (selector de edad, etapa y drawer del carrito). Después: wp-acf (campos y contenido inicial), wp-woocommerce (precio, carrito y drawer), lógica propia en el plugin `angelitozzz-cursos` (curso, acceso, inscripción) e `inc/woocommerce/` (carrito, etapas, descuento)
- **Grupo ACF:** Solución (landing)
- **Ubicación:** tipo de contenido = solucion
- **Padre:** ninguno
- **Notas de página:**
    - Fuentes: las 4 landings de Figma (`specs/figma/enlaces/curso-angelita-pro.md`, `curso-a-dormir-por-fin.md`, `curso-horarios-ideales.md`, `curso-recien-nacidos-go.md`). `Fuente` nombra la más completa; cada sección dice de qué landing sale su `Origen`.
    - Para todo los cursos, al momento de guardar en el carrito sale este tipo drawer: https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=3752-5054&t=C9Q9IWqYULHocJi2-4
    - En este curso conlleva mas cursos por dentro, por ejemplo, en este caso se rige segun las edades del bebe y cada uno tiene un precio
    - al momento de comprar ahora sale este modal : https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=3653-2355&t=C9Q9IWqYULHocJi2-4
    - En el apartado de etapas el boton que dice mas informacion tambien sale un modal : https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=3752-5964&t=C9Q9IWqYULHocJi2-4
    - En ese figma del modal de etapas sale un video de preview, con los precios de cada uno.
    - (Las cuatro notas anteriores son de Horarios Ideales; la del drawer, de A Dormir Por Fin.)
    - URL: `/soluciones-de-sueno/<slug>/` (archivo `/soluciones-de-sueno/`, ver `soluciones-de-sueno.md`). `h1`: el título del hero. Título SEO "<nombre de la solución> | Angelitozzz"; sin meta descripción en el diseño: se propone la descripción del hero.
    - Estructura: el hero, la tarjeta y la compra son fijos de cada solución; el cuerpo es el contenido flexible `solucion_secciones_bloques` (la clienta agrega, quita y ordena bloques); los relacionados van siempre al final y son automáticos. Ver la tabla "Orden de cada landing" al final.
    - Precio: nunca es un campo. Sale del producto de WooCommerce del curso (precio rebajado = precio de lanzamiento; si hay rebaja se imprime "antes $<precio normal>"). Los precios del diseño ("$67 USD (antes $97)" en tres heros, "$97" en relacionados y en el drawer) son de ejemplo. A Dormir Por Fin cuesta $399 (decisión de la F0; su bloque "Además, incluye" ya lo dice).
    - Horarios Ideales (`tipo_compra` = etapas): la tarjeta del hero muestra solo "Desde $37 USD" (el precio más bajo de sus etapas), sin escala de descuento ni "antes"; la escala va en los modales (decisión del usuario, 2026-11-13).
    - Textos corregidos en el contenido inicial (decisión del usuario, 2026-11-13): subtítulo del hero de Horarios Ideales "Curso Online · 4 a 36 meses" (el diseño dice "Asesoría Personalizada · 4 meses a 4 años", copiado de A Dormir Por Fin) y etiqueta del hero de Recién Nacidos Go "Curso Online" (el diseño dice "Asesoría personalizada").
    - Preguntas frecuentes: el diseño solo trae la respuesta de la primera pregunta de cada landing. Las demás se cargan con la respuesta vacía y la clienta las completa; una pregunta sin respuesta no se muestra.
    - Fondo de página: crema `--color-fondo` con las nubes de `pagina-fondo-nubes.webp` (igual que Inicio y Soluciones de sueño); cada bloque elige su color de fondo (`fondo`).
    - Sin frames móviles en Figma: el comportamiento en móvil de cada sección es una propuesta en su `Comportamiento`.
    - Cintillo, header y footer salen de `header.php` y `footer.php`; la plantilla no los repite.
- **Estado:** aprobado

## Sección: hero

- **Origen:** `specs/figma/lectura/curso-a-dormir-por-fin/hero.ref.txt` (nodo 4013:15172); variantes en `curso-angelita-pro/hero.ref.txt` (4012:13687), `curso-horarios-ideales/hero.ref.txt` (4013:19109) y `curso-recien-nacidos-go/hero.ref.txt` (4013:17861)
- **Destino:** `template-parts/pages/solucion/hero.php`
- **Grupo:** `solucion_hero`
- **Skill:** wp-helper
- **Comportamiento:** dos columnas: a la izquierda etiqueta (componente `etiqueta`, estilo `hero`), título `h1`, subtítulo subrayado, descripción, datos (cajas con borde: ícono + texto), enlace "¿Te regalaron este curso? Canjea tu código" (con ícono de regalo; lleva a la pantalla de canje, que es de otra tanda: queda `#` hasta entonces) y el enlace opcional de WhatsApp. A la derecha, la tarjeta de compra: imagen, precio (de Woo, ver notas), botón principal (`boton` frambuesa: con `tipo_compra` directa o suscripción agrega al carrito y abre el drawer; con etapas abre `modal-selecciona-edad`), botón "Regalar este curso" (`boton` secundario; el flujo de regalo es de otra tanda: queda `#`), la nota y el campo de cupón (un `<form>` con `<label>` "Código de cupón de descuento" visualmente oculto y botón circular de enviar; se conecta en la F4). Fondo: la misma imagen al 45 % de opacidad a la izquierda con el degradado beige encima (decorativa, `alt=""`). Parte del título en itálica (A Dormir Por Fin: "*para ti*"): necesita un estilo de texto `italica` de wp-helper (`install.mjs --add-style`) en la F2. En móvil (propuesta): una columna, la tarjeta de compra bajo el texto, la imagen de fondo se oculta y queda el degradado.
- **Indicaciones del diseño:**
    - nota: tiene 7 dias de prueba gratuita completa.
    - (Nota de A Dormir Por Fin.)
- **Estado:** aprobado

**Campos ACF**

| Campo                        | Tipo     | Descripción                                                                                                                                                                                                | Valor actual                                                                                                                                                         | Requerido |
| ---------------------------- | -------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------- |
| `solucion_hero_etiqueta`     | text     | Etiqueta blanca sobre el título (tipo de solución).                                                                                                                                                      | Asesoría personalizada                                                                                                                                               | no        |
| `solucion_hero_titular`      | textarea | Frase principal del hero (`h1`); el nombre de la solución es el título de la entrada. Para poner una parte en itálica, enciérrala con el marcador del estilo itálica.                                                                                                | A Dormir Por Fin.<br>Tu bebé durmiendo 10 a 12 horas de corrido — con un plan diseñado por Bárbara, para ti.                                                       | sí        |
| `solucion_hero_subtitulo`    | text     | Línea subrayada bajo el título: formato y edades.                                                                                                                                                       | Asesoría Personalizada · 4 meses a 4 años                                                                                                                            | no        |
| `solucion_hero_descripcion`  | textarea | Texto bajo el subtítulo.                                                                                                                                                                                  | Tres semanas de acompañamiento directo conmigo por WhatsApp. Cada duda, cada pregunta, cada noche difícil — respondida por Bárbara personalmente.                  | sí        |
| `solucion_hero_datos`        | repeater | Cajas con un dato corto (duración, con quién, canal). El diseño tiene 2.                                                                                                                                 | —                                                                                                                                                                    | no        |
| `solucion_hero_datos.icono`  | select   | Ícono de la caja. Opciones: reloj, barbara, whatsapp                                                                                                                                                      | reloj                                                                                                                                                                | sí        |
| `solucion_hero_datos.texto`  | text     | Texto de la caja.                                                                                                                                                                                         | 3 semanas                                                                                                                                                            | sí        |
| `solucion_hero_imagen`       | image    | Foto de la tarjeta de compra; también se usa, suave, de fondo del hero. Recomendado: hasta 2000 px en el lado más largo, horizontal.                                                                     | —                                                                                                                                                                    | sí        |
| `solucion_hero_boton`        | text     | Texto del botón de compra (agrega al carrito, o abre el selector de edad en Horarios Ideales).                                                                                                          | Comprar ahora                                                                                                                                                        | sí        |
| `solucion_hero_nota`         | text     | Línea bajo los botones. Si el producto tiene precio rebajado, delante se agrega solo "Precio de lanzamiento (antes $…) ·".                                                                             | 7 días de prueba completa                                                                                                                                            | no        |
| `solucion_hero_regalo`       | true_false | Muestra el botón "Regalar este curso" y el enlace "¿Te regalaron este curso? Canjea tu código".                                                                                                       | sí                                                                                                                                                                   | no        |
| `solucion_hero_whatsapp`     | link     | Enlace opcional bajo el canje (Recién Nacidos Go: "¿Tienes preguntas? Escríbeme por WhatsApp").                                                                                                         | —                                                                                                                                                                    | no        |

Valores de las otras landings (para el contenido inicial): ver "Orden de cada landing".

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] El título es el único `h1` de la página
- [ ] El precio no está escrito en la plantilla: sale del producto de Woo (en la F2, un valor fijo marcado `// woo:`)
- [ ] Con `tipo_compra` = etapas el botón abre `modal-selecciona-edad` y el precio dice "Desde $<mínimo> USD" sin "antes"
- [ ] El campo de cupón tiene `<label>` accesible y el botón de enviar tiene nombre accesible
- [ ] Sin `solucion_hero_regalo` no se imprimen ni el botón de regalo ni el enlace de canje
- [ ] La imagen de la tarjeta usa `loading="eager"` (es la imagen principal)
- [ ] En móvil la tarjeta de compra queda bajo el texto, sin scroll horizontal

## Sección: tarjeta

- **Origen:** `specs/figma/lectura/soluciones-de-sueno/shop.ref.txt` (tarjetas del listado, nodo 4011:11093)
- **Destino:** `template-parts/components/producto-card.php` (la llaman `archive-solucion.php`, la sección `relacionados` y el bloque de Inicio)
- **Grupo:** `solucion_tarjeta`
- **Skill:** ninguna
- **Comportamiento:** no se imprime en la landing: son los datos de la tarjeta de esta solución en el listado `/soluciones-de-sueno/`, en los relacionados y en el drawer. Nombre = título de la entrada; foto = imagen destacada (sin imagen, la tarjeta usa la imagen "not found"). El botón lleva a la landing de la solución.
- **Estado:** aprobado

**Campos ACF**

| Campo                               | Tipo     | Descripción                                                                                                    | Valor actual                                                                                       | Requerido |
| ----------------------------------- | -------- | -------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------- | --------- |
| `solucion_tarjeta_etiqueta`         | text     | Etiqueta de la esquina de la tarjeta (puede incluir el precio como texto).                                   | Asesoría con Bárbara · $399                                                                        | no        |
| `solucion_tarjeta_etiqueta_icono`   | select   | Ícono de la etiqueta. Opciones: ninguno, destellos, estrella                                                  | estrella                                                                                           | no        |
| `solucion_tarjeta_edad`             | text     | Edades, bajo el nombre.                                                                                        | (4 meses a 4 años)                                                                                 | no        |
| `solucion_tarjeta_descripcion`      | textarea | Descripción corta de la tarjeta.                                                                               | Bárbara diseña el plan específico para tu bebé y te acompaña de cerca durante 3 semanas.          | sí        |
| `solucion_tarjeta_beneficios`       | repeater | Viñetas de la tarjeta. El diseño tiene 4.                                                                      | —                                                                                                  | no        |
| `solucion_tarjeta_beneficios.texto` | text     | Una viñeta.                                                                                                    | Plan personalizado para noches y/o siestas                                                         | sí        |
| `solucion_tarjeta_boton`            | text     | Texto del botón de la tarjeta (el destino es siempre esta landing).                                           | Quiero mi asesoría personal                                                                        | sí        |

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] El listado, los relacionados y el drawer leen la tarjeta de estos campos, con el mismo componente `producto-card`

## Sección: compra

- **Origen:** notas de Horarios Ideales y `specs/figma/lectura/curso-horarios-ideales/modal-etapas.ref.txt` (escala "2 cursos $67 · 3 cursos $97")
- **Destino:** `inc/woocommerce/` (carrito, etapas, descuento) y el plugin `angelitozzz-cursos` (curso ↔ producto); sin marcado propio
- **Grupo:** `solucion_compra`
- **Skill:** ninguna
- **Comportamiento:** define qué vende la solución y cómo. `directa`: el botón del hero agrega el producto de Woo de su curso. `etapas`: abre `modal-selecciona-edad` con los cursos del bloque `etapas` y aplica el descuento por cantidad. `suscripcion` (Angelita Pro): la renovación cada 4 meses con 7 días de prueba se define en la F5 (depende de Niubiz). El precio de cada curso es el de su producto de Woo.
- **Estado:** aprobado

**Campos ACF**

| Campo                                  | Tipo         | Descripción                                                                                                         | Valor actual     | Requerido |
| -------------------------------------- | ------------ | ------------------------------------------------------------------------------------------------------------------- | ---------------- | --------- |
| `solucion_compra_cursos`               | relationship | Cursos que vende esta solución (con etapas, uno por etapa). Contenido: curso. Mínimo: 1                    | A Dormir Por Fin | sí        |
| `solucion_compra_tipo`                 | select       | Cómo se compra. Opciones: directa, etapas, suscripcion                                                              | directa          | sí        |
| `solucion_compra_descuentos`           | repeater     | Precio total al comprar varios cursos juntos (solo con etapas). El diseño tiene 2.                                 | —                | no        |
| `solucion_compra_descuentos.cantidad`  | number       | Cantidad de cursos.                                                                                                 | 2                | sí        |
| `solucion_compra_descuentos.total`     | number       | Precio total en USD por esa cantidad.                                                                               | 67               | sí        |

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] Ningún precio de curso es campo ACF: solo el total por cantidad, que es una regla de descuento

## Sección: secciones

- **Origen:** ver cada layout abajo
- **Destino:** `single-solucion.php` (recorre los bloques), `template-parts/pages/solucion/comparacion.php`, `template-parts/pages/solucion/iconos.php`, `template-parts/pages/solucion/lista.php`, `template-parts/pages/solucion/beneficios.php`, `template-parts/pages/solucion/experiencia.php`, `template-parts/pages/solucion/pasos.php`, `template-parts/pages/solucion/galeria.php`, `template-parts/pages/solucion/etapas.php`, `template-parts/pages/solucion/testimonios.php`, `template-parts/pages/solucion/preguntas.php`, `template-parts/accordions/solucion-preguntas.php`, `src/js/modules/solucion-galeria.js`, `src/js/modules/ver-mas.js`
- **Grupo:** `solucion_secciones`
- **Skill:** wp-accordion (layout `preguntas`), wp-helper
- **Comportamiento:** contenido flexible: un archivo por layout, cada uno recibe su fila por `$args`. Todos tienen `fondo` (crema `--color-fondo`, rosa `--color-fondo-rosa`, beige `--color-neutro`, salvia `--color-etiqueta`, blanco) y su decoración fija (nubes, destellos, corazón, "Z"): no es campo. Títulos de bloque en `h2`.
    - `comparacion` (A Dormir Por Fin, `seccion-de-comparacion.ref.txt`, 4013:15285): caja clara con dos columnas y un divisor vertical; cada columna con título `h3` y texto (la izquierda en viñetas). Móvil: una columna, el divisor pasa a horizontal.
    - `iconos` (para-ti de A. Pro 4013:13738, A Dormir 4013:15514, Horarios 4013:19266; porque-este-curso de Recién Nacidos 4013:17922): título, bajada opcional, grilla de 3 columnas de ítems centrados (ícono en mancha beige, título opcional `h3`, texto); la última fila incompleta se centra. Opcional: corazón decorativo detrás del título y el bloque "NO es para ti" (subtítulo `h3` + lista con ✕). Móvil: 1 columna (2 en tablet).
    - `lista` (qué incluye de A. Pro 4013:13796 y A Dormir 4013:15550; qué aprenderás de Horarios 4013:19268 y Recién Nacidos 4013:18034): título, foto a la izquierda (con estrellas y nubes decorativas) y lista con check y separadores a la derecha. Sin foto, la lista se centra (A. Pro no trae foto en el diseño). Con más de 5 ítems se muestran 5 y el botón "Ver más" / "Ver menos" despliega el resto (`ver-mas.js`, botón con `aria-expanded`, la lista completa está en el HTML). Móvil: foto arriba, lista debajo.
    - `beneficios` (cómo funciona de A. Pro 4013:13962; además incluye de A Dormir 4013:16796 y Horarios 4013:19363): caja redondeada (beige o crema) con título, bajada opcional y 2 o 3 columnas (ícono + texto) separadas por líneas verticales. Móvil: columnas apiladas con separador horizontal.
    - `experiencia` (A Dormir Por Fin, `experiencia-exclusiva.ref.txt`, 4013:15567): título, texto centrado (primer y último párrafo en negrita: el texto se escribe con el marcador de negrita de wp-helper), foto horizontal redondeada y botón. Móvil: igual, en una columna.
    - `pasos` (A Dormir Por Fin, `como-funciona.ref.txt`, 4013:15672): antetítulo, título, bajada, 3 tarjetas blancas (foto, "Paso n", título `h3`, texto, etiqueta crema) y botón verde. "Paso n" se numera solo. Móvil: tarjetas apiladas (2 en tablet).
    - `galeria` (conoce por dentro de A. Pro 4013:14037; resumen breve de Recién Nacidos 4013:18035): título, bajada, slider de diapositivas (una a la vez, flechas debajo; `solucion-galeria.js`), texto bajo las flechas y botón de compra opcional. Cada diapositiva es una imagen y, si tiene video de Vimeo, muestra el botón play y reproduce en el mismo lugar (decisión del usuario, 2026-11-13). Con una sola diapositiva no se muestran flechas. El botón hace lo mismo que el botón del hero y puede llevar el precio ("Comprar ahora — $97 USD").
    - `etapas` (Horarios Ideales, `etapas.ref.txt`, 4013:19194): título y 3 tarjetas (foto, título `h3`, texto, botón "Más información" que abre `modal-etapa` con los datos de esa etapa). Cada etapa es un curso. Móvil: tarjetas apiladas.
    - `testimonios` (componente Figma "Testimonio · Destacado": A. Pro 4013:14078, A Dormir 4013:15586, Horarios 4013:19383, Recién Nacidos 4013:18105): título, un testimonio a la vez (cita en itálica + autora), flechas a los lados y puntos; fila de "Z" y nubes decorativas. Usa el componente nuevo `testimonio-destacado` (`_shared.md`).
    - `preguntas` (A. Pro 4013:14164, A Dormir 4013:15724, Horarios 4013:19424, Recién Nacidos 4013:18192): título y acordeón (`<details>`, con wp-accordion); la primera abierta; con más de 5 se muestran 5 y "Ver más" despliega el resto. Una pregunta sin respuesta no se imprime.
- **Indicaciones del diseño:**
    - nota: consideraria que esto sea un campo ACF repetater en que caso se vaya a usar acf.
    - nota: Los testimonios de este curso son independientes.
    - nota: los testimonios de aca tambien son indepenientes
    - nota: las preguntas frecuentes tambien son independientes en este curso.
    - (La primera nota es de "Conoce Angelita Pro por dentro" → layout `galeria`, diapositivas en repetidor. Las de testimonios, de Angelita Pro y A Dormir Por Fin; la de preguntas, de las 4 landings.)
- **Estado:** aprobado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |
| `solucion_secciones_bloques` | flexible_content | Bloques del cuerpo de la landing, en el orden en que se muestran. Botón "Añadir bloque". | — | no |
| `solucion_secciones_bloques.comparacion` | group | Dos columnas: el problema y el resultado. | — | no |
| `solucion_secciones_bloques.comparacion.fondo` | select | Color de fondo. Opciones: crema, rosa, beige, salvia, blanco | blanco | sí |
| `solucion_secciones_bloques.comparacion.titulo_izquierda` | text | Título de la columna izquierda. | ¿Te suena familiar? | sí |
| `solucion_secciones_bloques.comparacion.texto_izquierda` | textarea | Texto de la columna izquierda. Una línea por viñeta. | • Llevas meses sin dormir una noche completa<br>• Tu bebé solo duerme en brazos, con pecho o contigo en la cama<br>• Ya intentaste métodos, consejos, rutinas — nada funciona de forma consistente<br>• Te sientes agotada, confundida y al límite<br>• Quieres una solución real — no otro consejo genérico de internet | sí |
| `solucion_secciones_bloques.comparacion.titulo_derecha` | text | Título de la columna derecha. | Ahora imagina esto. | sí |
| `solucion_secciones_bloques.comparacion.texto_derecha` | textarea | Texto de la columna derecha. | Acuestas a tu bebé en su cuna — y se duerme solo.<br>Duerme 10 a 12 horas de corrido, sin despertarte.<br>Sus siestas son largas, predecibles y en los horarios correctos para su edad.<br>Las tomas nocturnas innecesarias — eliminadas, gradual y respetuosamente.<br>Y tú — descansada, con energía, disfrutando tu maternidad. | sí |
| `solucion_secciones_bloques.iconos` | group | Grilla de ítems con ícono ("es para ti si…", "por qué este curso"). | — | no |
| `solucion_secciones_bloques.iconos.fondo` | select | Color de fondo. Opciones: crema, rosa, beige, salvia, blanco | crema | sí |
| `solucion_secciones_bloques.iconos.corazon` | true_false | Muestra el corazón decorativo detrás del título. | sí | no |
| `solucion_secciones_bloques.iconos.titulo` | text | Título del bloque. | Esta asesoría es para ti si... | sí |
| `solucion_secciones_bloques.iconos.bajada` | textarea | Texto bajo el título. | — | no |
| `solucion_secciones_bloques.iconos.items` | repeater | Ítems de la grilla, de izquierda a derecha. El diseño tiene 7. | — | sí |
| `solucion_secciones_bloques.iconos.items.icono` | select | Ícono. Opciones: reloj-de-arena, bebe-luna, bebe-sol, cuna, bebe-envuelto, biberon, documento, reloj | bebe-luna | sí |
| `solucion_secciones_bloques.iconos.items.titulo` | text | Título del ítem (opcional). | — | no |
| `solucion_secciones_bloques.iconos.items.texto` | textarea | Texto del ítem. | Prefieres trabajar directamente con Bárbara — la persona detrás del método. | sí |
| `solucion_secciones_bloques.iconos.no_titulo` | text | Subtítulo del bloque "NO es para ti" (vacío: no se muestra). | Esta asesoría NO es para ti si... | no |
| `solucion_secciones_bloques.iconos.no_items` | textarea | Lista del bloque "NO es para ti", una línea por ítem (el ✕ se pone solo). | Tu bebé tiene menos de 4 meses o más de 4 años<br>Buscas una solución sin ningún compromiso de tu parte | no |
| `solucion_secciones_bloques.lista` | group | Lista con check y foto ("qué incluye", "qué aprenderás"). | — | no |
| `solucion_secciones_bloques.lista.fondo` | select | Color de fondo. Opciones: crema, rosa, beige, salvia, blanco | crema | sí |
| `solucion_secciones_bloques.lista.titulo` | text | Título del bloque. | ¿Qué incluye tu asesoría? | sí |
| `solucion_secciones_bloques.lista.imagen` | image | Foto a la izquierda (sin foto, la lista se centra). Recomendado: hasta 2000 px en el lado más largo, vertical. | — | no |
| `solucion_secciones_bloques.lista.items` | repeater | Ítems de la lista. Con más de 5 aparece "Ver más". El diseño tiene 5. | — | sí |
| `solucion_secciones_bloques.lista.items.texto` | textarea | Texto del ítem. | Videollamada inicial — una sesión de 1 hora para conocernos y crear el plan ideal para ti y tu bebé de acuerdo a tu estilo. | sí |
| `solucion_secciones_bloques.beneficios` | group | Caja con 2 o 3 columnas de ícono y texto ("además incluye", "cómo funciona"). | — | no |
| `solucion_secciones_bloques.beneficios.fondo` | select | Color de fondo de la sección (la caja es beige sobre crema y crema sobre rosa). Opciones: crema, rosa | crema | sí |
| `solucion_secciones_bloques.beneficios.titulo` | text | Título de la caja. | Además, incluye | sí |
| `solucion_secciones_bloques.beneficios.bajada` | textarea | Texto bajo el título. | Todo por $399 USD · Cupos limitados — trabajo con un número reducido de familias a la vez. | no |
| `solucion_secciones_bloques.beneficios.items` | repeater | Columnas de la caja. El diseño tiene 2. Máximo: 3 | — | sí |
| `solucion_secciones_bloques.beneficios.items.icono` | select | Ícono. Opciones: libro, diamante, video | libro | sí |
| `solucion_secciones_bloques.beneficios.items.texto` | textarea | Texto de la columna. | E-book con guías de horarios — 4 meses a 3 años. Para navegar cada cambio con confianza. | sí |
| `solucion_secciones_bloques.experiencia` | group | Texto centrado con foto y botón. | — | no |
| `solucion_secciones_bloques.experiencia.fondo` | select | Color de fondo. Opciones: crema, rosa, beige, salvia, blanco | rosa | sí |
| `solucion_secciones_bloques.experiencia.titulo` | text | Título del bloque. | La experiencia más exclusiva de Angelitozzz. | sí |
| `solucion_secciones_bloques.experiencia.texto` | textarea | Texto. Para poner una parte en negrita, enciérrala con el marcador de negrita. | Acceso directo a Bárbara. Sin intermediarios. Sin sistemas.<br>Angelita fue entrenada con mi método y puede acompañar a miles de familias simultáneamente. Yo no puedo — y eso es exactamente lo que hace esta asesoría especial. Recibo una cantidad limitada de familias al mismo tiempo para garantizar mi total atención a cada caso.<br>Esta asesoría es para quien quiere trabajar con la persona detrás del método. Para casos complejos que necesitan una mirada experta. Para mamás que prefieren un ser humano de su lado. Y para bebés y niños de hasta 4 años — incluyendo mayores de 2 años y niños que duermen en su propia cama.<br>El resultado: tu bebé durmiendo 10 a 12 horas de corrido por las noches, con siestas largas y sin batalla, en los horarios perfectos para su edad. | sí |
| `solucion_secciones_bloques.experiencia.imagen` | image | Foto bajo el texto. Recomendado: hasta 2000 px en el lado más largo, horizontal. | — | no |
| `solucion_secciones_bloques.experiencia.boton` | link | Botón bajo la foto. | Escríbeme por WhatsApp | no |
| `solucion_secciones_bloques.pasos` | group | Tarjetas numeradas del proceso. | — | no |
| `solucion_secciones_bloques.pasos.fondo` | select | Color de fondo. Opciones: crema, rosa, beige, salvia, blanco | salvia | sí |
| `solucion_secciones_bloques.pasos.antetitulo` | text | Texto chico sobre el título. | Cómo funciona la asesoría | no |
| `solucion_secciones_bloques.pasos.titulo` | text | Título del bloque. | Un proceso de tres pasos — simple, claro y completamente guiado. | sí |
| `solucion_secciones_bloques.pasos.bajada` | textarea | Texto bajo el título. | Cada semana te guiaremos con estrategias claras y prácticas adaptadas al crecimiento y desarrollo de tu bebé. | no |
| `solucion_secciones_bloques.pasos.items` | repeater | Tarjetas, en orden ("Paso 1", "Paso 2"… se numeran solos). El diseño tiene 3. | — | sí |
| `solucion_secciones_bloques.pasos.items.imagen` | image | Foto de la tarjeta. Recomendado: hasta 2000 px en el lado más largo, horizontal. | — | sí |
| `solucion_secciones_bloques.pasos.items.titulo` | text | Título de la tarjeta. | Videollamada inicial | sí |
| `solucion_secciones_bloques.pasos.items.texto` | textarea | Texto de la tarjeta. | Una sesión de 1 hora para conocernos y crear el plan de sueño ideal para ti y tu bebé, de acuerdo a tu estilo. | sí |
| `solucion_secciones_bloques.pasos.items.etiqueta` | text | Etiqueta crema al pie de la tarjeta. | 1 hora · por videollamada | no |
| `solucion_secciones_bloques.pasos.boton` | link | Botón verde bajo las tarjetas. | Escríbeme por WhatsApp para empezar | no |
| `solucion_secciones_bloques.galeria` | group | Slider de imágenes o videos ("por dentro"). | — | no |
| `solucion_secciones_bloques.galeria.fondo` | select | Color de fondo. Opciones: crema, rosa, beige, salvia, blanco | rosa | sí |
| `solucion_secciones_bloques.galeria.titulo` | text | Título del bloque. | Así se ve el curso por dentro. | sí |
| `solucion_secciones_bloques.galeria.bajada` | textarea | Texto bajo el título. | Cada video es corto, claro y lleno de información práctica — para que puedas verlo cuando tu bebé te lo permita y aplicarlo ese mismo día. | no |
| `solucion_secciones_bloques.galeria.diapositivas` | repeater | Diapositivas del slider. El diseño tiene 1. | — | sí |
| `solucion_secciones_bloques.galeria.diapositivas.imagen` | image | Imagen o portada del video. Recomendado: hasta 2000 px en el lado más largo, horizontal. | — | sí |
| `solucion_secciones_bloques.galeria.diapositivas.video` | oembed | Video de Vimeo (opcional). Si lo tiene, la imagen muestra el botón play. | — | no |
| `solucion_secciones_bloques.galeria.texto` | text | Línea bajo las flechas. | Desde tu compra tienes 4 meses para acceder y ver el curso las veces que quieras. | no |
| `solucion_secciones_bloques.galeria.boton` | text | Texto del botón de compra (vacío: sin botón). Hace lo mismo que el botón del hero. | Comprar ahora | no |
| `solucion_secciones_bloques.galeria.boton_precio` | true_false | Agrega el precio al botón ("— $97 USD"). | sí | no |
| `solucion_secciones_bloques.etapas` | group | Etapas por edad, cada una un curso (Horarios Ideales). | — | no |
| `solucion_secciones_bloques.etapas.fondo` | select | Color de fondo. Opciones: crema, rosa, beige, salvia, blanco | crema | sí |
| `solucion_secciones_bloques.etapas.titulo` | text | Título del bloque. | El sueño cambia con cada etapa. Los horarios también. Elige la tuya. | sí |
| `solucion_secciones_bloques.etapas.items` | repeater | Etapas, de menor a mayor edad. El diseño tiene 3. | — | sí |
| `solucion_secciones_bloques.etapas.items.curso` | post_object | Curso de esta etapa (de su producto de Woo sale el precio). Contenido: curso | — | sí |
| `solucion_secciones_bloques.etapas.items.edad` | text | Edad corta, para el selector de edad. | 4 a 9 meses | sí |
| `solucion_secciones_bloques.etapas.items.imagen` | image | Foto de la tarjeta y del selector de edad. Recomendado: hasta 2000 px en el lado más largo, cuadrada. | — | sí |
| `solucion_secciones_bloques.etapas.items.titulo` | text | Título de la tarjeta. | Curso Para bebés de 4 a 9 meses | sí |
| `solucion_secciones_bloques.etapas.items.texto` | textarea | Texto de la tarjeta. | ¿Sientes que tu bebé parece tener sueño todo el día pero duerme poco? Esta etapa es un laberinto de señales confusas. Aprenderás a identificar las ventanas de vigilia correctas, evitar el círculo vicioso de siestas cortas y descubrir el secreto de la última ventana del día para que las tres siestas funcionen como relojito. Todo lo que necesitas para descifrar el horario ideal — sin adivinar. | sí |
| `solucion_secciones_bloques.etapas.items.modal_titulo` | text | Título del modal "Más información". | Para bebés de 4 a 9 meses | sí |
| `solucion_secciones_bloques.etapas.items.modal_texto` | textarea | Texto del modal. | ¿Sientes que tu bebé parece tener sueño todo el día pero igual duerme poco? En este curso aprenderás a priorizar las ventanas de vigilia y a evitar el círculo de siestas cortas y horarios caóticos. Descubre cómo lograr que las tres siestas funcionen como un reloj. | sí |
| `solucion_secciones_bloques.etapas.items.modal_video` | oembed | Video de presentación de Vimeo del modal. | — | no |
| `solucion_secciones_bloques.etapas.items.modal_portada` | image | Portada del video del modal. Recomendado: hasta 2000 px en el lado más largo, horizontal. | — | no |
| `solucion_secciones_bloques.etapas.modal_antetitulo` | text | Texto chico sobre el título de cada modal. | Introducción al curso de Horarios | no |
| `solucion_secciones_bloques.testimonios` | group | Slider de testimonios propios de esta solución. | — | no |
| `solucion_secciones_bloques.testimonios.fondo` | select | Color de fondo. Opciones: crema, rosa, beige, salvia, blanco | beige | sí |
| `solucion_secciones_bloques.testimonios.titulo` | text | Título del bloque. | Lo que dicen las mamás que trabajaron con Bárbara. | sí |
| `solucion_secciones_bloques.testimonios.items` | repeater | Testimonios, uno por diapositiva. El diseño tiene 4. | — | sí |
| `solucion_secciones_bloques.testimonios.items.texto` | textarea | Testimonio (sin comillas: se ponen solas). | Hay un antes y un después de la asesoría. La consistencia y la seguridad que nos dabas permitió que Lara aprenda a dormir sola. | sí |
| `solucion_secciones_bloques.testimonios.items.autora` | text | Quién lo dice. | Bernardita, mamá de Lara (7 meses) | sí |
| `solucion_secciones_bloques.preguntas` | group | Preguntas frecuentes propias de esta solución. | — | no |
| `solucion_secciones_bloques.preguntas.fondo` | select | Color de fondo. Opciones: crema, rosa, beige, salvia, blanco | crema | sí |
| `solucion_secciones_bloques.preguntas.titulo` | text | Título del bloque. | ¡Respondemos a tus preguntas! | sí |
| `solucion_secciones_bloques.preguntas.items` | repeater | Preguntas. Con más de 5 aparece "Ver más". El diseño tiene 7. | — | sí |
| `solucion_secciones_bloques.preguntas.items.pregunta` | text | Pregunta. | ¿Cómo funciona el proceso? | sí |
| `solucion_secciones_bloques.preguntas.items.respuesta` | textarea | Respuesta (vacía: la pregunta no se muestra). | — | no |

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] `single-solucion.php` recorre los bloques con un `get_template_part('template-parts/pages/solucion/<layout>', null, $fila)` por bloque; un layout desconocido no rompe la página
- [ ] Cada layout se ve igual al frame de su `Origen` con el contenido de esa landing
- [ ] `lista` y `preguntas` con más de 5 ítems muestran 5 y el resto con "Ver más"; el botón tiene `aria-expanded` y sin JS se ve la lista completa
- [ ] `galeria`: una diapositiva sin video es solo imagen; con video, el play tiene nombre accesible y el iframe de Vimeo se carga al pulsarlo (no al cargar la página)
- [ ] `preguntas` es un acordeón de wp-accordion (`<details>`), la primera abierta, sin enlaces dentro del título
- [ ] `etapas`: cada "Más información" es un `<button>` que abre `modal-etapa` con los datos de su etapa
- [ ] Los íconos de `iconos` y `beneficios` son SVG del tema con `aria-hidden="true"`; la decoración (nubes, destellos, corazón, "Z") no es campo y tiene `alt=""` o `aria-hidden`
- [ ] En móvil ningún bloque tiene scroll horizontal y las grillas pasan a 1 columna

## Sección: relacionados

- **Origen:** `specs/figma/lectura/curso-angelita-pro/contenido-relacionado.ref.txt` (nodo 4013:14319; igual en A Dormir 4013:16012, Horarios 4013:19577 y Recién Nacidos 4013:18237)
- **Destino:** `template-parts/pages/solucion/relacionados.php`, `template-parts/components/producto-card.php`
- **Grupo:** ninguno
- **Skill:** wp-helper
- **Comportamiento:** siempre al final de la landing, sin campos: 3 soluciones distintas de la actual, automáticas (decisión de la F0), cada una en el componente `producto-card` en versión corta: imagen destacada, etiqueta = `solucion_hero_etiqueta` ("Curso Online", "Asesoría personalizada", "Nuevo" con destellos), nombre, edad = `solucion_tarjeta_edad` + " · $<precio de Woo>" ("Desde" con etapas), sin descripción ni beneficios, y botón fijo "Más información" a su landing. Título `h2` "También podría interesarte..." y bajada fijos del tema. Fondo de lunas decorativo. Con menos de 3 soluciones publicadas muestra las que haya; sin ninguna, la sección no se imprime. En F2 lleva las tarjetas del diseño fijas; la consulta es de wp-query (`angelitozzz_query_related()`). Móvil: 1 columna (2 en tablet).
- **Indicaciones del diseño:**
    - nota: Que rija 3 card de contenido relacionado.
    - (Nota de las 4 landings. En A Dormir Por Fin y Recién Nacidos Go el frame dibuja 2 tarjetas visibles y una tercera oculta; se siguen las 3 de la nota.)
- **Estado:** aprobado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] Nunca aparece la solución actual entre las relacionadas
- [ ] Las tarjetas usan `producto-card`, sin marcado propio de tarjeta

## Sección: modal-selecciona-edad

- **Origen:** `specs/figma/lectura/curso-horarios-ideales/modal-selecciona-edad.ref.txt` (nodo 3653:2355)
- **Destino:** `template-parts/modals/selecciona-edad.php`
- **Grupo:** ninguno
- **Skill:** wp-modal
- **Comportamiento:** solo con `solucion_compra_tipo` = etapas; lo abre el botón de compra del hero y el de `galeria`. Modal de 568 px: título "Selecciona la edad de tu bebé", bajada "Por la compra de dos o tres cursos, consigue un precio especial." (textos fijos del tema), una casilla por etapa del bloque `etapas` (foto, `edad`), y al pie "Total:" con el precio calculado (precio de Woo de cada curso, o el total de `solucion_compra_descuentos` si coincide la cantidad) y la franja "Estás ahorrando $…" (solo si hay ahorro). "Agregar al carrito" agrega los cursos marcados, cierra el modal y abre el drawer; desactivado sin ninguna casilla marcada. Las casillas son `<input type="checkbox">` reales con `<label>` (no un formulario de wp-form: no envía correo). En móvil (propuesta): ocupa el ancho con 16 px de margen.
- **Estado:** aprobado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] Es un modal de wp-modal con `title`, sin `open` en el `<dialog>`
- [ ] El total y el ahorro se recalculan al marcar o desmarcar, y se anuncian (`aria-live="polite"`)
- [ ] Con 2 etapas marcadas el total es $67 y el ahorro $7; con 3, $97 y $14 (con los precios de prueba)

## Sección: modal-etapa

- **Origen:** `specs/figma/lectura/curso-horarios-ideales/modal-etapas.ref.txt` (nodo 3752:5964)
- **Destino:** `template-parts/modals/etapa.php`
- **Grupo:** ninguno
- **Skill:** wp-modal
- **Comportamiento:** uno por etapa (id `etapa-<n>`), lo abre su "Más información". Modal de 620 px: arriba la portada del video (`modal_portada`) con botón play y botón cerrar circular; al pulsar play se carga el video de Vimeo (`modal_video`) en el mismo lugar; sin video, solo la portada. Debajo: antetítulo (`modal_antetitulo` del bloque), título, texto, precio de Woo del curso ("$37 USD") con la escala de `solucion_compra_descuentos` debajo ("2 cursos $67 · 3 cursos $97") y "Agregar al carrito" (agrega ese curso y abre el drawer). Al cerrar, el video se detiene. En móvil (propuesta): ocupa el ancho con 16 px de margen.
- **Estado:** aprobado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] Es un modal de wp-modal con `title`, sin `open` en el `<dialog>`
- [ ] El iframe de Vimeo no se carga hasta pulsar play y se detiene al cerrar
- [ ] La escala de descuento sale de `solucion_compra_descuentos`, no está escrita en la plantilla

## Sección: drawer-carrito

- **Origen:** `specs/figma/lectura/curso-a-dormir-por-fin/drawer-carrito.ref.txt` (nodo 3752:5054)
- **Destino:** `template-parts/modals/carrito.php` (se imprime una vez en `footer.php`, en todo el sitio)
- **Grupo:** ninguno
- **Skill:** wp-modal
- **Comportamiento:** panel lateral derecho de 480 px y alto completo (`drawer` de wp-modal). Se abre al agregar un curso al carrito (desde cualquier landing) y desde el ícono del carrito del header. Contenido: título "Resumen de compra", los cursos del carrito (miniatura, "Curso", nombre, duración del acceso, precio, botón eliminar con nombre accesible), "Te puede interesar también" con una solución sugerida (su tarjeta en versión chica, con "Agregar") y al pie, fijo, "Subtotal" con el monto, "Ir a pagar" (`boton` frambuesa, al checkout) y "Seguir comprando" (cierra el drawer). Los textos son fijos del tema; los datos, del carrito de Woo. En F2 se maqueta con los datos del diseño fijos; se conecta con wp-woocommerce (mini carrito que se actualiza solo). La sugerida se elige igual que los relacionados (una solución que no esté en el carrito). En móvil (propuesta): ancho completo.
- **Estado:** aprobado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] Es un drawer de wp-modal con `title` "Resumen de compra", sin `open`, que devuelve el foco al botón que lo abrió
- [ ] El pie (subtotal y botones) queda fijo abajo y la lista hace scroll si no entra
- [ ] Con el carrito vacío muestra un mensaje y "Seguir comprando", sin subtotal

## Orden de cada landing

Contenido inicial de las 4 soluciones (F3). El "valor actual" de las tablas es el de A Dormir Por Fin; los textos de las demás están en `specs/figma/lectura/<landing>.json` (sección por sección) y se cargan tal cual, salvo las correcciones de las notas de página.

| Solución | Slug | `tipo` | Hero | Bloques (en orden) |
| -------- | ---- | ------ | ---- | ------------------ |
| Angelita Pro | `angelita-pro` | suscripcion | etiqueta "Nuevo" (destellos), título "Angelita Pro", subtítulo "Coach de sueño con IA · 4 a 24 meses", datos: reloj "2 semanas 24/7", whatsapp "WhatsApp"; botón "Quiero a Angelita"; regalo sí; sin foto propia (usa la imagen "not found") | iconos (para-ti, corazón, 5 ítems) · lista (qué incluye, sin foto, 5 ítems) · beneficios (cómo funciona, fondo crema, caja beige, 3: libro, diamante, video) · galeria (conoce por dentro, 1 diapositiva, botón "Quiero a Angelita" sin precio) · testimonios ("¡Historias reales de mamás felices!") · preguntas (5) |
| A Dormir Por Fin | `a-dormir-por-fin` | directa | valores de las tablas; datos: reloj "3 semanas", barbara "Con Bárbara"; regalo sí | comparacion · iconos (para-ti, corazón, 7 ítems + "NO es para ti") · lista (qué incluye, con foto, 5) · beneficios (además incluye, 2) · experiencia · pasos · testimonios · preguntas (7) |
| Horarios Ideales | `horarios-ideales` | etapas | etiqueta "Curso Online", subtítulo corregido "Curso Online · 4 a 36 meses", datos: reloj "3 semanas"; precio "Desde $37 USD"; regalo sí | etapas (3, con sus modales; solo el de 4 a 9 meses tiene texto y portada en el diseño) · iconos (para-ti, sin corazón, 6) · lista (qué aprenderás, fondo rosa, 6) · beneficios (además incluye, fondo rosa, caja crema, 3) · testimonios · preguntas (5); descuentos: 2 → 67, 3 → 97 |
| Recién Nacidos Go | `recien-nacidos-go` | directa | etiqueta corregida "Curso Online", subtítulo "Curso Online · 0 a 12 semanas", sin datos; WhatsApp "¿Tienes preguntas? Escríbeme por WhatsApp"; regalo sí | iconos (porque este curso, con bajada y títulos de ítem, 3) · lista (qué aprenderás, 9) · galeria (así se ve por dentro, botón "Comprar ahora" con precio) · testimonios · preguntas (5) |

Pendiente para revisar (no bloquea la aprobación):

- La nota del hero de los heros de A. Pro, Horarios y Recién Nacidos dice "Precio de lanzamiento (antes $97) · 7 días de prueba completa": con el precio de Woo, la parte del precio se arma sola y el campo guarda "7 días de prueba completa".
- "Semanas adicionales a $27 USD cada una" (lista de Angelita Pro) es texto, no un producto: si se venden semanas extra, se define en la F5.
