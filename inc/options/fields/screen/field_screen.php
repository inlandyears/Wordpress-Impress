<?php
class IMPRESS_Options_screen extends IMPRESS_Options{	
	
	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since IMPRESS_Options 1.0
	*/
	function __construct($field = array(), $value ='', $parent){
		
		parent::__construct($parent->sections, $parent->args, $parent->extra_tabs);
		$this->field = $field;
		$this->value = $value;
		//$this->render();
		
	}//function
	
	
	
	/**
	 * Field Render Function.
	 *
	 * Takes the vars and outputs the HTML for the field in the settings
	 *
	 * @since IMPRESS_Options 1.0
	*/
	function render(){

	global $IMPRESS_Options;
    $y = $IMPRESS_Options;
    $page = $y->get('pages_select');
    $permalink = get_permalink( $page );

		
		echo '<div class="farb-popup-wrapper" id="'.$this->field['id'].'">';

		echo '<iframe src="'.$permalink.'" width="530" height="305" frameborder="0"></iframe>';

		
		echo '</div>';
		
	}//function
	
	
	
}//class
?>