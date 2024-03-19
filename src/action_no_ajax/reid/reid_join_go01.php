<?php
require_once '../../system/system.php';
echo only_reg();
echo reid_level();
#-Вступление в сражение с мировым боссом-#
switch($act){
case 'join':
$user_health = $user['health']+$user['s_health']+$user['health_bonus']; //Здоровье игрока
#-Босс должен быть-#
$sel_reid_b = $pdo->query("SELECT * FROM `reid_boss`");
if($sel_reid_b-> rowCount() == 0) $error = 'Сражение не найдено!';
#-Игрока не должно быть в списке-#
$sel_reid_u = $pdo->prepare("SELECT * FROM `reid_users` WHERE `user_id` = :user_id");
$sel_reid_u->execute(array(':user_id' => $user['id']));
if($sel_reid_u-> rowCount() != 0) $error = 'Вы участвуете в сражении!';
#-Если нет ошибок-#
if(!isset($error)){
$reid_b = $sel_reid_b->fetch(PDO::FETCH_LAZY);
#-Добавляем в бой-#
$ins_reid_u = $pdo->prepare("INSERT INTO `reid_users` SET `user_id` = :user_id, `user_health` = :user_health, `user_t_health` = :user_health");
$ins_reid_u->execute(array(':user_id' => $user['id'], ':user_health' => $user_health));
#-Лог-#
if($reid_b['statys'] == 1){
if($user['pol'] == 1){$at = 'присоединился';}else{$at = 'присоединилась';}
$ins_log = $pdo->prepare("INSERT INTO `reid_log` SET `log` = :log, `time` = :time");
$ins_log->execute(array(':log' => "<img src='/style/images/user/user.png' alt=''/> <span style='color: #cb862c;'>$user[nick] $at к бою</span>", ':time' => time()));
}
header('Location: /reid');
exit;
}else{
header('Location: /reid');
$_SESSION['err'] = $error;
exit();
}
}
?>