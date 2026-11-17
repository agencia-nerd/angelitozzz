# Página: Cursos: modelo de datos

- **Resumen:** Qué guarda el plugin `angelitozzz-cursos` y con qué reglas: cursos, lecciones, inscripciones, avance, vencimiento, acceso, descargas, puente con WooCommerce, roles y las funciones que usa el tema. No es una página que se visite: es la base de la F2 y de los specs de la lección, la cuenta y el administrador.
- **Fuente:** `specs/plan/checklist-cursos.md`
- **Plantilla:** ninguna
- **Tipo:** detalle
- **Contenido:** curso
- **Secciones (en orden):** curso, leccion, inscripcion, avance, acceso, puente-woo, regalo, roles, funciones-publicas
- **Skills usadas:** ninguna. Es código del plugin `angelitozzz-cursos` (sin skill que lo cubra: se sigue la documentación oficial de WordPress y WooCommerce)
- **Grupo ACF:** ninguno
- **Ubicación:** tipo de contenido = curso
- **Padre:** ninguno
- **Notas de página:**
    - Decisiones de `specs/plan/checklist-cursos.md` (2026-11-13): LMS a medida, sin Tutor; cobro con WooCommerce + Niubiz; videos en Vimeo (ocultos, sin restricción de dominio); sin cuestionarios, certificados ni comentarios; por ahora solo Bárbara administra.
    - Los datos de cursos y lecciones los escribe el constructor de Bárbara (`admin-constructor.md`), no el admin de WordPress ni ACF. Las capturas del constructor están en `specs/figma/lectura/admin-cursos/constructor-*.png`; de ahí salen los campos de `curso` y `leccion`.
    - Todo en el plugin: prefijo `angelitozzz_` en funciones, opciones, hooks, metadatos (`_angelitozzz_*`) y tablas (`{prefix}angelitozzz_*`); text domain `angelitozzz-cursos`; las tablas se crean con migraciones versionadas (`includes/esquema.php`).
    - Fechas guardadas en UTC (`DATETIME`); se muestran en la zona horaria del sitio (`wp_date`).
    - Privacidad: al borrar una usuaria se borran sus inscripciones y su avance (`delete_user`), y el plugin registra el exportador y el borrador de datos personales de WordPress (Herramientas → Exportar / Borrar datos personales).
    - Desinstalar el plugin borra sus tablas y opciones; los cursos y lecciones (posts) se conservan, salvo que esté definida la constante `ANGELITOZZZ_CURSOS_BORRAR_TODO` (propuesta, para no perder contenido por un clic).
- **Estado:** aprobado

## Sección: curso

- **Origen:** pasos 1 y 3 del constructor (`specs/figma/lectura/admin-cursos/constructor-basico-formulario.png`, `constructor-adicional-listo.png`; ver `admin-constructor.md`) y tarjetas de la lista (`admin-cursos/seccion.ref.txt`, nodo 4184:29718)
- **Destino:** `wp-content/plugins/angelitozzz-cursos/includes/tipos.php`
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
        - `_angelitozzz_mes_mundo`: sí/no, "Incluye mes gratis en Mundo" (lo marca Bárbara en el constructor): muestra el banner de Mundo en la primera lección del curso (`leccion.md`).
        - `_angelitozzz_materiales`: PDF del curso, cada uno con `adjunto_id`, `modulo` (`id` del módulo) y `orden`, porque en el constructor se agregan dentro de un módulo ("+ Agregar descargable", `admin-constructor.md`). En la lección van al final del temario, sin check (`leccion.md`).
    - **Precio:** "Precio (USD)" y "Precio de lanzamiento" no se guardan en el curso. Son el precio normal y el rebajado de su producto de Woo, que el constructor actualiza.
    - **Paso 3 del diseño:** "Qué aprenderás", "Requisitos", "Para quién es" y "Duración total" no se guardan (decisión del usuario, 2026-11-13): los textos de venta están en la solución. La duración total se calcula sumando las lecciones.
- **Estado:** aplicado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] Solo quien tiene `angelitozzz_gestionar_cursos` puede crear o editar cursos, en el constructor o en el admin
- [x] `/cursos/<curso>/` nunca muestra contenido: redirige según el acceso
- [x] Los cursos no aparecen en la búsqueda del sitio ni en el mapa del sitio
- [x] Cada metadato se guarda saneado (texto, entero de una lista permitida, IDs que existen)

