<?php

/**
 * Sort Order Code
 */


/** Lets add the function for the new submenu */
add_action( 'admin_menu', 'impress_register_order_menu' );

/** Array for submenu and target function */
function impress_register_order_menu() {
  add_submenu_page(
    'edit.php?post_type=impress',
    'Order Blocks',
    'Order',
    'edit_pages', 'product-order',
    'impress_order_page'
  );
}

/** Page Target function for new submenu page */
function impress_order_page() {
?>
  <div class="wrap">
    <h2>Sort Products</h2>
    <p>Simply drag the product up or down and they will be saved in that order.</p>
  <?php $products = new WP_Query( array( 'post_type' => 'impress', 'posts_per_page' => -1, 'order' => 'ASC', 'orderby' => 'menu_order' ) ); ?>
  <?php if( $products->have_posts() ) : ?>

    <table class="wp-list-table widefat fixed posts" id="sortable-table">
      <thead>
        <tr>
          <th class="column-order">Order</th>
          <th class="column-thumbnail">Thumbnail</th>
          <th class="column-title">Title</th>
        </tr>
      </thead>
      <tbody data-post-type="product">
      <?php while( $products->have_posts() ) : $products->the_post(); ?>
        <tr id="post-<?php the_ID(); ?>">
          <td class="column-order"><img src="<?php echo IMPRESS_URL . 'img/move-icon.png'; ?>" title="" alt="Move Icon" width="30" height="30" class="" /></td>
          <td class="column-thumbnail"><?php the_post_thumbnail( 'thumbnail' ); ?></td>
          <td class="column-title"><strong><?php the_title(); ?></strong><div class="excerpt"><?php the_excerpt(); ?></div></td>
        </tr>
      <?php endwhile; ?>
      </tbody>
      <tfoot>
        <tr>
          <th class="column-order">Order</th>
          <th class="column-thumbnail">Thumbnail</th>
          <th class="column-title">Title</th>
        </tr>
      </tfoot>

    </table>

  <?php else: ?>

    <p>No blocks found, why not <a href="post-new.php?post_type=product">create one?</a></p>

  <?php endif; ?>
  <?php wp_reset_postdata(); // Don't forget to reset again! ?>

  <style>
    /* Dodgy CSS ^_^ */
    #sortable-table td { background: white; }
    #sortable-table .column-order { padding: 3px 10px; width: 50px; }
      #sortable-table .column-order img { cursor: move; }
    #sortable-table td.column-order { vertical-align: middle; text-align: center; }
    #sortable-table .column-thumbnail { width: 160px; }
  </style>

  </div><!-- .wrap -->

<?php

}


/** AJAX action called from js */
add_action( 'wp_ajax_impress_update_post_order', 'impress_update_post_order' );

/** AJAX fucntion */
function impress_update_post_order() {
  global $wpdb;

  $post_type     = $_POST['postType'];
  $order        = $_POST['order'];

  /**
  *    Expect: $sorted = array(
  *                menu_order => post-XX
  *            );
  */
  foreach( $order as $menu_order => $post_id )
  {
    $post_id         = intval( str_ireplace( 'post-', '', $post_id ) );
    $menu_order     = intval($menu_order);
    wp_update_post( array( 'ID' => $post_id, 'menu_order' => $menu_order ) );
  }

  die( '1' );
}

/** update the menu_order */
add_filter( 'wp_insert_post_data', 'my_wp_insert_post_data', 10, 2 );
function my_wp_insert_post_data( $data, $postarr ) {

  $post_type= 'impress';
  if ( $data[ 'post_type' ] == $post_type && get_post( $postarr[ 'ID' ] )->post_status == 'draft' ) {
    global $wpdb;
    $data[ 'menu_order' ]=$wpdb->get_var( "SELECT MIN(menu_order)+10 AS menu_order FROM {$wpdb->posts} WHERE post_type='{$post_type}'" );
  }
  return $data;
}

?>