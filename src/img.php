<?
   //  ini_set('error_reporting', E_ALL);
    // ini_set('display_errors', true);
///require_once 'system/system.php';
header('content-type: image/jpeg');
header('Pragma: public');
header('Cache-Control: max-age=86400');
header('Expires: '. gmdate('D, d M Y H:i:s \G\M\T', time() + 86400*365));
if(isset($_GET['url'])){
$url=$_GET['url'];
//	$img = file_get_contents('http://warsking.mobi/style/images/body/pets_duel.png');
// $image = imageCreateFromPng($img);
// imageJpeg($img);
 
/*function logo($i,$l){
// изображение
$image = imagecreatefromjpeg($i);
// ширина изображения
$image_width = imagesx($image);
// высота изображения
$image_height = imagesy($image);
// логотип
$logo = imagecreatefrompng($l);
// ширина логотипа
$logo_width = imagesx($logo);
// высота логотипа
$logo_height = imagesy($logo);
// Позиция лого
$image_x = $image_width - $logo_width - 10;
$image_y = $image_height - $logo_height - 10;
imagecopy($image, $logo, $image_x, $image_y, 0, 0, $logo_width, $logo_height);
// Освобождаем память изображения-логотипа
imagedestroy($logo);
// Перезаписываем изображение
imagejpeg($image, $i);
}

	

logo("img/r12.jpg".$filename, "watermark.png");

*/

//$img = file_get_contents('http://warsking.mobi/style/images/body/home_m.png');
//file_put_contents('img.png', $img);
//$img = imageCreateFromPng($url);
//magejpeg($img);
$image = imagecreatefromjpeg($url);
// ширина изображения
$image_width = imagesx($image);
// высота изображения
$image_height = imagesy($image);
// логотип
$logo = imagecreatefrompng('./logo.png');
// ширина логотипа
$logo_width = imagesx($logo);
// высота логотипа
$logo_height = imagesy($logo);
 
// Размещение в правом нижнем углу с отступом в 10 пикселей
$image_x = $image_width - $logo_width - 10;
$image_y = $image_height - $logo_height - 10;
imagecopy($image, $logo, $image_x, $image_y, 0, 0, $logo_width, $logo_height);
 
// Освобождаем память изображения-логотипа
imagedestroy($logo);

imagejpeg($image);
}else{
$img = imageCreateFromPng('./logo.png');
imagejpeg($img);
}
?>