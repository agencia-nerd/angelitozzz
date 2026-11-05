# Página: Preguntas frecuentes

- **Resumen:** Reúne las dudas más comunes sobre las soluciones de sueño y los cursos de Angelitozzz; el visitante abre la pregunta que le interesa y, si no encuentra su respuesta, escribe por WhatsApp desde el CTA final.
- **Fuente:** `figma:preguntas-frecuentes`
- **Plantilla:** `pages/template-preguntas-frecuentes.php`
- **Tipo:** estática
- **Contenido:** ninguno
- **Secciones (en orden):** seccion-preguntas-frecuentes, cta-final
- **Skills usadas:** wp-accordion (seccion-preguntas-frecuentes), wp-helper (cta-final)
- **Grupo ACF:** Preguntas frecuentes
- **Ubicación:** plantilla pages/template-preguntas-frecuentes.php
- **Padre:** ninguno
- **Notas de página:**
  - `h1` en seccion-preguntas-frecuentes ("¡Respondemos a tus preguntas!"); la página no tiene hero
  - título SEO "Preguntas frecuentes | Angelitozzz"
  - sin meta descripción en el diseño
  - fondo de la página `--color-fondo` (el de la sección de preguntas)
  - se llega desde el menú `pie-ayuda` del footer ("Preguntas frecuentes")
  - sin frame móvil en Figma: el móvil sigue lo propuesto en el `Comportamiento` de cada sección
- **Estado:** pendiente

## Sección: seccion-preguntas-frecuentes

- **Origen:** `specs/figma/lectura/preguntas-frecuentes/seccion-preguntas-frecuentes.ref.txt` (nodo 4020:22537, capa "Preguntas de la pagina")
- **Destino:** `template-parts/pages/preguntas-frecuentes/seccion-preguntas-frecuentes.php`
- **Grupo:** `preguntas_frecuentes_seccion_preguntas_frecuentes`
- **Skill:** wp-accordion
- **Comportamiento:** la sección no lleva markup propio: la arma wp-accordion sobre `<details>`/`<summary>` nativos con el diseño de Figma. Fondo `--color-fondo`, `py-22` (88), título `h1` centrado (`--text-h1`, `--color-marca`) y, 65 px más abajo (`gap-16`), la lista de 774 px de ancho centrada (`max-w-[774px]`, valor suelto; `py-5` y `gap-4` entre preguntas). Las preguntas salen del repetidor propio de esta página (`preguntas_frecuentes_seccion_preguntas_frecuentes_preguntas`), independiente de cualquier otra página. Cada fila: `<summary>` con el check (`preguntas-check.svg`, 14 × 13 px, decorativo con `aria-hidden`, en `--color-tinta`) a 10 px de la pregunta (`--text-body-l`, `--color-tinta`); al abrir, la respuesta (`--text-body-s`, `--color-tinta`) aparece 54 px debajo (valor suelto `gap-[54px]`) y la línea separadora queda 54 px después; cerrada, la línea va 54 px debajo de la pregunta. La línea es un borde de 1 px en `--color-neutro` (en CSS, no el SVG `preguntas-separador.svg`) con 26 px de sangría a la izquierda (`ml-[26px]`, valor suelto). **Indicador:** el check es el indicador del diseño y no cambia entre abierto y cerrado; no se agrega `+` ni chevron, y se oculta el marcador nativo de `<summary>` (`list-none` y `::-webkit-details-marker`). El `<summary>` muestra `cursor-pointer` y un foco visible (`focus-visible:outline` en `--color-marca`). **Lo que debe hacer wp-accordion:** la primera pregunta empieza abierta (`open`), las demás cerradas; cada una se abre y cierra sola (varias pueden estar abiertas a la vez, no es exclusivo); teclado nativo (Tab entre preguntas, Enter y Espacio abren y cierran); la respuesta se despliega con una transición de altura y opacidad de ~250 ms, que se desactiva con `prefers-reduced-motion`; todas las respuestas están en el HTML del servidor (también las cerradas y las ocultas por "Ver más") para que se indexen. **"Ver más":** se imprime solo cuando el repetidor tiene **más de 10** preguntas (nota del usuario, aunque el diseño lo muestra con 6): se ven las 10 primeras y las demás quedan en el HTML con `hidden`; el botón es un `<button type="button">` con `aria-expanded="false"` y `aria-controls` hacia el bloque oculto, texto `--text-body-xl` en `--color-marca` y la flecha hacia abajo de 10 px (`preguntas-ver-mas-flecha.svg` girado 180°, trazo `--color-contorno`, decorativa con `aria-hidden`), centrado bajo la lista con `gap-2.5`. Al pulsarlo muestra el resto, desaparece y el foco pasa al `<summary>` de la pregunta 11. Con 10 preguntas o menos no hay botón ni elementos ocultos. Si el repetidor está vacío, la sección muestra solo el título. Móvil (propuesto): `px-4 py-14`; el título baja a `--text-h3` y puede partirse en dos líneas (sin `whitespace-nowrap`); la lista ocupa todo el ancho; la pregunta se parte en varias líneas con el check alineado a la primera (`items-start`); los espacios de 54 px bajan a 24 px (`gap-6`) y la sangría de la línea a 24 px (`ml-6`); "Ver más" sigue centrado.
- **Indicaciones del diseño:**
  - las preguntas frecuentes de esta pagina son independientes de lo que se tiene en las demas paginas
  - cuando tenga mas de 10 preguntas, que aparezca lo que es el boton de ver mas.
