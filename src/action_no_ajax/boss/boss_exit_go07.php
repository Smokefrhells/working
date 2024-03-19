<?php
require_once '../../system/system.php';
global $user;
echo boss_level();
#-Покидание боя с боссом-#
switch($act){
case 'exit':
#-Игрок в бою-#
$sel_boss_u = $pdo->prepare("SELECT `id`, `battle_id`, `boss_id`, `user_id` FROM `boss_users` WHERE `user_id` = :user_id");
$sel_boss_u->execute(array(':user_id' => $user['id']));
if($sel_boss_u-> rowCount() == 0) $error = 'Вы не в бою!';

#-Если нет ошибок-#
if(!isset($error)){
$boss_u = $sel_boss_u->fetch(PDO::FETCH_LAZY);

#-Выборка данных босса-#
$sel_boss = $pdo->prepare("SELECT `id`, `time_otduh`, `type` FROM `boss` WHERE `id` = :boss_id");
$sel_boss->execute(array(':boss_id' => $boss_u['boss_id']));
$boss = $sel_boss->fetch(PDO::FETCH_LAZY);

#-Есть еще игроки или нет-#
$sel_boss_u2 = $pdo->prepare("SELECT `id`, `battle_id`, `user_id` FROM `boss_users` WHERE `user_id` != :user_id AND `battle_id` = :battle_id");
$sel_boss_u2->execute(array(':user_id' => $user['id'], ':battle_id' => $boss_u['battle_id']));
if($sel_boss_u2-> rowCount() == 0){
#-Удаляем бой-#
$del_boss_b = $pdo->prepare("DELETE FROM `boss_battle` WHERE `id` = :battle_id");
$del_boss_b->execute(array(':battle_id' => $boss_u['battle_id']));
#-Чистим лог-#
$del_log = $pdo->prepare("DELETE FROM `boss_log` WHERE `battle_id` = :battle_id");
$del_log->execute(array(':battle_id' => $boss_u['battle_id']));
#-Удаляем все уведомления-#
$del_event = $pdo->prepare("DELETE FROM `event_log` WHERE `type` = :type AND `ev_id` = :battle_id");
$del_event->execute(array(':type' => 2, ':battle_id' => $boss_u['battle_id']));
}else{
#-Лог-#
if($user['pol'] == 1){$at = 'покинул';}else{$at = 'покинула';}
$ins_log = $pdo->prepare("INSERT INTO `boss_log` SET `battle_id` = :battle_id, `log` = :log, `time` = :time");
$ins_log->execute(array(':battle_id' => $boss_u['battle_id'], ':log' => "<img src='/style/images/user/user.png' alt=''/> <span style='color: #cb862c;'>$user[nick] $at бой</span>", ':time' => time()));
}

#-Засчитываем поражение игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `battle` = :battle, `boss_progrash` = :boss_progrash WHERE `id` = :user_id LIMIT 1");
$upd_users->execute(array(':battle' => 0, ':boss_progrash' => $user['boss_progrash']+1, ':user_id' => $user['id']));	
#-Ставим время игроку-#
$ins_boss_t = $pdo->prepare("INSERT INTO `boss_time` SET `type` = :type, `boss_id` = :boss_id, `user_id` = :user_id, `time` = :time");
$ins_boss_t->execute(array(':type' => 2, ':boss_id' => $boss['id'], ':user_id' => $user['id'], ':time' => time()+$boss['time_otduh'])); 
#-Удаление игрока из боя-#
$del_boss_u = $pdo->prepare("DELETE FROM `boss_users` WHERE `user_id` = :user_id");
$del_boss_u->execute(array(':user_id' => $user['id']));	
header("Location: /boss?type=$boss[type]");
exit();
}else{
header('Location: /boss');
$_SESSION['err'] = $error;
exit();
}
}
?>