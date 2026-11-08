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
// [id, categoría, título, fecha, imagen destacada] de cada entrada de ejemplo del diseño.
$entradas = [
    ['entrada-especiales-1', 'Especiales', 'Aprendizaje del sueño y las lágrimas', '2024-03-01 09:00:00', 'assets/img/blog/especiales-tarjeta-1-foto.webp'],
    ['entrada-especiales-2', 'Especiales', '5 señales de que tu bebé está en transición de siestas', '2024-02-12 09:00:00', 'assets/img/blog/especiales-tarjeta-2-foto.webp'],
    ['entrada-especiales-3', 'Especiales', 'Cómo crear el ambiente ideal para dormir', '2024-01-28 09:00:00', 'assets/img/blog/especiales-tarjeta-3-foto.webp'],
    ['entrada-bebes-0-4-1', 'Bebés 0 – 4 meses', 'Los primeros días: qué esperar del sueño', '2024-03-01 09:00:00', 'assets/img/blog/bebes-0-4-tarjeta-1-foto.webp'],
    ['entrada-bebes-0-4-2', 'Bebés 0 – 4 meses', 'Día y noche: cómo ayudarle a diferenciarlos', '2024-02-12 09:00:00', 'assets/img/blog/bebes-0-4-tarjeta-2-foto.webp'],
    ['entrada-bebes-0-4-3', 'Bebés 0 – 4 meses', 'Envolver o no envolver: guía práctica', '2024-01-28 09:00:00', 'assets/img/blog/bebes-0-4-tarjeta-3-foto.webp'],
    ['entrada-bebes-4-24-1', 'Bebés 4 – 24 meses', 'Regresión de los 4 meses: qué está pasando', '2024-03-01 09:00:00', 'assets/img/blog/bebes-4-24-tarjeta-1-foto.webp'],
    ['entrada-bebes-4-24-2', 'Bebés 4 – 24 meses', 'Ventanas de vigilia según la edad', '2024-02-12 09:00:00', 'assets/img/blog/bebes-4-24-tarjeta-2-foto.webp'],
    ['entrada-bebes-4-24-3', 'Bebés 4 – 24 meses', 'Despertares nocturnos: causas comunes', '2024-01-28 09:00:00', 'assets/img/blog/bebes-4-24-tarjeta-3-foto.webp'],
    ['entrada-bebes-24m-4a-1', 'Bebés 24 meses – 4 años', 'De la cuna a la cama: cuándo y cómo', '2024-03-01 09:00:00', 'assets/img/blog/bebes-24m-4a-tarjeta-1-foto.webp'],
    ['entrada-bebes-24m-4a-2', 'Bebés 24 meses – 4 años', 'Pesadillas y terrores nocturnos', '2024-02-12 09:00:00', 'assets/img/blog/bebes-24m-4a-tarjeta-2-foto.webp'],
    ['entrada-bebes-24m-4a-3', 'Bebés 24 meses – 4 años', 'Rutinas de noche que sí funcionan', '2024-01-28 09:00:00', 'assets/img/blog/bebes-24m-4a-tarjeta-3-foto.webp'],
];

$items = [];
foreach ($entradas as [$id, $categoria, $titulo, $fecha, $imagen]) {
    $items[] = [
        'id'        => $id,
        'titulo'    => $titulo,
        'fecha'     => $fecha,
        'extracto'  => $resumen,
        'contenido' => $contenido,
        'imagen'    => ['_file' => $imagen, 'alt' => $titulo],
        'terms'     => ['category' => [$categoria]],
    ];
}

