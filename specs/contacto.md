# Página: Contacto

- **Resumen:** Página para escribirle a Angelitozzz: el visitante deja su consulta en el formulario de contacto o, si prefiere, escribe directo al correo o por WhatsApp desde la tarjeta de abajo.
- **Fuente:** `figma:contacto`
- **Plantilla:** `pages/template-contacto.php`
- **Tipo:** estática
- **Contenido:** ninguno
- **Secciones (en orden):** seccion-de-contacto, seccion-de-cta
- **Skills usadas:** wp-form (seccion-de-contacto), wp-helper
- **Grupo ACF:** Contacto
- **Ubicación:** plantilla pages/template-contacto.php
- **Padre:** ninguno
- **Notas de página:**
    - `h1` "Contacto" en seccion-de-contacto (es el título de la sección)
    - título SEO "Contacto | Angelitozzz"
    - sin meta descripción en el diseño
    - fondo de la página `--color-fondo` (las dos secciones no tienen fondo propio)
    - el correo y el WhatsApp de la tarjeta de abajo salen de Ajustes del sitio (`opciones_contacto_email`, `opciones_contacto_whatsapp`), no de campos de esta página
    - sin frame móvil en Figma: el móvil sigue lo propuesto en el `Comportamiento` de cada sección
- **Estado:** pendiente

## Sección: seccion-de-contacto

- **Origen:** `specs/figma/lectura/contacto/seccion-de-contacto.ref.txt` (nodo 4020:22112)
- **Destino:** `template-parts/pages/contacto/seccion-de-contacto.php`, `template-parts/forms/contacto.php` (lo escribe wp-form)
- **Grupo:** `contacto_seccion_de_contacto`
- **Skill:** wp-form
- **Comportamiento:** la sección solo imprime el encabezado e incluye el formulario; el formulario no tiene maquetado propio: lo escribe wp-form con el aspecto del diseño. Encabezado centrado (columna de 392 px, `gap-11.5`): título `h1` (`--text-display`, `--color-marca`) y subtítulo (`--text-body-s`, `--color-tinta`; si está vacío no se imprime). Sección con `py-22` y una columna centrada de 591 px; entre el encabezado y el formulario `gap-19` (76 px). **Formulario "contacto" (wp-form)**, campos en este orden con 34 px entre filas (`gap-8.5`): `nombre` (texto, requerido, etiqueta "Nombres y Apellidos:"); en una fila de dos columnas de 283 px (`justify-between`), `email` (email, requerido, etiqueta "Email:") y `celular` (teléfono, requerido, etiqueta "Celular (con código de país):") con un selector de país que el visitante puede cambiar (bandera y código, por defecto 🇵🇪 +51, se guarda el número completo con su código; el mismo selector que el campo de WhatsApp de `modal-coach`); `curso` (select, requerido, etiqueta "Seleccione curso" en lugar del "¿Sobre qué quieres escribirnos?" del diseño, con la flecha de desplegable a la derecha, `contacto-icono-desplegable.svg` de 20 px, decorativa): **las opciones son los cursos publicados** (productos de WooCommerce, ver `soluciones-de-sueno.md`) y un curso nuevo aparece solo en la lista, sin tocar el formulario; mientras Woo no esté conectado se maquetan con los 4 cursos del diseño (Angelita Pro, A Dormir Por Fin, Recién Nacidos Go, Horarios Ideales) y una primera opción vacía "Seleccione curso"; `mensaje` (área de texto, requerido, etiqueta "Mensaje:", alto mínimo 69 px sobre la línea); casilla `privacidad` (requerida) "He leído y acepto las políticas de privacidad" donde "políticas de privacidad" enlaza a la página de privacidad de WordPress (`get_privacy_policy_url()`, Ajustes → Privacidad; si no hay, `#` pendiente) y se abre en la misma pestaña; botón `<button type="submit">` "Enviar mensaje" con el estilo del botón `frambuesa` de `_shared.md` (mismas clases y flecha que el componente `boton`, que solo dibuja enlaces). Cada campo es una línea inferior de 1 px `--color-tinta` (`border-b`, sin caja ni fondo; las líneas SVG del diseño no se usan), con su `<label>` visible en `--text-body-s` `--color-tinta` a 9 px de la línea (`gap-2.25`); el texto que escribe el visitante usa el mismo estilo. La casilla es de 14 × 14 px con borde de 1 px `--color-tinta` y la marca en `--color-tinta`, con 15 px hasta su texto (`gap-3.75`). El enlace de privacidad va en `--color-tinta` como el resto del texto, subrayado para que se distinga (en el diseño no tiene color propio). Al enviar: un correo a `opciones_contacto_email` (Ajustes del sitio; si está vacío, al correo del administrador) con `Reply-To` del visitante, que hoy llega a Mailpit y luego sale por Fluent SMTP (el tema solo usa `wp_mail`); **y cada envío se guarda en el admin** (nombre, email, celular, curso, mensaje, fecha). Mensajes de éxito y de error en una región `aria-live` bajo el botón (el diseño no los muestra: texto propuesto "¡Gracias! Te responderemos pronto."). Valores sueltos: columna `max-w-[591px]`, encabezado `max-w-[392px]`, alto del mensaje `min-h-[69px]`. Móvil (propuesto): `px-4 py-16`, la columna ocupa todo el ancho; título en `--text-h1`, `gap-12` entre encabezado y formulario; email y celular pasan a una columna (dos desde `md`); las etiquetas siguen arriba de la línea y la casilla de privacidad salta de línea si no cabe; el botón se centra con su ancho natural.
- **Indicaciones del diseño:**
    - usar la skill wp form, y los correos que lleguen al mailpit, luego se usara el plugin Fluent SMPT.
