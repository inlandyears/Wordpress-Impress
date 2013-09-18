<?php

/**
 * impress_shortcode
 */


/** Lets do some shortcode functions */





function impress_program_animation()
{
    global $wp_query;  
    $args = array(
    'post_type'=> 'impress',
    'posts_per_page' => 20,
    'orderby' => 'menu_order',
    'order' => 'ASC'
    );
    // The Query
    $the_query = new WP_Query( $args );

    $rotate; 
    $fdin = 0; 
    $fdout = 0;
    $count = 1;
    $regex_pattern = get_shortcode_regex();

    echo '<style>'."\n"."\n";

    // The Loop
    while ( $the_query->have_posts() ) : $the_query->the_post();


        if(preg_match_all ('/'.$regex_pattern.'/s', get_the_content(), $regex_matches)){
            
            $index = 1;
            foreach($regex_matches[2] as $anmiate){
                if($anmiate == 'animate'){

                    $attribureStr = str_replace (" ", "&", trim ($regex_matches[3][$index-1]));
                    $attribureStr = str_replace ('"', '', $attribureStr);

                    //  Parse the attributes
                    $defaults = array (
                        'rotx_start'    => '0',
                        'rotx'          => '0',
                        'roty_start'    => '0',
                        'roty'          => '0',
                        'rotz_start'    => '0',
                        'rotz'          => '0',
                        'scale_start'   => '1',
                        'scale'         => '1',
                        'posx_start'    => '0',
                        'posx'          => '0',
                        'posy_start'    => '0',
                        'posy'          => '0',
                        'posz_start'    => '0',
                        'posz'          => '0',
                        'duration'      => '0.6',
                        'delay'         => '0',
                        'effect'        => '0',
                        'reset'         => '1',
                    );

                    $attributes = wp_parse_args ($attribureStr, $defaults);

                    if(isset($attributes['fadein'])){
                        $fadein = true;
                    }
                    if(isset($attributes['fadeout'])){
                        $fadeout = true;
                    }

                    $rotatex_start = 'rotateX('.$attributes["rotx_start"].'deg)';
                    $rotatey_start = 'rotateY('.$attributes["roty_start"].'deg)';
                    $rotatez_start = 'rotateZ('.$attributes["rotz_start"].'deg)';
                    $scale_start = 'scale3d('.$attributes["scale_start"].','.$attributes["scale_start"].',1)';
                    $posx_start = $attributes["posx_start"];
                    $posy_start = $attributes["posy_start"];
                    $posz_start = $attributes["posz_start"];

                    $translate3d_start = 'translate3d('.$posx_start.'px, '.$posy_start.'px, '.$posz_start.'px)';

                    $rotatex = 'rotateX('.$attributes["rotx"].'deg)';
                    $rotatey = 'rotateY('.$attributes["roty"].'deg)';
                    $rotatez = 'rotateZ('.$attributes["rotz"].'deg)';
                    $scale = 'scale3d('.$attributes["scale"].','.$attributes["scale"].',1)';
                    $posx = $attributes["posx"];
                    $posy = $attributes["posy"];
                    $posz = $attributes["posz"];

                    $translate3d = 'translate3d('.$posx.'px, '.$posy.'px, '.$posz.'px)';
                                        
                    $do_count = '.count-'.$count;


                    if(SUPPORTED) {
                        echo '#i-'.basename(get_permalink()).' .animate'.$do_count.'{'."\n";
                        //echo '-webkit-transform-origin: 50% 50% 50%;'."\n";
                        //echo '-webkit-transform: perspective(0);'."\n";

                        echo PREFIX.'transform: '.$rotatex_start.' '.$rotatey_start.'  '.$rotatez_start.' '.$scale_start.' '.$translate3d_start.';'."\n";
                        if($fadein) {
                            echo 'transition-property: all;'."\n";
                            echo PREFIX.'transition-property: all;'."\n";
                            echo 'opacity:0;'."\n";
                        }
                        if($fadeout) {
                            echo 'transition-property: all;'."\n";
                            echo PREFIX.'transition-property: all;'."\n";
                            echo 'opacity:1;'."\n";
                        }
                        echo '}'."\n"."\n";
                    }

                    if(!$attributes["reset"]){
                        $reset = ', #'.basename(get_permalink()).'.past .animate'.$do_count;
                    }

                    echo '#i-'.basename(get_permalink()).'.present .animate'.$do_count.' '.$reset.'{'."\n";
                    //echo '-webkit-transform-origin: 50% 50% 50%;'."\n";
                    //echo '-webkit-transform: perspective(0);'."\n";

                    echo PREFIX.'transform: '.$rotatex.' '.$rotatey.'  '.$rotatez.' '.$scale.' '.$translate3d.';'."\n";

                    if (isset ($attributes['duration'])) {
                        $duration = $attributes['duration'];
                        echo PREFIX.'transition-duration: '.$duration.'s;'."\n";
                    }
                    if (isset ($attributes['delay'])) {
                        $delay = $attributes['delay'];
                        echo PREFIX.'transition-delay: '.$delay.'s;'."\n";
                    }
                    if (isset ($attributes['effect'])) {
                        $effect = $attributes['effect'];
                        echo PREFIX.'transition-timing-function: '.$effect.';'."\n";
                    }
                    if($fadein) {
                        echo 'opacity:1;'."\n";
                    }
                    if($fadeout) {
                        echo 'opacity:0;'."\n";
                    }
                    echo '}'."\n"."\n";
                    $index++;
                    $count++;
                }
                
            }
        }
    endwhile;

    // Reset Post Data
    wp_reset_postdata();
    echo '</style>'."\n";
    
}
add_action( 'impress_head', 'impress_program_animation' );



