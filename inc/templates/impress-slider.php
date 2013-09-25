<?php

/**
 * Main fullscreen template
 */

?>

<div id="impress">

<?php

/** Get some Options via global */
//global $IMPRESS_Options;
//$y = $IMPRESS_Options;

$effect = $impress_page_options['IMPRESS_effect'][0];



/** Setup some presets */
$i = 0;
$j = 1;
$row = 0;
$spc = 1200;


/** Setup some presets */
$xd = $impress_page_options['IMPRESS_xpos'][0] ? $impress_page_options['IMPRESS_xpos'][0] : 0;
$yd = $impress_page_options['IMPRESS_ypos'][0] ? $impress_page_options['IMPRESS_ypos'][0] : 0;
$zd = $impress_page_options['IMPRESS_zpos'][0] ? $impress_page_options['IMPRESS_zpos'][0] : 0;
$xr =  $impress_page_options['IMPRESS_xrotate'][0] ?  $impress_page_options['IMPRESS_xrotate'][0] : 0;
$yr =  $impress_page_options['IMPRESS_yrotate'][0] ?  $impress_page_options['IMPRESS_yrotate'][0] : 0;
$zr =  $impress_page_options['IMPRESS_zrotate'][0] ?  $impress_page_options['IMPRESS_zrotate'][0] : 0;
$counter = 20;

/** preset the stage vars */
$stage_width = 2000;
$stage_height = 2000;
$encoded_slides = $impress_page_options['IMPRESS_slide'][0];
$slides = unserialize( $encoded_slides );

/** query */
//$loop = new WP_Query( $args );


foreach($slides as $key=>$slide) {

        $_id = get_the_ID();
    //$impress_page_options


        $slug = basename(get_permalink());
        //$slides = get_post_meta( $_id, 'IMPRESS_slide', false );

        $slideclass = $impress_page_options['IMPRESS_class'][0];
        if( $slide['custom_class'] != '')
            $slideclass = $slide['custom_class'];

        $override =  $slide['override']; //get_post_meta( $_id, 'IMPRESS_override', true );
        $showtitle = $impress_page_options['IMPRESS_title'][0];
        $custom_title = $slide['content_title']; // get_post_meta( $_id, 'IMPRESS_title', true );
        if($override == 1) {
            $xpos = $slide['xpos'] ? $slide['xpos'] : 0; // get_post_meta( $_id, 'IMPRESS_xpos', true );
            $ypos = $slide['ypos'] ? $slide['ypos'] : 0;
            $zpos = $slide['zpos'] ? $slide['zpos'] : 0;
            $xrot = $slide['xrotate'] ? $slide['xrotate'] : 0;
            $yrot = $slide['yrotate'] ? $slide['yrotate'] : 0;
            $zrot = $slide['zrotate'] ? $slide['zrotate'] : 0;
            $scale = $slide['scale'] ? $slide['scale'] : 1;
        } else {

            $xpos = $i*$xd;
            $ypos = $i*$yd;
            $zpos = $i*$zd;
            $xrot = $i*$xr;
            $yrot = $i*$yr;
            $zrot = $i*$zr;
            $scale = 1;
        }

    /*
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
     */
      //  remove_filter ('the_content','wpautop');
      //  $content = apply_filters('the_content', get_the_content());
        $content = $slide['content'];
        //$content = wp_kses($content, $IMPRESS_Options->field['allowed_html']);
        

        echo '<div id="slide-'.$i.'" class="step '.$slideclass.'" data-x="'.$xpos.'" data-y="'.$ypos.'" data-z="'.$zpos.'" data-scale="'.$scale.'" data-rotate-x="'.$xrot.'"  data-rotate-y="'.$yrot.'"  data-rotate-z="'.$zrot.'">'."\n";
        if($showtitle == 1){
            echo '<h1>'.$custom_title.'</h1>'."\n";
        }
        echo $content ."\n";
        echo '</div>'."\n";
        echo ''."\n";



        $i++;

        $stage_width = ($xpos + $spc)*2;
        $stage_height = ($ypos + $spc)*4;

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
