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
 * Angelitozzz: los menús quedan vacíos a propósito mientras sus páginas no existan (todas, salvo
 * Inicio, siguen pendientes). El motor crea cada menú una sola vez y omite las páginas que faltan,
 * así que hoy dejaría menús casi vacíos que reemplazarían los enlaces del diseño. Mientras tanto,
 * angelitozzz_menu() (inc/menus.php) muestra esos enlaces. Las ubicaciones se registran en inc/setup.php.
 * Se completan cuando las páginas de la tabla "Menús" de specs/_shared.md estén aplicadas.
 * El logo no tiene archivo en el tema: se sube a mano en Personalizar > Identidad del sitio.
 */
return [
    'menus' => [],
    'logo'  => '',
];
