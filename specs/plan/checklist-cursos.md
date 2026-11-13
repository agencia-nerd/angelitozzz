# Checklist: cursos, compra y cuentas

Avance del plan de cursos (Tutor LMS + WooCommerce + ACF). Una rama y un PR por fase. Se marca `- [x]` al terminar y verificar cada punto. Es documentación del tema: no se sube al servidor.

## Decisiones tomadas

| Tema | Decisión |
| --- | --- |
| Cursos y lecciones | Tutor LMS (gratis ahora, Pro más adelante) |
| Cobro | WooCommerce como motor de Tutor; pasarela Niubiz con su plugin oficial para WooCommerce, en USD; la renovación de Angelita Pro depende de que Niubiz habilite cobros recurrentes |
| Landing de venta | Tipo de contenido `solucion` con secciones ACF flexibles, unida a 1 o más cursos de Tutor |
| URL del listado | `/soluciones-de-sueno/` (y cada solución en `/soluciones-de-sueno/<slug>/`) |
| Angelita Pro | Suscripción cada 4 meses con 7 días de prueba (WooCommerce Subscriptions + Tutor Pro) |
| Garantía de 7 días | Devolución: se paga al momento; si se reembolsa el pedido, se quita el acceso |
| Regalo | Código de regalo (cupón de Woo de 100 %, un solo uso, 6 meses) que se canjea en la web |
| Administrador de cursos | Escritorio y lista de cursos propios; crear y editar con el constructor oficial de Tutor, con la marca |
| Contenido relacionado | Automático: 3 soluciones distintas de la actual |
| Textos de plugins | Filtro `gettext` en `inc/textos-plugins.php` (sin Loco Translate) |

## Reglas de mantenimiento

- Nunca editar archivos de plugins. Orden de preferencia: hooks y filtros → CSS → sobrescribir una plantilla.
- Cada plantilla sobrescrita (`woocommerce/`, `tutor/`) conserva todos sus `do_action()` y se anota en `specs/plan/sobrescrituras.md`.
- La lógica propia va en `inc/tutor/`, `inc/woocommerce/` e `inc/regalos/`, con el prefijo `angelitozzz_`. Con Woo, solo `WC_Order` y `WC_Product` (compatibles con HPOS).
- Antes de actualizar Woo o Tutor en producción: actualizar en el Local, revisar Woo → Estado → plantillas y `specs/plan/sobrescrituras.md`, y repetir las compras de prueba.

## F0 · Preparación

- [x] Instalar Tutor LMS (gratis) en el Local (4.2.1)
- [x] Tutor → Ajustes → Monetización: WooCommerce
- [x] URLs de Tutor en español: `/cursos/<curso>/` y `/lecciones/<lección>/` (las de `/courses/` redirigen)
- [ ] Ocultar `/tienda/` de Woo y redirigirla a `/soluciones-de-sueno/` → se hace en la F4, cuando exista `archive-solucion.php` (hoy `/tienda/` sirve de vista previa del listado)
- [x] Pasarela elegida por la clienta: **Niubiz**
- [ ] Mensaje a la clienta enviado: afiliación de Niubiz en USD, credenciales de prueba y consulta por cobros recurrentes
- [ ] Credenciales de comercio de Niubiz: código de comercio, usuario y contraseña de **prueba (Sandbox)** y de producción
- [ ] Respuesta de Niubiz: ¿la cuenta puede tener cobros automáticos con tarjeta guardada en USD ("Pago programado" / tokenización)? → define cómo se renueva Angelita Pro
- [ ] Plugin **oficial** de Niubiz para WooCommerce (descarga del portal de desarrolladores: `desarrolladores.niubiz.com.pe/docs/plugins`, con su manual de integración) instalado y probado en Sandbox con nuestra versión de WooCommerce:
    - [ ] Cobra en USD
    - [ ] Pago de prueba aprobado y rechazado en Sandbox
    - [ ] Funciona con el checkout clásico
    - [ ] Compatible con HPOS (sin aviso de incompatibilidad en WooCommerce → Ajustes → Avanzado → Funciones)
