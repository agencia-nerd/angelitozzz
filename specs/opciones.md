# Página: Ajustes del sitio

- **Resumen:** Lo que se repite en todas las páginas: el cintillo de promociones y su modal del coach de sueño, el header, el footer con el newsletter, y los datos de contacto y redes que usan varias páginas.
- **Fuente:** `figma:home`
- **Plantilla:** `header.php`
- **Tipo:** global
- **Contenido:** ninguno
- **Secciones (en orden):** cintillo-promociones, modal-coach, modal-coach-gracias, header, contacto, redes, footer
- **Skills usadas:** wp-modal (modal-coach), wp-form (modal-coach, footer), wp-helper
- **Grupo ACF:** Ajustes del sitio
- **Ubicación:** página de opciones: Ajustes del sitio
- **Padre:** ninguno
- **Notas de página:**
    - El cintillo-promociones, header, footer va en todas las paginas obligatoriamente.
    - La pagina tiene una imagen de fondo (quedara fija donde se usa), pero no en todas / otras paginas se repiten.
    - la seccion de testimonios no todos comparten los mismos testimonios, pero cada pagina que tiene testimonios es independiente, incluyendo la pagina de testimonios, pero si usar lo que se pide para las secciones testimonios (swiper, no automatico, redifreccionar a su pagina)
    - varias paginas compartes la estructura o similar a de cta-final, como se menciona en la nota de esa seccion, eso aun quedara pendiente para las demas cta-final de otras paginas.
    - Las tres notas anteriores son de las páginas (fondo de nubes, testimonios, cta-final): se aplican en sus specs, no en esta.
    - Se leen con `get_field('opciones_…', 'option')`; ninguna página repite el header ni el footer.
    - El logo principal viene de Personalizar > Identidad del sitio; los menús son las ubicaciones de "Menús" en `_shared.md`.
- **Estado:** aprobado

## Sección: cintillo-promociones

- **Origen:** `specs/figma/lectura/home/cintillo-promociones.ref.txt` (nodo 4011:9019)
- **Destino:** `header.php`, `template-parts/components/cintillo-promociones.php`
- **Grupo:** `opciones_cintillo_promociones`
- **Skill:** wp-helper
- **Comportamiento:** barra de 56 px sobre el header, en todas las páginas. Todo el cintillo es un `<button>` que abre el modal del coach (`modal-coach`) y lleva `aria-haspopup="dialog"`. Su texto es Medium 20 / 1.5 sin estilo de Figma: queda como valor arbitrario. En móvil el texto pasa a varias líneas, centrado, y la flecha se mantiene a la derecha. Si el texto está vacío, el cintillo no se muestra.
- **Indicaciones del diseño:**
    - Cuando se pase a ACF, en el apartado de configuracion del sitio vaya un campo para ese sintillo.
- **Estado:** aplicado

**Campos ACF**

| Campo                                 | Tipo | Descripción                                                                                                                              | Valor actual                                                                                     | Requerido |
| ------------------------------------- | ---- | ---------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------ | --------- |
| `opciones_cintillo_promociones_texto` | text | Texto del cintillo que aparece arriba de todas las páginas. Al hacer clic abre el modal del coach de sueño. Recomendado: ~140 caracteres | ¿Tu bebé no duerme bien? Nuestro coach de sueño virtual te ayuda a resolver tus dudas, gratis 💛 | no        |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] El cintillo es un `<button>` que abre el modal con teclado y con clic
- [x] Sale en todas las páginas desde `header.php`

## Sección: modal-coach

