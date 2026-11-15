<?php

/**
 * TEMPORAL (F4, solo maquetación): contenido de muestra de las 4 soluciones, igual al de Figma.
 *
 * Imita lo que en la F5 devolverán ACF (wp-acf), WooCommerce (precios) y wp-query (relacionados):
 * las claves son los nombres de los campos de specs/solucion.md y specs/soluciones-de-sueno.md, así
 * que cada vista cambia `angelitozzz_solucion_muestra()` por `get_field()` sin tocar su marcado.
 * Se borra en la F5 junto con inc/solucion-muestra/.
 *
 * inc/solucion-muestra/<parte>.php devuelve [slug => datos de esa parte] (hero y un archivo por layout).
 */

defined('ABSPATH') || exit;

/** Slugs de las 4 soluciones, en el orden del listado. */
function angelitozzz_solucion_muestra_slugs(): array
{
    return ['angelita-pro', 'a-dormir-por-fin', 'recien-nacidos-go', 'horarios-ideales'];
}

/** Lee inc/solucion-muestra/<parte>.php (una sola vez por petición). */
function angelitozzz_solucion_muestra_parte(string $parte): array
{
    static $partes = [];

    if (!isset($partes[$parte])) {
        $archivo        = get_theme_file_path('inc/solucion-muestra/' . sanitize_key($parte) . '.php');
        $partes[$parte] = is_readable($archivo) ? (array) require $archivo : [];
    }

    return $partes[$parte];
}

/** URL de la landing de una solución. */
function angelitozzz_solucion_muestra_url(string $slug): string
{
    return home_url('/soluciones-de-sueno/' . $slug . '/');
}

/**
 * Precio de muestra (woo: sale del producto de WooCommerce del curso). `antes` solo con rebaja;
 * `desde` con etapas (el precio más bajo, sin "antes").
 */
function angelitozzz_solucion_muestra_precio(string $slug): array
{
    $precios = [
        'angelita-pro'      => ['actual' => '$67', 'antes' => '$97', 'desde' => false],
        'a-dormir-por-fin'  => ['actual' => '$399', 'antes' => '', 'desde' => false],
        'recien-nacidos-go' => ['actual' => '$67', 'antes' => '$97', 'desde' => false],
        'horarios-ideales'  => ['actual' => '$37', 'antes' => '', 'desde' => true],
    ];

    return $precios[$slug] ?? ['actual' => '', 'antes' => '', 'desde' => false];
}

/** Grupo `solucion_compra` de muestra. */
function angelitozzz_solucion_muestra_compra(string $slug): array
{
    $tipos = [
        'angelita-pro'      => 'suscripcion',
        'a-dormir-por-fin'  => 'directa',
        'recien-nacidos-go' => 'directa',
        'horarios-ideales'  => 'etapas',
    ];
    $tipo = $tipos[$slug] ?? 'directa';

    return [
        'tipo'       => $tipo,
        'descuentos' => $tipo === 'etapas' ? [['cantidad' => 2, 'total' => 67], ['cantidad' => 3, 'total' => 97]] : [],
    ];
}

/**
 * Tarjetas del listado (grupo `solucion_tarjeta` + título e imagen destacada), en el formato de
 * producto-card. Las imágenes son las de muestra del diseño, guardadas en assets/img/.
 */
