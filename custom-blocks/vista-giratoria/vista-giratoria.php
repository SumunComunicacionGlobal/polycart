<?php
/**
 * Vista giratoria block
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$images = get_field( 'vista_giratoria_imagenes' );

if ( empty( $images ) ) {
	if ( $is_preview ) {
		echo '<p>' . esc_html__( 'Añade imágenes a la galería de la vista giratoria.', 'polycart' ) . '</p>';
	}
	return;
}

$classes = 'vista-giratoria';
if ( ! empty( $block['className'] ) ) {
	$classes .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
	$classes .= ' align' . $block['align'];
}
$anchor = ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : '';

echo '<div class="' . esc_attr( $classes ) . '"' . $anchor . '>';
foreach ( array_values( $images ) as $index => $image_id ) {
	echo wp_get_attachment_image(
		$image_id,
		'large',
		false,
		array(
			'class'   => 'vista-giratoria__frame' . ( 0 === $index ? ' is-active' : '' ),
			'loading' => 'eager',
			'alt'     => 0 === $index ? get_post_meta( $image_id, '_wp_attachment_image_alt', true ) : '',
		)
	);
}
echo '</div>';
