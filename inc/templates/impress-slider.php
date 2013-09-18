<?php

/**
 * Main fullscreen template
 */

?>

<div id="impress">

<?php

/** Get some Options via global */
global $IMPRESS_Options;
$y = $IMPRESS_Options;
$_posttype = $y->get('post_type_select');
$effect = $y->get('effect');



/** Setup some presets */
$i = 0;
$j = 1;
$row = 0;
$spc = 1200;


/** Setup some presets */
$xd = $y->get('xdis') ? $y->get('xdis') : 0;
$yd = $y->get('ydis') ? $y->get('ydis') : 0;
$zd = $y->get('zdis') ? $y->get('zdis') : 0;
$xr = $y->get('xrot') ? $y->get('xrot') : 0;
$yr = $y->get('yrot') ? $y->get('yrot') : 0;
$zr = $y->get('zrot') ? $y->get('zrot') : 0;
$counter = 20;

/** preset the stage vars */
$stage_width = 2000;
$stage_height = 2000;

/** What post type are we showing? */
if(!$_posttype){
    $_posttype = 'impress';
    $args = array( 
        'post_type' => $_posttype, 
        'posts_per_page' => $counter,
        'orderby' => 'menu_order',
        'order' => 'ASC'
    );
}elseif($_posttype == 'attachment'){
    $args = array( 
        'post_type' => 'attachment',
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'post_mime_type' => 'image', 
        'post_status' => null, 
        'post_parent' => null 
    );
}else{
    $args = array( 
        'post_type' => $_posttype, 
        'posts_per_page' => $counter,
        'orderby' => 'menu_order',
        'order' => 'ASC'
    );
}

/** query */
$loop = new WP_Query( $args );

/** Want to show media? We can */
if ($_posttype == 'attachment') {
    $attachments = get_posts($args);
    if ($attachments) {
        foreach ($attachments as $attach) {
/*
$xpos = $i*$xd;
$ypos = $i*$yd;
$zpos = $i*$zd;
$xrot = $i*$xr;
$yrot = $i*$yr;
$zrot = $i*$zr;
*/

/** GRID */

            if($i % 2 == 0){
                $row++;
                $xpos = $spc;
                $j = 1;
            }else{
                $xpos = $j*$spc;
            }
            $ypos = $row*$spc;
            $zpos = 0;
            $xrot = 0;
            $yrot = 0;
            $zrot = 0;

            $j++;

            $scale = 1;

            echo '  <div id="attachment-'.$i.'" class="step img" data-x="'.$xpos.'" data-y="'.$ypos.'" data-z="'.$zpos.'" data-scale="'.$scale.'" data-rotate-x="'.$xrot.'"  data-rotate-y="'.$yrot.'"  data-rotate-z="'.$zrot.'">'."\n";

            $image = wp_get_attachment_url( $attach->ID , false );
            echo '<img src="'.$image.'" title="'.get_the_title().'" />'."\n";
            $counter--;

            echo '  </div>'."\n";
            echo ''."\n";

            $i++;
        }
    }
}else{

    while ( $loop->have_posts() ) : $loop->the_post();
        $_id = get_the_ID();

        $slug = basename(get_permalink());
        $slides = get_post_meta( $_id, 'IMPRESS_slide', false );
        $slideclass = get_post_meta( $_id, 'IMPRESS_class', true );
        $override = get_post_meta( $_id, 'IMPRESS_override', true );
        $showtitle = get_post_meta( $_id, 'IMPRESS_title', true );
        if($override == 1) {
            $xpos = get_post_meta( $_id, 'IMPRESS_xpos', true );
            $ypos = get_post_meta( $_id, 'IMPRESS_ypos', true );
            $zpos = get_post_meta( $_id, 'IMPRESS_zpos', true );
            $xrot = get_post_meta( $_id, 'IMPRESS_xrotate', true );
            $yrot = get_post_meta( $_id, 'IMPRESS_yrotate', true );
            $zrot = get_post_meta( $_id, 'IMPRESS_zrotate', true );
            $scale = get_post_meta( $_id, 'IMPRESS_scale', true );
        } else {

            $xpos = $i*$xd;
            $ypos = $i*$yd;
            $zpos = $i*$zd;
            $xrot = $i*$xr;
            $yrot = $i*$yr;
            $zrot = $i*$zr;

/** GRID */
/*
if($i % 2 == 0){
    $row++;
    $xpos = $spc;
    $j = 1;
}else{
    $xpos = $j*$spc;
}
$ypos = $row*$spc;

$j++; 
*/
            $scale = 1;
        }

        $spdata = Array(
            'IMPRESS_xpos' => $xpos,
            'IMPRESS_ypos' => $ypos,
            'IMPRESS_zpos' => $zpos,
            'IMPRESS_xrotate' => $xrot,
            'IMPRESS_yrotate' => $yrot,
            'IMPRESS_zrotate' => $zrot,
            'IMPRESS_scale' => $scale
        );
        foreach ( $spdata as $k => $v ) {
            update_post_meta( $_id, $k, $v );
        }
        foreach ( $slides as $slide ) {
            $slideclass = $slide;
        }

        remove_filter ('the_content','wpautop');
        $content = apply_filters('the_content', get_the_content());

        //$content = wp_kses($content, $IMPRESS_Options->field['allowed_html']);
        

        echo '<div id="i-'.$slug.'" class="step '.$slideclass.'" data-x="'.$xpos.'" data-y="'.$ypos.'" data-z="'.$zpos.'" data-scale="'.$scale.'" data-rotate-x="'.$xrot.'"  data-rotate-y="'.$yrot.'"  data-rotate-z="'.$zrot.'">'."\n";
        if($showtitle == 1){
            echo '<h1>'.get_the_title().'</h1>'."\n";
        }
        echo $content ."\n";
        echo '</div>'."\n";
        echo ''."\n";



        $i++;

        $stage_width = ($xpos + $spc)*2;
        $stage_height = ($ypos + $spc)*4;
    endwhile;
}
$slideclass = '';

/** Load the background */
if($effect != 'none') {
    switch($effect) {
        case 'bokeh':
            $stage = array('bokeh-L1.png','bokeh-L2.png');
            break;
        case 'plaid':
            $stage = array('plaid.png');
            break;
        case 'boxes':
            $stage = array('boxes.png', 'boxes2.png');
            break;
        case 'hexagons':
            $stage = array('hexagon-01.png','hexagon-02.png');
            break;
        case 'stars':
            $stage = array('stars-1.png','stars-2.png');
            break;
        case 'star-color':
            $stage = array('stars-color-1.png','stars-color-2.png');
            break;
    }
    $idx = 0;
    shuffle($stage);
    foreach($stage as $layer) {
        $zpos = ($idx*-2000)-3000;
        $xpos = 0;
        $ypos = 0;
        $scale = ($idx*2)+1;
        echo '<div class="stage" data-x="'.$xpos.'" data-y="'.$ypos.'" data-z="'.$zpos.'" data-scale="'.$scale.'" data-rotate-x="0"  data-rotate-y="0"  data-rotate-z="0" style="background:url('.IMPRESS_URL.'img/'.$layer.');width:'.$stage_width.'px;height:'.$stage_height.'px;">&nbsp;</div>'."\n";
        $idx++;
    }
}

    ?>
</div>
