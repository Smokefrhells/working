<?php
require_once '../../system/system.php';
echo only_reg();
echo clan_level();
echo save();
#-Вступаем в клан-#
switch($act){
case 'join':
if(isset($_GET['clan_id'])){
$clan_id = check($_GET['clan_id']); //ID клана
#-Проверяем что мы не состоим в клане-#
$sel_clan_u = $pdo->prepare("SELECT `user_id` FROM `clan_users` WHERE `user_id` = :user_id");
$sel_clan_u->execute(array(':user_id' => $user['id']));
if($sel_clan_u -> rowCount() != 0) $error = 'Вы состоите в клане!';
#-Проверяем что такой клан есть-#
$sel_clan = $pdo->prepare("SELECT `id`, `quatity_user`, `close` FROM `clan` WHERE `id` = :clan_id");
$sel_clan->execute(array(':clan_id' => $clan_id));
if($sel_clan -> rowCount() == 0) $error = 'Клан не найден!';
#-Стоит время или нет-#
if($user['clan_time'] != 0) $error = 'Время не вышло!';
#-Если нет ошибок-#
if(!isset($error)){
$clan = $sel_clan->fetch(PDO::FETCH_LAZY); //Клан
#-Только если не через заявки-#
if($clan['close'] == 0){
#-Проверяем что игроков меньше чем нужно-#
$sel_clan_u_c = $pdo->prepare("SELECT COUNT(*) FROM `clan_users` WHERE `clan_id` = :clan_id");
$sel_clan_u_c->execute(array(':clan_id' => $clan['id']));
$amount_u = $sel_clan_u_c->fetch(PDO::FETCH_LAZY);
if($amount_u[0] < $clan['quatity_user']){
#-Удаляем все заявки игрока который проситься в клан-#
$del_clan_a = $pdo->prepare("DELETE FROM `clan_application` WHERE `user_id` = :user_id");
$del_clan_a->execute(array(':user_id' => $user['id']));
#-Добавляем в клан-#
$ins_clan_u = $pdo->prepare("INSERT INTO `clan_users` SET `clan_id` = :clan_id, `user_id` = :user_id, `time` = :time");
$ins_clan_u->execute(array(':clan_id' => $clan['id'], ':user_id' => $user['id'], ':time' => time()));
#-Записываем ID клана-#
$upd_users = $pdo->prepare("UPDATE `users` SET `clan_id` = :clan_id WHERE `id` = :id");
$upd_users->execute(array(':clan_id' => $clan['id'], ':id' => $user['id']));
#-История клана-#
$ins_clan_l = $pdo->prepare("INSERT INTO `clan_log` SET `type` = :type, `log` = :log, `clan_id` = :clan_id, `time` = :time");
$ins_clan_l ->execute(array(':type' => 1, ':log' => "<a href='/hero/$user[id]' style='display:inline;text-decoration:underline;padding:0px;'>$user[nick]</a> вступил(а) в клан", ':clan_id' => $clan_id, ':time' => time())); 
header("Location: /clan/view/$clan_id");
}else{
header("Location: /clan/application/$clan_id");
$_SESSION['err'] = 'В клане максимальный состав!';
exit();
}
}else{
header("Location: /clan/view/$clan_id");
$_SESSION['err'] = 'Только подача заявки!';
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