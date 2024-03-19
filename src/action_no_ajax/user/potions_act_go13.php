<?php
require_once '../../system/system.php';
echo only_reg();
#-Зелье бонус к параметрам-#
switch($act){
case 'itv':
if(isset($_GET['id'])){
$id = check($_GET['id']);
#-Проверяем ввод цифры-#
if(!preg_match('/^([0-9])+$/u',$_GET['id'])) $error = 'Некорректный идентификатор!';
#-Проверяем что такое зелье существует-#
$sel_potions = $pdo->prepare("SELECT * FROM `potions` WHERE `id` = :id AND `type` = 2");
$sel_potions->execute(array(':id' => $id));
if($sel_potions-> rowCount() == 0) $error = 'Зелье не найдено или неверный тип!';
#-Если нет ошибок-#
if(!isset($error)){
$potions = $sel_potions->fetch(PDO::FETCH_LAZY);
#-Проверяем что зелье есть у нас-#
$sel_potions_me = $pdo->prepare("SELECT * FROM `potions_me` WHERE `potions_id` = :potions_id AND `user_id` = :user_id");
$sel_potions_me->execute(array(':potions_id' => $potions['id'], ':user_id' => $user['id']));
if($sel_potions_me-> rowCount() != 0){
$potions_me = $sel_potions_me->fetch(PDO::FETCH_LAZY);
#-Какой бонус-#
//Сила
if($potions['id'] == 4){
$sila_bonus = round($user['sila'] * 0.10, 0);
if($user['sila_time'] == 0){
$sila_time = time()+86400;
}else{
$sila_time = $user['sila_time']+86400;	
}
$upd_users = $pdo->prepare("UPDATE `users` SET `sila_bonus` = :sila_bonus, `sila_time` = :sila_time WHERE `id` = :user_id");
$upd_users->execute(array(':sila_bonus' => $sila_bonus, ':sila_time' => $sila_time,  ':user_id' => $user['id']));
}
//Защита
if($potions['id'] == 5){
$zashita_bonus = round($user['zashita'] * 0.10, 0);
if($user['zashita_time'] == 0){
$zashita_time = time()+86400;
}else{
$zashita_time = $user['zashita_time']+86400;	
}
$upd_users = $pdo->prepare("UPDATE `users` SET `zashita_bonus` = :zashita_bonus, `zashita_time` = :zashita_time WHERE `id` = :user_id");
$upd_users->execute(array(':zashita_bonus' => $zashita_bonus, ':zashita_time' => $zashita_time,  ':user_id' => $user['id']));
}
//Здоровье
if($potions['id'] == 6){
$health_bonus = round($user['health'] * 0.10, 0);
if($user['health_time'] == 0){
$health_time = time()+86400;
}else{
$health_time = $user['health_time']+86400;
}
$upd_users = $pdo->prepare("UPDATE `users` SET `health_bonus` = :health_bonus, `health_time` = :health_time WHERE `id` = :user_id");
$upd_users->execute(array(':health_bonus' => $health_bonus, ':health_time' => $health_time,  ':user_id' => $user['id']));
}
#-Если количество больше 1 то просто отнимаем кол-во-#
if($potions_me['quatity'] >= 2){
$upd_potions_me = $pdo->prepare("UPDATE `potions_me` SET `quatity` = :quatity WHERE `potions_id` = :potions_id AND `user_id` = :user_id");
$upd_potions_me->execute(array(':quatity' => $potions_me['quatity'] - 1, ':potions_id' => $potions_me['potions_id'], ':user_id' => $user['id']));
}else{
$del_potions_me = $pdo->prepare("DELETE FROM `potions_me` WHERE `potions_id` = :potions_id AND `user_id` = :user_id");
$del_potions_me->execute(array(':potions_id' => $potions_me['potions_id'], ':user_id' => $user['id']));
}
header('Location: /bag?type=2');
$_SESSION['ok'] = 'Зелье использовано!';
exit();
}else{
header('Location: /bag?type=2');
$_SESSION['err'] = 'Зелье не найдено!';
exit();	
}
}else{
header('Location: /bag?type=2');
$_SESSION['err'] = $error;
exit();	
}
}else{
header('Location: /bag?type=2');
$_SESSION['err'] = 'Данные не переданы';
exit();
}
}
?>