- **Origen:** `specs/figma/lectura/home/modal-coach.ref.txt` (nodo 3888:6227, fondo 3888:6226)
- **Destino:** `template-parts/modals/modal-coach.php`, `template-parts/forms/coach.php`
- **Grupo:** `opciones_modal_coach`
- **Skill:** wp-modal, wp-form
- **Comportamiento:** wp-modal: `<dialog>` de 600 px de ancho, tarjeta blanca de radio 24 con sombra `0 20px 60px rgba(0,0,0,0.2)` y fondo `#141f21` al 50 % (valores sueltos); botón de cerrar circular de 40 px arriba a la derecha con texto accesible "Cerrar"; se abre desde el cintillo; cierra con Esc y con clic en el fondo; en móvil ocupa el ancho con 16 px de margen y hace scroll interno. wp-form, formulario "coach": campos `nombre` (texto, requerido), `whatsapp` (teléfono, requerido) con un selector de país que se puede cambiar: es el campo `whatsapp_pais` (select requerido, bandera y código, por defecto 🇵🇪 +51), que se guarda y se envía junto al número, `edad_bebe` (select, requerido: 0 a 4 meses, 4 a 12 meses, 12 a 24 meses, 2 a 4 años), `email` (email, opcional) y la casilla de aceptación (requerida) con el enlace a la página de Políticas de privacidad. Envía un correo (hoy a Mailpit, luego por Fluent SMTP) **y guarda cada inscripción en el admin** para leer los nombres de los inscritos. Al enviar con éxito, el mismo modal pasa a la pantalla `modal-coach-gracias` con el nombre escrito. El enlace "Ver Políticas de privacidad" usa `#a8566a` en el diseño, que es el token `--color-acento`; sale de `get_privacy_policy_url()` y, mientras no haya página fijada en Ajustes → Privacidad, el texto va sin enlace. Como pide wp-form, arriba del formulario va "Los campos con * son obligatorios." y cada campo obligatorio lleva `*`. Correo a info@angelitozzz.com; sin JavaScript, el `<noscript>` ofrece ese mismo correo. Texto de éxito (solo para lectores de pantalla, antes de la pantalla de gracias): "¡Listo! Ya puedes escribirle a tu coach de sueño virtual." (en `inc/forms/coach.php`). Animación `scale`; los valores del modal quedan como base del proyecto (`--modal-*` en `src/css/input.css`).
- **Indicaciones del diseño:**
    - lo abre el cintillo-promociones al hacer clic (pop-up del coach de sueño virtual).
    - El nombre es de acuerdo a lo que ponga el usuario. y por lo que se, eso se necesitaria llega al correo, pero como se va a necesitar el nombre del los inscritos crea un apartado de el admin para leer los nombres y se te facilite.
    - Ademas, para el texto de ese apartado del modal, el campo este en confuracion del sitio, es para que sea administralo el texto.
- **Estado:** aplicado

**Campos ACF**

| Campo                                 | Tipo     | Descripción                                                                                                 | Valor actual                                                                                                                                     | Requerido |
| ------------------------------------- | -------- | ----------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------ | --------- |
| `opciones_modal_coach_titulo`         | textarea | Título del modal del coach de sueño. Recomendado: ~200 caracteres                                           | ¡Hola! 💛 Vamos a conectarte con el coach de sueño virtual de Angelitozzz, para que te ayude a resolver cualquier duda sobre el sueño de tu bebé | sí        |
| `opciones_modal_coach_texto`          | textarea | Texto bajo el título, antes del formulario. Recomendado: ~90 caracteres                                     | Déjame estos datos y en un momento podrás escribirle, gratis.                                                                                    | no        |
| `opciones_modal_coach_ayuda_whatsapp` | text     | Texto de ayuda bajo el campo de WhatsApp. Recomendado: ~120 caracteres                                      | Lo necesitamos porque tu coach de sueño virtual te va a escribir y ayudarte por aquí 💛                                                          | no        |
| `opciones_modal_coach_ayuda_email`    | text     | Texto de ayuda bajo el campo de email. Recomendado: ~80 caracteres                                          | Así también te avisamos sobre contenido y novedades                                                                                              | no        |
| `opciones_modal_coach_aceptacion`     | text     | Texto de la casilla de aceptación, antes del enlace a Políticas de privacidad. Recomendado: ~130 caracteres | Acepto que Angelitozzz use mis datos para contactarme y enviarme información relacionada ·                                                       | sí        |
| `opciones_modal_coach_boton`          | text     | Texto del botón que envía el formulario. Recomendado: ~60 caracteres                                        | Quiero que me ayuden con el sueño de mi bebé                                                                                                     | sí        |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] El envío es un `<button type="submit">`, no un enlace
- [x] Cada inscripción queda guardada y se lee en el admin (nombre, WhatsApp, edad del bebé, email, fecha)
- [x] Al enviar, el modal muestra la pantalla de gracias sin recargar

## Sección: modal-coach-gracias

- **Origen:** `specs/figma/lectura/home/modal-coach-gracias.ref.txt` (nodo 3888:6508)
- **Destino:** `template-parts/modals/modal-coach.php`
- **Grupo:** `opciones_modal_coach_gracias`
- **Skill:** wp-modal
- **Comportamiento:** estado de éxito del mismo modal (wp-modal): reemplaza el formulario dentro del mismo `<dialog>` (560 px de ancho, mismo fondo y botón de cerrar). `{nombre}` se reemplaza por el nombre que escribió la persona. "Hablar ahora" abre `https://wa.me/<número>?text=<mensaje>` en una pestaña nueva, con el número de `opciones_contacto_whatsapp_coach` y el mensaje de `opciones_modal_coach_gracias_mensaje` con `{nombre}` reemplazado. El ícono de check (72 px) es un SVG decorativo. Mientras `opciones_contacto_whatsapp_coach` esté vacío, "Hablar ahora" se ve desactivado (sin enlace). JS en `src/js/modules/modal-coach.js`.
- **Indicaciones del diseño:**
    - la gracias del modal.
