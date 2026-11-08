# Página: Entrada del blog

- **Resumen:** Lo que guarda cada entrada del blog (tipo de contenido `post`) además de lo nativo de WordPress, para mostrarse en su artículo. No es una plantilla propia: el artículo es `single.php` (ver `articulo-blog.md`).
- **Fuente:** `figma:articulo-blog`
- **Plantilla:** ninguna
- **Tipo:** detalle
- **Contenido:** post
- **Secciones (en orden):** consejo
- **Skills usadas:** wp-acf (campos de cada entrada)
- **Grupo ACF:** Entrada del blog
- **Ubicación:** tipo de contenido = post
- **Padre:** ninguno
- **Notas de página:**
    - Título, contenido, extracto, fecha, autor, categoría e imagen destacada son nativos de WordPress: no son campos.
    - Los textos iguales en todos los artículos están en la página de opciones "Artículo del blog" (`articulo-blog.md`); aquí solo va lo que cambia en cada entrada.
    - Contenido de ejemplo para la carga inicial: el consejo del artículo de ejemplo completo (`inc/acf/seed/entrada.php`).
- **Estado:** aplicado

## Sección: consejo

- **Origen:** `specs/figma/lectura/articulo-blog/cuerpo-del-articulo.ref.txt` (bloque "Consejo de Bárbara", nodo 3751:4590)
- **Destino:** `template-parts/pages/articulo-blog/cuerpo-del-articulo.php`
- **Grupo:** `entrada_consejo`
- **Skill:** ninguna
- **Comportamiento:** el texto del consejo de cada entrada. La plantilla lo imprime al final del cuerpo del artículo, después del contenido del editor, en la caja beige con la bombilla fija y la etiqueta "Consejo de <primer nombre del autor>" (del perfil de usuario; si no tiene primer nombre, su nombre público). Si el campo queda vacío, la caja no se imprime. Se imprime con `angelitozzz_highlight()`: respeta los saltos de línea.
- **Estado:** aplicado
- **ACF:** conectado

**Campos ACF**

| Campo                   | Tipo     | Descripción                                                                                       | Valor actual                                                                                                             | Requerido |
| ----------------------- | -------- | ------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------ | --------- |
| `entrada_consejo_texto` | textarea | Consejo que se muestra al final del artículo, en la caja con la bombilla. Si se deja vacío no sale. | Antes de empezar, escribe tu plan y compártelo con tu pareja. Que ambos respondan igual hace todo más fácil para tu bebé. | no        |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] El texto del consejo se edita en cada entrada y sale al final del cuerpo; vacío, no sale
