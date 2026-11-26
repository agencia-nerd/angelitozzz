# Página: Cursos: modelo de datos

- **Resumen:** Qué guarda el plugin `angelitozzz-cursos` y con qué reglas: cursos, lecciones, inscripciones, avance, vencimiento, acceso, descargas, puente con WooCommerce, roles y las funciones que usa el tema. No es una página que se visite: es la base de la F2 y de los specs de la lección, la cuenta y el administrador.
- **Fuente:** `specs/plan/checklist-cursos.md`
- **Plantilla:** ninguna
- **Destinos:** este spec se implementa en el plugin `angelitozzz-cursos`, fuera del tema. Cada `Destino` es una ruta relativa al tema (`../../plugins/angelitozzz-cursos/includes/<archivo>.php`) para que `check.mjs` de design-import compruebe que el archivo existe (2026-11-24).
- **Tipo:** detalle
- **Contenido:** curso
- **Secciones (en orden):** curso, leccion, inscripcion, avance, acceso, archivos-privados, puente-woo, regalo, roles, funciones-publicas
- **Skills usadas:** ninguna. Es código del plugin `angelitozzz-cursos` (sin skill que lo cubra: se sigue la documentación oficial de WordPress y WooCommerce)
- **Grupo ACF:** ninguno
- **Ubicación:** tipo de contenido = curso
- **Padre:** ninguno
- **Notas de página:**
    - Decisiones de `specs/plan/checklist-cursos.md` (2026-11-13): LMS a medida, sin Tutor; cobro con WooCommerce + Openpay (era Niubiz hasta el 2026-11-18); videos en Vimeo (ocultos, sin restricción de dominio); sin cuestionarios, certificados ni comentarios; por ahora solo Bárbara administra.
    - **Cambio de rumbo de los videos (decisión del usuario, 2026-11-25):** los videos de las lecciones dejan Vimeo y se suben al sitio como archivos privados, con la misma protección que los materiales (sección `archivos-privados`). Los materiales aceptan más tipos que PDF. Lo que dicen de Vimeo las secciones `leccion` y `acceso` se reemplaza por esa sección; las secciones con estado `aprobado` son las que cambian.
    - Los datos de cursos y lecciones los escribe el constructor de Bárbara (`admin-constructor.md`), no el admin de WordPress ni ACF. Las capturas del constructor están en `specs/figma/lectura/admin-cursos/constructor-*.png`; de ahí salen los campos de `curso` y `leccion`.
    - Todo en el plugin: prefijo `angelitozzz_` en funciones, opciones, hooks, metadatos (`_angelitozzz_*`) y tablas (`{prefix}angelitozzz_*`); text domain `angelitozzz-cursos`; las tablas se crean con migraciones versionadas (`includes/esquema.php`).
    - Fechas guardadas en UTC (`DATETIME`); se muestran en la zona horaria del sitio (`wp_date`).
    - Privacidad: al borrar una usuaria se borran sus inscripciones y su avance (`delete_user`), y el plugin registra el exportador y el borrador de datos personales de WordPress (Herramientas → Exportar / Borrar datos personales; plugin 0.7.3, `includes/privacidad.php`). El exportador entrega sus inscripciones, su avance y el correo pendiente de confirmar. El borrador quita sus inscripciones (pierde el acceso), su avance, sus metadatos del plugin y su ID en los cupones de regalo que canjeó; los pedidos quedan para el borrador de WooCommerce.
    - Desinstalar el plugin (`uninstall.php` → `includes/desinstalar.php`, plugin 0.7.3) borra sus tablas, opciones, datos temporales (canje, mensajes, contadores viejos), metadatos de las usuarias, el rol, las capacidades y las tareas programadas. Los cursos, las lecciones y sus productos de Woo (con su meta `_angelitozzz_curso_id`, que el puente necesita al reinstalar) se conservan, salvo que esté definida la constante `ANGELITOZZZ_CURSOS_BORRAR_TODO`: entonces borra también los PDF de materiales, los cupones de regalo, las portadas que subió el constructor, los productos de los cursos, los cursos, las lecciones y la carpeta privada. Los pedidos nunca se tocan. Con los archivos privados (2026-11-25), `BORRAR_TODO` borra también sus registros y el almacén privado; sin esa constante se conservan, como los cursos.
- **Estado:** aprobado

## Sección: curso

- **Origen:** pasos 1 y 3 del constructor (`specs/figma/lectura/admin-cursos/constructor-basico-formulario.png`, `constructor-adicional-listo.png`; ver `admin-constructor.md`) y tarjetas de la lista (`admin-cursos/seccion.ref.txt`, nodo 4184:29718)
- **Destino:** `../../plugins/angelitozzz-cursos/includes/tipos.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** tipo de contenido `curso` registrado en código (`register_post_type`):
    - **Visibilidad:** `public` sí (para tener URL), `has_archive` no, `exclude_from_search` sí, `show_in_rest` no.
    - **Admin de WordPress:** `show_ui` solo con la capacidad `angelitozzz_gestionar_cursos`. Capacidades propias (`capability_type` `angelitozzz_curso`, `map_meta_cap`), para que nadie más edite cursos.
    - **URL:** `/cursos/<curso>/`. No tiene página propia: sin acceso redirige a su solución; con acceso, a la última lección vista o a la primera (sección `acceso`).
    - **Datos nativos:** título = "Título del curso"; extracto = "Descripción corta"; imagen destacada = "Imagen de portada"; estado `publish` / `draft` = "Publicado" / "Borrador", que se elige en la "Visibilidad" del paso "Publicar" (`admin-constructor.md`; el "Estado" del paso 1 del diseño se quitó por ser el mismo dato).
    - **Metadatos:**
        - `_angelitozzz_edad`: texto ("0 a 12 semanas"), "Categoría (edad)".
        - `_angelitozzz_tipo`: `curso_online` | `asesoria` | `suscripcion`, el "Tipo" del paso 1.
        - `_angelitozzz_duracion_acceso`: meses, 4, 6, 12 o 0 = ilimitado.
        - `_angelitozzz_producto_id`: su producto de Woo (sección `puente-woo`).
        - `_angelitozzz_modulos`: lista ordenada de módulos `[{id, titulo}]`; `id` es un identificador estable que no cambia al renombrar o reordenar.
        - `_angelitozzz_mes_mundo`: sí/no, "Incluye mes gratis en Mundo" (lo marca Bárbara en el constructor): muestra el banner de Mundo en la primera lección del curso (`leccion.md`) y el bloque en gracias y en el correo. Lo decide solo este interruptor, también en una suscripción (`_angelitozzz_tipo` = `suscripcion`): el constructor solo avisa que las suscripciones normalmente no lo incluyen (decisión del usuario, 2026-11-19). Se lee con `angelitozzz_cursos_incluye_mundo()`.
        - `_angelitozzz_materiales`: archivos del curso, cada uno con `adjunto_id`, `modulo` (`id` del módulo) y `orden`, porque en el constructor se agregan dentro de un módulo ("+ Agregar descargable", `admin-constructor.md`). En la lección van al final del temario, sin check (`leccion.md`). Desde el 2026-11-25, `adjunto_id` es el ID de un archivo privado (sección `archivos-privados`) de un tipo de material, no solo PDF.
    - **Precio:** "Precio (USD)" y "Precio de lanzamiento" no se guardan en el curso. Son el precio normal y el rebajado de su producto de Woo, que el constructor actualiza.
    - **Paso 3 del diseño:** "Qué aprenderás", "Requisitos", "Para quién es" y "Duración total" no se guardan (decisión del usuario, 2026-11-13): los textos de venta están en la solución. La duración total se calcula sumando las lecciones.
- **Estado:** aprobado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] Solo quien tiene `angelitozzz_gestionar_cursos` puede crear o editar cursos, en el constructor o en el admin
- [x] `/cursos/<curso>/` nunca muestra contenido: redirige según el acceso
- [x] Los cursos no aparecen en la búsqueda del sitio ni en el mapa del sitio
- [x] Cada metadato se guarda saneado (texto, entero de una lista permitida, IDs que existen)
- [ ] `_angelitozzz_materiales` del curso solo guarda IDs de archivos privados que existen y son de un tipo de material

## Sección: leccion

- **Origen:** paso 2 del constructor y modal "Editar lección" (`constructor-contenido-carga.png`, `constructor-modal-editar-modulo.png`) y la lección (`specs/figma/lectura/leccion/seccion-entera.ref.txt`)
- **Destino:** `../../plugins/angelitozzz-cursos/includes/tipos.php`, `../../plugins/angelitozzz-cursos/includes/video.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** tipo de contenido `leccion`, hijo de un `curso`:
    - `post_parent` = el curso; `menu_order` = el orden dentro de su módulo.
    - URL `/cursos/<curso>/<leccion>/`.
    - Mismas capacidades que `curso`; fuera de la búsqueda y del mapa del sitio.
    - **Datos nativos:** título = "Título de la lección"; contenido = "Descripción de la lección".
    - **Contenido:** solo se permite negrita, cursiva, listas, enlaces y párrafos, filtrado con `wp_kses` y una lista propia al guardar y al mostrar. Un enlace con `target` solo abre en pestaña nueva (`_blank`) y siempre lleva `rel="noopener noreferrer"`. El tema imprime el texto con `angelitozzz_cursos_texto_leccion()` (sección `funciones-publicas`), que lo vuelve a filtrar después de `the_content`. Un párrafo que empieza con "PD:" se muestra solo como cita (`leccion.md`), sin marcado especial en el texto guardado.
    - **Metadatos:**
        - `_angelitozzz_modulo`: `id` del módulo del curso.
        - `_angelitozzz_video`: ID del archivo privado de video de la lección (mp4 o webm; sección `archivos-privados`). Se guarda solo si existe y es de tipo video. Reemplaza al enlace de Vimeo (2026-11-25).
        - `_angelitozzz_duracion`: segundos, leídos del propio archivo en el servidor al terminar la subida (`wp_read_video_metadata()` de WordPress) y copiados a la lección al elegir el video ("se detecta sola").
        - `_angelitozzz_miniatura`: ya no se usa (era la miniatura de Vimeo). La portada del video es del archivo privado (sección `archivos-privados` → portada).
        - `_angelitozzz_vista_previa`: sí/no, "Lección de vista previa gratuita".
        - `_angelitozzz_materiales`: IDs de archivos privados de la lección (pestaña "Materiales"), de los tipos de material de la sección `archivos-privados`.
    - **Reproductor:** una sola función, `angelitozzz_cursos_video_html()` en `includes/video.php`, arma el reproductor. Desde el 2026-11-25 es un `<video>` del navegador con la dirección firmada del archivo (sección `acceso`), sin botón de descarga y con la marca de agua (`leccion.md` → video). Cambiar de proveedor sigue siendo cambiar ese archivo.
    - **Primera lección:** la de menor orden del primer módulo. Ahí va el banner de Mundo (`leccion.md`).
    - **Materiales:** son archivos privados (sección `archivos-privados`), de los tipos de material de esa sección. Se muestran con su nombre, su tipo y su tamaño ("PDF · 2,4 MB", "Word · 120 KB"); el "13 páginas" del diseño no se calcula.
    - **Transición (plugin 0.8.0, bloque 2):** mientras el constructor y la lección del tema no pasen a lo nuevo (bloque 4), `_angelitozzz_video` acepta un enlace de Vimeo o el ID de un archivo privado de video, y `_angelitozzz_materiales` (de la lección y del curso) acepta un adjunto PDF o un archivo privado de material. Así `main` sigue funcionando entre bloques.
    - **Migración (bloque 4):** las lecciones que tengan un enlace de Vimeo guardado lo pierden (el campo queda solo para IDs) y los PDF adjuntos pasan a archivos privados; hoy no hay cursos reales cargados, así que no se pierde contenido.
