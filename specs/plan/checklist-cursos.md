# Checklist: cursos, compra y cuentas

Avance del plan de cursos: LMS a medida en el plugin `angelitozzz-cursos`, con cobro por WooCommerce + Niubiz y contenido administrable con ACF. Una rama y un PR por fase. Se marca `- [x]` al terminar y verificar cada punto. Es documentación del tema: no se sube al servidor.

**Cambio de rumbo (2026-11-13):** se deja Tutor LMS (gratis y Pro). Los cursos, la cuenta de la alumna, la lección y el administrador de Bárbara se construyen a medida, iguales a Figma y a los specs. Lo hecho con Tutor en la F0 anterior era de prueba y se limpia en la nueva F0.

## Decisiones tomadas

| Tema | Decisión |
| --- | --- |
| Cursos y lecciones | A medida, en el plugin propio `angelitozzz-cursos` (tipos `curso` y `leccion`, tablas de inscripciones y de avance). Sin Tutor LMS |
| Alcance de la lección | Video, texto (con cita "PD"), materiales descargables, avance y banner de Mundo. Sin cuestionarios, certificados ni comentarios |
| Cobro | WooCommerce es el motor (carrito, checkout, cupones, pedidos, correos, reembolsos). Pasarela Niubiz con su plugin oficial para WooCommerce, en USD. El plugin propio solo hace el puente pedido → inscripción |
| Videos | Vimeo gratis, videos ocultos (no listados), sin restricción de dominio: riesgo aceptado por el usuario (quien tenga el enlace puede verlo). El código no se ata a Vimeo: una sola función arma el reproductor y se cambia de proveedor ahí |
| Landing de venta | Tipo de contenido `solucion` con secciones ACF flexibles, unida a 1 o más cursos |
| URL del listado | `/soluciones-de-sueno/` (y cada solución en `/soluciones-de-sueno/<slug>/`) |
| Angelita Pro | Suscripción cada 4 meses con 7 días de prueba: WooCommerce Subscriptions (licencia oficial) si Niubiz permite cobros con tarjeta guardada; si no, renovación manual con enlace de pago |
| Duración del acceso | 4, 6, 12 meses o ilimitado, por curso (lo elige Bárbara en el constructor). En el regalo, el acceso empieza el día del canje |
| Garantía de 7 días | Devolución: se paga al momento; si se reembolsa el pedido, se quita el acceso |
| Regalo | Código de regalo (cupón de Woo de 100 %, un solo uso, 6 meses) que se canjea en la web |
| Administrador de cursos | A medida, como Figma: escritorio, lista de cursos y constructor de 3 pasos (Básico, Contenido, Publicar). Bárbara es la dueña: cuenta propia de rol administradora, que edita el contenido en `/wp-admin/` y los cursos en `/administrador/` (decisión del usuario, 2026-11-15). El rol `instructora` queda para quien solo maneje cursos |
| Contenido relacionado | Automático: 3 soluciones distintas de la actual |
| Plugins de terceros | Solo oficiales y con actualizaciones. Nada de GPLVault para pagos, cuentas ni seguridad |

## Arquitectura

- **Plugin `angelitozzz-cursos`** (carpeta `wp-content/plugins/angelitozzz-cursos/`, con repo propio en GitHub, `agencia-nerd/angelitozzz-cursos`, desde el 2026-11-17): toda la lógica de negocio.
    - Tipos `curso` y `leccion` (con curso, módulo, orden, video, duración, texto y materiales).
    - Tablas `wp_angelitozzz_inscripciones` (alumna, curso, pedido, inicio, vencimiento, estado) y `wp_angelitozzz_progreso` (alumna, lección, completada, última vista), creadas con `dbDelta` y una versión de esquema guardada en una opción.
    - Acceso a lecciones y descargas protegidas.
    - Avance, vencimiento (tarea diaria), puente con Woo, cuentas, roles y capacidades.
    - API REST `angelitozzz/v1` del constructor.
    - Canje de regalos.
- **Tema `angelitozzz`:** solo presentación (plantillas de los specs, componentes, CSS y JS). Lee el plugin por sus funciones públicas `angelitozzz_cursos_*`; nunca hace consultas propias a las tablas del plugin.
- **Ajustes a WooCommerce** que son del tema (plantillas de carrito, checkout y gracias; bloque de Mundo en el correo): `woocommerce/` e `inc/woocommerce/`. Los campos de facturación del checkout son lógica: van en el plugin `angelitozzz-cursos` (regla de wp-woocommerce, 2026-11-14).

## Seguridad y buenas prácticas (todas las fases)

- **Entrada y salida:**
    - Se sanea todo lo que entra: `sanitize_*`, `absint` y listas permitidas.
    - Se escapa todo lo que sale: `esc_html`, `esc_attr`, `esc_url`, `wp_kses`.
    - El SQL va solo con `$wpdb->prepare`.
- **Formularios y peticiones:** cada formulario y cada petición AJAX o REST lleva nonce y comprobación de capacidad. Cada lección, descarga o inscripción comprueba que pertenece a la usuaria, para que nadie acceda a lo de otra cambiando un ID en la URL o en la petición.
- **Cuentas:**
    - Límite de intentos de inicio de sesión por IP y por correo.
    - Los mensajes no revelan si un correo está registrado.
    - Las 4 reglas de contraseña se validan en el servidor.
    - Cambiar el correo pide la contraseña actual.
    - `wp-admin` queda cerrado para las alumnas.