- [ ] Renovación de Angelita Pro (según la respuesta de Niubiz): plugin oficial si soporta WooCommerce Subscriptions; si no, un plugin de terceros de recurrencia para Niubiz probado en Sandbox, o renovación manual (se le envía el enlace de pago cada 4 meses)
- [x] Cursos de prueba en Tutor, cada uno con su producto de Woo (Simple + Virtual + For Tutor, oculto del catálogo) y la marca `_angelitozzz_prueba`: Angelita Pro, A Dormir Por Fin, Recién Nacidos Go y los 3 de Horarios Ideales
- [x] Precio de A Dormir Por Fin confirmado: **$399** (vale el del listado; el "$67 antes $97" de la landing es del diseño y se corrige en su spec en la F1). Producto de prueba actualizado
- [x] `specs/_shared.md`: "Contenido dinámico" con `solucion` (cpt), `curso` (Tutor) y `producto` (Woo, solo cobro)
- [x] `specs/_shared.md`: tabla WooCommerce (carrito, checkout y mini-carrito clásicos; cuenta en Tutor)
- [x] Crear `specs/plan/sobrescrituras.md`
- [x] Comprobado en el código de Tutor: al reembolsar o cancelar un pedido, Tutor quita la inscripción solo (no hace falta código propio)

## F1 · Specs desde Figma

- [x] Soluciones de sueño: actualizar el spec al tipo `solucion` (listado `archive-solucion.php`) → `soluciones-de-sueno.md`, vuelve a `pendiente`
- [x] Landing Angelita Pro
- [x] Landing A Dormir Por Fin
- [x] Landing Horarios Ideales (con modal de etapas y modal "Selecciona la edad")
- [x] Landing Recién Nacidos Go
- [x] Biblioteca de layouts de la landing (los que se repiten entre cursos van una sola vez) → las 4 landings son un solo spec, `solucion.md`: hero, tarjeta, compra, 10 layouts flexibles, relacionados y modales
- [x] Drawer del carrito (en `solucion.md`)
- [ ] Carrito
- [ ] Checkout
- [ ] Página de gracias (con bloque del mes gratis en Mundo) → faltan los enlaces de Figma en `specs/figma/enlaces/`
- [ ] Regalo: compra exitosa con código, "¿Te regalaron un curso?" y "Regalo canjeado" → faltan los enlaces de Figma en `specs/figma/enlaces/`
- [ ] Iniciar sesión, crear cuenta y recuperar contraseña
- [ ] Cuenta de la alumna: escritorio, mi perfil (datos y seguridad), cursos inscritos, historial de pedidos
- [ ] Cursos inscritos: proponer las vistas "Inscritos" y "Completados" (siguiendo "Activos")
- [ ] Vista de lección (temario, video, Resumen, Materiales, banner de Mundo, mensaje de Angelita Pro, cita "PD")
- [ ] Administrador de cursos: escritorio y lista de cursos de Bárbara
- [ ] Comportamiento en móvil propuesto en cada spec (no hay frames móviles)
- [ ] Todos los specs aprobados en `specs/_index.md`

## F2 · Maquetación

- [x] Listado de Soluciones de sueño maquetado (provisional en `woocommerce/archive-product.php`, rama `feat/soluciones-de-sueno`)
- [ ] Mover el listado a `archive-solucion.php` reutilizando `shop.php` y `producto-card`
- [ ] `single-solucion.php` + un archivo por layout en `template-parts/pages/solucion/`
- [ ] Modales con wp-modal: drawer del carrito, etapas, selector de edad
- [ ] Preguntas frecuentes de cada landing con wp-accordion
- [ ] Carrito, checkout y gracias en `woocommerce/`
- [ ] Regalo: pantallas de compra, canje y canjeado
- [ ] Login, registro y recuperar en `tutor/`
- [ ] Cuenta de la alumna en `tutor/dashboard/`
- [ ] Vista de lección en `tutor/`
- [ ] Escritorio y lista de cursos de Bárbara en `tutor/dashboard/`
- [ ] Constructor de Tutor con los colores de la marca (ajustes de Tutor + CSS acotado)

## F3 · Administrable (wp-acf)

