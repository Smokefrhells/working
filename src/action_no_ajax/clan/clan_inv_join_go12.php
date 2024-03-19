<?php
require_once '../../system/system.php';
echo only_reg();
echo clan_level();
echo save();
#-Принимаем заявку на вступление-#
switch($act){
case 'join':
if(isset($_GET['clan_id']) and isset($_GET['event_id'])){
$clan_id = check($_GET['clan_id']); //ID клана
$event_id = check($_GET['event_id']); //ID заявки
#-Проверяем что мы не состоим в клане-#
$sel_clan_u = $pdo->prepare("SELECT `user_id` FROM `clan_users` WHERE `user_id` = :user_id");
$sel_clan_u->execute(array(':user_id' => $user['id']));
if($sel_clan_u -> rowCount() != 0) $error = 'Вы состоите в клане!';
#-Проверяем что такой клан есть-#
$sel_clan = $pdo->prepare("SELECT `id`, `quatity_user` FROM `clan` WHERE `id` = :clan_id");
$sel_clan->execute(array(':clan_id' => $clan_id));
if($sel_clan -> rowCount() == 0) $error = 'Клан не найден!';
#-Проверяем что есть приглашение-#
$sel_event_l = $pdo->prepare("SELECT * FROM `event_log` WHERE `id` = :event_id");
$sel_event_l->execute(array(':event_id' => $event_id));
if($sel_event_l -> rowCount() == 0) $error = 'Приглашение не найдено!';
#-Стоит время или нет-#
if($user['clan_time'] != 0) $error = 'Время не вышло!';
#-Если нет ошибок-#
if(!isset($error)){
$clan = $sel_clan->fetch(PDO::FETCH_LAZY); //Клан
#-Проверяем что игроков меньше чем нужно-#
$sel_clan_u_c = $pdo->prepare("SELECT COUNT(*) FROM `clan_users` WHERE `clan_id` = :clan_id");
$sel_clan_u_c->execute(array(':clan_id' => $clan['id']));
$amount_u = $sel_clan_u_c->fetch(PDO::FETCH_LAZY);
if($amount_u[0] < $clan['quatity_user']){
#-Удаляем все приглашения в клан-#
$del_event_l = $pdo->prepare("DELETE FROM `event_log` WHERE `user_id` = :user_id AND `type` = 5");
$del_event_l->execute(array(':user_id' => $user['id']));
#-Добавляем в клан-#
$ins_clan_u = $pdo->prepare("INSERT INTO `clan_users` SET `clan_id` = :clan_id, `user_id` = :user_id, `time` = :time");
$ins_clan_u->execute(array(':clan_id' => $clan['id'], ':user_id' => $user['id'], ':time' => time()));
#-Записываем ID клана-#
$upd_users = $pdo->prepare("UPDATE `users` SET `clan_id` = :clan_id WHERE `id` = :id");
$upd_users->execute(array(':clan_id' => $clan['id'], ':id' => $user['id']));
#-История клана-#
if($user['pol'] == 1){$p = 'вступил';}else{$p = 'вступила';}
$ins_clan_l = $pdo->prepare("INSERT INTO `clan_log` SET `type` = :type, `log` = :log, `clan_id` = :clan_id, `time` = :time");
$ins_clan_l ->execute(array(':type' => 1, ':log' => "<a href='/hero/$user[id]' style='display:inline;text-decoration:underline;padding:0px;'>$user[nick]</a> $p в клан", ':clan_id' => $clan_id, ':time' => time())); 
header("Location: /clan/view/$clan_id");
}else{
header("Location: /clan/application/$clan_id");
$_SESSION['err'] = 'В клане максимальный состав!';
exit();
}
}else{
header("Location: /clan/view/$clan_id");
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /create_clan');
$_SESSION['err'] = 'Данные отсутствуют!';
exit();
}
}
?>