<?php
/*
 * 
 * Require the framework class before doing anything else, so we can use the defined urls and dirs
 * Also if running on windows you may have url problems, which can be fixed by defining the framework url first
 *
 */
//define('IMPRESS_OPTIONS_URL', site_url('path the options folder'));
if(!class_exists('IMPRESS_Options')){
	require_once( dirname( __FILE__ ) . '/options/options.php' );
}

/*
 * 
 * Custom function for filtering the sections array given by theme, good for child themes to override or add to the sections.
 * Simply include this function in the child themes functions.php file.
 *
 * NOTE: the defined constansts for urls, and dir will NOT be available at this point in a child theme, so you must use
 * get_template_directory_uri() if you want to use any of the built in icons
 *
 */
function add_another_section($sections){
	
	//$sections = array();
	$sections[] = array(
				'title' => __('A Section added by hook', 'impress-opts'),
				'desc' => __('<p class="description">This is a section created by adding a filter to the sections array, great to allow child themes, to add/remove sections from the options.</p>', 'impress-opts'),
				//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
				//You dont have to though, leave it blank for default.
				'icon' => trailingslashit(get_template_directory_uri()).'options/img/glyphicons/glyphicons_062_attach.png',
				//Lets leave this as a blank section, no options just some intro text set above.
				'fields' => array()
				);
	
	return $sections;
	
}//function
//add_filter('impress-opts-sections-twenty_eleven', 'add_another_section');


/*
 * 
 * Custom function for filtering the args array given by theme, good for child themes to override or add to the args array.
 *
 */
function change_framework_args($args){
	
	//$args['dev_mode'] = false;
	
	return $args;
	
}//function
//add_filter('impress-opts-args-twenty_eleven', 'change_framework_args');


/*
 * This is the meat of creating the optons page
 *
 * Override some of the default values, uncomment the args and change the values
 * - no $args are required, but there there to be over ridden if needed.
 *
 *
 */

