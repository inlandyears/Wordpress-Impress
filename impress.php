<?php
/*
 * Plugin Name: imPress
 * Plugin URI: http://www.hanusek.com/impress
 * Description: Create amazing presentations with Wordpress and Impress.js.
 * Version: 0.1.6
 * Author: Hanusek
 * Author URI: http://www.hanusek.com
 * License: GPL2+
 */


/** Prevent loading this file directly, Set Version */

defined( 'ABSPATH' ) || exit;
define( 'IMPRESS_VER', '0.1.2' );
define( 'GOOGLE_API_KEY', 'AIzaSyAcstCTO3Ua9ro64TbVH9JdX3Piz8Kcn3A' );


/** Define plugin URLs and Paths, for fast enqueuing scripts and styles */

if ( ! defined( 'IMPRESS_URL' ) )
    define( 'IMPRESS_URL', plugin_dir_url( __FILE__ ) );
define( 'IMPRESS_JS_URL', trailingslashit( IMPRESS_URL . 'js' ) );
define( 'IMPRESS_CSS_URL', trailingslashit( IMPRESS_URL . 'css' ) );
if ( ! defined( 'IMPRESS_DIR' ) )
    define( 'IMPRESS_DIR', plugin_dir_path( __FILE__ ) );
define( 'IMPRESS_INC_DIR', trailingslashit( IMPRESS_DIR . 'inc' ) );
#define( 'IMPRESS_FIELDS_DIR', trailingslashit( IMPRESS_INC_DIR . 'fields' ) );
#define( 'IMPRESS_CLASSES_DIR', trailingslashit( IMPRESS_INC_DIR . 'classes' ) );
    //set browser prefix

$browser = strtolower($_SERVER['HTTP_USER_AGENT']);
$prefix = '-webkit-';

if ( preg_match("/ie/", $browser )) { //Trying to find word IE if exists, then it is IE
    $browser = 'ie';
    $prefix = '-ms-';
}elseif( preg_match("/safari/", $browser) ) { //Trying to find word Safari if exists, then it is Safari
    $browser = "safari";
    $prefix = '-webkit-';
} else { //For this statement we take the first word from the string which is Opera or Mozilla 
    
    $browser2 = explode("/",$browser);
    $browser = strtolower($browser2['0']);

    if($browser == 'mozilla'){
        $prefix = '-moz-';
    }elseif($browser == 'opera'){
        $prefix = '-o-';
    }
}
define( 'PREFIX',  $prefix );

$is_array = array('-o-','-ms-');
$is = true;
foreach ($is_array as $arrayValue) {
    if ($arrayValue == PREFIX) { 
        $is = false; 
    }
}  
define( 'SUPPORTED',  $is);                       

/**
 * @todo Optimize code for loading plugin files ONLY on admin side
 * @see http://www.deluxeblogtips.com/?p=345
 */


/** Includes */

require_once IMPRESS_INC_DIR . 'post-types.php';        // Register post type
require_once IMPRESS_INC_DIR . "options.php";           // Admin Plugin Options
require_once IMPRESS_INC_DIR . 'meta-box.php';
require_once IMPRESS_INC_DIR . 'shortcode.php';         // Add Shortcode
require_once IMPRESS_INC_DIR . 'template.php';          // Add Template Page
require_once IMPRESS_INC_DIR . 'custom-edit.php';       // Customize the edit.php?post_type
require_once IMPRESS_INC_DIR . 'sort-order.php';        // Add sort order post types
require_once IMPRESS_INC_DIR . 'scripts.php';           // Include for js and css


?>