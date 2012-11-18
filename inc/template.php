<?php

/**
 * Calls the correct template
 */


/** Filter for calling a given template */
add_filter( 'page_template', 'impress_template' );

/** Find correct page to call the template */
function impress_template( $page_template ) {

    global $IMPRESS_Options;
    $y = $IMPRESS_Options;
    $page = $y->get('pages_select');

    if($page) {
        if ( is_page($page) ) {
            $page_template = IMPRESS_INC_DIR . 'templates/impress-template.php';
        }
        return $page_template;
    }
}

?>