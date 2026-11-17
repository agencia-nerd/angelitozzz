<?php
defined('ABSPATH') || exit;

/*
 * Menús y logo del sitio (skill wp-acf). Datos del proyecto: se escriben desde la tabla "Menús" de
 * specs/_shared.md y el diseño. wp-acf registra las ubicaciones y, la primera vez, crea los menús.
 *
 * 'menus'  => [
 *     'principal' => [
 *         'nombre' => 'Menú principal',
 *         'items'  => [
 *             ['titulo' => 'Inicio', 'pagina' => 'home'],                       // 'pagina' = slug del spec
 *             ['titulo' => 'Nosotros', 'pagina' => 'nosotros', 'hijos' => [...]],
 *             ['titulo' => 'Reglamentos', 'url' => '#', 'clases' => 'subtitulo'], // sin enlace
 *         ],
 *     ],
 * ],
 * 'logo'   => 'assets/img/logo.webp',   // archivo del tema que pasa a Personalizar > Identidad del sitio
 */
/*
 * Angelitozzz: los nombres son los mismos de inc/setup.php. Las páginas del tema van con 'pagina' (claves de
 * inc/acf/paginas.php); los archivos de un tipo de contenido (/soluciones-de-sueno/, /testimonios/) y las páginas
 * legales, que aún no existen, van con 'url'. Mientras una ubicación no tenga menú, angelitozzz_menu()
 * (inc/menus.php) muestra los mismos enlaces del diseño.
 * El logo no tiene archivo en el tema: se sube a mano en Personalizar > Identidad del sitio.
 */
return [
    'menus' => [
        'principal' => [
            'nombre' => 'Menú principal',
            'items'  => [
                ['titulo' => 'Soluciones de Sueño', 'url' => '/soluciones-de-sueno/'],
                ['titulo' => 'Blog', 'pagina' => 'blog'],
                ['titulo' => 'Mundo Angelitozzz', 'pagina' => 'mundo-angelitozzz'],
                ['titulo' => 'Conoce a Bárbara', 'pagina' => 'conoce-a-barbara'],
                ['titulo' => 'Testimonios', 'url' => '/testimonios/'],
            ],
        ],
        'cabecera-derecha' => [
            'nombre' => 'Header, a la derecha',
            'items'  => [
                ['titulo' => 'Contacto', 'pagina' => 'contacto'],
            ],
        ],
        'pie-navegacion' => [
            'nombre' => 'Pie: navegación',
            'items'  => [
                ['titulo' => 'Soluciones de Sueño', 'url' => '/soluciones-de-sueno/'],
                ['titulo' => 'Mundo Angelitozzz', 'pagina' => 'mundo-angelitozzz'],
                ['titulo' => 'Conoce a Bárbara', 'pagina' => 'conoce-a-barbara'],
                ['titulo' => 'Testimonios', 'url' => '/testimonios/'],
                ['titulo' => 'Blog', 'pagina' => 'blog'],
                ['titulo' => 'Contacto', 'pagina' => 'contacto'],
            ],
        ],
        'pie-legal' => [
            'nombre' => 'Pie: legal',
            'items'  => [
                ['titulo' => 'Políticas de privacidad', 'url' => '/politicas-de-privacidad/'],
                ['titulo' => 'Términos y condiciones', 'url' => '/terminos-y-condiciones/'],
            ],
        ],
        'pie-ayuda' => [
            'nombre' => 'Pie: ayuda',
            'items'  => [
                ['titulo' => 'Preguntas frecuentes', 'pagina' => 'preguntas-frecuentes'],
            ],
        ],
    ],
    'logo'  => '',
];
