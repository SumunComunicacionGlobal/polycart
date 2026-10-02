<?php
/**
 * Register ACF Blocks
 *
 * @link https://www.advancedcustomfields.com/resources/acf_register_block_type/
 *
 * @return void
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Register ACF blocks
 */
add_action( 'init', 'register_acf_blocks' );
function register_acf_blocks() {
    register_block_type( get_stylesheet_directory() . '/custom-blocks/product-features' );
    register_block_type( get_stylesheet_directory() . '/custom-blocks/space-calculator' );
}

/**
 * Register native custom blocks script
 */

function smn_register_blocks() {
    
    // Partent title Block
    require_once get_stylesheet_directory() . '/custom-blocks/parent-title/parent-title.php';
    register_block_type( get_stylesheet_directory() . '/custom-blocks/parent-title', [ 
        'render_callback' => 'smn_render_parent_title_block'] );
    
    // Carousel Block
    register_block_type( get_stylesheet_directory() . '/custom-blocks/carousel' );
    register_block_type( get_stylesheet_directory() . '/custom-blocks/slide');

    // Tabs Block
    register_block_type( get_stylesheet_directory() . '/custom-blocks/tabs');
    register_block_type( get_stylesheet_directory() . '/custom-blocks/tab');

    require_once get_stylesheet_directory() . '/custom-blocks/product-card-meta/product-card-meta.php';
    register_block_type(
        get_stylesheet_directory() . '/custom-blocks/product-card-meta',
        array( 'render_callback' => 'smn_render_product_card_meta_block' )
    );

    // Color Spot Block
    require_once get_stylesheet_directory() . '/custom-blocks/color-spot/color-spot.php';
    register_block_type(
        get_stylesheet_directory() . '/custom-blocks/color-spot',
        array( 'render_callback' => 'smn_render_color_spot_block' )
    );

    // Product Colors Block
    require_once get_stylesheet_directory() . '/custom-blocks/product-colors/product-colors.php';
    register_block_type(
        get_stylesheet_directory() . '/custom-blocks/product-colors',
        array( 'render_callback' => 'smn_render_product_colors_block' )
    );

    // Product Colors by Category Block
    require_once get_stylesheet_directory() . '/custom-blocks/product-colors-categorized/product-colors-categorized.php';
    register_block_type(
        get_stylesheet_directory() . '/custom-blocks/product-colors-categorized',
        array( 'render_callback' => 'smn_render_product_colors_categorized_block' )
    );

    // Product Data Block
    require_once get_stylesheet_directory() . '/custom-blocks/product-data/product-data.php';
    register_block_type(
        get_stylesheet_directory() . '/custom-blocks/product-data',
        array( 'render_callback' => 'smn_render_product_data_block' )
    );

    require_once get_stylesheet_directory() . '/custom-blocks/related-content/related-content.php';
    register_block_type(
        get_stylesheet_directory() . '/custom-blocks/related-content',
        array( 'render_callback' => 'smn_render_related_content_block' )
    );

}

add_action( 'init', 'smn_register_blocks' );
