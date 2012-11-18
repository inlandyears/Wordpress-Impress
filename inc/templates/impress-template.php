<?php 
global $IMPRESS_Options;
$y = $IMPRESS_Options;
$c = $y->get('type_color');
$grad_arr = $y->get('color_gradient');
$header = $y->get('header');
$footer = $y->get('footer2');
$type = $y->get('typeface');

$f = $grad_arr['from'];
$t = $grad_arr['to'];
$s = $grad_arr['type'];
?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=1024" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <title></title>
    
    <meta name="description" content="" />
    <meta name="author" content="" />

    <link href="<?php echo IMPRESS_CSS_URL; ?>style.css" rel="stylesheet" />
    
    <link rel="shortcut icon" href="favicon.png" />
    <link rel="apple-touch-icon" href="apple-touch-icon.png" />
<?php 
    
$subs = substr($type, 0, strrpos($type, ":"));
if($type) {
    echo '<link href="http://fonts.googleapis.com/css?family='.$subs.'" rel="stylesheet" type="text/css">';
}

echo '<style>'."\n";
echo 'body {'."\n";
echo 'color: '.$c.';'."\n";

if($s == 1) {
    echo 'background: -moz-linear-gradient(top,  '.$f.' 0%, '.$t.');'."\n";
    echo 'background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,'.$f.'), color-stop(100%, '.$t.'));'."\n";
    echo 'background: -webkit-linear-gradient(top, '.$f.' 0%, '.$t.' 100%);'."\n";
    echo 'background: -o-linear-gradient(top, '.$f.' 0%, '.$t.' 100%);'."\n";
    echo 'background: -ms-linear-gradient(top, '.$f.' 0%, '.$t.' 100%);'."\n";
    echo 'background: linear-gradient(to bottom, '.$f.' 0%, '.$t.' 100%);'."\n";
    echo 'filter: progid:DXImageTransform.Microsoft.gradient( startColorstr="'.$f.'", endColorstr="'.$t.'",GradientType=0 );'."\n";
}else{
    echo 'background: -moz-radial-gradient(center, ellipse cover,  '.$f.' 0%, '.$t.');'."\n";
    echo 'background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%,'.$f.'), color-stop(100%, '.$t.'));'."\n";
    echo 'background: -webkit-radial-gradient(center, ellipse cover,  '.$f.' 0%, '.$t.' 100%);'."\n";
    echo 'background: -o-radial-gradient(center, ellipse cover,  '.$f.' 0%, '.$t.' 100%);'."\n";
    echo 'background: -ms-radial-gradient(center, ellipse cover,  '.$f.' 0%, '.$t.' 100%);'."\n";
    echo 'background: radial-gradient(ellipse at center,  '.$f.' 0%, '.$t.' 100%);'."\n";
    echo 'filter: progid:DXImageTransform.Microsoft.gradient( startColorstr="'.$f.'", endColorstr="'.$t.'",GradientType=1 );'."\n";
}
echo '}'."\n";
echo '#impress {'."\n";
echo "font-family: '".$subs."';"."\n";
echo '}'."\n";
echo '</style>'."\n";
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