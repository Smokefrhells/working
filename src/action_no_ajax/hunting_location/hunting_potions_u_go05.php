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
$sel_hunting_u = $pdo->prepare("SELECT * FROM `hunting_battle_u` WHERE `user_id` = :user_id");
$sel_hunting_u->execute(array(':user_id' => $user['id']));
if($sel_hunting_u-> rowCount() != 0){
$battle = $sel_hunting_u->fetch(PDO::FETCH_LAZY);
#-Время до использования-#
if($battle['user_isp'] >= time()) $error = 'Время еще не вышло!';
$user_health = $user['health']+$user['s_health']+$user['health_bonus'];
$user_t_health = $battle['user_t_health'];
#-Проверяем нужно ли восстановление-#
if($user_t_health < $user_health){
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
$health = $battle['user_t_health'] + $health_prosent;

#-Если здоровья большне чем максимальное , то ставим на максимум-#
if($health <= $user_health ){
$upd_battle_u = $pdo->prepare("UPDATE `hunting_battle_u` SET `user_t_health` = :user_t_health, `user_isp` = :time WHERE `id` = :id LIMIT 1");
$upd_battle_u->execute(array(':user_t_health' => $health, ':time' => time()+30, ':id' => $battle['id']));
#-Записываем лог-#
if($user['pol'] == 1){$at = 'восстановил';}else{$at = 'восстановила';}
$ins_log = $pdo->prepare("INSERT INTO `hunting_log` SET `location` = :location, `log` = :log, `user_id` = :user_id, `time` = :time");
$ins_log->execute(array(':location' => $battle['location'], ':log' => "<img src='/style/images/user/health.png' alt=''/> <span style='color: #00a800;'>$user[nick] $at $health_prosent здоровья</span>", ':user_id' => $user['id'], ':time' => time()));		
header ('Location: /hunting_battle_u');
exit();
}else{
$upd_battle_u = $pdo->prepare("UPDATE `hunting_battle_u` SET `user_t_health` = :user_t_health, `user_isp` = :time WHERE `id` = :id LIMIT 1");
$upd_battle_u->execute(array(':user_t_health' => $user_health, ':time' => time()+30, ':id' => $battle['id']));
#-Записываем лог-#
if($user['pol'] == 1){$at = 'восстановил';}else{$at = 'восстановила';}
$ins_log = $pdo->prepare("INSERT INTO `hunting_log` SET `location` = :location, `log` = :log, `user_id` = :user_id, `time` = :time");
$ins_log->execute(array(':location' => $battle['location'], ':log' => "<img src='/style/images/user/health.png' alt=''/> <span style='color: #00a800;'>$user[nick] $at максимальное здоровья</span>", ':user_id' => $user['id'], ':time' => time()));		
header ('Location: /hunting_battle_u');
exit();
}
}
}else{
header ('Location: /hunting_battle_u');
$_SESSION['err'] = 'Зелье не найдено!';
exit();	
}
}else{
header ('Location: /hunting_battle_u');
$_SESSION['err'] = $error;
exit();
}
}else{
header ('Location: /hunting_battle_u');
$_SESSION['err'] = 'Зелье не найдено!';
exit();
}
}else{
header ('Location: /hunting_battle_u');
$_SESSION['notif'] = '<img src="/style/images/user/health.png" alt=""/> <span class="green">Вы</span> не нуждаетесь в восстановлении';
exit();	
}
}else{
header ('Location: /hunting_battle_u');
$_SESSION['err'] = 'Ошибка боя!';
exit();	
}
}
}
?>