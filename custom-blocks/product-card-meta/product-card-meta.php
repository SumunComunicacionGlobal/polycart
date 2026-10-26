<?php
/**
 * Render Product card meta block.
 *
 * This block intentionally has no editor settings. It only renders dynamic content
 * from the current product custom post type so the markup can be fully customized
 * in PHP without WooCommerce assumptions.
 *
 * @param array  $attributes Block attributes.
 * @param string $content    Block content.
 * @param WP_Block $block    Block instance.
 * @return string
 */
function smn_render_product_card_meta_block( $attributes, $content, $block ) {

    $post_id = get_the_ID();
    // ID de la página que contiene el loop (no el producto actual del loop)
    $page_id = get_queried_object_id();

    if ( ! $post_id ) {
        return '';
    }

    $post = get_post( $post_id );

    if ( ! $post ) {
        return '';
    }

    $keys = [ 'volumen_litros' => 'volume' ];

    if ( empty( $GLOBALS['smn_rendering_menu_products_card'] ) ) {
        $keys['carga_nominal_filtro'] = 'weight';
    }

    $items = array();

    foreach ( $keys as $field_name => $icon_name ) {
        $value = get_field( $field_name, $post_id );

        if ( false === $value || null === $value || '' === $value ) {
            continue;
        }

        $field_object = get_field_object( $field_name, $post_id );
        $suffix = isset( $field_object['append'] ) ? $field_object['append'] : '';

        $value_text = is_numeric( $value ) ? (string) $value : trim( (string) $value );

        if ( '' === $value_text ) {
            continue;
        }

        $icon_path = get_theme_file_path( '/assets/icons/' . $icon_name . '.svg' );
        $icon_svg = file_exists( $icon_path ) ? file_get_contents( $icon_path ) : '';
        $suffix = '' !== $suffix ? '<span class="product-card-meta__suffix">&nbsp;' . esc_html( $suffix ) . '</span>' : '';

        $items[] = sprintf(
            '<div class="product-card-meta__item product-card-meta__item--meta"><span class="product-card-meta__icon">%s</span><span class="product-card-meta__value">%s%s</span></div>',
            $icon_svg,
            esc_html( $value_text ),
            $suffix
        );
    }


    $all_colors = '';

    // $post_id === $page_id solo en la ficha del propio producto; en las cards del loop difieren.
    $is_product_single = ( $post_id === $page_id );

    if ( ! $is_product_single && PRODUCTOS_PAGE_ID != $page_id && have_rows( 'colores', $post_id ) && empty( $GLOBALS['smn_rendering_menu_products_card'] ) ) {

        $colores_ids = [];

        while ( have_rows( 'colores', $post_id ) ) {
            the_row();
            $colores_ids_temp = get_sub_field( 'colores_valores' );
            if ( is_array( $colores_ids_temp ) ) {
                $colores_ids = array_merge( $colores_ids, $colores_ids_temp );
            }

        }

        if ( ! empty( $colores_ids ) ) {
            $colores_ids = array_unique( $colores_ids );
            foreach ( $colores_ids as $color_id ) {
                $hex = get_field( 'color_hexadecimal', $color_id );
                $all_colors .= sprintf(
                    '<span class="product-card-meta__color" style="background-color:%s;"></span>',
                    esc_attr( $hex )
                );
            }
        }

        if ( '' !== $all_colors ) {
            $all_colors = sprintf(
                '<div class="product-card-meta__item product-card-meta__item--colors">%s</div>',
                $all_colors
            );
        }

    }

    $wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'wp-block-group is-wrap is-layout-flex product-card-meta' ) );

    return sprintf(
        '<div %1$s data-post-id="%2$d"><div class="product-card-meta__left">%3$s</div><div class="product-card-meta__right">%4$s</div></div>',
        $wrapper_attributes,
        esc_attr( $post_id ),
        implode( '', $items ),
        $all_colors
    );
}
