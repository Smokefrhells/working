<?php
require_once '../../system/system.php';
echo only_reg();
echo towers_level();

#-Начало боя башен-#
switch($act){
case 'start':
if(isset($_GET['type'])){
$type = check($_GET['type']);
$user_health = $user['health']+$user['s_health']+$user['health_bonus']; //Здоровье игрока
if($user['level'] <= 100){$gold = 85;$silver = 25000;}
if($user['level'] <= 75){$gold = 65;$silver = 15000;}
if($user['level'] <= 50){$gold = 45;$silver = 10000;}
if($user['level'] <= 25){$gold = 25;$silver = 5000;}

#-Проверка что игрок не участвует в бою-#
$sel_towers = $pdo->prepare("SELECT * FROM `towers` WHERE `user_id` = :user_id");
$sel_towers->execute(array(':user_id' => $user['id']));
if($sel_towers->rowCount() != 0) $error = 'Вы уже участвуете в бою!';
#-Золото или серебро-#
if($type == 'gold'){
if($user['gold'] < $gold)	$error = 'Недостаточно золота!';	
}else{
if($user['silver'] < $silver) $error = 'Недостаточно серебра!';
}

#-Если нет ошибок-#
if(!isset($error)){
#-Уровни боя-#
if($user['level'] >= 25 and $user['level'] < 40){
$min_level = 25;
$max_level = 40;
}
if($user['level'] >= 40 and $user['level'] < 60){
$min_level = 40;
$max_level = 60;
}
if($user['level'] >= 60 and $user['level'] < 80){
$min_level = 60;
$max_level = 80;
}
if($user['level'] >= 80 and $user['level'] <= 100){
$min_level = 80;	
$max_level = 100;
}

#-Есть ли бой с таким же уровнем-#
$sel_towers_b = $pdo->prepare("SELECT * FROM `towers` WHERE `level` >= :user_min_lvl AND `level` <= :user_max_lvl AND `type` = :type AND `statys` = 0");
$sel_towers_b->execute(array(':user_min_lvl' => $min_level, ':user_max_lvl' => $max_level, ':type' => $type));
if($sel_towers_b->rowCount() == 0){
$battle_id = rand(0, 1000);	
$group = rand(1, 2);
}else{
$towers_b = $sel_towers_b->fetch(PDO::FETCH_LAZY);	
#-Кол-во игроков с одинаковым battle_id-#
$sel_towers_a = $pdo->prepare("SELECT COUNT(*) FROM `towers` WHERE `battle_id` = :battle_id AND `type` = :type");
$sel_towers_a->execute(array(':battle_id' => $towers_b['battle_id'], ':type' => $type));
$towers_a = $sel_towers_a->fetch(PDO::FETCH_LAZY);
if($towers_a[0] < 4){
$battle_id = $towers_b['battle_id'];

#-Кол-во игроков 1 группы-#	
$sel_group_1 = $pdo->prepare("SELECT COUNT(*) FROM `towers` WHERE `group` = 1 AND `battle_id` = :battle_id");
$sel_group_1->execute(array(':battle_id' => $battle_id));
$group_1 = $sel_group_1->fetch(PDO::FETCH_LAZY);
#-Кол-во игроков 2 группы-#
$sel_group_2 = $pdo->prepare("SELECT COUNT(*) FROM `towers` WHERE `group` = 2 AND `battle_id` = :battle_id");
$sel_group_2->execute(array(':battle_id' => $battle_id));
$group_2 = $sel_group_2->fetch(PDO::FETCH_LAZY);
if($group_1[0] > $group_2[0]){$group = 2;}else{$group = 1;}
}else{
$battle_id = rand(0, 1000);
$group = rand(1, 2);
}
}

#-Создание боя-#
$ins_towers = $pdo->prepare("INSERT INTO `towers` SET `type` = :type, `level` = :level, `user_health` = :user_health, `user_t_health` = :user_health, `user_id` = :user_id, `battle_id` = :battle_id, `group` = :group");
$ins_towers->execute(array(':type' => $type, ':level' => $user['level'], ':user_health' => $user_health, ':user_id' => $user['id'], ':battle_id' => $battle_id, ':group' => $group));	
#-Минус средств-#
if($type == 'gold'){
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold WHERE `id` = :user_id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']-$gold, ':user_id' => $user['id']));
}else{
$upd_users = $pdo->prepare("UPDATE `users` SET `silver` = :silver WHERE `id` = :user_id LIMIT 1");
$upd_users->execute(array(':silver' => $user['silver']-$silver, ':user_id' => $user['id']));
}
header('Location: /towers');
}else{
header('Location: /towers');
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /towers');
$_SESSION['err'] = 'Данные не переданы!';
exit();
}
}
?>