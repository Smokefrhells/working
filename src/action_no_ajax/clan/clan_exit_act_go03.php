<?php
require_once '../../system/system.php';
echo only_reg();
echo clan_level();
echo save();
#-Покидаем клан-#
switch($act){
case 'exit':
if(isset($_GET['clan_id'])){
$clan_id = check($_GET['clan_id']); //ID клана
#-Проверяем что такой клан есть-#
$sel_clan = $pdo->prepare("SELECT `id` FROM `clan` WHERE `id` = :clan_id");
$sel_clan->execute(array(':clan_id' => $clan_id));
if($sel_clan -> rowCount() == 0) $error = 'Клан не найден!';
#-Проверяем что игрок состоит в клане-#
$sel_clan_u = $pdo->prepare("SELECT `clan_id`, `user_id` FROM `clan_users` WHERE `clan_id` = :clan_id AND `user_id` = :user_id");
$sel_clan_u->execute(array(':clan_id' => $clan_id, ':user_id' => $user['id']));
if($sel_clan_u -> rowCount() == 0) $error = 'Вы не состоите в клане!';
$clan_u = $sel_clan_u->fetch(PDO::FETCH_LAZY);
#-Проверяем сколько еще игроков в клане-#
$sel_clan_c = $pdo->prepare("SELECT COUNT(*) FROM `clan_users` WHERE `clan_id` = :clan_id");
$sel_clan_c->execute(array(':clan_id' => $clan_id));
$amount = $sel_clan_c->fetch(PDO::FETCH_LAZY);
#-Если есть еще игроки то проверяем-#
if($amount[0] > 1){
#-Проверяем что в клане есть основатель-#
$sel_clan_u2 = $pdo->prepare("SELECT `clan_id`, `user_id`, `prava` FROM `clan_users` WHERE `clan_id` = :clan_id AND `prava` = 4 AND `user_id` != :user_id");
$sel_clan_u2->execute(array(':clan_id' => $clan_id, ':user_id' => $user['id']));
if($sel_clan_u2-> rowCount() == 0) $error = 'Передайте права!';
}
#-Если нет ошибок-#
if(!isset($error)){
#-Если последний игрок то удаляем все данные клана-#
if($amount[0] == 1){
$del_clan_u = $pdo->prepare("DELETE FROM `clan` WHERE `id` = :clan_id");
$del_clan_u->execute(array(':clan_id' => $clan_id));
$del_clan_l = $pdo->prepare("DELETE FROM `clan_log` WHERE `clan_id` = :clan_id");
$del_clan_l->execute(array(':clan_id' => $clan_id));
$del_clan_r = $pdo->prepare("DELETE FROM `clan_razdel` WHERE `clan_id` = :clan_id");
$del_clan_r->execute(array(':clan_id' => $clan_id));
$del_clan_t = $pdo->prepare("DELETE FROM `clan_topic` WHERE `clan_id` = :clan_id");
$del_clan_t->execute(array(':clan_id' => $clan_id));
$del_clan_c = $pdo->prepare("DELETE FROM `clan_comment` WHERE `clan_id` = :clan_id");
$del_clan_c->execute(array(':clan_id' => $clan_id));
}
$del_clan_u = $pdo->prepare("DELETE FROM `clan_users` WHERE `clan_id` = :clan_id AND `user_id` = :user_id");
$del_clan_u->execute(array(':clan_id' => $clan_id, ':user_id' => $user['id']));
#-Ставим время задержки вступлений в клан-#
$upd_users = $pdo->prepare("UPDATE `users` SET `clan_time` = :time, `clan_id` = 0 WHERE `id` = :user_id");
$upd_users->execute(array(':time' => time()+86400, ':user_id' => $user['id']));
#-История клана-#
if($user['pol'] == 1){$p = 'покинул';}else{$p = 'покинула';}
$ins_clan_l = $pdo->prepare("INSERT INTO `clan_log` SET `type` = :type, `log` = :log, `clan_id` = :clan_id, `time` = :time");
$ins_clan_l ->execute(array(':type' => 2, ':log' => "<a href='/hero/$user[id]' style='display:inline;text-decoration:underline;padding:0px;'>$user[nick]</a> $p клан", ':clan_id' => $clan_id, ':time' => time())); 
header("Location: /clan");
}else{
header("Location: /clan/view/$clan_id");
$_SESSION['err'] = $error;
exit();
}
}else{
header("Location: /clan");
$_SESSION['err'] = 'Данные не получены!';
exit();
}
}
?>