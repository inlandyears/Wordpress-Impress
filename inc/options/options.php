<?php
if ( ! class_exists('IMPRESS_Options') ){
	
	// windows-proof constants: replace backward by forward slashes - thanks to: https://github.com/peterbouwmeester
	$fslashed_dir = trailingslashit(str_replace('\\','/',dirname(__FILE__)));
	$fslashed_abs = trailingslashit(str_replace('\\','/',ABSPATH));
	
	if(!defined('IMPRESS_OPTIONS_DIR')){
		define('IMPRESS_OPTIONS_DIR', $fslashed_dir);
	}
	
	if(!defined('IMPRESS_OPTIONS_URL')){
		define('IMPRESS_OPTIONS_URL', site_url(str_replace( $fslashed_abs, '', $fslashed_dir )));
	}
	
class IMPRESS_Options{
	
	protected $framework_url = 'http://leemason.github.com/NHP-Theme-Options-Framework/';
	protected $framework_version = '1.0.6';
		
	public $dir = IMPRESS_OPTIONS_DIR;
	public $url = IMPRESS_OPTIONS_URL;
	public $page = '';
	public $args = array();
	public $sections = array();
	public $extra_tabs = array();
	public $errors = array();
	public $warnings = array();
	public $options = array();
	
	

	/**
	 * Class Constructor. Defines the args for the theme options class
	 *
	 * @since IMPRESS_Options 1.0
	 *
	 * @param $array $args Arguments. Class constructor arguments.
	*/
	function __construct($sections = array(), $args = array(), $extra_tabs = array()){
		
		$defaults = array();
		
		$defaults['opt_name'] = '';//must be defined by theme/plugin
		
		$defaults['google_api_key'] = '';//must be defined for use with google webfonts field type
		
		$defaults['menu_icon'] = IMPRESS_OPTIONS_URL.'/img/menu_icon.png';
		$defaults['menu_title'] = __('Options', 'impress-opts');
		$defaults['page_icon'] = 'icon-themes';
		$defaults['page_title'] = __('Options', 'impress-opts');
		$defaults['page_slug'] = '_options';
		$defaults['page_cap'] = 'manage_options';
		$defaults['page_type'] = 'menu';
		$defaults['page_parent'] = '';
		$defaults['page_position'] = 100;
		$defaults['allow_sub_menu'] = true;
		
		$defaults['show_import_export'] = true;
		$defaults['dev_mode'] = true;
		$defaults['stylesheet_override'] = false;
		
		//$defaults['footer_credit'] = __('<span id="footer-thankyou">Options Panel created using the <a href="'.$this->framework_url.'" target="_blank">NHP Theme Options Framework</a> Version '.$this->framework_version.'</span>', 'impress-opts');
		
		$defaults['help_tabs'] = array();
		$defaults['help_sidebar'] = __('', 'impress-opts');
		
		//get args
		$this->args = wp_parse_args($args, $defaults);
		$this->args = apply_filters('impress-opts-args-'.$this->args['opt_name'], $this->args);
		
		//get sections
		$this->sections = apply_filters('impress-opts-sections-'.$this->args['opt_name'], $sections);
		
		//get extra tabs
		$this->extra_tabs = apply_filters('impress-opts-extra-tabs-'.$this->args['opt_name'], $extra_tabs);
		
		//set option with defaults
		add_action('init', array(&$this, '_set_default_options'));
		
		//options page
		add_action('admin_menu', array(&$this, '_options_page'));
		
		//register setting
		add_action('admin_init', array(&$this, '_register_setting'));
		
		//add the js for the error handling before the form
		add_action('impress-opts-page-before-form-'.$this->args['opt_name'], array(&$this, '_errors_js'), 1);
		
		//add the js for the warning handling before the form
		add_action('impress-opts-page-before-form-'.$this->args['opt_name'], array(&$this, '_warnings_js'), 2);
		
		//hook into the wp feeds for downloading the exported settings
		add_action('do_feed_nhpopts-'.$this->args['opt_name'], array(&$this, '_download_options'), 1, 1);
		
		//get the options for use later on
		$this->options = get_option($this->args['opt_name']);
		
	}//function
	
	
	/**
	 * ->get(); This is used to return and option value from the options array
	 *
	 * @since IMPRESS_Options 1.0.1
	 *
	 * @param $array $args Arguments. Class constructor arguments.
	*/
	function get($opt_name, $default = null){
		return (!empty($this->options[$opt_name])) ? $this->options[$opt_name] : $default;
	}//function
	
	/**
	 * ->set(); This is used to set an arbitrary option in the options array
	 *
	 * @since IMPRESS_Options 1.0.1
	 * 
	 * @param string $opt_name the name of the option being added
	 * @param mixed $value the value of the option being added
	 */
	function set($opt_name = '', $value = '') {
		if($opt_name != ''){
			$this->options[$opt_name] = $value;
			update_option($this->args['opt_name'], $this->options);
		}//if
	}
	
	/**
	 * ->show(); This is used to echo and option value from the options array
	 *
	 * @since IMPRESS_Options 1.0.1
	 *
	 * @param $array $args Arguments. Class constructor arguments.
	*/
	function show($opt_name, $default = ''){
		$option = $this->get($opt_name);
		if(!is_array($option) && $option != ''){
			echo $option;
		}elseif($default != ''){
			echo $default;
		}
	}//function
	
	
	
	/**
	 * Get default options into an array suitable for the settings API
	 *
	 * @since IMPRESS_Options 1.0
	 *
	*/
	function _default_values(){
		
		$defaults = array();
		
		foreach($this->sections as $k => $section){
			
			if(isset($section['fields'])){
		
				foreach($section['fields'] as $fieldk => $field){
					
					if(!isset($field['std'])){$field['std'] = '';}
						
						$defaults[$field['id']] = $field['std'];
					
				}//foreach
			
			}//if
			
		}//foreach
		
		//fix for notice on first page load
		$defaults['last_tab'] = 0;

		return $defaults;
		
	}
	
	
	
	/**
	 * Set default options on admin_init if option doesnt exist (theme activation hook caused problems, so admin_init it is)
	 *
	 * @since IMPRESS_Options 1.0
	 *
	*/
	function _set_default_options(){
		if(!get_option($this->args['opt_name'])){
			add_option($this->args['opt_name'], $this->_default_values());
		}
		$this->options = get_option($this->args['opt_name']);
	}//function
	
	
	/**
	 * Class Theme Options Page Function, creates main options page.
	 *
	 * @since IMPRESS_Options 1.0
	*/
	function _options_page(){
		if($this->args['page_type'] == 'submenu'){
			if(!isset($this->args['page_parent']) || empty($this->args['page_parent'])){
				$this->args['page_parent'] = 'themes.php';
			}
			$this->page = add_submenu_page(
							$this->args['page_parent'],
							$this->args['page_title'], 
							$this->args['menu_title'], 
							$this->args['page_cap'], 
							$this->args['page_slug'], 
							array(&$this, '_options_page_html')
						);
		}else{
			$this->page = add_menu_page(
							$this->args['page_title'], 
							$this->args['menu_title'], 
							$this->args['page_cap'], 
							$this->args['page_slug'], 
							array(&$this, '_options_page_html'),
							$this->args['menu_icon'],
							$this->args['page_position']
						);
						
		if(true === $this->args['allow_sub_menu']){
						
			//this is needed to remove the top level menu item from showing in the submenu
			add_submenu_page($this->args['page_slug'],$this->args['page_title'],'',$this->args['page_cap'],$this->args['page_slug'],create_function( '$a', "return null;" ));
						
						
			foreach($this->sections as $k => $section){
							
				add_submenu_page(
						$this->args['page_slug'],
						$section['title'], 
						$section['title'], 
						$this->args['page_cap'], 
						$this->args['page_slug'].'&tab='.$k, 
						create_function( '$a', "return null;" )
				);
					
			}
			
			if(true === $this->args['show_import_export']){
				
				add_submenu_page(
						$this->args['page_slug'],
						__('Import / Export', 'impress-opts'), 
						__('Import / Export', 'impress-opts'), 
						$this->args['page_cap'], 
						$this->args['page_slug'].'&tab=import_export_default', 
						create_function( '$a', "return null;" )
				);
					
			}//if
						

			foreach($this->extra_tabs as $k => $tab){
				
				add_submenu_page(
						$this->args['page_slug'],
						$tab['title'], 
						$tab['title'], 
						$this->args['page_cap'], 
						$this->args['page_slug'].'&tab='.$k, 
						create_function( '$a', "return null;" )
				);
				
			}

			if(true === $this->args['dev_mode']){
						
				add_submenu_page(
						$this->args['page_slug'],
						__('Dev Mode Info', 'impress-opts'), 
						__('Dev Mode Info', 'impress-opts'), 
						$this->args['page_cap'], 
						$this->args['page_slug'].'&tab=dev_mode_default', 
						create_function( '$a', "return null;" )
				);
				
			}//if

		}//if			
						
			
		}//else

		add_action('admin_print_styles-'.$this->page, array(&$this, '_enqueue'));
		add_action('load-'.$this->page, array(&$this, '_load_page'));
	}//function	
	
	

	/**
	 * enqueue styles/js for theme page
	 *
	 * @since IMPRESS_Options 1.0
	*/
	function _enqueue(){
		
		wp_register_style(
				'impress-opts-css', 
				$this->url.'css/options.css',
				array('farbtastic'),
				time(),
				'all'
			);

		wp_register_style(
				'impress-bootstrap-css', 
				$this->url.'css/bootstrap.min.css',
				'',
				time(),
				'all'
			);
			
		wp_register_style(
			'impress-opts-jquery-ui-css',
			apply_filters('impress-opts-ui-theme', $this->url.'css/jquery-ui-aristo/aristo.css'),
			'',
			time(),
			'all'
		);
			
			
		if(false === $this->args['stylesheet_override']){
			wp_enqueue_style('impress-opts-css');
			wp_enqueue_style('impress-bootstrap-css');
		}
		
		
		wp_enqueue_script(
			'impress-opts-js', 
			$this->url.'js/options.js', 
			array('jquery'),
			time(),
			true
		);
		wp_localize_script('impress-opts-js', 'impress_opts', array('reset_confirm' => __('Are you sure? Resetting will loose all custom values.', 'impress-opts'), 'opt_name' => $this->args['opt_name']));
		
		do_action('impress-opts-enqueue-'.$this->args['opt_name']);
		
		
		foreach($this->sections as $k => $section){
			
			if(isset($section['fields'])){
				
				foreach($section['fields'] as $fieldk => $field){
					
					if(isset($field['type'])){
					
						$field_class = 'IMPRESS_Options_'.$field['type'];
						
						if(!class_exists($field_class)){
							require_once($this->dir.'fields/'.$field['type'].'/field_'.$field['type'].'.php');
						}//if
				
						if(class_exists($field_class) && method_exists($field_class, 'enqueue')){
							$enqueue = new $field_class('','',$this);
							$enqueue->enqueue();
						}//if
						
					}//if type
					
				}//foreach
			
			}//if fields
			
		}//foreach
			
		
	}//function
	
	/**
	 * Download the options file, or display it
	 *
	 * @since IMPRESS_Options 1.0.1
	*/
	function _download_options(){
		//-'.$this->args['opt_name']
		if(!isset($_GET['secret']) || $_GET['secret'] != md5(AUTH_KEY.SECURE_AUTH_KEY)){wp_die('Invalid Secret for options use');exit;}
		if(!isset($_GET['feed'])){wp_die('No Feed Defined');exit;}
		$backup_options = get_option(str_replace('nhpopts-','',$_GET['feed']));
		$backup_options['impress-opts-backup'] = '1';
		$content = '###'.serialize($backup_options).'###';
		
		
		if(isset($_GET['action']) && $_GET['action'] == 'download_options'){
			header('Content-Description: File Transfer');
			header('Content-type: application/txt');
			header('Content-Disposition: attachment; filename="'.str_replace('nhpopts-','',$_GET['feed']).'_options_'.date('d-m-Y').'.txt"');
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			echo $content;
			exit;
		}else{
			echo $content;
			exit;
		}
	}
	
	
	
	
	/**
	 * show page help
	 *
	 * @since IMPRESS_Options 1.0
	*/
	function _load_page(){
		
		//do admin head action for this page
		add_action('admin_head', array(&$this, 'admin_head'));
		
		//do admin footer text hook
		add_filter('admin_footer_text', array(&$this, 'admin_footer_text'));
		
		$screen = get_current_screen();
		
		if(is_array($this->args['help_tabs'])){
			foreach($this->args['help_tabs'] as $tab){
				$screen->add_help_tab($tab);
			}//foreach
		}//if
		
		if($this->args['help_sidebar'] != ''){
			$screen->set_help_sidebar($this->args['help_sidebar']);
		}//if
		
		do_action('impress-opts-load-page-'.$this->args['opt_name'], $screen);
		
	}//function
	
	
	/**
	 * do action impress-opts-admin-head for theme options page
	 *
	 * @since IMPRESS_Options 1.0
	*/
	function admin_head(){

		global $IMPRESS_Options;
		$y = $IMPRESS_Options;
		$c = $y->get('type_color');
		$grad_arr = $y->get('color_gradient');

		$type = $y->get('typeface');

		$f = $grad_arr['from'];
		$t = $grad_arr['to'];
		$s = $grad_arr['type'];

		$subs = explode(":", $type);
		if($subs) {
		    echo '<link href="http://fonts.googleapis.com/css?family='.$type.'" rel="stylesheet" type="text/css">';
		}

		echo '<style>'."\n";
		echo '#canvas {'."\n";
		echo 'font-family: "'.$subs[0].'";'."\n";
		if (is_int($subs[1])) {
			echo 'font-weight: '.$subs[1].';'."\n";
		}else{
			echo 'font-style: '.$subs[1].';'."\n";
		}
		echo 'color: '.$c.';'."\n";

		if($s == 1) {
		    echo 'background: -moz-linear-gradient(top,  '.$f.' 0%, '.$t.');'."\n";
		    echo 'background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,'.$f.'), color-stop(100%, '.$t.'));'."\n";
		    echo 'background: -webkit-linear-gradient(top, '.$f.' 0%, '.$t.' 100%);'."\n";
		    echo 'background: -o-linear-gradient(top, '.$f.' 0%, '.$t.' 100%);'."\n";
		    echo 'background: -ms-linear-gradient(top, '.$f.' 0%, '.$t.' 100%);'."\n";
		    echo 'background: linear-gradient(to bottom, '.$f.' 0%, '.$t.' 100%);'."\n";
		    echo 'filter: progid:DXImageTransform.Microsoft.gradient( startColorstr="'.$f.'", endColorstr="'.$t.'",GradientType=0 );'."\n";
		}else{
		    echo 'background: -moz-radial-gradient(center, ellipse cover,  '.$f.' 0%, '.$t.');';
		    echo 'background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%,'.$f.'), color-stop(100%, '.$t.'));';
		    echo 'background: -webkit-radial-gradient(center, ellipse cover,  '.$f.' 0%, '.$t.' 100%);';
		    echo 'background: -o-radial-gradient(center, ellipse cover,  '.$f.' 0%, '.$t.' 100%);';
		    echo 'background: -ms-radial-gradient(center, ellipse cover,  '.$f.' 0%, '.$t.' 100%);';
		    echo 'background: radial-gradient(ellipse at center,  '.$f.' 0%, '.$t.' 100%);';
		    echo 'filter: progid:DXImageTransform.Microsoft.gradient( startColorstr="'.$f.'", endColorstr="'.$t.'",GradientType=1 );';


		}
		    echo '}'."\n";
			echo '</style>'."\n";

		
		do_action('impress-opts-admin-head-'.$this->args['opt_name'], $this);
		
	}//function
	
	
	function admin_footer_text($footer_text){
		//return $this->args['footer_credit'];
	}//function
	
	
	
	
	/**
	 * Register Option for use
	 *
	 * @since IMPRESS_Options 1.0
	*/
	function _register_setting(){
		
		register_setting($this->args['opt_name'].'_group', $this->args['opt_name'], array(&$this,'_validate_options'));
		
		foreach($this->sections as $k => $section){
			
			add_settings_section($k.'_section', $section['title'], array(&$this, '_section_desc'), $k.'_section_group');
			
			if(isset($section['fields'])){
			
				foreach($section['fields'] as $fieldk => $field){
					
					if(isset($field['title'])){
					
						$th = (isset($field['sub_desc']))?$field['title'].'<span class="description">'.$field['sub_desc'].'</span>':$field['title'];
					}else{
						$th = '';
					}
					
					add_settings_field($fieldk.'_field', $th, array(&$this,'_field_input'), $k.'_section_group', $k.'_section', $field); // checkbox
					
				}//foreach
			
			}//if(isset($section['fields'])){
			
		}//foreach
		
		do_action('impress-opts-register-settings-'.$this->args['opt_name']);
		
	}//function
	
	
	
	/**
	 * Validate the Options options before insertion
	 *
	 * @since IMPRESS_Options 1.0
	*/
	function _validate_options($plugin_options){
		
		set_transient('impress-opts-saved', '1', 1000 );
		
		if(!empty($plugin_options['import'])){
			
			if($plugin_options['import_code'] != ''){
				$import = $plugin_options['import_code'];
			}elseif($plugin_options['import_link'] != ''){
				$import = wp_remote_retrieve_body( wp_remote_get($plugin_options['import_link']) );
			}
			
			$imported_options = unserialize(trim($import,'###'));
			if(is_array($imported_options) && isset($imported_options['impress-opts-backup']) && $imported_options['impress-opts-backup'] == '1'){
				$imported_options['imported'] = 1;
				return $imported_options;
			}
			
			
		}
		
		
		if(!empty($plugin_options['defaults'])){
			$plugin_options = $this->_default_values();
			return $plugin_options;
		}//if set defaults

		
		//validate fields (if needed)
		$plugin_options = $this->_validate_values($plugin_options, $this->options);
		
		if($this->errors){
			set_transient('impress-opts-errors-'.$this->args['opt_name'], $this->errors, 1000 );		
		}//if errors
		
		if($this->warnings){
			set_transient('impress-opts-warnings-'.$this->args['opt_name'], $this->warnings, 1000 );		
		}//if errors
		
		do_action('impress-opts-options-validate-'.$this->args['opt_name'], $plugin_options, $this->options);
		
		
		unset($plugin_options['defaults']);
		unset($plugin_options['import']);
		unset($plugin_options['import_code']);
		unset($plugin_options['import_link']);
		
		return $plugin_options;	
	
	}//function
	
	
	
	
	/**
	 * Validate values from options form (used in settings api validate function)
	 * calls the custom validation class for the field so authors can override with custom classes
	 *
	 * @since IMPRESS_Options 1.0
	*/
	function _validate_values($plugin_options, $options){
		foreach($this->sections as $k => $section){
			
			if(isset($section['fields'])){
			
				foreach($section['fields'] as $fieldk => $field){
					$field['section_id'] = $k;
					
					if(isset($field['type']) && $field['type'] == 'multi_text'){continue;}//we cant validate this yet
					
					if(!isset($plugin_options[$field['id']]) || $plugin_options[$field['id']] == ''){
						continue;
					}
					
					/*
					//force validate of custom filed types
					if(isset($field['type']) && !isset($field['validate'])){
						if($field['type'] == 'color' || $field['type'] == 'color_gradient'){
							$field['validate'] = 'color';
						}elseif($field['type'] == 'date'){
							$field['validate'] = 'date';
						}
					}//if
					*/

	
					if(isset($field['validate'])){
						$validate = 'IMPRESS_Validation_'.$field['validate'];
						
						if(!class_exists($validate)){
							require_once($this->dir.'validation/'.$field['validate'].'/validation_'.$field['validate'].'.php');
						}//if
						
						if(class_exists($validate)){
							$validation = new $validate($field, $plugin_options[$field['id']], $options[$field['id']]);
							$plugin_options[$field['id']] = $validation->value;
							if(isset($validation->error)){
								$this->errors[] = $validation->error;
							}
							if(isset($validation->warning)){
								$this->warnings[] = $validation->warning;
							}
							continue;
						}//if
					}//if
					
					
					if(isset($field['validate_callback']) && function_exists($field['validate_callback'])){
						
						$callbackvalues = call_user_func($field['validate_callback'], $field, $plugin_options[$field['id']], $options[$field['id']]);
						$plugin_options[$field['id']] = $callbackvalues['value'];
						if(isset($callbackvalues['error'])){
							$this->errors[] = $callbackvalues['error'];
						}//if
						if(isset($callbackvalues['warning'])){
							$this->warnings[] = $callbackvalues['warning'];
						}//if
						
					}//if
					
					
				}//foreach
			
			}//if(isset($section['fields'])){
			
		}//foreach
		return $plugin_options;
	}//function
	
	
	
	
	
	
	
	
	/**
	 * HTML OUTPUT.
	 *
	 * @since IMPRESS_Options 1.0
	*/
	function _options_page_html(){
		
		echo '<div class="wrap">';
echo (isset($this->args['intro_text']))?$this->args['intro_text']:'';
do_action('impress-opts-page-before-form-'.$this->args['opt_name']);
echo '<form method="post" action="options.php" enctype="multipart/form-data" id="impress-opts-form-wrapper">';

				

			echo '<div class="row-fluid">';
				echo '<div id="impress-opts-sidebar" class="span3">';
					echo '<div class="impress_bg pad">';
						echo '<h2 id="impress-opts-heading">'.get_admin_page_title().'</h2>';
						echo '<div class="sprite icons36 gears"></div>';

						settings_fields($this->args['opt_name'].'_group');

						echo '<div class="updater">';


							if(isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true' && get_transient('impress-opts-saved') == '1'){
								if(isset($this->options['imported']) && $this->options['imported'] == 1){
									echo '<div id="impress-opts-imported" class="message">'.apply_filters('impress-opts-imported-text-'.$this->args['opt_name'], __('<strong>Settings Imported!</strong>', 'impress-opts')).'</div>';
								}else{
									echo '<div id="impress-opts-save" class="message">'.apply_filters('impress-opts-saved-text-'.$this->args['opt_name'], __('<strong>Settings Saved!</strong>', 'impress-opts')).'</div>';
								}
								delete_transient('impress-opts-saved');
							}
							echo '<div id="impress-opts-save-warn" class="message">'.apply_filters('impress-opts-changed-text-'.$this->args['opt_name'], __('<strong>You have unsaved changes.</strong>', 'impress-opts')).'</div>';
							echo '<div id="impress-opts-field-errors" class="message">'.__('<strong><span></span> error(s) were found!</strong>', 'impress-opts').'</div>';
					
							echo '<div id="impress-opts-field-warnings" class="message">'.__('<strong><span></span> warning(s) were found!</strong>', 'impress-opts').'</div>';

							echo '</div>';

						echo '</div>';
					echo '<ul id="impress-opts-group-menu" class="impress_bg">';
						foreach($this->sections as $k => $section){
							//$icon = (!isset($section['icon']))?'<img src="'.$this->url.'img/glyphicons/glyphicons_019_cogwheel.png" /> ':'<img src="'.$section['icon'].'" /> ';
							echo '<li id="'.$k.'_section_group_li" class="impress-opts-group-tab-link-li">';
								echo '<a href="javascript:void(0);" id="'.$k.'_section_group_li_a" class="impress-opts-group-tab-link-a" data-rel="'.$k.'"><span>'.$section['title'].'</span></a>';
								echo '<div class="sprite arrow"></div>';
							echo '</li>';
						}
						
						
						do_action('impress-opts-after-section-menu-items-'.$this->args['opt_name'], $this);
						
						if(true === $this->args['show_import_export']){
							echo '<li id="import_export_default_section_group_li" class="impress-opts-group-tab-link-li">';
									echo '<a href="javascript:void(0);" id="import_export_default_section_group_li_a" class="impress-opts-group-tab-link-a" data-rel="import_export_default"><span>'.__('Import / Export', 'impress-opts').'</span></a>';
									echo '<div class="sprite arrow"></div>';
							echo '</li>';
						}//if
						
						
						
						
						
						foreach($this->extra_tabs as $k => $tab){
							//$icon = (!isset($tab['icon']))?'<img src="'.$this->url.'img/glyphicons/glyphicons_019_cogwheel.png" /> ':'<img src="'.$tab['icon'].'" /> ';
							echo '<li id="'.$k.'_section_group_li" class="impress-opts-group-tab-link-li">';
								echo '<a href="javascript:void(0);" id="'.$k.'_section_group_li_a" class="impress-opts-group-tab-link-a custom-tab" data-rel="'.$k.'"><span>'.$tab['title'].'</span></a>';
								echo '<div class="sprite arrow"></div>';
							echo '</li>';
						}

						
						if(true === $this->args['dev_mode']){
							echo '<li id="dev_mode_default_section_group_li" class="impress-opts-group-tab-link-li">';
								echo '<a href="javascript:void(0);" id="dev_mode_default_section_group_li_a" class="impress-opts-group-tab-link-a custom-tab" data-rel="dev_mode_default"><span>'.__('Dev Mode Info', 'impress-opts').'</span></a>';
								echo '<div class="sprite arrow"></div>';
							echo '</li>';
						}//if
						
					echo '</ul>';
					echo '<div class="impress_bg pad">';
										if(isset($this->args['share_icons'])){
						echo '<div id="impress-opts-share">';
						foreach($this->args['share_icons'] as $link){
							echo '<a href="'.$link['link'].'" title="'.$link['title'].'" target="_blank" class="sprite icons36 '.$link['class'].'"></a>';
						}
						echo '</div>';
					}
					echo '</div>';
				echo '</div>';
				echo '<div class="span9">';

					echo '<div id="impress-opts-main" class="impress_bg">';

				$this->options['last_tab'] = (isset($_GET['tab']) && !get_transient('impress-opts-saved'))?$_GET['tab']:$this->options['last_tab'];
				
				echo '<input type="hidden" id="last_tab" name="'.$this->args['opt_name'].'[last_tab]" value="'.$this->options['last_tab'].'" />';
				
				echo '<div id="impress-opts-header">';
					global $IMPRESS_Options;
					$y = $IMPRESS_Options;
					$page = $y->get('pages_select');
				
					if ($page) {
						echo '<a href="'.get_page_link($page).'" class="button-secondary" target="_blank">Preview</a>';
					}

					submit_button('', 'primary', '', false);
				echo '</div>';

					
						foreach($this->sections as $k => $section){
							echo '<div id="'.$k.'_section_group'.'" class="impress-opts-group-tab">';
								do_settings_sections($k.'_section_group');
							echo '</div>';
						}					
						
						
						if(true === $this->args['show_import_export']){

							echo '<div id="import_export_default_section_group'.'" class="impress-opts-group-tab">';
									
									echo '<h3>'.__('Import / Export Options', 'impress-opts').'</h3>';
									echo '<div class="impress-opts-section-desc">';
									echo '<div class="impress-opts-section-desc"><p class="description">This is the Description. Again HTML is allowed</p></div>';
										echo '<h4>'.__('Import Options', 'impress-opts').'</h4>';
										
										echo '<p><a href="javascript:void(0);" id="impress-opts-import-code-button" class="button-secondary">Import from file</a> <a href="javascript:void(0);" id="impress-opts-import-link-button" class="button-secondary">Import from URL</a></p>';
										
										echo '<div id="impress-opts-import-code-wrapper">';
										
											echo '<div class="impress-opts-section-desc">';
							
												echo '<p class="description" id="import-code-description">'.apply_filters('impress-opts-import-file-description',__('Input your backup file below and hit Import to restore your sites options from a backup.', 'impress-opts')).'</p>';
											
											echo '</div>';
											
											echo '<textarea id="import-code-value" name="'.$this->args['opt_name'].'[import_code]" class="large-text" rows="8"></textarea>';
										
										echo '</div>';
									
									
										echo '<div id="impress-opts-import-link-wrapper">';
										
											echo '<div class="impress-opts-section-desc">';
												
												echo '<p class="description" id="import-link-description">'.apply_filters('impress-opts-import-link-description',__('Input the URL to another sites options set and hit Import to load the options from that site.', 'impress-opts')).'</p>';
											
											echo '</div>';

											echo '<input type="text" id="import-link-value" name="'.$this->args['opt_name'].'[import_link]" class="large-text" value="" />';
										echo '</div>';
								
									
									
									
										echo '<p id="impress-opts-import-action"><input type="submit" id="impress-opts-import" name="'.$this->args['opt_name'].'[import]" class="button-primary" value="'.__('Import', 'impress-opts').'"> <span>'.apply_filters('impress-opts-import-warning', __('WARNING! This will overwrite any existing options, please proceed with caution!', 'impress-opts')).'</span></p>';
									echo '</div>';
									echo '<div id="import_divide"></div>';
									echo '<div class="impress-opts-section-desc">';
									echo '<h4>'.__('Export Options', 'impress-opts').'</h4>';
									
										echo '<p class="description">'.apply_filters('impress-opts-backup-description', __('Here you can copy/download your themes current option settings. Keep this safe as you can use it as a backup should anything go wrong. Or you can use it to restore your settings on this site (or any other site). You also have the handy option to copy the link to yours sites settings. Which you can then use to duplicate on another site', 'impress-opts')).'</p>';
									
									
										echo '<p><a href="javascript:void(0);" id="impress-opts-export-code-copy" class="button-secondary">Copy</a> <a href="'.add_query_arg(array('feed' => 'nhpopts-'.$this->args['opt_name'], 'action' => 'download_options', 'secret' => md5(AUTH_KEY.SECURE_AUTH_KEY)), site_url()).'" id="impress-opts-export-code-dl" class="button-primary">Download</a> <a href="javascript:void(0);" id="impress-opts-export-link" class="button-secondary">Copy Link</a></p>';
										$backup_options = $this->options;
										$backup_options['impress-opts-backup'] = '1';
										$encoded_options = '###'.serialize($backup_options).'###';
										echo '<textarea class="large-text" id="impress-opts-export-code" rows="8">';print_r($encoded_options);echo '</textarea>';
										echo '<input type="text" class="large-text" id="impress-opts-export-link-value" value="'.add_query_arg(array('feed' => 'nhpopts-'.$this->args['opt_name'], 'secret' => md5(AUTH_KEY.SECURE_AUTH_KEY)), site_url()).'" />';
									
								echo '</div>';
								echo '</div>';
						}
						
						
						foreach($this->extra_tabs as $k => $tab){
							echo '<div id="'.$k.'_section_group'.'" class="impress-opts-group-tab">';
							echo '<h3>'.$tab['title'].'</h3>';
							echo $tab['content'];
							echo '</div>';
						}

						
						
						if(true === $this->args['dev_mode']){
							echo '<div id="dev_mode_default_section_group'.'" class="impress-opts-group-tab">';
								echo '<h3>'.__('Dev Mode Info', 'impress-opts').'</h3>';
								echo '<div class="impress-opts-section-desc">';
								echo '<textarea class="large-text" rows="24">'.print_r($this, true).'</textarea>';
								echo '</div>';
							echo '</div>';
						}
						
						
						do_action('impress-opts-after-section-items-'.$this->args['opt_name'], $this);


				echo '<div id="impress-opts-footer">';
				
					
					submit_button(__('Reset to Defaults', 'impress-opts'), 'secondary', $this->args['opt_name'].'[defaults]', false);
					submit_button('', 'primary', '', false);
					echo '<div class="clear"></div><!--clearfix-->';
				echo '</div>';


				
						echo '</div>';
					echo '</div>';
				echo '</div>';
			
			
			echo '</form>';
			
			do_action('impress-opts-page-after-form-'.$this->args['opt_name']);
			
		echo '</div><!--wrap-->';

	}//function
	
	
	
	/**
	 * JS to display the errors on the page
	 *
	 * @since IMPRESS_Options 1.0
	*/	
	function _errors_js(){
		
		if(isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true' && get_transient('impress-opts-errors-'.$this->args['opt_name'])){
				$errors = get_transient('impress-opts-errors-'.$this->args['opt_name']);
				$section_errors = array();
				foreach($errors as $error){
					$section_errors[$error['section_id']] = (isset($section_errors[$error['section_id']]))?$section_errors[$error['section_id']]:0;
					$section_errors[$error['section_id']]++;
				}
				
				
				echo '<script type="text/javascript">';
					echo 'jQuery(document).ready(function(){';
						echo 'jQuery("#impress-opts-field-errors span").html("'.count($errors).'");';
						echo 'jQuery("#impress-opts-field-errors").show();';
						
						foreach($section_errors as $sectionkey => $section_error){
							echo 'jQuery("#'.$sectionkey.'_section_group_li_a").append("<span class=\"impress-opts-menu-error\">'.$section_error.'</span>");';
						}
						
						foreach($errors as $error){
							echo 'jQuery("#'.$error['id'].'").addClass("impress-opts-field-error");';
							echo 'jQuery("#'.$error['id'].'").closest("td").append("<span class=\"impress-opts-th-error\">'.$error['msg'].'</span>");';
						}
					echo '});';
				echo '</script>';
				delete_transient('impress-opts-errors-'.$this->args['opt_name']);
			}
		
	}//function
	
	
	
	/**
	 * JS to display the warnings on the page
	 *
	 * @since IMPRESS_Options 1.0.3
	*/	
	function _warnings_js(){
		
		if(isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true' && get_transient('impress-opts-warnings-'.$this->args['opt_name'])){
				$warnings = get_transient('impress-opts-warnings-'.$this->args['opt_name']);
				$section_warnings = array();
				foreach($warnings as $warning){
					$section_warnings[$warning['section_id']] = (isset($section_warnings[$warning['section_id']]))?$section_warnings[$warning['section_id']]:0;
					$section_warnings[$warning['section_id']]++;
				}
				
				
				echo '<script type="text/javascript">';
					echo 'jQuery(document).ready(function(){';
						echo 'jQuery("#impress-opts-field-warnings span").html("'.count($warnings).'");';
						echo 'jQuery("#impress-opts-field-warnings").show();';
						
						foreach($section_warnings as $sectionkey => $section_warning){
							echo 'jQuery("#'.$sectionkey.'_section_group_li_a").append("<span class=\"impress-opts-menu-warning\">'.$section_warning.'</span>");';
						}
						
						foreach($warnings as $warning){
							echo 'jQuery("#'.$warning['id'].'").addClass("impress-opts-field-warning");';
							echo 'jQuery("#'.$warning['id'].'").closest("td").append("<span class=\"impress-opts-th-warning\">'.$warning['msg'].'</span>");';
						}
					echo '});';
				echo '</script>';
				delete_transient('impress-opts-warnings-'.$this->args['opt_name']);
			}
		
	}//function
	
	

	
	
	/**
	 * Section HTML OUTPUT.
	 *
	 * @since IMPRESS_Options 1.0
	*/	
	function _section_desc($section){
		
		$id = rtrim($section['id'], '_section');
		
		if(isset($this->sections[$id]['desc']) && !empty($this->sections[$id]['desc'])) {
			echo '<div class="impress-opts-section-desc">'.$this->sections[$id]['desc'].'</div>';
		}
		
	}//function
	
	
	
	
	/**
	 * Field HTML OUTPUT.
	 *
	 * Gets option from options array, then calls the speicfic field type class - allows extending by other devs
	 *
	 * @since IMPRESS_Options 1.0
	*/
	function _field_input($field){
		
		
		if(isset($field['callback']) && function_exists($field['callback'])){
			$value = (isset($this->options[$field['id']]))?$this->options[$field['id']]:'';
			do_action('impress-opts-before-field-'.$this->args['opt_name'], $field, $value);
			call_user_func($field['callback'], $field, $value);
			do_action('impress-opts-after-field-'.$this->args['opt_name'], $field, $value);
			return;
		}
		
		if(isset($field['type'])){
			
			$field_class = 'IMPRESS_Options_'.$field['type'];
			
			if(class_exists($field_class)){
				require_once($this->dir.'fields/'.$field['type'].'/field_'.$field['type'].'.php');
			}//if
			
			if(class_exists($field_class)){
				$value = (isset($this->options[$field['id']]))?$this->options[$field['id']]:'';
				do_action('impress-opts-before-field-'.$this->args['opt_name'], $field, $value);
				$render = '';
				$render = new $field_class($field, $value, $this);
				$render->render();
				do_action('impress-opts-after-field-'.$this->args['opt_name'], $field, $value);
			}//if
			
		}//if $field['type']
		
	}//function

	
}//class
}//if
?>