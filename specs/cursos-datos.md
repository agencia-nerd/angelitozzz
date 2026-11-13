# Página: Cursos: modelo de datos

- **Resumen:** Qué guarda el plugin `angelitozzz-cursos` y con qué reglas: cursos, lecciones, inscripciones, avance, vencimiento, acceso, descargas, puente con WooCommerce, roles y las funciones que usa el tema. No es una página que se visite: es la base de la F2 y de los specs de la lección, la cuenta y el administrador.
- **Fuente:** `specs/plan/checklist-cursos.md`
- **Plantilla:** ninguna
- **Tipo:** detalle
- **Contenido:** curso
- **Secciones (en orden):** curso, leccion, inscripcion, avance, acceso, puente-woo, roles, funciones-publicas
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
- **Estado:** aprobado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] Solo quien tiene `angelitozzz_gestionar_cursos` puede crear o editar cursos, en el constructor o en el admin
- [ ] `/cursos/<curso>/` nunca muestra contenido: redirige según el acceso
- [ ] Los cursos no aparecen en la búsqueda del sitio ni en el mapa del sitio
- [ ] Cada metadato se guarda saneado (texto, entero de una lista permitida, IDs que existen)

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
        - `_angelitozzz_vista_previa`: sí/no, "Lección de vista previa gratuita".
        - `_angelitozzz_materiales`: IDs de PDF de la lección (pestaña "Materiales").
    - **Reproductor:** una sola función, `angelitozzz_cursos_video_html()` en `includes/video.php`, arma el iframe del proveedor. Hoy es Vimeo con `dnt=1`; cambiar de proveedor es cambiar esa función.
    - **Primera lección:** la de menor orden del primer módulo. Ahí va el banner de Mundo (`leccion.md`).
    - **Materiales (PDF):** se suben a una carpeta privada `uploads/angelitozzz-privado/`, con nombre aleatorio y regla que impide el acceso directo. Se entregan solo por la descarga con permiso (sección `acceso`). Tipo verificado por contenido (`wp_check_filetype_and_ext`, solo `application/pdf`) y tamaño máximo (propuesta: 20 MB). Se muestran con su nombre y su tamaño ("PDF · 2,4 MB"); el "13 páginas" del diseño no se calcula.
    - **Servidor:** la regla de la carpeta privada depende del servidor (`.htaccess` en Apache, regla `location` en Nginx). Se confirma con el hosting antes del deploy; la descarga nunca depende solo de esa regla, porque los nombres son aleatorios y no se listan.
- **Estado:** aprobado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] Un enlace de video que no es de Vimeo no se guarda
- [ ] El texto de la lección no deja pasar etiquetas fuera de la lista permitida (por ejemplo `<script>` o atributos `on*`)
- [ ] Un PDF no se puede abrir con la URL de `uploads/` (403) y un archivo que no es PDF no se sube
- [ ] Cambiar de proveedor de video solo toca `includes/video.php`

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
        - `creado` y `actualizado`.
    - **Índices:** `usuario_id`, `curso_id`, `vence`, y único (`pedido_id`, `item_id`). Ese índice único hace que la misma línea de un pedido nunca inscriba dos veces.
    - **Reglas:**
        - **Inicio:** al quedar pagado el pedido; en un regalo, el día del canje.
        - **Vence:** inicio + `_angelitozzz_duracion_acceso` meses, al final del día en la zona horaria del sitio; 0 = ilimitado (`vence` nulo).
        - **Volver a comprar un curso ya activo** (propuesta): se extiende desde el vencimiento actual; no hay dos inscripciones activas del mismo curso.
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

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] Toda consulta a la tabla usa `$wpdb->prepare`
- [ ] Un pedido que Woo marca pagado dos veces (`processing` y luego `completed`) crea una sola inscripción
- [ ] Un acceso de 4 meses comprado hoy vence en 4 meses, al final de ese día; uno ilimitado no vence
- [ ] Una inscripción vencida no da acceso aunque la tarea diaria no haya corrido
- [ ] Prueba PHPUnit de alta, extensión, vencimiento y cancelación

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
        - **Marcar como completa:** `POST angelitozzz/v1/lecciones/<id>/completar` con nonce `wp_rest`; solo la usuaria con sesión y con acceso. Se puede desmarcar con `DELETE` en la misma ruta.
        - **Porcentaje:** lecciones completadas / lecciones publicadas del curso, redondeado. Los PDF del curso no cuentan.
        - **Estados del curso para la alumna:** 0 % = "Empezar", 1 a 99 % = "Continuar", 100 % = "Completado".
        - **Pestañas de la cuenta:** Inscritos = todas sus inscripciones (activas y por expirar); Activos = de 0 a 99 %; Completados = 100 %. Las vencidas o canceladas no se listan en "Cursos inscritos" (se ven en el historial).
        - **Escritorio:** el resumen cuenta inscritos, completados y en progreso con las mismas reglas.
    - **Al despublicar o borrar una lección:** se recalcula el porcentaje. El avance de esa lección se borra con la lección.
- **Estado:** aprobado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] Una usuaria no puede completar una lección de un curso sin acceso ni la de otra usuaria (la ruta ignora cualquier `usuario_id` recibido)
- [ ] La ruta sin nonce o sin sesión responde 401/403
- [ ] El porcentaje, los conteos del escritorio y las pestañas salen de una sola función (no se calculan distinto en cada vista)

## Sección: acceso

