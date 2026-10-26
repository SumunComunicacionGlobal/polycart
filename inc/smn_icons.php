<?php
/**
 * sumun icon collection
 *
 * @package smn
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action('init', 'smn_icons_register');

function smn_icons_register(): void
{
	wp_register_icon_collection('polycart', [
		'label'       => __('Polycart', 'polycart'),
		'description' => __('Iconos para Polycart.', 'polycart')
	]);

	$icons = [
		[ __('Arrow down 2', 'polycart'), 'arrow-down-2' ],
		[ __('Arrow down', 'polycart'), 'arrow-down' ],
		[ __('Arrow left 2', 'polycart'), 'arrow-left-2' ],
		[ __('Arrow left', 'polycart'), 'arrow-left' ],
		[ __('Arrow right 2', 'polycart'), 'arrow-right-2' ],
		[ __('Arrow right', 'polycart'), 'arrow-right' ],
		[ __('Arrow up 2', 'polycart'), 'arrow-up-2' ],
		[ __('Arrow up', 'polycart'), 'arrow-up' ],
		[ __('Calendar', 'polycart'), 'calendar' ],
		[ __('Cart', 'polycart'), 'cart' ],
		[ __('Carts', 'polycart'), 'carts' ],
		[ __('Chain', 'polycart'), 'chain' ],
		[ __('Chevron down 2', 'polycart'), 'chevron-down-2' ],
		[ __('Chevron down', 'polycart'), 'chevron-down' ],
		[ __('Chevron left 2', 'polycart'), 'chevron-left-2' ],
		[ __('Chevron left', 'polycart'), 'chevron-left' ],
		[ __('Chevron right 2', 'polycart'), 'chevron-right-2' ],
		[ __('Chevron right', 'polycart'), 'chevron-right' ],
		[ __('Chevron up 2', 'polycart'), 'chevron-up-2' ],
		[ __('Chevron up', 'polycart'), 'chevron-up' ],
		[ __('Chip', 'polycart'), 'chip' ],
		[ __('Clock', 'polycart'), 'clock' ],
		[ __('Container small', 'polycart'), 'container-small' ],
		[ __('Container', 'polycart'), 'container' ],
		[ __('Customer', 'polycart'), 'customer' ],
		[ __('Dot', 'polycart'), 'dot' ],
		[ __('Dot horizontal', 'polycart'), 'dot-horizontal' ],
		[ __('Leaf', 'polycart'), 'leaf' ],
		[ __('Menu 2', 'polycart'), 'menu-2' ],
		[ __('Menu', 'polycart'), 'menu' ],
		[ __('Minus', 'polycart'), 'minus' ],
		[ __('More vertical', 'polycart'), 'more-vertical' ],
		[ __('Mute', 'polycart'), 'mute' ],
		[ __('Oxido', 'polycart'), 'oxido' ],
		[ __('Plus', 'polycart'), 'plus' ],
		[ __('Reciclable', 'polycart'), 'reciclable' ],
		[ __('Search', 'polycart'), 'search' ],
		[ __('Shield', 'polycart'), 'shield' ],
		[ __('Tool', 'polycart'), 'tool' ],
		[ __('Trailer', 'polycart'), 'trailer' ],
		[ __('Truck', 'polycart'), 'truck' ],
		[ __('Baskets', 'polycart'), 'stacked-baskets' ],
		[ __('Volume', 'polycart'), 'volume' ],
		[ __('Weight', 'polycart'), 'weight' ],
		[ __('Whell', 'polycart'), 'whell' ],
		[ __('Area', 'polycart'), 'area' ],
		[ __('X', 'polycart'), 'x' ],
	];

	foreach ( $icons as $icon ) {
		wp_register_icon( 'polycart/' . $icon[1], [
			'label'   => $icon[0],
			'file_path' => get_template_directory() . '/assets/icons/' . $icon[1] . '.svg',
		] );
	}

}