- **Estado:** pendiente

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
|---|---|---|---|---|
| `preguntas_frecuentes_seccion_preguntas_frecuentes_titulo` | text | Título principal de la página, centrado sobre la lista de preguntas. Recomendado: ~45 caracteres | ¡Respondemos a tus preguntas! | sí |
| `preguntas_frecuentes_seccion_preguntas_frecuentes_preguntas` | repeater | Preguntas frecuentes de esta página, en el orden en que se muestran; son solo de esta página. La primera aparece abierta. Con más de 10 se muestran las 10 primeras y el botón "Ver más" muestra el resto. El diseño trae 6: ¿Cuál de las soluciones es para mí?, ¿Por cuánto tiempo tengo acceso a los cursos?, ¿Puedo regalar un curso?, ¿Este curso logrará que mi bebé duerma de corrido?, ¿Cómo funciona Mundo Angelitozzz? y ¿Los métodos implican dejar llorar al bebé? | ¿Cuál de las soluciones es para mí? | no |
| `preguntas_frecuentes_seccion_preguntas_frecuentes_preguntas.pregunta` | text | Pregunta. Se muestra junto al check y abre o cierra su respuesta. Recomendado: ~55 caracteres | ¿Cuál de las soluciones es para mí? | sí |
| `preguntas_frecuentes_seccion_preguntas_frecuentes_preguntas.respuesta` | textarea | Respuesta que aparece al abrir la pregunta. Usa Enter para separar párrafos. Recomendado: ~440 caracteres | Depende de la edad de tu bebé y del acompañamiento que buscas. Recién Nacidos Go es para 0 a 12 semanas; los Cursos de Horarios, de 4 meses a 3 años; Angelita Pro te acompaña por WhatsApp de 4 a 24 meses, y A Dormir Por Fin es la asesoría personalizada con Bárbara. Si tienes dudas, escríbenos por WhatsApp. | sí |
| `preguntas_frecuentes_seccion_preguntas_frecuentes_ver_mas` | text | Texto del botón que muestra el resto de las preguntas. Solo aparece con más de 10 preguntas. Recomendado: ~15 caracteres | Ver más | no |

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] La lista está hecha con wp-accordion sobre `<details>`/`<summary>`, sin markup propio de la sección
- [ ] La primera pregunta carga abierta y las demás cerradas; se abren y cierran con Enter y Espacio
- [ ] El check es el único indicador: no hay `+`, chevron ni marcador nativo de `<summary>`
- [ ] Con 10 preguntas o menos no se imprime "Ver más"; con 11 o más se ven 10 y el botón (con `aria-expanded`) muestra el resto, desaparece y lleva el foco a la pregunta 11
- [ ] Todas las respuestas están en el HTML del servidor, también las ocultas
- [ ] Con `prefers-reduced-motion` las respuestas abren sin animación
- [ ] En móvil las preguntas largas se parten en varias líneas sin scroll horizontal

## Sección: cta-final

- **Origen:** `specs/figma/lectura/preguntas-frecuentes/cta-final.ref.txt` (nodo 4020:22578, instancia del componente Figma "CTA final" 3713:17003)
- **Destino:** `pages/template-preguntas-frecuentes.php`, `template-parts/components/cta-final.php`
- **Grupo:** `preguntas_frecuentes_cta_final`
- **Skill:** wp-helper
- **Comportamiento:** usa el componente compartido `cta-final` (`_shared.md`), llamado desde la plantilla con `get_template_part('template-parts/components/cta-final', null, $args)` y los argumentos `titulo`, `descripcion` (visible en esta página) y `boton`. Fondo `--color-fondo-rosa`, título `h2` centrado (`--text-h2`, `--color-marca`), descripción `--text-body-l` en `--color-tinta`, botón `boton` variante `frambuesa` con flecha, y los destellos y trazos blancos decorativos del componente (`cta-final-*.svg`, el destello grande sobresale por debajo de la sección). **Botón de WhatsApp (nota del usuario):** el número no es un campo de esta página: sale de `opciones_contacto_whatsapp` de Ajustes del sitio (`get_field(..., 'option')`). La plantilla arma el array link que recibe `boton`: `url` = `https://wa.me/<número>` y, si `opciones_contacto_whatsapp_mensaje` tiene texto, `?text=` con el mensaje codificado (`rawurlencode`); `title` = el texto de `preguntas_frecuentes_cta_final_boton_texto`; `target` = `_blank` (el helper de enlaces pone `rel` y el aviso `sr-only` de pestaña nueva). Si el número está vacío, el botón no se muestra. Móvil: el del componente compartido (título en `--text-h3`, `px-4`, decoraciones reducidas u ocultas).
- **Indicaciones del diseño:**
  - es ese apartado especifico, pide que se redireccione al whatsapp, o sea usar el campo de configuracion del sitio correspondiente.
- **Estado:** pendiente

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
|---|---|---|---|---|
| `preguntas_frecuentes_cta_final_titulo` | text | Título del bloque de cierre de la página. Recomendado: ~45 caracteres | ¿No encontraste tu respuesta? | sí |
| `preguntas_frecuentes_cta_final_descripcion` | textarea | Texto opcional bajo el título. Si se deja vacío no se muestra. Recomendado: ~100 caracteres | Escríbenos y te ayudamos a encontrar la mejor opción para tu bebé. | no |
| `preguntas_frecuentes_cta_final_boton_texto` | text | Texto del botón que abre WhatsApp. El número y el mensaje se editan en Ajustes del sitio. Recomendado: ~35 caracteres | Escríbeme por WhatsApp | sí |

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] La sección es una llamada al componente `cta-final` con `$args`, sin markup propio en la plantilla
- [ ] El botón abre `https://wa.me/<opciones_contacto_whatsapp>` (con `?text=` si hay mensaje) en una pestaña nueva, pasando por el helper de enlaces
- [ ] Sin número en Ajustes del sitio el botón no se muestra y no queda un enlace vacío
