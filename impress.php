<?php
/*
Plugin Name: imPress
Plugin URI: http://www.hanusek.com/impress
Description: Create amazing presentations with Wordpress and Impress.js.
Version: 1.01
Author: Hanusek
Author URI: http://www.hanusek.com
License: GPL2+
*/

/*******************
       __.-._      |
       '-._"7'     |
        /'.-c      |
        |  /|      |
  Yoda _)_/4l      |
                   |
*******************/


// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

// Script version, used to add version for scripts and styles
define( 'IMPRESS_VER', '0.1.0' );

// Define plugin URLs, for fast enqueuing scripts and styles
if ( ! defined( 'IMPRESS_URL' ) )
	define( 'IMPRESS_URL', plugin_dir_url( __FILE__ ) );
define( 'IMPRESS_JS_URL', trailingslashit( IMPRESS_URL . 'js' ) );
define( 'IMPRESS_CSS_URL', trailingslashit( IMPRESS_URL . 'css' ) );

// Plugin paths, for including files
if ( ! defined( 'IMPRESS_DIR' ) )
	define( 'IMPRESS_DIR', plugin_dir_path( __FILE__ ) );
define( 'IMPRESS_INC_DIR', trailingslashit( IMPRESS_DIR . 'inc' ) );
#define( 'IMPRESS_FIELDS_DIR', trailingslashit( IMPRESS_INC_DIR . 'fields' ) );
#define( 'IMPRESS_CLASSES_DIR', trailingslashit( IMPRESS_INC_DIR . 'classes' ) );                             

// Optimize code for loading plugin files ONLY on admin side
// @see http://www.deluxeblogtips.com/?p=345


// Helper function to retrieve meta value
require_once IMPRESS_INC_DIR . 'scripts.php';
require_once IMPRESS_INC_DIR . 'post-types.php';

//Add Options
// Admin Plugin Options
require_once IMPRESS_INC_DIR . "options.php";


// Meta Boxes
require_once IMPRESS_INC_DIR . 'meta-box/meta-box.php';
require_once IMPRESS_INC_DIR . 'custom-meta-box.php';



add_filter( 'page_template', 'impress_template' );
add_shortcode( 'impress', 'impress_shortcode' );

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


?>