<?php
require_once '../../system/system.php';
echo only_reg();
/*Действие над зельем*/
switch($act){
case 'isp':
if(isset($_GET['id'])){
$id = check($_GET['id']);
#-Проверяем ввод цифры-#
if(!preg_match('/^([0-9])+$/u',$_GET['id'])) $error = 'Введите цифру!';
#-Выборка данных о бое-#
$sel_reid_u = $pdo->prepare("SELECT * FROM `reid_users` WHERE `user_id` = :user_id");
$sel_reid_u->execute(array(':user_id' => $user['id']));
if($sel_reid_u-> rowCount() != 0){
$reid_u = $sel_reid_u->fetch(PDO::FETCH_LAZY);
$user_health = $user['health']+$user['s_health']+$user['health_bonus'];
#-Время до использования-#
if($reid_u['user_isp'] >= time()) $error = 'Время еще не вышло!';
#-Проверяем нужно ли восстановление-#
if($reid_u['user_t_health'] < $user_health){
#-Выборка данных зелье-#
$sel_potions_me = $pdo->prepare("SELECT * FROM `potions_me` WHERE `potions_id` = :potions_id AND `user_id` = :user_id");
$sel_potions_me->execute(array(':potions_id' => $id, ':user_id' => $user['id']));
#-Только если есть такое зелье-#
if($sel_potions_me-> rowCount() != 0){
#-Если нет ошибок-#
if(!isset($error)){
$potions_me = $sel_potions_me->fetch(PDO::FETCH_LAZY);
#-Делаем выборку зелья в целом-#
$sel_potions = $pdo->prepare("SELECT * FROM `potions` WHERE `id` = :id");
$sel_potions->execute(array(':id' => $id));
#-Только если есть такое зелье-#
if($sel_potions-> rowCount() != 0){
$potions = $sel_potions->fetch(PDO::FETCH_LAZY);
#-Если тип равен 1, то добавляем здоровья-#
if($potions['type'] == 1){
#-Если количество больше 1 то просто отнимаем кол-во-#
if($potions_me['quatity'] >= 2){
$upd_potions_me = $pdo->prepare("UPDATE `potions_me` SET `quatity` = :quatity WHERE `potions_id` = :potions_id AND `user_id` = :user_id LIMIT 1");
$upd_potions_me->execute(array(':quatity' => $potions_me['quatity'] - 1, ':potions_id' => $potions_me['potions_id'], ':user_id' => $user['id']));
}else{
$del_potions_me = $pdo->prepare("DELETE FROM `potions_me` WHERE `potions_id` = :potions_id AND `user_id` = :user_id");
$del_potions_me->execute(array(':potions_id' => $potions_me['potions_id'], ':user_id' => $user['id']));
}

#-Высчитываем проценты от здоровья-#
if($potions['id'] == 1){
$health_prosent = round(($user['health']+$user['s_health']+$user['health_bonus']) * 0.10, 0);
}
if($potions['id'] == 2){
$health_prosent = round(($user['health']+$user['s_health']+$user['health_bonus']) * 0.30, 0);
}
if($potions['id'] == 3){
$health_prosent = round(($user['health']+$user['s_health']+$user['health_bonus']) * 0.50, 0);
}
#-Здоровье с восстановлением-#
$health = $reid_u['user_t_health'] + $health_prosent;

#-Если здоровья больше чем максимальное , то ставим на максимум-#
if($health <= $user_health){
#-Восстановление-#
$upd_reid_u = $pdo->prepare("UPDATE `reid_users` SET `user_t_health` = :user_t_health, `user_isp` = :time WHERE `id` = :id LIMIT 1");
$upd_reid_u->execute(array(':user_t_health' => $health, ':time' => time()+30, ':id' => $reid_u['id']));
#-Лог-#
if($user['pol'] == 1){$at = 'восстановил';}else{$at = 'восстановила';}
$ins_log = $pdo->prepare("INSERT INTO `reid_log` SET `log` = :log, `time` = :time");
$ins_log->execute(array(':log' => "<img src='/style/images/user/health.png' alt=''/> <span style='color: #00a800;'>$user[nick] $at $health_prosent здоровья</span>", ':time' => time()));
header('Location: /reid');
$_SESSION['notif'] = '<img src="/style/images/user/health.png" alt=""/> <span class="green">Вы</span> восстановили '.$health_prosent.' здоровья';
exit();
}else{
#-Восстановление-#
$upd_reid_u = $pdo->prepare("UPDATE `reid_users` SET `user_t_health` = :user_t_health, `user_isp` = :time WHERE `id` = :id LIMIT 1");
$upd_reid_u->execute(array(':user_t_health' => $user_health , ':time' => time()+30, ':id' => $reid_u['id']));
#-Лог-#
if($user['pol'] == 1){$at = 'восстановил';}else{$at = 'восстановила';}
$ins_log = $pdo->prepare("INSERT INTO `reid_log` SET `log` = :log, `time` = :time");
$ins_log->execute(array(':log' => "<img src='/style/images/user/health.png' alt=''/> <span style='color: #00a800;'>$user[nick] $at максимальное здоровье</span>", ':time' => time()));
header('Location: /reid');
$_SESSION['notif'] = '<img src="/style/images/user/health.png" alt=""/><span class="green">Вы</span> восстановили максимальное здоровье';
exit();
}
}
}else{
header ('Location: /reid');
$_SESSION['err'] = 'Зелье не найдено!';
exit();	
}
}else{
header ('Location: /reid');
$_SESSION['err'] = $error;
exit();
}
}else{
header ('Location: /reid');
$_SESSION['err'] = 'Зелье не найдено!';
exit();
}
}else{
header ('Location: /reid');
$_SESSION['notif'] = '<img src="/style/images/user/health.png" alt=""/><span class="green">Вы</span> не нуждаетесь в восстановлении';
exit();	
}
}else{
header ('Location: /reid');
$_SESSION['err'] = 'Ошибка боя!';
exit();	
}
}
}
?>