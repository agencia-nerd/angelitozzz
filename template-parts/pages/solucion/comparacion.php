<?php

/**
 * Solución · bloque `comparacion` (specs/solucion.md, solucion_secciones_bloques).
 *
 * Caja crema con dos columnas y un divisor vertical: a la izquierda el problema (una viñeta por
 * línea del campo), a la derecha el resultado. Móvil: una columna con el divisor horizontal.
 *
 * $args: fondo, titulo_izquierda, texto_izquierda, titulo_derecha, texto_derecha (+ contexto, indice).
 */

defined('ABSPATH') || exit;

$titulo_izquierda = (string) ($args['titulo_izquierda'] ?? '');
$titulo_derecha   = (string) ($args['titulo_derecha'] ?? '');
$texto_derecha    = (string) ($args['texto_derecha'] ?? '');
// Una viñeta por línea (el campo es una lista, no un texto con saltos).
$vinetas = array_values(array_filter(
    array_map('trim', preg_split('/\R/', (string) ($args['texto_izquierda'] ?? '')) ?: []),
    'strlen'
));

if ($titulo_izquierda === '' && $titulo_derecha === '') {
    return;
}

$id_titulo = wp_unique_id('solucion-comparacion-titulo-');
?>
<section class="<?= esc_attr('px-4 py-[85px] max-md:py-14 ' . angelitozzz_solucion_fondo((string) ($args['fondo'] ?? 'blanco'))) ?>" aria-labelledby="<?= esc_attr($id_titulo) ?>">
    <div class="mx-auto flex max-w-[1272px] items-center justify-center gap-[110px] bg-fondo p-14 max-lg:flex-col max-lg:items-stretch max-lg:gap-10 max-md:px-6 max-md:py-10">
        <div class="flex w-[470px] flex-col gap-[23px] max-lg:w-full">
            <h2 id="<?= esc_attr($id_titulo) ?>" class="text-h3 text-marca max-md:text-h4"><?= angelitozzz_highlight($titulo_izquierda) ?></h2>
            <?php if ($vinetas) : ?>
                <ul class="lista-puntos text-cuerpo text-tinta">
                    <?php foreach ($vinetas as $vineta) : ?>
                        <li><?= angelitozzz_highlight($vineta) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <div class="w-px shrink-0 self-stretch bg-neutro max-lg:h-px max-lg:w-full" aria-hidden="true"></div>

        <div class="flex w-[470px] flex-col gap-[23px] max-lg:w-full">
            <h2 class="text-h3 text-marca max-md:text-h4"><?= angelitozzz_highlight($titulo_derecha) ?></h2>
            <?php if ($texto_derecha !== '') : ?>
                <p class="text-cuerpo text-tinta"><?= angelitozzz_highlight($texto_derecha) ?></p>
            <?php endif; ?>
        </div>
    </div>
</section>
