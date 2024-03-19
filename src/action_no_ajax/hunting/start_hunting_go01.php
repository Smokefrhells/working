<?php
require_once '../../system/system.php';
echo only_reg();
echo hunting_campaign();
/*Начало охоты*/
switch($act){
case 'battle':
#-Проверяем в бою мы или нет-#
$sel_hunting_b = $pdo->prepare("SELECT * FROM `hunting_battle` WHERE `user_id` = :user_id");
$sel_hunting_b->execute(array(':user_id' => $user['id']));
#-Если нет то продолжаем-#
if($sel_hunting_b-> rowCount() == 0){
if(isset($_GET['loc'])){
$loc = check($_GET['loc']);
#-Выборка локации-#
$sel_hunting = $pdo->prepare("SELECT * FROM `hunting` WHERE `id` = :id");
$sel_hunting->execute(array(':id' => $loc));
$hunting = $sel_hunting->fetch(PDO::FETCH_LAZY);

if(($hunting['id'] == 1 and $user['level'] >= 1) OR ($hunting['id'] == 2 and $user['level'] >= 20) OR ($hunting['id'] == 3 and $user['level'] >= 35) OR ($hunting['id'] == 4 and $user['level'] >= 50) OR ($hunting['id'] == 5 and $user['level'] >= 65) OR ($hunting['id'] == 6 and $user['level'] >= 80) OR ($hunting['id'] == 7 and $user['level'] >= 95)){
#-Отдых или нет-#
$sel_hunting_t = $pdo->prepare("SELECT * FROM `hunting_time` WHERE `user_id` = :user_id AND `hunting_id` = :hunting_id");
$sel_hunting_t->execute(array(':user_id' => $user['id'], ':hunting_id' => $hunting['id']));
if($sel_hunting_t->rowCount() == 0){
#-Выборка монстров из полученной локации обычные-#
$sel_monsters = $pdo->prepare("SELECT * FROM `monsters` WHERE `location` = :location");
$sel_monsters->execute(array(':location' => $loc));
#-Если есть монстры-#
if($sel_monsters-> rowCount() != 0){
while($monstr = $sel_monsters->fetch(PDO::FETCH_LAZY))
{	
#-Обычные монстры-#
if($monstr['type'] == '0'){
$monstr_obusnie[$monstr['id']] = "$monstr[id]";
}
#-Редкие монстры-#
if($monstr['type'] == '1'){
$monstr_redkie[$monstr['id']] = "$monstr[id]";
}
#-Легендарные монстры-#
if($monstr['type'] == '2'){
$monstr_legendar[$monstr['id']] = "$monstr[id]";
}
}
#-Рандомно выбираем монстра для охоты-#
$rand_ohota = rand(0,100);
#-Если меньше или равно 75, то обычный монстр-#
if($rand_ohota <= 75){
$monstr_id = array_rand($monstr_obusnie, 1);
}
#-Если больше 75, то редкий монстр-#
if($rand_ohota >= 75){
$monstr_id = array_rand($monstr_redkie, 1);
}
#-Если больше 90, то легендарный монстр-#
if($rand_ohota >= 90){
$monstr_id = array_rand($monstr_legendar, 1);
}
#-Делаем повторную выборку-#
$sel_monsters2 = $pdo->prepare("SELECT * FROM `monsters` WHERE `id` = :id");
$sel_monsters2->execute(array(':id' => $monstr_id));
$monstrs = $sel_monsters2->fetch(PDO::FETCH_LAZY);
$ins_h_battle = $pdo->prepare("INSERT INTO `hunting_battle` SET `monstr_id` = :monstr_id, `monstr_t_health` = :monstr_t_health, `monstr_health` = :monstr_health, `user_health` = :user_health, `user_t_health` = :user_t_health, `user_id` = :user_id, `time` = :time");
$ins_h_battle->execute(array(':monstr_id' => $monstr_id, ':monstr_t_health' => $monstrs['health'], ':monstr_health' => $monstrs['health'], ':user_t_health' => $user['health']+$user['s_health']+$user['health_bonus'], ':user_health' => $user['health']+$user['s_health']+$user['health_bonus'], ':user_id' => $user['id'], ':time' => time())); 
#-Темный лес-#
if($hunting['id'] == 1){
if($user['hunting_1'] < 9){
$upd_users = $pdo->prepare("UPDATE `users` SET `battle` = :battle, `hunting_1` = :hunting_1, `hunting_b_1` = :hunting_b_1 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':battle' => 1, ':hunting_1' => $user['hunting_1']+1, ':hunting_b_1' => $user['hunting_b_1']+1, ':id' => $user['id']));
}else{
$upd_users = $pdo->prepare("UPDATE `users` SET `battle` = :battle, `hunting_1` = :hunting_1, `hunting_b_1` = :hunting_b_1 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':battle' => 1, ':hunting_1' => $user['hunting_1']+1, ':hunting_b_1' => $user['hunting_b_1']+1, ':id' => $user['id']));
$ins_hunting_t = $pdo->prepare("INSERT INTO `hunting_time` SET `hunting_id` = :hunting_id, `hunting_time` = :hunting_time, `user_id` = :user_id");
$ins_hunting_t->execute(array(':hunting_id'=> $hunting['id'], ':hunting_time' => time()+$hunting['time_hunting'], ':user_id' => $user['id']));
}
}
#-Заброшенный замок-#
if($hunting['id'] == 2 and $user['level'] >= 20){
if($user['hunting_2'] < 9){
$upd_users = $pdo->prepare("UPDATE `users` SET `battle` = :battle, `hunting_2` = :hunting_2, `hunting_b_2` = :hunting_b_2 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':battle' => 1, ':hunting_2' => $user['hunting_2']+1, ':hunting_b_2' => $user['hunting_b_2']+1, ':id' => $user['id']));
}else{
$upd_users = $pdo->prepare("UPDATE `users` SET `battle` = :battle, `hunting_2` = :hunting_2, `hunting_b_2` = :hunting_b_2 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':battle' => 1, ':hunting_2' => $user['hunting_2']+1, ':hunting_b_2' => $user['hunting_b_2']+1, ':id' => $user['id']));
$ins_hunting_t = $pdo->prepare("INSERT INTO `hunting_time` SET `hunting_id` = :hunting_id, `hunting_time` = :hunting_time, `user_id` = :user_id");
$ins_hunting_t->execute(array(':hunting_id'=> $hunting['id'], ':hunting_time' => time()+$hunting['time_hunting'], ':user_id' => $user['id']));
}
}
#-Пещера-#
if($hunting['id'] == 3 and $user['level'] >= 35){
if($user['hunting_3'] < 9){
$upd_users = $pdo->prepare("UPDATE `users` SET `battle` = :battle, `hunting_3` = :hunting_3, `hunting_b_3` = :hunting_b_3 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':battle' => 1, ':hunting_3' => $user['hunting_3']+1, ':hunting_b_3' => $user['hunting_b_3']+1, ':id' => $user['id']));
}else{
$upd_users = $pdo->prepare("UPDATE `users` SET `battle` = :battle, `hunting_3` = :hunting_3, `hunting_b_3` = :hunting_b_3 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':battle' => 1, ':hunting_3' => $user['hunting_3']+1, ':hunting_b_3' => $user['hunting_b_3']+1, ':id' => $user['id']));
$ins_hunting_t = $pdo->prepare("INSERT INTO `hunting_time` SET `hunting_id` = :hunting_id, `hunting_time` = :hunting_time, `user_id` = :user_id");
$ins_hunting_t->execute(array(':hunting_id'=> $hunting['id'], ':hunting_time' => time()+$hunting['time_hunting'], ':user_id' => $user['id']));
}
}
#-Пустыня-#
if($hunting['id'] == 4 and $user['level'] >= 50){
if($user['hunting_4'] < 9){
$upd_users = $pdo->prepare("UPDATE `users` SET `battle` = :battle, `hunting_4` = :hunting_4, `hunting_b_4` = :hunting_b_4 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':battle' => 1, ':hunting_4' => $user['hunting_4']+1, ':hunting_b_4' => $user['hunting_b_4']+1, ':id' => $user['id']));
}else{
$upd_users = $pdo->prepare("UPDATE `users` SET `battle` = :battle, `hunting_4` = :hunting_4, `hunting_b_4` = :hunting_b_4 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':battle' => 1, ':hunting_4' => $user['hunting_4']+1, ':hunting_b_4' => $user['hunting_b_4']+1, ':id' => $user['id']));
$ins_hunting_t = $pdo->prepare("INSERT INTO `hunting_time` SET `hunting_id` = :hunting_id, `hunting_time` = :hunting_time, `user_id` = :user_id");
$ins_hunting_t->execute(array(':hunting_id'=> $hunting['id'], ':hunting_time' => time()+$hunting['time_hunting'], ':user_id' => $user['id']));
}
}
#-Катакомбы-#
if($hunting['id'] == 5 and $user['level'] >= 65){
if($user['hunting_5'] < 9){
$upd_users = $pdo->prepare("UPDATE `users` SET `battle` = :battle, `hunting_5` = :hunting_5, `hunting_b_5` = :hunting_b_5 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':battle' => 1, ':hunting_5' => $user['hunting_5']+1, ':hunting_b_5' => $user['hunting_b_5']+1, ':id' => $user['id']));
}else{
$upd_users = $pdo->prepare("UPDATE `users` SET `battle` = :battle, `hunting_5` = :hunting_5, `hunting_b_5` = :hunting_b_5 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':battle' => 1, ':hunting_5' => $user['hunting_5']+1, ':hunting_b_5' => $user['hunting_b_5']+1, ':id' => $user['id']));
$ins_hunting_t = $pdo->prepare("INSERT INTO `hunting_time` SET `hunting_id` = :hunting_id, `hunting_time` = :hunting_time, `user_id` = :user_id");
$ins_hunting_t->execute(array(':hunting_id'=> $hunting['id'], ':hunting_time' => time()+$hunting['time_hunting'], ':user_id' => $user['id']));
}
}
#-Руины-#
if($hunting['id'] == 6 and $user['level'] >= 80){
if($user['hunting_6'] < 9){
$upd_users = $pdo->prepare("UPDATE `users` SET `battle` = :battle, `hunting_6` = :hunting_6, `hunting_b_6` = :hunting_b_6 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':battle' => 1, ':hunting_6' => $user['hunting_6']+1, ':hunting_b_6' => $user['hunting_b_6']+1, ':id' => $user['id']));
}else{
$upd_users = $pdo->prepare("UPDATE `users` SET `battle` = :battle, `hunting_6` = :hunting_6, `hunting_b_6` = :hunting_b_6 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':battle' => 1, ':hunting_6' => $user['hunting_6']+1, ':hunting_b_6' => $user['hunting_b_6']+1, ':id' => $user['id']));
$ins_hunting_t = $pdo->prepare("INSERT INTO `hunting_time` SET `hunting_id` = :hunting_id, `hunting_time` = :hunting_time, `user_id` = :user_id");
$ins_hunting_t->execute(array(':hunting_id'=> $hunting['id'], ':hunting_time' => time()+$hunting['time_hunting'], ':user_id' => $user['id']));
}
}
#-Преисподняя-#
if($hunting['id'] == 7 and $user['level'] >= 95){
if($user['hunting_7'] < 9){
$upd_users = $pdo->prepare("UPDATE `users` SET `battle` = :battle, `hunting_7` = :hunting_7, `hunting_b_7` = :hunting_b_7 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':battle' => 1, ':hunting_7' => $user['hunting_7']+1, ':hunting_b_7' => $user['hunting_b_7']+1, ':id' => $user['id']));
}else{
$upd_users = $pdo->prepare("UPDATE `users` SET `battle` = :battle, `hunting_7` = :hunting_7, `hunting_b_7` = :hunting_b_7 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':battle' => 1, ':hunting_7' => $user['hunting_7']+1, ':hunting_b_7' => $user['hunting_b_7']+1, ':id' => $user['id']));
$ins_hunting_t = $pdo->prepare("INSERT INTO `hunting_time` SET `hunting_id` = :hunting_id, `hunting_time` = :hunting_time, `user_id` = :user_id");
$ins_hunting_t->execute(array(':hunting_id'=> $hunting['id'], ':hunting_time' => time()+$hunting['time_hunting'], ':user_id' => $user['id']));
}
}
header('Location: /hunting_battle');
}else{
header('Location: /');
$_SESSION['err'] = 'Монстров не обнаружено';
exit();	
}
}else{
header('Location: /select_location');
}
}else{
header('Location: /select_location');
$_SESSION['err'] = 'Ошибка уровня!';
exit();
}
}else{
header('Location: /');
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /hunting_battle');
exit();	
}
}
?>