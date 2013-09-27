<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'RWMB_Impress_Field' ) )
{
	class RWMB_Impress_Field
	{


       /**
		 * Get field HTML
		 *
		 * @param string $html
		 * @param mixed  $meta
		 * @param array  $field
		 *
		 * @return string
		 */
		static function html( $html, $meta, $field )
		{
            $html .= '<div style="clear:both"></div>';


           /*
            $html .= '<label>Select Content Type</label>';
            $field['options'] = array('post'=>'Posts','page'=>'Pages','attachment'=>'Media');
            $html .= RWMB_Select_Field::html($html, array($meta['post_type_select']), self::refactor_field( $field, 'post_type_select' ));
            $html .= '<div style="clear:both"></div>';

            $html .= '<label>Page Content</label>';
            $options  = self::get_impress_page_options();
            $field['options'] = $options;
            $html .= RWMB_Select_Field::html($html, array($meta['content_page']), self::refactor_field( $field, 'content_page' ));
            $html .= '<div style="clear:both"></div>';

            $html .= '<label>Post Content</label>';
            $options  = self::get_impress_post_options();
            $field['options'] = $options;
            $html .= RWMB_Select_Field::html($html, array($meta['content_post']), self::refactor_field( $field, 'content_post' ));
            $html .= '<div style="clear:both"></div>';

            $html .= '<label>Media Content</label>';
            $media_field = self::refactor_field( $field, 'content_media' );
            $html .='<input class="impress_add_media" id="' . $media_field['field_name']. '" type="text" size="36" name="' . $media_field['field_name']. '" value="'. $meta['content_media'] .'" />';
            $html .='<input class="upload_image_button" id="' . $media_field['field_name']. '_button" type="button" value="Add Media" />';
            $html .= '<div style="clear:both"></div>';
           // $html .= RWMB_Text_Field::html($html, $meta['first'], $temp_field);
*/

            $html .= '<label>Title</label>';
            $html .= RWMB_Text_Field::html($html, $meta['content_title'], self::refactor_field( $field, 'content_title' ));
            $html .= '<div style="clear:both"></div>';

            $html .= '<label>Content</label>';
            $html .= RWMB_Wysiwyg_Field::html($html, $meta['content'], self::refactor_field( $field, 'content' ));
            $html .= '<div style="clear:both"></div>';

            $html .= '<label>Custom Class</label>';
            $html .= RWMB_Text_Field::html($html, $meta['custom_class'], self::refactor_field( $field, 'custom_class' ));
            $html .= '<div style="clear:both"></div>';


            $html .= '<label>Overide Spatial Settings</label>';
            $html .= RWMB_Checkbox_Field::html($html, $meta['override'], self::refactor_field( $field, 'override' ));
            $html .= '<div style="clear:both"></div>';


          //  $html .= '<label>first</label>';
          //  $html .= RWMB_Text_Field::html($html, $meta['first'], self::refactor_field( $field, 'first' ));
          //  $html .= '<div style="clear:both"></div>';

            $html .= '<label>X-Axis Space</label>';
            //   'name' => 'X Position',
            //	'id'   => "{$prefix}xpos",
            //	'type' => 'number',
            $field['std']  = 0;
            $field['min']  = -200000;
            $field['step'] = 1000;
            $html .= RWMB_Number_Field::html($html, $meta['xpos'], self::refactor_field( $field, 'xpos' ));

            $html .= '<div style="clear:both"></div>';


            $html .= '<label>Y-Axis Space</label>';
            $html .= RWMB_Number_Field::html($html, $meta['ypos'], self::refactor_field( $field, 'ypos' ));
            $html .= '<div style="clear:both"></div>';

            $html .= '<label>Z-Axis Space</label>';
            $html .= RWMB_Number_Field::html($html, $meta['zpos'], self::refactor_field( $field, 'zpos' ));
            $html .= '<div style="clear:both"></div>';

            $html .= '<label>X Rotate</label>';
            $field['std']  = 0;
            $field['min']  = -359;
            $field['step'] = 1;
            $html .= RWMB_Number_Field::html($html, $meta['xrotate'], self::refactor_field( $field, 'xrotate' ));
            $html .= '<div style="clear:both"></div>';

            $html .= '<label>Y Rotate</label>';
            $html .= RWMB_Number_Field::html($html, $meta['yrotate'], self::refactor_field( $field, 'yrotate' ));
            $html .= '<div style="clear:both"></div>';

            $html .= '<label>Z Rotate</label>';
            $html .= RWMB_Number_Field::html($html, $meta['zrotate'], self::refactor_field( $field, 'zrotate' ));
            $html .= '<div style="clear:both"></div>';
            $html .= '<hr>';


            return $html;


		}

        static function refactor_field( $field,$variable_name )
        {
            $pattern = '/(.*)\[(.*)\]/';
            preg_match($pattern, $field['field_name'], $matches);
            $match = $matches[2];
            $field['field_name'] .= "[$variable_name]";
            $field['id'] .= "[$match][$variable_name]";
            return $field;
        }


		/**
		 * Normalize parameters for field
		 *
		 * @param array $field
		 *
		 * @return array
		 */
		static function normalize_field( $field )
		{
			$field = wp_parse_args( $field, array(
				'size' => 30,
			) );
			return $field;
		}

        static function meta( $meta, $post_id, $saved, $field )
        {
            $meta = get_post_meta( $post_id, $field['id'], !$field['multiple'] );

            // Use $field['std'] only when the meta box hasn't been saved (i.e. the first time we run)
            $meta = ( !$saved && '' === $meta || array() === $meta ) ? $field['std'] : $meta;

            // Escape attributes for non-wysiwyg fields
           // if ( 'wysiwyg' !== $field['type'] )
           //     $meta = is_array( $meta ) ? array_map( 'esc_attr', $meta ) : esc_attr( $meta );

            return $meta;
        }


        static function get_impress_page_options( $args='' ) {
            $defaults = array(
            'depth' => 0, 'child_of' => 0,
            'selected' => 0, 'echo' => 0,
            'name' => 'page_id', 'id' => '',
            'show_option_none' => '', 'show_option_no_change' => '',
            'option_none_value' => ''
            );

            $r = wp_parse_args( $args, $defaults );
            extract( $r, EXTR_SKIP );

            $pages = get_pages($r);
            $options = array();
            foreach($pages as $page) {
               // $options[2] = 'post_title';
                $pid = $page->ID;
                $options[$pid] = $page->post_title;
            }




            //$output .= walk_page_dropdown_tree($pages, $depth, $r);

          //  $output = apply_filters('wp_dropdown_pages', $output);


            return $options;
        }





        static function get_impress_post_options( $args='' ) {
          //  $defaults = array(
          //      'depth' => 0, 'child_of' => 0,
          //      'selected' => 0, 'echo' => 0,
          //      'name' => 'page_id', 'id' => '',
          //      'show_option_none' => '', 'show_option_no_change' => '',
          //      'option_none_value' => ''
          //  );

          //  $r = wp_parse_args( $args, $defaults );
          //  extract( $r, EXTR_SKIP );

            $pages = get_posts();
            $options = array();
            foreach($pages as $page) {
                // $options[2] = 'post_title';
                $pid = $page->ID;
                $options[$pid] = $page->post_title;
            }




            //$output .= walk_page_dropdown_tree($pages, $depth, $r);

            //  $output = apply_filters('wp_dropdown_pages', $output);


            return $options;
        }








	}
}