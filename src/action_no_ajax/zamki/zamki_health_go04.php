<?php
require_once '../../system/system.php';
echo only_reg();
echo zamki_level();

#-Лечение здоровья замка-#
switch($act){
case 'health':
#-Проверяем что есть сражения-#
$sel_zamki = $pdo->query("SELECT * FROM `zamki` WHERE `statys` = 1");
if($sel_zamki-> rowCount() == 0) $error = 'Ошибка!';
#-Проверяем что участвуем в сражении-#
$sel_zamki_u = $pdo->prepare("SELECT `id`, `user_id`, `quatity_health`, `storona` FROM `zamki_users` WHERE `user_id` = :user_id AND `quatity_health` = 0");
$sel_zamki_u->execute(array(':user_id' => $user['id']));
if($sel_zamki_u-> rowCount() == 0) $error = 'Ошибка!';
#-Если не достаточно золота-#
if($user['gold'] < 85) $error = 'Недостаточно золота!';

#-Если нет ошибок-#
if(!isset($error)){
$zamki = $sel_zamki->fetch(PDO::FETCH_LAZY);
$zamki_u = $sel_zamki_u->fetch(PDO::FETCH_LAZY);
#-Здоровье игрока-#
$user_health = $user['health']+$user['s_health']+$user['health_bonus'];

if($zamki_u['storona'] == 'right'){
	
if(($zamki['health_t_right']+$user_health) > $zamki['health_max_right']){
$health_right = $zamki['health_max_right'];	
}else{
$health_right = $zamki['health_t_right']+$user_health;	
}
#-Добавление здоровья правым-#
$upd_zamki = $pdo->prepare("UPDATE `zamki` SET `health_right` = :health_right, `health_t_right` = :health_right LIMIT 1");
$upd_zamki->execute(array(':health_right' => $health_right));
#-Запись лога-#
$ins_log = $pdo->prepare("INSERT INTO `zamki_log` SET `log` = :log, `storona` = :storona, `time` = :time");
$ins_log->execute(array(':log' => "<img src='/style/images/body/zamki.png' alt=''/>Замок Правых: вылечено <img src='/style/images/user/health.png' alt=''/>$user_health", ':storona' => 'right', ':time' => time()));
}else{
	
if(($zamki['health_t_left']+$user_health) > $zamki['health_max_left']){
$health_left = $zamki['health_max_left'];	
}else{
$health_left = $zamki['health_t_left']+$user_health;	
}
#-Добавление здоровья левым-#
$upd_zamki = $pdo->prepare("UPDATE `zamki` SET `health_left` = :health_left, `health_t_left` = :health_left LIMIT 1");
$upd_zamki->execute(array(':health_left' => $health_left));
#-Запись лога-#
$ins_log = $pdo->prepare("INSERT INTO `zamki_log` SET `log` = :log, `storona` = :storona, `time` = :time");
$ins_log->execute(array(':log' => "<img src='/style/images/body/zamki.png' alt=''/>Замок Левых: вылечено <img src='/style/images/user/health.png' alt=''/>$user_health", ':storona' => 'left', ':time' => time()));
}

#-Минус золота у игрока-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold WHERE `id` = :user_id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']-85, ':user_id' => $user['id']));
#-Использовать можно только 1 раз-#
$upd_zamki_u = $pdo->prepare("UPDATE `zamki_users` SET `quatity_health` = 1 WHERE `user_id` = :user_id LIMIT 1");
$upd_zamki_u->execute(array(':user_id' => $user['id']));
header('Location: /zamki_battle');
exit();	
}else{
header('Location: /zamki_battle');
$_SESSION['err'] = $error;
exit();	
}
}
?>