## Sección: leccion

- **Origen:** paso 2 del constructor y modal "Editar lección" (`constructor-contenido-carga.png`, `constructor-modal-editar-modulo.png`) y la lección (`specs/figma/lectura/leccion/seccion-entera.ref.txt`)
- **Destino:** `wp-content/plugins/angelitozzz-cursos/includes/tipos.php`, `includes/video.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** tipo de contenido `leccion`, hijo de un `curso`:
    - `post_parent` = el curso; `menu_order` = el orden dentro de su módulo.
    - URL `/cursos/<curso>/<leccion>/`.
    - Mismas capacidades que `curso`; fuera de la búsqueda y del mapa del sitio.
    - **Datos nativos:** título = "Título de la lección"; contenido = "Descripción de la lección".
    - **Contenido:** solo se permite negrita, cursiva, listas, enlaces y párrafos, filtrado con `wp_kses` y una lista propia al guardar y al mostrar. Un párrafo que empieza con "PD:" se muestra solo como cita (`leccion.md`), sin marcado especial en el texto guardado.
    - **Metadatos:**
        - `_angelitozzz_modulo`: `id` del módulo del curso.
        - `_angelitozzz_video`: enlace de Vimeo, guardado solo si es una URL `https://vimeo.com/…` o `https://player.vimeo.com/…` válida.
        - `_angelitozzz_duracion`: segundos, leídos de Vimeo con oEmbed al guardar ("se detecta sola").
        - `_angelitozzz_miniatura`: URL de la miniatura del video, leída de Vimeo con oEmbed junto con la duración (F4, bloque 6b: la muestra el modal de la lección del constructor).
        - `_angelitozzz_vista_previa`: sí/no, "Lección de vista previa gratuita".
        - `_angelitozzz_materiales`: IDs de PDF de la lección (pestaña "Materiales").
    - **Reproductor:** una sola función, `angelitozzz_cursos_video_html()` en `includes/video.php`, arma el iframe del proveedor. Hoy es Vimeo con `dnt=1`; cambiar de proveedor es cambiar esa función.
    - **Primera lección:** la de menor orden del primer módulo. Ahí va el banner de Mundo (`leccion.md`).
    - **Materiales (PDF):** se suben a una carpeta privada `uploads/angelitozzz-privado/`, con nombre aleatorio y regla que impide el acceso directo. Se entregan solo por la descarga con permiso (sección `acceso`). Tipo verificado por contenido (`wp_check_filetype_and_ext`, solo `application/pdf`) y tamaño máximo (propuesta: 20 MB). Se muestran con su nombre y su tamaño ("PDF · 2,4 MB"); el "13 páginas" del diseño no se calcula.
    - **Servidor:** la regla de la carpeta privada depende del servidor (`.htaccess` en Apache, regla `location` en Nginx). Se confirma con el hosting antes del deploy; la descarga nunca depende solo de esa regla, porque los nombres son aleatorios y no se listan.
- **Estado:** aplicado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] Un enlace de video que no es de Vimeo no se guarda
- [x] El texto de la lección no deja pasar etiquetas fuera de la lista permitida (por ejemplo `<script>` o atributos `on*`)
- [x] Un PDF no se puede abrir con la URL de `uploads/` (403) y un archivo que no es PDF no se sube
- [x] La lógica del video (validar el enlace, leer duración y miniatura, armar el reproductor de la lección) está en `includes/video.php`; los textos de la interfaz pueden nombrar al proveedor

## Sección: inscripcion

