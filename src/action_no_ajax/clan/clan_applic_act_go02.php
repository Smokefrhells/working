<?php
require_once '../../system/system.php';
echo only_reg();
echo save();
echo clan_level();
#-Подаем заявку на вступление-#
switch($act){
case 'add':
if(isset($_GET['clan_id'])){
$clan_id = check($_GET['clan_id']); //ID клана
#-Проверяем что мы не состоим в клане-#
$sel_clan_u = $pdo->prepare("SELECT `user_id` FROM `clan_users` WHERE `user_id` = :user_id");
$sel_clan_u->execute(array(':user_id' => $user['id']));
if($sel_clan_u -> rowCount() != 0) $error = 'Вы состоите в клане!';
#-Проверяем что такой клан есть-#
$sel_clan = $pdo->prepare("SELECT `id`, `quatity_user` FROM `clan` WHERE `id` = :clan_id");
$sel_clan->execute(array(':clan_id' => $clan_id));
if($sel_clan -> rowCount() == 0) $error = 'Клан не найден!';
#-Проверяем что заявки нет-#
$sel_clan_app = $pdo->prepare("SELECT `clan_id`, `user_id` FROM `clan_application` WHERE `clan_id` = :clan_id AND `user_id` = :user_id");
$sel_clan_app->execute(array(':clan_id' => $clan_id, ':user_id' => $user['id']));
if($sel_clan_app -> rowCount() != 0) $error = 'Вы подали заявку!';
#-Если нет ошибок-#
if(!isset($error)){
$clan = $sel_clan->fetch(PDO::FETCH_LAZY); //Клан
#-Проверяем что игроков меньше чем нужно-#
$sel_clan_u_c = $pdo->prepare("SELECT COUNT(*) FROM `clan_users` WHERE `clan_id` = :clan_id");
$sel_clan_u_c->execute(array(':clan_id' => $clan['id']));
$amount_u = $sel_clan_u_c->fetch(PDO::FETCH_LAZY);
if($amount_u[0] < $clan['quatity_user']){
#-Создаем заявку-#
$ins_clan_app = $pdo->prepare("INSERT INTO `clan_application` SET `clan_id` = :clan_id, `user_id` = :user_id, `time` = :time");
$ins_clan_app->execute(array(':clan_id' => $clan_id, ':user_id' => $user['id'], ':time' => time()));
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

#-Отзываем заявку-#
switch($act){
case 'del':
if(isset($_GET['clan_id'])){
$clan_id = check($_GET['clan_id']); //ID клана
#-Проверяем что такой клан есть-#
$sel_clan = $pdo->prepare("SELECT `id` FROM `clan` WHERE `id` = :clan_id");
$sel_clan->execute(array(':clan_id' => $clan_id));
if($sel_clan -> rowCount() == 0) $error = 'Клан не найден!';
#-Проверяем что заявки есть-#
$sel_clan_app = $pdo->prepare("SELECT `clan_id`, `user_id` FROM `clan_application` WHERE `clan_id` = :clan_id AND `user_id` = :user_id");
$sel_clan_app->execute(array(':clan_id' => $clan_id, ':user_id' => $user['id']));
if($sel_clan_app -> rowCount() == 0) $error = 'Заявка не найдена!';
#-Если нет ошибок-#
if(!isset($error)){
#-Удаляем заявку-#
$del_clan_a = $pdo->prepare("DELETE FROM `clan_application` WHERE `clan_id` = :clan_id AND `user_id` = :user_id");
$del_clan_a->execute(array(':clan_id' => $clan_id, ':user_id' => $user['id']));
header("Location: /clan/view/$clan_id");
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

#-Принимаем заявку-#
switch($act){
case 'ok':
if(isset($_GET['clan_id']) and isset($_GET['app_id']) and isset($_GET['user_id'])){
$clan_id = check($_GET['clan_id']); //ID клана
$app_id = check($_GET['app_id']); //ID заявки
$user_id = check($_GET['user_id']); //ID игрока
#-Проверяем что клан существует-#
$sel_clan = $pdo->prepare("SELECT `id`, `quatity_user` FROM `clan` WHERE `id` = :clan_id");
$sel_clan->execute(array(':clan_id' => $clan_id));
if($sel_clan -> rowCount() == 0) $error = 'Клан не найден!';
#-Проверяем что мы состоим в этом клане-#
$sel_clan_u = $pdo->prepare("SELECT * FROM `clan_users` WHERE `clan_id` = :clan_id AND `user_id` = :user_id AND (`prava` = 4 OR `prava` = 3 OR `prava` = 2)");
$sel_clan_u->execute(array(':clan_id' => $clan_id, ':user_id' => $user['id']));
if($sel_clan_u -> rowCount() == 0) $error = 'Вы не состоите в клане или нет прав!';
#-Проверяем что игрок не состоит в клане-#
$sel_clan_u2 = $pdo->prepare("SELECT * FROM `clan_users` WHERE `user_id` = :user_id");
$sel_clan_u2->execute(array(':user_id' => $user_id));
if($sel_clan_u2 -> rowCount() != 0) $error = 'Игрок уже состоит в клане!';
#-Проверяем что есть заявка-#
$sel_clan_a = $pdo->prepare("SELECT * FROM `clan_application` WHERE `id` = :app_id");
$sel_clan_a->execute(array(':app_id' => $app_id));
if($sel_clan_a -> rowCount() == 0) $error = 'Заявка не найдена!';
#-Проверяем что игрок существует-#
$sel_users = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :id");
$sel_users->execute(array(':id' => $user_id));
if($sel_users -> rowCount() == 0) $error = 'Игрок не найден!';
#-Если нет ошибок-#
if(!isset($error)){
$all = $sel_users->fetch(PDO::FETCH_LAZY); //Игрок
$clan = $sel_clan->fetch(PDO::FETCH_LAZY); //Клан
$clan_u = $sel_clan_u->fetch(PDO::FETCH_LAZY); //Игрок клана
$clan_a = $sel_clan_a->fetch(PDO::FETCH_LAZY); //Заявка
#-Проверяем что игроков меньше чем нужно-#
$sel_clan_u_c = $pdo->prepare("SELECT COUNT(*) FROM `clan_users` WHERE `clan_id` = :clan_id");
$sel_clan_u_c->execute(array(':clan_id' => $clan['id']));
$amount_u = $sel_clan_u_c->fetch(PDO::FETCH_LAZY);
if($amount_u[0] < $clan['quatity_user']){
#-Удаляем все заявки игрока который проситься в клан-#
$del_clan_a = $pdo->prepare("DELETE FROM `clan_application` WHERE `user_id` = :user_id");
$del_clan_a->execute(array(':user_id' => $clan_a['user_id']));
#-Добавляем в клан-#
$ins_clan_u = $pdo->prepare("INSERT INTO `clan_users` SET `clan_id` = :clan_id, `user_id` = :user_id, `time` = :time");
$ins_clan_u->execute(array(':clan_id' => $clan_id, ':user_id' => $clan_a['user_id'], ':time' => time()));
#-Записываем ID клана-#
$upd_users = $pdo->prepare("UPDATE `users` SET `clan_id` = :clan_id WHERE `id` = :id");
$upd_users->execute(array(':clan_id' => $clan['id'], ':id' => $all['id']));
#-История клана-#
$ins_clan_l = $pdo->prepare("INSERT INTO `clan_log` SET `type` = :type, `log` = :log, `clan_id` = :clan_id, `time` = :time");
$ins_clan_l ->execute(array(':type' => 1, ':log' => "<a href='/hero/$user[id]' style='display:inline;text-decoration:underline;padding:0px;'>$user[nick]</a> принял(а) заявку <a href='/hero/$all[id]' style='display:inline;text-decoration:underline;padding:0px;'>$all[nick]</a> в клан", ':clan_id' => $clan_id, ':time' => time())); 
header("Location: /clan/application/$clan_id");
}else{
header("Location: /clan/application/$clan_id");
$_SESSION['err'] = 'В клане максимальный состав!';
exit();
}
}else{
header("Location: /clan/application/$clan_id");
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /clan');
$_SESSION['err'] = 'Данные отсутствуют!';
exit();
}
}

#-Отказываем-#
switch($act){
case 'err':
if(isset($_GET['clan_id']) and isset($_GET['app_id'])){
$clan_id = check($_GET['clan_id']); //ID клана
$app_id = check($_GET['app_id']); //ID заявки
#-Проверяем что клан существует-#
$sel_clan = $pdo->prepare("SELECT `id` FROM `clan` WHERE `id` = :clan_id");
$sel_clan->execute(array(':clan_id' => $clan_id));
if($sel_clan -> rowCount() == 0) $error = 'Клан не найден!';
#-Проверяем что мы состоим в этом клане-#
$sel_clan_u = $pdo->prepare("SELECT * FROM `clan_users` WHERE `clan_id` = :clan_id AND `user_id` = :user_id");
$sel_clan_u->execute(array(':clan_id' => $clan_id, ':user_id' => $user['id']));
if($sel_clan_u -> rowCount() == 0) $error = 'Вы не состоите в клане!';
#-Проверяем что есть заявка-#
$sel_clan_a = $pdo->prepare("SELECT * FROM `clan_application` WHERE `id` = :app_id");
$sel_clan_a->execute(array(':app_id' => $app_id));
if($sel_clan_a -> rowCount() == 0) $error = 'Заявка не найдена!';
#-Если нет ошибок-#
if(!isset($error)){
$clan_u = $sel_clan_u->fetch(PDO::FETCH_LAZY); //Игрок клана
$clan_a = $sel_clan_a->fetch(PDO::FETCH_LAZY); //Заявка
#-Проверяем права-#
if($clan_u['prava'] == 3 or $clan_u['prava'] == 4){
#-Удаляем заявку на вступление-#
$del_clan_a = $pdo->prepare("DELETE FROM `clan_application` WHERE `clan_id` = :clan_id AND `user_id` = :user_id");
$del_clan_a->execute(array(':clan_id' => $clan_a['clan_id'], ':user_id' => $clan_a['user_id']));
header("Location: /clan/application/$clan_id");
}else{
header("Location: /clan/application/$clan_id");
$_SESSION['err'] = 'У вас нет прав!';
exit();
}
}else{
header("Location: /clan/application/$clan_id");
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