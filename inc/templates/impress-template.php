<?php

/**
 * Main fullscreen template
 */

/** Get some Options via global */
global $IMPRESS_Options;
$y = $IMPRESS_Options;

/** Set options */
$c = $y->get('type_color');
$grad_arr = $y->get('color_gradient');
$header = $y->get('header');
$footer = $y->get('footer2');
$type = $y->get('typeface');

/** Gradient Color Array */
$f = $grad_arr['from'];
$t = $grad_arr['to'];
$s = $grad_arr['type'];

/** Start HTML */
?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=1024" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <title></title>
    
    <meta name="description" content="Impress.js for Wordpress" />
    <meta name="author" content="http://hanusek.com/impress" />

    <link href="<?php echo IMPRESS_CSS_URL; ?>style.css" rel="stylesheet" />
    <link href="<?php echo IMPRESS_CSS_URL; ?>vendor/bootstrap.min.css" rel="stylesheet" />
    
    <link rel="shortcut icon" href="favicon.png" />
    <link rel="apple-touch-icon" href="<?php echo IMPRESS_URL; ?>img/icons/Icon-72@2x.png" />
<?php 

/** Google Fonts ----NEEDS WORK */
$subs = explode(":", $type);
$type = urlencode ( $type );
if($subs) {
    echo '<link href="http://fonts.googleapis.com/css?family='.$type.'" rel="stylesheet" type="text/css">'."\n";
}

echo '<style>'."\n";
echo 'a {'."\n";
echo 'color: '.$c.';'."\n";
echo '}'."\n";
echo 'body {'."\n";
echo 'color: '.$c.';'."\n";


if($s == 1) {
    echo 'background: '.$t.';'."\n";
    echo 'background: '.PREFIX.'linear-gradient(top, '.$f.' 0%, '.$t.' 100%);'."\n";
    echo 'background: '.PREFIX.'gradient(linear, left top, left bottom, color-stop(0%,'.$f.'), color-stop(100%, '.$t.'));'."\n";
    echo 'background: linear-gradient(to bottom,  '.$f.' 0%, '.$t.' 100%);'."\n";
}else{
    echo 'background: '.$t.';'."\n";
    echo 'background: '.PREFIX.'radial-gradient(center, ellipse cover,  '.$f.' 0%, '.$t.' 100%);'."\n";
    echo 'background: '.PREFIX.'gradient(radial, center center, 0px, center center, 100%, color-stop(0%,'.$f.'), color-stop(100%, '.$t.'));'."\n";
    echo 'background: radial-gradient(ellipse at center,  '.$f.' 0%, '.$t.' 100%);'."\n";
}

echo '}'."\n";
echo '#impress, b {'."\n";
echo 'font-family: "'.$subs[0].'";'."\n";
if (is_int($subs[1])) {
    echo 'font-weight: '.$subs[1].';'."\n";
}else{
    echo 'font-style: '.$subs[1].';'."\n";
}
echo '}'."\n";
echo '</style>'."\n";
do_action('impress_head');
?>


</head>
<body class="impress-not-supported">
<header><?php echo $header; ?></header>

<div class="fallback-message">
    <p>Your browser <b>doesn't support the features required</b> by impress.js, so you are presented with a simplified version of this presentation.</p>
    <p>For the best experience please use the latest <b>Chrome</b>, <b>Safari</b> or <b>Firefox</b> browser.</p>
</div>

<?php 
require_once IMPRESS_INC_DIR . "templates/impress-slider.php";
?>

<div class="hint">
    <p>Use a spacebar or arrow keys to navigate</p>
</div>


<footer><?php echo $footer; ?></footer>
<?php 


echo '<script type="text/javascript" src="'. IMPRESS_JS_URL . 'vendor/impress.js'.'?ver=0.1.0"></script>';
echo '<script>if ("ontouchstart" in document.documentElement) { document.querySelector(".hint").innerHTML = "<p>Tap on the left or right to navigate</p>"; }</script>';
echo '<script>impress().init();</script>';


?>
</body>
</html>