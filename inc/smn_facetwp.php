<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'facetwp_scripts', function() {
  ?>
  <script>
    (function($) {
      $(function() {
        if ('object' != typeof FWP) return;
 
        /* Modify each facet's wrapper HTML */
        FWP.hooks.addFilter('facetwp/flyout/facet_html', function(facet_html, context) {
          var facet = $('.facetwp-facet-' + context.facet_name);
          if ('reset' === facet.attr('data-type')) {
            return facet_html.replace('<h3>{label}</h3>', '');
          }
          return facet_html.replace('<h3>{label}</h3>', '<label>{label}</label>'); // Example: replace <h3> facet label tags with <h2>
        });
      });
    })(fUtil) </script>
  <?php
}, 100 );

add_action( 'facetwp_scripts', function() {
  ?>
  <script>
    (function($) {
      $(function() {
        if ('object' != typeof FWP) return;
 
        /* Modify the flyout wrapper HTML */
        FWP.hooks.addFilter('facetwp/flyout/flyout_html', function(flyout_html) {
          return flyout_html.replace(' <div class="facetwp-flyout-close">x</div>', ' <div class="facetwp-flyout-close"><a><img src="<?php echo esc_url( get_template_directory_uri() . '/assets/icons/x.svg' ); ?>" alt="Close"></a></div>');
        });
 
      });
    })(fUtil);
  </script>
  <?php
}, 100 );

