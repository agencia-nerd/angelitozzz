<?php

/**
 * Menú de la cuenta ("drawer" de usuario-escritorio 4164:25044 y de las demás vistas): saludo con el
 * nombre (h1), los ítems de la cuenta y "Cerrar sesión". En móvil el saludo queda arriba y los ítems
 * pasan a una fila con scroll propio (la página no se desplaza a los lados).
 *
 * $args:
 * - menu   ('alumna' | 'instructora', por defecto 'alumna')
 * - activo (alumna: 'escritorio' | 'perfil' | 'cursos' | 'pedidos'; instructora: 'escritorio' | 'cursos'; requerido)
 */

defined('ABSPATH') || exit;

$menu   = ($args['menu'] ?? 'alumna') === 'instructora' ? 'instructora' : 'alumna';
$activo = (string) ($args['activo'] ?? '');

$usuaria = wp_get_current_user();
$nombre  = trim((string) $usuaria->first_name) !== '' ? (string) $usuaria->first_name : (string) $usuaria->display_name;

$url = static fn(string $vista): string => function_exists('angelitozzz_cursos_url_pagina')
    ? angelitozzz_cursos_url_pagina('cuenta', $vista)
    : home_url('/cuenta/' . ($vista !== '' ? $vista . '/' : ''));

$items = $menu === 'instructora'
    ? [
        'escritorio' => ['texto' => __('Escritorio', 'angelitozzz'), 'url' => home_url('/administrador/'), 'icono' => 'menu-escritorio'],
        'cursos'     => ['texto' => __('Cursos', 'angelitozzz'), 'url' => home_url('/administrador/cursos/'), 'icono' => 'menu-cursos'],
    ]
    : [
        'escritorio' => ['texto' => __('Escritorio', 'angelitozzz'), 'url' => $url(''), 'icono' => 'menu-escritorio'],
        'perfil'     => ['texto' => __('Mi perfil', 'angelitozzz'), 'url' => $url('perfil'), 'icono' => 'menu-perfil'],
        'cursos'     => ['texto' => __('Cursos inscritos', 'angelitozzz'), 'url' => $url('cursos'), 'icono' => 'menu-cursos'],
        'pedidos'    => ['texto' => __('Historial de pedidos', 'angelitozzz'), 'url' => $url('pedidos'), 'icono' => 'menu-pedidos'],
    ];

$ancho = $menu === 'instructora' ? 'lg:w-[458px]' : 'lg:w-[377px]';
?>
<aside class="<?= esc_attr('flex shrink-0 flex-col gap-12 lg:min-h-[1140px] lg:border-r lg:border-tinta lg:pr-14 max-lg:gap-6 max-lg:border-b max-lg:border-neutro max-lg:pb-6 ' . $ancho) ?>">
    <div class="flex flex-col gap-10 max-lg:gap-2">
        <p class="text-cuerpo-s text-tinta" aria-hidden="true"><?= esc_html__('Hola,', 'angelitozzz') ?></p>
        <h1 class="text-h1 text-marca max-md:text-h3">
            <span class="sr-only"><?= esc_html__('Hola,', 'angelitozzz') ?> </span><?= esc_html($nombre) ?>
        </h1>
    </div>

    <nav class="-mx-4 overflow-x-auto px-4 lg:mx-0 lg:overflow-visible lg:px-0" aria-label="<?= esc_attr($menu === 'instructora' ? __('Administrador de cursos', 'angelitozzz') : __('Mi cuenta', 'angelitozzz')) ?>">
        <ul class="flex gap-[14px] lg:flex-col max-lg:w-max">
            <?php foreach ($items as $clave => $item) :
                $es_activo = $clave === $activo;
                ?>
                <li>
                    <a class="<?= esc_attr('flex h-[50px] items-center gap-[15px] rounded-[15px] pl-[18px] pr-6 whitespace-nowrap lg:w-[321px] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca ' . ($es_activo ? 'bg-marca text-h5 text-blanco' : 'text-cuerpo-l text-tinta hover:bg-blanco')) ?>"
                        href="<?= esc_url($item['url']) ?>"<?= $es_activo ? ' aria-current="page"' : '' ?>>
                        <span class="<?= esc_attr('flex size-9 shrink-0 items-center justify-center rounded-full ' . ($es_activo ? 'bg-blanco text-marca' : 'bg-etiqueta text-blanco')) ?>">
                            <?= angelitozzz_svg('cuenta/' . $item['icono'], 'size-6') // SVG fijo del tema. ?>
                        </span>
                        <span><?= esc_html($item['texto']) ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <a class="inline-flex items-center gap-[10px] self-start text-rotulo text-marca focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca lg:mt-auto lg:pl-7" href="<?= esc_url(wp_logout_url(home_url('/'))) ?>">
        <span><?= esc_html__('Cerrar sesión', 'angelitozzz') ?></span>
        <svg class="size-[18px] shrink-0" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M3.75 9h10.5M9 3.75 14.25 9 9 14.25" /></svg>
    </a>
</aside>
