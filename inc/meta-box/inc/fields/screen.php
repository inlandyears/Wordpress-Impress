<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'RWMB_Screen_Field' ) )
{
	class RWMB_Screen_Field
	{

		/**
		 * Enqueue scripts and styles
		 *
		 * @return void
		 */
		static function admin_enqueue_scripts()
		{
			wp_enqueue_script( 'rwmb-screen', RWMB_JS_URL . 'screen.js', array( ), RWMB_VER, true );
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

			global $post;

			return '<iframe src="'. get_permalink($post->ID) .'" width="100%" height="1" id="screen_frame" frameborder="0"></iframe>';
		}
	}
}