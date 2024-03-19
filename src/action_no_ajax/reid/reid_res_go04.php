<?php
require_once '../../system/system.php';
echo only_reg();
echo reid_level();
#-Воскрешение-#
switch($act){
case 'res':
$user_health = $user['health']+$user['s_health']+$user['health_bonus']; //Здоровье игрока
#-Босс должен быть и статус 1-#
$sel_reid_b = $pdo->query("SELECT * FROM `reid_boss` WHERE `statys` = 1");
if($sel_reid_b-> rowCount() == 0) $error = 'Сражение не найдено!';
#-Игрок должен быть в списке с нулевым здоровьем-#
$sel_reid_u = $pdo->prepare("SELECT * FROM `reid_users` WHERE `user_id` = :user_id AND `user_t_health` = 0 AND `user_vosk` < 4");
$sel_reid_u->execute(array(':user_id' => $user['id']));
if($sel_reid_u-> rowCount() == 0) $error = 'Ошибка воскрешения!';
#-Если нет ошибок-#
if(!isset($error)){
$reid_b = $sel_reid_b->fetch(PDO::FETCH_LAZY);
$reid_u = $sel_reid_u->fetch(PDO::FETCH_LAZY);
$gold_v = ($reid_b['type']*15)*$reid_u['user_vosk']; //Необходимое кол-во золота
if($user['gold'] >= $gold_v){
#-Минусуем золото-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold WHERE `id` = :user_id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']-$gold_v, ':user_id' => $user['id']));
#-Воскрешаем-#
$upd_reid_u = $pdo->prepare("UPDATE `reid_users` SET `user_health` = :user_health, `user_t_health` = :user_health, `user_vosk` = :user_vosk WHERE `id` = :id LIMIT 1");
$upd_reid_u->execute(array(':user_health' => $user_health, ':user_vosk' => $reid_u['user_vosk']+1, ':id' => $reid_u['id']));
#-Лог-#
if($user['pol'] == 1){$at = 'воскрес';}else{$at = 'воскресла';}
$ins_log = $pdo->prepare("INSERT INTO `reid_log` SET `log` = :log, `time` = :time");
$ins_log->execute(array(':log' => "<img src='/style/images/user/health.png' alt=''/> <span style='color: #00a800;'>$user[nick] $at</span>", ':time' => time()));
header('Location: /reid');
exit;
}else{
header('Location: /reid');
$_SESSION['err'] = 'Недостаточно золота!';
exit();
}
}else{
header('Location: /reid');
$_SESSION['err'] = $error;
exit();
}
}
?>