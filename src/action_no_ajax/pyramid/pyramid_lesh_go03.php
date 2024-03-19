<?php
require_once '../../system/system.php';
echo only_reg();

#-Лечение-#
switch($act){
case 'lesh':
#-Проверка что бой существует-#
$sel_pyramid_b = $pdo->query("SELECT * FROM `pyramid_battle_b`");
if($sel_pyramid_b->rowCount() == 0) $error = 'Бой еще не начат!';
#-Проверка что игрок участвует в бою-#
$sel_pyramid_u = $pdo->prepare("SELECT * FROM `pyramid_battle_u` WHERE `user_id` = :user_id AND `health` != 0 AND `health` < `max_health` AND `lesh` < 10");
$sel_pyramid_u->execute(array(':user_id' => $user['id']));
if($sel_pyramid_u->rowCount() == 0) $error = 'Восстановление не нужно или не участник боя!';
#-Проверка что достаточно золота-#
if($user['gold'] < 100) $error = 'Недостаточно золота!';

#-Если нет ошибок-#
if(!isset($error)){
$pyramid_u = $sel_pyramid_u->fetch(PDO::FETCH_LAZY);
$lesh_health = $pyramid_u['max_health'] * 0.50; //50% от восстановления

#-Восстановление максимума или %-#
if(($pyramid_u['health']+$lesh_health) > $pyramid_u['max_health']){
#-Восстановление максимального здоровья-#
$upd_pyramid_u = $pdo->prepare("UPDATE `pyramid_battle_u` SET `health` = :max_health, `lesh` = `lesh`+1 WHERE `user_id` = :user_id LIMIT 1");	
$upd_pyramid_u->execute(array(':max_health' => $pyramid_u['max_health'], ':user_id' => $user['id']));	
#-Запись лога-#
$ins_pyramid_log = $pdo->prepare("INSERT INTO `pyramid_battle_l` SET `log` = :log");
$ins_pyramid_log->execute(array(':log' => "<img src='/style/images/user/health.png'/> <span class='green'>$user[nick] восстановил(ла) максимальное здоровье</span>"));
}else{
#-Востановление 50% здоровья-#
$upd_pyramid_u = $pdo->prepare("UPDATE `pyramid_battle_u` SET `health` = :health, `lesh` = `lesh`+1 WHERE `user_id` = :user_id LIMIT 1");	
$upd_pyramid_u->execute(array(':health' => $pyramid_u['health']+$lesh_health, ':user_id' => $user['id']));
#-Запись лога-#
$ins_pyramid_log = $pdo->prepare("INSERT INTO `pyramid_battle_l` SET `log` = :log");
$ins_pyramid_log->execute(array(':log' => "<img src='/style/images/user/health.png'/> <span class='green'>$user[nick] восстановил(ла) $lesh_health</span>"));
}
#-Минус золото-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold WHERE `id` = :user_id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']-100, ':user_id' => $user['id']));
header('Location: /pyramid');
exit();	
}else{
header('Location: /pyramid');
$_SESSION['err'] = $error;
exit();	
}
}
?>