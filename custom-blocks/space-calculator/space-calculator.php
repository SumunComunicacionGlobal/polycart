<?php
/**
* Space calculator block
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'smn_space_calculator_icon' ) ) {
    function smn_space_calculator_icon( $icon_name, $width = 40 ) {
        $path = get_theme_file_path( '/assets/icons/' . $icon_name . '.svg' );

        if ( ! file_exists( $path ) ) {
            return '';
        }

        return sprintf(
            '<div class="space-calculator__icon" style="--smn-icon-width:%dpx;">%s</div>',
            (int) $width,
            file_get_contents( $path )
        );
    }
}

$post_id     = get_queried_object_id();
$campo_calculo = get_field( 'campo_calculo' ) ?: 'largo_mm';
$base_length = (float) get_field( $campo_calculo, $post_id );
$calculation_field = get_field_object( $campo_calculo, $post_id );
$calculation_label = ! empty( $calculation_field['label'] ) ? $calculation_field['label'] : __( 'Espacio', 'polycart' );
$icon_name   = sanitize_key( (string) get_field( 'icon_name' ) ) ?: 'carts';
$block_id    = 'space-calculator-' . uniqid();

$default_units = 20;

$unit_fields = array(
    'unidades_contenedor_20'    => array( 'icon' => 'container-small', 'width' => 40 ),
    'unidades_contenedor_40_hc' => array( 'icon' => 'container',       'width' => 62 ),
    'unidades_trailer'          => array( 'icon' => 'trailer',         'width' => 62 ),
    'unidades_camion_9'         => array( 'icon' => 'trailer',           'width' => 62 ),
    'unidades_camion_13'        => array( 'icon' => 'trailer',           'width' => 62 ),
);

if ( ! have_rows( 'variaciones_calculadora' ) ) {
    return;
}

$tabs   = array();
$panels = array();
$index  = 0;

while ( have_rows( 'variaciones_calculadora' ) ) :
    the_row();

    $nombre    = get_sub_field( 'nombre_variacion' );
    $encaje    = get_sub_field( 'distancia_encaje' );
    $tab_label = $nombre ? $nombre : sprintf( __( 'Variación %d', 'polycart' ), $index + 1 );

    $calculator = '';

    // Only build the calculator when the variation defines a nesting distance.
    if ( '' !== $encaje && null !== $encaje && false !== $encaje ) {
        $calculator = sprintf(
            '<div class="wp-block-column">
                <div class="wp-block-group is-style-card-line has-global-padding is-layout-constrained">
                    <div class="wp-block-group is-content-justification-space-between is-nowrap is-layout-flex">
                        <div class="space-calculator__intro">
                            <p class="has-medium-font-size"><strong>%1$s</strong></p>
                            <p>%2$s</p>
                        </div>
                        %3$s
                    </div>
                    <div class="space-calculator__form" data-base="%4$s" data-step="%5$s">
                        <label class="space-calculator__field">
                            <span class="space-calculator__field-label">%6$s</span>
                            <input type="number" class="space-calculator__input" min="1" step="1" value="%9$d" inputmode="numeric">
                        </label>
                        <label class="space-calculator__result">
                            <span class="space-calculator__result-label">%7$s</span>
                            <input type="text" class="space-calculator__result-value" value="%8$s" readonly disabled>
                        </label>
                    </div>
                </div>
            </div>',
            esc_html__( 'Calcula tu espacio', 'polycart' ),
            esc_html__( 'Indica el número de unidades para conocer el espacio total.', 'polycart' ),
            smn_space_calculator_icon( $icon_name, 80 ),
            esc_attr( $base_length ),
            esc_attr( (float) $encaje ),
            esc_html__( 'Unidades', 'polycart' ),
            esc_html( sprintf( __( '%s total:', 'polycart' ), $calculation_label ) ),
            esc_attr( number_format_i18n( $base_length + ( $default_units - 1 ) * (float) $encaje ) . ' mm' ),
            $default_units
        );
    }

    $unit_rows = '';

    foreach ( $unit_fields as $field_name => $config ) {
        $value = get_sub_field( $field_name );

        if ( '' === $value || null === $value || false === $value ) {
            continue;
        }

        $field_object = get_sub_field_object( $field_name );
        $label        = ! empty( $field_object['label'] ) ? $field_object['label'] : $field_name;
        $suffix       = ! empty( $field_object['append'] ) ? ' ' . $field_object['append'] : '';

        $unit_rows .= sprintf(
            '<div class="wp-block-group is-content-justification-space-between is-nowrap is-layout-flex space-calculator__unit">
                <div class="space-calculator__unit-text">
                    <div class="space-calculator__unit-label"><strong>%1$s</strong></div>
                    <div class="space-calculator__unit-value%4$s">%2$s</div>
                </div>
                %3$s
            </div>',
            esc_html( $label ),
            trim( $value . $suffix ),
            smn_space_calculator_icon( $config['icon'], $config['width'] ),
            false !== stripos( $value . $suffix, '<br' ) ? ' has-small-font-size' : ''
        );
    }

    $units_column = '';

    if ( '' !== $unit_rows ) {
        $units_column = sprintf(
            '<div class="wp-block-column">
            <div class="wp-block-group has-border-color has-neutral-30-border-color has-neutral-white-background-color has-background space-calculator__units"><p class="has-medium-font-size"><strong>%s</strong></p>%s</div>
            </div>',
            esc_html__( 'Transporte', 'polycart' ),
            $unit_rows
        );
    }

    if ( '' === $calculator && '' === $units_column ) {
        continue;
    }

    $tabs[] = sprintf(
        '<button type="button" class="space-calculator__tab%1$s" role="tab" tabid="%2$d" id="%3$s-tab-%2$d" aria-controls="%3$s-panel-%2$d" aria-selected="%4$s" tabindex="%5$d">%6$s</button>',
        empty( $panels ) ? ' is-active' : '',
        $index,
        esc_attr( $block_id ),
        empty( $panels ) ? 'true' : 'false',
        empty( $panels ) ? 0 : -1,
        esc_html( $tab_label )
    );

    $panels[] = sprintf(
        '<div class="space-calculator__panel%1$s" id="%2$s-panel-%3$d" tabid="%3$d" role="tabpanel" aria-labelledby="%2$s-tab-%3$d"%4$s>
            <div class="wp-block-columns">%5$s%6$s</div>
        </div>',
        1 === count( $tabs ) ? ' is-active' : '',
        esc_attr( $block_id ),
        $index,
        1 === count( $tabs ) ? '' : ' hidden',
        $calculator,
        $units_column
    );

    $index++;

endwhile;

if ( empty( $panels ) ) {
    return;
}

if ( 1 === count( $panels ) ) {
    $processor = new WP_HTML_Tag_Processor( $panels[0] );
    if ( $processor->next_tag() ) {
        $processor->remove_attribute( 'role' );
        $processor->remove_attribute( 'aria-labelledby' );
    }
    $panels[0] = $processor->get_updated_html();
}
?>

<div class="wp-block-group space-calculator" id="<?php echo esc_attr( $block_id ); ?>" style="margin-top:var(--wp--preset--spacing--40);margin-bottom:var(--wp--preset--spacing--40)">
    <?php if ( count( $tabs ) > 1 ) : ?>
    <div class="space-calculator__tablist" role="tablist" aria-label="<?php esc_attr_e( 'Variaciones', 'polycart' ); ?>">
        <?php echo implode( '', $tabs ); ?>
    </div>
    <?php endif; ?>
    <?php echo implode( '', $panels ); ?>
</div>

<script>
(function () {
    var root = document.getElementById('<?php echo esc_js( $block_id ); ?>');

    if (!root || root.dataset.smnInit) {
        return;
    }

    root.dataset.smnInit = '1';

    var tabs = Array.prototype.slice.call(root.querySelectorAll('.space-calculator__tab'));
    var panels = Array.prototype.slice.call(root.querySelectorAll('.space-calculator__panel'));

    function activate(id) {
        tabs.forEach(function (tab) {
            var selected = tab.getAttribute('tabid') === id;
            tab.setAttribute('aria-selected', selected ? 'true' : 'false');
            tab.tabIndex = selected ? 0 : -1;
            tab.classList.toggle('is-active', selected);
        });

        panels.forEach(function (panel) {
            var selected = panel.getAttribute('tabid') === id;

            panel.classList.toggle('is-active', selected);

            if (!selected) {
                panel.hidden = true;
                return;
            }

            if (panel.hidden) {
                panel.classList.add('is-entering');
                panel.hidden = false;
                // Force a reflow so the browser animates from the transparent state.
                void panel.offsetWidth;
                panel.classList.remove('is-entering');
            }
        });

    }

    tabs.forEach(function (tab, i) {
        tab.addEventListener('click', function () {
            activate(tab.getAttribute('tabid'));
        });

        tab.addEventListener('keydown', function (event) {
            var next = null;

            if (event.key === 'ArrowRight') {
                next = tabs[i + 1] || tabs[0];
            } else if (event.key === 'ArrowLeft') {
                next = tabs[i - 1] || tabs[tabs.length - 1];
            }

            if (next) {
                event.preventDefault();
                next.focus();
                activate(next.getAttribute('tabid'));
            }
        });
    });

    var forms = Array.prototype.slice.call(root.querySelectorAll('.space-calculator__form'));
    var formatter = new Intl.NumberFormat(document.documentElement.lang || 'es-ES');

    // Every variation shares the same unit count, so panels stay in sync when switching tabs.
    function render(units) {
        forms.forEach(function (form) {
            var base = parseFloat(form.dataset.base) || 0;
            var step = parseFloat(form.dataset.step) || 0;

            form.querySelector('.space-calculator__input').value = units;
            form.querySelector('.space-calculator__result-value').value = formatter.format(base + (units - 1) * step) + ' mm';
        });
    }

    forms.forEach(function (form) {
        var input = form.querySelector('.space-calculator__input');

        input.addEventListener('input', function (event) {
            if (event.target.value === '') {
                return;
            }

            var units = parseInt(event.target.value, 10);

            if (isNaN(units) || units < 1) {
                units = 1;
            }

            render(units);
        });

        input.addEventListener('blur', function () {
            if (input.value === '') {
                render(1);
            }
        });
    });

    if (forms.length) {
        render(parseInt(forms[0].querySelector('.space-calculator__input').value, 10) || <?php echo (int) $default_units; ?>);
    }
}());
</script>
