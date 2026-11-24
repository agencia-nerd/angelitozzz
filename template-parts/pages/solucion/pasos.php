<?php

/**
 * Solución · bloque `pasos` (specs/solucion.md, solucion_secciones_bloques).
 *
 * Antetítulo, título, bajada, tarjetas blancas (foto, "Paso n" numerado solo, título, texto y etiqueta
 * crema) y botón verde. Decoración fija: dos destellos arriba y una nube abajo a la derecha.
 * Móvil: tarjetas apiladas (2 en tablet).
 *
 * $args: fondo, antetitulo, titulo, bajada, items (imagen, titulo, texto, etiqueta), whatsapp, whatsapp_texto
 * (+ contexto, indice). El botón abre WhatsApp con el número de Ajustes del sitio (angelitozzz_whatsapp);
 * sin número no se muestra.
 */

defined('ABSPATH') || exit;

$antetitulo = (string) ($args['antetitulo'] ?? '');
$titulo     = (string) ($args['titulo'] ?? '');
$bajada     = (string) ($args['bajada'] ?? '');
$boton      = !empty($args['whatsapp']) ? angelitozzz_whatsapp((string) ($args['whatsapp_texto'] ?? '')) : null;
$items      = array_values(array_filter(
    (array) ($args['items'] ?? []),
    static fn($item): bool => is_array($item) && trim((string) ($item['titulo'] ?? '')) !== ''
));

if ($titulo === '' && !$items) {
    return;
}

$id_titulo = wp_unique_id('solucion-pasos-titulo-');
$deco      = static fn(string $archivo): string => get_theme_file_uri('assets/img/solucion/' . $archivo);
?>
<section class="<?= esc_attr('relative overflow-hidden px-4 py-[104px] max-md:py-14 ' . angelitozzz_solucion_fondo((string) ($args['fondo'] ?? 'salvia'))) ?>" aria-labelledby="<?= esc_attr($id_titulo) ?>">
    <img class="pointer-events-none absolute left-[13.02%] top-[8.23%] h-[58px] w-[60px] max-lg:hidden" src="<?= esc_url($deco('pasos-destello-izquierda.svg')) ?>" alt="" aria-hidden="true" width="60" height="58" loading="lazy">
    <img class="pointer-events-none absolute right-[13.75%] top-[10.98%] h-[43px] w-[46px] max-lg:hidden" src="<?= esc_url($deco('pasos-destello-derecha.svg')) ?>" alt="" aria-hidden="true" width="46" height="43" loading="lazy">
    <img class="pointer-events-none absolute -bottom-px right-[-2.5%] h-[131px] w-[300px] max-md:h-[65px] max-md:w-[150px]" src="<?= esc_url($deco('pasos-nube.svg')) ?>" alt="" aria-hidden="true" width="300" height="131" loading="lazy">

    <div class="relative mx-auto flex max-w-[1110px] flex-col items-center gap-5 text-center">
        <?php if ($antetitulo !== '') : ?>
            <p class="text-eyebrow text-marca"><?= esc_html($antetitulo) ?></p>
        <?php endif; ?>
        <h2 id="<?= esc_attr($id_titulo) ?>" class="max-w-[900px] text-h1 text-marca max-md:text-h3"><?= angelitozzz_highlight($titulo) ?></h2>
        <?php if ($bajada !== '') : ?>
            <p class="max-w-[760px] text-cuerpo-l text-marca"><?= angelitozzz_highlight($bajada) ?></p>
        <?php endif; ?>

        <?php if ($items) : ?>
            <ol class="mt-6 grid w-full grid-cols-1 gap-8 text-left md:grid-cols-2 lg:grid-cols-3">
                <?php foreach ($items as $n => $item) :
                    $etiqueta = trim((string) ($item['etiqueta'] ?? ''));
                    ?>
                    <li class="flex flex-col overflow-hidden rounded-tarjeta bg-blanco">
                        <?php $foto = angelitozzz_image($item['imagen'] ?? '', [
                            'class'        => 'size-full object-cover',
                            'alt_fallback' => (string) $item['titulo'],
                        ]); ?>
                        <?php if ($foto !== '') : ?>
                            <div class="aspect-[349/220] w-full overflow-hidden"><?= $foto ?></div>
                        <?php endif; ?>
                        <div class="flex flex-1 flex-col items-start gap-[10px] p-7 pb-14 max-md:pb-8">
                            <p class="text-rotulo-s text-acento"><?= esc_html(sprintf(__('Paso %d', 'angelitozzz'), $n + 1)) ?></p>
                            <h3 class="text-h4 text-marca"><?= angelitozzz_highlight((string) $item['titulo']) ?></h3>
                            <p class="text-cuerpo text-tinta"><?= angelitozzz_highlight((string) ($item['texto'] ?? '')) ?></p>
                            <?php if ($etiqueta !== '') : ?>
                                <span class="rounded-full bg-fondo px-[14px] py-[6px] text-rotulo-s text-marca"><?= esc_html($etiqueta) ?></span>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ol>
        <?php endif; ?>

        <?php if ($boton) : ?>
            <div class="mt-11 max-md:mt-6">
                <?php get_template_part('template-parts/components/boton', null, [
                    'enlace'   => $boton,
                    'variante' => 'verde',
                ]); ?>
            </div>
        <?php endif; ?>
    </div>
</section>
