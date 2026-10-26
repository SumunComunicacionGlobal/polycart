<?php
/**
 * Render Product data block.
 *
 * Reads every field belonging to the ACF group with key "group_6aa1636619877"
 * and displays each pair "Label" - "Valor" in a three-column grid.
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Block content.
 * @param WP_Block $block      Block instance.
 * @return string
 */
function smn_render_product_data_block( $attributes, $content, $block ) {
    $post_id = get_the_ID();


    if ( ! $post_id ) {
        $post_id = get_queried_object_id();
    }

    if ( ! $post_id ) {
        return '';
    }

    $field_objects = get_field_objects( $post_id, false );

    if ( empty( $field_objects ) ) {
        return '';
    }
    // echo '<pre>';
    // print_r( $field_objects );
    // echo '</pre>';
    $items = array();

    foreach ( $field_objects as $field_name => $field_object ) {
        if ( empty( $field_object['parent'] ) || PRODUCT_DATA_ACF_GROUP_ID !== $field_object['parent'] ) {
            continue;
        }

        $value = get_field( $field_name, $post_id );

        if ( is_null( $value ) || ( is_array( $value ) && empty( $value ) ) || '' === $value ) {
            continue;
        }

        $suffix = ! empty( $field_object['append'] ) ? '&nbsp;' . $field_object['append'] : '';

        $label = ! empty( $field_object['label'] ) ? $field_object['label'] : $field_name;
        $formatted_value = smn_format_product_data_value( $value );

        if ( '' === $formatted_value ) {
            continue;
        }

        $items[] = sprintf(
            '<div class="product-data__item"><span class="product-data__label">%1$s</span><span class="product-data__value">%2$s</span></div>',
            esc_html( $label ),
            $formatted_value . $suffix
        );
    }

    if ( empty( $items ) ) {
        return '';
    }

    return sprintf(
        '<div %1$s><div class="product-data__grid">%2$s</div></div>',
        get_block_wrapper_attributes( array( 'class' => 'product-data' ) ),
        implode( '', $items )
    );
}

/**
 * Normalize ACF field values so they can be displayed safely in the block.
 *
 * @param mixed $value Original field value.
 * @return string
 */
function smn_format_product_data_value( $value ) {
    if ( is_null( $value ) ) {
        return '';
    }

    if ( is_bool( $value ) ) {
        return $value ? __( 'Sí', 'polycart' ) : __( 'No', 'polycart' );
    }

    if ( is_array( $value ) ) {
        $parts = array();

        foreach ( $value as $item ) {
            $formatted_item = smn_format_product_data_value( $item );

            if ( '' !== $formatted_item ) {
                $parts[] = $formatted_item;
            }
        }

        return implode( ', ', $parts );
    }

    if ( is_object( $value ) ) {
        if ( isset( $value->post_title ) ) {
            return (string) $value->post_title;
        }

        if ( isset( $value->title ) ) {
            return (string) $value->title;
        }

        if ( method_exists( $value, '__toString' ) ) {
            return (string) $value;
        }

        return '';
    }

    $output = trim( (string) $value );

    if ( '' === $output ) {
        return '';
    }

    $output = str_replace( ' kg', '&nbsp;kg', $output );

    if ( false !== stripos( $output, '<br' ) ) {
        $output = '<span class="has-small-font-size">' . $output . '</span>';
    }

    return $output;
    // return wp_strip_all_tags( $output );
}
