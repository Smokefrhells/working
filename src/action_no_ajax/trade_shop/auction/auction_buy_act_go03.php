<?php
require_once '../../../system/system.php';
echo only_reg();

#-Покупка снаряжение в аукционе-#
switch($act){
case 'buy':
if($user['level'] >= 10 and isset($_GET['weapon_id'])){
$weapon_id = check($_GET['weapon_id']);
$page = $_GET['page'];

#-Проверка снаряжения-#
$sel_weapon_me = $pdo->prepare("SELECT * FROM `weapon_me` WHERE `id` = :weapon_id AND `user_id` != :user_id AND `runa` = 0 AND `state` = 0 AND `auction` = 1 AND `gold` > 0 AND `silver` > 0");
$sel_weapon_me->execute(array(':weapon_id' => $weapon_id, ':user_id' => $user['id']));
if($sel_weapon_me-> rowCount() == 0) $error = 'Снаряжение не найдено!';

#-Если нет ошибок-#
if(!isset($error)){
$weapon_me = $sel_weapon_me->fetch(PDO::FETCH_LAZY);

#-Выборка данных-#
$sel_weapon = $pdo->prepare("SELECT `id`, `name` FROM `weapon` WHERE `id` = :weapon_id");
$sel_weapon->execute(array(':weapon_id' => $weapon_me['weapon_id']));
$weapon = $sel_weapon->fetch(PDO::FETCH_LAZY);	

#-Достаточно золота-#
if($user['gold'] >= $weapon_me['gold'] and $user['silver'] >= $weapon_me['silver']){

#-Минус средств-#
$upd_users_m = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `silver` = :silver WHERE `id` = :user_id LIMIT 1");
$upd_users_m->execute(array(':gold' => $user['gold']-$weapon_me['gold'], ':silver' => $user['silver']-$weapon_me['silver'], ':user_id' => $user['id']));
#-Плюс средств-#
$upd_users_p = $pdo->prepare("UPDATE `users` SET `gold` = `gold` + :gold, `silver` = `silver` + :silver WHERE `id` = :user_id LIMIT 1");
$upd_users_p->execute(array(':gold' => $weapon_me['gold'], ':silver' => $weapon_me['silver'], ':user_id' => $weapon_me['user_id']));
#-Лог-#
$ins_event = $pdo->prepare("INSERT INTO `event_log` SET `type` = :type, `log` = :log, `user_id` = :user_id, `time` = :time");
$ins_event->execute(array(':type' => 11, ':log' => "$user[nick] купил(а) $weapon[name] за: <img src='/style/images/many/gold.png' alt=''/>$weapon_me[gold] <img src='/style/images/many/silver.png' alt=''/>$weapon_me[silver]", ':user_id' => $weapon_me['user_id'], ':time' => time()));

#-Передача вещи-#
$upd_weapon_me = $pdo->prepare("UPDATE `weapon_me` SET `auction` = 0, `gold` = 0, `silver` = 0, `time` = 0, `user_id` = :user_id WHERE `id` = :weapon_id LIMIT 1");
$upd_weapon_me->execute(array(':user_id' => $user['id'], ':weapon_id' => $weapon_me['id']));
header("Location: /auction?page=$page");
$_SESSION['ok'] = 'Куплено!';
exit();	
}else{
header("Location: /auction?page=$page");
$_SESSION['err'] = 'Недостаточно средств!';
exit();
}
}else{
header("Location: /auction?page=$page");
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /trade_shop');
$_SESSION['err'] = 'Данные не переданы!';
exit();
}
}
?>