- **Estado:** aprobado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] El texto de la lección no deja pasar etiquetas fuera de la lista permitida (por ejemplo `<script>` o atributos `on*`)
- [x] Un enlace del texto de la lección con `target` sale con `rel="noopener noreferrer"`, al guardar y al mostrar
- [ ] `_angelitozzz_video` solo guarda el ID de un archivo privado de tipo video; un ID que no existe o de otro tipo no se guarda
- [ ] La duración se lee del archivo al subirlo y se ve en el temario y en el constructor
- [ ] La lógica del video (armar el reproductor de la lección) sigue solo en `includes/video.php`
- [ ] Un material de un tipo permitido se guarda y se muestra con su tipo y su tamaño

## Sección: inscripcion

- **Origen:** decisiones de `specs/plan/checklist-cursos.md` (duración del acceso, garantía, regalo) y estados del historial (`usuario-historial-pedido.md`)
- **Destino:** `../../plugins/angelitozzz-cursos/includes/esquema.php`, `../../plugins/angelitozzz-cursos/includes/inscripciones.php`, `../../plugins/angelitozzz-cursos/includes/vencimiento.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** tabla `{prefix}angelitozzz_inscripciones` (la crea la migración 1 de `esquema.php`):
    - **Columnas:**
        - `id`: BIGINT, clave.
        - `usuario_id`, `curso_id`.
        - `pedido_id` y `item_id`: el pedido y la línea de Woo; nulos en un canje.
        - `origen`: `compra` | `regalo`.
        - `inicio`: `DATETIME` en UTC.
        - `vence`: `DATETIME` en UTC; nulo = ilimitado.
        - `estado`: `activa` | `vencida` | `cancelada`.
        - `meses`: duración comprada (0 = ilimitado), para recalcular una fila encadenada si se cancela la anterior.
        - `creado` y `actualizado`.
    - **Índices:** `usuario_id`, `curso_id`, `vence`, y único (`pedido_id`, `item_id`). Ese índice único hace que la misma línea de un pedido nunca inscriba dos veces.
    - **Reglas:**
        - **Inicio:** al quedar pagado el pedido; en un regalo, el día del canje.
        - **Vence:** inicio + `_angelitozzz_duracion_acceso` meses, al final del día en la zona horaria del sitio; 0 = ilimitado (`vence` nulo).
        - **Volver a comprar un curso ya activo** (cambio de la F2, 2026-11-14): cada compra es su propia fila; si el curso está vigente con fecha de fin, la nueva empieza cuando vence la actual (se encadena). Para la alumna es un solo curso con el vencimiento más lejano, y un reembolso de la segunda compra solo quita lo que esa compra dio. Si el acceso vigente es ilimitado, la nueva fila empieza el mismo día. Si se cancela una fila, las que venían encadenadas detrás se adelantan para que no quede un hueco sin acceso. Un bloqueo por usuaria y curso evita que dos pagos simultáneos se encadenen desde la misma fecha; si en 10 segundos no se consigue, no inscribe y devuelve un error (nunca inscribe sin el bloqueo).
        - **Pedido pagado de nuevo** (plugin 0.6.1, 2026-11-19): si una línea ya inscribió y su fila está `cancelada`, vuelve a `activa` como una compra nueva: empieza hoy (o al terminar el acceso vigente del mismo curso) y vence según sus propios meses. No vuelve con las fechas viejas.
        - **"Por expirar":** no es un estado guardado. Se calcula al leer (activa y vence en 15 días o menos).
        - **"Pendiente" del historial:** sale del pedido de Woo (`pending` o `on-hold`), no de esta tabla.
        - **Vencimiento:** una tarea diaria con Action Scheduler de WooCommerce (`angelitozzz_cursos_vencer`) pasa a `vencida` las activas cuyo `vence` ya pasó. El acceso igual se comprueba con la fecha en cada lectura: no depende de que la tarea haya corrido.
        - **Sin alta manual** (decisión del usuario, 2026-11-20): no se inscribe a mano. Para dar un curso sin cobrar se usa un cupón de 100 % y la alumna lo compra.
    - **Registro:** cada alta, extensión o baja queda en el registro de Woo (`wc_get_logger`, fuente `angelitozzz-cursos`) con la usuaria, el curso, el pedido y el motivo.
- **Estado:** aplicado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] Toda consulta a la tabla usa `$wpdb->prepare`
- [x] Un pedido que Woo marca pagado dos veces (`processing` y luego `completed`) crea una sola inscripción
- [x] Un acceso de 4 meses comprado hoy vence en 4 meses, al final de ese día; uno ilimitado no vence
- [x] Una inscripción vencida no da acceso aunque la tarea diaria no haya corrido
- [x] Prueba PHPUnit de alta, extensión, vencimiento y cancelación

## Sección: avance

- **Origen:** temario y "Marcar como completo" de la lección (`leccion/seccion-entera.ref.txt`), tarjetas con progreso de `usuario-escritorio.md` y `usuario-cursos.md`
- **Destino:** `../../plugins/angelitozzz-cursos/includes/esquema.php`, `../../plugins/angelitozzz-cursos/includes/avance.php`, `../../plugins/angelitozzz-cursos/includes/rest.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** tabla `{prefix}angelitozzz_progreso` (la crea la migración 1 de `esquema.php`):
    - **Columnas:** `usuario_id`, `leccion_id`, `curso_id`, `completada` (`DATETIME` UTC o nulo) y `vista` (`DATETIME` UTC).
    - **Clave e índices:** clave (`usuario_id`, `leccion_id`); índice (`usuario_id`, `curso_id`).
    - **Reglas:**
        - **Vista:** abrir una lección con acceso guarda `vista`, que alimenta "Continuar curso" (última lección vista).
        - **Marcar como completa:** `POST angelitozzz/v1/lecciones/<id>/completar` con nonce `wp_rest`; solo la usuaria con sesión y con el curso comprado (o quien gestiona). Una lección de vista previa se ve sin comprar, pero no guarda avance ni "última vista". Se puede desmarcar con `DELETE` en la misma ruta.
        - **Porcentaje:** lecciones completadas / lecciones publicadas del curso, redondeado. Los PDF del curso no cuentan.
        - **Estados del curso para la alumna:** 0 % = "Empezar", 1 a 99 % = "Continuar", 100 % = "Completado".
        - **Pestañas de la cuenta:** Inscritos = todas sus inscripciones (activas y por expirar); Activos = de 0 a 99 %; Completados = 100 %. Las vencidas o canceladas no se listan en "Cursos inscritos" (se ven en el historial).
        - **Escritorio:** el resumen cuenta inscritos, completados y en progreso con las mismas reglas.
    - **Al despublicar o borrar una lección:** se recalcula el porcentaje. El avance de esa lección se borra con la lección.