- **Subidas de archivos:** se comprueba el tipo real del archivo (`wp_check_filetype_and_ext`) y su tamaño máximo. Los PDF de materiales quedan fuera del acceso público y se entregan por una descarga con permiso.
- **Pagos:**
    - La tarjeta solo la maneja el plugin de Niubiz.
    - La inscripción se crea una sola vez aunque Woo avise dos veces.
    - Cada alta o baja de acceso queda en el registro de Woo (`wc_get_logger`).
- **Claves:** no se guardan en el repo. Las credenciales de Niubiz se cargan en sus ajustes, distintas para prueba y producción.
- **Calidad del código:**
    - En el plugin: `php -l`, PHPCS con WordPress Coding Standards y PHPUnit (con las pruebas de WordPress) para inscripción, vencimiento, acceso y puente con Woo.
    - `/security-review` y `/code-review` al cerrar cada fase.

## Reglas de mantenimiento

- Nunca editar archivos de plugins de terceros. Orden de preferencia: hooks y filtros → CSS → sobrescribir una plantilla.
- Cada plantilla sobrescrita de `woocommerce/` conserva todos sus `do_action()` y se anota en `specs/plan/sobrescrituras.md`.
- Con Woo, solo `WC_Order` y `WC_Product` (compatibles con HPOS).
- El plugin propio versiona su esquema: cada cambio de tablas es una migración, y la desinstalación (`uninstall.php`) borra lo suyo.
- Antes de actualizar Woo o Niubiz en producción: actualizar en el Local, revisar Woo → Estado → plantillas y `specs/plan/sobrescrituras.md`, y repetir las compras de prueba.

## F0 · Limpieza y base

- [x] Pasarela elegida por la clienta: **Niubiz**
- [x] Precio de A Dormir Por Fin confirmado: **$399**
- [x] `specs/_shared.md`: "Contenido dinámico" y tabla WooCommerce (carrito, checkout y mini-carrito clásicos)
- [x] Crear `specs/plan/sobrescrituras.md`
- [x] Desinstalar Tutor LMS en el Local (2026-11-13, con respaldo previo del usuario):
    - [x] borrar sus tablas `wp_tutor_*`, sus opciones, sus metadatos, el rol `tutor_instructor` y sus capacidades;
    - [x] borrar las páginas "Escritorio" y "Registro de Estudiante";
    - [x] borrar los 6 cursos `courses` de prueba;
    - [x] borrar las páginas duplicadas "Cart" y "Checkout".
- [x] Borrar los 6 productos de prueba (`_angelitozzz_prueba`); se rehacen ligados a los cursos propios.
- [x] Borrar `inc/tutor/estilos.php` y su `require_once` de `functions.php`.
- [x] Esqueleto del plugin `angelitozzz-cursos` en `wp-content/plugins/` (2026-11-13): cabecera, activación, migraciones versionadas, desinstalación, compatibilidad con HPOS, config de PHPCS y PHPUnit. Se activó en la F2.
- [x] Repo del plugin: en GitHub, `agencia-nerd/angelitozzz-cursos` (2026-11-17). Al principio no se creó (decisión del usuario, 2026-11-13); el 2026-11-14 se hizo un `git init` local, y su historial se perdió al importar el sitio a otra PC, porque All-in-One no lleva `.git`.
- [x] `composer install` en el plugin y suite de pruebas de WordPress con una base de datos de pruebas (2026-11-14): PHP 8.4 de Local, suite de WP 7.1.4 en `~/Local Sites/angelitozzz/wp-tests/` y base `local_tests`; `composer test` en verde (detalle en el README del plugin)
- [x] Ocultar `/tienda/` de Woo y redirigirla a `/soluciones-de-sueno/` (2026-11-17, `inc/tienda.php`).
- [x] Mensaje a la clienta enviado (respuesta pendiente): afiliación de Niubiz en USD, credenciales de prueba y consulta por cobros recurrentes.
- [ ] Credenciales de comercio de Niubiz: código de comercio, usuario y contraseña de **prueba (Sandbox)** y de producción.
- [ ] Respuesta de Niubiz: ¿la cuenta puede tener cobros automáticos con tarjeta guardada en USD ("Pago programado" / tokenización)? Define cómo se renueva Angelita Pro.
- [ ] Plugin **oficial** de Niubiz para WooCommerce (portal de desarrolladores: `desarrolladores.niubiz.com.pe/docs/plugins`) instalado y probado en Sandbox:
    - [ ] Cobra en USD
    - [ ] Pago de prueba aprobado y rechazado
    - [ ] Funciona con el checkout clásico
    - [ ] Compatible con HPOS
    - [ ] Verificar si exige nombre y apellido por separado o el país (afecta a `checkout.md`)
- [ ] Vimeo: confirmar con la clienta que su cuenta gratis permite videos ocultos y que el espacio le alcanza.

## F1 · Specs

