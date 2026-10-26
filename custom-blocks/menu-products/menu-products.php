<?php
/**
 * Render Menú Products.
 *
 * Muestra el catálogo agrupado por product_cat: título, series en badges y
 * una card por producto renderizada desde la parte "product-menu".
 */
function smn_render_menu_products_block( $attributes, $content, $block ) {

    $categories = get_terms(
        array(
            'taxonomy'   => 'product_cat',
            'hide_empty' => true,
        )
    );

    if ( is_wp_error( $categories ) || empty( $categories ) ) {
        return '';
    }

    $card_template = get_block_template( get_stylesheet() . '//product-menu', 'wp_template_part' );
    $card_content  = $card_template ? $card_template->content : '';

    $sections = '';

    foreach ( $categories as $category ) {
        $product_ids = get_posts(
            array(
                'post_type'      => 'product',
                'post_status'    => 'publish',
                'posts_per_page' => -1,
                'fields'         => 'ids',
                'no_found_rows'  => true,
                'tax_query'      => array(
                    array(
                        'taxonomy' => 'product_cat',
                        'terms'    => $category->term_id,
                    ),
                ),
            )
        );

        if ( empty( $product_ids ) ) {
            continue;
        }

        $badges = '';
        $series = wp_get_object_terms( $product_ids, 'serie' );

        if ( ! is_wp_error( $series ) ) {
            foreach ( $series as $serie ) {
                $badges .= sprintf( '<span class="is-style-badge">%s</span>', esc_html( $serie->name ) );
            }
        }

        $cards = '';

        foreach ( $product_ids as $product_id ) {
            $cards .= '<div class="menu-products__item">' . smn_render_menu_products_card( $card_content, $product_id ) . '</div>';
        }

        $sections .= sprintf(
            '<section class="menu-products__category">
                <p class="wp-block-paragraph menu-products__title"><a href="%1$s">%2$s</a></p>
                %3$s
                <div class="wp-block-group is-layout-grid menu-products__grid">%4$s</div>
            </section>',
            esc_url( get_term_link( $category ) ),
            esc_html( $category->name ),
            '' !== $badges ? '<div class="menu-products__series">' . $badges . '</div>' : '',
            $cards
        );
    }

    if ( '' === $sections ) {
        return '';
    }

    return sprintf(
        '<div %1$s>%2$s</div>',
        get_block_wrapper_attributes( array( 'class' => 'menu-products' ) ),
        $sections
    );
}

/**
 * Render the product-menu template part for a single product.
 *
 * @param string $card_content Block markup of the template part.
 * @param int    $product_id   Product ID.
 * @return string
 */
function smn_render_menu_products_card( $card_content, $product_id ) {
    global $post;

    if ( '' === trim( $card_content ) ) {
        return '';
    }

    $previous_post = $post;
    // render_block() builds the postId/postType context from the global post.
    $post = get_post( $product_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
    setup_postdata( $post );

    $context_key = 'smn_rendering_menu_products_card';
    $had_previous_context = array_key_exists( $context_key, $GLOBALS );
    $previous_context = $had_previous_context ? $GLOBALS[ $context_key ] : null;
    $GLOBALS[ $context_key ] = true;

    try {
        $html = do_blocks( $card_content );
    } finally {
        if ( $had_previous_context ) {
            $GLOBALS[ $context_key ] = $previous_context;
        } else {
            unset( $GLOBALS[ $context_key ] );
        }
    }

    $post = $previous_post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

    if ( $post ) {
        setup_postdata( $post );
    }

    return $html;
}
