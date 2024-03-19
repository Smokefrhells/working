<?php
require_once '../../system/system.php';
echo only_reg();
echo hunting_campaign();

#-Ускоряем время охота-#
switch($act){
case 'accel':
if(isset($_GET['hunting_id'])){
$id = check($_GET['hunting_id']);
#-Выборка локации-#
$sel_hunting = $pdo->prepare("SELECT * FROM `hunting` WHERE `id` = :id");
$sel_hunting->execute(array(':id' => $id));
if($sel_hunting-> rowCount() != 0){
$hunting = $sel_hunting->fetch(PDO::FETCH_LAZY);
#-Отдых или нет-#
$sel_hunting_t = $pdo->prepare("SELECT * FROM `hunting_time` WHERE `user_id` = :user_id AND `hunting_id` = :hunting_id");
$sel_hunting_t->execute(array(':user_id' => $user['id'], ':hunting_id' => $hunting['id']));
if($sel_hunting_t->rowCount() != 0){
$hunting_time = $sel_hunting_t->fetch(PDO::FETCH_LAZY);
#-Сколько времени осталось-#
$hunting_ostatok = $hunting_time['hunting_time']-time();

#-Золото за ускорение времени-#
$hour = floor($hunting_ostatok/3600);
if($hour <= 0){
$min = round(((($hunting_ostatok/60%60) * 60) /35), 0);
if($min < 1){
$gold_time = 1;
}else{
$gold_time = round($min, 0);
}
}else{
$minut = ($hunting_ostatok/60%60) * 60;
$hou = ($hour * 3600);
$summa = round($minut+$hou, 0);
$gold_time = round($summa / 35, 0);
}
$hunting_levels= array('','1','20','35','50','65','80','95');	
//if($user['level']> $hunting_levels[$hunting['id']]){
#-Проверка хватает ли денег-#
if($user['gold'] >= $gold_time){
#-Отнимаем деньги у пользователя-#
if($hunting['id'] == 1){
$hunting_t = 'hunting_1';
}
if($hunting['id'] == 2){
$hunting_t = 'hunting_2';
}
if($hunting['id'] == 3){
$hunting_t = 'hunting_3';
}
if($hunting['id'] == 4){
$hunting_t = 'hunting_4';
}
if($hunting['id'] == 5){
$hunting_t = 'hunting_5';
}
if($hunting['id'] == 6){
$hunting_t = 'hunting_6';
}
if($hunting['id'] == 7){
$hunting_t = 'hunting_7';
}
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `$hunting_t` = :hunting WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold'] - $gold_time, ':hunting' => 0, ':id' => $user['id'])); 
$del_hunting_t = $pdo->prepare("DELETE FROM `hunting_time` WHERE `hunting_id` = :hunting_id AND `user_id` = :user_id");
$del_hunting_t->execute(array(':hunting_id' => $hunting['id'], ':user_id' => $user['id']));
header('Location: /select_location');
}else{
header('Location: /select_location');
$_SESSION['err'] = 'Недостаточно золота!';
exit();
}
/*}else{
header('Location: /select_location');
}*/
}else{
header('Location: /select_location');
}
}else{
header('Location: /select_location');
}
}else{
header('Location: /select_location');
}
}
?>