<?php

session_start();

function getRandomWord($len = 6) {
    $word = array_merge(range('0', '9'),range('A','H'),range('i','z'));
    shuffle($word);
    return substr(implode($word), 0, $len);
}

function draw_random_lines($image, $width, $height, $linecolor) {
    for ($i = 0; $i < 2; $i++) { // Number of lines to draw
        $x1 = rand(0, $width);
        $y1 = rand(0, $height);
        $x2 = rand(0, $width);
        $y2 = rand(0, $height);
        imageline($image, $x1, $y1, $x2, $y2, $linecolor);
    }
}

$path_name = dirname(__FILE__) . '/fonts/arial.ttf';

$ranStr = getRandomWord();
$_SESSION["vercode"] = $ranStr;

$height = 40; //CAPTCHA image height
$width = 140; //CAPTCHA image width
$font_size = 20; 
$image_p = imagecreate($width, $height);
$graybg = imagecolorallocate($image_p, 41, 73, 132);
$textcolor = imagecolorallocate($image_p, 255, 255, 0);
$linecolor = imagecolorallocate($image_p, 255, 255, 255); // Gray color for zigzag lines
draw_random_lines($image_p, $width, $height, $linecolor);

imagefttext($image_p, $font_size, -2, 20, 26, $textcolor, $path_name, $ranStr);
//imagestring($image_p, $font_size, 5, 3, $ranStr, $white);
imagepng($image_p);

?>