<?php
require_once '../../system/system.php';
echo only_reg();
echo clan_level();
echo save();
#-Повышаем или понижаем права игрока-#
switch($act){
case 'go':
if(isset($_GET['clan_id']) and isset($_GET['clan_user']) and isset($_POST['prava'])){
$clan_id = check($_GET['clan_id']); //ID клана
$clan_user = check($_GET['clan_user']); //ID игрока
$prava = check($_POST['prava']); //Права которые ставим
$page = check($_GET['page']);
$type = check($_GET['type']);
#-Проверяем права-#
if(!in_array($prava, array('0', '1', '2', '3'))) $error = 'Ошибка прав!';
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
if($sel_clan_u2 -> rowCount() == 0) $error = 'Действие невозможно!';
}
if($me['prava'] == 4){
#-Проверяем что игрок состоит в клане-#
$sel_clan_u2 = $pdo->prepare("SELECT * FROM `clan_users` WHERE `clan_id` = :clan_id AND `user_id` = :user_id AND `prava` != 4");
$sel_clan_u2->execute(array(':clan_id' => $clan_id, ':user_id' => $clan_user));
if($sel_clan_u2 -> rowCount() == 0) $error = 'Действие невозможно!';
}
#-Проверяем что игрок существует-#
$sel_users = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :id");
$sel_users->execute(array(':id' => $clan_user));
if($sel_users -> rowCount() == 0) $error = 'Игрок не найден!';
#-Если нет ошибок-#
if(!isset($error)){
$all = $sel_users->fetch(PDO::FETCH_LAZY);
$clan_u2 = $sel_clan_u2->fetch(PDO::FETCH_LAZY);
#-Ставим права игроку-#
$upd_clan_u = $pdo->prepare("UPDATE `clan_users` SET `prava` = :prava WHERE `clan_id` = :clan_id AND `user_id` = :clan_user");
$upd_clan_u->execute(array(':prava' => $prava, ':clan_id' => $clan_id, ':clan_user' => $clan_user));
#-История клана-#
if($prava > $clan_u2['prava']){
$p = 'повысил(а)';
}else{
$p = 'понизил(а)';
}
if($prava == 0){$prav = 'Новичка';}
if($prava == 1){$prav = 'Бойца';}
if($prava == 2){$prav = 'Ветерана';}
if($prava == 3){$prav = 'Старейшины';}
$ins_clan_l = $pdo->prepare("INSERT INTO `clan_log` SET `type` = :type, `log` = :log, `clan_id` = :clan_id, `time` = :time");
$ins_clan_l ->execute(array(':type' => 5, ':log' => "<a href='/hero/$user[id]' style='display:inline;text-decoration:underline;padding:0px;'>$user[nick]</a> $p звание <a href='/hero/$all[id]' style='display:inline;text-decoration:underline;padding:0px;'>$all[nick]</a> до: $prav ", ':clan_id' => $clan_id, ':time' => time())); 
header("Location: /clan/users/$clan_id?page=$page&type=$type");
}else{
header("Location: /clan/users/$clan_id?page=$page&type=$type");
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /clan');
$_SESSION['err'] = 'Данные отсутствуют!';
exit();
}
}

#-Передача прав другому игроку-#
switch($act){
case 'osnova':
if(isset($_GET['clan_id']) and isset($_GET['clan_user'])){
$clan_id = check($_GET['clan_id']); //ID клана
$clan_user = check($_GET['clan_user']); //ID игрока
$page = check($_GET['page']);
$type = check($_GET['type']);
#-Проверяем что клан существует-#
$sel_clan = $pdo->prepare("SELECT `id` FROM `clan` WHERE `id` = :clan_id");
$sel_clan->execute(array(':clan_id' => $clan_id));
if($sel_clan -> rowCount() == 0) $error = 'Клан не найден!';
#-Проверяем что мы состоим в этом клане-#
$sel_clan_u = $pdo->prepare("SELECT * FROM `clan_users` WHERE `clan_id` = :clan_id AND `user_id` = :user_id AND `prava` = 4");
$sel_clan_u->execute(array(':clan_id' => $clan_id, ':user_id' => $user['id']));
if($sel_clan_u -> rowCount() == 0) $error = 'Вы не состоите в клане или у вас нет прав!';
#-Проверяем что игрок состоит в клане-#
$sel_clan_u2 = $pdo->prepare("SELECT * FROM `clan_users` WHERE `clan_id` = :clan_id AND `user_id` = :user_id AND `prava` != 4");
$sel_clan_u2->execute(array(':clan_id' => $clan_id, ':user_id' => $clan_user));
if($sel_clan_u2 -> rowCount() == 0) $error = 'Игрок не состоит в этом клане или это создатель!';
#-Проверяем что игрок существует-#
$sel_users = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :id");
$sel_users->execute(array(':id' => $clan_user));
if($sel_users -> rowCount() == 0) $error = 'Игрок не найден!';
#-Если нет ошибок-#
if(!isset($error)){
$all = $sel_users->fetch(PDO::FETCH_LAZY);
$clan_u2 = $sel_clan_u2->fetch(PDO::FETCH_LAZY);
#-Ставим права игроку-#
$upd_clan_u = $pdo->prepare("UPDATE `clan_users` SET `prava` = :prava WHERE `clan_id` = :clan_id AND `user_id` = :clan_user");
$upd_clan_u->execute(array(':prava' => 4, ':clan_id' => $clan_id, ':clan_user' => $clan_user));
#-Убираем права у основателя-#
$upd_clan_u2 = $pdo->prepare("UPDATE `clan_users` SET `prava` = :prava WHERE `clan_id` = :clan_id AND `user_id` = :clan_user");
$upd_clan_u2->execute(array(':prava' => 3, ':clan_id' => $clan_id, ':clan_user' => $user['id']));
#-Лог клана-#
$ins_clan_l = $pdo->prepare("INSERT INTO `clan_log` SET `type` = :type, `log` = :log, `clan_id` = :clan_id, `time` = :time");
$ins_clan_l ->execute(array(':type' => 5, ':log' => "<a href='/hero/$user[id]' style='display:inline;text-decoration:underline;padding:0px;'>$user[nick]</a> передал(а) права Основателя игроку <a href='/hero/$all[id]' style='display:inline;text-decoration:underline;padding:0px;'>$all[nick]</a>", ':clan_id' => $clan_id, ':time' => time())); 
header("Location: /clan/users/$clan_id?page=$page&type=$type");
}else{
header("Location: /clan/users/$clan_id?page=$page&type=$type");
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /clan');
$_SESSION['err'] = 'Данные отсутствуют!';
exit();
}
}
?>