- **Origen:** decisiones de `specs/plan/checklist-cursos.md` (duración del acceso, garantía, regalo) y estados del historial (`usuario-historial-pedido.md`)
- **Destino:** `wp-content/plugins/angelitozzz-cursos/includes/esquema.php` (migración 1), `includes/inscripciones.php`, `includes/vencimiento.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** tabla `{prefix}angelitozzz_inscripciones`:
    - **Columnas:**
        - `id`: BIGINT, clave.
        - `usuario_id`, `curso_id`.
        - `pedido_id` y `item_id`: el pedido y la línea de Woo; nulos en canje o alta manual.
        - `origen`: `compra` | `regalo` | `manual`.
        - `inicio`: `DATETIME` en UTC.
        - `vence`: `DATETIME` en UTC; nulo = ilimitado.
        - `estado`: `activa` | `vencida` | `cancelada`.
        - `meses`: duración comprada (0 = ilimitado), para recalcular una fila encadenada si se cancela la anterior.
        - `creado` y `actualizado`.
    - **Índices:** `usuario_id`, `curso_id`, `vence`, y único (`pedido_id`, `item_id`). Ese índice único hace que la misma línea de un pedido nunca inscriba dos veces.
    - **Reglas:**
        - **Inicio:** al quedar pagado el pedido; en un regalo, el día del canje.
        - **Vence:** inicio + `_angelitozzz_duracion_acceso` meses, al final del día en la zona horaria del sitio; 0 = ilimitado (`vence` nulo).
        - **Volver a comprar un curso ya activo** (cambio de la F2, 2026-11-14): cada compra es su propia fila; si el curso está vigente con fecha de fin, la nueva empieza cuando vence la actual (se encadena). Para la alumna es un solo curso con el vencimiento más lejano, y un reembolso de la segunda compra solo quita lo que esa compra dio. Si el acceso vigente es ilimitado, la nueva fila empieza el mismo día. Si se cancela una fila, las que venían encadenadas detrás se adelantan para que no quede un hueco sin acceso. Un bloqueo por usuaria y curso evita que dos pagos simultáneos se encadenen desde la misma fecha.
        - **"Por expirar":** no es un estado guardado. Se calcula al leer (activa y vence en 15 días o menos).
        - **"Pendiente" del historial:** sale del pedido de Woo (`pending` o `on-hold`), no de esta tabla.
        - **Vencimiento:** una tarea diaria con Action Scheduler de WooCommerce (`angelitozzz_cursos_vencer`) pasa a `vencida` las activas cuyo `vence` ya pasó. El acceso igual se comprueba con la fecha en cada lectura: no depende de que la tarea haya corrido.
        - **Alta manual:** solo la administradora o la instructora, desde el administrador (propuesta para F5), con `origen` `manual`.
    - **Registro:** cada alta, extensión o baja queda en el registro de Woo (`wc_get_logger`, fuente `angelitozzz-cursos`) con la usuaria, el curso, el pedido y el motivo.
- **Estado:** aprobado

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
- **Destino:** `wp-content/plugins/angelitozzz-cursos/includes/esquema.php` (migración 1), `includes/avance.php`, `includes/rest.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** tabla `{prefix}angelitozzz_progreso`:
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
- **Destino:** `wp-content/plugins/angelitozzz-cursos/includes/acceso.php`, `includes/descargas.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** una sola función decide si una usuaria puede ver una lección: `angelitozzz_cursos_puede_ver( $leccion_id, $usuario_id )`.
    - **Es sí cuando:** la lección está publicada y además es vista previa (con el curso publicado), o la usuaria tiene una inscripción `activa` del curso con `vence` nulo o futuro (con el curso publicado o en borrador), o la usuaria tiene `angelitozzz_gestionar_cursos`.
    - **Curso en borrador** (decisión del usuario, F4 bloque 6a): sale del sitio y de la venta, pero las alumnas con inscripción vigente conservan su acceso (lecciones, avance, materiales y su cuenta). La vista previa deja de verse. En la papelera, nadie entra.
    - **Al abrir una lección sin acceso** (`template_redirect`, antes de imprimir nada):
        - sin sesión → `/iniciar-sesion/?redirect_to=<lección>`;
        - con sesión → la solución del curso, con el aviso "Este curso no está en tu cuenta" (propuesta);
        - lección en borrador, o curso en borrador sin inscripción vigente → 404, salvo para quien gestiona cursos.
    - **Solución de un curso:** la `solucion` publicada que lo tiene en `solucion_compra_cursos` (o en una etapa). Si no hay, `/soluciones-de-sueno/`.
    - **Descargas de materiales:**
        - URL `/cursos/descargar/<id>/?_wpnonce=…`.
        - Comprueba sesión, nonce y que el PDF pertenezca a una lección o curso que la usuaria puede ver.
        - Entrega el archivo con `Content-Type: application/pdf`, `Content-Disposition: attachment`, `X-Content-Type-Options: nosniff` y sin caché pública.
        - Si algo falla, 403, sin revelar si el archivo existe.
    - **Sin listados ni feeds:** la consulta principal de `curso` o `leccion` que no es de una sola entrada (`/?post_type=leccion`, su feed) no devuelve nada, así el texto de una lección solo se ve pasando por este control.
    - **Materiales privados:** el adjunto de cada PDF se guarda con estado `private` y su página de adjunto da 404 (salvo para quien gestiona), para que la URL del archivo no se revele recorriendo IDs. La subida exige poder editar el curso o la lección y comprueba, además del tipo, que el archivo empiece con `%PDF-`.
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

## Sección: puente-woo

- **Origen:** tabla "Contenido dinámico" (`producto`) de `_shared.md`, `checkout.md` y `solucion.md` (sección `compra`)
- **Destino:** `wp-content/plugins/angelitozzz-cursos/includes/woocommerce.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** WooCommerce cobra y el plugin solo convierte pedidos pagados en inscripciones (y reembolsos en bajas).
    - **Curso ↔ producto:**
        - Cada curso tiene un producto Simple, Virtual, "Vender individualmente", oculto del catálogo y de la búsqueda.
        - Los une `_angelitozzz_producto_id` en el curso y `_angelitozzz_curso_id` en el producto.
        - El constructor crea o actualiza el producto al guardar: nombre, precio normal y precio rebajado (precio de lanzamiento), estado igual al del curso.
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
    - **HPOS:** solo `wc_get_order()` y `WC_Order`. La compatibilidad está declarada en `includes/compatibilidad.php`.
    - **Angelita Pro (F6):** si se usa WooCommerce Subscriptions, cada renovación pagada extiende la inscripción y la cancelación de la suscripción la deja vencer.
