<?php

// Custom Post Types
add_action( 'init', 'create_post_type' );
function create_post_type() {
	register_post_type( 'impress',
		array(
			'labels' => array(
				'name' => __( 'Impress' ),
				'singular_name' => __( 'Impress Block' ),
				'add_new' => __( 'Add New' ),
				'add_new_item' => __( 'Add New Block' ),
				'edit' => __( 'Edit' ),
				'edit_item' => __( 'Edit Block' ),
				'new_item' => __( 'New Block' ),
				'view' => __( 'View Block' ),
				'view_item' => __( 'View Block' ),
				'search_items' => __( 'Search Blocks' ),
				'not_found' => __( 'No Blocks found' ),
				'not_found_in_trash' => __( 'No Blocks found in Trash' ),
				'parent' => __( 'Parent Block' )
			),
		'description' => __( 'A Impress Block is a type of content that is the most wonderful content in the world. There are no alternatives that match how insanely creative and beautiful it is.' ),
		'menu_icon' => IMPRESS_URL . '/img/impress.png',
		'public' => true,
		'show_ui' => true,
		'publicly_queryable' => true,
		'exclude_from_search' => false,
		'has_archive' => true,
		'menu_position' => 11,
		'show_in_nav_menus' => true,
		'supports' => array( 'title', 'editor', 'thumbnail' ),
		)
	);
}




?>