- **Estado:** aplicado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] Una usuaria no puede completar una lección de un curso sin acceso ni la de otra usuaria (la ruta ignora cualquier `usuario_id` recibido)
- [x] La ruta sin nonce o sin sesión responde 401/403
- [x] El porcentaje, los conteos del escritorio y las pestañas salen de una sola función (no se calculan distinto en cada vista)

## Sección: acceso

- **Origen:** decisiones de `specs/plan/checklist-cursos.md` y reglas de seguridad de `CLAUDE.md`
- **Destino:** `../../plugins/angelitozzz-cursos/includes/acceso.php`, `../../plugins/angelitozzz-cursos/includes/descargas.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** una sola función decide si una usuaria puede ver una lección: `angelitozzz_cursos_puede_ver( $leccion_id, $usuario_id )`.
    - **Es sí cuando:** la lección está publicada y además es vista previa (con el curso publicado), o la usuaria tiene una inscripción `activa` del curso con `vence` nulo o futuro (con el curso publicado o en borrador), o la usuaria tiene `angelitozzz_gestionar_cursos`.
    - **Curso en borrador** (decisión del usuario, F4 bloque 6a): sale del sitio y de la venta, pero las alumnas con inscripción vigente conservan su acceso (lecciones, avance, materiales y su cuenta). La vista previa deja de verse. En la papelera, nadie entra.
    - **Al abrir una lección sin acceso** (`template_redirect`, antes de imprimir nada):
        - sin sesión → `/iniciar-sesion/?redirect_to=<lección>`;
        - con sesión → la solución del curso, con el aviso "Este curso no está en tu cuenta. Puedes comprarlo aquí o canjear un código de regalo." (F5-c: `?aviso=sin-acceso` desde una lección o desde un curso; la landing y `/soluciones-de-sueno/` lo muestran con `aviso-formulario` y `angelitozzz_cursos_aviso_acceso()`);
        - lección en borrador, o curso en borrador sin inscripción vigente → 404, salvo para quien gestiona cursos.
    - **Solución de un curso:** la `solucion` publicada que lo tiene en `solucion_compra_cursos` (o en una etapa). Si no hay, `/soluciones-de-sueno/`.
    - **Entrega de archivos privados (2026-11-25; reemplaza a la descarga con nonce de los PDF):** el video de la lección, sus materiales y los del curso se entregan solo por una dirección firmada.
        - **Dirección:** `/cursos/archivo/<id>/?l=<leccion_o_curso>&e=<vence>&f=<firma>`. La arma el plugin al imprimir la lección (`angelitozzz_cursos_url_archivo()`); el tema nunca la arma.
        - **Firma:** `hash_hmac( 'sha256', archivo|usuaria|lección o curso|vence|sesión, wp_salt( 'auth' ) )`. "Sesión" es el resumen del token de la sesión de WordPress (`wp_get_session_token()`), no el token.
        - **Vence** a las 4 horas (propuesta, con filtro). Al recargar la lección sale una dirección nueva.
        - **En cada pedido** (también en cada pedazo del video) se comprueba, sin confiar en nada de la URL salvo la firma:
            - que la firma sea válida y no esté vencida;
            - que la usuaria de la sesión sea la de la firma y que su sesión sea la misma (cerrar sesión la invalida);
            - que el archivo esté vinculado a esa lección o a ese curso;
            - que la usuaria pueda ver la lección (`angelitozzz_cursos_puede_ver()`) o, si es material del curso, el curso (`angelitozzz_cursos_puede_ver_curso()`). Si vence la inscripción, se reembolsa o se pasa a borrador sin inscripción, deja de entregarse al momento.
        - **Qué ve cada caso:** sin sesión, en otro navegador o en una ventana privada no se ve. Con la sesión de otra alumna, tampoco (la firma es de otra usuaria). La misma alumna en otra pestaña de su navegador sí (es su sesión). No hay límite de sesiones por cuenta (decisión del usuario, 2026-11-25). Única excepción: el video de una lección de **vista previa** (y su portada) se ve sin sesión, como la propia lección (la dirección se firma para "sin usuaria" y sigue venciendo a las 4 horas). Los materiales nunca: ni los del curso ni los de esa misma lección piden menos que sesión con acceso (plugin 0.8.1, revisión de seguridad del bloque 2).
        - **Contexto 0:** la vista previa de quien gestiona cursos (pantalla "Archivos privados" y constructor) firma sin lección ni curso; solo sirve con la capacidad `angelitozzz_gestionar_cursos`.
        - **Por pedazos (HTTP Range):** responde `Accept-Ranges: bytes`. Con `Range: bytes=a-b`, `a-` o `-n` entrega solo ese trozo con `206 Partial Content` y `Content-Range`; un rango imposible da `416`. **Sin `Range`** (plugin 0.8.1): el video y el audio responden `416` (el reproductor del navegador siempre pide por pedazos; así nadie retiene un proceso descargando un video entero); los materiales se descargan enteros con `200`. Lo hace PHP leyendo por bloques (`fopen`, `fseek`, `fread` de 1 MB con `flush`), con un tiempo máximo de 5 minutos por entrega (`set_time_limit(300)`; el hosting tiene `max_execution_time` de 30 s y "sin límite" dejaría retener un proceso). **Pedazos chicos (2026-11-25):** cada respuesta 206 entrega como máximo 4 MB, aunque el navegador pida más (`bytes=a-` sin fin); el navegador pide el siguiente solo. Así cada pedido dura una fracción de segundo y no ocupa por mucho tiempo uno de los 25 procesos a la vez del hosting (Entry Processes; si se llenan, el sitio responde 508). El hosting también limita la lectura del disco a 10 MB/s para toda la cuenta, así que los videos livianos (ver la recomendación de `archivos-privados`) dejan más margen para las alumnas que miran a la vez. No depende de X-Sendfile (decisión del usuario, 2026-11-25). Sin esto, el video no se puede adelantar y en Safari de iPhone ni siquiera se reproduce.
        - **Cabeceras:** el tipo real guardado (`Content-Type`), `X-Content-Type-Options: nosniff`, `Cache-Control: private, no-store, max-age=0` y `Content-Disposition`: `inline` para el video y el audio (se reproducen en la página) y `attachment` con el nombre del archivo saneado para lo que se descarga (`angelitozzz_cursos_nombre_descarga()`, sin el "Privado:").
        - **Si algo falla:** 403 sin detalles, sin revelar si el archivo existe.
    - **Sin listados ni feeds:** la consulta principal de `curso` o `leccion` que no es de una sola entrada (`/?post_type=leccion`, su feed) no devuelve nada, así el texto de una lección solo se ve pasando por este control.
    - **Archivos privados:** no tienen página propia ni aparecen en la REST de WordPress, en la Biblioteca de medios ni en la búsqueda (sección `archivos-privados`).
    - **Nunca se confía en IDs recibidos:** la usuaria es siempre la de la sesión (`get_current_user_id()`).
- **Estado:** aprobado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] Sin sesión, una lección lleva a iniciar sesión y vuelve a la lección al entrar
- [x] Con sesión y sin inscripción (o vencida o cancelada), una lección lleva a la solución del curso
- [x] Cambiar el ID de una descarga o de una lección en la URL no da acceso a algo ajeno
- [x] Una lección de vista previa se ve sin comprar
- [x] Pruebas PHPUnit de `angelitozzz_cursos_puede_ver()` con cada caso
- [x] (0.8.0: pruebas PHPUnit y en el Local) La dirección firmada de un archivo responde 403 sin sesión, con otra usuaria, con otra sesión de la misma usuaria (después de cerrar sesión), vencida, con la firma cambiada, con otro archivo o con la inscripción vencida o cancelada
- [x] (0.8.0, también con curl en el Local) Un pedido con `Range` responde 206 con el trozo justo y `Content-Range`; un rango imposible, 416; sin `Range`, 200 con el archivo entero (desde la 0.8.1, solo los materiales)
- [x] (0.8.1, también con curl en el Local) Un video o un audio sin `Range` responde 416, no el archivo entero
- [x] (0.8.1) Sin sesión, de una lección de vista previa solo se entregan el video y su portada; sus materiales, no
- [x] (0.8.0) Una respuesta 206 nunca entrega más de 4 MB, aunque el pedido sea `bytes=a-`
- [ ] El video se puede adelantar en Chrome, Firefox y Safari (también en iPhone)
- [x] (0.8.0, `tests/test-archivos.php`) Pruebas PHPUnit de la firma, del permiso y de los rangos

## Sección: archivos-privados

- **Origen:** decisiones del usuario del 2026-11-25 (videos fuera de Vimeo, materiales de varios tipos, apartado "Archivos privados" fuera de Medios) y cómo lo resuelve WooCommerce con sus descargables (`woocommerce_uploads`, `class-wc-download-handler.php`, `class-wc-admin-upload-downloadable-product.php`)
- **Destino:** `../../plugins/angelitozzz-cursos/includes/descargas.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** el almacén de los archivos de los cursos que solo ven las alumnas con acceso: el video de cada lección y los materiales. Lo administran la pantalla de `/wp-admin/` (`archivos-privados.md`) y el constructor (`admin-constructor.md`). En el bloque 2 la lógica se reparte en archivos propios del plugin (almacén, subida, entrega); `descargas.php` es el de hoy.
    - **Registro:** un tipo de contenido propio, `angelitozzz_archivo`, no público: sin URL, sin REST, sin búsqueda ni mapa del sitio, y `show_ui` no (lo muestra la pantalla propia). No son adjuntos de WordPress, así que no aparecen en la Biblioteca de medios, WordPress no les genera tamaños de imagen y no tienen página de adjunto. Título = el nombre legible ("Horarios 4 a 9 meses"). Metadatos:
        - `_angelitozzz_ruta`: ruta del archivo relativa al almacén (nombre aleatorio de 24 caracteres más la extensión);
        - `_angelitozzz_mime` y `_angelitozzz_tamano` (bytes), de la revisión al subir;
        - `_angelitozzz_clase`: `video`, `audio`, `pdf`, `office` o `imagen`;
        - `_angelitozzz_duracion`: segundos, solo video y audio;
        - `_angelitozzz_portada`: ID de otro archivo privado de imagen, solo video (ver **Portada**).
    - **Dónde se guardan:**
        - **Fuera de la web**, si está definida la constante `ANGELITOZZZ_CURSOS_RUTA_PRIVADA` en `wp-config.php`. En producción (cPanel, 2026-11-25) es `/home/fizidnwp/angelitozzz-privado`, al lado de `public_html` y no dentro: ninguna URL llega ahí, aunque falte una regla del servidor.
        - **Sin la constante** (el Local), `uploads/angelitozzz-privado/`, como hoy, con su `.htaccess` (`Require all denied`), su `index.php` y la regla `deny` de Nginx (`specs/pruebas/entorno/`).
        - La entrega nunca depende solo del lugar: siempre pasa por la dirección firmada (sección `acceso`).
    - **Tipos permitidos (lista cerrada, decisión del usuario, 2026-11-25):**
        - **Video** (solo como video de una lección): `mp4` (`video/mp4`) y `webm` (`video/webm`).
        - **Material** (lección o curso): `pdf`; `docx`, `xlsx` y `pptx` (Office moderno; no `doc`, `xls` ni `ppt`, que pueden llevar macros); `jpg`/`jpeg`, `png` y `webp`; `mp3` (`audio/mpeg`).
        - **SVG nunca** (puede llevar código), ni ningún otro tipo.
        - Un video no se ofrece como material: si se pudiera descargar desde la lección, la protección del reproductor no serviría.
        - **Revisión del tipo:** la extensión **y** el contenido (`wp_check_filetype_and_ext` con la lista de arriba), más la firma de los primeros bytes: `%PDF-` en PDF; `PK` en Office (son zip); la de JPEG, PNG, WebP, MP3, MP4 (`ftyp`) y WebM. Si no coinciden, no se guarda.
    - **Tope de peso, con advertencia y sin bloqueo (decisión del usuario, 2026-11-25):** video 500 MB, audio 50 MB, PDF 20 MB, Office 30 MB e imagen 5 MB (propuesta; constantes con filtro). Si el archivo pasa el tope de su tipo, se sube igual y se muestra en rojo: "Este <tipo> pesa <peso>; lo recomendado es hasta <tope>. Se subirá igual, pero va a tardar más en cargar para las alumnas. Para los videos, mira en el manual cómo achicarlo con HandBrake." La web no comprime ni convierte nada, tampoco las imágenes.
    - **Recomendación de calidad del video, sin límite (decisión del usuario, 2026-11-25):** la resolución la decide la clienta; la web solo aconseja. Antes de subir, el navegador lee el alto del video (`videoHeight`) y su duración y calcula el peso por minuto. Si el video pasa de 1080p, o de unos 15 MB por minuto, muestra en ámbar (aviso, no error): "Este video es <resolución> y pesa <N> MB por minuto. Para que cargue rápido a las alumnas, te recomendamos exportarlo en 720p o 1080p con HandBrake (mira el manual). Se subirá igual." La subida no se detiene. Si el navegador no puede leer el video, no se muestra nada.
    - **Subida por partes (REST `angelitozzz/v1`, nonce `wp_rest` y capacidad `angelitozzz_gestionar_cursos`):**
        1. `POST /archivos/subidas` con el nombre, el peso y el tipo: revisa la extensión y el tipo declarado y devuelve un ID de subida.
        2. `PUT /archivos/subidas/<id>` con cada trozo (unos 10 MB) como cuerpo y su posición (`Content-Range: bytes a-b/total`, o el parámetro `inicio`). Cada trozo entra en los 30 s del hosting. Un trozo que falla se reintenta solo (3 veces): uno que ya llegó se acepta sin duplicarlo, y uno que se adelanta responde 409 con lo recibido para seguir desde ahí.
        3. `POST /archivos/subidas/<id>/fin`: une los trozos y revisa el tipo real y la firma. Lee la duración de video y audio (`wp_read_video_metadata()` y `wp_read_audio_metadata()`), mueve el archivo al almacén con nombre aleatorio y crea el registro.
        - Los trozos esperan en una carpeta temporal dentro del almacén. La tarea diaria borra las subidas que quedaron a medias hace más de 24 horas.
        - En pantalla, una barra de avance con el porcentaje y "Cancelar".
    - **Otras rutas** (plugin 0.8.0, `includes/archivos-subida.php`, misma capacidad y nonce): `DELETE /archivos/subidas/<id>` (cancelar), `GET /archivos` (la lista), `POST /archivos/<id>` (renombrar), `DELETE /archivos/<id>` (borrar; 409 si está en uso) y `POST /archivos/<id>/portada` (la portada, en un solo envío).
    - **Portada del video:** al subir un video, el navegador toma un cuadro del segundo 1 y lo sube como imagen privada (`_angelitozzz_portada`), con `POST /archivos/<id>/portada`; la portada anterior se borra si ya nada la usa. Es la portada de la lección antes de pulsar play y la miniatura del constructor. Si el navegador no puede (formato que no reproduce), no hay portada y se ve el fondo beige (`leccion.md` → video).
    - **Dónde se usa:** se calcula leyendo `_angelitozzz_video` y `_angelitozzz_materiales` de las lecciones y `_angelitozzz_materiales` de los cursos (con lo que esté en la papelera). Un archivo en uso no se puede borrar: primero se quita de la lección o del curso.
    - **Quitar no es borrar:** quitar un archivo de una lección o de un curso solo lo desvincula; sigue en "Archivos privados" para volver a usarlo. Se borra del disco solo desde esa pantalla y solo si no está en uso.
    - **Migración (bloque 4, junto con el cambio del constructor):** los PDF de materiales que ya existan (adjuntos con `_angelitozzz_material`) pasan a `angelitozzz_archivo`, con su archivo movido al almacén y los IDs actualizados en `_angelitozzz_materiales`. Hoy no hay cursos reales cargados, pero la migración se escribe igual, con su prueba.
    - **Desinstalar:** sin `ANGELITOZZZ_CURSOS_BORRAR_TODO` se conservan los registros y el almacén; con ella se borran los dos.
