<?php
require_once '../../system/system.php';
global $user;
echo duel_level();
session_start();
#-Есть ли у нас доступные бои-#
$user_duel = floor($user['level']/2);
if($user['duel_b'] < $user_duel){
	
#-Начинаем бой в дуэлях-#
switch($act){
case 'start':
if(!isset($_SESSION['duel_id'])){
#-Выбираем игрока для дуэли-#
$min_level = $user['level'] - 5;
$max_level = $user['level'] + 5;
$sel_users = $pdo->prepare("SELECT `id`, `level`, `save` FROM `users` WHERE `level` > :min_level AND `level` < :max_level AND `id` != :user_id AND `save` = 1 ORDER BY RAND()");
$sel_users->execute(array(':min_level' => $min_level, ':max_level' => $max_level, ':user_id' => $user['id']));
if($sel_users->rowCount() != 0){
$all = $sel_users->fetch(PDO::FETCH_LAZY);
#-Записываем ID в сессию-#
$_SESSION['duel_id'] = $all['id'];
header("Location: /duel_battle");
}else{
header('Location: /duel_battle');
$_SESSION['err'] = 'Для вашего уровня нет игроков!';
exit();
}
}else{
header('Location: /duel_battle');
}
}

#-Смена врага в дуэлях-#
switch($act){
case 'next':
if(isset($_SESSION['duel_id'])){
$ank_id = $_SESSION['duel_id'];
$silver = $user['level'] * 42;
#-Достаточно ли серебра-#
if($user['silver'] >= $silver){
#-Выбираем игрока для дуэли-#
$min_level = $user['level'] - 5;
$max_level = $user['level'] + 5;
$sel_users = $pdo->prepare("SELECT `id`, `level`, `save` FROM `users` WHERE `level` > :min_level AND `level` < :max_level AND `id` != :user_id AND `id` != :ank_id AND `save` = 1 ORDER BY RAND()");
$sel_users->execute(array(':min_level' => $min_level, ':max_level' => $max_level, ':user_id' => $user['id'], ':ank_id' => $ank_id));
if($sel_users->rowCount() != 0){
$all = $sel_users->fetch(PDO::FETCH_LAZY);
#-Записываем ID в сессию-#
$_SESSION['duel_id'] = $all['id'];
#-Минусуем серебро-#
$upd_users = $pdo->prepare("UPDATE `users` SET `silver` = :silver WHERE `id` = :user_id");
$upd_users->execute(array(':silver' => $user['silver']-$silver, ':user_id' => $user['id']));
header("Location: /duel_battle");
}else{
header('Location: /duel_battle');
$_SESSION['err'] = 'Для вашего уровня нет игроков!';
exit();
}
}else{
header('Location: /duel_battle');
$_SESSION['err'] = 'Недостаточно серебра!';
exit();
}
}else{
header('Location: /duel');
}
}

#-Начинаем бой из героя-#
switch($act){
case 'atk_hero':
#-Получаем данные-#
if(isset($_GET['ank_id'])){
$ank_id = check($_GET['ank_id']);
#-Проверяем существует ли такой игрок-#
$sel_users = $pdo->prepare("SELECT `id` FROM `users` WHERE `id` = :ank_id");
$sel_users->execute(array(':ank_id' => $ank_id));
if($sel_users-> rowCount() != 0){
$all = $sel_users->fetch(PDO::FETCH_LAZY);
#-Проверяем атаковали такого игрока или нет-#
$sel_duel_d = $pdo->prepare("SELECT * FROM `duel_delay` WHERE `user_id` = :user_id AND `ank_id` = :ank_id");
$sel_duel_d->execute(array(':user_id' => $user['id'], ':ank_id' => $all['id']));
if($sel_duel_d-> rowCount() == 0){
#-Записываем ID в сессию-#
$_SESSION['duel_id'] = $all['id'];
#-Добавляем время задержки-#
$ins_duel_d = $pdo->prepare("INSERT INTO `duel_delay` SET `user_id` = :user_id, `ank_id` = :ank_id, `time` = :time");
$ins_duel_d->execute(array(':user_id' => $user['id'], ':ank_id' => $all['id'], ':time' => time()+86400));
header('Location: /duel_battle');
}else{
header("Location: /call_duel?ank_id=$ank_id");
$_SESSION['err'] = 'Можно 1 раз в 24 часа!';
exit();
}
}else{
header("Location: /hero/$user[id]");
$_SESSION['err'] = 'Такого игрока не существует!';
exit();
}
}else{
header("Location: /hero/$user[id]");
$_SESSION['err'] = 'Ошибка получения данных';
exit();
}
}

#-ДУЭЛИ ОНЛАЙН-#
#-Очередь или начало сражения-#
switch($act){
case 'start_online':
#-Проверяем сейчас в дуэли или нет-#
$sel_duel = $pdo->prepare("SELECT * FROM `duel_dattle` WHERE `user_id` = :user_id");
$sel_duel->execute(array(':user_id' => $user['id']));
if($sel_duel-> rowCount() == 0){
#-Проверяем сейчас в дуэли или нет-#
$sel_duel_o = $pdo->prepare("SELECT * FROM `duel_online` WHERE `user_id` = :user_id OR `ank_id` = :user_id");
$sel_duel_o->execute(array(':user_id' => $user['id']));
if($sel_duel_o-> rowCount() == 0){
$sel_duel_b = $pdo->prepare("SELECT * FROM `duel_online` WHERE `level_1` <= :user_level AND `level_2` >=  :user_level AND `user_id` != :user_id AND `block` = 0 ORDER BY RAND()");
$sel_duel_b->execute(array(':user_level' => $user['level'], ':user_id' => $user['id']));
#-Если нет оппонентов, то стаем в очередь-#
if($sel_duel_b->rowCount() == 0){
$user_health = $user['health']+$user['s_health']+$user['health_bonus'];
$ins_duel = $pdo->prepare("INSERT INTO `duel_online` SET `ank_id` = 0, `ank_health` = 0, `ank_t_health` = 0, `user_id` = :user_id, `user_health` = :user_t_health, `user_t_health` = :user_t_health, `level_1` = :level_1, `level_2` = :level_2, `time` = :time");
$ins_duel->execute(array(':user_id' => $user['id'], ':user_health' => $user_health, ':user_t_health' => $user_health, ':level_1' => $user['level']-5, ':level_2' => $user['level']+5, ':time' => time()+300));
$upd_users = $pdo->prepare("UPDATE `users` SET `battle` = 1 WHERE `id` = :id");
$upd_users->execute(array(':id' => $user['id']));
}else{ //Если есть оппоненты, то начинаем бой
$duel_b = $sel_duel_b->fetch(PDO::FETCH_LAZY);	
#-Делаем выборку оппонента-#
$sel_users = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :all_id");
$sel_users->execute(array(':all_id' => $duel_b['user_id']));
$all = $sel_users->fetch(PDO::FETCH_LAZY);	
$all_health = $all['health']+$all['s_health'];
$user_health = $user['health']+$user['s_health'];
#-Редактируем данные дуэли-#
$upd_duel = $pdo->prepare("UPDATE `duel_online` SET `ank_id` = :ank_id, `ank_health` = :user_health, `ank_t_health` = :user_health, `statys` = 1, `time` = :time WHERE `user_id` = :all_id LIMIT 1");
$upd_duel->execute(array(':ank_id' => $user['id'], ':user_health' => $user_health, ':all_id' => $all['id'], ':time' => time()+30));
#-Ставим статус - Бой себе-#
$upd_users = $pdo->prepare("UPDATE `users` SET `battle` = 1 WHERE `id` = :id");
$upd_users->execute(array(':id' => $user['id']));
#-Статус - Бой у оппонента-#
$upd_users_op = $pdo->prepare("UPDATE `users` SET `battle` = 1 WHERE `id` = :id");
$upd_users_op->execute(array(':id' => $all['id']));
}
header('Location: /duel');
}else{
header('Location: /duel');
$_SESSION['err'] = 'Вы сейчас в бою!';
exit();	
}
}else{
header('Location: /duel');
$_SESSION['err'] = 'Вы сейчас в бою!';
exit();	
}
}

#-Покидаем очередь-#
switch($act){
case 'exit_oshered':
$sel_duel_o = $pdo->prepare("SELECT * FROM `duel_online` WHERE `user_id` = :user_id AND `statys` = 0");
$sel_duel_o->execute(array(':user_id' => $user['id']));
if($sel_duel_o-> rowCount() != 0){
#-Удаляем очередь-#
$del_duel_o = $pdo->prepare("DELETE FROM `duel_online` WHERE `user_id` = :user_id");
$del_duel_o->execute(array(':user_id' => $user['id']));	
#-Снимаем с героя статус Бой-#
$upd_users = $pdo->prepare("UPDATE `users` SET `battle` = 0 WHERE `id` = :id");
$upd_users->execute(array(':id' => $user['id']));
header('Location: /duel');
$_SESSION['ok'] = 'Вы покинули очередь!';
exit();	
}else{
header('Location: /duel');
$_SESSION['err'] = 'Это действие не возможно!';
exit();		
}
}

#-Дуэли онлайн из игрока-#
switch($act){
case 'atk_online_hero':
if(isset($_GET['ank_id'])){
$ank_id = check($_GET['ank_id']);
#-Проверяем существует ли такой игрок-#
$sel_users = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :ank_id");
$sel_users->execute(array(':ank_id' => $ank_id));
if($sel_users-> rowCount() != 0){
$all = $sel_users->fetch(PDO::FETCH_LAZY);
#-Проверяем есть ли бои у оппонента-#
$all_duel = floor($all['level']/2);
if($all['duel_b'] < $all_duel){
#-Проверяем сейчас в дуэли или нет-#
$sel_duel = $pdo->prepare("SELECT * FROM `duel_dattle` WHERE `user_id` = :user_id");
$sel_duel->execute(array(':user_id' => $user['id']));
if($sel_duel-> rowCount() == 0){
#-Проверяем сейчас в дуэли или нет-#
$sel_duel_o = $pdo->prepare("SELECT * FROM `duel_online` WHERE `user_id` = :user_id OR `ank_id` = :user_id");
$sel_duel_o->execute(array(':user_id' => $user['id']));
if($sel_duel_o-> rowCount() == 0){
#-Проверяем оппонент в дуэли или нет-#
$sel_duel_ank = $pdo->prepare("SELECT * FROM `duel_battle` WHERE `user_id` = :ank_id");
$sel_duel_ank->execute(array(':ank_id' => $all['id']));
if($sel_duel_ank-> rowCount() == 0){
$sel_duel_o_ank = $pdo->prepare("SELECT * FROM `duel_online` WHERE `user_id` = :ank_id OR `ank_id` = :ank_id");
$sel_duel_o_ank->execute(array(':ank_id' => $all['id']));
if($sel_duel_o_ank-> rowCount() == 0){
#-Записываем бой-#
$user_health = $user['health']+$user['s_health']+$user['health_bonus'];
$ins_duel = $pdo->prepare("INSERT INTO `duel_online` SET `ank_id` = 0, `ank_health` = 0, `ank_t_health` = 0, `user_id` = :user_id, `user_health` = :user_t_health, `user_t_health` = :user_t_health, `block` = 1, `time` = :time");
$ins_duel->execute(array(':user_id' => $user['id'], ':user_health' => $user_health, ':user_t_health' => $user_health, ':time' => time()+60));
$upd_users = $pdo->prepare("UPDATE `users` SET `battle` = 1 WHERE `id` = :id");
$upd_users->execute(array(':id' => $user['id']));
#-Отправляем лог оппоненту-#
$ins_event = $pdo->prepare("INSERT INTO `event_log` SET `type` = 1, `log` = :log, `user_id` = :ank_id, `ank_id` = :user_id, `time` = :time");
$ins_event->execute(array(':log' => "<a href='/hero/$user[id]' style='style='display:inline;text-decoration:underline;padding:0px;'>$user[nick]</a> вызывает вас на дуэль", ':ank_id' => $all['id'], ':user_id' => $user['id'], ':time' => time()));
header('Location: /duel');
}else{
header("Location: /call_duel?ank_id=$ank_id");
$_SESSION['err'] = 'Оппонент в бою!';
exit();
}
}else{
header("Location: /call_duel?ank_id=$ank_id");
$_SESSION['err'] = 'Оппонент в бою!';
exit();
}
}else{
header("Location: /call_duel?ank_id=$ank_id");
$_SESSION['err'] = 'Вы в бою!';
exit();
}
}else{
header("Location: /call_duel?ank_id=$ank_id");
$_SESSION['err'] = 'Вы в бою!';
exit();
}
}else{
header("Location: /hero/$user[id]");
$_SESSION['err'] = 'Игрок отдыхает!';
exit();
}
}else{
header("Location: /hero/$user[id]");
$_SESSION['err'] = 'Такого игрока не существует!';
exit();
}
}else{
header("Location: /hero/$user[id]");
$_SESSION['err'] = 'Ошибка получения данных';
exit();
}
}
#-Соглашаемся на онлайн дуэль-#
switch($act){
case 'agree_duel':
#-Получаем данные-#
if(isset($_GET['event_id'])){
$event_id = check($_GET['event_id']);
#-Есть ли такое уведомление-#
$sel_event = $pdo->prepare("SELECT * FROM `event_log` WHERE `id` = :event_id AND `user_id` = :user_id AND `type` = 1");
$sel_event->execute(array(':event_id' => $event_id, ':user_id' => $user['id']));
if($sel_event-> rowCount() != 0){
$event = $sel_event->fetch(PDO::FETCH_LAZY);
#-Проверяем сейчас в дуэли или нет-#
$sel_duel = $pdo->prepare("SELECT * FROM `duel_dattle` WHERE `user_id` = :user_id");
$sel_duel->execute(array(':user_id' => $user['id']));
if($sel_duel-> rowCount() == 0){
#-Проверяем сейчас в дуэли или нет-#
$sel_duel_o = $pdo->prepare("SELECT * FROM `duel_online` WHERE `user_id` = :user_id OR `ank_id` = :user_id");
$sel_duel_o->execute(array(':user_id' => $user['id']));
if($sel_duel_o-> rowCount() == 0){
#-Проверяем существует ли дуэль-#
$sel_duel_o2 = $pdo->prepare("SELECT * FROM `duel_online` WHERE `user_id` = :ank_id");
$sel_duel_o2->execute(array(':ank_id' => $event['ank_id']));
if($sel_duel_o2-> rowCount() != 0){
$user_health = $user['health']+$user['s_health']+$user['health_bonus'];	
#-Редактируем бой и ставим статус боя-#
$upd_duel = $pdo->prepare("UPDATE `duel_online` SET `ank_id` = :user_id, `ank_health` = :user_health, `ank_t_health` = :user_health, `statys` = 1, `time` = :time WHERE `user_id` = :ank_id LIMIT 1");
$upd_duel->execute(array(':user_id' => $user['id'], ':user_health' => $user_health, ':ank_id' => $event['ank_id'], ':time' => time()+30));
#-Статус бой у игрока-#
$upd_users = $pdo->prepare("UPDATE `users` SET `battle` = 1 WHERE `id` = :id");
$upd_users->execute(array(':id' => $user['id']));
#-Удаляем уведомление-#
$del_event = $pdo->prepare("DELETE FROM `event_log` WHERE `id` = :event_id AND `user_id` = :user_id");
$del_event->execute(array(':event_id' => $event['id'], ':user_id' => $user['id']));
header('Location: /duel_online');
}else{
header('Location: /duel');
$_SESSION['err'] = 'Бой не найден!';
exit();	
}
}else{
header('Location: /duel');
$_SESSION['err'] = 'Вы сейчас в бою!';
exit();	
}
}else{
header('Location: /duel');
$_SESSION['err'] = 'Вы сейчас в бою!';
exit();	
}
}else{
header('Location: /duel');
$_SESSION['err'] = 'Уведомление не найдено!';
exit();	
}
}else{
header('Location: /duel');
$_SESSION['err'] = 'Данные не получены!';
exit();	
}
}
}else{
header('Location: /duel');
exit();
}
?>