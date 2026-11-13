<?php

/**
 * Mundo Angelitozzz · promocinar-marca: situaciones y para quién es
 * (specs/mundo-angelitozzz.md, grupo mundo_angelitozzz_promocinar_marca).
 * Lee su grupo ACF; el contenido inicial está en inc/acf/seed/mundo-angelitozzz.php.
 */

defined('ABSPATH') || exit;

$grupo              = get_field('mundo_angelitozzz_promocinar_marca') ?: [];
$situaciones_titulo = (string) ($grupo['situaciones_titulo'] ?? '');
$situaciones        = (array) ($grupo['situaciones'] ?? []);
$situaciones_nota   = (string) ($grupo['situaciones_nota'] ?? '');
$perfiles_titulo    = (string) ($grupo['perfiles_titulo'] ?? '');
$perfiles           = (array) ($grupo['perfiles'] ?? []);
?>
<section class="bg-blanco px-4 py-12 lg:py-22" aria-label="<?= esc_attr__('Para quién es Mundo Angelitozzz', 'angelitozzz') ?>">
    <div class="mx-auto grid max-w-[1191px] rounded-tarjeta bg-fondo p-6 lg:grid-cols-2 lg:rounded-[30px] lg:px-18 lg:py-14">
        <div class="flex flex-col gap-6 lg:pr-10">
            <h2 class="text-h4 text-marca lg:text-h3"><?= esc_html($situaciones_titulo) ?></h2>
            <ul class="flex list-disc flex-col gap-3 pl-6 text-cuerpo text-tinta marker:text-tinta">
                <?php foreach ($situaciones as $fila) : ?>
                    <li><?= esc_html((string) ($fila['texto'] ?? '')) ?></li>
                <?php endforeach; ?>
            </ul>
            <?php if ($situaciones_nota !== '') : ?>
                <p class="text-nota italic text-[#c4704a]"><?= angelitozzz_highlight($situaciones_nota) ?></p>
            <?php endif; ?>
        </div>
        <div class="mt-6 flex flex-col gap-6 border-t border-neutro pt-6 lg:mt-0 lg:border-l lg:border-t-0 lg:pl-10 lg:pt-0">
            <h2 class="text-h4 text-marca lg:text-h3"><?= esc_html($perfiles_titulo) ?></h2>
            <ul class="flex list-disc flex-col gap-3 pl-6 text-cuerpo text-tinta marker:text-tinta">
                <?php foreach ($perfiles as $fila) : ?>
                    <li><?= esc_html((string) ($fila['texto'] ?? '')) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</section>
