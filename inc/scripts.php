<?php
/**
 * This file contains all helpers/public functions
 * that can be used both on the back-end or front-end
 */

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;


function impress_enqueue_style() {
	wp_enqueue_style( 'impress_css', IMPRESS_CSS_URL . 'style.css', IMPRESS_VER );
}
function impress_enqueue_scripts() {
	wp_enqueue_script( 'impress_js', IMPRESS_JS_URL . 'vendor/impress.js', array( 'jquery' ), IMPRESS_VER, true );
	wp_enqueue_script( 'wp_impress_js', IMPRESS_JS_URL . 'wp_impress.js', array( 'impress_js' ), IMPRESS_VER, true );
}
add_action( 'wp_head', 'impress_enqueue_scripts' );
add_action( 'wp_enqueue_scripts', 'impress_enqueue_style' );

function impress_footer() {
    echo '<script>impress().init();</script>'."\n";
}
add_action( 'wp_footer', 'impress_footer', 100 );



?>