<?php
require_once '../system.php';
#-Крон задание начала боя против босса ТЭПА-#

#-Параметры питомца ТЭПА-#
$name = 'Нихиль'; //Имя
$images = '/style/images/monstru/pyramid/nihil.png'; //Картинка 
$sila = 300; //Сила
$zashita = 300; //Защита
$health = 10000000; //Здоровье
$time = 7200; //Время на бой в секундах

$sel_pyramid_b = $pdo->query("SELECT `id` FROM `pyramid_battle_b`");
if($sel_pyramid_b->rowCount() == 0){
#-Если боя нет то начинаем-#
$ins_pyramid_b = $pdo->prepare("INSERT INTO `pyramid_battle_b` SET `name` = :name, `images` = :images, `sila` = :sila, `zashita` = :zashita, `health` = :health, `max_health` = :health, `time` = :time");
$ins_pyramid_b->execute(array(':name' => $name, ':images' => $images, ':sila' => $sila, ':zashita' => $zashita, ':health' => $health, ':time' => time()+$time));
#-Чистка лога-#
$del_pyramid_l = $pdo->query("DELETE FROM `pyramid_battle_l`");
}