- [x] Soluciones de sueño (`soluciones-de-sueno.md`) y las 4 landings con sus layouts, modales y drawer (`solucion.md`)
- [x] Carrito y checkout (`carrito.md`, `checkout.md`)
- [x] Iniciar sesión, crear cuenta y recuperar contraseña
- [x] Cuenta de la alumna: escritorio, mi perfil, cursos inscritos (con las vistas propuestas) e historial de pedidos (`usuario-*.md`)
- [x] Lecturas de Figma de la lección, del escritorio del administrador y de la lista de cursos con las capturas del constructor
- [x] Specs aprobados ajustados al LMS propio (sin Tutor): plantillas y `Destino`, reglas de acceso y de seguridad
- [x] `cursos-datos.md`: modelo de datos (curso, lección, inscripción, avance, vencimiento, roles, reglas de acceso, puente con Woo) → aprobado
- [x] `leccion.md`: temario, video, Resumen, Materiales, banner de Mundo (con la línea "Si ya lo activaste…"), mensaje de Angelita Pro, cita "PD", "Marcar como completo" → aprobado (con `opciones.md` → `mes-gratis-mundo`)
- [x] `admin-escritorio.md` y `admin-cursos.md` (lista, menú "Más opciones", aviso de publicado) → aprobados
- [x] `admin-constructor.md`: pasos Básico, Contenido y Publicar (el paso 3 deja de tener los textos de venta), y los modales de editar y eliminar → aprobado (2026-11-14)
- [x] Página de gracias (`gracias.md`, 2026-11-14): propuesta sin Figma (confirmación, estados de pago y bloque del mes gratis en Mundo) más la tarjeta "¡Tu regalo está listo!" de Figma → aprobado (2026-11-14)
- [x] Regalo (2026-11-14): `canjear-regalo.md` (página `/canjear-regalo/` con "¿Te regalaron un curso?" y "Regalo canjeado"), la compra exitosa con código en `gracias.md` y la sección `regalo` de `cursos-datos.md` (código, canje y reembolso) → aprobados (2026-11-14)
- [x] Comportamiento en móvil propuesto en cada spec (no hay frames móviles); el 2026-11-14 se completaron las 5 secciones del constructor que no lo tenían
- [x] Todos los specs aprobados en `specs/_index.md` (2026-11-14): la F1 queda cerrada

## F2 · Plugin: datos, acceso y puente con Woo

- [x] Tipos `curso` y `leccion`, con su relación, módulos y orden
- [x] Tablas de inscripciones y de avance con migraciones
- [x] Acceso: lección solo con inscripción vigente; curso sin comprar → su solución
- [x] Materiales: carpeta protegida y descarga con permiso
- [x] Avance: completar lección, última vista, porcentaje y conteos (inscritos, activos, completados)
- [x] Vencimiento: tarea diaria y estado "Por expirar" (15 días o menos)
- [x] Puente con Woo: pedido pagado → inscribe (sin duplicar); reembolsado o cancelado → quita el acceso; registro en `wc_get_logger`
- [x] Reproductor de video en una sola función (Vimeo hoy)
- [x] Pruebas PHPUnit de inscripción, vencimiento, acceso y puente (2026-11-14: plugin 0.2.0)
- [x] Revisión de seguridad y correctitud de la fase (2026-11-14): como en ese momento el plugin no tenía repo, `/security-review` y `/code-review` no aplicaban (revisan un diff de git); se hizo una revisión independiente del plugin completo. Desde el 2026-11-17 el plugin está en GitHub y ya se pueden usar. Sus 10 hallazgos se corrigieron con su prueba (57 pruebas en verde)

## F3 · Cuentas

- [x] Iniciar sesión con límite de intentos y vuelta a la página de origen
- [x] Crear cuenta: usuario armado del correo y las 4 reglas de contraseña
- [x] Recuperar contraseña en 3 pasos, sin revelar si el correo existe
- [x] Mi perfil: datos personales (cambiar el correo pide la contraseña) y seguridad
- [x] Rol `instructora` y capacidades; `wp-admin` cerrado para alumnas; `/mi-cuenta/` → escritorio (2026-11-14: la instructora conservó `wp-admin` hasta que existió el constructor; desde la F4, bloque 6b, va a `/administrador/`)
- [x] Cuenta creada al pagar en el checkout (Woo) con las mismas reglas (plugin 0.3.0, 78 pruebas en verde; las pantallas son de la F4)
- [ ] Auditoría de seguridad al cerrar la F3 (decisión del usuario, 2026-11-14): plugin completo (F2 + F3) y plantillas de cuentas, con la skill `cloudflare/security-audit-skill` (https://github.com/cloudflare/security-audit-skill; leer su código antes de instalarla). Si no sirve para PHP/WordPress, otra equivalente. Antes de correrla, preguntar al usuario el perfil y el tope de agentes (usa muchos subagentes). Cada hallazgo se corrige con su prueba

## F4 · Maquetación

Una rama por bloque (decisión del usuario, 2026-11-14): 1) soluciones, 2) carrito, checkout y gracias, 3) regalo, 4) acceso, 5) cuenta y lección, 6) administrador. Solo maquetación: los datos de muestra viven en `inc/solucion-muestra.php` y `inc/solucion-muestra/` hasta la F5. El tipo `solucion` lo registraba temporalmente el mu-plugin de vista previa; desde la F5-a lo registra ACF (`acf-json/post_type_*.json`).

