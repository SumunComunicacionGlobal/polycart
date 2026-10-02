<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Agrega un filtro para el bloque de consulta de WordPress
// que muestra los posts relacionados en la página de un post y los filtra por categorías
add_filter('render_block_data', function ($parsed_block) {
    if (
        is_single() &&
        isset($parsed_block['blockName']) &&
        $parsed_block['blockName'] === 'core/query' &&
        isset($parsed_block['attrs']['className']) &&
        strpos($parsed_block['attrs']['className'], 'is-style-is-related-posts') !== false
    ) {
        $category_ids = wp_get_post_categories(get_the_ID());

        if (!empty($category_ids)) {
            $parsed_block['attrs']['query']['categoryIds'] = $category_ids;
            $parsed_block['attrs']['query']['exclude'] = [get_the_ID()];
            $parsed_block['attrs']['query']['sticky'] = '';
            $parsed_block['attrs']['query']['perPage'] = 6;
        }
    }

    return $parsed_block;
});

add_action( 'pre_get_posts', 'smn_pre_get_posts' );
function smn_pre_get_posts($query) {
    if (!$query->is_main_query() || is_admin() ) return;

    if (is_search()) {
        $query->set('posts_per_page', 30);
    } elseif( is_tax( 'portfolio_category') ) {
        $query->set('posts_per_page', 24);
    }
}

function cmplz_show_banner_on_click() {
	?>
	<script>
        function addEvent(event, selector, callback, context) {
            document.addEventListener(event, e => {
                if ( e.target.closest(selector) ) {
                    callback(e);
                }
            });
        }
        addEvent('click', 'a.cmplz-show-banner, li.cmplz-show-banner > a', function(){
            event.preventDefault();
            document.querySelectorAll('.cmplz-manage-consent').forEach(obj => {
                obj.click();
            });
        });
	</script>
	<?php
}
add_action( 'wp_footer', 'cmplz_show_banner_on_click' );

// Agrega un icono SVG (dot.svg) antes del texto en bloques paragraph y heading
// que tengan la clase "is-style-badge"
add_filter('render_block', function ($block_content, $block) {
    if (
        in_array($block['blockName'], ['core/paragraph', 'core/heading'], true) &&
        isset($block['attrs']['className']) &&
        strpos($block['attrs']['className'], 'is-style-badge') !== false
    ) {
        $svg_path = get_theme_file_path('/assets/icons/dot.svg');

        if (file_exists($svg_path)) {
            $svg_content = file_get_contents($svg_path);
            $icon_markup = '<span class="badge-icon">' . $svg_content . '</span>';

            // Inserta el icono justo después de la etiqueta de apertura del tag (p o h1-h6)
            $block_content = preg_replace(
                '/(<(?:p|h[1-6])[^>]*>)/',
                '$1' . $icon_markup,
                $block_content,
                1
            );
        }
    }

    if (
        isset($block['blockName']) &&
        $block['blockName'] === 'core/post-terms'
    ) {
        $block_content = preg_replace_callback(
            '/<a\b([^>]*)>/i',
            function ($matches) {
                $attrs = $matches[1];

                $svg_path = get_theme_file_path('/assets/icons/dot.svg');

                if (file_exists($svg_path)) {
                    $svg_content = file_get_contents($svg_path);
                    $icon_markup = '<span class="badge-icon">' . $svg_content . '</span>';
                }


                if (preg_match('/\sclass=(["\'])(.*?)\1/i', $attrs, $class_matches)) {
                    $classes = preg_split('/\s+/', trim($class_matches[2]));

                    if (!in_array('is-style-badge', $classes, true)) {
                        $classes[] = 'is-style-badge';
                        $new_class = ' class="' . esc_attr(implode(' ', $classes)) . '"';
                        $attrs = preg_replace('/\sclass=(["\'])(.*?)\1/i', $new_class, $attrs, 1);
                    }

                    return '<a' . $attrs . '>' . $icon_markup;
                }

                return '<a' . $attrs . ' class="is-style-badge">' . $icon_markup;
            },
            $block_content
        );
    }

    if (
        isset($block['blockName']) &&
        $block['blockName'] === 'core/button'
    ) {
        $svg_path = get_theme_file_path('/assets/icons/arrow-down.svg');

        if ( file_exists( $svg_path ) ) {
            $svg_content = file_get_contents( $svg_path );
            $icon_markup = '<span class="button-icon button-arrow-icon">' . $svg_content . '</span>';

            $block_content = preg_replace_callback(
                '/<a\b((?:[^>"\']|"[^"]*"|\'[^\']*\')*)>(.*?)<\/a>/is',
                function ( $matches ) use ( $icon_markup ) {
                    $attrs = $matches[1];
                    $inner = $matches[2];

                    if ( preg_match('/\bhref\s*=\s*(?:"|\')\s*(#[^"\']+)\s*(?:"|\')/i', $attrs, $href_match ) ) {
                        if ( false === strpos( $inner, 'button-arrow-icon' ) ) {
                            return '<a' . $attrs . '>' . $inner . $icon_markup . '</a>';
                        }
                    }

                    return $matches[0];
                },
                $block_content
            );
        }
    }

    if ( is_singular( 'product' ) ) {
        if (
            isset($block['blockName']) &&
            $block['blockName'] === 'core/post-title'
        ) {
            // Replace <h6> with paragraph
            $block_content = preg_replace(
                array(
                    '/<h6\b([^>]*)>/i',
                    '/<\/h6>/i'
                ),
                array(
                    '<p$1><strong>',
                    '</strong></p>'
                ),
                $block_content
            );
        }

    }

    return $block_content;
}, 10, 2);

// Sustituye el contenido del bloque nativo core/breadcrumbs por la miga de pan de Rank Math
add_filter('render_block', function ($block_content, $block) {
    if (
        empty($block['blockName']) ||
        'core/breadcrumbs' !== $block['blockName'] ||
        ! function_exists('rank_math_the_breadcrumbs')
    ) {
        return $block_content;
    }

    if (!preg_match('/^(<[^>]+>)(.*)(<\/[^>]+>)\s*$/s', trim($block_content), $matches)) {
        return $block_content;
    }

    ob_start();
    rank_math_the_breadcrumbs();
    $rank_math_breadcrumbs = ob_get_clean();

    if ('' === trim($rank_math_breadcrumbs)) {
        return $block_content;
    }

    return $matches[1] . $rank_math_breadcrumbs . $matches[3];
}, 10, 2);

