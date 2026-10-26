<?php

function smn_render_product_image_gallery_block( $attributes, $content, $block ) {
    $post_id = ! empty( $block->context['postId'] ) ? (int) $block->context['postId'] : get_the_ID();

    if ( defined( 'REST_REQUEST' ) && REST_REQUEST && ( ! $post_id || 'wp_template' === get_post_type( $post_id ) ) ) {
        $preview_ids = get_posts(
            array(
                'post_type'      => 'product',
                'post_status'    => 'publish',
                'posts_per_page' => 1,
                'fields'         => 'ids',
                'meta_key'       => '_thumbnail_id',
            )
        );
        $post_id = ! empty( $preview_ids ) ? (int) $preview_ids[0] : 0;
    }

    if ( ! $post_id || 'product' !== get_post_type( $post_id ) ) {
        return '';
    }

    $gallery_ids = function_exists( 'get_field' ) ? get_field( 'product_image_gallery', $post_id, false ) : array();
    $image_ids = array_map( 'absint', (array) $gallery_ids );
    array_unshift( $image_ids, get_post_thumbnail_id( $post_id ) );
    $image_ids = array_values( array_filter( array_unique( $image_ids ), 'wp_attachment_is_image' ) );

    if ( empty( $image_ids ) ) {
        return '';
    }

    $gallery_id = wp_unique_id( 'product-image-gallery-' );
    $slides = array();
    $thumbnails = array();
    $count = count( $image_ids );

    foreach ( $image_ids as $index => $image_id ) {
        $alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );

        if ( '' === trim( $alt ) ) {
            $alt = get_the_title( $post_id );
        }

        $slides[] = sprintf(
            '<div class="product-image-gallery__slide">%s</div>',
            wp_get_attachment_image(
                $image_id,
                'large',
                false,
                array(
                    'class'    => 'product-image-gallery__image',
                    'alt'      => $alt,
                    'loading'  => 0 === $index ? 'eager' : 'lazy',
                    'decoding' => 'async',
                )
            )
        );

        if ( $count > 1 ) {
            $thumbnails[] = sprintf(
                '<button type="button" class="product-image-gallery__thumbnail" data-slide="%1$d" aria-label="%2$s" aria-controls="%3$s" aria-current="%4$s">%5$s</button>',
                $index,
                esc_attr( sprintf( __( 'Ver imagen %1$d de %2$d', 'polycart' ), $index + 1, $count ) ),
                esc_attr( $gallery_id . '-slides' ),
                0 === $index ? 'true' : 'false',
                wp_get_attachment_image( $image_id, 'thumbnail', false, array( 'alt' => '', 'loading' => 'lazy' ) )
            );
        }
    }

    $badges = '';

    foreach ( array( 'serie', 'product_tag' ) as $taxonomy ) {
        $terms = get_the_terms( $post_id, $taxonomy );

        if ( is_wp_error( $terms ) || empty( $terms ) ) {
            continue;
        }

        foreach ( $terms as $term ) {
            $is_tag = 'product_tag' === $taxonomy;
            $badges .= sprintf(
                '<span class="product-image-gallery__badge%1$s"><span class="product-image-gallery__badge-icon"><img src="%2$s" width="8" height="7" alt=""></span>%3$s</span>',
                $is_tag ? ' product-image-gallery__badge--primary' : '',
                esc_url( get_theme_file_uri( '/assets/icons/product-gallery-dot' . ( $is_tag ? '-white' : '' ) . '.svg' ) ),
                esc_html( $term->name )
            );
        }
    }

    $arrows = '';
    if ( $count > 1 ) {
        $arrows = sprintf(
            '<button type="button" class="product-image-gallery__arrow product-image-gallery__arrow--prev" data-gallery-prev aria-label="%1$s" disabled><span class="product-image-gallery__arrow-icon"></span></button><button type="button" class="product-image-gallery__arrow product-image-gallery__arrow--next" data-gallery-next aria-label="%2$s"><span class="product-image-gallery__arrow-icon"></span></button>',
            esc_attr__( 'Imagen anterior', 'polycart' ),
            esc_attr__( 'Imagen siguiente', 'polycart' )
        );
    }

    return sprintf(
        '<div %1$s id="%2$s" role="region" aria-roledescription="%3$s" aria-label="%4$s">
            <div class="product-image-gallery__main">
                <div class="product-image-gallery__slides" id="%2$s-slides">%5$s</div>
                %6$s
                %7$s
            </div>
            %8$s
        </div>',
        get_block_wrapper_attributes( array( 'class' => 'product-image-gallery' . ( $count > 1 ? ' product-image-gallery--carousel' : '' ) ) ),
        esc_attr( $gallery_id ),
        esc_attr__( 'Galeria de imagenes', 'polycart' ),
        esc_attr( sprintf( __( 'Imagenes de %s', 'polycart' ), get_the_title( $post_id ) ) ),
        implode( '', $slides ),
        $badges ? '<div class="product-image-gallery__badges">' . $badges . '</div>' : '',
        $arrows,
        $thumbnails ? '<div class="product-image-gallery__thumbnails" role="group" aria-label="' . esc_attr__( 'Miniaturas del producto', 'polycart' ) . '">' . implode( '', $thumbnails ) . '</div>' : ''
    );
}