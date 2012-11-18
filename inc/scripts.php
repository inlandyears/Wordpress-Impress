<?php
/**
 * This file contains all helpers/public functions
 * that can be used both on the back-end or front-end
 */


/** Prevent loading this file directly */

defined( 'ABSPATH' ) || exit;


/**
 * Lets now add the admin scripts we need for Front-End
 * Enqueue will do it
 */

/** Enqueue styles */
function impress_enqueue_style() {
	wp_enqueue_style( 'impress_css', IMPRESS_CSS_URL . 'style.css', IMPRESS_VER );
}

/** Enqueue scripts */
function impress_enqueue_scripts() {
	wp_enqueue_script( 'impress_js', IMPRESS_JS_URL . 'vendor/impress.js', array( 'jquery' ), IMPRESS_VER, true );
	wp_enqueue_script( 'wp_impress_js', IMPRESS_JS_URL . 'wp_impress.js', array( 'impress_js' ), IMPRESS_VER, true );
}

/** Print footer scripts */
function impress_footer() {
    echo '<script>impress().init();</script>'."\n";
}

add_action( 'wp_head', 'impress_enqueue_scripts' );				// Add scripts to header
add_action( 'wp_enqueue_scripts', 'impress_enqueue_style' );	// Add styles to header
add_action( 'wp_footer', 'impress_footer', 100 );				// Add footer scripts


/**
 * Lets now add the admin scripts we need for Back-End
 * Enqueue will do it
 */

function impress_admin_enqueue_scripts() {
  wp_enqueue_script( 'jquery-ui-sortable' );
  wp_enqueue_script( 'impress-admin-scripts', IMPRESS_JS_URL . 'sort-order.js' );
}

add_action( 'admin_enqueue_scripts', 'impress_admin_enqueue_scripts' );


?>