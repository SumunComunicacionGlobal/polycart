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
function smn_render_product_colors_categorized_block( $attributes, $content, $block ) {

    $post_id = get_the_ID();

    if ( ! $post_id ) {
        return '';
    }

    if ( ! have_rows( 'colores', $post_id ) ) {
        return '';
    }

    $rows = array();

    while ( have_rows( 'colores', $post_id ) ) {
        the_row();

        $label = get_sub_field( 'colores_etiqueta' );
        $color_ids = get_sub_field( 'colores_valores' );
        $notes = get_sub_field( 'colores_notas' );

        if ( empty( $color_ids ) ) {
            continue;
        }

        $color_ids = array_filter( (array) $color_ids, 'strlen' );
        $color_ids = array_values( array_unique( $color_ids ) );

        if ( empty( $color_ids ) ) {
            continue;
        }

        $swatches = array();

        foreach ( $color_ids as $color_id ) {
            $hex = get_field( 'color_hexadecimal', $color_id );

            if ( ! $hex ) {
                continue;
            }

            $swatches[] = sprintf(
                '<div class="product-colors__color-wrapper"><span class="product-colors__color color-spot__circle" style="background-color:%s;" title="%s"></span><span class="product-colors__color-label">%s</span></div>',
                esc_attr( $hex ),
                esc_attr( get_the_title( $color_id ) ),
                esc_html( get_the_title( $color_id ) )
            );
        }

        if ( empty( $swatches ) ) {
            continue;
        }

        $label_html = '';
        if ( ! empty( $label ) ) {
            $label_html = sprintf(
                '<span class="product-colors-categorized__label">%s</span>',
                esc_html( $label )
            );
        }

        $notes_html = '';
        if ( ! empty( $notes ) ) {
            $notes_html = sprintf(
                '<p class="product-colors-categorized__notes">%s</p>',
                esc_html( $notes )
            );
        }

        $rows[] = sprintf(
            '<div class="product-colors-categorized__row">%1$s<div class="product-colors product-colors-categorized__colors">%2$s</div>%3$s</div>',
            $label_html,
            implode( '', $swatches ),
            $notes_html
        );
    }

    if ( empty( $rows ) ) {
        return '';
    }

    return sprintf(
        '<div %1$s>%2$s</div>',
        get_block_wrapper_attributes( array( 'class' => 'product-colors-categorized' ) ),
        implode( '', $rows )
    );
}
