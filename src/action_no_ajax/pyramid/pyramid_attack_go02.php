<?php
require_once '../../system/system.php';
echo only_reg();

#-Атака в пирамиде-#
switch($act){
case 'attc':
#-Параметры ТЭПА-#
$name = 'Тэп'; //имя
$images = '/style/images/monstru/pyramid/tep.png'; //путь картинки
$sila = 200; //сила
$zashita = 600; //защита
$health = 300000; //здоровье
$krut = 'off';

#-Проверка что бой существует-#
$sel_pyramid_b = $pdo->query("SELECT * FROM `pyramid_battle_b`");
if($sel_pyramid_b->rowCount() == 0) $error = 'Бой еще не начат!';
#-Проверка что игрок участвует в бою-#
$sel_pyramid_u = $pdo->prepare("SELECT * FROM `pyramid_battle_u` WHERE `user_id` = :user_id AND `health` != 0 AND `zamor` = 0");
$sel_pyramid_u->execute(array(':user_id' => $user['id']));
if($sel_pyramid_u->rowCount() == 0) $error = 'Вы не участвуете в бою или погибли!';

#-Нет ошибок-#
if(!isset($error)){
$pyramid_b = $sel_pyramid_b->fetch(PDO::FETCH_LAZY);
$pyramid_u = $sel_pyramid_u->fetch(PDO::FETCH_LAZY);

#-Урон игрока-#
$v_user_uron = ((($pyramid_u['sila']) * 0.5) * 10);
$v_ank_zashita = ((($pyramid_b['zashita']) * 0.2) * 10);
if($v_user_uron > $v_ank_zashita){
$r_user_uron_1 = ($v_user_uron - $v_ank_zashita);
$r_user_uron_2 = ($v_user_uron - ($v_ank_zashita / 2));
}else{
$r_user_uron_1 = ($v_user_uron / 5);
$r_user_uron_2 = ($v_user_uron / 4);
}
$user_uron = rand($r_user_uron_1, $r_user_uron_2);
$user_uron = round($user_uron, 0);

#-Урон монстра-#
$v_monstr_uron = (($pyramid_b['sila'] * 0.5) * 10);
$v_user_zashita = ((($pyramid_u['zashita']) * 0.2) * 10);
if($v_monstr_uron > $v_user_zashita){
$r_monstr_uron_1 = ($v_monstr_uron - $v_user_zashita);
$r_monstr_uron_2 = ($v_monstr_uron - ($v_user_zashita / 2));
}else{
$r_monstr_uron_1 = ($v_monstr_uron / 5);
$r_monstr_uron_2 = ($v_monstr_uron / 4);
}
$monstr_uron = rand($r_monstr_uron_1, $r_monstr_uron_2);
$monstr_uron = round($monstr_uron, 0);
#-Крит удар-#
if($pyramid_b['statys'] == 2 and $pyramid_b['form'] == 2){
$rand_krut = rand(0, 100);
if($rand_krut <= 25){
$monstr_uron = $monstr_uron * 2;
$krut = 'on';
}
}

$user_health = $pyramid_u['health'] - $monstr_uron;
$monstr_health = $pyramid_b['health'] - $user_uron;

#-ПОБЕДИЛ ИГРОК-#
if($user_uron >= $pyramid_b['health']){
	
#-Если бой против Питомца-#
if($pyramid_b['statys'] == 1){
#-Смена монстра-#
$upd_pyramid_b = $pdo->prepare("UPDATE `pyramid_battle_b` SET `name` = :name, `images` = :images, `sila` = :sila, `zashita` = :zashita, `health` = :health, `max_health` = :health, `statys` = 2 WHERE `id` = :id LIMIT 1");
$upd_pyramid_b->execute(array(':name' => $name, ':images' => $images, ':sila' => $sila, ':zashita' => $zashita, ':health' => $health, ':id' => $pyramid_b['id']));
#-Смена игроков-#
$sel_pyramid_u_all = $pdo->query("SELECT * FROM `pyramid_battle_u`");
while($pyramid_u_all = $sel_pyramid_u_all->fetch(PDO::FETCH_LAZY)){
#-Выборка игроков и параметров-#
$sel_users = $pdo->prepare("SELECT `id`, `nick`, `avatar`, `sila`, `s_sila`, `sila_bonus`, `zashita`, `s_zashita`, `zashita_bonus`, `health`, `s_health`, `health_bonus` FROM `users` WHERE `id` = :user_id");
$sel_users->execute(array(':user_id' => $pyramid_u_all['user_id']));
$all = $sel_users->fetch(PDO::FETCH_LAZY);
#-Изменение параметров-#
$upd_pyramid_u = $pdo->prepare("UPDATE `pyramid_battle_u` SET `name` = :name, `images` = :images, `sila` = :sila, `zashita` = :zashita, `health` = :health, `max_health` = :health, `lesh` = 0, `vosk` = 0 WHERE `user_id` = :all_id LIMIT 1");
$upd_pyramid_u->execute(array(':name' => $all['nick'], ':images' => "/style/avatar/$all[avatar]", ':sila' => $all['sila']+$all['s_sila']+$all['sila_bonus'], ':zashita' => $all['zashita']+$all['s_zashita']+$all['zashita_bonus'], ':health' => $all['health']+$all['s_health']+$all['health_bonus'], ':all_id' => $all['id']));
}

#-Рандомно выбор вещи тому кто убил питомца-#	
$sel_weapon = $pdo->query("SELECT * FROM `weapon` WHERE `no_magaz` = 12 ORDER BY RAND() LIMIT 1");	
$weapon = $sel_weapon->fetch(PDO::FETCH_LAZY);
#-Запись вещи-#
$ins_weapon_me = $pdo->prepare("INSERT INTO `weapon_me` SET `type` = :type, `weapon_id` = :weapon_id, `user_id` = :user_id, `time` = :time");
$ins_weapon_me ->execute(array(':type' => $weapon['type'], ':weapon_id' => $weapon['id'], ':user_id' => $user['id'], ':time' => time())); 	
#-Лог-#
$ins_pyramid_log = $pdo->prepare("INSERT INTO `pyramid_battle_l` SET `log` = :log");
$ins_pyramid_log->execute(array(':log' => "<img src='/style/images/body/snarag.png'/> <span class='blue'>$user[nick] получил(ла) $weapon[name]</span><br/><img src='/style/images/body/rip.png'/>$pyramid_b[name] был повержен"));
header('Location: /pyramid');	
}

#-Бой против ТЭПА-#
if($pyramid_b['statys'] == 2){
#-Выпадение вещей тэпа двум случайным игрокам-#	
for($i = 1; $i < 2; $i++){
$sel_all_rand = $pdo->query("SELECT * FROM `pyramid_battle_u` ORDER BY RAND() LIMIT 1");
$all_rand = $sel_all_rand->fetch(PDO::FETCH_LAZY);
$sel_weapon_rand = $pdo->query("SELECT * FROM `weapon` WHERE `no_magaz` = 13 ORDER BY RAND() LIMIT 1");
$weapon_rand = $sel_weapon_rand->fetch(PDO::FETCH_LAZY);
$ins_weapon_me = $pdo->prepare("INSERT INTO `weapon_me` SET `type` = :type, `weapon_id` = :weapon_id, `user_id` = :user_id, `time` = :time");
$ins_weapon_me->execute(array(':type' => $weapon_rand['type'], ':weapon_id' => $weapon_rand['id'], ':user_id' => $all_rand['user_id'], ':time' => time())); 	
#-Лог-#
$ins_pyramid_log = $pdo->prepare("INSERT INTO `pyramid_battle_l` SET `log` = :log");
$ins_pyramid_log->execute(array(':log' => "<img src='/style/images/body/snarag.png'/> <span class='blue'>$all_rand[name] получил(ла) $weapon_rand[name]</span>"));
}
#-Удаление боя-#
$del_pyramid_b = $pdo->query("DELETE FROM `pyramid_battle_b`");
#-Удаление игроков из боя-#
$del_pyramid_u = $pdo->query("DELETE FROM `pyramid_battle_u`");
header('Location: /pyramid');	
}

}else{
#-ПОБЕДИЛ МОНСТР-#
if($monstr_uron >= $pyramid_u['health']){
#-Здоровье игрока-#
$upd_pyramid_u = $pdo->prepare("UPDATE `pyramid_battle_u` SET `health` = :health WHERE `user_id` = :user_id LIMIT 1");
$upd_pyramid_u->execute(array(':health' => 0, ':user_id' => $user['id']));
#-Лог-#
$ins_pyramid_log = $pdo->prepare("INSERT INTO `pyramid_battle_l` SET `log` = :log");
$ins_pyramid_log->execute(array(':log' => "<img src='/style/images/body/rip.png'/> <span class='green'>$user[nick] погиб(ла)</span>"));
header('Location: /pyramid');
}else{
#-ПРОДОЛЖЕНИЕ БОЯ-#
#-Здоровье босса-#
$upd_pyramid_b = $pdo->prepare("UPDATE `pyramid_battle_b` SET `health` = :health WHERE `id` = :id LIMIT 1");
$upd_pyramid_b->execute(array(':health' => $monstr_health, ':id' => $pyramid_b['id']));
#-Здоровье игрока-#
$upd_pyramid_u = $pdo->prepare("UPDATE `pyramid_battle_u` SET `health` = :health WHERE `user_id` = :user_id LIMIT 1");
$upd_pyramid_u->execute(array(':health' => $user_health, ':user_id' => $user['id']));
#-Лог-#
if($krut == 'on'){
$ins_pyramid_log = $pdo->prepare("INSERT INTO `pyramid_battle_l` SET `log` = :log");
$ins_pyramid_log->execute(array(':log' => "<img src='/style/images/body/attack.png'/> <span class='green'>$user[nick] нанес(ла) $user_uron урона</span><br/><img src='/style/images/body/attack.png'/> <span class='red'>$pyramid_b[name] использовал удар Астрала и нанес $monstr_uron урона</span>"));
}else{
$ins_pyramid_log = $pdo->prepare("INSERT INTO `pyramid_battle_l` SET `log` = :log");
$ins_pyramid_log->execute(array(':log' => "<img src='/style/images/body/attack.png'/> <span class='green'>$user[nick] нанес(ла) $user_uron урона</span><br/><img src='/style/images/body/attack.png'/> <span class='red'>$pyramid_b[name] нанес $monstr_uron урона</span>"));
}

#-Бой против ТЭПА-#
if($pyramid_b['statys'] == 2){
$health_10 = $pyramid_b['max_health'] * 0.10; //10% здоровья
$health_50 = $pyramid_b['max_health'] * 0.50; //50% здоровья

#-2 форма ТЭПА-#
if($pyramid_b['health'] <= $health_10 and $pyramid['form'] < 2){
#-Восстановление-#
$upd_pyramid_he = $pdo->prepare("UPDATE `pyramid_battle_b` SET `health` = :health, `form` = 2 WHERE `id` = :id LIMIT 1");	
$upd_pyramid_he->execute(array(':health' => $pyramid_b['health']+$health_50, ':id' => $pyramid_b['id']));
#-Лог-#
$ins_pyramid_log = $pdo->prepare("INSERT INTO `pyramid_battle_l` SET `log` = :log");
$ins_pyramid_log->execute(array(':log' => "<img src='/style/images/body/pyramid.png' width='13px' height='13px'/> <span class='yellow'>ТЭП: Глупцы сейчас Вы познаете настоящую силу Астрала!</span>"));
}
	
#-Заморозка-#
$rand_zamor = rand(0, 100);
if($rand_zamor >= 93){
#-Замораживаем игрока-#
$upd_pyramid_zamor = $pdo->prepare("UPDATE `pyramid_battle_u` SET `zamor` = :zamor WHERE `user_id` = :user_id LIMIT 1");	
$upd_pyramid_zamor->execute(array(':zamor' => time()+30, ':user_id' => $user['id']));
#-Лог-#
$ins_pyramid_log = $pdo->prepare("INSERT INTO `pyramid_battle_l` SET `log` = :log");
$ins_pyramid_log->execute(array(':log' => "<img src='/style/images/body/ok.png'/> <span class='blue'>$user[nick] был ошеломлён током</span>"));
}	
}
header('Location: /pyramid');	
}
}
}else{
header('Location: /pyramid');
$_SESSION['err'] = $error;
exit();
}
}