- **Estado:** aprobado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] Comprar un curso deja una inscripción activa con su vencimiento; comprar 2 etapas deja 2
- [x] Reembolsar el pedido deja las inscripciones en `cancelada` y quita el acceso
- [x] El producto del curso no aparece en `/tienda/`, en la búsqueda ni en los feeds
- [x] Funciona igual con HPOS activado
- [x] Pruebas PHPUnit del alta por pedido pagado y la baja por reembolso

## Sección: regalo

- **Origen:** decisión "Regalo" de `specs/plan/checklist-cursos.md` (cupón de Woo de 100 %, un solo uso, 6 meses) y las pantallas de `gracias.md` → `regalo-listo` y `canjear-regalo.md`
- **Destino:** `wp-content/plugins/angelitozzz-cursos/includes/regalos.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** comprar un curso para otra persona genera un código; quien lo recibe lo canjea en `/canjear-regalo/` y queda inscrita desde ese día.
    - **Comprar como regalo:** "Regalar este curso" (`solucion.md`) agrega el producto al carrito con el dato de línea `_angelitozzz_regalo` = 1 (se guarda en la línea del pedido). Un mismo curso puede estar en el carrito para ella y como regalo, como dos líneas.
    - **Código:**
        - Se crea al quedar pagado el pedido (mismos hooks que `puente-woo`), uno por línea de regalo, sin duplicar si Woo avisa dos veces.
        - Es un cupón de Woo (`WC_Coupon`): 100 % de descuento, un solo uso, limitado al producto del curso y con vencimiento a 6 meses de la compra. Así se ve y se puede anular desde Marketing → Cupones.
        - Metadatos del cupón: `_angelitozzz_regalo_curso_id`, `_angelitozzz_regalo_pedido_id` y `_angelitozzz_regalo_item_id`. La línea del pedido guarda el código (`_angelitozzz_regalo_codigo`).
        - Formato del diseño: `ANGZ-` y 4 caracteres en mayúscula sin los que se confunden (sin 0, O, 1, I), generados con `random_int`; se reintenta si ya existe.
        - El código se muestra en la página de gracias (`gracias.md` → `regalo-listo`) y llega en el correo del pedido (F5).
    - **Canje** (`canjear-regalo.md`):
        - No pasa por el checkout: el plugin valida el cupón (existe, es de regalo, no usado, no vencido) e inscribe a la usuaria de la sesión en `_angelitozzz_regalo_curso_id` con `origen` `regalo`, sin `pedido_id` ni `item_id`, y el inicio el día del canje.
        - En la misma operación marca el cupón como usado (por esa usuaria), con un bloqueo para que dos envíos a la vez no lo canjeen dos veces.
        - Si ya tiene ese curso activo, el acceso del canje se encadena después del vigente (regla de `inscripcion`).
        - Límite de intentos por IP y por usuaria; un solo mensaje de error para inexistente, usado o vencido.
        - Sin sesión, el código válido se guarda en un dato temporal del servidor unido a una cookie de un solo uso, y se completa al volver con sesión.
    - **Reembolso del pedido de regalo:** si el código no se usó, el cupón se anula (pasa a borrador); si ya se canjeó, la inscripción de quien lo recibió pasa a `cancelada`.
    - **Registro:** creación, canje y anulación quedan en `wc_get_logger` (fuente `angelitozzz-cursos`).
- **Estado:** aprobado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] Un pedido pagado con una línea de regalo crea un cupón y no inscribe a quien compró
- [ ] El canje inscribe a quien lo usa, con inicio el día del canje, y el cupón queda usado
- [ ] Un código usado, vencido o inexistente no inscribe
- [ ] Reembolsar el pedido anula el código o cancela la inscripción del regalo
- [ ] Pruebas PHPUnit de la creación del código, el canje, el doble canje y el reembolso

## Sección: roles

- **Origen:** decisión "por ahora solo Bárbara" (`specs/plan/checklist-cursos.md`) y `usuario-*.md`
- **Destino:** `wp-content/plugins/angelitozzz-cursos/includes/capacidades.php`, `includes/cuentas.php`, `includes/cuentas-acceso.php`
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
- [x] Desactivar y desinstalar el plugin no deja el rol ni la capacidad; al reactivarlo vuelven

## Sección: funciones-publicas

- **Origen:** las vistas que pinta el tema: `usuario-*.md`, `leccion.md`, `admin-*.md`, `solucion.md`
- **Destino:** `wp-content/plugins/angelitozzz-cursos/includes/api.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** el tema solo usa estas funciones y nunca consulta las tablas. Todas trabajan con la usuaria de la sesión, salvo las del administrador, que exigen la capacidad.
    - **Comprobar que el plugin existe:** el tema verifica `function_exists( 'angelitozzz_cursos_activo' )` y, si no existe, muestra un aviso en vez de romper la página.
    - **Funciones:**
        - `angelitozzz_cursos_activo()`: el plugin está cargado.
        - `angelitozzz_cursos_de_usuaria( $pestana )`: cursos con avance. `$pestana` es `inscritos`, `activos` o `completados`.
        - `angelitozzz_cursos_resumen()`: conteos del escritorio.
        - `angelitozzz_cursos_progreso( $curso_id )`: porcentaje, lecciones completadas y total, y última lección vista.
        - `angelitozzz_cursos_temario( $curso_id )`: módulos y lecciones con su estado (completada, actual).
        - `angelitozzz_cursos_puede_ver( $leccion_id )`: si la usuaria puede ver la lección.
        - `angelitozzz_cursos_video_html( $leccion_id )`: el reproductor.
        - `angelitozzz_cursos_materiales( $id )`: nombre, tamaño y URL de descarga firmada.
        - `angelitozzz_cursos_estado_acceso( $pedido_id, $item_id )`: Activo, Por expirar, Vencido, Cancelado o Pendiente, para el historial.
        - `angelitozzz_cursos_es_primera_leccion( $leccion_id )`.
        - `angelitozzz_cursos_solucion_de( $curso_id )`.
        - **Administrador (F4, bloque 6; `includes/admin.php`):** exigen la capacidad `angelitozzz_gestionar_cursos`; sin ella devuelven datos vacíos.
            - `angelitozzz_cursos_admin_resumen()`: `estudiantes` (alumnas distintas con al menos una inscripción, de cualquier estado), `cursos` (publicados), `ganancias` y `ganancias_texto` (con el formato de moneda de Woo, en texto plano). Las ganancias son la suma de las líneas de productos de cursos en pedidos pagados (procesando o completados), menos lo reembolsado de cada línea; incluyen los regalos.
            - `angelitozzz_cursos_admin_lista( $opciones )`: `estado` (`publicados` o `todos`, por defecto `todos`), `orden` (`titulo` o `reciente`, por defecto `reciente`) y `pagina` (12 por página; 0 = todos). Devuelve `cursos`, `total`, `paginas` y `pagina`. Cada curso trae `id`, `titulo`, `estado` (`publicado` o `borrador`), `imagen_id`, `estudiantes` (alumnas con acceso hoy o encadenado), `fecha` (`d/m/Y`), `precio` (el rebajado si lo hay, en texto plano), `url_editar` y `url_sitio` (su solución, o vacío si no está en ninguna).
            - `angelitozzz_cursos_vista_admin()`: la vista de `/administrador/` (`escritorio`, `cursos` o `constructor`), con `curso_id`, `paso` (1, 2 o 3) y `pagina`.
            - `angelitozzz_cursos_url_admin( $vista, $curso_id, $paso )`: URL de `escritorio`, `cursos`, `nuevo` o `editar`.
            - `angelitozzz_cursos_aviso_publicado( $curso_id )`: el texto del aviso "¡Curso publicado!", que cambia si el curso no está en ninguna solución.
            - `angelitozzz_cursos_pendientes_publicar( $curso_id )`: lo que falta para publicar (`clave`, `texto`, `bloquea`), para "Antes de publicar" del constructor.
            - `angelitozzz_cursos_admin_curso( $curso_id )` (F4, bloque 6b; `includes/constructor.php`): todo lo que muestra el constructor. Campos del paso 1 (`titulo`, `descripcion`, `edad`, `tipo`, `duracion`, `mes_mundo`, `precio`, `precio_lanzamiento` del producto de Woo), `portada_id` y `portada_url`, `estado`, `estudiantes` (con acceso), `modulos` con sus `items` en orden (lecciones: `id`, `titulo`, `video`, `duracion`, `duracion_texto`, `miniatura`, `contenido`, `extracto`, `vista_previa`, `materiales`; PDF del curso: `id`, `nombre`, `tamano`), `totales` (módulos, lecciones y duración) y `pendientes`. Con 0, los valores de un curso nuevo.
        - **Cuentas (F3):**
            - `angelitozzz_cursos_url_envio()` y `angelitozzz_cursos_campos_ocultos( $accion, $volver_a )`: a dónde se envía un formulario y sus campos ocultos (acción, nonce, `redirect_to`). Acciones: `angelitozzz_iniciar_sesion`, `angelitozzz_crear_cuenta`, `angelitozzz_recuperar`, `angelitozzz_nueva_contrasena`, `angelitozzz_perfil_datos`, `angelitozzz_perfil_contrasena`.
            - `angelitozzz_cursos_mensaje()`: el resultado del último envío (tipo `error` o `aviso`, texto y lo ya escrito, nunca contraseñas), una sola vez.
            - `angelitozzz_cursos_reglas_contrasena( $clave )`: el estado de las 4 reglas, para la lista `reglas-contrasena`.
            - `angelitozzz_cursos_url_pagina( $pagina, $vista )` y `angelitozzz_cursos_vista_cuenta()`: URL de las páginas de cuentas y la vista de `/cuenta/` (escritorio, perfil, cursos o pedidos).
            - `angelitozzz_cursos_url_login( $volver )`: URL de `/iniciar-sesion/` con su vuelta; si la vuelta es otra página de acceso no la anida (F4, bloque 6b). La usa el botón "Iniciar sesión" del header.
            - `angelitozzz_cursos_inicio_de( $usuario_id )`: la página de inicio de cada rol (alumna: `/cuenta/`; quien gestiona cursos, instructora o administradora: `/administrador/`). La usa "Mi cuenta" del header.
            - `angelitozzz_cursos_es_pagina_acceso( $url )`: si una URL es de iniciar sesión, crear cuenta o recuperar contraseña (no sirven como vuelta).
            - `angelitozzz_cursos_datos_perfil()`: nombre, apellidos, correo, teléfono y el correo pendiente de confirmar.
        - `angelitozzz_cursos_pedido_cursos( $pedido_id )`: los cursos de un pedido de la usuaria (no regalo), con duración y primera lección, para la página de gracias.
        - `angelitozzz_cursos_pedido_regalos( $pedido_id )`: los regalos de un pedido de la usuaria, con curso y código.
        - `angelitozzz_cursos_puede_ver_curso( $curso_id, $usuario_id )`: si la usuaria compró el curso (lo usa la lección para el botón de completar, los candados del temario y el PDF del curso).
        - `angelitozzz_cursos_ultimo_canje()`: el canje que la usuaria acaba de hacer (curso, duración, primera lección); se lee una sola vez.
    - **Los datos ya vienen saneados, pero no escapados.** El tema escapa siempre al imprimir.
- **Estado:** aprobado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] Ninguna plantilla del tema hace consultas a las tablas del plugin
- [x] Con el plugin desactivado, las páginas de la cuenta y de la lección muestran un aviso y no un error fatal
