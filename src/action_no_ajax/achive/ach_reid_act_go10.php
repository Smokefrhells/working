<?php
require_once '../../system/system.php';
echo only_reg();

#-Достижения рейд-#
switch($act){
case 'get':
#-Проверяем что достижение еще не выполнено-#
if($user['ach_reid'] != 10){
	
#-Первое-#
if($user['ach_reid'] == 0 and $user['reid_pobeda'] >= 1){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `silver` = :silver, `ach_reid` = 1 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':silver' => $user['silver']+15000, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => 1, ':user_id' => $user['id'], ':time' => time())); 
header('Location: /achive?type=2');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Второе-#
if($user['ach_reid'] == 1 and $user['reid_pobeda'] >= 3){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `silver` = :silver, `key` = :key, `ach_reid` = 2 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':silver' => $user['silver']+25000, ':key' => $user['key']+5, ':id' => $user['id'])); 	
header('Location: /achive?type=2');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Третье-#
if($user['ach_reid'] == 2 and $user['reid_pobeda'] >= 7){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_reid` = 3 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+100, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => 1, ':user_id' => $user['id'], ':time' => time())); 
header('Location: /achive?type=2');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Четвертое-#
if($user['ach_reid'] == 3 and $user['reid_pobeda'] >= 15){
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `silver` = :silver, `ach_reid` = 4 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+130, ':silver' => $user['silver']+120000, ':id' => $user['id'])); 	
header('Location: /achive?type=2');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Пятое-#
if($user['ach_reid'] == 4 and $user['reid_pobeda'] >= 25){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `silver` = :silver, `ach_reid` = 5 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+240,  ':silver' => $user['silver']+160000, ':id' => $user['id'])); 	
header('Location: /achive?type=2');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Шестое-#
if($user['ach_reid'] == 5 and $user['reid_pobeda'] >= 50){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_reid` = 6 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+470, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => 2, ':user_id' => $user['id'], ':time' => time())); 
header('Location: /achive?type=2');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Седьмое-#
if($user['ach_reid'] == 6 and $user['reid_pobeda'] >= 85){
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `key` = :key, `ach_reid` = 7 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+650, ':key' => $user['key']+15, ':id' => $user['id'])); 	
header('Location: /achive?type=2');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Восьмое-#
if($user['ach_reid'] == 7 and $user['reid_pobeda'] >= 150){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `silver` = :silver, `ach_reid` = 8 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+850,  ':silver' => $user['silver']+200000, ':id' => $user['id'])); 	
header('Location: /achive?type=2');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Девятое-#
if($user['ach_reid'] == 8 and $user['reid_pobeda'] >= 250){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_reid` = 9 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+1100, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => 2, ':user_id' => $user['id'], ':time' => time())); 
header('Location: /achive?type=2');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Десятое-#
if($user['ach_reid'] == 9 and $user['reid_pobeda'] >= 400){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_reid` = 10 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+1500, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => 3, ':user_id' => $user['id'], ':time' => time())); 
header('Location: /achive?type=2');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

if($user['reid_pobeda'] < 1 or $user['reid_pobeda'] < 3 or $user['reid_pobeda'] < 7 or $user['reid_pobeda'] < 15 or $user['reid_pobeda'] < 25 or $user['reid_pobeda'] < 50 or $user['reid_pobeda'] < 85 or $user['reid_pobeda'] < 150 or $user['reid_pobeda'] < 250 or $user['reid_pobeda'] < 400){
header('Location: /achive?type=2');
exit();		
}
}else{
header('Location: /achive?type=2');
$_SESSION['err'] = 'Достижение выполнено!';
exit();	
}
}
?>