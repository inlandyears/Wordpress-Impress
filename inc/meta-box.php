<?php
function Print_impress_fileds($cnt, $p = null) {
	if ($p === null){
	    $a = $b = $c = '';
	}else{
	    $a = $p['n'];
	    $b = $p['d'];
	    $c = $p['p'];
	}
	return sprintf(
	   '<li  style="border: 1px solid #000;">
			<label>Nr :</label>
			<input type="text" name="impress_data[%s][n]" size="10" value="%s"/>

			<label>Description :</label>
			<input type="text" name="impress_data[%s][d]" size="50" value="%s"/>

			<label>Price :</label>
			<input type="text" name="impress_data[%s][p]" size="20" value="%s"/>
			<span class="remove">Remove</span>
		</li>',
		$cnt, $a,
		$cnt, $b,
		$cnt, $c
	);
}


//add custom field - price
//add_action("add_meta_boxes", "object_init");

function object_init(){
  add_meta_box("impress_meta_id", "Slides","impress_meta", "impress", "normal", "low");

}

function impress_meta(){
 global $post;

  $data = get_post_meta($post->ID,"impress_data",true);
  echo '<div>';
  echo '<ul id="impress_items">';
  $c = 0;
    if (count($data) > 0){
        foreach((array)$data as $p ){
            if (isset($p['p']) || isset($p['d'])|| isset($p['n'])){
                echo Print_impress_fileds($c,$p);
                $c = $c +1;
            }
        }

    }
    echo '</ul>';

    ?>
        <span id="here"></span>
        <span class="add"><?php echo __('Add Impress Data'); ?></span>
        <script>
            var $ =jQuery.noConflict();
                $(document).ready(function() {
                var count = <?php echo $c; ?>;
                $(".add").click(function() {
                    count = count + 1;
                    $('#impress_items').append('<li><label>Nr :</label><input type="text" name="impress_data[' + count + '][n]" size="10" value=""/><label>Description :</label><input type="text" name="impress_data[' + count + '][d]" size="50" value=""/><label>Price :</label><input type="text" name="impress_data[' + count + '][p]" size="20" value=""/><span class="remove">Remove</span></li>');
                   //$('#impress_items').append('<? echo implode('',explode("\n",Print_price_fileds('count'))); ?>'.replace(/count/g, count));
                    return false;
                });
                $(".remove").live('click', function() {
                    $(this).parent().remove();
                });
            });
        </script>
        <style>#impress_items {list-style: none;}</style>
    <?php
    echo '</div>';
}


//Save product price
//add_action('save_post', 'save_detailss');

function save_detailss($post_id){ 
global $post;


    // to prevent metadata or custom fields from disappearing... 
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
    return $post_id; 
    // OK, we're authenticated: we need to find and save the data
    if (isset($_POST['impress_data'])){
        $data = $_POST['impress_data'];
        update_post_meta($post_id,'impress_data',$data);
    }else{
        delete_post_meta($post_id,'impress_data');
    }
} 


















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

// 2nd meta box
$meta_boxes[] = array(
	// Meta box id, UNIQUE per meta box. Optional since 4.1.5
	'id' => 'impressscreen',

	// Meta box title - Will appear at the drag and drop handle bar. Required.
	'title' => 'Preview',

	// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
	'pages' => array( 'impress'),

	// Where the meta box appear: normal (default), advanced, side. Optional.
	'context' => 'normal',

	// Order of meta box: high (default), low. Optional.
	'priority' => 'high',

	'fields' => array(
		// SELECT BOX
		// TEXTAREA
		array(
			'name' => 'Screen',
			'desc' => '',
			'id'   => "{$prefix}screen",
			'type' => 'screen',
			'cols' => '20',
			'rows' => '3',
		),
	)
);

$pages = get_pages(); 
foreach ( $pages as $page ) {
	$arr[$page->post_name] = $page->post_title;
}

// 2nd meta box
$meta_boxes[] = array(
	// Meta box id, UNIQUE per meta box. Optional since 4.1.5
	'id' => 'impresssettings',

	// Meta box title - Will appear at the drag and drop handle bar. Required.
	'title' => 'Settings',

	// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
	'pages' => array( 'impress'),

	// Where the meta box appear: normal (default), advanced, side. Optional.
	'context' => 'normal',

	// Order of meta box: high (default), low. Optional.
	'priority' => 'high',

	'fields' => array(
		// SELECT BOX
		array(
			'name'     => 'Select a Page',
			'id'       => "{$prefix}selectpage",
			'type'     => 'select',
			// Array of 'value' => 'Label' pairs for select box
			'options'  => $arr,
			// Select multiple values, optional. Default is false.
			'multiple' => false,
		),
	)
);

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
$meta_boxes = array();