- [ ] Tipo de contenido `solucion` (archivo `/soluciones-de-sueno/`)
- [ ] Campos de la tarjeta: etiqueta, ícono, edad, beneficios (la foto es la imagen destacada)
- [ ] Relación `cursos` con los cursos de Tutor
- [ ] `tipo_compra`: directa, selector de etapas o suscripción
- [ ] `precios_por_cantidad` (repetidor)
- [ ] `secciones` con Flexible Content y su biblioteca de layouts
- [ ] Texto de la garantía de 7 días en la landing
- [ ] Ajustes del sitio: enlace y textos del mes gratis en Mundo, textos del regalo
- [ ] Contenido inicial (seed) de las 4 soluciones
- [ ] Listado y landings leen ACF, sin texto fijo

## F4 · Conexión

- [ ] wp-query: listado de soluciones (12 por página, "Ver más")
- [ ] wp-woocommerce: soporte del tema, sobrescrituras con `@version`, CSS clásico de Woo quitado
- [ ] wp-woocommerce: mini-carrito que se actualiza solo (drawer)
- [ ] Borrar `wp-content/mu-plugins/angelitozzz-preview-woo.php`
- [ ] `inc/tutor/`: curso sin comprar → su solución; inscrita → la lección
- [ ] `inc/tutor/`: precio de la landing leído del producto de Woo (precio de lanzamiento = precio rebajado)
- [ ] `inc/tutor/`: banner de Mundo solo en la primera lección de cada curso (no en Angelita Pro)
- [ ] `inc/tutor/`: mensaje inicial de Angelita Pro sobre el video
- [ ] `inc/tutor/`: cita "PD" (formato "Cita" del editor + CSS)
- [x] Pedido reembolsado → se quita la inscripción: lo hace Tutor solo (`enrolled_courses_status_change`); se prueba en la verificación
- [ ] `inc/woocommerce/`: "Agregar al carrito" sin recargar y abre el drawer
- [ ] `inc/woocommerce/`: el selector de etapas agrega varios cursos
- [ ] `inc/woocommerce/`: descuento por cantidad leído de ACF
- [ ] `inc/woocommerce/`: campo de cupón en el hero
- [ ] `inc/woocommerce/`: bloque de Mundo en gracias y en el correo de pedido, excepto en regalos
- [ ] `inc/regalos/`: comprar como regalo genera el cupón (100 %, ese producto, un uso, 6 meses)
- [ ] `inc/regalos/`: canje con sesión o creando la cuenta, inscripción con la API de Tutor, cupón marcado como usado
- [ ] `inc/textos-plugins.php`: "Exercise files" → "Materiales" y demás textos de los plugins
- [ ] Relacionados automáticos: 3 soluciones distintas de la actual
- [ ] `specs/plan/sobrescrituras.md` al día

## F5 · Pagos y Pro

- [ ] Pasarela en modo prueba
- [ ] Comprar Tutor LMS Pro
- [ ] Vencimiento de inscripción: 4, 6, 12 meses o ilimitado (también en el regalo: el acceso empieza el día del canje)
- [ ] WooCommerce Subscriptions + complemento de suscripciones de Tutor Pro
- [ ] Angelita Pro: cada 4 meses con 7 días de prueba, renovación con la tarjeta guardada
- [ ] Pasarela en producción

## F6 · Cierre

- [ ] QA en escritorio, tablet y móvil
- [ ] Manual de la clienta con wp-manual
- [ ] Deploy: `pnpm build`, subir `dist/` y los PHP (sin `hot`, `node_modules/`, `src/` ni configs)

## Verificación (repetir antes de cada deploy)

- [ ] `php -l`, `pnpm build` y los `check.mjs` de design-import y wp-acf en 0
- [ ] Compra de 1 curso
- [ ] Horarios Ideales con 2 y con 3 etapas, con el descuento correcto
- [ ] Compra con cupón
- [ ] Regalo: genera el código, el canje inscribe y el acceso empieza ese día
- [ ] Angelita Pro: prueba de 7 días y renovación
- [ ] Reembolso: el pedido reembolsado quita el acceso
- [ ] Inscripción, progreso y materiales en la lección
- [ ] Banner de Mundo solo en la primera lección
- [ ] Correo de pedido con el bloque de Mundo y sin él (regalo)
- [ ] Prueba de actualización: Woo y Tutor actualizados en el Local, plantillas al día en Woo → Estado y en `specs/plan/sobrescrituras.md`
