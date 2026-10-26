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
    register_block_type( get_stylesheet_directory() . '/custom-blocks/vista-giratoria' );
}

add_action( 'acf/include_fields', 'smn_register_vista_giratoria_fields' );
function smn_register_vista_giratoria_fields() {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) {
        return;
    }

    acf_add_local_field_group(
        array(
            'key'      => 'group_smn_vista_giratoria',
            'title'    => 'Bloque: Vista giratoria',
            'fields'   => array(
                array(
                    'key'           => 'field_smn_vista_giratoria_imagenes',
                    'label'         => 'Imágenes',
                    'name'          => 'vista_giratoria_imagenes',
                    'type'          => 'gallery',
                    'return_format' => 'id',
                    'preview_size'  => 'thumbnail',
                    'library'       => 'all',
                    'mime_types'    => 'jpg,jpeg,png,webp,avif',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param'    => 'block',
                        'operator' => '==',
                        'value'    => 'smn/vista-giratoria',
                    ),
                ),
            ),
        )
    );
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

    require_once get_stylesheet_directory() . '/custom-blocks/product-image-gallery/product-image-gallery.php';
    register_block_type(
        get_stylesheet_directory() . '/custom-blocks/product-image-gallery',
        array( 'render_callback' => 'smn_render_product_image_gallery_block' )
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

    require_once get_stylesheet_directory() . '/custom-blocks/menu-products/menu-products.php';
    register_block_type(
        get_stylesheet_directory() . '/custom-blocks/menu-products',
        array( 'render_callback' => 'smn_render_menu_products_block' )
    );

}

add_action( 'init', 'smn_register_blocks' );

function smn_register_block_patterns() {
    if ( WP_Block_Patterns_Registry::get_instance()->is_registered( 'polycart/card-plastic' ) ) {
        return;
    }

    register_block_pattern(
        'polycart/card-plastic',
        array(
            'title'         => __( 'Tarjeta 100% plastico', 'polycart' ),
            'categories'    => array( 'featured' ),
            'viewportWidth' => 563,
            'inserter'      => true,
            'filePath'      => get_theme_file_path( '/patterns/card-plastic.php' ),
        )
    );
}

add_action( 'init', 'smn_register_block_patterns', 9 );
