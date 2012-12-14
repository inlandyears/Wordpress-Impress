<?php
class IMPRESS_Options_canvas extends IMPRESS_Options{	
	
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
				
		echo '<div class="canvas">';
		echo '</div>';
		
	}//function
	
	
	/**
	 * Enqueue Function.
	 *
	 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
	 *
	 * @since IMPRESS_Options 1.0
	*/
	function enqueue(){
		
		wp_enqueue_script(
			'impress-opts-field-canvas-js', 
			IMPRESS_OPTIONS_URL.'fields/color/field_color.js', 
			array('jquery', 'farbtastic'),
			time(),
			true
		);
		
	}//function
	
}//class
?>