if (!function_exists('impress_shortcode')) {
    function impress_shortcode( $atts ){

        echo '<div class="fallback-message">'."\n";
        echo '<p>Your browser <b>doesn\'t support the features required</b> by impress.js, so you are presented with a simplified version of this presentation.</p>'."\n";
        echo '<p>For the best experience please use the latest <b>Chrome</b>, <b>Safari</b> or <b>Firefox</b> browser.</p>'."\n";
        echo '</div>'."\n";

        echo '<div id="wrap">'."\n";
        require_once IMPRESS_INC_DIR . "impress-slider.php";
        echo '</div>'."\n";

        echo '<div class="hint">'."\n";
        echo '<p>Use a spacebar or arrow keys to navigate</p>'."\n";
        echo '</div>'."\n";
    }
}

if (!function_exists('map_shortcode')) {
    function map_shortcode( $atts ) {
        extract( shortcode_atts( array(
            'width' => '640',
            'height' => '384',
            'zoom' => '12',
            'type' => '',
            'lat' => '', 
            'long' => '', 
        ), $atts ) );
        return '<iframe width="'.esc_attr($width).'" height="'.esc_attr($height).'" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/?ie=UTF8&amp;t=m&amp;ll='.esc_attr($lat).','.esc_attr($long).'&amp;spn=0.095002,0.145912&amp;z='.esc_attr($zoom).'&amp;output=embed"></iframe>';
    }
}

if (!function_exists('image_shortcode')) {
    function image_shortcode( $atts ) {
        extract( shortcode_atts( array(
            'src' => '',
            'width' => '400',
            'height' => '300',
            'caption' => '',
            'align' => '',
            'alt' => 'Image'
        ), $atts ) );

        $img = '<img src="'.esc_attr($src).'" class="align'.esc_attr($align).'" style="width:'.esc_attr($width).'px; height:'.esc_attr($height).'px;" alt="'.esc_attr($alt).'" />';
        if (esc_attr($caption)) {
            return '<div class="wp-caption align'.esc_attr($align).'">'.$img.'<p class="wp-caption-text">'.esc_attr($caption).'</p></div>';
        } else {
            return $img;
        }
    }
}

if (!function_exists('youtube_shortcode')) {
    function youtube_shortcode( $atts ) {
        extract( shortcode_atts( array(
            'width' => '640',
            'height' => '384',
            'code' => '',
        ), $atts ) );
        return '<iframe title="YouTube video player" width="'.esc_attr($width).'" height="'.esc_attr($height).'" src="http://www.youtube.com/embed/'.esc_attr($code).'?theme=dark&amp;rel=0&amp;wmode=transparent" frameborder="0" allowfullscreen=""></iframe>';
    }
}

if (!function_exists('vimeo_shortcode')) {
    function vimeo_shortcode( $atts ) {
        extract( shortcode_atts( array(
            'width' => '640',
            'height' => '384',
            'code' => '',
        ), $atts ) );
        return '<iframe src="http://player.vimeo.com/video/'.esc_attr($code).'?title=0&amp;byline=0&amp;portrait=0" width="'.esc_attr($width).'" height="'.esc_attr($height).'" frameborder="0"></iframe>';
    }
}

if (!function_exists('video_shortcode')) {
    function video_shortcode( $atts ) {
        extract( shortcode_atts( array(
            'width' => '640',
            'height' => '384',
            'mp4_src' => '',
            'ogg_src' => '',
        ), $atts ) );
        $op = '<video width="'.esc_attr($width).'" height="'.esc_attr($height).'" poster="" controls>'."\n";
        $op .= '<source src="'.esc_attr($mp4_src).'" type="video/mp4" />'."\n";
        $op .= '<source src="'.esc_attr($ogg_src).'" type="video/ogg" />'."\n";
        $op .= '<object width="'.esc_attr($width).'" height="'.esc_attr($width).'" type="application/x-shockwave-flash" data="/player.swf?image=placeholder.jpg&file='.esc_attr($mp4_src).'">'."\n";
        $op .= '<param name="movie" value="path/to/swf/player.swf?image=placeholder.jpg&file='.esc_attr($mp4_src).'" />'."\n";
        $op .= '</object>'."\n";
        $op .= '</video>'."\n";

        return $op;
    }
}

