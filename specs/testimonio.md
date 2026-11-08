# Página: Testimonio

- **Resumen:** Lo que guarda cada testimonio (tipo de contenido `testimonio`) para mostrarse en la tarjeta de la página Testimonios. No es una página que el visitante abra: el diseño no tiene ficha individual.
- **Fuente:** `figma:testimonios`
- **Plantilla:** ninguna
- **Tipo:** detalle
- **Contenido:** testimonio
- **Secciones (en orden):** ficha
- **Skills usadas:** ninguna
- **Grupo ACF:** Testimonio
- **Ubicación:** tipo de contenido = testimonio
- **Padre:** ninguno
- **Notas de página:**
    - incluye las skills necesarias.
    - Esta pagina no deberia de tener internas.
    - Los testimonios son solo un mostrario en `/testimonios/`: no tienen página individual, no redirigen a ninguna parte y la tarjeta no es un enlace. El tipo de contenido se registra sin URL propia (no consultable públicamente) y no hay `single-testimonio.php`.
    - Nombre, foto y texto son nativos de WordPress (título, imagen destacada, contenido): no son campos.
    - La categoría es la taxonomía nativa `categoria_testimonio` (Soluciones de Sueño, Mundo Angelitozzz), una sola por testimonio; no es un campo ACF y se elige en la caja de la taxonomía.
    - Los campos se imprimen en `template-parts/components/testimonio-card.php`, que usa `archive-testimonio.php` (ver `testimonios.md`).
    - Contenido de ejemplo para la carga inicial: las 5 tarjetas del diseño (Giannina, Andrea, Javiera, Bernardita, Joanna) con sus fotos de `specs/figma/lectura/testimonios/assets/`.
- **Estado:** aplicado

## Sección: ficha

- **Origen:** `specs/figma/lectura/testimonios/seccion-testimonios.ref.txt` (tarjetas de la lista, nodo 3749:4226)
- **Destino:** `template-parts/components/testimonio-card.php`
- **Grupo:** `testimonio_ficha`
- **Skill:** ninguna
- **Comportamiento:** las estrellas se pintan con `--color-estrella` (las llenas) y `--color-neutro` (las vacías), y se leen como un solo texto accesible ("5 de 5 estrellas"). La edad va en la pastilla crema junto a la categoría y se oculta si está vacía. La categoría no es un campo ACF: se elige en la caja nativa de la taxonomía `categoria_testimonio` (wp-acf 1.8.0 no genera campos `taxonomy`) y la tarjeta la lee del término; se espera una sola por testimonio.
- **Estado:** aplicado
- **ACF:** conectado

**Campos ACF**

| Campo                        | Tipo         | Descripción                                                                                                                     | Valor actual      | Requerido |
| ---------------------------- | ------------ | ------------------------------------------------------------------------------------------------------------------------------- | ----------------- | --------- |
| `testimonio_ficha_estrellas` | button_group | Valoración del testimonio, de 1 a 5 estrellas (opciones: 1, 2, 3, 4, 5; por defecto 5). Se muestra junto al nombre              | 5                 | sí        |
| `testimonio_ficha_edad`      | text         | Edad del bebé tal como se lee en la tarjeta, en la pastilla junto a la categoría.                   | Bebé de 2.8 años  | no        |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] Las estrellas muestran el valor del campo y se leen como un solo texto accesible
- [x] Sin edad, la pastilla de edad no se imprime