- **Estado:** aprobado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [x] (0.8.0) Un SVG, un `doc` o cualquier tipo fuera de la lista no se guarda, aunque se le cambie la extensión
- [x] (0.8.0) Un archivo de la lista con el contenido de otro tipo (por ejemplo, un `.pdf` que no empieza con `%PDF-`) no se guarda
- [ ] Un video de 1 GB se sube por partes con su barra de avance; si se corta un trozo, se reintenta
- [ ] Un archivo que pasa el tope de su tipo se sube igual y muestra la advertencia en rojo
- [ ] Un video de más de 1080p, o de más de 15 MB por minuto, muestra la recomendación de HandBrake y se sube igual
- [x] (0.8.0: PHPUnit con la ruta fuera de uploads; 403 con curl en el Local) Con `ANGELITOZZZ_CURSOS_RUTA_PRIVADA`, el archivo queda fuera de la carpeta de la web; sin ella, en `uploads/angelitozzz-privado/` y su URL directa responde 403
- [x] (0.8.0) Los archivos privados no aparecen en la Biblioteca de medios, en `/wp-json/wp/v2/media` ni en la búsqueda
- [x] (0.8.0) Un archivo en uso no se puede borrar; quitarlo de una lección no lo borra
- [x] (0.8.0) Solo quien tiene `angelitozzz_gestionar_cursos` puede subir, listar o borrar (una alumna recibe 403)
- [ ] La migración pasa los PDF que ya existían y sus lecciones los siguen mostrando
- [ ] Pruebas PHPUnit de la revisión del tipo, la subida por partes, "dónde se usa", el borrado y la migración

