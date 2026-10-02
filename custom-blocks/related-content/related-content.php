<?php
/**
 * Render the related content block.
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Block content.
 * @param WP_Block $block      Block instance.
 * @return string
 */
function smn_render_related_content_block( $attributes, $content, $block ) {
	$post_id   = isset( $block->context['postId'] ) ? (int) $block->context['postId'] : get_the_ID();
	$post_type = isset( $block->context['postType'] ) ? $block->context['postType'] : get_post_type( $post_id );
	$is_rest_request = defined( 'REST_REQUEST' ) && REST_REQUEST;

	if ( $is_rest_request && 'wp_template' === $post_type ) {
		$template      = get_post( $post_id );
		$template_type = array(
			'single'         => 'post',
			'single-post'    => 'post',
			'single-product' => 'product',
		);
		$post_type     = $template && isset( $template_type[ $template->post_name ] )
			? $template_type[ $template->post_name ]
			: $post_type;
	}

	$config = array(
		'post'    => array(
			'taxonomy' => 'category',
			'badge'    => __( 'Artículos', 'polycart' ),
			'heading'  => __( 'Artículos relacionados', 'polycart' ),
            'limit'    => 3,
		),
		'product' => array(
			'taxonomy' => 'product_cat',
			'badge'    => __( 'Productos', 'polycart' ),
			'heading'  => __( 'Modelos relacionados', 'polycart' ),
            'limit'    => 9,
		),
	);

	if ( ! $post_id || ! isset( $config[ $post_type ] ) ) {
		return '';
	}

	$taxonomy = $config[ $post_type ]['taxonomy'];

	if ( $is_rest_request && 'wp_template' === get_post_type( $post_id ) ) {
		$preview_posts = get_posts(
			array(
				'post_type'      => $post_type,
				'post_status'    => 'publish',
				'posts_per_page' => 20,
				'fields'         => 'ids',
			)
		);

		foreach ( $preview_posts as $preview_post_id ) {
			$preview_terms = wp_get_post_terms( $preview_post_id, $taxonomy, array( 'fields' => 'ids' ) );

			if ( is_wp_error( $preview_terms ) || empty( $preview_terms ) ) {
				continue;
			}

			$preview_related = get_posts(
				array(
					'post_type'      => $post_type,
					'post_status'    => 'publish',
					'posts_per_page' => 1,
					'fields'         => 'ids',
					'post__not_in'   => array( $preview_post_id ),
					'tax_query'      => array(
						array(
							'taxonomy' => $taxonomy,
							'field'    => 'term_id',
							'terms'    => $preview_terms,
						),
					),
				)
			);

			if ( ! empty( $preview_related ) ) {
				$post_id = $preview_post_id;
				break;
			}
		}
	}

	$term_ids = wp_get_post_terms( $post_id, $taxonomy, array( 'fields' => 'ids' ) );

	if ( is_wp_error( $term_ids ) || empty( $term_ids ) ) {
		return '';
	}

	$related = new WP_Query(
		array(
			'post_type'           => $post_type,
			'post_status'         => 'publish',
			'posts_per_page'      => $config[ $post_type ]['limit'],
			'post__not_in'        => array( $post_id ),
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'tax_query'           => array(
				array(
					'taxonomy' => $taxonomy,
					'field'    => 'term_id',
					'terms'    => $term_ids,
				),
			),
		)
	);

	if ( ! $related->have_posts() ) {
		return '';
	}

	$items = '';

	while ( $related->have_posts() ) {
		$related->the_post();

		if ( 'product' === $post_type ) {
			$product_card = do_blocks(
				'<!-- wp:template-part {"slug":"card-producto","theme":"polycart"} /-->'
			);

			$items .= sprintf(
				'<li class="wp-block-post related-content__item">%s</li>',
				$product_card
			);

			continue;
		} else {
            $post_card = do_blocks(
                '<!-- wp:template-part {"slug":"card-post","theme":"polycart"} /-->'
            );

            $items .= sprintf(
                '<li class="wp-block-post related-content__item">%s</li>',
                $post_card
            );

            continue;
        }

    }

	wp_reset_postdata();

	$header = do_blocks(
		sprintf(
			'<!-- wp:paragraph {"className":"is-style-badge"} --><p class="is-style-badge">%1$s</p><!-- /wp:paragraph --><!-- wp:heading --><h2 class="wp-block-heading">%2$s</h2><!-- /wp:heading -->',
			esc_html( $config[ $post_type ]['badge'] ),
			esc_html( $config[ $post_type ]['heading'] )
		)
	);

	return sprintf(
		'<section %1$s>
            %2$s
            <ul class="related-content__grid wp-block-post-template is-layout-grid">
				%3$s
            </ul>
        </section>',
		get_block_wrapper_attributes( array( 'class' => 'related-content wp-block-query' ) ),
		$header,
		$items
	);
}