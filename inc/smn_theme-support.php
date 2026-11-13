<?php
/**
 * sumun functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package smn
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Enable ACF datastore
add_filter( 'acf/settings/enable_datastore', '__return_true' );


/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function smn_hybrid_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		*/
	load_theme_textdomain( 'polycart', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// Default thumbnail size
	add_image_size( 'img-header', 640, 480, true );
	add_image_size( 'img-card', 380, 192, true );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'primary-menu' => esc_html__( 'Primary', 'polycart' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);

	// Add support for Appearance Tools.
	add_theme_support( 'appearance-tools' );

	// Custom excerpt length
	function custom_excerpt_length( $length ) {
		return 55;
	}
	add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );
}

add_action( 'after_setup_theme', 'smn_hybrid_setup' );

function smn_post_excerpt_length( $args, $block_name ) {
	if ( 'core/post-excerpt' !== $block_name || ! is_callable( $args['render_callback'] ?? null ) ) {
		return $args;
	}

	$render_callback = $args['render_callback'];
	$args['render_callback'] = static function ( $attributes, $content, $block ) use ( $render_callback ) {
		$post_id = $block->context['postId'] ?? 0;
		$attributes['excerptLength'] = $post_id && has_excerpt( $post_id ) ? null : 55;

		return $render_callback( $attributes, $content, $block );
	};

	return $args;
}
add_filter( 'register_block_type_args', 'smn_post_excerpt_length', 10, 2 );

// Soporte para shortcodes en block template parts
function render_shortcodes_in_blocks( $block_content ) {
	return do_shortcode( $block_content );
}
add_filter( 'render_block', 'render_shortcodes_in_blocks', 10, 2 );

function smn_tab_label_to_heading( $block_content, $block ) {
	if ( 'smn/tab' !== ( $block['blockName'] ?? '' ) ) {
		return $block_content;
	}

	return preg_replace_callback(
		'~<label\b([^>]*\sclass="[^"]*\bwp-block-smn-tab__label\b[^"]*"[^>]*)>(.*?)</label>~is',
		static function ( $matches ) {
			return '<h3' . $matches[1] . '>' . $matches[2] . '</h3>';
		},
		$block_content
	);
}
add_filter( 'render_block', 'smn_tab_label_to_heading', 20, 2 );