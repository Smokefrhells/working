<?php
require_once '../../system/system.php';
global $user;
echo boss_level();
#-Помощь боссы-#
switch($act){
case 'inv':
if(isset($_GET['all_id']) and isset($_GET['battle_id'])){
$all_id = check($_GET['all_id']);
$battle_id = check($_GET['battle_id']);
#-Можно еще приглашать игроков-#
$sel_boss_y = $pdo->prepare("SELECT `id`, `battle_id` FROM `boss_users` WHERE `battle_id` = :battle_id");
$sel_boss_y->execute(array(':battle_id' => $battle_id));
if($sel_boss_y->rowCount() > 4) $error = 'Максимальное кол-во участников!';
#-Существует ли игрок-#
$sel_users = $pdo->prepare("SELECT `id`, `level`, `ev_help` FROM `users` WHERE `id` = :all_id AND `ev_help` = 0");
$sel_users->execute(array(':all_id' => $all_id));
if($sel_users-> rowCount() == 0) $error = 'Игрок не найден или запрещено!';
#-Текущий игрок в бою-#
$sel_boss_u1 = $pdo->prepare("SELECT `id`, `boss_id`, `user_id`, `glava` FROM `boss_users` WHERE `user_id` = :user_id AND `glava` = 1");
$sel_boss_u1->execute(array(':user_id' => $user['id']));
if($sel_boss_u1-> rowCount() == 0) $error = 'Вы не в бою!';
#-Игрок которого приглашаем не состоит в бою с боссом-#
$sel_boss_u2 = $pdo->prepare("SELECT `id`, `user_id` FROM `boss_users` WHERE `user_id` = :all_id");
$sel_boss_u2->execute(array(':all_id' => $all_id));
if($sel_boss_u2-> rowCount() != 0) $error = 'Игрок в бою с боссом!';
#-Проверяем отправляли уведомление или нет-#
$sel_event = $pdo->prepare("SELECT * FROM `event_log` WHERE `user_id` = :all_id AND `ev_id` = :battle_id");
$sel_event->execute(array(':all_id' => $all_id, ':battle_id' => $battle_id));
if($sel_event-> rowCount() != 0) $error = 'Уведомление уже отправлено!';
#-Если нет ошибок-#
if(!isset($error)){
$all = $sel_users->fetch(PDO::FETCH_LAZY);
$boss_u = $sel_boss_u1->fetch(PDO::FETCH_LAZY);

#-Выборка имени босса-#
$sel_boss = $pdo->prepare("SELECT `id`, `name` FROM `boss` WHERE `id` = :boss_id");
$sel_boss->execute(array(':boss_id' => $boss_u['boss_id']));
$boss = $sel_boss->fetch(PDO::FETCH_LAZY);

#-Стоит время или нет-#
$sel_boss_t = $pdo->prepare("SELECT * FROM `boss_time` WHERE `user_id` = :all_id AND `boss_id` = :boss_id");
$sel_boss_t->execute(array(':all_id' => $all['id'], ':boss_id' => $boss_u['boss_id']));
if($sel_boss_t-> rowCount() == 0){
#-Проверяем что лвл игрока больше или равен лвл босса-#
$sel_boss = $pdo->prepare("SELECT `id`, `level` FROM `boss` WHERE `id` = :boss_id AND `level` <= :user_level");
$sel_boss->execute(array(':boss_id' => $boss_u['boss_id'], ':user_level' => $all['level']));
if($sel_boss-> rowCount() != 0){
#-Создаем событие-#
$ins_event = $pdo->prepare("INSERT INTO `event_log` SET `type` = :type, `log` = :log, `user_id` = :ank_id, `ank_id` = :user_id, `ev_id` = :battle_id, `time` = :time");
$ins_event ->execute(array(':type' => 2, ':log' => "<a href='/hero/$user[id]' style='display:inline;text-decoration:underline;padding:0px;'>$user[nick]</a> зовет вас на помощь с боссом $boss[name]</a>", ':ank_id' => $all_id, ':user_id' => $user['id'], ':battle_id' => $battle_id, ':time' => time())); 
header('Location: /boss_help');
exit();
}else{
header('Location: /boss_help');
$_SESSION['err'] = 'Этот босс у игрока не открыт!';
exit();
}
}else{
header('Location: /boss_help');
$_SESSION['err'] = 'Ошибка!';
exit();
}
}else{
header('Location: /boss_help');
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /boss_help');
$_SESSION['err'] = 'Данные не получены!';
exit();
}
}
?>