<?php

header("Content-type: image/png");

$image = ImageCreate(250, 40);

$color['1'] = ImageColorAllocate($image, 255, 255, 255); 
$color['2'] = ImageColorAllocate($image, 169, 169, 169); 
$color['3'] = ImageColorAllocate($image, 220, 220, 220); 

ImageFilledRectangle($image, 0, 0, 300, 50, $color['1']);
ImageFilledArc($image, 25, 25, 40, 40, 180, 360, $color['3'], 1);
imageellipse($image, 140, 20, 30,  30, $color['2']); 

ImageTTFText($image, rand(25,30), 0, rand(25,30), rand(30,35), $color['3'], './arial.ttf', 'test'); 

Imagepng($image); 
ImageDestroy($image); 

?>
