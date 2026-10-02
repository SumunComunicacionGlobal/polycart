<?php
/**
 * Render Color Spot block.
 *
 * Bloque dinámico que muestra un círculo con el color del campo ACF color_hexadecimal.
 *
 * @param array $attributes Block attributes.
 * @return string
 */
function smn_render_color_spot_block( $attributes ) {
    $color_id = ! empty( $attributes['colorId'] ) ? (int) $attributes['colorId'] : get_the_ID();
    if ( ! $color_id ) return '';

    $hex = get_field( 'color_hexadecimal', $color_id );
    if ( ! $hex ) return '';

    return sprintf(
        '<div %s><span class="color-spot__circle" style="background-color:%s;"></span></div>',
        get_block_wrapper_attributes(),
        esc_attr( $hex )
    );
}
