<?php

/**
 * Inicio · testimonios (specs/home.md, grupo home_testimonios).
 * Repetidor propio de Inicio, independiente del tipo `testimonio` y de otras páginas.
 */

defined('ABSPATH') || exit;

get_template_part('template-parts/components/testimonios-slider', null, [
    'titulo'      => 'SHHH... Bebés durmiendo felices',
    'testimonios' => [
        [
            'texto'     => '“Probé de todo y nada funcionaba. Con el plan de Bárbara, en una semana mi bebé dormía en su cuna.”',
            'autora'    => 'Carolina, mamá de Tomás',
            'detalle'   => 'Bebé de 6 meses',
            'estrellas' => 5,
        ],
        [
            'texto'     => '“Bárbara, te hago un altar: en 4 días avanzamos lo que jamás pensé lograr, sin dejarlo llorar.”',
            'autora'    => 'Daniela, mamá de Mateo',
            'detalle'   => 'Bebé de 8 meses',
            'estrellas' => 5,
        ],
        [
            'texto'     => '“Compré el curso con mi hija de 8 semanas; a los 4 meses dormía de corrido. Hoy tiene 2 años y sigue durmiendo increíble.”',
            'autora'    => 'Lucía, mamá de Emilia',
            'detalle'   => 'Bebé de 2 años',
            'estrellas' => 5,
        ],
        [
            'texto'     => '“Mi bebé tiene 12 días y ya la puedo poner en el moisés. Antes solo dormía en brazos.”',
            'autora'    => 'Valeria, mamá de Joaquín',
            'detalle'   => 'Bebé de 12 días',
            'estrellas' => 5,
        ],
        [
            'texto'     => '“Pensé que nunca iba a volver a dormir una noche completa. Hoy dormimos todos.”',
            'autora'    => 'Andrea, mamá de Lucas',
            'detalle'   => 'Bebé de 7 meses',
            'estrellas' => 5,
        ],
    ],
    'boton'       => ['url' => home_url('/testimonios/'), 'title' => 'Ver más testimonios', 'target' => ''],
]);
