# Página: Canjear regalo

- **Resumen:** Quien recibió un curso de regalo escribe el código que le llegó por correo y el curso queda en su cuenta. Si no tiene cuenta, la crea en el paso siguiente y vuelve aquí para terminar el canje.
- **Fuente:** `figma:canjear-regalo`
- **Plantilla:** `pages/template-canjear-regalo.php`
- **Tipo:** estática
- **Contenido:** ninguno
- **Secciones (en orden):** canje, canjeado
- **Skills usadas:** ninguna. El formulario lo procesa el plugin `angelitozzz-cursos` (no wp-form), como el inicio de sesión
- **Grupo ACF:** ninguno
- **Ubicación:** ninguna
- **Padre:** ninguno
- **Notas de página:**
    - Página propia `/canjear-regalo/` (propuesta aprobada en el plan del 2026-11-14). Se llega desde el enlace "¿Te regalaron este curso? Canjea tu código" de cada landing (`solucion.md` → `hero`) y desde el correo del regalo.
    - Las dos secciones son dos estados de la misma tarjeta y nunca se ven juntas: `canje` es el formulario; `canjeado` se ve después de canjear con éxito. Tras el envío, el plugin redirige a la misma página (POST → redirección → GET), así que recargar no vuelve a canjear.
    - Sin sesión: si el código es válido, el plugin lo guarda en el servidor (dato temporal unido a una cookie de un solo uso, nunca el código en la URL) y lleva a `/crear-cuenta/` con `redirect_to` a esta página; desde ahí también se puede ir a `/iniciar-sesion/`. Al volver con la sesión iniciada, el canje se completa solo y se ve `canjeado`.
    - Sin campos ACF: los textos son fijos del tema (text domain `angelitozzz`; decisión del usuario, 2026-11-19). El nombre del curso y la duración del acceso salen del plugin.
    - Conectada en la F5-c (plugin 0.7.0): el formulario se envía a la acción `angelitozzz_canjear_regalo` y la página lee `angelitozzz_cursos_ultimo_canje()`. Sin el plugin activo se ve el aviso "El canje no está disponible en este momento. Escríbenos por WhatsApp y te ayudamos." (`funciones-publicas`).
    - `h1`: el título de la tarjeta visible ("¿Te regalaron un curso?" o "¡Listo, ya tienes tu curso!"). Título SEO "Canjear regalo | Angelitozzz", con `noindex`.
    - Fondo crema `--color-fondo`; la tarjeta blanca centrada (componente `confirmacion-tarjeta`). Cintillo, header y footer salen de `header.php` y `footer.php`.
    - Sin frame móvil en Figma: el comportamiento en móvil es una propuesta.
- **Estado:** aplicado

## Sección: canje