if (!function_exists('column_shortcode')) {
    function column_shortcode( $atts ) {
        extract( shortcode_atts( array(
            'size' => '',
        ), $atts ) );

        $img = '<img src="'.esc_attr($src).'" class="align'.esc_attr($align).'" />';
        if (esc_attr($caption)) {
            return '<div class="wp-caption align'.esc_attr($align).'">'.$img.'<p class="wp-caption-text">'.esc_attr($caption).'</p></div>';
        } else {
            return $img;
        }
    }
}

if (!function_exists('row_start_shortcode')) {
    function row_start_shortcode( $atts ) {
        extract( shortcode_atts( array( 'type' => ''), $atts ) );
        $class =  esc_attr($offset) ? esc_attr($offset) : 'row-fluid';
        //$class="row-fluid";
        return '<div class="'.$class.'">'; 
    }
}

if (!function_exists('row_end_shortcode')) {
    function row_end_shortcode( $atts ) { return '</div><!-- end row -->'."\n"; }
}

if (!function_exists('column_half_shortcode')) {
    function column_half_shortcode( $atts, $content = null ) {
        $op = '';
        extract( shortcode_atts( array(
            'offset' => '',
        ), $atts ) );
        $xs =  'offset'.esc_attr($offset);
        $op = '<div class="span6 '.$xs.'">'."\n";
        $op .= $content."\n";
        $op .= '</div>'."\n";
        return $op;
    }
}

if (!function_exists('column_third_shortcode')) {
    function column_third_shortcode( $atts, $content = null ) {
        $op = '';
        extract( shortcode_atts( array(
            'offset' => '',
        ), $atts ) );
        $xs =  'offset'.esc_attr($offset);
        $op = '<div class="span4 '.$xs.'">'."\n";
        $op .= $content."\n";
        $op .= '</div>'."\n";
        return $op;
    }
}

if (!function_exists('column_third_shortcode')) {
    function column_quarter_shortcode( $atts, $content = null ) {
        $op = '';
        extract( shortcode_atts( array(
            'offset' => '',
        ), $atts ) );
        $xs =  'offset'.esc_attr($offset);
        $op = '<div class="span3 '.$xs.'">'."\n";
        $op .= $content."\n";
        $op .= '</div>'."\n";
        return $op;
    }
}

if (!function_exists('column_sixth_shortcode')) {
    function column_sixth_shortcode( $atts, $content = null ) {
        $op = '';
        extract( shortcode_atts( array(
            'offset' => '',
        ), $atts ) );
        $xs =  'offset'.esc_attr($offset);
        $op = '<div class="span2 '.$xs.'">'."\n";
        $op .= $content."\n";
        $op .= '</div>'."\n";
        return $op;
    }
}

if (!function_exists('column_twelfth_shortcode')) {
    function column_twelfth_shortcode( $atts, $content = null ) {
        $op = '';
        extract( shortcode_atts( array(
            'offset' => '',
        ), $atts ) );
        $xs =  'offset'.esc_attr($offset);
        $op = '<div class="span1 '.$xs.'">'."\n";
        $op .= $content."\n";
        $op .= '</div>'."\n";
        return $op;
    }
}

if (!function_exists('animate_shortcode')) {
    function animate_shortcode( $atts, $content = null ) {
        static $count = 1;
        extract( shortcode_atts( array(
            'align' => '',
            'style' => '',
        ), $atts ) );
        if (esc_attr($style) != ''){
            $style = 'style="'.esc_attr($style).'"';
        }
        $op = '<span class="animate count-'.$count.' align'.esc_attr($align).'" '.$style.'>'."\n";
        $op .= do_shortcode($content)."\n";
        $op .= '</span>'."\n";
        $count++;
        return $op;
    }
}

/** Lets add our shortcodes */
add_shortcode( 'animate', 'animate_shortcode' );


/** Media shortcodes */
add_shortcode( 'map', 'map_shortcode' );
add_shortcode( 'image', 'image_shortcode' );
add_shortcode( 'youtube', 'youtube_shortcode' );
add_shortcode( 'vimeo', 'vimeo_shortcode' );
add_shortcode( 'video', 'video_shortcode' );

/** Bootsrtap Scaffolding */
add_shortcode( 'row_start', 'row_start_shortcode' );
add_shortcode( 'column_half', 'column_half_shortcode' );
add_shortcode( 'column_third', 'column_third_shortcode' );
add_shortcode( 'column_quarter', 'column_quarter_shortcode' );
add_shortcode( 'column_sixth', 'column_sixth_shortcode' );
add_shortcode( 'column_twelfth', 'column_twelfth_shortcode' );
add_shortcode( 'row_end', 'row_end_shortcode' );



?>