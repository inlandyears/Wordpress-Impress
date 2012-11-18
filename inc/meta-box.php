<?php
/**
 * Registering meta boxes
 *
 * All the definitions of meta boxes are listed below with comments.
 * Please read them CAREFULLY.
 *
 * You also should read the changelog to know what has been changed before updating.
 *
 * For more information, please visit:
 * @link http://www.deluxeblogtips.com/meta-box/
 */

/********************* META BOX DEFINITIONS ***********************/

/**
 * Prefix of meta keys (optional)
 * Use underscore (_) at the beginning to make keys hidden
 * Alt.: You also can make prefix empty to disable it
 */
// Better has an underscore as last sign

require_once IMPRESS_INC_DIR . 'meta-box/meta-box.php'; // Meta Boxes on Post Types

$prefix = 'IMPRESS_';

global $meta_boxes;


$meta_boxes = array();

// 1st meta box
$meta_boxes[] = array(
	// Meta box id, UNIQUE per meta box. Optional since 4.1.5
	'id' => 'positioning',

	// Meta box title - Will appear at the drag and drop handle bar. Required.
	'title' => 'Positioning',

	// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
	'pages' => array( 'impress'),

	// Where the meta box appear: normal (default), advanced, side. Optional.
	'context' => 'normal',

	// Order of meta box: high (default), low. Optional.
	'priority' => 'high',

	// List of meta fields
	'fields' => array(

		// TEXT
		array(
			// Field name - Will be used as label
			'name'  => 'Custom Class',
			// Field ID, i.e. the meta key
			'id'    => "{$prefix}class",
			// Field description (optional)
			'desc'  => '"slide" will give it a background.',
			'type'  => 'text',
			// Default value (optional)
			'std'   => ''
		),
		// CHECKBOX
		array(
			'name' => 'Overide Spatial Settings',
			'id'   => "{$prefix}override",
			'type' => 'checkbox',
			// Value can be 0 or 1
			'std'  => 0,
		),
		// X Position
		array(
			'name' => 'X Position',
			'id'   => "{$prefix}xpos",
			'type' => 'number',
			'std'  => 0,
			'min'  => -200000,
			'step' => 1000,
		),
		// Y Position
		array(
			'name' => 'Y Position',
			'id'   => "{$prefix}ypos",
			'type' => 'number',
			'std'  => 0,
			'min'  => -200000,
			'step' => 1000,
		),
		// Z Position
		array(
			'name' => 'Z Position',
			'id'   => "{$prefix}zpos",
			'type' => 'number',
			'std'  => 0,
			'min'  => -200000,
			'step' => 1000,
		),
		// X Rotate
		array(
			'name' => 'X Rotate',
			'id'   => "{$prefix}xrotate",
			'type' => 'number',
			'std'  => 0,
			'min'  => -359,
			'step' => 1,
		),
		// Y Rotate
		array(
			'name' => 'Y Rotate',
			'id'   => "{$prefix}yrotate",
			'type' => 'number',
			'std'  => 0,
			'min'  => -359,
			'step' => 1,
		),
		// Z Rotate
		array(
			'name' => 'Z Rotate',
			'id'   => "{$prefix}zrotate",
			'type' => 'number',
			'std'  => 0,
			'min'  => -359,
			'step' => 1,
		),
		// Scale
		array(
			'name' => 'Scale',
			'id'   => "{$prefix}scale",
			'type' => 'number',
			'std'  => 1,
			'min'  => 0,
			'step' => 1,
		),
		// CHECKBOX
		array(
			'name' => 'Show the Title',
			'id'   => "{$prefix}title",
			'type' => 'checkbox',
			// Value can be 0 or 1
			'std'  => 0,
		),
	),
);

// 2nd meta box
$meta_boxes[] = array(
	'title' => 'Advanced Fields',

	'fields' => array(
		// NUMBER
		array(
			'name' => 'Number',
			'id'   => "{$prefix}number2",
			'type' => 'number',

			'min'  => 0,
			'step' => 5,
		)
	)
);

/********************* META BOX REGISTERING ***********************/

/**
 * Register meta boxes
 *
 * @return void
 */
function IMPRESS_register_meta_boxes()
{
	// Make sure there's no errors when the plugin is deactivated or during upgrade
	if ( !class_exists( 'RW_Meta_Box' ) )
		return;

	global $meta_boxes;
	foreach ( $meta_boxes as $meta_box )
	{
		new RW_Meta_Box( $meta_box );
	}
}
// Hook to 'admin_init' to make sure the meta box class is loaded before
// (in case using the meta box class in another plugin)
// This is also helpful for some conditionals like checking page template, categories, etc.
add_action( 'admin_init', 'IMPRESS_register_meta_boxes' );