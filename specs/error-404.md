# Página: Página no encontrada (404)

- **Resumen:** Lo que ve quien llega a una dirección que no existe (un enlace mal escrito, una página borrada). Le dice qué pasó y le da dos caminos para seguir: el inicio y Soluciones de sueño.
- **Fuente:** propuesta sin Figma (2026-11-25), con la tarjeta de `specs/figma/lectura/canjear-regalo/canjeado.ref.txt` (nodo 3677:1971)
- **Plantilla:** `404.php`
- **Tipo:** estática
- **Contenido:** ninguno
- **Secciones (en orden):** aviso
- **Skills usadas:** ninguna
- **Grupo ACF:** ninguno
- **Ubicación:** ninguna
- **Padre:** ninguno
- **Notas de página:**
    - Pedido del usuario (2026-11-25): proponer y hacer la página 404 (antes caía en `index.php`, sin diseño).
    - No es una página de WordPress: WordPress elige `404.php` solo para cualquier URL que no existe, con el estado HTTP 404. No lleva `Template Name`.
    - Sin campos ACF: los textos son fijos del tema (text domain `angelitozzz`), como en gracias y canjear regalo.
    - `h1`: "No encontramos esta página". Título SEO "Página no encontrada" (lo pone WordPress; Yoast llega después).
    - Fondo crema `--color-fondo`; la tarjeta blanca centrada (componente `confirmacion-tarjeta`), con el mismo espacio que canjear regalo (`py-30`, en móvil `py-14`). Cintillo, header y footer salen de `header.php` y `footer.php`.
    - La búsqueda general de WordPress (`/?s=`) no llega aquí: redirige al blog (`blog.md`).
- **Estado:** aplicado

## Sección: aviso

- **Origen:** propuesta sin Figma, con la tarjeta de `specs/figma/lectura/canjear-regalo/canjeado.ref.txt` (nodo 3677:1971)
- **Destino:** `404.php`, `template-parts/pages/error-404/aviso.php`, `template-parts/components/confirmacion-tarjeta.php`, `template-parts/components/boton.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** componente `confirmacion-tarjeta` sin ícono, con la pastilla neutra "Error 404", el `h1` "No encontramos esta página" y el texto "Puede que el enlace esté mal escrito o que la página ya no exista. Te dejamos algunos caminos para seguir.". Debajo, dos `boton` en una fila centrada (`gap-4`): frambuesa "Ir al inicio" (la portada) y secundario "Ver soluciones de sueño" (el archivo de `solucion`, `/soluciones-de-sueno/`; si el tipo no tiene archivo, ese botón no se imprime). Cierra con `confirmacion-tarjeta-fin`, sin nota. Móvil (propuesta): la tarjeta ocupa el ancho con 16 px de margen, el título baja a `--text-h3` y los botones van uno debajo del otro, a todo el ancho de la tarjeta y con el texto centrado.
- **Estado:** aplicado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] Una URL que no existe responde con el estado HTTP 404 y muestra esta tarjeta, con el cintillo, el header y el footer del sitio
- [x] "Ir al inicio" lleva a la portada y "Ver soluciones de sueño" a `/soluciones-de-sueno/`
- [x] A 390 y 320 px los botones van uno debajo del otro y no hay desplazamiento horizontal (probado en Chrome, 2026-11-25)
