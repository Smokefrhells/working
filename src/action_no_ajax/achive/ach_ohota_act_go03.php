<?php
require_once '../../system/system.php';
echo only_reg();
#-Достижения охота-#
switch($act){
case 'get':
$battle_o = $user['hunting_b_1']+$user['hunting_b_2']+$user['hunting_b_3']+$user['hunting_b_4']+$user['hunting_b_5']+$user['hunting_b_6']+$user['hunting_b_7'];
#-Проверяем что достижение еще не выполнено-#
if($user['ach_ohota'] != 10){

#-Первое-#
if($user['ach_ohota'] == 0 and $battle_o >= 100){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `silver` = :silver, `ach_ohota` = 1 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':silver' => $user['silver']+12000, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => '1', ':user_id' => $user['id'], ':time' => time())); 
header('Location: /achive');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Второе-#
if($user['ach_ohota'] == 1 and $battle_o >= 1500){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `silver` = :silver, `key` = :key, `ach_ohota` = 2 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':silver' => $user['silver']+50000, ':key' => $user['key']+12, ':id' => $user['id'])); 	
header('Location: /achive');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Третье-#
if($user['ach_ohota'] == 2 and $battle_o >= 2000){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_ohota` = 3 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+100, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => '2', ':user_id' => $user['id'], ':time' => time())); 
header("Location: /achive");
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Четвертое-#
if($user['ach_ohota'] == 3 and $battle_o >= 4500){
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `key` = :key, `ach_ohota` = 4 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+200, ':key' => $user['key']+50, ':id' => $user['id'])); 	
header("Location: /achive");
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Пятое-#
if($user['ach_ohota'] == 4 and $battle_o >= 6000){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `silver` = :silver, `ach_ohota` = 5 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+350,  ':silver' => $user['silver']+100000, ':id' => $user['id'])); 	
header('Location: /achive');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Шестое-#
if($user['ach_ohota'] == 5 and $battle_o >= 7500){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_ohota` = 6 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+450, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => '2', ':user_id' => $user['id'], ':time' => time())); 
header("Location: /achive");
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Седьмое-#
if($user['ach_ohota'] == 6 and $battle_o >= 9000){
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `key` = :key, `ach_ohota` = 7 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+550, ':key' => $user['key']+80, ':id' => $user['id'])); 	
header("Location: /achive");
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Восьмое-#
if($user['ach_ohota'] == 7 and $battle_o >= 10500){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `silver` = :silver, `ach_ohota` = 8 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+750,  ':silver' => $user['silver']+200000, ':id' => $user['id'])); 	
header('Location: /achive');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Девятое-#
if($user['ach_ohota'] == 8 and $battle_o >= 12000){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_ohota` = 9 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+900, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => '2', ':user_id' => $user['id'], ':time' => time())); 
header("Location: /achive");
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Десятое-#
if($user['ach_ohota'] == 9 and $battle_o >= 14000){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_ohota` = 10 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+1200, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => '3', ':user_id' => $user['id'], ':time' => time())); 
header("Location: /achive");
$_SESSION['ok'] = 'Награда получена!';
exit();
}

if($battle_o < 100 or $battle_o < 1500 or $battle_o < 2000 or $battle_o < 4500 or $battle_o < 6000 or $battle_o < 7500 or $battle_o < 9000 or $battle_o < 10500 or $battle_o < 12000 or $battle_o < 14000){
header('Location: /achive');
exit();		
}
}else{
header('Location: /achive');
$_SESSION['err'] = 'Достижение выполнено!';
exit();	
}
}
?>