function angelitozzz_solucion_muestra_tarjetas(): array
{
    $img = static fn(string $archivo): string => get_theme_file_uri('assets/img/' . $archivo);

    $tarjetas = [
        'angelita-pro' => [
            'imagen'         => '', // Vacía a propósito: el componente muestra la imagen por defecto "not found".
            'etiqueta'       => 'Nuevo · Desde $97',
            'etiqueta_icono' => 'destellos',
            'titulo'         => 'Angelita Pro',
            'edad'           => '(4 a 24 meses)',
            'descripcion'    => 'Tu coach de sueño con inteligencia artificial, entrenada con el método Angelitozzz. Disponible 24/7 por WhatsApp — sin turnos, sin horarios, sin esperas.',
            'beneficios'     => [
                'Plan integral de sueño — noches y siestas',
                'Respuestas inmediatas a tus dudas en tiempo real',
                'Seguimiento paso a paso las 24 horas',
                'Acceso desde cualquier lugar por WhatsApp',
                'Empieza esta noche',
            ],
            'boton_texto'    => 'Quiero conocer a Angelita',
        ],
        'a-dormir-por-fin' => [
            'imagen'         => $img('shop-a-dormir-por-fin.webp'),
            'etiqueta'       => 'Asesoría con Bárbara · $399',
            'etiqueta_icono' => 'estrella',
            'titulo'         => 'A Dormir Por Fin',
            'edad'           => '(4 meses a 4 años)',
            'descripcion'    => 'Bárbara diseña el plan específico para tu bebé y te acompaña de cerca durante 3 semanas.',
            'beneficios'     => [
                'Plan personalizado para noches y/o siestas',
                'WhatsApp ilimitado con Bárbara durante 3 semanas',
                'Ajustes diarios según la respuesta de tu bebé',
                'Acceso premium directo con Bárbara',
            ],
            'boton_texto'    => 'Quiero mi asesoría personal',
        ],
        'recien-nacidos-go' => [
            'imagen'         => $img('shop-recien-nacidos-go.webp'),
            'etiqueta'       => 'Curso Online · $97',
            'etiqueta_icono' => 'ninguno',
            'titulo'         => 'Recién Nacidos Go',
            'edad'           => '(0 a 12 semanas)',
            'descripcion'    => 'Todo lo que necesitas saber para que tu recién nacido duerma mejor — técnicas suaves, rutinas claras y 100% compatible con la lactancia.',
            'beneficios'     => [
                '11 videos cortos y prácticos',
                'PDF con teoría, rutinas y horarios',
                'Acceso por 6 meses',
                'Bonus: 4 sesiones de coaching grupal en vivo con Bárbara',
            ],
            'boton_texto'    => 'Ver curso',
        ],
        'horarios-ideales' => [
            'imagen'         => $img('shop-horarios-ideales.webp'),
            'etiqueta'       => 'Curso Online · Desde $37',
            'etiqueta_icono' => 'ninguno',
            'titulo'         => 'Cursos de Horarios Ideales',
            'edad'           => '(4 meses a 3 años)',
            'descripcion'    => 'Aprende a crear el horario ideal de sueño para tu bebé según su edad. Olvida las tablas genéricas y deja de adivinar.',
            'beneficios'     => [
                'Videos explicativos por etapa',
                'Guías de horarios descargables',
                'Ejemplos prácticos para días difíciles',
                'Disponible para 4–9, 9–15 y 15–36 meses',
            ],
            'boton_texto'    => 'Ver cursos por edad',
        ],
    ];

    foreach ($tarjetas as $slug => &$tarjeta) {
        $tarjeta['slug'] = $slug;
        $tarjeta['url']  = angelitozzz_solucion_muestra_url($slug);
    }
    unset($tarjeta);

    return $tarjetas;
}

/**
 * Relacionadas (wp-query: angelitozzz_query_related()): hasta 3 soluciones distintas de la actual,
 * en la versión corta de producto-card (etiqueta del hero, edad + precio, botón "Más información").
 */
function angelitozzz_solucion_muestra_relacionadas(string $actual): array
{
    $heros = angelitozzz_solucion_muestra_parte('hero');
    $items = [];

    foreach (angelitozzz_solucion_muestra_tarjetas() as $slug => $tarjeta) {
        if ($slug === $actual) {
            continue;
        }
        $precio = angelitozzz_solucion_muestra_precio($slug);
        $edad   = trim((string) $tarjeta['edad'], '() ');
        if ($precio['actual'] !== '') {
            $edad .= ' · ' . ($precio['desde'] ? 'Desde ' : '') . $precio['actual'];
        }

        $items[] = [
            'imagen'         => $tarjeta['imagen'],
            'etiqueta'       => (string) ($heros[$slug]['etiqueta'] ?? ''),
            'etiqueta_icono' => $tarjeta['etiqueta_icono'],
            'titulo'         => $tarjeta['titulo'],
            'edad'           => $edad,
            'boton'          => ['title' => __('Más información', 'angelitozzz'), 'url' => $tarjeta['url']],
        ];
    }

    return array_slice($items, 0, 3);
}

/**
 * Orden de los bloques de cada landing (specs/solucion.md → "Orden de cada landing").
 * Cada layout aparece una sola vez por landing.
 */
function angelitozzz_solucion_muestra_orden(string $slug): array
{
    $orden = [
        'angelita-pro'      => ['iconos', 'lista', 'beneficios', 'galeria', 'testimonios', 'preguntas'],
        'a-dormir-por-fin'  => ['comparacion', 'iconos', 'lista', 'beneficios', 'experiencia', 'pasos', 'testimonios', 'preguntas'],
        'horarios-ideales'  => ['etapas', 'iconos', 'lista', 'beneficios', 'testimonios', 'preguntas'],
        'recien-nacidos-go' => ['iconos', 'lista', 'galeria', 'testimonios', 'preguntas'],
    ];

    return $orden[$slug] ?? [];
}

/**
 * Todo lo que necesita single-solucion.php para una solución:
 * - hero:    grupo `solucion_hero`
 * - compra:  grupo `solucion_compra`
 * - precio:  precio de muestra (woo)
 * - bloques: filas de `solucion_secciones_bloques`, cada una con `acf_fc_layout`
 */
function angelitozzz_solucion_muestra(string $slug): array
{
    $bloques = [];
    foreach (angelitozzz_solucion_muestra_orden($slug) as $layout) {
        $fila = angelitozzz_solucion_muestra_parte($layout)[$slug] ?? null;
        if (is_array($fila)) {
            $bloques[] = ['acf_fc_layout' => $layout] + $fila;
        }
    }

    return [
        'hero'    => (array) (angelitozzz_solucion_muestra_parte('hero')[$slug] ?? []),
        'compra'  => angelitozzz_solucion_muestra_compra($slug),
        'precio'  => angelitozzz_solucion_muestra_precio($slug),
        'bloques' => $bloques,
    ];
}