$webfonts_options = array();
$webfonts =  get_google_webfonts_array(GOOGLE_API_KEY);
foreach($webfonts->items as $cut){

    foreach($cut->variants as $variant){

        $webfonts_options[$cut->family.':'.$variant] = $cut->family.' - '.$variant;
      }
}

$meta_boxes[] = array(
    // Meta box id, UNIQUE per meta box. Optional since 4.1.5
    'id' => 'impress_design',

    // Meta box title - Will appear at the drag and drop handle bar. Required.
    'title' => 'Design',

    // Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
    'pages' => array( 'impress'),

    // Where the meta box appear: normal (default), advanced, side. Optional.
    'context' => 'normal',

    // Order of meta box: high (default), low. Optional.
    'priority' => 'high',

    'fields' => array(
        // SELECT BOX



        array( 'type'     => 'screen'),
        array(
            'name'     => 'Type Color',
            'id'       => "{$prefix}type_color",
            'type'     => 'color',
            'std'      => '#000000'
           // 'clone' => true
        ),
        array( 'type'     => 'divider'),
        array(
            'name'     => 'Gradient From',
            'id'       => "{$prefix}color_gradient_from",
            'type'     => 'color',
            'std'      => '#575757'

        ),
        array(
            'name'     => 'Gradient To',
            'id'       => "{$prefix}color_gradient_from",
            'type'     => 'color',
            'std'      => '#303030'

        ),



        array(
            'name'     => 'Gradient Type',
            'id'       => "{$prefix}color_gradient_type",
            'type'     => 'select',
            'std'      => '1',
            'desc'     => 'Choose two colors and a select a gradient style for your background.',
            'options'   => array(1=>'Linear',2=>'Radius')

        ),
        array( 'type'     => 'divider'),
        array(
            'name'     => 'Select Effect',
            'id'       => "{$prefix}effect",
            'type'     => 'select',
            'std'      => 'bokeh',
            'desc'     => 'Choose a background parallax effect.',
            'options'   => array(   "none"=>"None","bokeh"=>"Bokeh","plaid"=>"Plaid","boxes"=>"Boxes","hexagons"=>"Hexagons","stars"=>"Stars","star-color"=>"Stars Color")

        ),

        array(
            'name'     => 'Choose Typeface',
            'id'       => "{$prefix}typeface",
            'type'     => 'select',
            'std'      => '',
            'desc'     => 'Select a Google Web Font for your typeface.',
            'options'   => $webfonts_options

        ),

        array(
            'name'     => 'Header',
            'id'       => "{$prefix}header",
            'type'     => 'textarea',
            'std'      => '',
            'desc'     => 'Custom HTML Allowed',
        ),

        array(
            'name'     => 'Footer',
            'id'       => "{$prefix}footer",
            'type'     => 'textarea',
            'std'      => '',
            'desc'     => 'Custom HTML Allowed',
        ),

    )
);



$meta_boxes[] = array(
	// Meta box id, UNIQUE per meta box. Optional since 4.1.5
	'id' => 'impressslide',

	// Meta box title - Will appear at the drag and drop handle bar. Required.
	'title' => 'Slides',

	// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
	'pages' => array( 'impress'),

	// Where the meta box appear: normal (default), advanced, side. Optional.
	'context' => 'normal',

	// Order of meta box: high (default), low. Optional.
	'priority' => 'high',

	'fields' => array(
		// SELECT BOX




        array(
			'name'     => 'Select a Page',
			'id'       => "{$prefix}slide",
			'type'     => 'impress',
			// Array of 'value' => 'Label' pairs for select box
		//	'options'  => array(
		//			'option1' => 'value1',
		//			'option2' => 'value2'
		//		),
          //  'options' => array('value1'=>'label1','value2'=>'label2'),
          //  'multiple'   => 'true',
			'clone' => true
		),



	)
);


$meta_boxes[] = array(
    // Meta box id, UNIQUE per meta box. Optional since 4.1.5
    'id' => 'impressslide2',

    // Meta box title - Will appear at the drag and drop handle bar. Required.
    'title' => 'Slides2',

    // Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
    'pages' => array( 'impress'),

    // Where the meta box appear: normal (default), advanced, side. Optional.
    'context' => 'normal',

    // Order of meta box: high (default), low. Optional.
    'priority' => 'high',

    'fields' => array(
        // SELECT BOX




        array(
            'name'     => 'Select a Page',
            'id'       => "{$prefix}slide2",
            'type'     => 'text',
            // Array of 'value' => 'Label' pairs for select box
            //	'options'  => array(
            //			'option1' => 'value1',
            //			'option2' => 'value2'
            //		),
            //  'options' => array('value1'=>'label1','value2'=>'label2'),
            //  'multiple'   => 'true',
            'clone' => true
        ),



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