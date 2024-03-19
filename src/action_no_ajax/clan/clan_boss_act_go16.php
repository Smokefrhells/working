<?php
require_once '../../system/system.php';
echo only_reg();
echo clan_level();

#-Начало боя-#
switch($act){
case 'start':
if(isset($_GET['boss_id']) and isset($_GET['clan_id'])){
$boss_id = check($_GET['boss_id']);	
$clan_id = check($_GET['clan_id']);

#-Проверка существования клана-#
$sel_clan = $pdo->prepare("SELECT `id`, `level`, `key_1`, `key_2`, `key_3`, `key_4`, `key_5` FROM `clan` WHERE `id` = :clan_id AND `level` >= 10");
$sel_clan->execute(array(':clan_id' => $clan_id));
if($sel_clan-> rowCount() == 0) $error = 'Клан не найден!';
#-Проверка что игрок состоит в клане-#
$sel_clan_u = $pdo->prepare("SELECT `id`, `clan_id`, `user_id`, `prava` FROM `clan_users` WHERE `clan_id` = :clan_id AND `user_id` = :user_id AND (`prava` = 3 OR `prava` = 4)");
$sel_clan_u->execute(array(':clan_id' => $clan_id, ':user_id' => $user['id']));
if($sel_clan_u-> rowCount() == 0) $error = 'Не состоите в клане или нет прав!';
#-Проверка существования босса-#
$sel_clan_boss = $pdo->prepare("SELECT `id`, `name`, `health` FROM `clan_boss` WHERE `id` = :boss_id");
$sel_clan_boss->execute(array(':boss_id' => $boss_id));
if($sel_clan_boss-> rowCount() == 0) $error = 'Босс не найден!';
#-Проверка что нет боя-#
$sel_clan_battle = $pdo->prepare("SELECT * FROM `clan_boss_battle` WHERE `clan_id` = :clan_id");
$sel_clan_battle->execute(array(':clan_id' => $clan_id));
if($sel_clan_battle-> rowCount() != 0) $error = 'Уже есть бой!';
#-Проверка что не стоит время-#
$sel_clan_boss_t = $pdo->prepare("SELECT * FROM `clan_boss_time` WHERE `clan_id` = :clan_id AND `boss_id` = :boss_id");
$sel_clan_boss_t->execute(array(':clan_id' => $clan_id, ':boss_id' => $boss_id));
if($sel_clan_boss_t-> rowCount() != 0) $error = 'Босс на отдыхе!';

#-Если нет ошибок-#
if(!isset($error)){
$clan = $sel_clan->fetch(PDO::FETCH_LAZY);	
$clan_boss = $sel_clan_boss->fetch(PDO::FETCH_LAZY);

if($clan_boss['id'] > 1){
#-Ключи-#
if($clan_boss['id'] == 2){$key = 'key_1';}
if($clan_boss['id'] == 3){$key = 'key_2';}
if($clan_boss['id'] == 4){$key = 'key_3';}
if($clan_boss['id'] == 5){$key = 'key_4';}

if($clan[$key] >= 2){
$upd_clan = $pdo->prepare("UPDATE `clan` SET `$key` = `$key`-2 WHERE `id` = :clan_id LIMIT 1");
$upd_clan->execute(array(':clan_id' => $clan['id']));
$ok = 'OKEY';
}else{
header('Location: /clan_boss');
$_SESSION['err'] = 'Недостаточно ключей!';
exit();		
}
}

#-Создание боя-#
if($clan_boss['id'] == 1 or $ok = 'OKEY'){
$ins_clan_b = $pdo->prepare("INSERT INTO `clan_boss_battle` SET `clan_id` = :clan_id, `boss_id` = :boss_id, `boss_t_health` = :boss_t_health, `time` = :time");
$ins_clan_b->execute(array(':clan_id' => $clan['id'], ':boss_id' => $clan_boss['id'], ':boss_t_health' => $clan_boss['health'], ':time' => time()+1200));
#-История-#
$ins_clan_l = $pdo->prepare("INSERT INTO `clan_log` SET `type` = :type, `log` = :log, `clan_id` = :clan_id, `time` = :time");
$ins_clan_l ->execute(array(':type' => 11, ':log' => "$user[nick] начал(а) сражение с боссом $clan_boss[name]", ':clan_id' => $clan['id'], ':time' => time())); 
}
header('Location: /clan_boss');
$_SESSION['ok'] = 'Бой начался!';
exit();	
}else{
header('Location: /clan_boss');
$_SESSION['err'] = $error;
exit();		
}
}else{
header('Location: /clan_boss');
$_SESSION['err'] = 'Данные не переданы!';
exit();	
}
}

