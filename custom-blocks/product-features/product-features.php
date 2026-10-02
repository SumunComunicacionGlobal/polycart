<?php
/**
* Product features block
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$features = get_field( 'caracteristicas' );
if ( $features ) :

    $display = ( get_field( 'mostrar_como' ) === 'cuadricula' ) ? '' : 'as-layout-stack';

    echo '<div class="wp-block-group is-layout-grid product-features ' . esc_attr( $display ) . '">';
    foreach ( $features as $feature ) {
        echo '<div class="product-feature">';
            echo '<div class="product-feature__inner">';
                echo '<div class="product-feature__thumbnail">';
                    echo get_the_post_thumbnail( $feature, 'medium_large' );
                echo '</div>'; // Close product-feature__thumbnail
                echo '<div class="product-feature__content">';
                    echo '<div class="product-feature__title-row">';
                        echo '<div class="product-feature__title">'; 
                            echo esc_html( get_the_title( $feature ) );
                        echo '</div>'; // Close product-feature__title
                        echo '<div class="product-feature__tags">';
                            $tags = get_the_terms( $feature, 'feature-tag' );
                            if ( $tags && ! is_wp_error( $tags ) ) {
                                foreach ( $tags as $tag ) {
                                    echo '<span class="product-feature__tag">' . esc_html( $tag->name ) . '</span>';
                                }
                            }
                        echo '</div>'; // Close product-feature__tags
                    echo '</div>'; // Close product-feature__title-row
                    echo apply_filters( 'the_content', get_post_field( 'post_content', $feature ) );
                echo '</div>'; // Close product-feature__content
            echo '</div>'; // Close product-feature__inner
        echo '</div>'; // Close product-feature
    }
    echo '</div>';
endif;