- [x] Listado de Soluciones de sueño maquetado (era provisional en `woocommerce/archive-product.php`)
- [x] Mover el listado a `archive-solucion.php` reutilizando `shop.php` y `producto-card` (bloque 1, 2026-11-14)
- [x] `single-solucion.php` + un archivo por layout en `template-parts/pages/solucion/` (bloque 1)
- [x] Modales con wp-modal (drawer del carrito, etapas, selector de edad) y preguntas frecuentes con wp-accordion (bloque 1)
- [x] Carrito, checkout y gracias en `woocommerce/` (bloque 2, 2026-11-14; vista previa con `?vista_previa=carrito|carrito-vacio|checkout|gracias&estado=…`)
- [x] Regalo: pantallas de compra, canje y canjeado (bloque 3, 2026-11-14: compra en los bloques 1 y 2; canje y canjeado en `/canjear-regalo/`, vista previa `?vista_previa=canjear-regalo&estado=…`)
- [x] Iniciar sesión, crear cuenta y recuperar contraseña (bloque 4, 2026-11-14): conectadas de verdad al plugin (sin muestra) y probadas de punta a punta en el Local. Las 3 páginas existen en el Local (`/cuenta/`, a donde lleva entrar sin origen, llegó en el bloque 5)
- [x] Cuenta de la alumna (4 vistas) con `cuenta-menu`, `cuenta-pestanas` y `curso-progreso-card` (bloque 5, 2026-11-14): conectada al plugin y a WooCommerce, probada con datos de prueba (borrados al terminar). La página `/cuenta/` existe en el Local
- [x] Lección (bloque 5, 2026-11-14): conectada al plugin (acceso, temario, avance por REST, video, materiales, banner de Mundo y mensaje de Angelita Pro)
- [x] Administrador de Bárbara, bloque 6a (2026-11-14): escritorio (resumen y tabla) y lista de cursos (tarjetas, menú "Más opciones", publicar, pasar a borrador, eliminar y aviso de publicado), conectados al plugin 0.4.0 (`includes/admin.php`, `includes/rest-admin.php`, 90 pruebas en verde; un curso en borrador conserva el acceso de sus inscritas) y probados en el Local con una instructora de prueba. La página `/administrador/` existe en el Local (creada a mano); el token `--color-terracota` entra en `_shared.md`
- [x] Administrador de Bárbara, bloque 6b (2026-11-14): constructor de 3 pasos (Básico, Contenido y Publicar) conectado al plugin 0.5.0 (`includes/constructor.php`, `includes/rest-constructor.php`, 101 pruebas en verde), con Tiptap 3.34.0 para la descripción de la lección. Probado en el Local de punta a punta: crear un curso con portada, módulo, lecciones con video de Vimeo, texto y PDF, ordenar (arrastre y teclado), eliminar y publicar (producto de Woo publicado y oculto del catálogo). Adelanta tareas de la F5 (abajo, marcadas)
- [x] Imágenes pesadas de Figma a WebP (img-webp) antes de las plantillas: hechas en el bloque 1; los bloques 2 a 6 solo usan SVG (no queda ningún PNG ni JPG en `assets/`)

## F5 · Administrable y conexión

**Entorno de pruebas en cada PC:** hay dos PC, la de casa (MySQL de Local en el `10090`) y la del trabajo (en el `10222`). El 2026-11-17 el sitio se importó a la del trabajo y el entorno se rearmó a mano. All-in-One no lleva `~/bin/`, `wp-tests/`, `conf/nginx/` ni `.git`, y el `phpunit.xml` que sí lleva queda con las rutas de la otra PC.

Desde el 2026-11-17 el entorno ya no se arma a mano:
- El plugin calcula sus rutas y lee el puerto del `sites.json` de Local (`tests/bootstrap.php` y `tests/wp-tests-config.php`), sin `phpunit.xml`.
- El resto lo revisa o lo arma `node specs/pruebas/entorno/instalar.mjs [--instalar]`.
- `composer test`: 106 pruebas en verde.

En otra PC, seguir `specs/pruebas/LEEME.md` → "Entorno en otra PC" antes de tocar el plugin.

**Orden de la F5 (decisión del usuario, 2026-11-17, tras la auditoría completa del proyecto):** F5-a (ACF y wp-query) → F5-b (WooCommerce) → auditoría de seguridad de la F3 y deuda de la F2 → F5-c (plugin: regalo, Mundo, lección) → cierre. Una rama por subfase y el plan aprobado antes de cada una.

### F5-a · Soluciones administrables (hecha)

