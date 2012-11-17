
<div id="impress">

<?php
global $IMPRESS_Options;
$y = $IMPRESS_Options;
$_posttype = $y->get('post_type_select');

$i = 0;
$j = 1;
$row = 0;
$spc = 1200;
$xd = $y->get('xdis') ? $y->get('xdis') : 1000;
$yd = $y->get('ydis') ? $y->get('ydis') : 0;
$zd = $y->get('zdis') ? $y->get('zdis') : 0;
$xr = $y->get('xrot') ? $y->get('xrot') : 0;
$yr = $y->get('yrot') ? $y->get('yrot') : 0;
$zr = $y->get('zrot') ? $y->get('zrot') : 0;
$counter = 100;

if(!$_posttype){
    $_posttype = 'impress';
    $args = array( 
        'post_type' => $_posttype, 
        'posts_per_page' => $counter 
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
        'posts_per_page' => $counter 
    );
}
$loop = new WP_Query( $args );

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
            /* GRID  */

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
            
            /* GRID
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



        echo '  <div id="'.$slug.'" class="step '.$slideclass.'" data-x="'.$xpos.'" data-y="'.$ypos.'" data-z="'.$zpos.'" data-scale="'.$scale.'" data-rotate-x="'.$xrot.'"  data-rotate-y="'.$yrot.'"  data-rotate-z="'.$zrot.'">'."\n";
        if($showtitle == 1){
            echo '      <h1>'.get_the_title().'</h1>'."\n";
        }
        echo '      <p>',get_the_content().'</p>'."\n";
        echo '  </div>'."\n";
        echo ''."\n";



        $i++;
    endwhile;
}
$slideclass = '';

    ?>

</div>
