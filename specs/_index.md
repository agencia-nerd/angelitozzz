# Specs de Angelitozzz

Generado con design-import 1.14.0 el 2026-11-05.

Estados: `pendiente` (por revisar), `aprobado` (se puede aplicar), `descartado` (no se aplica), `aplicado` (ya está en el tema).

Antes de las páginas, revisa y aprueba [\_shared.md](_shared.md): tokens, componentes compartidos y nomenclatura.

| Página                               | Spec                                                       | Plantilla                                 | Estado   |
| ------------------------------------ | ---------------------------------------------------------- | ----------------------------------------- | -------- |
| Inicio                               | [home.md](home.md)                                         | `front-page.php`                          | aplicado |
| Conoce a Bárbara                     | [conoce-a-barbara.md](conoce-a-barbara.md)                 | `pages/template-conoce-a-barbara.php`     | aplicado |
| Contacto                             | [contacto.md](contacto.md)                                 | `pages/template-contacto.php`             | aplicado |
| Mundo Angelitozzz                    | [mundo-angelitozzz.md](mundo-angelitozzz.md)               | `pages/template-mundo-angelitozzz.php`    | aplicado |
| Preguntas frecuentes                 | [preguntas-frecuentes.md](preguntas-frecuentes.md)         | `pages/template-preguntas-frecuentes.php` | aplicado |
| Blog                                 | [blog.md](blog.md)                                         | `home.php`                                | aplicado |
| Artículo del blog                    | [articulo-blog.md](articulo-blog.md)                       | `single.php`                              | aplicado |
| Entrada del blog                     | [entrada.md](entrada.md)                                   | ninguna                                   | aplicado |
| Soluciones de sueño                  | [soluciones-de-sueno.md](soluciones-de-sueno.md)           | `archive-solucion.php`                    | aplicado |
| Solución (landing)                   | [solucion.md](solucion.md)                                 | `single-solucion.php`                     | aprobado |
| Testimonios                          | [testimonios.md](testimonios.md)                           | `archive-testimonio.php`                  | aplicado |
| Testimonio                           | [testimonio.md](testimonio.md)                             | ninguna                                   | aplicado |
| Ajustes del sitio                    | [opciones.md](opciones.md)                                 | `header.php`                              | aplicado |
| Carrito                              | [carrito.md](carrito.md)                                   | `woocommerce/cart/cart.php`               | aplicado |
| Checkout                             | [checkout.md](checkout.md)                                 | `woocommerce/checkout/form-checkout.php`  | aplicado |
| Iniciar sesión                       | [iniciar-sesion.md](iniciar-sesion.md)                     | `pages/template-iniciar-sesion.php`       | aplicado |
| Crear cuenta                         | [crear-cuenta.md](crear-cuenta.md)                         | `pages/template-crear-cuenta.php`         | aplicado |
| Recuperar contraseña                 | [recuperar-contrasena.md](recuperar-contrasena.md)         | `pages/template-recuperar-contrasena.php` | aplicado |
| Mi cuenta                            | [usuario-escritorio.md](usuario-escritorio.md)             | `pages/template-cuenta.php`               | aplicado |
| Cuenta: mi perfil                    | [usuario-mi-perfil.md](usuario-mi-perfil.md)               | `pages/template-cuenta.php`               | aplicado |
| Cuenta: cursos inscritos             | [usuario-cursos.md](usuario-cursos.md)                     | `pages/template-cuenta.php`               | aplicado |
| Cuenta: historial de pedidos         | [usuario-historial-pedido.md](usuario-historial-pedido.md) | `pages/template-cuenta.php`               | aplicado |
| Cursos: modelo de datos              | [cursos-datos.md](cursos-datos.md)                         | ninguna                                   | aprobado |
| Lección                              | [leccion.md](leccion.md)                                   | `single-leccion.php`                      | aprobado |
| Administrador                        | [admin-escritorio.md](admin-escritorio.md)                 | `pages/template-administrador.php`        | aplicado |
| Administrador: cursos                | [admin-cursos.md](admin-cursos.md)                         | `pages/template-administrador.php`        | aplicado |
| Administrador: constructor de cursos | [admin-constructor.md](admin-constructor.md)               | `pages/template-administrador.php`        | aprobado |
| Canjear regalo                       | [canjear-regalo.md](canjear-regalo.md)                     | `pages/template-canjear-regalo.php`       | aplicado |
| Gracias por tu compra                | [gracias.md](gracias.md)                                   | `woocommerce/checkout/thankyou.php`       | aplicado |
| Página no encontrada (404)           | [error-404.md](error-404.md)                               | `404.php`                                 | aplicado |
| Archivos privados (wp-admin)         | [archivos-privados.md](archivos-privados.md)               | ninguna                                   | aprobado |

<!-- design-import:paginas:fin -->

**Nota (2026-11-18).** `aplicado` quiere decir que el diseño ya está en el tema. No quiere decir que la página funcione de punta a punta.

Desde la F5-c (2026-11-19) ninguna página aplicada espera lógica pendiente del plugin: gracias muestra los códigos de regalo y canjear regalo tiene su acción.

**Archivos privados (2026-11-25).** Solución, Cursos: modelo de datos, Lección y Administrador: constructor vuelven a `aprobado` por el cambio de los videos (de Vimeo a archivos privados del sitio) y de los materiales (más tipos que PDF). Lo ya hecho de esas páginas sigue funcionando; solo las secciones en `aprobado` dentro de cada spec cambian. Se aplican en los bloques 2 a 5 de `plan/checklist-cursos.md` → "F5-d".

El avance real está en `plan/checklist-cursos.md`. La línea "Generado con design-import 1.14.0" es la versión con la que se crearon los specs; hoy el proyecto usa design-import 1.17.0 (`CLAUDE.md`).
