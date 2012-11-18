<?php

/**
 * impress_shortcode
 */


/** Lets do some shortcode functions */

function impress_shortcode( $atts ){

    echo '<div class="fallback-message">'."\n";
    echo '<p>Your browser <b>doesn\'t support the features required</b> by impress.js, so you are presented with a simplified version of this presentation.</p>'."\n";
    echo '<p>For the best experience please use the latest <b>Chrome</b>, <b>Safari</b> or <b>Firefox</b> browser.</p>'."\n";
    echo '</div>'."\n";

    echo '<div id="wrap">'."\n";
    require_once IMPRESS_INC_DIR . "impress-slider.php";
    echo '</div>'."\n";

    echo '<div class="hint">'."\n";
    echo '<p>Use a spacebar or arrow keys to navigate</p>'."\n";
    echo '</div>'."\n";


}


/** Lets register it and attached the function */

add_shortcode( 'impress', 'impress_shortcode' );

?>