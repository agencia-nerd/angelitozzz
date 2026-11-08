<?php
defined('ABSPATH') || exit;

/*
 * Entradas de ejemplo del blog (specs/blog.md, datos de ejemplo de cada sección de categoría).
 * Título, extracto, fecha y categoría salen del diseño; el contenido es de relleno y debe reemplazarse.
 * 'imagen' es la imagen destacada: el motor no la lee; la pone inc/acf/fotos-destacadas.php.
 * Los carga inc/acf/runner.php una sola vez por item (por su id): se pueden borrar desde Entradas.
 */

$resumen   = 'Qué esperar cuando tu bebé protesta y cómo acompañarlo con calma, sin culpa.';
$contenido = '<p>' . $resumen . '</p><p>Este es un artículo de ejemplo. Reemplaza este texto desde Entradas.</p>';
$fechas    = ['2024-03-01 09:00:00', '2024-02-12 09:00:00', '2024-01-28 09:00:00'];

$categorias = [
    'Especiales' => ['especiales', [
        'Aprendizaje del sueño y las lágrimas',
        '5 señales de que tu bebé está en transición de siestas',
        'Cómo crear el ambiente ideal para dormir',
    ]],
    'Bebés 0 – 4 meses' => ['bebes-0-4', [
        'Los primeros días: qué esperar del sueño',
        'Día y noche: cómo ayudarle a diferenciarlos',
        'Envolver o no envolver: guía práctica',
    ]],
    'Bebés 4 – 24 meses' => ['bebes-4-24', [
        'Regresión de los 4 meses: qué está pasando',
        'Ventanas de vigilia según la edad',
        'Despertares nocturnos: causas comunes',
    ]],
    'Bebés 24 meses – 4 años' => ['bebes-24m-4a', [
        'De la cuna a la cama: cuándo y cómo',
        'Pesadillas y terrores nocturnos',
        'Rutinas de noche que sí funcionan',
    ]],
];

$items = [];
foreach ($categorias as $categoria => [$prefijo, $titulos]) {
    foreach ($titulos as $i => $titulo) {
        $n = $i + 1;
        $items[] = [
            'id'        => "entrada-{$prefijo}-{$n}",
            'titulo'    => $titulo,
            'fecha'     => $fechas[$i],
            'extracto'  => $resumen,
            'contenido' => $contenido,
            'imagen'    => ['_file' => "assets/img/blog/{$prefijo}-tarjeta-{$n}-foto.webp", 'alt' => $titulo],
            'terms'     => ['category' => [$categoria]],
        ];
    }
}

return [
    'post_type' => 'post',
    'terms'     => ['category' => array_keys($categorias)],
    'items'     => $items,
];