## Sección: puente-woo

- **Origen:** tabla "Contenido dinámico" (`producto`) de `_shared.md`, `checkout.md` y `solucion.md` (sección `compra`)
- **Destino:** `../../plugins/angelitozzz-cursos/includes/woocommerce.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** WooCommerce cobra y el plugin solo convierte pedidos pagados en inscripciones (y reembolsos en bajas).
    - **Curso ↔ producto:**
        - Cada curso tiene un producto Simple, Virtual, "Vender individualmente", oculto del catálogo y de la búsqueda.
        - Los une `_angelitozzz_producto_id` en el curso y `_angelitozzz_curso_id` en el producto.
        - El constructor crea o actualiza el producto al guardar: nombre, precio normal y precio rebajado (precio de lanzamiento), estado igual al del curso. Virtual y "Vender individualmente" se aplican en cada guardado, no solo al crearlo, y una migración los aplica a los productos que ya existen (F5-b).
    - **Marca de regalo** (sección `regalo`): el carrito recibe `angelitozzz_regalo=1` al agregar (`woocommerce_add_cart_item_data`), la línea muestra "Regalo" (`woocommerce_get_item_data`) y al crear el pedido se guarda `_angelitozzz_regalo` en la línea (`woocommerce_checkout_create_order_line_item`). Destino: `includes/woocommerce.php` (F5-b; el código del regalo es de la F5-c).
        - Solo con `WC_Product` (nunca escribe tablas de Woo directamente).
    - **Pedido pagado:**
        - Con `woocommerce_order_status_processing` y `woocommerce_order_status_completed`, por cada línea cuyo producto tiene `_angelitozzz_curso_id`, se inscribe a la clienta del pedido (`customer_id`).
        - Sin duplicar (índice único `pedido_id` + `item_id`).
        - Una línea ya reembolsada del todo (por cantidad o por monto) no inscribe, aunque el pedido pase después a completado.
        - Si la línea es un regalo, no inscribe: genera el código (sección `regalo`).
    - **Reembolso o cancelación:**
        - Con `woocommerce_order_status_refunded` y `woocommerce_order_status_cancelled`, todas las inscripciones del pedido pasan a `cancelada`.
        - Con un reembolso parcial (`woocommerce_order_refunded`), solo las líneas reembolsadas del todo, por cantidad o por monto. Un reembolso solo de importe que no se asigna a ninguna línea no quita accesos: para devolver un curso, el reembolso se hace desde la línea del pedido.
    - **Pedido sin clienta** (no debería pasar: el checkout exige cuenta): no inscribe y lo registra como error.
    - **Inscripción fallida** (plugin 0.6.1): cada línea que no se pudo inscribir deja una nota en el pedido y una línea en el registro, con la usuaria y el curso. Si falló porque otra inscripción del mismo curso tenía el bloqueo, se reintenta una vez a los 5 minutos (Action Scheduler, `angelitozzz_cursos_reintentar_pedido`), solo si el pedido sigue pagado.
    - **HPOS:** solo `wc_get_order()` y `WC_Order`. La compatibilidad está declarada en `includes/compatibilidad.php`.
    - **Angelita Pro (F6, sin construir):** el plugin de Openpay no soporta WooCommerce Subscriptions, así que la renovación cada 4 meses se define en la F6 (`checklist-cursos.md`). Mientras tanto se vende como un curso directo y el puente lo inscribe igual que a los demás.
- **Estado:** aplicado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] Comprar un curso deja una inscripción activa con su vencimiento; comprar 2 etapas deja 2
- [x] Reembolsar el pedido deja las inscripciones en `cancelada` y quita el acceso
- [x] El producto del curso no aparece en `/tienda/`, en la búsqueda ni en los feeds (plugin 0.7.0: fuera del mapa del sitio, que tampoco lista categorías ni etiquetas de producto, y de las consultas de productos por la REST API, como la búsqueda de la Store API; probado en el Local el 2026-11-19)
- [x] Funciona igual con HPOS activado (plugin 0.7.3, 2026-11-20: `test_puente_con_y_sin_hpos` en `tests/test-woocommerce.php`)
- [x] Pruebas PHPUnit del alta por pedido pagado y la baja por reembolso

## Sección: regalo

- **Origen:** decisión "Regalo" de `specs/plan/checklist-cursos.md` (cupón de Woo de 100 %, un solo uso, 6 meses) y las pantallas de `gracias.md` → `regalo-listo` y `canjear-regalo.md`
- **Destino:** `../../plugins/angelitozzz-cursos/includes/regalos.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** comprar un curso para otra persona genera un código; quien lo recibe lo canjea en `/canjear-regalo/` y queda inscrita desde ese día.
    - **Comprar como regalo:** "Regalar este curso" (`solucion.md`) agrega el producto al carrito con el dato de línea `_angelitozzz_regalo` = 1 (se guarda en la línea del pedido). Un mismo curso puede estar en el carrito para ella y como regalo, como dos líneas.
    - **Código:**
        - Se crea al quedar pagado el pedido (mismos hooks que `puente-woo`), uno por línea de regalo, sin duplicar si Woo avisa dos veces.
        - Es un cupón de Woo (`WC_Coupon`): 100 % de descuento, un solo uso, limitado al producto del curso y con vencimiento a 6 meses de la compra. Así se ve y se puede anular desde Marketing → Cupones.
        - Metadatos del cupón: `_angelitozzz_regalo_curso_id`, `_angelitozzz_regalo_pedido_id` y `_angelitozzz_regalo_item_id`; al canjearse, `_angelitozzz_regalo_inscripcion_id` y `_angelitozzz_regalo_usuaria_id` (así el reembolso encuentra la inscripción del canje). La línea del pedido guarda el código (`_angelitozzz_regalo_codigo`) y el ID del cupón (`_angelitozzz_regalo_cupon_id`).
        - Una línea ya reembolsada del todo no genera código. El vencimiento es a 6 meses de la fecha de pago, al final de ese día en la zona del sitio (misma regla que `inscripcion`).
        - **No sirve en el checkout** (plugin 0.7.0): aplicarlo en el carrito, el checkout o el cupón del hero da "Este código es un regalo: canjéalo en la página "Canjear regalo"." (filtro `woocommerce_coupon_is_valid`, y `woocommerce_coupon_error` para que cualquier otro error de Woo con ese código, como "no se puede aplicar a los productos seleccionados", muestre el mismo aviso).
        - Formato: `ANGZ-XXXX-XXXX-XXXX`, 12 caracteres en mayúscula sin los que se confunden (sin 0, O, 1, I), generados con `random_int`; se reintenta si ya existe. Son 60 bits: el formato del diseño (`ANGZ-` y 4 caracteres, unos 10⁶ códigos) se podía adivinar probando cupones en Woo (`?wc-ajax=apply_coupon` no tiene límite), así que se alargó en el plugin 0.7.4 (revisión de seguridad de la F5, 2026-11-24). El canje acepta el código con o sin guiones, con espacios y en minúsculas, y un código del formato anterior se sigue canjeando.
        - El código se muestra en la página de gracias (`gracias.md` → `regalo-listo`) y llega en el correo del pedido a quien compra (decisión del usuario, 2026-11-19: no hay correo a quien recibe; quien compra lo comparte). El bloque del correo lo imprime el tema (`inc/woocommerce/correos.php`) con `angelitozzz_cursos_regalos_de_pedido()`.
    - **Canje** (`canjear-regalo.md`):
        - No pasa por el checkout: el plugin valida el cupón (existe, es de regalo, no usado, no vencido) e inscribe a la usuaria de la sesión en `_angelitozzz_regalo_curso_id` con `origen` `regalo`, sin `pedido_id` ni `item_id`, y el inicio el día del canje.
        - En la misma operación marca el cupón como usado (por esa usuaria), con un bloqueo para que dos envíos a la vez no lo canjeen dos veces.
        - Si ya tiene ese curso activo, el acceso del canje se encadena después del vigente (regla de `inscripcion`).
        - Límite de intentos fallidos: 5 cada 15 minutos por IP y, con sesión, por usuaria (acción `canje` de la tabla `angelitozzz_intentos`); un solo mensaje de error para inexistente, usado, vencido o anulado.
        - La duración es la del curso en el momento del canje.
        - Sin sesión, el código válido se guarda en un dato temporal del servidor (1 hora) unido a una cookie propia `angelitozzz_canje` (HttpOnly, de un solo uso), y lleva a `/crear-cuenta/` con el aviso "Tu código es válido. Crea tu cuenta (o inicia sesión) para terminar el canje." Al volver a `/canjear-regalo/` con sesión, el canje se completa solo (se vuelve a validar el código).
        - El resultado queda como dato de un solo uso de la usuaria (10 minutos) que lee `angelitozzz_cursos_ultimo_canje()`; los errores, como mensaje de un solo uso con lo escrito.
    - **Reembolso o cancelación del pedido de regalo** (todo el pedido o solo esa línea): si el código no se usó, el cupón se anula (pasa a borrador); si ya se canjeó, la inscripción de quien lo recibió pasa a `cancelada` (y se reencadena lo que venía detrás). Si el pedido se vuelve a pagar, el cupón anulado vuelve a publicarse, o la inscripción del canje se reactiva con fechas nuevas.
    - **Registro:** creación, canje y anulación quedan en `wc_get_logger` (fuente `angelitozzz-cursos`).
