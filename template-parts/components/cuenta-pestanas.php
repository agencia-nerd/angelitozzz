<?php

/**
 * Pestañas de la cuenta (tarjetas de 173 × 94 con ícono sobre el texto: usuario-mi-perfil,
 * usuario-cursos, usuario-historial-pedido). Dos modos:
 * - 'enlaces': cada pestaña es un enlace (cursos: ?pestana=); la activa lleva aria-current.
 * - 'tabs': role="tablist" que cambia de panel sin recargar (src/js/modules/pestanas.js; perfil).
 *   Cada panel lo imprime la vista con id = "<id>-panel" y role="tabpanel". Sin JS se ven todos.
 * Con una sola pestaña (historial) es un título con el estilo de la activa.
 *
 * $args:
 * - pestanas (array de ['id', 'texto', 'icono' (assets/img/cuenta/<icono>.svg), 'url' (enlaces), 'numero' opcional]; requerido)
 * - activa   (string, requerido: id de la pestaña activa)
 * - modo     ('enlaces' | 'tabs', por defecto 'enlaces')
 * - etiqueta (string, opcional: nombre accesible del grupo)
 */

defined('ABSPATH') || exit;

$pestanas = array_values(array_filter((array) ($args['pestanas'] ?? []), static fn($p): bool => is_array($p) && !empty($p['id']) && !empty($p['texto'])));
$activa   = (string) ($args['activa'] ?? '');
$modo     = ($args['modo'] ?? 'enlaces') === 'tabs' ? 'tabs' : 'enlaces';
$etiqueta = (string) ($args['etiqueta'] ?? '');

if (!$pestanas) {
    return;
}

$clase = static fn(bool $es_activa): string => 'flex h-[94px] w-[173px] shrink-0 cursor-pointer flex-col items-center justify-center gap-2 rounded-[15px] px-3 text-center text-cuerpo-l focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca '
    . ($es_activa ? 'border-2 border-contorno bg-[#fafeff] text-marca' : 'border-2 border-transparent bg-blanco text-tinta hover:border-neutro');

$contenido = static function (array $p): string {
    $numero = isset($p['numero']) ? ' <span class="text-tinta-suave">(' . esc_html((string) (int) $p['numero']) . ')</span>' : '';
    return angelitozzz_svg('cuenta/' . (string) ($p['icono'] ?? ''), 'h-[35px] w-auto max-w-[35px] text-marca')
        . '<span>' . esc_html((string) $p['texto']) . $numero . '</span>';
};

$grupo = 'flex gap-[10px] overflow-x-auto pb-1 max-lg:-mx-4 max-lg:px-4';
?>
<?php if (count($pestanas) === 1) : ?>
    <div class="<?= esc_attr($grupo) ?>">
        <p class="<?= esc_attr($clase(true)) ?>"><?= $contenido($pestanas[0]) // Escapado en $contenido. ?></p>
    </div>
<?php elseif ($modo === 'tabs') : ?>
    <div class="<?= esc_attr($grupo) ?>" role="tablist"<?= $etiqueta !== '' ? ' aria-label="' . esc_attr($etiqueta) . '"' : '' ?> data-pestanas>
        <?php foreach ($pestanas as $p) :
            $id        = sanitize_html_class((string) $p['id']);
            $es_activa = $p['id'] === $activa;
            ?>
            <?php // En tabs el aspecto de la activa sale de aria-selected (lo cambia el JS), no de una clase fija. ?>
            <button class="<?= esc_attr($clase(false) . ' aria-selected:border-contorno aria-selected:bg-[#fafeff] aria-selected:text-marca') ?>" type="button" role="tab"
                id="<?= esc_attr($id) ?>-pestana" aria-controls="<?= esc_attr($id) ?>-panel"
                aria-selected="<?= $es_activa ? 'true' : 'false' ?>" tabindex="<?= $es_activa ? '0' : '-1' ?>">
                <?= $contenido($p) // Escapado en $contenido. ?>
            </button>
        <?php endforeach; ?>
    </div>
<?php else : ?>
    <nav class="<?= esc_attr($grupo) ?>"<?= $etiqueta !== '' ? ' aria-label="' . esc_attr($etiqueta) . '"' : '' ?>>
        <?php foreach ($pestanas as $p) :
            $es_activa = $p['id'] === $activa;
            ?>
            <a class="<?= esc_attr($clase($es_activa)) ?>" href="<?= esc_url((string) ($p['url'] ?? '')) ?>"<?= $es_activa ? ' aria-current="page"' : '' ?>>
                <?= $contenido($p) // Escapado en $contenido. ?>
            </a>
        <?php endforeach; ?>
    </nav>
<?php endif; ?>
