<?php

/**
 * TEMPORAL (F4): layout `lista` de cada solución, con los textos de Figma (specs/solucion.md).
 * Las fotos son las de muestra del diseño (assets/img/solucion/<slug>/). Se borra en la F5.
 */

defined('ABSPATH') || exit;

$foto = static fn(string $slug): string => get_theme_file_uri('assets/img/solucion/' . $slug . '/lista-foto.webp');

return [
    'angelita-pro' => [
        'fondo'  => 'crema',
        'titulo' => '¿Qué incluye Angelita Pro?',
        'imagen' => '', // El diseño no trae foto: la lista se centra.
        'items'  => [
            ['texto' => 'Angelita — 2 semanas de acompañamiento 24/7 por WhatsApp. La activas cuando estés lista.'],
            ['texto' => 'Videos de preparación e implementación: Bárbara te explica qué va a pasar cada noche.'],
            ['texto' => 'Tu Plan de Sueño descargable: el paso a paso completo, siempre a mano.'],
            ['texto' => 'Plan de siestas incluido: noches y siestas, tú decides si trabajas ambas o solo una.'],
            ['texto' => 'Semanas adicionales a $27 USD cada una, si necesitas más tiempo.'],
        ],
    ],
    'a-dormir-por-fin' => [
        'fondo'  => 'crema',
        'titulo' => '¿Qué incluye tu asesoría?',
        'imagen' => $foto('a-dormir-por-fin'),
        'items'  => [
            ['texto' => 'Videollamada inicial — una sesión de 1 hora para conocernos y crear el plan ideal para ti y tu bebé de acuerdo a tu estilo.'],
            ['texto' => 'Plan de sueño integral personalizado — métodos, horarios, rutinas y todo lo que necesitas para empezar.'],
            ['texto' => '3 semanas de WhatsApp ilimitado con Bárbara — cada duda, cada pregunta, respondida por Bárbara personalmente.'],
            ['texto' => 'Plan de siestas incluido (si deseas).'],
            ['texto' => 'Ajustes según la respuesta de tu bebé.'],
        ],
    ],
    'horarios-ideales' => [
        'fondo'  => 'rosa',
        'titulo' => '¿Qué aprenderás?',
        'imagen' => $foto('horarios-ideales'),
        'items'  => [
            ['texto' => 'Cómo combinar señales de sueño y ventanas de vigilia para crear rutinas que funcionen.'],
            ['texto' => 'A identificar los rangos de horario ideales para tu bebé — no promedios genéricos.'],
            ['texto' => 'Cómo manejar días complicados: siestas cortas, cansancio acumulado y saltos de desarrollo.'],
            ['texto' => 'Qué hacer cuando los horarios cambian con cada etapa — para adaptarte sin volver a cero.'],
            ['texto' => 'Cómo organizar tu día para que el sueño fluya naturalmente, sin peleas ni frustración.'],
            ['texto' => 'Cómo manejar las madrugadas y los despertares tempranos antes de las 6am.'],
        ],
    ],
    'recien-nacidos-go' => [
        'fondo'  => 'blanco',
        'titulo' => '¿Qué aprenderás?',
        'imagen' => $foto('recien-nacidos-go'),
        'items'  => [
            ['texto' => 'Entender el sueño de tu recién nacido según su edad y etapa — sin confusión ni información contradictoria.'],
            ['texto' => 'Identificar sus señales de sueño tempranas para actuar antes de que llegue al llanto.'],
            ['texto' => 'Calmar el llanto con 5 herramientas prácticas y efectivas.'],
            ['texto' => 'Usar correctamente el swaddle, el ruido blanco y otras herramientas de calma para ayudarle a dormir mejor.'],
            ['texto' => 'Crear el ambiente de sueño ideal para tu bebé.'],
            ['texto' => 'Establecer ventanas de vigilia y horarios flexibles para su edad.'],
            ['texto' => 'Construir rutinas suaves que le ayuden a anticipar el sueño.'],
            ['texto' => 'Manejar los despertares nocturnos para alargar sus bloques de sueño.'],
            ['texto' => 'Sentar las bases para que aprenda a conciliar el sueño de manera gradual e independiente.'],
        ],
    ],
];