- [x] wp-acf: `solucion` (tarjeta, compra, flexible de secciones), "Archivo de solucion" y mes gratis en Mundo; contenido inicial de las 4 soluciones y los 5 menús (F5-a, 2026-11-17). El tipo lo registra ACF con sus URL (`URL:` en los specs, wp-acf 1.13.0). Los botones de WhatsApp de las landings usan el número de Ajustes del sitio. Los textos del regalo quedan para la F5-c, con el regalo
- [x] wp-query: listado de soluciones (12 por página, "Ver más" sin recargar) y relacionados automáticos (F5-a, 2026-11-17)
- [x] Páginas `/iniciar-sesion/`, `/crear-cuenta/`, `/recuperar-contrasena/`, `/cuenta/`, `/administrador/` y `/canjear-regalo/` en `inc/acf/paginas.php` con su plantilla (2026-11-17, wp-acf 1.13.0: `Página WP` en los specs de cuenta y administrador, `Origen: plugin` para `curso` y `leccion`). En el Local se conservaron las que ya existían y se creó `/canjear-regalo/`
- [x] Constructor: API REST con permisos, nonce y validación; al guardar el paso 1 crea o actualiza el producto de Woo del curso y al publicar lo publica oculto del catálogo (adelantado en la F4, bloque 6b)
- [x] Cerrar `wp-admin` para la instructora cuando el constructor funcione (va al administrador de cursos; `cursos-datos.md` → `roles`; adelantado en la F4, bloque 6b)

### F5-b · WooCommerce

- [ ] wp-woocommerce: soporte del tema, sobrescrituras con `@version`, CSS clásico de Woo quitado, mini-carrito que se actualiza solo
- [ ] `page.php` (o `singular.php`): hoy no existe, así que Carrito, Finalizar compra, Privacidad, Términos y cualquier página sin plantilla caen en `index.php`, que solo imprime el extracto (auditoría 2026-11-17)
- [ ] Plantillas de `woocommerce/`: cambiar los `// woo:` por los `do_action()` y filtros reales (hoy todos están comentados), los nonces del carrito, del pago y del login (`cart.php:76`, `payment.php:46`, `form-login.php:46`), el bucle de pasarelas (`payment.php:34-35`, sin él Niubiz no aparece) y los campos con `woocommerce_form_field()` (`form-billing.php:54`, `:106`)
- [ ] `@version` de cada sobrescritura según el original instalado: WooCommerce está en **11.3.0** y `sobrescrituras.md` dice 11.2.2 (`cart/cart.php` ya subió a 11.3.0)
- [ ] Probar `#order_review` y `#place_order`, que quedan fuera del `<form name="checkout">` (`form-checkout.php:71`, `:81`, con `form=`): el JS de Woo que actualiza el resumen podría no funcionar igual
- [ ] Contador del carrito del header: hoy es fijo (`header.php:37`, `$carrito_cantidad = 1`)
- [ ] Borrar `wp-content/mu-plugins/angelitozzz-preview-woo.php` (F5-b). Ya no registra el tipo `solucion` (F5-a); le quedan el soporte de Woo, el CSS de Woo quitado, el modo "próximamente" y la vista previa `?vista_previa=`
- [ ] Borrar la muestra `inc/solucion-muestra.php` e `inc/solucion-muestra/carrito.php` (y su `require_once`). Desde la F5-a solo quedan los precios de muestra (landing, etapas y relacionados) y el drawer del carrito: pasan a los precios de Woo y al carrito real en la F5-b
- [ ] Borrar la muestra `inc/compra-muestra.php` (y su `require_once`) y la ruta `?vista_previa=` del mu-plugin: carrito, checkout y gracias leen `WC()->cart` y `WC_Order`
- [ ] Al borrar la muestra, conectar antes las 3 vistas que la llaman sin `function_exists` (darían error fatal): `woocommerce/cart/cart.php:17`, `woocommerce/checkout/review-order.php:14` y `pages/template-canjear-regalo.php:16`. Además, `inc/compra-muestra.php` hoy se carga también en producción
- [ ] Páginas Carrito y Finalizar compra con el shortcode clásico (hoy tienen los bloques de Woo) y productos de prueba ligados a los cursos
- [ ] Campos de facturación del checkout (`checkout.md`, con `billing_phone_prefijo`) en el plugin `angelitozzz-cursos`, y el login del checkout con el límite de intentos de la F3
- [ ] Precio de la landing leído del producto de Woo (precio de lanzamiento = precio rebajado)
- [ ] "Agregar al carrito" sin recargar y abre el drawer; selector de etapas; descuento por cantidad leído de ACF; cupón en el hero
- [ ] `specs/plan/sobrescrituras.md` al día

### Antes de la F5-c · Auditoría de la F3 y deuda de la F2

La auditoría pendiente de la F3 (arriba) va aquí, antes de la F5-c: el canje y el login del checkout reutilizan el límite de intentos y las cuentas. Hallazgos ya encontrados en la auditoría del 2026-11-17, cada uno con su prueba:

