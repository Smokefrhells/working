<?php
require_once '../../system/system.php';
echo only_reg();

/*Начало боя с боссом*/
switch($act){
case 'atk':
if($user['start'] >= 6){
#-Проверяем в бою мы или нет-#
$sel_boss_u = $pdo->prepare("SELECT * FROM `boss_users` WHERE `user_id` = :user_id");
$sel_boss_u->execute(array(':user_id' => $user['id']));
#-Если нет то продолжаем-#
if($sel_boss_u-> rowCount() == 0){
if(isset($_GET['id'])){
$id = check($_GET['id']);
#-Есть ли такой босс-#
$sel_boss = $pdo->prepare("SELECT * FROM `boss` WHERE `id` = :id AND `type` != 4"); //type != 4
$sel_boss->execute(array(':id' => $id));
if($sel_boss-> rowCount() != 0){
$boss = $sel_boss->fetch(PDO::FETCH_LAZY);
#-Уровень больше или равен Боссу-#
if($user['level'] >= $boss['level'] and $user['level'] >= 5){
#-Проверяем отдых-#
$sel_boss_t = $pdo->prepare("SELECT * FROM `boss_time` WHERE `boss_id` = :boss_id AND `user_id` = :user_id AND `type` = :type");
$sel_boss_t->execute(array(':boss_id' => $boss['id'], ':user_id' => $user['id'], ':type' => 2));
if($sel_boss_t-> rowCount() == 0){
#-Запись в БД-#
$ins_boss_b = $pdo->prepare("INSERT INTO `boss_battle` SET `boss_id` = :boss_id, `boss_t_health` = :boss_t_health, `boss_health` = :boss_health, `time` = :time");
$ins_boss_b->execute(array(':boss_id' => $boss['id'], ':boss_t_health' => $boss['health'], ':boss_health' => $boss['health'], ':time' => time())); 
$boss_id = $pdo->lastInsertId();
$ins_boss_u = $pdo->prepare("INSERT INTO `boss_users` SET `glava` = 1, `battle_id` = :battle_id, `boss_id` = :boss_id, `user_id` = :user_id, `user_t_health` = :user_t_health, `user_health` = :user_health");
$ins_boss_u->execute(array(':battle_id' => $boss_id, ':boss_id' => $boss['id'], ':user_id' => $user['id'], ':user_t_health' => $user['health']+$user['s_health']+$user['health_bonus'], ':user_health' => $user['health']+$user['s_health']+$user['health_bonus'])); 
$upd_users = $pdo->prepare("UPDATE `users` SET `battle` = :battle WHERE `id` = :id");
$upd_users->execute(array(':battle' => 1, ':id' => $user['id']));
header('Location: /boss_battle');
}else{
header('Location: /boss');
$_SESSION['err'] = 'Босс отдыхает!';
exit();	
}
}else{
header('Location: /');
$_SESSION['err'] = 'Слишко мал уровень!';
exit();	
}
}else{
header('Location: /boss');
$_SESSION['err'] = 'Босс не найден!';
exit();	
}
}else{
header('Location: /');
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /');
$_SESSION['err'] = 'Вы сейчас в бою!';
exit();	
}
}else{
header('Location: /');
exit();	
}
}
?>