- **Estado:** aprobado

**Campos ACF**

| Campo                                  | Tipo     | Descripción                                                                                                               | Valor actual                                                                                                 | Requerido |
| -------------------------------------- | -------- | ------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------ | --------- |
| `opciones_modal_coach_gracias_titulo`  | text     | Título de la pantalla de gracias. `{nombre}` se reemplaza por el nombre de la persona. Recomendado: ~40 caracteres        | ¡Listo, {nombre}! 💛                                                                                         | sí        |
| `opciones_modal_coach_gracias_texto`   | textarea | Texto de la pantalla de gracias. Recomendado: ~160 caracteres                                                             | Ya puedes escribirle a tu coach de sueño virtual y empezar a resolver las dudas de sueño de tu bebé, gratis. | no        |
| `opciones_modal_coach_gracias_boton`   | text     | Texto del botón que abre WhatsApp. Recomendado: ~20 caracteres                                                            | Hablar ahora                                                                                                 | sí        |
| `opciones_modal_coach_gracias_mensaje` | text     | Mensaje que se escribe solo en WhatsApp. `{nombre}` se reemplaza por el nombre de la persona. Recomendado: ~80 caracteres | Hola, soy {nombre} 💛 Quiero ayuda con el sueño de mi bebé.                                                  | sí        |
| `opciones_modal_coach_gracias_nota`    | text     | Nota bajo el botón que explica qué pasa al hacer clic. Recomendado: ~40 caracteres                                        | Se abre WhatsApp con el mensaje:                                                                             | no        |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] El título y el mensaje de WhatsApp muestran el nombre escrito en el formulario
- [ ] "Hablar ahora" abre WhatsApp con el número del coach y el mensaje

## Sección: header

- **Origen:** `specs/figma/lectura/home/header.ref.txt` (nodo 4011:9020)
- **Destino:** `header.php`
- **Grupo:** ninguno
- **Skill:** wp-helper
- **Comportamiento:** el logo viene de Identidad del sitio (`the_custom_logo()`, 87 × 63 px) y enlaza al inicio. El menú central es la ubicación `principal` y "Contacto" es la ubicación `cabecera-derecha`, al lado del botón "Iniciar sesión". "Iniciar sesión" (ícono de persona) y el carrito (con contador `--color-acento`, texto de 12 px suelto) son enlaces: WooCommerce aún no está definido (`_shared.md`), así que apuntan a `#` y el contador muestra el valor del diseño. Fondo `--color-fondo` (crema, como el frame de Figma) con `--shadow-header`. Mientras el tema no tenga logo en Identidad del sitio, se muestra el nombre del sitio como texto. Los menús se imprimen con `angelitozzz_menu()` (`inc/menus.php`): mientras una ubicación no tenga menú asignado, muestra los enlaces del diseño. Móvil: logo a la izquierda, carrito y un botón de menú (hamburguesa) a la derecha; el botón abre un panel con los dos menús y "Iniciar sesión" (`<button aria-expanded>`, JS en `src/js/modules/menu.js`).
- **Estado:** aplicado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] El logo sale de Identidad del sitio, no de un archivo fijo ni de un campo ACF
- [x] El menú móvil abre y cierra con teclado y anuncia su estado con `aria-expanded`

## Sección: contacto

- **Origen:** datos que usan contacto/seccion-de-cta, mundo-angelitozzz/hero, preguntas-frecuentes/cta-final, soluciones-de-sueno/cta-final y modal-coach-gracias
- **Destino:** `template-parts/components/cta-final.php`, `template-parts/pages/contacto/seccion-de-cta.php`, `template-parts/pages/mundo-angelitozzz/hero.php`
- **Grupo:** `opciones_contacto`
- **Skill:** wp-helper
- **Comportamiento:** no se muestra por sí sola: las secciones que piden WhatsApp o correo leen estos campos. El WhatsApp se guarda solo con dígitos y código de país, y los botones arman `https://wa.me/<número>?text=<mensaje>` con el helper de enlaces. El correo se imprime como `mailto:`. Se aplica junto con las páginas que la usan (Contacto, Mundo Angelitozzz y los CTA finales con WhatsApp); hoy ningún archivo del tema la lee.
- **Estado:** pendiente

**Campos ACF**

