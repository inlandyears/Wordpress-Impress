<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'RWMB_Slide_Field' ) )
{
	class RWMB_Slide_Field
	{
		/**
		 * Enqueue scripts and styles
		 *
		 * @return void
		 */
		static function admin_enqueue_scripts()
		{
			wp_enqueue_style( 'rwmb-meta-box-wysiwyg', RWMB_CSS_URL . 'wysiwyg.css', array(), RWMB_VER );
			wp_enqueue_style( 'rwmb-impress', RWMB_CSS_URL . 'impress.css', array(), RWMB_VER );
		}


		/**
		 * Add field actions
		 *
		 * @return void
		 */
		static function add_actions()
		{
			// Add TinyMCE script for WP version < 3.3
			global $wp_version;

			if ( version_compare( $wp_version, '3.2.1' ) < 1 )
			{
				add_action( 'admin_print_footer-post.php', 'wp_tiny_mce', 25 );
				add_action( 'admin_print_footer-post-new.php', 'wp_tiny_mce', 25 );
			}
		}

		/**
		 * Change field value on save
		 *
		 * @param mixed $new
		 * @param mixed $old
		 * @param int   $post_id
		 * @param array $field
		 *
		 * @return string
		 */
		static function value( $new, $old, $post_id, $field )
		{
			return wpautop( $new );
		}

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
			global $wp_version;


			return sprintf(
				'<div class="slide">
					<input type="text" class="slideTitle" name="%s" id="%s" value="%s" />

				</div>',

				// Slide Title
				$field['field_name'],
				empty( $field['clone'] ) ? $field['id'] : '',
				$meta


			);

			
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
				'step' => 1,
				'min'  => 0,
			) );

			$field['options'] = wp_parse_args( $field['options'], array(
				'editor_class' => 'rwmb-wysiwyg',
				'dfw'          => true, // Use default WordPress full screen UI
			) );



			// Keep the filter to be compatible with previous versions
			$field['options'] = apply_filters( 'rwmb_wysiwyg_settings', $field['options'] );

			return $field;
		}
	}
}