- **Estado:** aplicado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] Un pedido pagado con una línea de regalo crea un cupón y no inscribe a quien compró
- [x] El canje inscribe a quien lo usa, con inicio el día del canje, y el cupón queda usado
- [x] Un código usado, vencido o inexistente no inscribe
- [x] Reembolsar el pedido anula el código o cancela la inscripción del regalo
- [x] Pruebas PHPUnit de la creación del código, el canje, el doble canje y el reembolso (plugin 0.7.0, `tests/test-regalos.php`; probado también en el Local el 2026-11-19: compra mixta, correo, gracias, canje con y sin sesión, código en el carrito, reembolso y cancelación)

## Sección: roles

- **Origen:** decisión "por ahora solo Bárbara" (`specs/plan/checklist-cursos.md`) y `usuario-*.md`
- **Destino:** `../../plugins/angelitozzz-cursos/includes/capacidades.php`, `../../plugins/angelitozzz-cursos/includes/cuentas.php`, `../../plugins/angelitozzz-cursos/includes/cuentas-acceso.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** quién hace qué, con capacidades propias.
    - **Alumnas:** rol `customer` de Woo.
    - **Rol `instructora`** (lo crea la migración):
        - capacidades `read`, `upload_files` y `angelitozzz_gestionar_cursos`, más las de `angelitozzz_curso` y `leccion`;
        - sin `edit_posts` ni acceso a otras partes del admin.
    - **Administradora:** recibe también `angelitozzz_gestionar_cursos`. La desactivación y la desinstalación borran el rol y la capacidad; al reactivar se crean de nuevo y las usuarias recuperan su rol.
    - **`wp-admin`:**
        - cerrado para `customer`, que va a `/cuenta/` (F3), y para la `instructora`, que va a `/administrador/` (F4, bloque 6b, al existir el constructor; solo si esa página está publicada). La administradora sigue entrando;
        - quedan permitidos `admin-ajax.php`, `admin-post.php` y la REST API;
        - la barra de administración se oculta para ellas.
    - **Pantallas de entrada** (pedido del usuario, F4 bloque 6b): hay una sola lista de cuentas (la de WordPress) y dos pantallas. `/wp-admin/` sin sesión usa la de WordPress (`wp-login.php`, con sus propios enlaces de registro y de contraseña): es la entrada del equipo. El resto del sitio usa `/iniciar-sesion/` con el diseño. Cualquiera puede entrar por cualquiera de las dos y después cada rol termina en su lugar: alumna en `/cuenta/`, instructora en `/administrador/`; la administradora, en el escritorio de WordPress si entró por `wp-login.php` y en `/administrador/` si entró por `/iniciar-sesion/` sin destino. El límite de intentos vale en las dos. Un navegador tiene una sola sesión: entrar con otra cuenta reemplaza la anterior y cerrar sesión la cierra en las dos pantallas (para probar dos cuentas a la vez, una ventana privada).
    - **Mensajes y límite de intentos** (plugin 0.6.1, 2026-11-19): todo inicio de sesión fallido (formulario del tema, `wp-login.php`, login de Woo, XML-RPC) responde "Correo o contraseña incorrectos.", exista o no la cuenta. Recuperar la contraseña en `wp-login.php` con un correo que no existe lleva a la misma confirmación que si existiera. Los contadores del límite viven en la tabla `angelitozzz_intentos` (migración 3): sumar es una sola consulta, la ventana empieza con el primer intento y un intento ya bloqueado no la alarga. La tarea diaria borra los contadores vencidos. Excepción aceptada: "Crear cuenta" y el checkout avisan si el correo ya tiene cuenta (`crear-cuenta.md`, `checkout.md`).
    - **Página de inicio de cada rol** (`angelitozzz_cursos_inicio_de()`): después de entrar sin destino, y en el "Mi cuenta" del header, la alumna va a `/cuenta/` y quien gestiona cursos (instructora o administradora) a `/administrador/` (decisión del usuario, 2026-11-15).
    - **Bárbara** (decisión del usuario, 2026-11-15): es la dueña del sitio y tiene una cuenta propia, con su correo, de rol **administradora**. Edita el contenido en `/wp-admin/` y los cursos en `/administrador/` (la administradora tiene `angelitozzz_gestionar_cursos`). La cuenta de la agencia es otra. El rol `instructora` queda para quien solo maneje cursos (una ayudante, por ejemplo); no hay registro de instructoras: se lo asigna una administradora.
- **Estado:** aplicado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] Una alumna que entra a `/wp-admin/` va a `/cuenta/`
- [x] Una alumna no puede usar la API del constructor (403)
- [x] Desactivar el plugin no deja el rol ni la capacidad; al reactivarlo vuelven (0.5.1, con su prueba)
- [x] Desinstalar el plugin no deja el rol ni la capacidad (plugin 0.7.3, `tests/test-desinstalar.php`)
- [x] Desactivar el plugin no deja guardadas sus URL (`/cursos/…`, `/cuenta/…`, `/administrador/…`)

## Sección: funciones-publicas

- **Origen:** las vistas que pinta el tema: `usuario-*.md`, `leccion.md`, `admin-*.md`, `solucion.md`
- **Destino:** `../../plugins/angelitozzz-cursos/includes/api.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** el tema solo usa estas funciones y nunca consulta las tablas. Trabajan con la usuaria de la sesión, salvo las que piden `$usuario_id` (así lo dice cada una) y las del administrador, que exigen la capacidad.
    - **Dónde viven (2026-11-18):** `includes/api.php` reúne solo una parte. Las demás están en el archivo de su tema: `acceso.php`, `video.php`, `cuentas*.php`, `admin.php`, `rest-admin.php` y `constructor.php`. Lo público es esta lista, no el archivo. Las diferencias entre la lista, el código y lo que llama el tema están anotadas en `checklist-cursos.md` → "Deuda de la F2 y del tema".
    - **Comprobar que el plugin existe:** el tema verifica que exista la función del plugin que va a usar (`function_exists`) y, si no existe, muestra un aviso en vez de romper la página.
    - **Funciones:**
        - `angelitozzz_cursos_activo()`: el plugin está cargado.
        - `angelitozzz_cursos_de_usuaria( $pestana )`: cursos con avance. `$pestana` es `inscritos`, `activos` o `completados`.
        - `angelitozzz_cursos_resumen()`: conteos del escritorio.
        - `angelitozzz_cursos_progreso( $curso_id )`: porcentaje, lecciones completadas y total, y última lección vista.
        - `angelitozzz_cursos_temario( $curso_id, $actual_id )`: módulos y lecciones con su estado (completada, actual). `$actual_id` es la lección que se está viendo (0 = ninguna).
        - `angelitozzz_cursos_puede_ver( $leccion_id, $usuario_id )`: si la usuaria puede ver la lección. Sin `$usuario_id`, la de la sesión; 0 = sin sesión.
        - `angelitozzz_cursos_datos_tarjeta( $curso_id, $usuario_id, $vence )`: los datos de un curso para `curso-progreso-card` (los arma `de_usuaria()`).
        - `angelitozzz_cursos_puede_gestionar( $usuario_id )`: si puede gestionar cursos (sin `$usuario_id`, la de la sesión). La usa `/administrador/`.
        - `angelitozzz_cursos_redirect_seguro( $url, $defecto )`: la vuelta de un `redirect_to`, solo si es de este sitio. La usan iniciar sesión y crear cuenta.
        - `angelitozzz_cursos_video_html( $leccion_id )`: el reproductor (desde el 2026-11-25, el `<video>` con la dirección firmada, la marca de agua y su botón de pantalla completa; vacío si la usuaria no puede ver la lección o no hay video).
        - `angelitozzz_cursos_video_portada( $leccion_id )` (2026-11-25): la dirección firmada de la portada del video, o vacío.
        - `angelitozzz_cursos_url_archivo( $archivo_id, $contexto_id )` (2026-11-25): la dirección firmada de un archivo privado para la usuaria de la sesión. `$contexto_id` es la lección o el curso al que está vinculado. Vacío si no tiene permiso.
        - `angelitozzz_cursos_video_url( $url, $opciones )` (F5-c): **se quita en el bloque 5** (2026-11-25). Era la dirección del reproductor de Vimeo para `video-portada`; los videos de las landings pasan a ser archivos públicos de ACF (`solucion.md`) y el tema ya no la llama.
        - `angelitozzz_cursos_puede_completar( $leccion_id, $usuario_id )` (F5-c): si puede guardar avance (ve la lección y tiene el curso, o gestiona cursos). Acción de formulario `angelitozzz_completar_leccion` (`leccion_id`, `marcar`) para "Marcar como completo" sin JS.
        - `angelitozzz_cursos_aviso_acceso()` (F5-c): el texto del aviso "Este curso no está en tu cuenta…" si la página llegó con `?aviso=sin-acceso`; si no, vacío.
        - `angelitozzz_cursos_nombre_descarga( $adjunto_id )` (F5-c): el nombre del archivo descargado.
        - `angelitozzz_cursos_texto_leccion( $leccion_id )` (0.7.2): el texto de la lección listo para imprimir (`the_content` y después la lista permitida, con `rel` en los enlaces con `target`); vacío si la usuaria no puede ver la lección.
        - `angelitozzz_cursos_materiales( $id )`: nombre, tamaño y URL de descarga firmada. Desde el 2026-11-25 también `tipo` (`pdf`, `office`, `imagen` o `audio`) y `tipo_texto` ("PDF", "Word", "Excel", "PowerPoint", "Imagen", "Audio"), para el ícono y la línea "<tipo> · <tamaño>"; la URL es la dirección firmada de `url_archivo()`.
        - `angelitozzz_cursos_estado_linea( $pedido_id, $item_id )` (F5-c): el estado de una línea para el historial, `clave` y `texto`. Línea propia: `activo`, `por_expirar`, `vencido`, `cancelado` o `pendiente`. Línea de regalo: `regalo_sin_canjear`, `regalo_canjeado`, `regalo_vencido`, `cancelado` o `pendiente`. El tema elige el color por la clave.
        - `angelitozzz_cursos_estado_acceso( $pedido_id, $item_id )`: solo el texto de `estado_linea()`.
        - `angelitozzz_cursos_es_primera_leccion( $leccion_id )`.
        - `angelitozzz_cursos_solucion_de( $curso_id )`.
        - **Administrador (F4, bloque 6; `includes/admin.php`):** exigen la capacidad `angelitozzz_gestionar_cursos`; sin ella devuelven datos vacíos.
            - `angelitozzz_cursos_admin_resumen()`: `estudiantes` (alumnas distintas con al menos una inscripción, de cualquier estado), `cursos` (publicados), `ganancias` y `ganancias_texto` (con el formato de moneda de Woo, en texto plano). Las ganancias son la suma de las líneas de productos de cursos en pedidos pagados (procesando o completados), menos lo reembolsado de cada línea; incluyen los regalos.
            - `angelitozzz_cursos_admin_lista( $opciones )`: `estado` (`publicados` o `todos`, por defecto `todos`), `orden` (`titulo` o `reciente`, por defecto `reciente`) y `pagina` (12 por página; 0 = todos). Devuelve `cursos`, `total`, `paginas` y `pagina`. Cada curso trae `id`, `titulo`, `estado` (`publicado` o `borrador`), `imagen_id`, `estudiantes` (alumnas con acceso hoy o encadenado), `fecha` (`d/m/Y`), `precio` (el rebajado si lo hay, en texto plano), `url_editar` y `url_sitio` (su solución, o vacío si no está en ninguna).
            - `angelitozzz_cursos_vista_admin()`: la vista de `/administrador/` (`escritorio`, `cursos` o `constructor`), con `curso_id`, `paso` (1, 2 o 3) y `pagina`.
            - `angelitozzz_cursos_url_admin( $vista, $curso_id, $paso )`: URL de `escritorio`, `cursos`, `nuevo` o `editar`.
            - `angelitozzz_cursos_aviso_publicado( $curso_id )` (`includes/rest-admin.php`): el texto del aviso "¡Curso publicado!", que cambia si el curso no está en ninguna solución.
            - `angelitozzz_cursos_pendientes_publicar( $curso_id )`: lo que falta para publicar (`clave`, `texto`, `bloquea`), para "Antes de publicar" del constructor. Publicar usa la misma regla sin mirar quién pregunta (`angelitozzz_cursos_faltan_para_publicar()`, interna), así que nunca publica con algo pendiente.
            - `angelitozzz_cursos_admin_curso( $curso_id )` (F4, bloque 6b; `includes/constructor.php`): todo lo que muestra el constructor. Campos del paso 1 (`titulo`, `descripcion`, `edad`, `tipo`, `duracion`, `mes_mundo`, `precio`, `precio_lanzamiento` del producto de Woo), `portada_id` y `portada_url`, `estado`, `estudiantes` (con acceso), `modulos` con sus `items` en orden (lecciones: `id`, `titulo`, `video`, `duracion`, `duracion_texto`, `miniatura`, `contenido`, `extracto`, `vista_previa`, `materiales`; PDF del curso: `id`, `nombre`, `tamano`), `totales` (módulos, lecciones y duración) y `pendientes`. Con 0, los valores de un curso nuevo. Desde el 2026-11-25, `video` es el ID del archivo privado con su `nombre`, y `miniatura` es la dirección firmada de su portada; los materiales traen también `tipo`.
            - `angelitozzz_cursos_admin_archivos( $opciones )` (2026-11-25): la lista de "Archivos privados" para la pantalla de `/wp-admin/` y para "Elegir de mis archivos" del constructor. Filtros `clase` (`video` o `material`), `buscar` y `pagina` (24 por página). Devuelve `archivos`, `total`, `paginas` y `pagina`. Cada archivo trae `id`, `nombre`, `clase`, `extension`, `mime`, `tamano` y `tamano_texto`, `tipo_texto`, `duracion` y `duracion_texto`, `portada` y `portada_url` (firmada), `fecha`, `en_uso` (lecciones y cursos donde está, con su enlace al constructor), `se_puede_borrar` y `url_ver` (dirección firmada de contexto 0, para la vista previa).
        - **Cuentas (F3):**
            - `angelitozzz_cursos_url_envio()` y `angelitozzz_cursos_campos_ocultos( $accion, $volver_a )`: a dónde se envía un formulario y sus campos ocultos (acción, nonce, `redirect_to`). Acciones: `angelitozzz_iniciar_sesion`, `angelitozzz_crear_cuenta`, `angelitozzz_recuperar`, `angelitozzz_nueva_contrasena`, `angelitozzz_perfil_datos`, `angelitozzz_perfil_contrasena`.
            - `angelitozzz_cursos_mensaje()`: el resultado del último envío (tipo `error` o `aviso`, texto y lo ya escrito, nunca contraseñas), una sola vez.
            - `angelitozzz_cursos_reglas_contrasena( $clave )`: el estado de las 4 reglas, para la lista `reglas-contrasena`.
            - `angelitozzz_cursos_url_pagina( $pagina, $vista )` y `angelitozzz_cursos_vista_cuenta()`: URL de las páginas de cuentas y la vista de `/cuenta/` (escritorio, perfil, cursos o pedidos).
            - `angelitozzz_cursos_url_login( $volver )`: URL de `/iniciar-sesion/` con su vuelta; si la vuelta es otra página de acceso no la anida (F4, bloque 6b). La usa el botón "Iniciar sesión" del header.
            - `angelitozzz_cursos_inicio_de( $usuario_id )` (pide la usuaria: el header pasa la de la sesión): la página de inicio de cada rol (alumna: `/cuenta/`; quien gestiona cursos, instructora o administradora: `/administrador/`). La usa "Mi cuenta" del header.
            - `angelitozzz_cursos_es_pagina_acceso( $url )`: si una URL es de iniciar sesión, crear cuenta o recuperar contraseña (no sirven como vuelta).
            - `angelitozzz_cursos_datos_perfil()`: nombre, apellidos, correo, teléfono y el correo pendiente de confirmar.
        - **Compra (F5-b):**
            - `angelitozzz_cursos_producto_de( $curso_id )`: el ID del producto de Woo del curso (0 si no tiene). El tema lee de ahí el precio con `wc_get_product()`.
            - `angelitozzz_cursos_linea_carrito_es_regalo( $linea )`: si una línea de `WC()->cart->get_cart()` es un regalo (`includes/woocommerce.php`).
            - `angelitozzz_cursos_datos_producto( $producto_id )`: `curso_id` (0 si el producto no es de un curso), `meses` (0 = ilimitado) y `acceso_texto` ("Acceso por 6 meses" o "Acceso ilimitado"), para el drawer, el carrito, el checkout y gracias.
        - `angelitozzz_cursos_pedido_cursos( $pedido_id )`: los cursos de un pedido de la usuaria (no regalo), con duración, primera lección y `mes_mundo`, para la página de gracias.
        - `angelitozzz_cursos_pedido_regalos( $pedido_id )`: los regalos de un pedido de la usuaria (`curso_id`, `titulo`, `codigo`), para la página de gracias.
        - `angelitozzz_cursos_regalos_de_pedido( $pedido )` (F5-c): lo mismo, pero recibe el `WC_Order` que entrega WooCommerce, para el correo del pedido (sin sesión).
        - `angelitozzz_cursos_pedido_con_mundo( $pedido )` (F5-c): si el pedido (`WC_Order`) lleva el bloque del mes gratis en Mundo: al menos un curso propio (no regalo, no devuelto) con `mes_mundo`. La usan gracias y el correo.
        - `angelitozzz_cursos_datos_curso( $curso_id )` (F5-c): `titulo`, `tipo`, `mes_mundo` y `meses` de un curso, para la lección (mensaje de Angelita Pro y banner de Mundo).
        - Acción de formulario `angelitozzz_canjear_regalo` (F5-c, con o sin sesión; campo `codigo`), con `angelitozzz_cursos_campos_ocultos()`.
        - `angelitozzz_cursos_puede_ver_curso( $curso_id, $usuario_id )` (pide la usuaria): si la usuaria compró el curso (lo usa la lección para el botón de completar, los candados del temario y el PDF del curso).
        - `angelitozzz_cursos_ultimo_canje()`: lo que muestra `/canjear-regalo/`, una sola vez. Justo después de canjear, `estado` `canjeado` con `curso`, `meses` (0 = ilimitado), `primera_leccion`, `cursos_url` y `empieza` (la fecha si el acceso quedó encadenado a uno vigente; vacío si empieza hoy); tras un envío con error, `estado` `canje` con `valor` (lo escrito) y `error`; si no, vacío.
    - **Los datos ya vienen saneados, pero no escapados.** El tema escapa siempre al imprimir.
- **Estado:** aprobado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] Ninguna plantilla del tema hace consultas a las tablas del plugin
- [x] Con el plugin desactivado, las páginas de la cuenta y de la lección muestran un aviso y no un error fatal