// Artículo de ejemplo completo (specs/articulo-blog.md, cuerpo-del-articulo): todo el contenido del diseño
// (entradilla, h2, párrafos, lista, imagen con pie y cita) en HTML normal, más el consejo en su campo ACF
// (specs/entrada.md), para comprobar que el cuerpo se ve igual que el diseño. La imagen del contenido va con ruta
// relativa (sin dominio).
$foto_contenido = '/wp-content/themes/angelitozzz/assets/img/articulo-blog/cuerpo-foto-bebe-gorro.webp';
$items[] = [
    'id'        => 'entrada-ejemplo-completo',
    'titulo'    => '¿Por qué llora cuando cambiamos sus hábitos?',
    'fecha'     => '2024-03-02 09:00:00',
    'extracto'  => 'Cuando un bebé aprende a dormir de otra manera, es normal que proteste. La clave está en cómo lo acompañamos.',
    'imagen'    => ['_file' => 'assets/img/articulo-blog/articulo-ejemplo-destacada.webp', 'alt' => 'Bebé durmiendo tranquilo'],
    'terms'     => ['category' => ['Especiales']],
    'contenido' => implode("\n\n", [
        '<!-- wp:paragraph --><p>Cuando un bebé aprende a dormir de otra manera, es normal que proteste. Llorar es su forma de decir “esto es nuevo para mí”. La clave está en cómo lo acompañamos.</p><!-- /wp:paragraph -->',
        '<!-- wp:heading --><h2 class="wp-block-heading">¿Por qué llora cuando cambiamos sus hábitos?</h2><!-- /wp:heading -->',
        '<!-- wp:paragraph --><p>Los bebés se acostumbran a dormirse de una forma específica: en brazos, con pecho o en movimiento. Cuando cambiamos esa forma, necesitan tiempo para aprender una nueva. El llanto en esos primeros días no significa que algo esté mal, sino que están procesando el cambio.</p><!-- /wp:paragraph -->',
        '<!-- wp:paragraph --><p>Lo importante es que nunca se queden solos sin respuesta: acompañarlos con calma y consistencia es lo que les da seguridad.</p><!-- /wp:paragraph -->',
        '<!-- wp:quote --><blockquote class="wp-block-quote"><!-- wp:paragraph --><p>“El llanto no es el enemigo. La inconsistencia, sí. Un plan claro hace que el cambio dure días, no semanas.”</p><!-- /wp:paragraph --><cite>Bárbara</cite></blockquote><!-- /wp:quote -->',
        '<!-- wp:heading --><h2 class="wp-block-heading">Tres claves para acompañarlo</h2><!-- /wp:heading -->',
        '<!-- wp:list --><ul class="wp-block-list"><!-- wp:list-item --><li>Elige un momento tranquilo para empezar, no en medio de un viaje o una enfermedad.</li><!-- /wp:list-item --><!-- wp:list-item --><li>Mantén la misma respuesta cada vez: la consistencia le da seguridad.</li><!-- /wp:list-item --><!-- wp:list-item --><li>Cuida tu descanso: una mamá descansada acompaña mejor.</li><!-- /wp:list-item --></ul><!-- /wp:list -->',
        '<!-- wp:image {"sizeSlug":"full"} --><figure class="wp-block-image size-full"><img src="' . $foto_contenido . '" alt="Bebé con gorro durmiendo"/><figcaption class="wp-element-caption">Un ambiente oscuro, tranquilo y seguro ayuda a que el cambio sea más fácil.</figcaption></figure><!-- /wp:image -->',
        '<!-- wp:heading --><h2 class="wp-block-heading">¿Cuánto dura esta etapa?</h2><!-- /wp:heading -->',
        '<!-- wp:paragraph --><p>En la mayoría de las familias, las protestas disminuyen en pocos días cuando el plan se aplica con constancia. Cada bebé es distinto, y por eso un plan personalizado marca la diferencia.</p><!-- /wp:paragraph -->',
    ]),
    'fields'    => [
        'entrada_consejo' => [
            'texto' => 'Antes de empezar, escribe tu plan y compártelo con tu pareja. Que ambos respondan igual hace todo más fácil para tu bebé.',
        ],
    ],
];

return [
    'post_type' => 'post',
    'terms'     => ['category' => ['Especiales', 'Bebés 0 – 4 meses', 'Bebés 4 – 24 meses', 'Bebés 24 meses – 4 años']],
    'items'     => $items,
];
