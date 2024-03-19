<?php
#-Функция вывода картинки в зависимости от текущего здоровья-#
function img_zamki($health_max,$health_t){
$zamki_100 = $health_max;
$zamki_98 = round($health_max * 0.98, 0);
$zamki_95 = round($health_max * 0.95, 0);
$zamki_75 = round($health_max * 0.75, 0);
$zamki_60 = round($health_max * 0.60, 0);
$zamki_45 = round($health_max * 0.45, 0);
$zamki_30 = round($health_max * 0.30, 0);
$zamki_15 = round($health_max * 0.15, 0);

if($health_t <= $zamki_100){ $images = '/style/images/zamki/0.png';}
if($health_t <= $zamki_98){ $images = '/style/images/zamki/1.png';}
if($health_t <= $zamki_95){ $images = '/style/images/zamki/2.png';}
if($health_t <= $zamki_75){ $images = '/style/images/zamki/3.png';}	
if($health_t <= $zamki_60){ $images = '/style/images/zamki/4.png';}
if($health_t <= $zamki_45){ $images = '/style/images/zamki/5.png';}
if($health_t <= $zamki_30){ $images = '/style/images/zamki/6.png';}
if($health_t <= $zamki_15){ $images = '/style/images/zamki/7.png';}
return $images;
}

/**
 * 1 Локация 50 к параметрам
 * 2 Локация 100 к параметрам
 * 3 локация 150 к параметрам
 * 4 Локация 200 к параметрам
 * 5 Локация 250 к параметрам
 * 6 Локация 300 к параметрам
 * 7 локация 350 к параметрам
 */
function params($id) {

    $arr = [
        1 => 50, 100, 150, 200, 250, 300, 350
    ];

    return $arr[$id];
}
?>