- **Origen:** decisiones de `specs/plan/checklist-cursos.md` y reglas de seguridad de `CLAUDE.md`
- **Destino:** `wp-content/plugins/angelitozzz-cursos/includes/acceso.php`, `includes/descargas.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** una sola función decide si una usuaria puede ver una lección: `angelitozzz_cursos_puede_ver( $leccion_id, $usuario_id )`.
    - **Es sí cuando:** la lección está publicada y además es vista previa, o la usuaria tiene una inscripción `activa` del curso con `vence` nulo o futuro, o la usuaria tiene `angelitozzz_gestionar_cursos`.
    - **Al abrir una lección sin acceso** (`template_redirect`, antes de imprimir nada):
        - sin sesión → `/iniciar-sesion/?redirect_to=<lección>`;
        - con sesión → la solución del curso, con el aviso "Este curso no está en tu cuenta" (propuesta);
        - curso o lección en borrador → 404, salvo para quien gestiona cursos.
    - **Solución de un curso:** la `solucion` publicada que lo tiene en `solucion_compra_cursos` (o en una etapa). Si no hay, `/soluciones-de-sueno/`.
    - **Descargas de materiales:**
        - URL `/cursos/descargar/<id>/?_wpnonce=…`.
        - Comprueba sesión, nonce y que el PDF pertenezca a una lección o curso que la usuaria puede ver.
        - Entrega el archivo con `Content-Type: application/pdf`, `Content-Disposition: attachment`, `X-Content-Type-Options: nosniff` y sin caché pública.
        - Si algo falla, 403, sin revelar si el archivo existe.
    - **Nunca se confía en IDs recibidos:** la usuaria es siempre la de la sesión (`get_current_user_id()`).
- **Estado:** aprobado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] Sin sesión, una lección lleva a iniciar sesión y vuelve a la lección al entrar
- [ ] Con sesión y sin inscripción (o vencida o cancelada), una lección lleva a la solución del curso
- [ ] Cambiar el ID de una descarga o de una lección en la URL no da acceso a algo ajeno
- [ ] Una lección de vista previa se ve sin comprar
- [ ] Pruebas PHPUnit de `angelitozzz_cursos_puede_ver()` con cada caso

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
        - Si la línea es un regalo, no inscribe: genera el código (F5).
    - **Reembolso o cancelación:**
        - Con `woocommerce_order_status_refunded` y `woocommerce_order_status_cancelled`, todas las inscripciones del pedido pasan a `cancelada`.
        - Con un reembolso parcial por línea (`woocommerce_order_refunded`), solo las líneas reembolsadas.
    - **Pedido sin clienta** (no debería pasar: el checkout exige cuenta): no inscribe y lo registra como error.
    - **HPOS:** solo `wc_get_order()` y `WC_Order`. La compatibilidad está declarada en `includes/compatibilidad.php`.
    - **Angelita Pro (F6):** si se usa WooCommerce Subscriptions, cada renovación pagada extiende la inscripción y la cancelación de la suscripción la deja vencer.
- **Estado:** aprobado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] Comprar un curso deja una inscripción activa con su vencimiento; comprar 2 etapas deja 2
- [ ] Reembolsar el pedido deja las inscripciones en `cancelada` y quita el acceso
- [ ] El producto del curso no aparece en `/tienda/`, en la búsqueda ni en los feeds
- [ ] Funciona igual con HPOS activado
- [ ] Pruebas PHPUnit del alta por pedido pagado y la baja por reembolso

## Sección: roles

- **Origen:** decisión "por ahora solo Bárbara" (`specs/plan/checklist-cursos.md`) y `usuario-*.md`
- **Destino:** `wp-content/plugins/angelitozzz-cursos/includes/roles.php`, `includes/cuentas.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** quién hace qué, con capacidades propias.
    - **Alumnas:** rol `customer` de Woo.
    - **Rol `instructora`** (lo crea la migración):
        - capacidades `read`, `upload_files` y `angelitozzz_gestionar_cursos`, más las de `angelitozzz_curso` y `leccion`;
        - sin `edit_posts` ni acceso a otras partes del admin.
    - **Administradora:** recibe también `angelitozzz_gestionar_cursos`. La desinstalación borra el rol y la capacidad.
    - **`wp-admin`:**
        - cerrado para `customer` e `instructora`, que van a `/cuenta/` o al administrador de cursos;
        - quedan permitidos `admin-ajax.php`, `admin-post.php` y la REST API;
        - la barra de administración se oculta para ellas.
    - **Bárbara:** la administradora le asigna el rol `instructora` a su usuaria (no hay registro de instructoras).
- **Estado:** aprobado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] Una alumna que entra a `/wp-admin/` va a `/cuenta/`
- [ ] Una alumna no puede usar la API del constructor (403)
- [ ] Desactivar y desinstalar el plugin no deja el rol ni la capacidad

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
        - `angelitozzz_cursos_admin_resumen()`: estudiantes, cursos y ganancias del escritorio de Bárbara; las ganancias son la suma de los pedidos pagados de sus productos, menos los reembolsos.
        - `angelitozzz_cursos_admin_lista()`: los cursos con su número de estudiantes.
    - **Los datos ya vienen saneados, pero no escapados.** El tema escapa siempre al imprimir.
- **Estado:** aprobado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] Ninguna plantilla del tema hace consultas a las tablas del plugin
- [ ] Con el plugin desactivado, las páginas de la cuenta y de la lección muestran un aviso y no un error fatal