| Campo                                | Tipo  | Descripción                                                                                                                      | Valor actual         | Requerido |
| ------------------------------------ | ----- | -------------------------------------------------------------------------------------------------------------------------------- | -------------------- | --------- |
| `opciones_contacto_whatsapp`         | text  | Número de WhatsApp de Angelitozzz, con código de país y sin espacios (51999999999). Lo usan los botones "Escríbeme por WhatsApp" | —                    | sí        |
| `opciones_contacto_whatsapp_mensaje` | text  | Mensaje que se escribe solo al abrir WhatsApp desde los botones del sitio. Recomendado: ~80 caracteres                           | —                    | no        |
| `opciones_contacto_whatsapp_coach`   | text  | Número de WhatsApp del coach de sueño virtual, con código de país y sin espacios. Lo usa el botón "Hablar ahora" del modal       | —                    | sí        |
| `opciones_contacto_email`            | email | Correo de contacto que se muestra en la página Contacto y abre el correo al hacer clic                                           | info@angelitozzz.com | sí        |

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] Ningún número de WhatsApp ni correo queda escrito en una plantilla de página

## Sección: redes

- **Origen:** `specs/figma/lectura/home/footer.ref.txt` (fila "Síguenos")
- **Destino:** `footer.php`
- **Grupo:** `opciones_redes`
- **Skill:** wp-helper
- **Comportamiento:** íconos circulares de 3 redes (Instagram, TikTok, Facebook) con borde `--color-contorno`. Cada enlace abre en una pestaña nueva con el helper de enlaces y lleva el nombre de la red como texto accesible. El ícono sale de la red elegida (SVG del tema), no se sube. Una red sin enlace no se muestra; hasta el paso ACF, por decisión del usuario, las 3 redes del diseño se ven sin enlace (aún no hay URLs).
- **Estado:** aprobado

**Campos ACF**

| Campo                         | Tipo     | Descripción                                                                  | Valor actual | Requerido |
| ----------------------------- | -------- | ---------------------------------------------------------------------------- | ------------ | --------- |
| `opciones_redes_titulo`       | text     | Título junto a los íconos de redes en el footer. Recomendado: ~15 caracteres | Síguenos     | no        |
| `opciones_redes_items`        | repeater | Redes sociales del footer, en el orden en que se muestran. El diseño tiene 3 | —            | no        |
| `opciones_redes_items.red`    | select   | Red social: Instagram, TikTok, Facebook, YouTube. Define el ícono            | Instagram    | sí        |
| `opciones_redes_items.enlace` | url      | Dirección del perfil en esa red                                              | —            | sí        |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [ ] Cada ícono tiene nombre accesible y avisa que abre en pestaña nueva

## Sección: footer

- **Origen:** `specs/figma/lectura/home/footer.ref.txt` (nodo 4011:9245)
- **Destino:** `footer.php`, `template-parts/forms/newsletter.php`
- **Grupo:** `opciones_footer`
- **Skill:** wp-form
- **Comportamiento:** bloque del newsletter (título, texto y formulario de un campo email con botón verde "Suscribirme"), 4 columnas de enlaces (ubicaciones `pie-navegacion` en dos columnas, `pie-legal` y `pie-ayuda`), la fila de redes (sección `redes`), el copyright y el crédito fijo "Made with ♥ NERD" con el logo SVG de NERD enlazado a la agencia. Formulario "newsletter" con wp-form: campo `email` (requerido), sin casilla en el diseño; envía un correo (Mailpit, luego Fluent SMTP) y también guarda cada suscripción en el admin. El placeholder usa `#8a8a8a` (valor suelto). Correo a info@angelitozzz.com; sin JavaScript, el `<noscript>` ofrece ese mismo correo. Texto de éxito: "¡Gracias! Ya estás suscrita a nuestro blog." (en `inc/forms/newsletter.php`). El crédito enlaza a https://nerd.pe en una pestaña nueva. Móvil: newsletter arriba a todo el ancho, columnas de enlaces en 2 × 2, redes y copyright centrados.
- **Indicaciones del diseño:**
    - Usar la skill wp-form para ese formulario de correo del newsletter.
    - Las redes sociales que vaya en configuracion del sitio
- **Estado:** aprobado

**Campos ACF**

| Campo                               | Tipo     | Descripción                                                                | Valor actual                                                           | Requerido |
| ----------------------------------- | -------- | -------------------------------------------------------------------------- | ---------------------------------------------------------------------- | --------- |
| `opciones_footer_newsletter_titulo` | text     | Título del bloque de suscripción del footer. Recomendado: ~40 caracteres   | Suscríbete a nuestro blog                                              | sí        |
| `opciones_footer_newsletter_texto`  | textarea | Texto bajo el título de suscripción. Recomendado: ~110 caracteres          | Te enviaremos consejos gratuitos para que tu Angelito aprenda a dormir | no        |
| `opciones_footer_copyright`         | text     | Texto de derechos al pie de todas las páginas. Recomendado: ~70 caracteres | Angelitozzz ©2026 Todos los derechos reservados                        | sí        |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [ ] Los enlaces del footer salen de las 3 ubicaciones de menú, no están escritos en la plantilla
- [x] Cada suscripción queda guardada y se lee en el admin
