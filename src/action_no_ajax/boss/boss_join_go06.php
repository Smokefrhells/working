<?php
require_once '../../system/system.php';
global $user;
echo boss_level();
#-Вступление-#
switch($act){
case 'join':
if(isset($_GET['battle_id'])){
$battle_id = check($_GET['battle_id']);
#-Можно еще вступать-#
$sel_boss_y = $pdo->prepare("SELECT `id`, `battle_id` FROM `boss_users` WHERE `battle_id` = :battle_id");
$sel_boss_y->execute(array(':battle_id' => $battle_id));
if($sel_boss_y->rowCount() > 4) $error = 'Максимальное кол-во участников!';
#-Игрок не в бою-#
$sel_boss_u1 = $pdo->prepare("SELECT `id`, `user_id` FROM `boss_users` WHERE `user_id` = :user_id");
$sel_boss_u1->execute(array(':user_id' => $user['id']));
if($sel_boss_u1-> rowCount() != 0) $error = 'Вы в бою!';
#-Игрок который пригласил-#
$sel_boss_u2 = $pdo->prepare("SELECT `id`, `boss_id`, `battle_id`, `user_id` FROM `boss_users` WHERE `battle_id` = :battle_id");
$sel_boss_u2->execute(array(':battle_id' => $battle_id));
if($sel_boss_u2-> rowCount() == 0) $error = 'Бой окончен!';
#-Проверяем что есть уведомление-#
$sel_event = $pdo->prepare("SELECT * FROM `event_log` WHERE `user_id` = :user_id AND `ev_id` = :battle_id");
$sel_event->execute(array(':user_id' => $user['id'], ':battle_id' => $battle_id));
if($sel_event-> rowCount() == 0) $error = 'Уведомление не найдено!';
#-Если нет ошибок-#
if(!isset($error)){
$boss_u = $sel_boss_u2->fetch(PDO::FETCH_LAZY);
#-Стоит время или нет-#
$sel_boss_t = $pdo->prepare("SELECT * FROM `boss_time` WHERE `user_id` = :all_id AND `boss_id` = :boss_id");
$sel_boss_t->execute(array(':all_id' => $user['id'], ':boss_id' => $boss_u['boss_id']));
if($sel_boss_t-> rowCount() == 0){
#-Записываем игрока в бой-#
$ins_boss_u = $pdo->prepare("INSERT INTO `boss_users` SET `battle_id` = :battle_id, `boss_id` = :boss_id, `user_id` = :user_id, `user_t_health` = :user_t_health, `user_health` = :user_health");
$ins_boss_u->execute(array(':battle_id' => $boss_u['battle_id'], ':boss_id' => $boss_u['boss_id'], ':user_id' => $user['id'], ':user_t_health' => $user['health']+$user['s_health']+$user['health_bonus'], ':user_health' => $user['health']+$user['s_health']+$user['health_bonus'])); 
#-Ставим статус бой-#
$upd_users = $pdo->prepare("UPDATE `users` SET `battle` = :battle WHERE `id` = :id");
$upd_users->execute(array(':battle' => 1, ':id' => $user['id']));
#-Удаляем приглашение-#
$del_event = $pdo->prepare("DELETE FROM `event_log` WHERE `user_id` = :user_id AND `ev_id` = :ev_id");
$del_event->execute(array(':user_id' => $user['id'], ':ev_id' => $boss_u['battle_id']));
#-Лог-#
if($user['pol'] == 1){$at = 'присоединился';}else{$at = 'присоединилась';}
$ins_log = $pdo->prepare("INSERT INTO `boss_log` SET `battle_id` = :battle_id, `log` = :log, `time` = :time");
$ins_log->execute(array(':battle_id' => $boss_u['battle_id'], ':log' => "<img src='/style/images/user/user.png' alt=''/> <span style='color: #cb862c;'>$user[nick] $at к бою</span>", ':time' => time()));
header('Location: /boss_battle');
exit();
}else{
header('Location: /');
$_SESSION['err'] = 'Босс на отдыхе!';
exit();
}
}else{
header('Location: /');
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /');
$_SESSION['err'] = 'Данные не получены!';
exit();
}
}
?>