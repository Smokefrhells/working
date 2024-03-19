<?php
require_once '../../system/system.php';
echo only_reg();
echo clan_level();
echo save();
#-Приглашение в клан-#
switch($act){
case 'inv':
if(isset($_GET['all_id']) and isset($_GET['clan_id'])){
$all_id = check($_GET['all_id']);
$clan_id = check($_GET['clan_id']);
#-Существует ли игрок-#
$sel_users = $pdo->prepare("SELECT `id`, `level`, `ev_clan`, `clan_time` FROM `users` WHERE `id` = :all_id AND `ev_clan` = 0 AND `clan_time` = 0");
$sel_users->execute(array(':all_id' => $all_id));
if($sel_users-> rowCount() == 0) $error = 'Игрок не найден или запрещено!';
#-Проверяем что игрок не состоит в клане-#
$sel_clan_u = $pdo->prepare("SELECT `user_id` FROM `clan_users` WHERE `user_id` = :user_id");
$sel_clan_u->execute(array(':user_id' => $all_id));
if($sel_clan_u -> rowCount() != 0) $error = 'Игрок состоит в клане!';
#-Проверяим что мы состоим в этом клане и есть права-#
$sel_clan_me = $pdo->prepare("SELECT * FROM `clan_users` WHERE `user_id` = :user_id AND `prava` != 0");
$sel_clan_me->execute(array(':user_id' => $user['id']));
if($sel_clan_me-> rowCount() == 0)  $error = 'Вы не состоите в клане или нет прав!';
#-Проверяем что такой клан есть-#
$sel_clan = $pdo->prepare("SELECT `id`, `quatity_user`, `name` FROM `clan` WHERE `id` = :clan_id");
$sel_clan->execute(array(':clan_id' => $clan_id));
if($sel_clan -> rowCount() == 0) $error = 'Клан не найден!';
#-Проверяем что нет уведомления-#
$sel_event_log = $pdo->prepare("SELECT * FROM `event_log` WHERE `user_id` = :ank_id AND `ev_id` = :clan_id AND `type` = 5");
$sel_event_log->execute(array(':ank_id' => $all_id, ':clan_id' => $clan_id));
if($sel_event_log -> rowCount() != 0) $error = 'Приглашение уже отправлено!';
#-Если нет ошибок-#
if(!isset($error)){
$clan = $sel_clan->fetch(PDO::FETCH_LAZY); //Клан	
#-Проверяем что игроков меньше чем нужно-#
$sel_clan_u_c = $pdo->prepare("SELECT COUNT(*) FROM `clan_users` WHERE `clan_id` = :clan_id");
$sel_clan_u_c->execute(array(':clan_id' => $clan['id']));
$amount_u = $sel_clan_u_c->fetch(PDO::FETCH_LAZY);
if($amount_u[0] < $clan['quatity_user']){
#-Создаем событие-#
$ins_event = $pdo->prepare("INSERT INTO `event_log` SET `type` = :type, `log` = :log, `user_id` = :ank_id, `ank_id` = :user_id, `ev_id` = :clan_id, `time` = :time");
$ins_event ->execute(array(':type' => 5, ':log' => "<a href='/hero/$user[id]' style='display:inline;text-decoration:underline;padding:0px;'>$user[nick]</a> приглашает вас в клан <a href='/clan/view/$clan[id]' style='display:inline;text-decoration:underline;padding:0px;'>$clan[name]</a>", ':ank_id' => $all_id, ':user_id' => $user['id'], ':clan_id' => $clan['id'], ':time' => time())); 
header("Location: /hero/$all_id");
$_SESSION['ok'] = 'Приглашение в клан отправлено!';
exit();
}else{
header("Location: /clan/application/$clan_id");
$_SESSION['err'] = 'В клане максимальный состав!';
exit();
}
}else{
header("Location: /hero/$all_id");
$_SESSION['err'] = $error;
exit();
}
}else{
header("Location: /hero/$user[id]");
$_SESSION['err'] = 'Данные не получены!';
exit();
}
}
?>