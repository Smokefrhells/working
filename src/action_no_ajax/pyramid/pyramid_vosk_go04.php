<?php
require_once '../../system/system.php';
echo only_reg();

#-Воскрешение-#
switch($act){
case 'vosk':
#-Проверка что бой существует-#
$sel_pyramid_b = $pdo->query("SELECT * FROM `pyramid_battle_b`");
if($sel_pyramid_b->rowCount() == 0) $error = 'Бой еще не начат!';
#-Проверка что игрок участвует в бою-#
$sel_pyramid_u = $pdo->prepare("SELECT * FROM `pyramid_battle_u` WHERE `user_id` = :user_id AND `health` = 0 AND `vosk` < 10");
$sel_pyramid_u->execute(array(':user_id' => $user['id']));
if($sel_pyramid_u->rowCount() == 0) $error = 'Воскрешение не нужно или не участник боя!';
#-Проверка что достаточно золота-#
if($user['gold'] < 150) $error = 'Недостаточно золота!';

#-Если нет ошибок-#
if(!isset($error)){
$pyramid_u = $sel_pyramid_u->fetch(PDO::FETCH_LAZY);
#-Воскрешение-#
$upd_pyramid_u = $pdo->prepare("UPDATE `pyramid_battle_u` SET `health` = :health, `vosk` = `vosk`+1 WHERE `user_id` = :user_id LIMIT 1");	
$upd_pyramid_u->execute(array(':health' => $pyramid_u['max_health'], ':user_id' => $user['id']));
#-Запись лога-#
$ins_pyramid_log = $pdo->prepare("INSERT INTO `pyramid_battle_l` SET `log` = :log");
$ins_pyramid_log->execute(array(':log' => "<img src='/style/images/user/health.png'/> <span class='yellow'>$user[nick] воскрес</span>"));
#-Минус золото-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold WHERE `id` = :user_id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']-150, ':user_id' => $user['id']));
header('Location: /pyramid');
exit();	
}else{
header('Location: /pyramid');
$_SESSION['err'] = $error;
exit();	
}
}
?>