- **Estado:** pendiente

**Campos ACF**

| Campo                                    | Tipo     | Descripción                                                                          | Valor actual                                                           | Requerido |
| ---------------------------------------- | -------- | ------------------------------------------------------------------------------------ | ---------------------------------------------------------------------- | --------- |
| `contacto_seccion_de_contacto_titulo`    | text     | Título principal de la página (h1), sobre el formulario. Recomendado: ~15 caracteres | Contacto                                                               | sí        |
| `contacto_seccion_de_contacto_subtitulo` | textarea | Frase bajo el título que invita a llenar el formulario. Recomendado: ~110 caracteres | ¡Introduce tu información aquí y pronto me pondré en contacto contigo! | no        |

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] El título es el único `h1` de la página
- [ ] El selector de país del celular se puede cambiar, empieza en 🇵🇪 +51 y el número se guarda con su código
- [ ] Cada envío llega a Mailpit y queda guardado en el admin (nombre, email, celular, curso, mensaje, fecha)
- [ ] El enlace "políticas de privacidad" lleva a la página de privacidad de WordPress
- [ ] Las líneas de los campos y la casilla usan `--color-tinta`, sin el `#000000` suelto del diseño

## Sección: seccion-de-cta

- **Origen:** `specs/figma/lectura/contacto/seccion-de-cta.ref.txt` (nodo 4020:22179)
- **Destino:** `template-parts/pages/contacto/seccion-de-cta.php`
- **Grupo:** `contacto_seccion_de_cta`
- **Skill:** wp-helper
- **Comportamiento:** sección con `py-22` y una tarjeta centrada de `--container-sitio` de ancho: fondo `--color-neutro`, `--radius-tarjeta`, `p-16`, `gap-3.5`, contenido centrado, `overflow-hidden` y `relative`. Dentro: título `h2` (`--text-h2`, `--color-marca`), párrafo (`--text-body-l`, `--color-tinta`, centrado), el correo como enlace `mailto:` (`--text-h3`, `--color-marca`, subrayado al pasar el mouse) y, 8 px más abajo (`mt-2`, el marco vacío de 10 × 8 del diseño), el componente `boton` variante `frambuesa` con flecha y `texto` "Escríbenos por WhatsApp". El correo sale de `opciones_contacto_email` (Ajustes del sitio, no es campo de esta página): `href` `mailto:` armado con `esc_url()` y el texto con `esc_html()`, los dos pasados por `antispambot()`; si el campo está vacío, la línea no se imprime. El botón arma `https://wa.me/<número>?text=<mensaje>` con `opciones_contacto_whatsapp` y `opciones_contacto_whatsapp_mensaje` (Ajustes del sitio, mensaje codificado con `rawurlencode`) y se abre en una pestaña nueva por el helper de enlaces (pone `rel` y el aviso `sr-only`); si no hay número, el botón no se imprime. Si el párrafo está vacío no se imprime. Decoración (SVG fijos del tema, `alt=""`, `aria-hidden`, posición absoluta, sin tapar el texto): estrella de contorno teal (`cta-estrella-contorno-inferior.svg`, 121 × 78) abajo a la izquierda, asomando unos 30 px fuera del borde izquierdo y cortada por la tarjeta; destello dorado (`cta-destello-dorado-pequeno.svg`, 27 × 27 dentro de una caja de 50) abajo a la izquierda, a 120 px del borde izquierdo; destello blanco (`cta-destello-blanco-superior.svg`, 80 × 69) arriba a la derecha, a unos 30 px de los bordes. Valores sueltos: el dorado `#D8B11E` y el teal con opacidad 0.9 están dentro de los SVG (no van en el CSS). Móvil (propuesto): `px-4 py-16`; la tarjeta ocupa todo el ancho con `px-6 py-12`; título en `--text-h3`, el párrafo salta de línea (sin `whitespace-nowrap`) y el correo en `--text-h4` con `break-words` para que no se salga; el botón se centra con su ancho natural; la estrella y el destello blanco se reducen a la mitad y el destello dorado se oculta bajo `md`.
- **Indicaciones del diseño:**
    - el correo que sea un enlace que lleve al correo (mailto:)
    - usar el campo de whatsapp que se usa en configuracion del sitio
- **Estado:** pendiente

**Campos ACF**

| Campo                            | Tipo     | Descripción                                                                                  | Valor actual                                                      | Requerido |
| -------------------------------- | -------- | -------------------------------------------------------------------------------------------- | ----------------------------------------------------------------- | --------- |
| `contacto_seccion_de_cta_titulo` | text     | Título de la tarjeta de contacto directo, al final de la página. Recomendado: ~15 caracteres | Escríbenos                                                        | sí        |
| `contacto_seccion_de_cta_texto`  | textarea | Frase bajo el título, antes del correo y del botón de WhatsApp. Recomendado: ~100 caracteres | Da el primer paso hacia un mundo de tranquilidad junto a tu bebé. | no        |

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] El correo es un enlace `mailto:` que sale de `opciones_contacto_email`; no hay correo escrito en la plantilla
- [ ] El botón abre WhatsApp en una pestaña nueva con el número de `opciones_contacto_whatsapp` y el mensaje de `opciones_contacto_whatsapp_mensaje`
- [ ] Sin número de WhatsApp el botón no se imprime; sin correo, la línea del correo no se imprime