- [ ] Seguridad: mensajes que revelan si un correo está registrado (contra la regla de "Cuentas"): crear cuenta, "Ya existe una cuenta con ese correo" (`cuentas-formularios.php:181`, y la prueba lo da por bueno, `test-cuentas.php:186`); el error de Woo en el checkout; `wp-login.php` y el login del checkout sin filtro `login_errors`; "Ese correo ya está en uso" en el perfil
- [ ] Seguridad: el límite de intentos usa transitorios no atómicos (`cuentas.php:122-127`) y `wp_login_failed` suma también estando bloqueado, así que la ventana se alarga (`cuentas-formularios.php:37`, `:88-94`)
- [ ] Seguridad: los enlaces del texto de la lección admiten `target` sin forzar `rel` (`tipos.php:158-163`); el tema imprime la lección con `the_content` sin la lista permitida de `wp_kses` (`template-parts/pages/leccion/pestanas.php`)
- [ ] Plugin: producto del curso con "Vender individualmente" y virtual, como pide `cursos-datos.md` → `puente-woo`. `set_sold_individually` no se llama en ningún lado (revisión del 2026-11-18) y `set_virtual` solo se llama al crear el producto (`constructor.php:187`): los dos tienen que aplicarse también al actualizar
- [ ] Plugin: exportador y borrador de datos personales de WordPress (`wp_privacy_personal_data_*`; nota de página de `cursos-datos.md`)
- [ ] Plugin: una inscripción `cancelada` que se reactiva no recalcula `inicio` ni `vence` ni reencadena (`inscripciones.php:53-56`)
- [ ] Plugin: no se comprueba el resultado de `GET_LOCK` (`inscripciones.php:63`); el registro del cambio de estado no lleva la usuaria ni el curso (`inscripciones.php:191-197`)
- [ ] Plugin: `angelitozzz_cursos_puede_ver()` exige `$usuario_id` aunque el spec lo da como opcional (`acceso.php:43`); `angelitozzz_cursos_pendientes_publicar()` no exige la capacidad (`admin.php:425`); `_angelitozzz_producto_id` no comprueba que el producto exista (`tipos.php:204`)
- [ ] Plugin: `uninstall.php` no borra la meta `_angelitozzz_correo_pendiente`, los transitorios `angelitozzz_lim_*` y `angelitozzz_msg_*` ni la meta `_angelitozzz_curso_id` de los productos. Con `ANGELITOZZZ_CURSOS_BORRAR_TODO` tampoco borra los productos de Woo ni las portadas que crea el constructor (`constructor.php:183-199`, `:241-253`); su cabecera dice "borra todo lo que el plugin creó" (revisión del 2026-11-18)
- [ ] Plugin: el PDF descargado sale con el nombre "Privado-…". `descargas.php:229` arma el nombre con `get_the_title()`, que antepone "Privado:" a los adjuntos privados. Hay que leer el título crudo, como ya hace `angelitozzz_cursos_datos_material()` (`:257`). Con su prueba (revisión del 2026-11-18)
- [ ] Plugin: alta manual de una inscripción (existe el origen `manual`, sin pantalla ni ruta). Decidir si entra o se saca del spec
- [ ] Tema y plugin: el tema lee el plugin solo por sus funciones públicas (regla de `CLAUDE.md`). Hoy `single-leccion.php:27-28` lee `_angelitozzz_tipo` y `_angelitozzz_mes_mundo` con `get_post_meta` (falta una función pública, que también sirve para el bloque de Mundo del correo), y el tema llama a funciones que no están en la lista pública del spec: `angelitozzz_cursos_puede_gestionar()`, `angelitozzz_cursos_vimeo_partes()` y `angelitozzz_cursos_redirect_seguro()`
- [ ] Tema y plugin: más diferencias entre la lista pública de `cursos-datos.md` → `funciones-publicas` y el código (revisión del 2026-11-18). Se corrige el spec o el código, uno por uno:
    - `angelitozzz_cursos_temario()` recibe un segundo argumento, `$actual_id`, que el spec no nombra.
    - `angelitozzz_cursos_datos_tarjeta()` está en `api.php` pero no en el spec.
    - `puede_ver_curso()` e `inicio_de()` piden `$usuario_id`, aunque el spec dice que todas trabajan con la usuaria de la sesión.
    - `angelitozzz_cursos_aviso_publicado()` no exige la capacidad, aunque el spec dice que las del administrador sí la exigen.
- [ ] Tema: `template-parts/pages/cuenta/pedidos.php:59-64` elige el color de cada estado comparando con el texto traducido que devuelve el plugin ("Vencido", "Por expirar"…). Si cambia la traducción, se rompe. El plugin tiene que devolver también una clave del estado (revisión del 2026-11-18)
- [ ] Tema: las 4 reglas de contraseña están repetidas en el tema (`template-parts/components/reglas-contrasena.php:23-28` y `src/js/modules/reglas-contrasena.js`) en vez de leerse de `angelitozzz_cursos_reglas_contrasena()`. Solo ayudan al escribir (el servidor valida), pero si cambian en el plugin quedan distintas (revisión del 2026-11-18)

### F5-c · Plugin: regalo, Mundo y lección