- **Origen:** `specs/figma/lectura/canjear-regalo/canje.ref.txt` (nodo 3640:1593)
- **Destino:** `pages/template-canjear-regalo.php`, `template-parts/pages/canjear-regalo/canje.php`, `template-parts/components/confirmacion-tarjeta.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** componente `confirmacion-tarjeta` con el ícono de regalo (56 px, decorativo), sin pastilla, `h1` "¿Te regalaron un curso?" y la bajada "Ingresa el código que recibiste por correo y empieza cuando quieras.". Formulario `<form method="post">` con nonce, enviado a una acción del plugin `angelitozzz-cursos`:
    - **Campo:** caja de ancho completo con fondo `--color-fondo`, borde `--color-neutro`, radio 12, padding 16/20, texto `--text-cuerpo`; `<label>` "Código de regalo" visualmente oculto (`sr-only`) y placeholder "Código de regalo (ej. ANGZ-4K7P)" en `--color-tinta-suave`; `autocomplete="off"`, `autocapitalize="characters"`, `spellcheck="false"`. El servidor quita los espacios y pasa a mayúsculas antes de buscar.
    - **Botón:** `<button type="submit">` "Canjear código" con el aspecto de `boton` frambuesa con flecha. Mientras envía, se desactiva.
    - **Nota** (`--text-nota`, `--color-tinta-suave`): "Si aún no tienes cuenta, la creas en el siguiente paso." Con sesión iniciada no se muestra.
    - **Errores** (propuesta, el diseño no los muestra): un solo mensaje para código inexistente, usado o vencido, "Este código no es válido o ya fue usado. Revisa que esté bien escrito.", y por exceso de intentos, "Demasiados intentos. Prueba de nuevo en unos minutos.". Van arriba del campo, en un recuadro con `role="alert"`; el campo toma borde `--color-acento` y conserva lo escrito.
    - **Seguridad** (plugin, F5): nonce; límite de intentos por IP y por usuaria (5 cada 15 minutos, propuesta); el código es de un solo uso y se marca usado en la misma operación que inscribe, para que dos envíos a la vez no lo canjeen dos veces.
    - **Si ya tiene ese curso activo:** el acceso del canje empieza cuando vence el vigente (se encadena, misma regla que volver a comprar, `cursos-datos.md` → `inscripcion`).
    - Valores sueltos: tarjeta de 720 px, padding 56/64, radio 24 (`--radius-tarjeta`), separación 24 px entre bloques. Móvil (propuesta): la tarjeta ocupa el ancho con 16 px de margen y padding 32/24; el título baja a `--text-h3`; el botón queda centrado con su ancho natural. El botón se desactiva al enviar con `src/js/modules/enviar-una-vez.js`. Sin sesión, un código válido lleva a `/crear-cuenta/` con el aviso "Tu código es válido. Crea tu cuenta (o inicia sesión) para terminar el canje.".
- **Indicaciones del diseño:**
    - nota: cuando alguien quiere canjear el curso.
- **Estado:** aplicado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] El campo tiene `<label>` (oculto) asociado, `autocomplete="off"`, `autocapitalize="characters"` y `spellcheck="false"`
- [x] Código inexistente, usado o vencido muestran el mismo mensaje con `role="alert"`, sin perder lo escrito; el campo queda con `aria-invalid` y apunta al mensaje
- [x] El exceso de intentos muestra su propio mensaje con `role="alert"`
- [x] El botón se desactiva al enviar (`enviar-una-vez.js`) y no se desactiva si el campo está vacío
- [x] La nota de crear cuenta solo se ve sin sesión
- [x] En móvil la tarjeta no tiene scroll horizontal

Verificado en la F5-c (2026-11-19, pruebas del plugin y en el Local):

- [x] El formulario lleva nonce
- [x] Un código válido inscribe a la usuaria en su curso, con `origen` `regalo` y el inicio el día del canje
- [x] El mismo código no se puede canjear dos veces, ni con dos envíos a la vez
- [x] Tras varios intentos fallidos se bloquea un rato
- [x] Sin sesión, un código válido lleva a crear cuenta; al volver con sesión el canje termina solo y el código nunca viaja en la URL
- [x] Recargar después de canjear no vuelve a enviar el formulario

## Sección: canjeado

- **Origen:** `specs/figma/lectura/canjear-regalo/canjeado.ref.txt` (nodo 3677:1971)
- **Destino:** `template-parts/pages/canjear-regalo/canjeado.php`, `template-parts/components/confirmacion-tarjeta.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** se ve solo justo después de un canje con éxito: la vista lee el último canje de la usuaria que el plugin deja como dato de un solo uso (nunca un ID en la URL). Si se entra a la página sin ese dato, se ve `canje`. Componente `confirmacion-tarjeta` con:
    - Ícono check (56 px, decorativo) y la pastilla verde "✓ Regalo canjeado" (fondo `--color-marca`, texto `--color-fondo`, `--text-rotulo`, radio 100, padding 6/14).
    - `h1` "¡Listo, ya tienes tu curso!".
    - Texto: "<curso> ya está en tu cuenta. Puedes empezar ahora o encontrarlo cuando quieras en “Mis cursos”." (nombre del curso del plugin).
    - Caja crema (`--color-fondo`, radio 12, padding 14/20, sin borde, texto `--text-cuerpo` `--color-marca`, centrado): "Tu acceso de <duración> empieza hoy." (4, 6 o 12 meses); con acceso ilimitado, "Tu acceso ilimitado empieza hoy.". Si ya tenía el curso vigente, el acceso del regalo se encadena (propuesta F5-c): "Ya tenías este curso: tus <N> meses más empiezan el <fecha>." (o "tu acceso ilimitado empieza el <fecha>.").
    - `boton` frambuesa con flecha "Empezar el curso" a la primera lección del curso; debajo, el enlace subrayado "Ir a Mis cursos" (`--text-rotulo`, `--color-marca`) a `/cuenta/cursos/`.
    - Móvil (propuesta): igual que `canje`; el texto largo se parte en varias líneas y la caja de acceso puede ocupar dos.
- **Indicaciones del diseño:**
    - nota: curso canjeado.
- **Estado:** aplicado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] Muestra el nombre del curso canjeado y la duración del acceso ("de 4 meses") o "ilimitado"
- [x] "Ir a Mis cursos" lleva a `/cuenta/cursos/`

Verificado en la F5-c (2026-11-19, en el Local y en las pruebas del plugin):

- [x] La duración es la real del curso
- [x] "Empezar el curso" abre la primera lección y la lección se puede ver (inscripción activa)
- [x] El curso aparece en Mis cursos
- [x] Entrar a `/canjear-regalo/` otra vez muestra el formulario, no la confirmación
