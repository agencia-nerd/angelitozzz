<?php

/**
 * Mundo Angelitozzz · qué incluye (specs/mundo-angelitozzz.md, grupo mundo_angelitozzz_que_incluye_mundo).
 * Lee su grupo ACF; el contenido inicial está en inc/acf/seed/mundo-angelitozzz.php.
 * La nube asoma sobre la sección siguiente.
 */

defined('ABSPATH') || exit;

$grupo      = get_field('mundo_angelitozzz_que_incluye_mundo') ?: [];
$antetitulo = (string) ($grupo['antetitulo'] ?? '');
$titulo     = (string) ($grupo['titulo'] ?? '');
$bajada     = (string) ($grupo['bajada'] ?? '');
$beneficios = (array) ($grupo['beneficios'] ?? []);

$img = static fn(string $archivo): string => esc_url(get_theme_file_uri('assets/img/mundo-angelitozzz/' . $archivo));

// El enlace de suscripción es el del hero (el pago se hace fuera del sitio).
$hero = get_field('mundo_angelitozzz_hero') ?: [];
?>
<section class="relative z-10 bg-etiqueta px-4 py-16 lg:py-26" aria-labelledby="mundo-angelitozzz-incluye-titulo">
    <img class="pointer-events-none absolute left-[6%] top-6 w-[30px] lg:w-[60px]" src="<?= $img('que-incluye-destello-izquierda.svg') ?>" alt="" aria-hidden="true" width="60" height="63">
    <img class="pointer-events-none absolute right-[6%] top-6 w-6 lg:w-[46px]" src="<?= $img('que-incluye-destello-derecha.svg') ?>" alt="" aria-hidden="true" width="46" height="47">
    <img class="pointer-events-none absolute bottom-[-40px] right-[-3.13%] w-40 lg:bottom-[-65px] lg:w-[300px]" src="<?= $img('que-incluye-nube.svg') ?>" alt="" aria-hidden="true" width="300" height="131">

    <div class="relative mx-auto max-w-sitio">
        <div class="flex flex-col items-center gap-5 text-center">
            <?php if ($antetitulo !== '') : ?>
                <p class="text-eyebrow text-marca"><?= esc_html($antetitulo) ?></p>
            <?php endif; ?>
            <h2 id="mundo-angelitozzz-incluye-titulo" class="max-w-[900px] text-h3 text-marca lg:text-h1"><?= angelitozzz_highlight($titulo) ?></h2>
            <?php if ($bajada !== '') : ?>
                <p class="max-w-[760px] text-body text-marca lg:text-body-l"><?= angelitozzz_highlight($bajada) ?></p>
            <?php endif; ?>
        </div>

        <ul class="mt-11 grid items-stretch gap-8 lg:grid-cols-3">
            <?php foreach ($beneficios as $beneficio) :
                $titulo_tarjeta = (string) ($beneficio['titulo'] ?? '');
                $etiqueta       = (string) ($beneficio['etiqueta'] ?? '');
            ?>
                <li class="flex flex-col overflow-hidden rounded-tarjeta bg-blanco">
                    <?= angelitozzz_image($beneficio['imagen'] ?? null, [
                        'class'        => 'aspect-[349/220] w-full object-cover',
                        'alt_fallback' => $titulo_tarjeta,
                    ]) ?>
                    <div class="flex flex-1 flex-col items-start gap-2.5 p-7">
                        <h3 class="text-h4 text-marca"><?= esc_html($titulo_tarjeta) ?></h3>
                        <p class="text-body text-tinta"><?= angelitozzz_highlight((string) ($beneficio['texto'] ?? '')) ?></p>
                        <?php if ($etiqueta !== '') : ?>
                            <span class="mt-auto rounded-full bg-fondo px-3.5 py-1.5 text-label-s text-marca"><?= esc_html($etiqueta) ?></span>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="mt-11 flex justify-center">
            <?php get_template_part('template-parts/components/boton', null, [
                'enlace'   => ['url' => (string) ($hero['enlace_suscripcion'] ?? ''), 'target' => '_blank'],
                'texto'    => (string) ($grupo['boton'] ?? ''),
                'variante' => 'verde',
            ]); ?>
        </div>
    </div>
</section>
