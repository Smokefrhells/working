<?php
require_once '../../system/system.php';
echo only_reg();
#-Достижения дуэли-#
switch($act){
case 'get':
#-Проверяем что достижение еще не выполнено-#
if($user['ach_duel'] != 10){
#-Первое-#
if($user['ach_duel'] == 0 and $user['duel_pobeda'] >= 10){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `silver` = :silver, `ach_duel` = 1 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':silver' => $user['silver']+2500, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => '1', ':user_id' => $user['id'], ':time' => time())); 
header('Location: /achive');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Второе-#
if($user['ach_duel'] == 1 and $user['duel_pobeda'] >= 50){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `silver` = :silver, `key` = :key, `ach_duel` = 2 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':silver' => $user['silver']+5000, ':key' => $user['key']+5, ':id' => $user['id'])); 	
header('Location: /achive');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Третье-#
if($user['ach_duel'] == 2 and $user['duel_pobeda'] >= 100){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_duel` = 3 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+30, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => '2', ':user_id' => $user['id'], ':time' => time())); 
header("Location: /achive");
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Четвертое-#
if($user['ach_duel'] == 3 and $user['duel_pobeda'] >= 300){
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `key` = :key, `ach_duel` = 4 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+80, ':key' => $user['key']+30, ':id' => $user['id'])); 	
header("Location: /achive");
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Пятое-#
if($user['ach_duel'] == 4 and $user['duel_pobeda'] >= 500){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `silver` = :silver, `ach_duel` = 5 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+120,  ':silver' => $user['silver']+50000, ':id' => $user['id'])); 	
header('Location: /achive');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Шестое-#
if($user['ach_duel'] == 5 and $user['duel_pobeda'] >= 1000){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_duel` = 6 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+200, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => '2', ':user_id' => $user['id'], ':time' => time())); 
header("Location: /achive");
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Седьмое-#
if($user['ach_duel'] == 6 and $user['duel_pobeda'] >= 1500){
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `key` = :key, `ach_duel` = 7 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+250, ':key' => $user['key']+50, ':id' => $user['id'])); 	
header("Location: /achive");
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Восьмое-#
if($user['ach_duel'] == 7 and $user['duel_pobeda'] >= 2000){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `silver` = :silver, `ach_duel` = 8 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+350,  ':silver' => $user['silver']+100000, ':id' => $user['id'])); 	
header('Location: /achive');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Девятое-#
if($user['ach_duel'] == 8 and $user['duel_pobeda'] >= 3000){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_duel` = 9 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+400, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => '2', ':user_id' => $user['id'], ':time' => time())); 
header("Location: /achive");
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Десятое-#
if($user['ach_duel'] == 9 and $user['duel_pobeda'] >= 5000){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_duel` = 10 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+500, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => '3', ':user_id' => $user['id'], ':time' => time())); 
header("Location: /achive");
$_SESSION['ok'] = 'Награда получена!';
exit();
}

if($user['duel_pobeda'] < 10 or $user['duel_pobeda'] < 50 or $user['duel_pobeda'] < 100 or $user['duel_pobeda'] < 300 or $user['duel_pobeda'] < 500 or $user['duel_pobeda'] < 1000 or $user['duel_pobeda'] < 1500 or $user['duel_pobeda'] < 2000 or $user['duel_pobeda'] < 3000 or $user['duel_pobeda'] < 5000){
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