- [ ] Regalo: comprar como regalo genera el cupón; canje con sesión o creando la cuenta; el acceso empieza ese día
- [ ] Acción de canje en el plugin (`canjear-regalo.md`): nonce, límite de intentos, código de un solo uso marcado en la misma operación que inscribe, dato de un solo uso para la confirmación y flujo sin sesión por crear cuenta (la página `/canjear-regalo/` ya está en `inc/acf/paginas.php`, F5-a)
- [ ] Funciones públicas del regalo que pide `cursos-datos.md` y no existen: `angelitozzz_cursos_pedido_regalos()` y `angelitozzz_cursos_ultimo_canje()`; textos del regalo en Ajustes del sitio
- [ ] Bloque de Mundo en gracias y en el correo de pedido, excepto en regalos
- [ ] Banner de Mundo solo en la primera lección de cada curso (no en Angelita Pro); mensaje inicial de Angelita Pro
- [ ] Plugin: acción `admin-post` para "Marcar como completo" sin JS (`leccion.md`). El nombre de los materiales se arregló en la F4, bloque 6b, para la lista de la lección: se guarda el nombre legible del archivo y se lee sin el "Privado:" (los PDF subidos antes conservan el título saneado). El archivo descargado todavía sale con "Privado" (ver "Antes de la F5-c")
- [ ] Constructor: cambiar los diálogos nativos del navegador por modales de wp-modal con el estilo del modal `eliminar` (pedido del usuario, 2026-11-14: no siguen el diseño y son pobres en accesibilidad). Son el `window.prompt` que pide la URL del enlace en el editor de la lección (`src/js/modules/editor-texto.js`) el `window.alert` de enlace no válido (`editor-texto.js:97`, sumado en la auditoría del 2026-11-17) y el `window.confirm` de "descartar cambios" al cerrar el modal de la lección (`src/js/modules/constructor-contenido.js`). El enlace necesita un campo URL con su validación (solo http, https o mailto) y "Quitar enlace"; el descarte, "Seguir editando" y "Descartar cambios". Spec: `admin-constructor.md` → `modal-leccion`
- [ ] Hallazgos de la verificación de `cursos-datos.md` (2026-11-17):
    - [ ] El producto del curso (oculto del catálogo) sale en el mapa del sitio (`wp-sitemap-posts-product-1.xml`, también `product_cat`) y en la búsqueda de la Store API de Woo (`/wp-json/wc/store/v1/products?search=`). Su ficha ya redirige a `/soluciones-de-sueno/`; falta sacarlo de los sitemaps
    - [ ] Aviso "Este curso no está en tu cuenta" (`cursos-datos.md` → `acceso`): el plugin manda a la landing de la solución del curso con `?aviso=sin-acceso` (`acceso.php:230`; a `/soluciones-de-sueno/` solo si el curso no tiene solución), pero el tema no lo muestra
    - [x] Criterio de `leccion` sobre el proveedor de video: se reescribe (la lógica está en `includes/video.php`; los textos pueden nombrar a Vimeo). Pendiente aparte: `template-parts/components/video-portada.php` arma por su cuenta la URL del reproductor de Vimeo; en la F5 debería pedírsela al plugin
    - [x] Rol al desactivar (decisión del usuario): la desactivación quita el rol `instructora` y la capacidad, y la activación los vuelve a crear (plugin 0.5.1, `angelitozzz-cursos.php`, con su prueba; 106 pruebas en verde)

### Cierre de la F5

- [ ] Decidir (pendiente del usuario, 2026-11-15): aceptar el nombre de usuario en `/iniciar-sesion/`. El servidor ya lo acepta (`wp_authenticate()`); solo hay que cambiar el campo de tipo `email` a texto, la etiqueta "Correo electrónico" por "Correo o nombre de usuario" (se aparta de Figma) y el error a "Correo, usuario o contraseña incorrectos". Sirve sobre todo al equipo: el usuario de las alumnas se genera del correo. Spec: `iniciar-sesion.md`
- [ ] `check.mjs` de design-import en 0. Hoy tiene 11 errores:
    - 9 son los `Destino` de `cursos-datos.md`, que apuntan a archivos del plugin y el revisor los busca dentro del tema;
    - 1 es una nota de `home.md` que falta pasar a "Indicaciones del diseño";
    - 1 es `cursos-datos.md` → `roles`, con un criterio sin marcar: la desinstalación no tiene prueba (2026-11-18). Se cierra con la prueba de la desinstalación (más abajo, en esta misma sección).

  Los 5 componentes sin documentar ya están en `_shared.md` (2026-11-18). Los avisos de "Argumentos" que quedan vienen de valores entre comillas invertidas que el revisor toma como argumentos. El aviso de wp-acf sobre "Escríbenos" en Contacto no aplica: el título ya sale del campo, y lo único fijo es el texto del botón "Escríbenos por WhatsApp", que el spec deja fijo.
- [ ] Pruebas en Chrome (`specs/pruebas/`): no cubren soluciones, carrito, checkout, gracias, canje, formularios, modales ni filtros. `cdp-vacios.mjs:36` usa una usuaria que ningún script crea; falta el script de limpieza que nombra `cdp-bloque4.mjs:2`; `cdp-vacios.mjs` y `cdp-bloque6.mjs` usan el mismo puerto (9339)
- [ ] Plugin: prueba con HPOS activado y prueba del registro `wc_get_logger` (hoy ninguna); prueba de la desinstalación (`cursos-datos.md` → `roles`)
- [ ] Plugin: al desactivarlo, `flush_rewrite_rules()` (`angelitozzz-cursos.php:72`) corre en la misma petición en que el plugin ya registró sus reglas, así que `/cursos/…`, `/cuenta/…` y `/administrador/…` quedan guardadas. Hay que quitarlas antes de vaciar (revisión del 2026-11-18)
- [ ] Tema: el enlace de WhatsApp se arma igual en 4 lugares. Se pasa todo a un solo helper: ya existe `angelitozzz_solucion_whatsapp()` (`inc/soluciones.php:25`). Los lugares son:
    - `pages/template-preguntas-frecuentes.php:21-26`
    - `template-parts/pages/contacto/seccion-de-cta.php:20-25`
    - `template-parts/pages/mundo-angelitozzz/hero.php:22-26`, que además lee `opciones_contacto_whatsapp` como campo suelto, distinto del resto del tema (revisión del 2026-11-18)
