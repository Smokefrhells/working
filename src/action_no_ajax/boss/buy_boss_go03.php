<?php
require_once '../../system/system.php';
echo only_reg();
#-Ускоряем время отдыха Босса-#
switch($act){
case 'accel':
if(isset($_GET['id'])){
$id = check($_GET['id']);
#-Выборка Босса-#
$sel_boss = $pdo->prepare("SELECT * FROM `boss` WHERE `id` = :id");
$sel_boss->execute(array(':id' => $id));
if($sel_boss-> rowCount() != 0){
$boss = $sel_boss->fetch(PDO::FETCH_LAZY);
#-Проверяем время-#
$sel_boss_t = $pdo->prepare("SELECT * FROM `boss_time` WHERE `boss_id` = :boss_id AND `user_id` = :user_id  AND `type` = :type");
$sel_boss_t->execute(array(':boss_id' => $boss['id'], ':user_id' => $user['id'], ':type' => 2));
if($sel_boss_t-> rowCount() != 0){
$boss_t = $sel_boss_t->fetch(PDO::FETCH_LAZY);
$boss_ostatok = $boss_t['time']-time(); //Высчитываем сколько осталось времени
#-Золото за ускорение времени-#
$hour = floor($boss_ostatok/3600);
if($hour <= 0){
$min = round((($boss_ostatok/60%60) * 60) /85, 0);
if($min < 1){
$gold_time = 1;
}else{
$gold_time = round($min, 0);
}
}else{
$minut = ($boss_ostatok/60%60) * 60;
$hou = ($hour * 3600);
$summa = round($minut+$hou, 0);
$gold_time = round($summa / 85, 0);
}
#-Достаточно ли золота-#
if($user['gold'] >= $gold_time){
#-Отнимаем деньги у игрока-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold WHERE `id` = :id");
$upd_users->execute(array(':gold' => $user['gold'] - $gold_time, ':id' => $user['id'])); 
#-Удаляем время-#
$del_boss_t = $pdo->prepare("DELETE FROM `boss_time` WHERE `boss_id` = :boss_id AND `user_id` = :user_id AND `type` = 2");
$del_boss_t->execute(array(':boss_id' => $boss['id'], ':user_id' => $user['id']));
header("Location: /boss?type=$boss[type]");
}else{
header("Location: /boss?type=$boss[type]");
$_SESSION['err'] = 'Недостаточно золота!';
exit();
}
}else{
header("Location: /boss?type=$boss[type]");
$_SESSION['err'] = 'Босс не на отдыхе!';
exit();
}
}else{
header('Location: /boss');
$_SESSION['err'] = 'Босс не найден!';
exit();
}
}else{
header('Location: /boss');
$_SESSION['err'] = 'Ошибка!';
exit();
}
}

#-Воскрешение-#
switch($act){
case 'res':
#-Выборка данных о бое-#
$sel_boss_users = $pdo->prepare("SELECT * FROM `boss_users` WHERE `user_id` = :user_id");
$sel_boss_users->execute(array(':user_id' => $user['id']));
if($sel_boss_users-> rowCount() != 0){
$boss_u = $sel_boss_users->fetch(PDO::FETCH_LAZY);
#-Делаем выборку боя монстра-#
$sel_boss_battle = $pdo->prepare("SELECT * FROM `boss_battle` WHERE `id` = :id");
$sel_boss_battle->execute(array(':id' => $boss_u['battle_id']));
if($sel_boss_battle-> rowCount() != 0){
$battle = $sel_boss_battle->fetch(PDO::FETCH_LAZY);
#-Проверяем последний игрок или нет-#
$sel_boss_live = $pdo->prepare("SELECT COUNT(*) FROM `boss_users` WHERE `battle_id` = :battle_id AND `user_t_health` > 0");
$sel_boss_live->execute(array(':battle_id' => $boss_u['battle_id']));
$boss_live = $sel_boss_live->fetch(PDO::FETCH_LAZY);
if($boss_live[0] >= 1){
$vosk = ($battle['boss_id']*10)+20; //Цена за воскрешение
#-Достаточно ли золота и здоровье равно 0-#
if($user['gold'] >= $vosk and $boss_u['user_t_health'] == 0){
#-Минусуем деньги у игрока-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold'] - $vosk, ':id' => $user['id'])); 
#-Воскрешаем-#
$upd_boss_u = $pdo->prepare("UPDATE `boss_users` SET `user_t_health` = :user_t_health, `user_health` = :user_t_health WHERE `id` = :id LIMIT 1");
$upd_boss_u->execute(array(':user_t_health' => $user['health']+$user['s_health']+$user['health_bonus'], ':id' => $boss_u['id']));
#-Лог-#
if($user['pol'] == 1){$at = 'воскрес';}else{$at = 'воскресла';}
$ins_log = $pdo->prepare("INSERT INTO `boss_log` SET `battle_id` = :battle_id, `log` = :log, `time` = :time");
$ins_log->execute(array(':battle_id' => $battle['id'], ':log' => "<img src='/style/images/user/health.png' alt=''/> <span style='color: #00a800;'>$user[nick] $at</span>", ':time' => time()));
header("Location: /boss_battle");
}else{
header('Location: /boss_battle');
$_SESSION['err'] = 'Недостаточно золота или вы не мертвы!';
exit();
}
}else{
header('Location: /boss_battle');
}
}else{
header('Location: /boss_battle');
}
}else{
header('Location: /boss_battle');
}
}
?>