#-Атака-#
switch($act){
case 'attc':
if(isset($_GET['boss_id']) and isset($_GET['clan_id'])){
$boss_id = check($_GET['boss_id']);	
$clan_id = check($_GET['clan_id']);

#-Проверка существования босса-#
$sel_clan_boss = $pdo->prepare("SELECT * FROM `clan_boss` WHERE `id` = :boss_id");
$sel_clan_boss->execute(array(':boss_id' => $boss_id));
if($sel_clan_boss-> rowCount() == 0) $error = 'Босс не найден!';
#-Проверка что есть бой-#
$sel_clan_battle = $pdo->prepare("SELECT * FROM `clan_boss_battle` WHERE `clan_id` = :clan_id AND `boss_id` = :boss_id");
$sel_clan_battle->execute(array(':clan_id' => $clan_id, ':boss_id' => $boss_id));
if($sel_clan_battle-> rowCount() == 0) $error = 'Бой не найден!';
#-Время между ударами-#
$sel_boss_battle_u = $pdo->prepare("SELECT * FROM `clan_boss_battle_u` WHERE `clan_id` = :clan_id AND `user_id` = :user_id AND `time` > :time ORDER BY `id` DESC LIMIT 1");
$sel_boss_battle_u->execute(array(':clan_id' => $clan_id, ':user_id' => $user['id'], ':time' => time()));
if($sel_boss_battle_u-> rowCount() != 0) $error = 'Подождите еще!';

#-Если нет ошибок-#
if(!isset($error)){
$boss_battle = $sel_clan_battle->fetch(PDO::FETCH_LAZY);
$clan_boss = $sel_clan_boss->fetch(PDO::FETCH_LAZY);
	
#-Урон игрока-#
$v_user_uron = ((($user['sila']+$user['s_sila']+$user['sila_bonus']) * 0.5) * 10);
$v_boss_zashita = (($clan_boss['zashita'] * 0.2) * 10);
if($v_user_uron > $v_boss_zashita){
$r_user_uron_1 = ($v_user_uron - $v_boss_zashita);
$r_user_uron_2 = ($v_user_uron - ($v_boss_zashita / 2));
}else{
$r_user_uron_1 = ($v_user_uron / 5);
$r_user_uron_2 = ($v_user_uron / 4);
}
$user_uron = rand($r_user_uron_1, $r_user_uron_2);
$user_uron = round($user_uron, 0);
$boss_health = $boss_battle['boss_t_health']-$user_uron;

#-ПОБЕДА-#	
if($user_uron >= $boss_battle['boss_t_health']){
if($clan_boss['id'] == 1){$key = 'key_1';}
if($clan_boss['id'] == 2){$key = 'key_2';}
if($clan_boss['id'] == 3){$key = 'key_3';}
if($clan_boss['id'] == 4){$key = 'key_4';}
if($clan_boss['id'] == 5){$key = 'key_5';}	
	
$gold = rand($clan_boss['gold_1'], $clan_boss['gold_2']);
#-Награда в казну клана-#
$upd_clan = $pdo->prepare("UPDATE `clan` SET `$key` = `$key`+1, `gold` = `gold` + :gold WHERE `id` = :clan_id");
$upd_clan->execute(array(':gold' => $gold, ':clan_id' => $boss_battle['clan_id']));
#-Установка времени-#
$ins_clan_boss_time = $pdo->prepare("INSERT INTO `clan_boss_time` SET `clan_id` = :clan_id, `boss_id` = :boss_id, `time` = :time");
$ins_clan_boss_time->execute(array(':clan_id' => $boss_battle['clan_id'], ':boss_id' => $clan_boss['id'], ':time' => time()+$clan_boss['time_otduh']));
#-История-#
$ins_clan_l = $pdo->prepare("INSERT INTO `clan_log` SET `type` = :type, `log` = :log, `clan_id` = :clan_id, `time` = :time");
$ins_clan_l ->execute(array(':type' => 11, ':log' => "Босс $clan_boss[name] был уничтожен игроком <img src='/style/images/user/user.png' alt=''/>$user[nick]: <img src='/style/images/many/gold.png' alt=''/>+$gold в казну", ':clan_id' => $boss_battle['clan_id'], ':time' => time())); 
#-Удаление боя-#
$del_c_boss_battle = $pdo->prepare("DELETE FROM `clan_boss_battle` WHERE `clan_id` = :clan_id");
$del_c_boss_battle->execute(array(':clan_id' => $boss_battle['clan_id']));
#-Удаление лога-#
$del_c_boss_battle_u = $pdo->prepare("DELETE FROM `clan_boss_battle_u` WHERE `clan_id` = :clan_id");
$del_c_boss_battle_u->execute(array(':clan_id' => $boss_battle['clan_id']));
header('Location: /clan_boss');
$_SESSION['ok'] = 'Вы уничтожили босса!';
exit();
}else{
#-Здоровье босса-#
$upd_clan_boss_b = $pdo->prepare("UPDATE `clan_boss_battle` SET `boss_t_health` = :boss_health WHERE `id` = :id LIMIT 1");
$upd_clan_boss_b->execute(array(':boss_health' => $boss_health, ':id' => $boss_battle['id']));	
#-Запись лога-#
$ins_c_boss_battle_u = $pdo->prepare("INSERT INTO `clan_boss_battle_u` SET `clan_id` = :clan_id, `user_id` = :user_id, `log` = :log, `time` = :time");
$ins_c_boss_battle_u->execute(array(':clan_id' => $boss_battle['clan_id'], ':user_id' => $user['id'], ':log' => "<img src='/style/images/body/attack.png' alt=''/> $user[nick] нанес(ла) $user_uron урона", ':time' => time()+60));
header('Location: /clan_boss');
$_SESSION['notif'] = '<img src="/style/images/body/attack.png"/> <span class="green">Вы</span> нанесли '.$user_uron.' урона';
exit();	
}
}else{
header('Location: /clan_boss');
$_SESSION['err'] = $error;
exit();		
}
}else{
header('Location: /clan_boss');
$_SESSION['err'] = 'Данные не переданы!';
exit();	
}
}
?>