function setup_framework_options(){
global $allowedposttags;

$args = array();

$tabs = array();

//Set it to dev mode to view the class settings/info in the form - default is false
$args['dev_mode'] = false;

//google api key MUST BE DEFINED IF YOU WANT TO USE GOOGLE WEBFONTS
$args['google_api_key'] = 'AIzaSyAcstCTO3Ua9ro64TbVH9JdX3Piz8Kcn3A';

//Remove the default stylesheet? make sure you enqueue another one all the page will look whack!
//$args['stylesheet_override'] = true;

//Add HTML before the form
//$args['intro_text'] = __('<p>This is the HTML which can be displayed before the form, it isnt required, but more info is always better. Anything goes in terms of markup here, any HTML.</p>', 'impress-opts');

//Setup custom links in the footer for share icons
$args['share_icons']['twitter'] = array(
										'link' => 'http://twitter.com/jcnh74',
										'title' => 'Folow me on Twitter', 
										'class' => 'twitter'
										);
$args['share_icons']['googleplus'] = array(
										'link' => 'https://plus.google.com/111834439628863984679/posts',
										'title' => 'Find me on Google Plus', 
										'class' => 'googleplus'
										);
$args['share_icons']['linked_in'] = array(
										'link' => 'http://www.linkedin.com/in/johnhanusek',
										'title' => 'Find me on LinkedIn', 
										'class' => 'linkedin'
										);

//Choose to disable the import/export feature
$args['show_import_export'] = false;

//Choose a custom option name for your theme options, the default is the theme name in lowercase with spaces replaced by underscores
$args['opt_name'] = 'impress_options';

//Custom menu icon
//$args['menu_icon'] = '';

//Custom menu title for options page - default is "Options"
$args['menu_title'] = __('Settings', 'impress-opts');

//Custom Page Title for options page - default is "Options"
$args['page_title'] = __('Settings', 'impress-opts');

//Custom page slug for options page (wp-admin/themes.php?page=***) - default is "impress_theme_options"
$args['page_slug'] = 'impress_theme_options';

//Custom page capability - default is set to "manage_options"
//$args['page_cap'] = 'manage_options';

//page type - "menu" (adds a top menu section) or "submenu" (adds a submenu) - default is set to "menu"
$args['page_type'] = 'submenu';

//parent menu - default is set to "themes.php" (Appearance)
//the list of available parent menus is available here: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
$args['page_parent'] = 'edit.php?post_type=impress';

//custom page location - default 100 - must be unique or will override other items
$args['page_position'] = 14;

//Custom page icon class (used to override the page icon next to heading)
//$args['page_icon'] = 'icon-themes';

//Want to disable the sections showing as a submenu in the admin? uncomment this line
//$args['allow_sub_menu'] = false;
		
//Set ANY custom page help tabs - displayed using the new help tab API, show in order of definition	
/*	
$args['help_tabs'][] = array(
							'id' => 'impress-opts-1',
							'title' => __('Theme Information 1', 'impress-opts'),
							'content' => __('<p>This is the tab content, HTML is allowed.</p>', 'impress-opts')
							);
$args['help_tabs'][] = array(
							'id' => 'impress-opts-2',
							'title' => __('Theme Information 2', 'impress-opts'),
							'content' => __('<p>This is the tab content, HTML is allowed.</p>', 'impress-opts')
							);

//Set the Help Sidebar for the options page - no sidebar by default										
$args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', 'impress-opts');
*/


$sections = array();

$sections[] = array(
				'title' => __('Getting Started', 'impress-opts'),
				'desc' => __('<div class="description"><h4>Select A Post Type and a Page to Display.</h4>First select if you want to display your Posts from the blog, Media from your media directory or manage your slides right in Impress. Then Select a Page where Impress should run.</div>', 'impress-opts'),
				//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
				//You dont have to though, leave it blank for default.
				'icon' => IMPRESS_OPTIONS_URL.'img/glyphicons/glyphicons_062_attach.png',
				//Lets leave this as a blank section, no options just some intro text set above.
				'fields' => array(
					array(
						'id' => 'post_type_select',
						'type' => 'post_type_select',
						'title' => __('Select Content', 'nhp-opts'), 
						//'sub_desc' => __('Choose to show your Pages, Posts or other Custom Post Types', 'nhp-opts'),
						'desc' => __('Choose to show your Pages, Posts or other Custom Post Types.', 'nhp-opts'),
						//'args' => array()//uses get_post_types
						'std' => 'impress',
						),
					/*
					array(
						'id' => 'cats_select',
						'type' => 'cats_select',
						'title' => __('Select Category', 'nhp-opts'), 
						//'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('Choose a Post category if you like.', 'nhp-opts'),
						'args' => array('number' => '10')//uses get_categories
						),
						*/
					array(
						'id' => 'pages_select',
						'type' => 'pages_select',
						'title' => __('Select a Page', 'nhp-opts'), 
						//'sub_desc' => __('Choose a page to show your presentation.', 'nhp-opts'),
						'desc' => __('Choose a page to show your presentation.', 'nhp-opts'),
						'args' => array()//uses get_pages
						),
					)
				);

				
$sections[] = array(
				'icon' => IMPRESS_OPTIONS_URL.'img/glyphicons/glyphicons_107_text_resize.png',
				'title' => __('Space', 'impress-opts'),
				'desc' => __('<div class="description">Tweak these settings to get the desired effect of your presentation</div>', 'impress-opts'),
				'fields' => array(
					array(
						'id' => 'xdis',
						'type' => 'text',
						'title' => __('X-Axis Space', 'impress-opts'),
						'sub_desc' => __('Increment in X-Axis distance apart from other sides.', 'impress-opts'),
						'desc' => __('"-1000" comes from the left where "1000" comes from the right.', 'impress-opts'),
						'validate' => 'numeric',
						'std' => '1000',
						'class' => 'xdis'
						),
					array(
						'id' => 'ydis',
						'type' => 'text',
						'title' => __('Y-Axis Space', 'impress-opts'),
						'sub_desc' => __('Increment in Y-Axis distance apart from other sides.', 'impress-opts'),
						'desc' => __('"-1000" comes from above space where "1000" comes from below.', 'impress-opts'),
						'validate' => 'numeric',
						'std' => '0',
						'class' => 'ydis'
						),
					array(
						'id' => 'zdis',
						'type' => 'text',
						'title' => __('Z-Axis Space', 'impress-opts'),
						'sub_desc' => __('Increment in Z-Axis distance apart from other sides.', 'impress-opts'),
						'desc' => __('"-1000" comes towards you in space where "1000" goes forward in space.', 'impress-opts'),
						'validate' => 'numeric',
						'std' => '0',
						'class' => 'zdis'
						),
					array(
						'id' => 'xrot',
						'type' => 'text',
						'title' => __('X Rotation', 'impress-opts'),
						'sub_desc' => __('Increment in X-Axis rotation in relation to other sides.', 'impress-opts'),
						'desc' => __('"15" will rotate on the X-Axis the first slide 15 degrees, the second 30 degrees, the third 45 degrees etc...', 'impress-opts'),
						'validate' => 'numeric',
						'std' => '0',
						'class' => 'xrot'
						),
					array(
						'id' => 'yrot',
						'type' => 'text',
						'title' => __('Y Rotation', 'impress-opts'),
						'sub_desc' => __('Increment in Y-Axis rotation in relation to other sides.', 'impress-opts'),
						'desc' => __('"15" will rotate on the Y-Axis the first slide 15 degrees, the second 30 degrees, the third 45 degrees etc...', 'impress-opts'),
						'validate' => 'numeric',
						'std' => '0',
						'class' => 'yrot'
						),
					array(
						'id' => 'zrot',
						'type' => 'text',
						'title' => __('Z Rotation', 'impress-opts'),
						'sub_desc' => __('Increment in Z-Axis rotation in relation to other sides.', 'impress-opts'),
						'desc' => __('"15" will rotate on the Z-Axis the first slide 15 degrees, the second 30 degrees, the third 45 degrees etc...', 'impress-opts'),
						'validate' => 'numeric',
						'std' => '0',
						'class' => 'zrot'
						),
					)

				);
$sections[] = array(
				'icon' => IMPRESS_OPTIONS_URL.'img/glyphicons/glyphicons_023_cogwheels.png',
				'title' => __('Design', 'impress-opts'),
				'fields' => array(
					array(
						'id' => 'screen',
						'type' => 'screen',
						'title' => __('Color Gradient Option', 'impress-opts'), 
						//'sub_desc' => __('Only color validation can be done on this field type', 'impress-opts'),
						'desc' => __('<br />Choose two colors and a select a gradient style for your background.', 'impress-opts'),
						'options' => array('1' => 'Linear','2' => 'Radius'),
						'std' => array('from' => '#575757', 'to' => '#303030', 'type' => '0')
						),
					array(
						'id' => 'type_color',
						'type' => 'color',
						'title' => __('Type Color', 'impress-opts'), 
						//'sub_desc' => __('Only color validation can be done on this field type', 'impress-opts'),
						'desc' => __('Choose a color for your text.', 'impress-opts'),
						'std' => '#EDEDED'
						),
					array(
						'id' => 'color_gradient',
						'type' => 'color_gradient',
						'title' => __('Color Gradient Option', 'impress-opts'), 
						//'sub_desc' => __('Only color validation can be done on this field type', 'impress-opts'),
						'desc' => __('<br />Choose two colors and a select a gradient style for your background.', 'impress-opts'),
						'options' => array('1' => 'Linear','2' => 'Radius'),
						'std' => array('from' => '#575757', 'to' => '#303030', 'type' => '0')
						),
					array(
						'id' => 'effect',
						'type' => 'select',
						'title' => __('Select Effect', 'nhp-opts'), 
						'desc' => __('Choose a background parallax effect.', 'nhp-opts'),
						'options' => array('none' => 'None', 'bokeh' => 'Bokeh', 'plaid' => 'Plaid', 'boxes' => 'Boxes', 'hexagons' => 'Hexagons', 'stars' => 'Stars', 'star-color' => 'Stars Color'),//Must provide key => value pairs for select options
						'std' => 'none'
						),
					array(
						'id' => 'typeface',
						'type' => 'google_webfonts',//doesnt need to be called for callback fields
						'title' => __('Choose Typeface', 'impress-opts'), 
						//'sub_desc' => __('', 'impress-opts'),
						'desc' => __('<br />Select a Google Web Font for your typeface.<br /><br /><br /><br /><br /><br />', 'impress-opts'),
						)			
					)
				);

$sections[] = array(
				'icon' => IMPRESS_OPTIONS_URL.'img/glyphicons/glyphicons_093_crop.png',
				'title' => __('Header/Footer', 'impress-opts'),
				'desc' => __('<div class="description">You want to add some stuff. Go ahead and HTML away.</div>', 'impress-opts'),
				'fields' => array(
					array(
						'id' => 'header',
						'type' => 'textarea',
						'title' => __('Header', 'impress-opts'), 
						'sub_desc' => __('Custom HTML Allowed', 'impress-opts'),
						'validate' => 'html_custom',
						'std' => '',
						'allowed_html' => $allowedposttags
						),
					array(
						'id' => 'footer2',
						'type' => 'textarea',
						'title' => __('Footer', 'impress-opts'), 
						'sub_desc' => __('Custom HTML Allowed', 'impress-opts'),
						'validate' => 'html_custom',
						'std' => '<div class="footer">hanusek.com</div>',
						'allowed_html'=> $allowedposttags
						)
					)
				);

	global $IMPRESS_Options;
	$IMPRESS_Options = new IMPRESS_Options($sections, $args, $tabs);

}//function
add_action('init', 'setup_framework_options', 0);

/*
 * 
 * Custom function for the callback referenced above
 *
 */
function my_custom_field($field, $value){
	print_r($field);
	print_r($value);

}//function

/*
 * 
 * Custom function for the callback validation referenced above
 *
 */
function validate_callback_function($field, $value, $existing_value){
	
	$error = false;
	$value =  'just testing';
	/*
	do your validation
	
	if(something){
		$value = $value;
	}elseif(somthing else){
		$error = true;
		$value = $existing_value;
		$field['msg'] = 'your custom error message';
	}
	*/
	
	$return['value'] = $value;
	if($error == true){
		$return['error'] = $field;
	}
	return $return;
	
}//function
?>