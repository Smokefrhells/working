<?php
require_once '../../system/system.php';
echo only_reg();
#-Берем все задание-#
switch($act){
case 'take':
#-Охота-#
$sel_hunting = $pdo->prepare("SELECT * FROM `daily_tasks` WHERE `user_id` = :user_id AND `type` = 1");
$sel_hunting->execute(array(':user_id' => $user['id']));
if($sel_hunting-> rowCount() == 0){
$ins_tasks = $pdo->prepare("INSERT INTO `daily_tasks` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_tasks->execute(array(':type' => 1, ':user_id' => $user['id'], ':time' => time())); 
}

#-Уровень больше 4-#
if($user['level'] >= 5){
#-Дуэли оффлайн-#
$sel_duel_off = $pdo->prepare("SELECT * FROM `daily_tasks` WHERE `user_id` = :user_id AND `type` = 2");
$sel_duel_off->execute(array(':user_id' => $user['id']));
if($sel_duel_off-> rowCount() == 0){
$ins_tasks = $pdo->prepare("INSERT INTO `daily_tasks` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_tasks->execute(array(':type' => 2, ':user_id' => $user['id'], ':time' => time())); 
}

#-Дуэли онлайн-#
$sel_duel_on = $pdo->prepare("SELECT * FROM `daily_tasks` WHERE `user_id` = :user_id AND `type` = 3");
$sel_duel_on->execute(array(':user_id' => $user['id']));
if($sel_duel_on-> rowCount() == 0){
$ins_tasks = $pdo->prepare("INSERT INTO `daily_tasks` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_tasks->execute(array(':type' => 3, ':user_id' => $user['id'], ':time' => time())); 
}

#-Какое количество боссов доступно-#
$sel_boss = $pdo->prepare("SELECT COUNT(*) FROM `boss` WHERE `level` <= :user_level AND `type` != 4");
$sel_boss->execute(array(':user_level' => $user['level']));
$amount = $sel_boss->fetch(PDO::FETCH_LAZY);
#-Боссы-#
for($i = 1; $i <= $amount[0]; $i++) {
$sel_boss_t = $pdo->prepare("SELECT * FROM `daily_tasks` WHERE `user_id` = :user_id AND `type` = 4 AND `type_id` = :type_id");
$sel_boss_t->execute(array(':user_id' => $user['id'], ':type_id' => $i));
if($sel_boss_t-> rowCount() == 0){
$ins_tasks = $pdo->prepare("INSERT INTO `daily_tasks` SET `type` = :type, `type_id` = :type_id, `user_id` = :user_id, `time` = :time");
$ins_tasks->execute(array(':type' => 4, ':type_id' => $i, ':user_id' => $user['id'], ':time' => time())); 
}
}
}
header('Location: /daily_tasks');
exit();
}
?>