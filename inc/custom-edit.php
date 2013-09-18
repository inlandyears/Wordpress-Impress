<?php

/**
 * Manipulate the edit.php for better list
 */


/** Settings for images */

add_theme_support('post-thumbnails');
add_image_size('featured_preview', 55, 55, true);


/** Get the featured image */

function impress_get_featured_image($post_ID){
	$post_thumbnail_id = get_post_thumbnail_id($post_ID);
		if ($post_thumbnail_id){
			$post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, 'featured_preview');
		return $post_thumbnail_img[0];
	}
}


/** Settings for images */

add_filter('manage_impress_posts_columns', 'impress_columns_head_only', 10);  
add_action('manage_impress_posts_custom_column', 'impress_columns_content_only', 10, 2);  

/** Column Headers */

function impress_columns_head_only($defaults) { 
	// Replace Originals
	/*
	return array(
	    'cb' => 'Image',
	    'title' => __('Title'),
	    'date' => __('Date'),
	    'publisher' => __('Publisher'),
	    'book_author' =>__( 'Book Author')
	); 
    */
    $defaults['featured_image'] = 'Image';  
    return $defaults;  
}

/** Columns */

function impress_columns_content_only($column_name, $post_ID) {  
	if ($column_name == 'featured_image') {
		$post_featured_image = impress_get_featured_image($post_ID);
		if ($post_featured_image){
			echo '<img src="' . $post_featured_image . '" style="width:55px;" />'; 
  		}
 	} 
}  

?>