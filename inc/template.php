<?php

/**
 * Calls the correct template
 */


/** Filter for calling a given template */
//add_filter( 'page_template', 'impress_page_template' );
add_filter( 'single_template', 'impress_post_template' );

/** Find correct page to call the template */
function impress_page_template( $page_template ) {

    global $IMPRESS_Options;
    $y = $IMPRESS_Options;

    $impress_post_id = $y->getImpressPostId( get_the_ID() );

    if($impress_post_id) {
                 $IMPRESS_Options->current_post_id = $impress_post_id;
                $page_template = IMPRESS_INC_DIR . 'templates/impress-template.php';
    }
    return $page_template;
}


/** Find correct post to call the template */
function impress_post_template( $page_template ) {

    global $IMPRESS_Options;

    $impress_post_id = get_the_ID();

    if($impress_post_id) {
        $IMPRESS_Options->current_post_id = $impress_post_id;
        $page_template = IMPRESS_INC_DIR . 'templates/impress-template.php';
    }
    return $page_template;
}

?>