- [ ] Tema: detalles de la revisión del 2026-11-18:
    - **Textos sin `__()`:** `footer.php:118` "Made with", `header.php:36` "Carrito", `template-parts/pages/solucion/hero.php:41` "Precio de lanzamiento (antes…" y `solucion/galeria.php:32` " USD".
    - **Modal coach:** el título es un textarea, pero `template-parts/modals/modal-coach.php:17,29` lo pasa como `title` del modal sin `angelitozzz_highlight`, así que pierde los saltos de línea y los colores.
    - **Enlace de WhatsApp del modal coach:** `src/js/modules/modal-coach.js:39-40` escribe `target` y `rel` a mano; el aviso `sr-only` sí está.
    - **Motor de wp-query:** `inc/query/motor.php:1072` usa `_n()` sin text domain. Es un archivo del motor: se avisa en la skill wp-query, no se edita.
- [ ] `/security-review` y `/code-review` de la fase

## F6 · Pagos y Angelita Pro

- [ ] Niubiz en modo prueba con las compras de la verificación
- [ ] Angelita Pro: WooCommerce Subscriptions (licencia oficial) cada 4 meses con 7 días de prueba, o renovación manual según la respuesta de Niubiz
- [ ] Niubiz en producción

## F7 · Cierre

- [ ] QA en escritorio, tablet y móvil
- [ ] Revisión de seguridad final (`/security-review` de todo el plugin y las plantillas)
- [ ] Manual de la clienta con wp-manual (incluye el administrador de cursos)
- [ ] Cuenta de Bárbara en producción: usuaria propia con su correo y rol administradora (no la de la agencia). Con ella entra a `/wp-admin/` (contenido) y a `/administrador/` (cursos)
- [ ] Logo del sitio en Apariencia → Personalizar → Identidad del sitio (decisión del usuario, 2026-11-17: no se carga desde `inc/acf/sitio.php`)
- [ ] Ajustes del sitio: enlace del mes gratis en Mundo (nas.io), número de WhatsApp y URLs de las redes de la clienta; cursos de cada solución en `solucion_compra_cursos` (en las etapas, también el de cada etapa)
- [ ] Decidir dónde se guardan los PDF privados de materiales (pendiente desde el 2026-11-17). Hoy van en `wp-content/uploads/angelitozzz-privado/` y dependen de una regla `deny` de Nginx, o de su equivalente en Apache, en Local y en producción. Si la regla falta, los PDF quedan públicos.
    - **Opción preferida:** una carpeta fuera de la raíz pública, que no necesita regla en el servidor.
    - **Toca el plugin:** `includes/descargas.php` (dónde se guardan y cómo se entregan), `uninstall.php`, una migración que mueva los PDF que ya existan y sus pruebas.
    - **Antes de decidir,** confirmar que el hosting de producción deja escribir fuera de `public_html`.
    - **Si no deja,** se mantiene la regla y se comprueba el 403 en producción.
- [ ] Deploy:
    - [ ] Enlaces permanentes en "Nombre de la entrada" (Ajustes → Enlaces permanentes). Las URL del plugin (`/administrador/…`, `/cuenta/perfil/`, `/cursos/<curso>/<leccion>/`, `/cursos/descargar/<id>/`) usan reglas de reescritura y no funcionan con los enlaces simples. El JS del administrador ya no depende de esto: arma las URL de la API sobre la base de `rest_url()` (F4, bloque 6b).
    - [ ] Tema: `pnpm build` y subir el tema con `dist/`, sin `hot`, `node_modules/`, `src/`, `specs/` ni configs.
    - [ ] Plugin `angelitozzz-cursos`: subirlo sin tests ni configs de desarrollo.

## Verificación (repetir antes de cada deploy)

Las herramientas para probar en el Local (datos de prueba, pruebas en Chrome y cómo armar el entorno en otra PC) están en `specs/pruebas/LEEME.md`.

- [ ] `php -l`, PHPCS y PHPUnit del plugin, `pnpm build` y los `check.mjs` de design-import y wp-acf en 0
- [ ] Compra de 1 curso → inscripción con su vencimiento
- [ ] Horarios Ideales con 2 y con 3 etapas, con el descuento correcto
- [ ] Compra con cupón
- [ ] Regalo: genera el código, el canje inscribe y el acceso empieza ese día
- [ ] Angelita Pro: prueba de 7 días y renovación
- [ ] Reembolso: el pedido reembolsado quita el acceso
- [ ] Vencimiento: un acceso vencido no abre la lección y figura "Vencido" en el historial
- [ ] Sin acceso: la lección y el PDF de materiales no se abren (ni cambiando el ID en la URL)
- [ ] Inicio de sesión: se bloquea tras varios intentos y no revela si el correo existe
- [ ] Constructor: una alumna no puede usar la API ni ver el administrador
- [ ] Avance y materiales en la lección; banner de Mundo solo en la primera lección
- [ ] Correo de pedido con el bloque de Mundo y sin él (regalo)
- [ ] Prueba de actualización: Woo y Niubiz actualizados en el Local, plantillas al día en Woo → Estado y en `specs/plan/sobrescrituras.md`
