<?php
/**
 * Title: Tarjeta 100% plastico
 * Slug: polycart/card-plastic
 * Categories: featured
 * Viewport Width: 563
 */
?>
<!-- wp:group {"className":"is-style-card-plastic","layout":{"type":"default"}} -->
<div class="wp-block-group is-style-card-plastic">
    <!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"card-plastic__background"} -->
    <figure class="wp-block-image size-full card-plastic__background"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/img/card-plastic-background.png' ) ); ?>" alt=""/></figure>
    <!-- /wp:image -->

    <!-- wp:group {"className":"card-plastic__panel","layout":{"type":"default"}} -->
    <div class="wp-block-group card-plastic__panel">
        <!-- wp:group {"className":"card-plastic__header","layout":{"type":"default"}} -->
        <div class="wp-block-group card-plastic__header">
            <!-- wp:paragraph -->
            <p>05</p>
            <!-- /wp:paragraph -->

            <!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
            <figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/icons/card-plastic-recycling.svg' ) ); ?>" alt="Reciclable"/></figure>
            <!-- /wp:image -->
        </div>
        <!-- /wp:group -->

        <!-- wp:group {"className":"card-plastic__content","layout":{"type":"default"}} -->
        <div class="wp-block-group card-plastic__content">
            <!-- wp:heading {"level":4} -->
            <h4 class="wp-block-heading">100% pl&#225;stico</h4>
            <!-- /wp:heading -->

            <!-- wp:paragraph -->
            <p>Pensados para rendir. Dise&#241;ados para durar.</p>
            <!-- /wp:paragraph -->
        </div>
        <!-- /wp:group -->
    </div>
    <!-- /wp:group -->

    <!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"card-plastic__cutout"} -->
    <figure class="wp-block-image size-full card-plastic__cutout"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/img/card-plastic-cutout.png' ) ); ?>" alt="Cesta amarilla Polycart con productos de supermercado"/></figure>
    <!-- /wp:image -->
</div>
<!-- /wp:group -->