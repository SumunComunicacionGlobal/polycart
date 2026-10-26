<?php
/**
 * Render Product colors block.
 *
 * Bloque dinámico sin opciones de editor. Muestra los colores disponibles
 * del producto actual a partir del repetidor ACF "colores".
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Block content.
 * @param WP_Block $block      Block instance.
 * @return string
 */
function smn_render_product_colors_block( $attributes, $content, $block ) {

    $post_id = get_the_ID();


    if ( ! $post_id ) {
        return '';
    }

    if ( ! have_rows( 'colores', $post_id ) ) {
        return '';
    }

    $colores_ids = array();

    while ( have_rows( 'colores', $post_id ) ) {

        the_row();
        $colores_ids_temp = get_sub_field( 'colores_valores' );

        if ( is_array( $colores_ids_temp ) ) {
            $colores_ids = array_merge( $colores_ids, $colores_ids_temp );
        }
    }

    if ( empty( $colores_ids ) ) {
        return '';
    }

    $items = array();

    foreach ( array_unique( $colores_ids ) as $color_id ) {
        $hex = get_field( 'color_hexadecimal', $color_id );

        if ( ! $hex ) {
            continue;
        }

        $items[] = sprintf(
            '<span class="product-colors__color color-spot__circle" style="background-color:%s;" title="%s"></span>',
            esc_attr( $hex ),
            esc_attr( get_the_title( $color_id ) )
        );
    }

    if ( empty( $items ) ) {
        return '';
    }

    return sprintf(
        '<div %1$s><span class="product-colors__label">%2$s</span><div class="product-colors__colors">%3$s</div></div>',
        get_block_wrapper_attributes( array( 'class' => 'product-colors' ) ),
        esc_html__( 'Colores disponibles:', 'polycart' ),
        implode( '', $items )
    );
}
