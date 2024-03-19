<?php
require_once '../../system/system.php';
echo only_reg();
echo clan_level();
echo save();
#-Выганяем игрока-#
switch($act){
case 'del':
if(isset($_GET['clan_id']) and isset($_GET['clan_user'])){
$clan_id = check($_GET['clan_id']); //ID клана
$clan_user = check($_GET['clan_user']); //ID игрока
#-Проверяем что клан существует-#
$sel_clan = $pdo->prepare("SELECT `id` FROM `clan` WHERE `id` = :clan_id");
$sel_clan->execute(array(':clan_id' => $clan_id));
if($sel_clan -> rowCount() == 0) $error = 'Клан не найден!';
#-Проверяем что мы состоим в этом клане-#
$sel_clan_u = $pdo->prepare("SELECT * FROM `clan_users` WHERE `clan_id` = :clan_id AND `user_id` = :user_id AND (`prava` = 4 OR `prava` = 3)");
$sel_clan_u->execute(array(':clan_id' => $clan_id, ':user_id' => $user['id']));
if($sel_clan_u -> rowCount() == 0) $error = 'Вы не состоите в клане или у вас нет прав!';
$me = $sel_clan_u->fetch(PDO::FETCH_LAZY);
if($me['prava'] == 3){
#-Проверяем что игрок состоит в клане-#
$sel_clan_u2 = $pdo->prepare("SELECT * FROM `clan_users` WHERE `clan_id` = :clan_id AND `user_id` = :user_id AND `prava` != 4 AND `prava` != 3");
$sel_clan_u2->execute(array(':clan_id' => $clan_id, ':user_id' => $clan_user));
if($sel_clan_u2 -> rowCount() == 0) $error = 'Игрок не состоит в этом клане или нельзя исключить!';
}
if($me['prava'] == 4){
#-Проверяем что игрок состоит в клане-#
$sel_clan_u2 = $pdo->prepare("SELECT * FROM `clan_users` WHERE `clan_id` = :clan_id AND `user_id` = :user_id AND `prava` != 4");
$sel_clan_u2->execute(array(':clan_id' => $clan_id, ':user_id' => $clan_user));
if($sel_clan_u2 -> rowCount() == 0) $error = 'Игрок не состоит в этом клане или нельзя исключить!';
}
#-Проверяем что игрок существует-#
$sel_users = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :id");
$sel_users->execute(array(':id' => $clan_user));
if($sel_users -> rowCount() == 0) $error = 'Игрок не найден!';
#-Если нет ошибок
if(!isset($error)){
$all = $sel_users->fetch(PDO::FETCH_LAZY);
#-Удаляем игрока из клана-#
$del_clan_u = $pdo->prepare("DELETE FROM `clan_users` WHERE `clan_id` = :clan_id AND `user_id` = :clan_user");
$del_clan_u->execute(array(':clan_id' => $clan_id, ':clan_user' => $clan_user));
#-Удаляем ID клана-#
$upd_users = $pdo->prepare("UPDATE `users` SET `clan_id` = :clan_id WHERE `id` = :id");
$upd_users->execute(array(':clan_id' => 0, ':id' => $all['id']));
#-История клана-#
$ins_clan_l = $pdo->prepare("INSERT INTO `clan_log` SET `type` = :type, `log` = :log, `clan_id` = :clan_id, `time` = :time");
$ins_clan_l ->execute(array(':type' => 4, ':log' => "<a href='/hero/$user[id]' style='display:inline;text-decoration:underline;padding:0px;'>$user[nick]</a> исключил(а) игрока <a href='/hero/$all[id]' style='display:inline;text-decoration:underline;padding:0px;'>$all[nick]</a>", ':clan_id' => $clan_id, ':time' => time())); 
header("Location: /clan/users/$clan_id");
}else{
header("Location: /clan/users/$clan_id");
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /create_clan');
$_SESSION['err'] = 'Данные не переданы!';
exit();
}
}
?>